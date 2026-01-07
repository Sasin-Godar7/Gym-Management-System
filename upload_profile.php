<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image'])) {
    $username = $_SESSION['username'];
    $file = $_FILES['profile_image'];
    
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = "profile_" . $username . "_" . time() . "." . $ext;
    $target = "uploads/profile_pics/" . $filename;

    if (move_uploaded_file($file['tmp_name'], $target)) {
        // Database update
        $sql = "UPDATE users SET profile_pic = '$filename' WHERE username = '$username'";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(['success' => true, 'filename' => $filename]);
        } else {
            echo json_encode(['success' => false, 'message' => 'DB Error']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Upload Failed']);
    }
    exit;
}