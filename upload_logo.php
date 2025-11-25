<?php
require_once __DIR__ . '/db.php';

if (empty($_SESSION['user']['id'])) {
    echo json_encode(["status" => "error", "message" => "Please login"]);
    exit;
}

$allowedLogo = ['png','jpg','jpeg'];
$allowedFavicon = ['png','ico'];
$maxSize = 100 * 1024;

$uploadDir = __DIR__ . '/uploads';
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0755, true);
}

$errors = [];
$success = [];

if (!empty($_FILES['logo']['name'])) {
    $file = $_FILES['logo'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedLogo)) {
        $errors[] = "Logo file must be PNG or JPG";
    } elseif ($file['size'] > $maxSize) {
        $errors[] = "Logo size must be under 100KB";
    } else {
        $filename = "logo_" . time() . "." . $ext;

        if (move_uploaded_file($file['tmp_name'], $uploadDir . "/" . $filename)) {
            $update = $mysqli->prepare("UPDATE settings SET logo = ?, updated_at = NOW() WHERE id = 1");
            $update->bind_param("s", $filename);
            $update->execute();
            $success[] = "Logo updated";
        } else {
            $errors[] = "Failed to upload logo";
        }
    }
}


if (!empty($_FILES['favicon']['name'])) {
    $file = $_FILES['favicon'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedFavicon)) {
        $errors[] = "Favicon must be PNG or ICO";
    } elseif ($file['size'] > $maxSize) {
        $errors[] = "Favicon size must be under 100KB";
    } else {
        $filename = "favicon_" . time() . "." . $ext;

        if (move_uploaded_file($file['tmp_name'], $uploadDir . "/" . $filename)) {
            $update = $mysqli->prepare("UPDATE settings SET favicon = ?, updated_at = NOW() WHERE id = 1");
            $update->bind_param("s", $filename);
            $update->execute();
            $success[] = "Favicon updated";
        } else {
            $errors[] = "Failed to upload favicon";
        }
    }
}


if (!empty($errors)) {
    echo json_encode([
        "status" => "error",
        "message" => implode(", ", $errors)
    ]);
    exit;
}

if (empty($success)) {
    echo json_encode([
        "status" => "error",
        "message" => "No file selected"
    ]);
    exit;
}

echo json_encode([
    "status" => "success",
    "message" => implode(" & ", $success)
]);
exit;
?>
