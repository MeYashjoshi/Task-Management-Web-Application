<?php
require_once "db.php";

if (empty($_SESSION['user']['id'])) {
    echo json_encode(["status" => "error", "message" => "Please login"]);
    exit;
}

$userId = $_SESSION['user']['id'];
$id = intval($_POST['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(["status" => "error", "message" => "Invalid task"]);
    exit;
}

$del = $mysqli->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
$del->bind_param("ii", $id, $userId);
$del->execute();

if ($del->affected_rows > 0) {
    echo json_encode(["status" => "success", "message" => "Task deleted successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Task not found"]);
}

exit;
