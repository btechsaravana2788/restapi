================================================
TO Execute this file as below:
 Step1: Extract the zip file
 Step2: Put under Xampp/Wampp folder
 Step3: Create database with name restapi
 Step4: Import database
 Step5: Open Postman
 Step6: Add data

HTTP Method	
GET	http://localhost/restapi/rest.php Get all tasks		
GET	http://localhost/restapi/rest.php?id=1	Get a single task by ID	
POST http://localhost/restapi/rest.php	Create a new task	{ "title": "Task title", "description": "Task description", "status": "pending" }
PUT	http://localhost/restapi/rest.php?id=1	Update an existing task	{ "title": "Updated title", "description": "Updated description", "status": "completed" }
DELETE	http://localhost/restapi/rest.php?id=1	Delete a task by ID