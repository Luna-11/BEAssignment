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

// DEBUG: Check the actual table structure
echo "<!-- Debug: Checking users table structure -->";
$result = $conn->query("DESCRIBE users");
if ($result) {
    echo "<!-- Users table columns: -->";
    while ($row = $result->fetch_assoc()) {
        echo "<!-- " . $row['Field'] . " (" . $row['Type'] . ") -->";
    }
} else {
    echo "<!-- Could not describe users table -->";
}

// Check if we can find the user with different ID column names
$possibleIdColumns = ['id', 'userID', 'user_id', 'userId'];
$userFound = false;
$actualIdColumn = '';

foreach ($possibleIdColumns as $column) {
    $checkSql = "SELECT COUNT(*) as count FROM users WHERE $column = ?";
    if ($stmt = $conn->prepare($checkSql)) {
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        if ($row['count'] > 0) {
            $userFound = true;
            $actualIdColumn = $column;
            echo "<!-- Found user using column: $column -->";
            break;
        }
    }
}

if (!$userFound) {
    echo "<!-- Could not find user with any ID column -->";
} else {
    echo "<!-- Using ID column: $actualIdColumn -->";
}

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

// Handle profile image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profileImage'])) {
    $uploadResult = handleProfileImageUpload($_FILES['profileImage'], $userID);
    if ($uploadResult['success']) {
        // Update user profile with new image path - FIXED: using 'id' instead of 'userID'
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
    <title>Account Dashboard - FoodFusion</title>
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
                    /* Ensure smooth transitions */
            .tab-content {
                display: none;
                animation: fadeIn 0.3s ease-in-out;
            }

            .tab-content.active {
                display: block;
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            /* Fix any layout shifting */
            .sidebar-link {
                transition: all 0.2s ease;
            }

            /* Prevent content flash */
            #tab-content {
                min-height: 600px;
            }
        .active-tab {
            background-color: #f9f1e5;
            color: #7b4e48;
            border-left: 4px solid #C89091;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        body {
            background-color: #f9f1e5;
            color: #7b4e48;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .notification-unread {
            background-color: #fef7f7;
            border-left: 4px solid #C89091;
        }
        .notification-read {
            background-color: #fcfaf2;
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
                        <a href="#" class="sidebar-link flex items-center px-6 py-3 text-text-color hover:bg-light-pink transition-colors duration-200" data-tab="profile">
                            <i class="fas fa-user-circle mr-3 text-primary"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center px-6 py-3 text-text-color hover:bg-light-pink transition-colors duration-200" data-tab="posts">
                            <i class="fas fa-utensils mr-3 text-primary"></i>
                            <span>My Recipes</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center px-6 py-3 text-text-color hover:bg-light-pink transition-colors duration-200" data-tab="recipes">
                            <i class="fas fa-bookmark mr-3 text-primary"></i>
                            <span>Saved Recipes</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center px-6 py-3 text-text-color hover:bg-light-pink transition-colors duration-200" data-tab="saved-posts">
                            <i class="fas fa-save mr-3 text-primary"></i>
                            <span>Saved Posts</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center px-6 py-3 text-text-color hover:bg-light-pink transition-colors duration-200" data-tab="liked-posts">
                            <i class="fas fa-heart mr-3 text-primary"></i>
                            <span>Liked Posts</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center px-6 py-3 text-text-color hover:bg-light-pink transition-colors duration-200" data-tab="notifications">
                            <i class="fas fa-bell mr-3 text-primary"></i>
                            <span>Notifications</span>
                            <span class="ml-auto bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
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
            <div id="tab-content">
                <!-- Profile Tab -->

<div id="profile" class="tab-content active">
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
                        <i class="fas fa-utensils mr-1"></i> 12 Recipes
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-light-pink text-text-color">
                        <i class="fas fa-heart mr-1"></i> 45 Likes
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

                <!-- My Recipes Tab -->
                <div id="posts" class="tab-content">
                    <div class="bg-lightest rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-primary flex items-center">
                                <i class="fas fa-utensils mr-2"></i> My Recipes
                            </h2>
                            <a href="upload-recipe.html" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary hover:bg-medium-pink focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                <i class="fas fa-plus mr-2"></i> Add New Recipe
                            </a>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="border border-light-pink rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex flex-col md:flex-row gap-4">
                                    <div class="md:w-1/4">
                                        <div class="h-40 bg-gradient-to-r from-primary to-medium-pink rounded-lg flex items-center justify-center text-white text-4xl">
                                            <i class="fas fa-utensils"></i>
                                        </div>
                                    </div>
                                    <div class="md:w-3/4">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-lg font-medium text-text-color">Spicy Thai Basil Noodles</h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-light-pink text-text-color">
                                                Published
                                            </span>
                                        </div>
                                        <p class="text-sm text-medium-gray mt-1">Posted on September 20, 2025</p>
                                        <p class="mt-2 text-text-color">A flavorful fusion dish combining Thai basil with Italian pasta noodles. Perfect for a quick weeknight dinner with a spicy kick.</p>
                                        <div class="mt-3 flex space-x-2">
                                            <span class="inline-flex items-center text-sm text-medium-gray">
                                                <i class="fas fa-clock mr-1"></i> 25 min
                                            </span>
                                            <span class="inline-flex items-center text-sm text-medium-gray">
                                                <i class="fas fa-utensil-spoon mr-1"></i> Medium
                                            </span>
                                            <span class="inline-flex items-center text-sm text-medium-gray">
                                                <i class="fas fa-heart mr-1"></i> 24 likes
                                            </span>
                                        </div>
                                        <div class="mt-3 flex space-x-2">
                                            <button class="text-sm text-primary hover:text-medium-pink transition-colors">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </button>
                                            <button class="text-sm text-primary hover:text-medium-pink transition-colors">
                                                <i class="fas fa-trash mr-1"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="border border-light-pink rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex flex-col md:flex-row gap-4">
                                    <div class="md:w-1/4">
                                        <div class="h-40 bg-gradient-to-r from-primary to-medium-pink rounded-lg flex items-center justify-center text-white text-4xl">
                                            <i class="fas fa-utensils"></i>
                                        </div>
                                    </div>
                                    <div class="md:w-3/4">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-lg font-medium text-text-color">Mediterranean Breakfast Bowl</h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-light-pink text-text-color">
                                                Draft
                                            </span>
                                        </div>
                                        <p class="text-sm text-medium-gray mt-1">Last edited on September 18, 2025</p>
                                        <p class="mt-2 text-text-color">A healthy breakfast bowl with Mediterranean flavors including feta, olives, and fresh vegetables. Perfect for a nutritious start to your day.</p>
                                        <div class="mt-3 flex space-x-2">
                                            <span class="inline-flex items-center text-sm text-medium-gray">
                                                <i class="fas fa-clock mr-1"></i> 15 min
                                            </span>
                                            <span class="inline-flex items-center text-sm text-medium-gray">
                                                <i class="fas fa-utensil-spoon mr-1"></i> Easy
                                            </span>
                                        </div>
                                        <div class="mt-3 flex space-x-2">
                                            <button class="text-sm text-primary hover:text-medium-pink transition-colors">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </button>
                                            <button class="text-sm text-primary hover:text-medium-pink transition-colors">
                                                <i class="fas fa-trash mr-1"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Saved Recipes Tab -->
                <div id="recipes" class="tab-content">
                    <div class="bg-lightest rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold text-primary mb-6 flex items-center">
                            <i class="fas fa-bookmark mr-2"></i> Saved Recipes
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="border border-light-pink rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                <div class="h-48 bg-gradient-to-r from-primary to-medium-pink flex items-center justify-center text-white text-5xl">
                                    <i class="fas fa-utensils"></i>
                                </div>
                                <div class="p-4">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-lg font-medium text-text-color">Vegetable Stir Fry</h3>
                                        <button class="text-primary hover:text-medium-pink transition-colors">
                                            <i class="fas fa-bookmark"></i>
                                        </button>
                                    </div>
                                    <p class="mt-1 text-sm text-medium-gray">By Chef Maria • 30 min</p>
                                    <p class="mt-2 text-text-color">A quick and healthy vegetable stir fry with a savory sauce that's perfect for busy weeknights.</p>
                                    <div class="mt-3 flex justify-between items-center">
                                        <span class="inline-flex items-center text-sm text-medium-gray">
                                            <i class="fas fa-heart mr-1"></i> 42
                                        </span>
                                        <button class="text-sm text-primary hover:text-medium-pink transition-colors">
                                            View Recipe <i class="fas fa-arrow-right ml-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="border border-light-pink rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                <div class="h-48 bg-gradient-to-r from-primary to-medium-pink flex items-center justify-center text-white text-5xl">
                                    <i class="fas fa-cookie"></i>
                                </div>
                                <div class="p-4">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-lg font-medium text-text-color">Chocolate Chip Cookies</h3>
                                        <button class="text-primary hover:text-medium-pink transition-colors">
                                            <i class="fas fa-bookmark"></i>
                                        </button>
                                    </div>
                                    <p class="mt-1 text-sm text-medium-gray">By Baker John • 45 min</p>
                                    <p class="mt-2 text-text-color">Classic chocolate chip cookies with a soft center and crispy edges. The perfect treat for any occasion.</p>
                                    <div class="mt-3 flex justify-between items-center">
                                        <span class="inline-flex items-center text-sm text-medium-gray">
                                            <i class="fas fa-heart mr-1"></i> 67
                                        </span>
                                        <button class="text-sm text-primary hover:text-medium-pink transition-colors">
                                            View Recipe <i class="fas fa-arrow-right ml-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Saved Posts Tab -->
                <div id="saved-posts" class="tab-content">
                    <div class="bg-lightest rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold text-primary mb-6 flex items-center">
                            <i class="fas fa-save mr-2"></i> Saved Posts
                        </h2>
                        
                        <div class="space-y-6">
                            <div class="border border-light-pink rounded-lg p-4 hover:shadow-md transition-shadow">
                                <h3 class="text-lg font-medium text-text-color">10 Tips for Better Meal Planning</h3>
                                <p class="text-sm text-medium-gray mt-1 flex items-center">
                                    <i class="fas fa-user mr-2"></i> By Nutrition Expert • September 15, 2025
                                </p>
                                <p class="mt-2 text-text-color">Learn how to plan your meals effectively to save time and eat healthier. These practical tips will transform your approach to cooking and nutrition.</p>
                                <div class="mt-3 flex justify-between items-center">
                                    <div class="flex space-x-2">
                                        <span class="inline-flex items-center text-sm text-medium-gray">
                                            <i class="fas fa-heart mr-1"></i> 128
                                        </span>
                                        <span class="inline-flex items-center text-sm text-medium-gray">
                                            <i class="fas fa-comment mr-1"></i> 24
                                        </span>
                                    </div>
                                    <button class="text-sm text-primary hover:text-medium-pink transition-colors">
                                        Read More <i class="fas fa-arrow-right ml-1"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="border border-light-pink rounded-lg p-4 hover:shadow-md transition-shadow">
                                <h3 class="text-lg font-medium text-text-color">The Science of Sourdough</h3>
                                <p class="text-sm text-medium-gray mt-1 flex items-center">
                                    <i class="fas fa-user mr-2"></i> By Bread Master • September 10, 2025
                                </p>
                                <div class="mt-3 flex justify-between items-center">
                                    <div class="flex space-x-2">
                                        <span class="inline-flex items-center text-sm text-medium-gray">
                                            <i class="fas fa-heart mr-1"></i> 89
                                        </span>
                                        <span class="inline-flex items-center text-sm text-medium-gray">
                                            <i class="fas fa-comment mr-1"></i> 17
                                        </span>
                                    </div>
                                    <button class="text-sm text-primary hover:text-medium-pink transition-colors">
                                        Read More <i class="fas fa-arrow-right ml-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liked Posts Tab -->
                <div id="liked-posts" class="tab-content">
                    <div class="bg-lightest rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold text-primary mb-6 flex items-center">
                            <i class="fas fa-heart mr-2"></i> Liked Posts
                        </h2>
                        
                        <div class="space-y-6">
                            <div class="border border-light-pink rounded-lg p-4 hover:shadow-md transition-shadow">
                                <h3 class="text-lg font-medium text-text-color">Seasonal Fall Recipes</h3>
                                <p class="text-sm text-medium-gray mt-1 flex items-center">
                                    <i class="fas fa-user mr-2"></i> By Seasonal Chef • September 22, 2025
                                </p>
                                <p class="mt-2 text-text-color">Delicious recipes using autumn's best produce like pumpkins, apples, and squash. Celebrate the flavors of fall with these comforting dishes.</p>
                                <div class="mt-3 flex justify-between items-center">
                                    <div class="flex space-x-2">
                                        <span class="inline-flex items-center text-sm text-medium-gray">
                                            <i class="fas fa-heart mr-1 text-primary"></i> 156
                                        </span>
                                        <span class="inline-flex items-center text-sm text-medium-gray">
                                            <i class="fas fa-comment mr-1"></i> 32
                                        </span>
                                    </div>
                                    <button class="text-sm text-primary hover:text-medium-pink transition-colors">
                                        Read More <i class="fas fa-arrow-right ml-1"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="border border-light-pink rounded-lg p-4 hover:shadow-md transition-shadow">
                                <h3 class="text-lg font-medium text-text-color">Kitchen Organization Hacks</h3>
                                <p class="text-sm text-medium-gray mt-1 flex items-center">
                                    <i class="fas fa-user mr-2"></i> By Organization Pro • September 18, 2025
                                </p>
                                <p class="mt-2 text-text-color">Simple tips to organize your kitchen for maximum efficiency and enjoyment. Transform your cooking space with these clever organization ideas.</p>
                                <div class="mt-3 flex justify-between items-center">
                                    <div class="flex space-x-2">
                                        <span class="inline-flex items-center text-sm text-medium-gray">
                                            <i class="fas fa-heart mr-1 text-primary"></i> 203
                                        </span>
                                        <span class="inline-flex items-center text-sm text-medium-gray">
                                            <i class="fas fa-comment mr-1"></i> 41
                                        </span>
                                    </div>
                                    <button class="text-sm text-primary hover:text-medium-pink transition-colors">
                                        Read More <i class="fas fa-arrow-right ml-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications Tab -->
                <div id="notifications" class="tab-content">
                    <div class="bg-lightest rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-primary flex items-center">
                                <i class="fas fa-bell mr-2"></i> Notifications
                            </h2>
                            <div class="flex space-x-2">
                                <button class="inline-flex items-center px-3 py-1 border border-primary text-sm font-medium rounded-lg text-primary hover:bg-light-pink transition-colors" id="mark-all-read">
                                    <i class="fas fa-check-double mr-1"></i> Mark All as Read
                                </button>
                                <button class="inline-flex items-center px-3 py-1 border border-primary text-sm font-medium rounded-lg text-primary hover:bg-light-pink transition-colors" id="clear-all">
                                    <i class="fas fa-trash-alt mr-1"></i> Clear All
                                </button>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Unread Notification -->
                            <div class="border border-light-pink rounded-lg p-4 hover:shadow-md transition-shadow notification-unread">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white">
                                            <i class="fas fa-heart"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex justify-between items-start">
                                            <p class="text-text-color font-medium">Someone liked your recipe</p>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary text-white">
                                                New
                                            </span>
                                        </div>
                                        <p class="text-sm text-medium-gray mt-1">Maria Rodriguez liked your "Spicy Thai Basil Noodles" recipe</p>
                                        <p class="text-xs text-medium-gray mt-1">2 hours ago</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Unread Notification -->
                            <div class="border border-light-pink rounded-lg p-4 hover:shadow-md transition-shadow notification-unread">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white">
                                            <i class="fas fa-comment"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex justify-between items-start">
                                            <p class="text-text-color font-medium">New comment on your recipe</p>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary text-white">
                                                New
                                            </span>
                                        </div>
                                        <p class="text-sm text-medium-gray mt-1">Chef John commented: "Great recipe! I added some extra chili for more heat."</p>
                                        <p class="text-xs text-medium-gray mt-1">5 hours ago</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Unread Notification -->
                            <div class="border border-light-pink rounded-lg p-4 hover:shadow-md transition-shadow notification-unread">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white">
                                            <i class="fas fa-user-plus"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex justify-between items-start">
                                            <p class="text-text-color font-medium">New follower</p>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary text-white">
                                                New
                                            </span>
                                        </div>
                                        <p class="text-sm text-medium-gray mt-1">FoodieFan123 started following you</p>
                                        <p class="text-xs text-medium-gray mt-1">Yesterday</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Read Notification -->
                            <div class="border border-light-pink rounded-lg p-4 hover:shadow-md transition-shadow notification-read">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-medium-gray rounded-full flex items-center justify-center text-white">
                                            <i class="fas fa-bookmark"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <p class="text-text-color font-medium">Your recipe was saved</p>
                                        <p class="text-sm text-medium-gray mt-1">CookingEnthusiast saved your "Mediterranean Breakfast Bowl" recipe</p>
                                        <p class="text-xs text-medium-gray mt-1">2 days ago</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Read Notification -->
                            <div class="border border-light-pink rounded-lg p-4 hover:shadow-md transition-shadow notification-read">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-medium-gray rounded-full flex items-center justify-center text-white">
                                            <i class="fas fa-award"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <p class="text-text-color font-medium">Achievement unlocked!</p>
                                        <p class="text-sm text-medium-gray mt-1">You've earned the "Recipe Master" badge for publishing 10 recipes</p>
                                        <p class="text-xs text-medium-gray mt-1">3 days ago</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all sidebar links
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        const tabContents = document.querySelectorAll('.tab-content');
        
        // Function to switch tabs
        function switchTab(targetTab) {
            // Remove active class from all links and contents
            sidebarLinks.forEach(link => {
                link.classList.remove('active-tab');
            });
            
            tabContents.forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Add active class to clicked link
            const activeLink = document.querySelector(`.sidebar-link[data-tab="${targetTab}"]`);
            if (activeLink) {
                activeLink.classList.add('active-tab');
            }
            
            // Show the target tab content
            const targetContent = document.getElementById(targetTab);
            if (targetContent) {
                targetContent.classList.add('active');
            }
        }
        
        // Add click event listeners to sidebar links
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetTab = this.getAttribute('data-tab');
                switchTab(targetTab);
                
                // Update URL without page reload
                history.pushState(null, null, `#${targetTab}`);
            });
        });
        
        // Handle browser back/forward buttons
        window.addEventListener('popstate', function() {
            const hash = window.location.hash.substring(1);
            if (hash) {
                switchTab(hash);
            }
        });
        
        // Check URL hash on page load
        const initialHash = window.location.hash.substring(1);
        if (initialHash && document.getElementById(initialHash)) {
            switchTab(initialHash);
        } else {
            // Set the first tab as active by default
            if (sidebarLinks.length > 0) {
                const firstTab = sidebarLinks[0].getAttribute('data-tab');
                switchTab(firstTab);
            }
        }
        
        // Logout button functionality
        const logoutBtn = document.getElementById('logout-btn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to logout?')) {
                    window.location.href = 'logout.php';
                }
            });
        }

        // Notification functionality
        const markAllReadBtn = document.getElementById('mark-all-read');
        const clearAllBtn = document.getElementById('clear-all');
        
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', function() {
                const unreadNotifications = document.querySelectorAll('.notification-unread');
                unreadNotifications.forEach(notification => {
                    notification.classList.remove('notification-unread');
                    notification.classList.add('notification-read');
                    
                    // Remove the "New" badge
                    const badge = notification.querySelector('.bg-primary');
                    if (badge) {
                        badge.remove();
                    }
                    
                    // Change icon background to gray
                    const iconContainer = notification.querySelector('.bg-primary');
                    if (iconContainer) {
                        iconContainer.classList.remove('bg-primary');
                        iconContainer.classList.add('bg-medium-gray');
                    }
                });
                
                // Update notification count in sidebar
                const notificationCount = document.querySelector('.sidebar-link[data-tab="notifications"] .bg-primary');
                if (notificationCount) {
                    notificationCount.remove();
                }
            });
        }
        
        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to clear all notifications?')) {
                    const notificationsContainer = document.querySelector('#notifications .space-y-4');
                    if (notificationsContainer) {
                        notificationsContainer.innerHTML = '<p class="text-center text-medium-gray py-4">No notifications</p>';
                    }
                    
                    // Update notification count in sidebar
                    const notificationCount = document.querySelector('.sidebar-link[data-tab="notifications"] .bg-primary');
                    if (notificationCount) {
                        notificationCount.remove();
                    }
                }
            });
        }
    });
</script>
</body>
</html>