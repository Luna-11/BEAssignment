<?php
session_start();
include('./configMysql.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];
$error = '';
$success = '';
$title = '';
$description = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    
    // Validate input
    if (empty($title) || empty($description)) {
        $error = "Error: Title and description are required.";
    } elseif (strlen($title) > 100) {
        $error = "Error: Title must be less than 100 characters.";
    } else {
        // Combine title and description for the post field
        $post = $title . ': ' . $description;
        $date = date("Y-m-d H:i:s");

        $file = $_FILES['media'] ?? null;
        $targetDir = "uploads/post/";
        
        // Create directory if it doesn't exist with proper permissions
        if (!file_exists($targetDir)) {
            if (!mkdir($targetDir, 0755, true)) {
                $error = "Error: Could not create upload directory. Please check folder permissions.";
            }
        }

        // Check if directory is writable
        if (empty($error) && file_exists($targetDir) && !is_writable($targetDir)) {
            $error = "Error: Upload directory is not writable. Please check permissions (chmod 755).";
        }

        $mediaName = null;
        $mediaType = null;

        // Allowed types
        $allowedImageTypes = ['image/jpeg','image/jpg','image/png','image/gif','image/webp'];
        $allowedVideoTypes = ['video/mp4','video/webm','video/ogg'];
        $maxImageSize = 4 * 1024 * 1024;  // 4MB
        $maxVideoSize = 10 * 1024 * 1024; // 10MB

        // Handle file upload
        if (empty($error) && $file && $file['error'] == UPLOAD_ERR_OK) {
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            // Verify the uploaded file
            if (!is_uploaded_file($file['tmp_name'])) {
                $error = "Error: Invalid file upload attempt.";
            } else {
                $mimeType = mime_content_type($file['tmp_name']);

                if (in_array($mimeType, $allowedImageTypes)) {
                    if ($file['size'] > $maxImageSize) {
                        $error = "Error: Image too large (max 4MB).";
                    } else {
                        $mediaType = "image";
                    }
                } elseif (in_array($mimeType, $allowedVideoTypes)) {
                    if ($file['size'] > $maxVideoSize) {
                        $error = "Error: Video too large (max 10MB).";
                    } else {
                        $mediaType = "video";
                    }
                } else {
                    $error = "Error: Unsupported file type. Please upload images (JPEG, PNG, GIF, WebP) or videos (MP4, WebM, OGG).";
                }

                if (empty($error)) {
                    $mediaName = uniqid() . '.' . $extension;
                    $mediaPath = $targetDir . $mediaName;
                    
                    if (move_uploaded_file($file['tmp_name'], $mediaPath)) {
                        // Double-check that file was created
                        if (!file_exists($mediaPath)) {
                            $error = "Error: File was not saved properly. Please try again.";
                            $mediaName = null;
                            $mediaType = null;
                        } else {
                            // Verify file size after upload
                            if (filesize($mediaPath) == 0) {
                                $error = "Error: Uploaded file is empty or corrupted.";
                                unlink($mediaPath); // Remove empty file
                                $mediaName = null;
                                $mediaType = null;
                            }
                        }
                    } else {
                        $error = "Error: Failed to save file. Please check directory permissions.";
                        $mediaName = null;
                        $mediaType = null;
                    }
                }
            }
        } elseif ($file && $file['error'] != UPLOAD_ERR_NO_FILE) {
            // Handle upload errors
            $uploadErrors = [
                UPLOAD_ERR_INI_SIZE => 'File too large (server limit exceeded)',
                UPLOAD_ERR_FORM_SIZE => 'File too large (form limit exceeded)',
                UPLOAD_ERR_PARTIAL => 'File upload was incomplete',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
            ];
            $errorCode = $file['error'];
            $error = "Upload error: " . ($uploadErrors[$errorCode] ?? "Unknown error (Code: $errorCode)");
        }

        // Insert into database if no errors
        if (empty($error)) {
            // Ensure only valid enum values for ENUM field
            if ($mediaType && !in_array($mediaType, ['image', 'video'])) {
                $mediaType = null;
            }

            $stmt = $conn->prepare("
                INSERT INTO community (post, postDate, userID, media, mediaType) 
                VALUES (?, ?, ?, ?, ?)
            ");

            if (!$stmt) {
                $error = "Database Error: " . $conn->error;
            } else {
                // Handle NULL values properly
                $bindMediaName = !empty($mediaName) ? $mediaName : null;
                $bindMediaType = !empty($mediaType) ? $mediaType : null;

                $stmt->bind_param("ssiss", 
                    $post, 
                    $date, 
                    $userID, 
                    $bindMediaName, 
                    $bindMediaType
                );

                if ($stmt->execute()) {
                    $success = "Post shared successfully!" . ($bindMediaName ? " File: $bindMediaName" : "");
                    // Clear form fields
                    $title = "";
                    $description = "";
                } else {
                    $error = "Database Error: " . $stmt->error;
                    if (!empty($mediaName)) {
                        unlink($targetDir . $mediaName);
                    }
                }
                $stmt->close();
            }
        }
    }
}

// Check what columns exist in the users table
$userColumns = $conn->query("SHOW COLUMNS FROM users");
$userColumnNames = [];
if ($userColumns) {
    while ($col = $userColumns->fetch_assoc()) {
        $userColumnNames[] = $col['Field'];
    }
}

// Build the query based on available columns - UPDATED FOR YOUR TABLE STRUCTURE
$selectColumns = "c.*";
$joinClause = "";

// Use first_name since that's what you have in your table
if (in_array('first_name', $userColumnNames)) {
    $selectColumns .= ", u.first_name";
    $joinClause = "LEFT JOIN users u ON c.userID = u.id"; // Join on u.id since that's your primary key
} elseif (in_array('username', $userColumnNames)) {
    $selectColumns .= ", u.username";
    $joinClause = "LEFT JOIN users u ON c.userID = u.id";
} elseif (in_array('name', $userColumnNames)) {
    $selectColumns .= ", u.name as username";
    $joinClause = "LEFT JOIN users u ON c.userID = u.id";
} elseif (in_array('mail', $userColumnNames)) {
    $selectColumns .= ", u.mail as username";
    $joinClause = "LEFT JOIN users u ON c.userID = u.id";
} else {
    // If no user identifying column exists, just get the userID
    $selectColumns .= ", c.userID";
}

// Fetch existing posts from database to display
$posts = [];
$query = "SELECT $selectColumns FROM community c $joinClause ORDER BY c.postDate DESC";
$postsQuery = $conn->query($query);

if ($postsQuery) {
    while ($row = $postsQuery->fetch_assoc()) {
        $posts[] = $row;
    }
}
?>