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

// Fetch user's recipes from the database
$userRecipes = [];
$recipeCount = 0;

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    
    // SIMPLIFIED QUERY - Let's start with basic recipe data first
    $recipeQuery = "
        SELECT 
            r.recipeID,
            r.recipeName,
            r.difficultID,
            r.userID,
            r.image,
            r.text,
            r.recipeDescription,
            r.date,
            r.cuisineTypeID,
            r.foodTypeID,
            r.dietaryID,
            r.ingredient,
            d.difficultyName,
            c.cuisineType,
            f.foodType,
            dp.dietName
        FROM recipe r
        LEFT JOIN difficultyLev d ON r.difficultID = d.difficultyID
        LEFT JOIN cuisineType c ON r.cuisineTypeID = c.cuisineTypeID
        LEFT JOIN foodType f ON r.foodTypeID = f.foodTypeID
        LEFT JOIN dietPreferences dp ON r.dietaryID = dp.dietID
        WHERE r.userID = ?
        ORDER BY r.date DESC
    ";
    
    $stmt = $conn->prepare($recipeQuery);
    if ($stmt) {
        $stmt->bind_param("i", $userID);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $userRecipes[] = $row;
            }
            $recipeCount = count($userRecipes);
        } else {
            // Fallback: Try simple query without joins
            $fallbackQuery = "SELECT * FROM recipe WHERE userID = ? ORDER BY date DESC";
            $fallbackStmt = $conn->prepare($fallbackQuery);
            if ($fallbackStmt) {
                $fallbackStmt->bind_param("i", $userID);
                $fallbackStmt->execute();
                $fallbackResult = $fallbackStmt->get_result();
                
                while ($row = $fallbackResult->fetch_assoc()) {
                    $userRecipes[] = $row;
                }
                $recipeCount = count($userRecipes);
                $fallbackStmt->close();
            }
        }
        $stmt->close();
    } else {
        // Final fallback: Direct query
        $directQuery = "SELECT * FROM recipe WHERE userID = $userID ORDER BY date DESC";
        $directResult = $conn->query($directQuery);
        if ($directResult) {
            while ($row = $directResult->fetch_assoc()) {
                $userRecipes[] = $row;
            }
            $recipeCount = count($userRecipes);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Recipes - FoodFusion</title>
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
                        <a href="my-recipes.php" class="sidebar-link flex items-center px-6 py-3 text-text-color bg-light-pink active-tab">
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
            <div id="my-recipes-content">
                <div class="bg-lightest rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-primary flex items-center">
                            <i class="fas fa-utensils mr-2"></i> My Recipes
                        </h2>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-text-color">
                            <?php echo $recipeCount; ?> recipe<?php echo $recipeCount != 1 ? 's' : ''; ?> found
                        </p>
                    </div>
                    
                    <div class="space-y-6">
                        <?php if (empty($userRecipes)): ?>
                            <div class="text-center py-12">
                                <div class="text-6xl text-light-pink mb-4">
                                    <i class="fas fa-utensils"></i>
                                </div>
                                <h3 class="text-xl font-medium text-text-color mb-2">No recipes yet</h3>
                                <p class="text-medium-gray mb-6">Start sharing your culinary creations with the community!</p>
                                <a href="upload-recipe.php" class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-primary hover:bg-medium-pink focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                    <i class="fas fa-plus mr-2"></i> Create Your First Recipe
                                </a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($userRecipes as $recipe): ?>
                                <div class="border border-light-pink rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex flex-col md:flex-row gap-4">
                                        <!-- Recipe Image -->
                                        <div class="md:w-1/4">
                                            <?php if (!empty($recipe['image']) && $recipe['image'] != 'uploads/'): ?>
                                                <img src="<?php echo htmlspecialchars($recipe['image']); ?>" 
                                                    alt="<?php echo htmlspecialchars($recipe['recipeName']); ?>" 
                                                    class="w-full h-40 object-cover rounded-lg"
                                                    onerror="this.src='https://via.placeholder.com/300x200?text=Recipe+Image'">
                                            <?php else: ?>
                                                <div class="h-40 bg-gradient-to-r from-primary to-medium-pink rounded-lg flex items-center justify-center text-white text-4xl">
                                                    <i class="fas fa-utensils"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Recipe Details -->
                                        <div class="md:w-3/4">
                                            <div class="flex justify-between items-start">
                                                <h3 class="text-lg font-medium text-text-color">
                                                    <?php echo htmlspecialchars($recipe['recipeName']); ?>
                                                </h3>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-light-pink text-text-color">
                                                    <?php 
                                                    if (!empty($recipe['difficultyName'])) {
                                                        echo htmlspecialchars($recipe['difficultyName']);
                                                    } elseif (!empty($recipe['difficultID'])) {
                                                        echo "Level " . htmlspecialchars($recipe['difficultID']);
                                                    } else {
                                                        echo "Not specified";
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                            
                                            <p class="text-sm text-medium-gray mt-1">
                                                Posted on <?php echo date('F j, Y', strtotime($recipe['date'])); ?>
                                            </p>
                                            
                                            <?php if (!empty($recipe['recipeDescription'])): ?>
                                                <p class="mt-2 text-text-color">
                                                    <?php echo htmlspecialchars(substr($recipe['recipeDescription'], 0, 150)); ?>
                                                    <?php if (strlen($recipe['recipeDescription']) > 150): ?>...<?php endif; ?>
                                                </p>
                                            <?php elseif (!empty($recipe['text'])): ?>
                                                <p class="mt-2 text-text-color">
                                                    <?php echo htmlspecialchars(substr($recipe['text'], 0, 150)); ?>
                                                    <?php if (strlen($recipe['text']) > 150): ?>...<?php endif; ?>
                                                </p>
                                            <?php endif; ?>
                                            
                                            <div class="mt-3 flex justify-between items-center">
                                                <div class="flex space-x-2">
                                                    <?php if (!empty($recipe['cuisineType'])): ?>
                                                        <span class="inline-flex items-center text-sm text-medium-gray">
                                                            <i class="fas fa-globe mr-1"></i> 
                                                            <?php echo htmlspecialchars($recipe['cuisineType']); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <a href="reDetail.php?id=<?php echo $recipe['recipeID']; ?>" 
                                                       class="text-sm text-primary hover:text-medium-pink transition-colors">
                                                        View Recipe <i class="fas fa-arrow-right ml-1"></i>
                                                    </a>
                                                </div>
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
    function confirmDelete(recipeId) {
        if (confirm('Are you sure you want to delete this recipe? This action cannot be undone.')) {
            // Send AJAX request to delete the recipe
            fetch('delete-recipe.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'recipe_id=' + recipeId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to show updated list
                    location.reload();
                } else {
                    alert('Error deleting recipe: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting recipe');
            });
        }
    }

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