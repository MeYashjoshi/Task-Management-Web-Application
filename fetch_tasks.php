<?php
require_once "db.php";

if (empty($_SESSION['user']['id'])) {
    echo json_encode(["data" => []]);
    exit;
}

$userId = $_SESSION['user']['id'];

$status = $_GET['status'] ?? '';
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

$where = " WHERE user_id = ? ";
$params = [$userId];
$types = "i";

if ($status !== "") {
    $where .= " AND status = ? ";
    $params[] = $status;
    $types .= "s";
}

if ($category !== "") {
    $where .= " AND category = ? ";
    $params[] = $category;
    $types .= "s";
}

if ($search !== "") {
    $where .= " AND title LIKE ? ";
    $params[] = "%$search%";
    $types .= "s";
}

$sql = "SELECT id, title, category, status, due_date FROM tasks $where ORDER BY id DESC";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();

$data = [];
while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(["data" => $data]);
exit;
