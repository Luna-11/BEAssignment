<?php
session_start();
include('./configMysql.php');
include('./function.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

$userID = $_SESSION['userID'];

// Get user profile data for sidebar
$profileResult = getUserProfileMySQLi($userID, $conn);
if ($profileResult['success']) {
    $user = $profileResult['user'];
} else {
    $message = 'Error loading profile: ' . $profileResult['error'];
    $messageType = 'error';
}

$userRecipeCount = 0;
if (isset($_SESSION['userID'])) {
    $countQuery = "SELECT COUNT(*) as recipe_count FROM recipe WHERE userID = ?";
    $stmt = $conn->prepare($countQuery);
    if ($stmt) {
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $userRecipeCount = $row['recipe_count'];
        $stmt->close();
    }
}

// Fetch user's comments from the database
$userComments = [];
$commentCount = 0;

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    
    // Query to get user's comments with community post information
    $commentsQuery = "
        SELECT 
            c.commentID,
            c.comment,
            c.commentDate,
            c.communityID,
            co.title as postTitle,
            co.content as postContent,
            co.image as postImage,
            co.date as postDate,
            u.first_name,
            u.last_name
        FROM comment c
        INNER JOIN community co ON c.communityID = co.communityID
        LEFT JOIN users u ON co.userID = u.id
        WHERE c.userID = ?
        ORDER BY c.commentDate DESC
    ";
    
    $stmt = $conn->prepare($commentsQuery);
    if ($stmt) {
        $stmt->bind_param("i", $userID);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $userComments[] = $row;
            }
            $commentCount = count($userComments);
        } else {
            // Fallback: Try simple query without joins
            $fallbackQuery = "SELECT * FROM comment WHERE userID = ? ORDER BY commentDate DESC";
            $fallbackStmt = $conn->prepare($fallbackQuery);
            if ($fallbackStmt) {
                $fallbackStmt->bind_param("i", $userID);
                $fallbackStmt->execute();
                $fallbackResult = $fallbackStmt->get_result();
                
                while ($row = $fallbackResult->fetch_assoc()) {
                    $userComments[] = $row;
                }
                $commentCount = count($userComments);
                $fallbackStmt->close();
            }
        }
        $stmt->close();
    } else {
        // Final fallback: Direct query
        $directQuery = "SELECT * FROM comment WHERE userID = $userID ORDER BY commentDate DESC";
        $directResult = $conn->query($directQuery);
        if ($directResult) {
            while ($row = $directResult->fetch_assoc()) {
                $userComments[] = $row;
            }
            $commentCount = count($userComments);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Comments - FoodFusion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#C89091',
                        'text-color': '#7b4e48',
                        'lightest': '#fcfaf2',
                        'light-pink': '#e9d0cb',
                        'medium-pink': '#ddb2b1',
                        'light-yellow': '#f9f1e5',
                        'white': '#fff',
                        'black': '#222',
                        'light-gray': '#bbb',
                        'medium-gray': '#555',
                        'shadow-color': 'rgba(0,0,0,0.1)',
                        'border-color': '#ccc',
                        'button-color': '#333'
                    },
                    fontFamily: {
                        'segoe': ['Segoe UI', 'Tahoma', 'Geneva', 'Verdana', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        .active-tab {
            background-color: #f9f1e5;
            color: #7b4e48;
            border-left: 4px solid #C89091;
        }
        body {
            background-color: #f9f1e5;
            color: #7b4e48;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body class="bg-light-yellow text-text-color font-segoe min-h-screen">
    <!-- Navbar Placeholder -->
    <?php include 'navbar.php'; ?>

    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-lightest h-screen shadow-md fixed">
            <div class="p-6 border-b border-light-pink">
                <div class="flex items-center space-x-3">
                    <img src="<?php echo htmlspecialchars($user['profileImage'] ?? 'https://via.placeholder.com/50'); ?>" 
                        alt="Profile" 
                        class="w-12 h-12 rounded-full border-2 border-medium-pink"
                        onerror="this.src='https://via.placeholder.com/50'">
                    <div>
                        <h2 class="font-bold text-text-color">
                            <?php echo htmlspecialchars(($user['first_name'] ?? 'User') . ' ' . ($user['last_name'] ?? '')); ?>
                        </h2>
                    </div>
                </div>
            </div>

            <nav class="mt-6">
                <div class="px-6 py-2">
                    <h2 class="text-xs font-semibold text-medium-gray uppercase tracking-wide">Main</h2>
                </div>
                <ul>
                    <li>
                        <a href="profile.php" class="sidebar-link flex items-center px-6 py-3 text-text-color hover:bg-light-pink transition-colors duration-200">
                            <i class="fas fa-user-circle mr-3 text-primary"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="my-recipes.php" class="sidebar-link flex items-center px-6 py-3 text-text-color hover:bg-light-pink transition-colors duration-200">
                            <i class="fas fa-utensils mr-3 text-primary"></i>
                            <span>My Recipes</span>
                        </a>
                    </li>
                    <li>
                        <a href="saved-recipes.php" class="sidebar-link flex items-center px-6 py-3 text-text-color hover:bg-light-pink transition-colors duration-200">
                            <i class="fas fa-bookmark mr-3 text-primary"></i>
                            <span>Saved Recipes</span>
                        </a>
                    </li>
                    <li>
                        <a href="my-comments.php" class="sidebar-link flex items-center px-6 py-3 text-text-color bg-light-pink active-tab">
                            <i class="fas fa-heart mr-3 text-primary"></i>
                            <span>Comments</span>
                        </a>
                    </li>
                </ul>
                
                <div class="px-6 py-2 mt-6">
                    <h2 class="text-xs font-semibold text-medium-gray uppercase tracking-wide">Account</h2>
                </div>
                <ul>
                    <li>
                        <a href="#" class="flex items-center px-6 py-3 text-text-color hover:bg-light-pink transition-colors duration-200" id="logout-btn">
                            <i class="fas fa-sign-out-alt mr-3 text-primary"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="ml-64 flex-1 p-8">
            <div id="my-comments-content">
                <div class="bg-lightest rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-primary mb-6 flex items-center">
                        <i class="fas fa-comments mr-2"></i> My Comments
                    </h2>
                    
                    <div class="mb-4">
                        <p class="text-text-color">
                            <?php echo $commentCount; ?> comment<?php echo $commentCount != 1 ? 's' : ''; ?> found
                        </p>
                    </div>
                    
                    <div class="space-y-6">
                        <?php if (empty($userComments)): ?>
                            <div class="text-center py-12">
                                <div class="text-6xl text-light-pink mb-4">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <h3 class="text-xl font-medium text-text-color mb-2">No comments yet</h3>
                                <p class="text-medium-gray mb-6">Start engaging with the community by leaving comments on posts!</p>
                                <a href="community.php" class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-primary hover:bg-medium-pink focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                    <i class="fas fa-users mr-2"></i> Visit Community
                                </a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($userComments as $comment): ?>
                                <div class="border border-light-pink rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex flex-col md:flex-row gap-4">
                                        <!-- Post Image -->
                                        <div class="md:w-1/4">
                                            <?php if (!empty($comment['postImage']) && $comment['postImage'] != 'uploads/post'): ?>
                                                <img src="<?php echo htmlspecialchars($comment['postImage']); ?>" 
                                                    alt="<?php echo htmlspecialchars($comment['postTitle'] ?? 'Community Post'); ?>" 
                                                    class="w-full h-32 object-cover rounded-lg"
                                                    onerror="this.src='https://via.placeholder.com/300x200?text=Community+Post'">
                                            <?php else: ?>
                                                <div class="h-32 bg-gradient-to-r from-primary to-medium-pink rounded-lg flex items-center justify-center text-white text-2xl">
                                                    <i class="fas fa-users"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Comment Details -->
                                        <div class="md:w-3/4">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h3 class="text-lg font-medium text-text-color">
                                                        <?php 
                                                        if (!empty($comment['postTitle'])) {
                                                            echo htmlspecialchars($comment['postTitle']);
                                                        } else {
                                                            echo "Community Post #" . htmlspecialchars($comment['communityID']);
                                                        }
                                                        ?>
                                                    </h3>
                                                    <p class="text-sm text-medium-gray mt-1">
                                                        <?php if (!empty($comment['first_name'])): ?>
                                                            Post by <?php echo htmlspecialchars($comment['first_name'] . ' ' . $comment['last_name']); ?> • 
                                                        <?php endif; ?>
                                                        Commented on <?php echo date('F j, Y \a\t g:i A', strtotime($comment['commentDate'])); ?>
                                                    </p>
                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-light-pink text-text-color">
                                                    Community Post
                                                </span>
                                            </div>
                                            
                                            <!-- Comment Content -->
                                            <div class="mt-3 p-3 bg-white rounded-lg border border-light-pink">
                                                <p class="text-text-color">
                                                    <?php echo htmlspecialchars($comment['comment']); ?>
                                                </p>
                                            </div>
                                            
                                            <div class="mt-3 flex justify-end">
                                                <a href="community.php#post-<?php echo $comment['communityID']; ?>" 
                                                   class="text-sm text-primary hover:text-medium-pink transition-colors">
                                                    View Post <i class="fas fa-arrow-right ml-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Logout button functionality
    document.addEventListener('DOMContentLoaded', function() {
        const logoutBtn = document.getElementById('logout-btn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to logout?')) {
                    window.location.href = 'logout.php';
                }
            });
        }
    });
    </script>
</body>
</html>