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

// Check if community table exists, if not create it
$tableCheck = $conn->query("SHOW TABLES LIKE 'community'");
if ($tableCheck->num_rows == 0) {
    // Create the community table
    $createTableSQL = "
        CREATE TABLE community (
            postID INT AUTO_INCREMENT PRIMARY KEY,
            post TEXT NOT NULL,
            postDate DATETIME NOT NULL,
            userID INT NOT NULL,
            media VARCHAR(255) DEFAULT NULL,
            mediaType ENUM('image', 'video') DEFAULT NULL,
            FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE
        )
    ";
    
    if ($conn->query($createTableSQL)) {
        $success = "Community table created successfully!";
    } else {
        $error = "Error creating community table: " . $conn->error;
    }
}

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

// Build the query based on available columns
$selectColumns = "c.*";
$joinClause = "";

if (in_array('username', $userColumnNames)) {
    $selectColumns .= ", u.username";
    $joinClause = "LEFT JOIN users u ON c.userID = u.userID";
} elseif (in_array('name', $userColumnNames)) {
    $selectColumns .= ", u.name as username";
    $joinClause = "LEFT JOIN users u ON c.userID = u.userID";
} elseif (in_array('email', $userColumnNames)) {
    $selectColumns .= ", u.email as username";
    $joinClause = "LEFT JOIN users u ON c.userID = u.userID";
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Cookbook - FoodFusion</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#C89091',
                        'text': '#7b4e48',
                        'lightest': '#fcfaf2',
                        'light-pink': '#e9d0cb',
                        'medium-pink': '#ddb2b1',
                        'light-yellow': '#f9f1e5',
                        'white': '#fff',
                        'black': '#222',
                        'light-gray': '#bbb',
                        'medium-gray': '#555',
                        'border': '#ccc',
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>      
        .error-message {
            background-color: #fee;
            border: 1px solid #fcc;
            color: #c00;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        
        .success-message {
            background-color: #efe;
            border: 1px solid #cfc;
            color: #0c0;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        
        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            border: 2px dashed #ccc;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: #f9f9f9;
        }
        
        .file-upload-label:hover {
            border-color: #C89091;
            background-color: #fcfaf2;
        }
        
        .preview-container img, .preview-container video {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            object-fit: cover;
        }
        
        .preview-container {
            position: relative;
            display: inline-block;
            margin-top: 1rem;
        }
        
        .remove-media-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }
        
        .upload-status {
            margin-top: 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="bg-light-yellow min-h-screen flex flex-col">
    <!-- Navbar -->    
    <?php include('navbar.php'); ?>

    <!-- Add padding to account for fixed navbar -->
    <div>
        <!-- Community Hero -->
        <section class="bg-gradient-to-br from-primary to-medium-pink text-white py-14 px-5 text-center">
            <div class="container mx-auto">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Community Cookbook</h1>
                <p class="text-xl opacity-90 max-w-2xl mx-auto">Share your culinary adventures with fellow food enthusiasts</p>
            </div>
        </section>

        <!-- Community Feed -->
        <section class="py-12 flex-grow">
            <div class="container mx-auto px-4">
                <div class="max-w-2xl mx-auto">
                    <!-- Create Post Widget -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white text-xl">
                                <i class="fas fa-user"></i>
                            </div>
                            <button id="shareRecipeBtn" class="flex-grow bg-light-yellow text-medium-gray text-left px-4 py-3 rounded-full hover:bg-light-pink transition-colors">
                                What's cooking today?
                            </button>
                        </div>
                    </div>

                    <!-- Display Messages -->
                    <?php if (!empty($error)): ?>
                        <div class="error-message mb-4">
                            <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
                            <?php if (strpos($error, 'permission') !== false): ?>
                                <br><small>Please ensure the 'uploads/post' directory exists and has write permissions (chmod 755).</small>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success)): ?>
                        <div class="success-message mb-4"><?php echo htmlspecialchars($success); ?></div>
                    <?php endif; ?>

                    <!-- Posts List -->
                    <div id="postsList">
                        <?php if (!empty($posts)): ?>
                            <?php foreach ($posts as $post): ?>
                                <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
                                    <div class="p-6 pb-0">
                                        <div class="flex items-center space-x-4 mb-4">
                                            <div class="w-11 h-11 bg-primary rounded-full flex items-center justify-center text-white">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-black">
                                                    <?php 
                                                    if (isset($post['username'])) {
                                                        echo htmlspecialchars($post['username']);
                                                    } elseif (isset($post['userID'])) {
                                                        echo "User " . htmlspecialchars($post['userID']);
                                                    } else {
                                                        echo "User";
                                                    }
                                                    ?>
                                                </h4>
                                                <p class="text-sm text-medium-gray">
                                                    <?php echo date('F j, Y g:i A', strtotime($post['postDate'])); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="p-6 pt-0">
                                        <?php
                                        $postContent = $post['post'];
                                        $titleEnd = strpos($postContent, ':');
                                        if ($titleEnd !== false) {
                                            $displayTitle = substr($postContent, 0, $titleEnd);
                                            $displayDescription = substr($postContent, $titleEnd + 1);
                                        } else {
                                            $displayTitle = $postContent;
                                            $displayDescription = '';
                                        }
                                        ?>
                                        
                                        <h3 class="text-xl font-semibold text-black mb-4">
                                            <?php echo htmlspecialchars(trim($displayTitle)); ?>
                                        </h3>
                                        <p class="text-text mb-4">
                                            <?php echo htmlspecialchars(trim($displayDescription)); ?>
                                        </p>
                                        
                                        <?php if (!empty($post['media'])): ?>
                                            <div class="mb-6 relative">
                                                <?php if ($post['mediaType'] == 'image'): ?>
                                                    <img src="uploads/post/<?php echo htmlspecialchars($post['media']); ?>" 
                                                         alt="<?php echo htmlspecialchars($displayTitle); ?>" 
                                                         class="w-full rounded-lg object-cover h-88"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                                    <div class="error-message hidden">Error loading image</div>
                                                <?php elseif ($post['mediaType'] == 'video'): ?>
                                                    <video controls class="w-full rounded-lg object-cover h-64">
                                                        <source src="uploads/post/<?php echo htmlspecialchars($post['media']); ?>" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-sm text-medium-gray mb-4">
                                                No media attached to this post
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="border-t border-border pt-4 flex justify-between items-center">
                                            <div class="flex space-x-4">
                                                <button class="like-btn flex items-center space-x-2 text-medium-gray hover:text-primary transition-colors">
                                                    <i class="far fa-heart"></i>
                                                    <span>Like</span>
                                                </button>
                                                <button class="flex items-center space-x-2 text-medium-gray hover:text-primary transition-colors">
                                                    <i class="far fa-comment"></i>
                                                    <span>Comment</span>
                                                </button>
                                            </div>
                                            <div class="text-sm text-medium-gray">0 likes • 0 comments</div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                                <i class="fas fa-utensils text-4xl text-primary mb-4"></i>
                                <h3 class="text-xl font-semibold text-text mb-2">No posts yet</h3>
                                <p class="text-medium-gray">Be the first to share your culinary creation!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Share Recipe Modal -->
    <div id="shareRecipeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl max-h-[75vh] overflow-y-auto">
            <div class="p-6 border-b border-border">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-text">Share Your Creation</h2>
                    <button id="closeShareRecipe" class="text-light-gray text-2xl hover:text-black">&times;</button>
                </div>
            </div>
            
            <form id="recipeForm" class="p-6 space-y-4" enctype="multipart/form-data" method="POST">
                <div>
                    <label class="block font-semibold text-text mb-2">Post Title *</label>
                    <input type="text" name="title" required 
                           class="w-full p-3 border-2 border-border rounded focus:border-primary outline-none" 
                           placeholder="Give your post a catchy title" 
                           value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>"
                           maxlength="100">
                    <div class="text-sm text-medium-gray mt-1">Max 100 characters</div>
                </div>
                
                <div>
                    <label class="block font-semibold text-text mb-2">Description *</label>
                    <textarea name="description" rows="3" 
                              placeholder="Tell us about your culinary creation..." required
                              class="w-full p-3 border-2 border-border rounded focus:border-primary outline-none"><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
                </div>
                
                <div>
                    <label class="block font-semibold text-text mb-2">Add Media (Image or Video - Optional)</label>
                    <div class="file-upload">
                        <input type="file" id="mediaUpload" name="media" accept="image/*,video/*" class="hidden">
                        <label for="mediaUpload" id="fileUploadLabel" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt text-4xl text-primary mb-2"></i>
                            <span class="font-semibold text-text">Click to upload an image or video</span>
                            <span class="text-sm text-medium-gray mt-1">Supports JPG, PNG, GIF, WebP, MP4, WebM, OGG (Max 10MB)</span>
                        </label>
                    </div>
                    <div id="fileInfo" class="upload-status hidden"></div>
                    <div id="previewContainer" class="preview-container hidden">
                        <!-- Preview will be inserted here -->
                    </div>
                </div>
                
                <div class="pt-4">
                    <button type="submit" class="w-full bg-primary text-white p-4 rounded hover:bg-medium-pink font-semibold text-lg transition-colors">
                        Share Post
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functionality
        const shareRecipeBtn = document.getElementById('shareRecipeBtn');
        const shareRecipeModal = document.getElementById('shareRecipeModal');
        const closeShareRecipe = document.getElementById('closeShareRecipe');
        const mediaUpload = document.getElementById('mediaUpload');
        const fileUploadLabel = document.getElementById('fileUploadLabel');
        const previewContainer = document.getElementById('previewContainer');
        const fileInfo = document.getElementById('fileInfo');

        shareRecipeBtn.addEventListener('click', () => {
            shareRecipeModal.classList.remove('hidden');
            shareRecipeModal.classList.add('flex');
        });
        
        closeShareRecipe.addEventListener('click', () => {
            closeModal();
        });

        // File upload preview with enhanced feedback
        mediaUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            previewContainer.classList.add('hidden');
            previewContainer.innerHTML = '';
            fileInfo.classList.add('hidden');
            fileInfo.innerHTML = '';

            if (file) {
                // Show file information
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                fileInfo.innerHTML = `Selected: ${file.name} (${fileSize} MB)`;
                fileInfo.classList.remove('hidden');

                if (file.size > 10 * 1024 * 1024) {
                    fileInfo.innerHTML = `<span style="color: red;">File too large: ${fileSize} MB (max 10MB)</span>`;
                    mediaUpload.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.innerHTML = '';
                    
                    // Create remove button
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'remove-media-btn';
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                    removeBtn.title = 'Remove file';
                    removeBtn.addEventListener('click', function() {
                        mediaUpload.value = '';
                        previewContainer.classList.add('hidden');
                        previewContainer.innerHTML = '';
                        fileInfo.classList.add('hidden');
                    });
                    
                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Preview';
                        img.className = 'w-full h-64 object-cover';
                        previewContainer.appendChild(img);
                    } else if (file.type.startsWith('video/')) {
                        const video = document.createElement('video');
                        video.src = e.target.result;
                        video.controls = true;
                        video.className = 'w-full h-64 object-cover';
                        previewContainer.appendChild(video);
                    } else {
                        fileInfo.innerHTML = `<span style="color: orange;">Unsupported file type: ${file.type}</span>`;
                        return;
                    }
                    
                    previewContainer.appendChild(removeBtn);
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // Close modal when clicking outside
        shareRecipeModal.addEventListener('click', function(e) {
            if (e.target === shareRecipeModal) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !shareRecipeModal.classList.contains('hidden')) {
                closeModal();
            }
        });

        function closeModal() {
            shareRecipeModal.classList.add('hidden');
            shareRecipeModal.classList.remove('flex');
            document.getElementById('recipeForm').reset();
            previewContainer.classList.add('hidden');
            previewContainer.innerHTML = '';
            fileInfo.classList.add('hidden');
        }

        // Form validation
        document.getElementById('recipeForm').addEventListener('submit', function(e) {
            const title = document.querySelector('input[name="title"]').value.trim();
            const description = document.querySelector('textarea[name="description"]').value.trim();
            
            if (!title || !description) {
                e.preventDefault();
                alert('Please fill in both title and description fields.');
            }
        });

        // Auto-refresh after successful post submission
        <?php if (!empty($success)): ?>
            setTimeout(() => {
                window.location.href = window.location.href.split('?')[0];
            }, 2000);
        <?php endif; ?>
    </script>
</body>
</html>