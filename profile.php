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

// Initialize variables
$message = '';
$messageType = '';
$user = [];

// Get user profile data
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

// Handle profile image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profileImage'])) {
    $uploadResult = handleProfileImageUpload($_FILES['profileImage'], $userID);
    if ($uploadResult['success']) {
        // Update user profile with new image path
        $updateSql = "UPDATE users SET profileImage = ? WHERE id = ?";
        $stmt = $conn->prepare($updateSql);
        if ($stmt) {
            $stmt->bind_param("si", $uploadResult['filePath'], $userID);
            if ($stmt->execute()) {
                $message = 'Profile image updated successfully!';
                $messageType = 'success';
                // Reload user data
                $profileResult = getUserProfileMySQLi($userID, $conn);
                if ($profileResult['success']) {
                    $user = $profileResult['user'];
                }
            } else {
                $message = 'Failed to update profile image in database: ' . $stmt->error;
                $messageType = 'error';
            }
            $stmt->close();
        } else {
            $message = 'Database prepare error: ' . $conn->error;
            $messageType = 'error';
        }
    } else {
        $message = $uploadResult['error'];
        $messageType = 'error';
    }
}

// Handle profile form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $updateData = [
        'first_name' => $_POST['firstName'] ?? '',
        'last_name' => $_POST['lastName'] ?? '',
        'mail' => $_POST['email'] ?? ''
    ];
    
    $updateResult = updateUserProfileMySQLi($userID, $updateData, $conn);
    if ($updateResult['success']) {
        $message = $updateResult['message'];
        $messageType = 'success';
        // Reload user data
        $profileResult = getUserProfileMySQLi($userID, $conn);
        if ($profileResult['success']) {
            $user = $profileResult['user'];
        }
    } else {
        $message = $updateResult['error'];
        $messageType = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - FoodFusion</title>
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
                        <a href="profile.php" class="sidebar-link flex items-center px-6 py-3 text-text-color bg-light-pink active-tab">
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
                        <a href="my-comments.php" class="sidebar-link flex items-center px-6 py-3 text-text-color hover:bg-light-pink transition-colors duration-200">
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
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-text-color mb-6 flex items-center">
                    <i class="fas fa-user-circle mr-2 text-primary"></i> My Profile
                </h2>
                
                <?php if (!empty($message)): ?>
                    <div class="mb-4 p-4 border rounded <?php echo $messageType === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Profile Picture + Info -->
                    <div class="md:w-1/3 flex flex-col items-center">
                        <div class="relative mb-4">
                            <img src="<?php echo htmlspecialchars($user['profileImage'] ?? 'https://via.placeholder.com/200'); ?>" 
                                alt="Profile Picture" 
                                class="w-48 h-48 rounded-full object-cover border-4 border-primary shadow-lg"
                                onerror="this.src='https://via.placeholder.com/200'">
                            
                            <!-- Profile Image Upload Form -->
                            <form method="POST" enctype="multipart/form-data" class="absolute bottom-2 right-2">
                                <input type="file" id="profileImageInput" name="profileImage" accept="image/*" 
                                    class="hidden" onchange="this.form.submit()">
                                <button type="button" onclick="document.getElementById('profileImageInput').click()" 
                                        class="bg-primary text-white p-2 rounded-full hover:bg-medium-pink transition-colors">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </form>
                        </div>
                        
                        <h3 class="text-xl font-bold text-text-color">
                            <?php echo htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')); ?>
                        </h3>
                                                
                    <div class="mt-4 flex space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-light-pink text-text-color">
                            <i class="fas fa-utensils mr-1"></i> 
                            <?php echo $userRecipeCount; ?> Recipe<?php echo $userRecipeCount != 1 ? 's' : ''; ?>
                        </span>
                    </div>
                    </div>
                    
                    <!-- Profile Form -->
                    <div class="md:w-2/3">
                        <form method="POST" class="space-y-4">
                            <input type="hidden" name="update_profile" value="1">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="firstName" class="block text-sm font-medium text-text-color">First Name</label>
                                    <input type="text" id="firstName" name="firstName" 
                                        value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>"
                                        class="mt-1 block w-full px-3 py-2 border border-light-pink rounded-lg bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-light-pink"
                                        required>
                                </div>
                                <div>
                                    <label for="lastName" class="block text-sm font-medium text-text-color">Last Name</label>
                                    <input type="text" id="lastName" name="lastName" 
                                        value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>"
                                        class="mt-1 block w-full px-3 py-2 border border-light-pink rounded-lg bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-light-pink"
                                        required>
                                </div>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-text-color">Email Address</label>
                                <input type="email" id="email" name="email" 
                                    value="<?php echo htmlspecialchars($user['mail'] ?? ''); ?>"
                                    class="mt-1 block w-full px-3 py-2 border border-light-pink rounded-lg bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-light-pink"
                                    required>
                            </div>
                            
                            <div class="pt-4">
                                <button type="submit" 
                                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary hover:bg-medium-pink focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                    <i class="fas fa-save mr-2"></i> Update Profile
                                </button>
                            </div>
                        </form>
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