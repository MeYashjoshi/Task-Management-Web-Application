<?php
require_once __DIR__ . '/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = "Enter a valid email";
    if ($password === '')
        $errors[] = "Enter password";

    if (!empty($errors)) {
        echo json_encode(["status" => "error", "message" => $errors[0]]);
        exit;
    }

    $stmt = $mysqli->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($user = $res->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {

            $_SESSION['user'] = [
                'id' => (int) $user['id'],
                'name' => $user['name'],
                'email' => $email,
                'role' => $user['role']
            ];
            echo json_encode(["status" => "success", "message" => "Login successful"]);
            exit;
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No user found with this email"]);
        exit;
    }
}

echo json_encode(["status" => "error", "message" => "Invalid request"]);
exit;
