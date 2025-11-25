<?php
require_once __DIR__ . '/db.php';


if (empty($_SESSION['user']['id'])) {
    echo json_encode(["status"=>"error","message"=>"Please login first"]);
    exit;
}
$userId = (int)$_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status"=>"error","message"=>"Invalid request"]);
    exit;
}

$id = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : 0;
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$category = in_array($_POST['category'] ?? '', ['Work','Personal']) ? $_POST['category'] : 'Work';
$status = in_array($_POST['status'] ?? '', ['To Do','In Progress','Completed']) ? $_POST['status'] : 'To Do';
$due_date = $_POST['due_date'] ?? null;
if ($due_date === '') $due_date = null;

$errors = [];
if ($title === '') $errors[] = "Title is required";
if ($due_date && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $due_date)) $errors[] = "Due date must be YYYY-MM-DD or empty";

if (!empty($errors)) {
    echo json_encode(["status"=>"error","message"=>$errors[0]]);
    exit;
}

if ($id > 0) {
    // Ensure task 
    $check = $mysqli->prepare("SELECT id FROM tasks WHERE id = ? AND user_id = ?");
    $check->bind_param("ii", $id, $userId);
    $check->execute();
    $check->store_result();
    if ($check->num_rows === 0) {
        echo json_encode(["status"=>"error","message"=>"Task not found or not allowed"]);
        exit;
    }

    // Check duplicate Task
    $dup = $mysqli->prepare("SELECT id FROM tasks WHERE user_id = ? AND title = ? AND id <> ?");
    $dup->bind_param("isi", $userId, $title, $id);
    $dup->execute();
    $dup->store_result();
    if ($dup->num_rows > 0) {
        echo json_encode(["status"=>"error","message"=>"You already have a task with same title"]);
        exit;
    }

    // Update Task
    $upd = $mysqli->prepare("UPDATE tasks SET title = ?, description = ?, category = ?, status = ?, due_date = ? WHERE id = ? AND user_id = ?");
    $upd->bind_param("ssssiii", $title, $description, $category, $status, $due_date, $id, $userId);
  
    $ok = $upd->execute();
    if ($ok) {
        echo json_encode(["status"=>"success","message"=>"Task updated"]);
        exit;
    } else {
        echo json_encode(["status"=>"error","message"=>"Could not update task"]);
        exit;
    }
} 



else {


    
    // Add Task
    $dup = $mysqli->prepare("SELECT id FROM tasks WHERE user_id = ? AND title = ?");
    $dup->bind_param("is", $userId, $title);
    $dup->execute();
    $dup->store_result();
    if ($dup->num_rows > 0) {
        echo json_encode(["status"=>"error","message"=>"You already have a task with same title"]);
        exit;
    }

    $ins = $mysqli->prepare("INSERT INTO tasks (user_id, title, description, category, status, due_date) VALUES (?, ?, ?, ?, ?, ?)");
    $ins->bind_param("isssss", $userId, $title, $description, $category, $status, $due_date);
    if ($ins->execute()) {
        echo json_encode(["status"=>"success","message"=>"Task added"]);
        exit;
    } else {
        echo json_encode(["status"=>"error","message"=>"Could not save task"]);
        exit;
    }
}
