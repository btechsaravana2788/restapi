<?php
include 'db.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $result = $conn->query("SELECT * FROM tasks WHERE id=$id");
            $data = $result->fetch_assoc();
            echo json_encode($data);
        } else {
            $result = $conn->query("SELECT * FROM tasks");
            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            echo json_encode($users);
        }
        break;

    case 'POST':
        $title = $input['title'];
        $description = $input['description'];
        $status = $input['status'];
        // /echo "INSERT INTO tasks (title, description, status) VALUES ('$title', '$description', $status)";
        $conn->query("INSERT INTO tasks (title, description, status) VALUES ('$title', '$description', '$status')");
        echo json_encode(["message" => "Task added successfully"]);
        break;

    case 'PUT':
        $id = $_GET['id'];
        $title = $input['title'];
        $description = $input['description'];
        $status = $input['status'];
        $conn->query("UPDATE tasks SET title='$title',
                     description='$description', status='$status' WHERE id=$id");
        echo json_encode(["message" => "Taks updated successfully"]);
        break;

    case 'DELETE':
        $id = $_GET['id'];
        $conn->query("DELETE FROM tasks WHERE id=$id");
        echo json_encode(["message" => "Task deleted successfully"]);
        break;

    default:
        echo json_encode(["message" => "Invalid request method"]);
        break;
}

$conn->close();
?>
