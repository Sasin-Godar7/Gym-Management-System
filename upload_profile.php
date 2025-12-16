<?php
session_start();
require 'config.php'; // DB connection

$uploadDir = 'uploads/';

// Create folder if not exists
if(!is_dir($uploadDir)){
    mkdir($uploadDir, 0777, true);
}
$filepath = $uploadDir . $filename;


if(isset($_FILES['profile_pic'])) {

    $file = $_FILES['profile_pic'];
    $filename = time().'_'.$file['name'];
    $filepath = 'uploads/'.$filename;

    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if(in_array($ext, $allowed)) {
        if(move_uploaded_file($file['tmp_name'], $filepath)) {
            $stmt = $conn->prepare("UPDATE users SET profile_pic=? WHERE username=?");
            $stmt->bind_param("ss", $filename, $_SESSION['username']);
            $stmt->execute();
            $_SESSION['profile_pic'] = $filename;
            header("Location: user_dashboard.php"); // refresh dashboard
            exit();
        } else {
            echo "Failed to upload file.";
        }
    } else {
        echo "Invalid file type. Only JPG, PNG, GIF allowed.";
    }
} else {
    echo "No file selected.";
}
?>
