<?php
include('db.php');
// Get HTTP method and path
$method = $_SERVER['REQUEST_METHOD'];
$path = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
//print_r($path);

// Simple Router
if ($path[0] !== 'tasks') {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
    exit;
}

// Helper function to get request body as JSON
function getRequestBody() {
    return json_decode(file_get_contents('php://input'), true);
}

// GET /tasks
if ($method === 'GET' && count($path) === 1) {
    $stmt = $pdo->query('SELECT * FROM tasks');
    $tasks = $stmt->fetchAll();
    echo json_encode($tasks);
    exit;
}

// GET /tasks/{id}
if ($method === 'GET' && count($path) === 2 && is_numeric($path[1])) {
    $id = (int)$path[1];
    $stmt = $pdo->prepare('SELECT * FROM tasks WHERE id = ?');
    $stmt->execute([$id]);
    $task = $stmt->fetch();
    if ($task) {
        echo json_encode($task);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Task not found']);
    }
    exit;
}

// POST /tasks
if ($method === 'POST' && count($path) === 1) {
    $data = getRequestBody();
    if (!isset($data['title']) || !isset($data['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Title and Description are required']);
        exit;
    }
    $stmt = $pdo->prepare('INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)');
    $stmt->execute([
        $data['title'],
        $data['description'],
        'pending'
    ]);
    $id = $pdo->lastInsertId();
    echo json_encode(['message' => 'Task created', 'id' => $id]);
    exit;
}

// PUT /tasks/{id}
if ($method === 'PUT' && count($path) === 2 && is_numeric($path[1])) {
    $id = (int)$path[1];
    $data = getRequestBody();
    $fields = [];
    $params = [];

    if (isset($data['title'])) {
        $fields[] = 'title = ?';
        $params[] = $data['title'];
    }
    if (isset($data['description'])) {
        $fields[] = 'description = ?';
        $params[] = $data['description'];
    }
    if (isset($data['status'])) {
        $fields[] = 'status = ?';
        $params[] = $data['status'];
    }
    if (empty($fields)) {
        http_response_code(400);
        echo json_encode(['error' => 'No fields to update']);
        exit;
    }

    $params[] = $id;
    $sql = 'UPDATE tasks SET ' . implode(', ', $fields) . ' WHERE id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode(['message' => 'Task updated']);
    exit;
}

// If none matched
http_response_code(405);
echo json_encode(['error' => 'Method Not Allowed']);
?>