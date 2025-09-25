<?php
session_start();
include('../php/database.php');

// Check if user is logged in
if (!isset($_SESSION['customerID'])) {
    header("Location: login.php");
    exit();
}

$customerID = $_SESSION['customerID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $post = htmlspecialchars($title . ': ' . $description, ENT_QUOTES); // Combine title + description
    $date = date("Y-m-d H:i:s");

    $file = $_FILES['media'] ?? null;

    $targetDir = "../uploads/post/";
    if (!file_exists($targetDir)) mkdir($targetDir, 0755, true);

    $mediaName = null;
    $mediaType = null;

    // Allowed types
    $allowedImageTypes = ['image/jpeg','image/jpg','image/png','image/gif','image/webp'];
    $allowedVideoTypes = ['video/mp4','video/webm','video/ogg'];
    $maxImageSize = 4 * 1024 * 1024;  // 4MB
    $maxVideoSize = 10 * 1024 * 1024; // 10MB

    if ($file && $file['error'] == UPLOAD_ERR_OK) {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $mimeType = mime_content_type($file['tmp_name']);

        if (in_array($mimeType, $allowedImageTypes)) {
            if ($file['size'] > $maxImageSize) {
                die("Error: Image too large (max 4MB).");
            }
            $mediaType = "image";
        } elseif (in_array($mimeType, $allowedVideoTypes)) {
            if ($file['size'] > $maxVideoSize) {
                die("Error: Video too large (max 10MB).");
            }
            $mediaType = "video";
        } else {
            die("Error: Unsupported file type.");
        }

        $mediaName = uniqid() . '.' . $extension;
        $mediaPath = $targetDir . $mediaName;
        if (!move_uploaded_file($file['tmp_name'], $mediaPath)) {
            die("Error: Failed to upload file.");
        }
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO communitypost (post, postDate, userID, media, mediaType) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $post, $date, $customerID, $mediaName, $mediaType);

    if ($stmt->execute()) {
        // Redirect back to community page
        header("Location: community.php");
        exit();
    } else {
        die("Database Error: " . $stmt->error);
    }
}
?>
