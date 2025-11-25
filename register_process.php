<?php

require_once __DIR__ . '/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($name === '')
        $errors[] = "Name is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = "Valid email is required";
    if (strlen($password) < 6)
        $errors[] = "Password must be at least 6 characters";
    if ($password !== $confirm)
        $errors[] = "Passwords do not match";

    if (empty($errors)) {

        $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo json_encode([
                "status" => "error",
                "message" => "Email already registered"
            ]);
            exit;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $ins = $mysqli->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $ins->bind_param("sss", $name, $email, $hash);

        if ($ins->execute()) {
            echo json_encode([
                "status" => "success",
                "message" => "Registration successful"
            ]);
            exit;
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Something went wrong, please try again"
            ]);
            exit;
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => $errors[0]
        ]);
        exit;
    }
}

echo json_encode([
    "status" => "error",
    "message" => "Invalid request"
]);
exit;
