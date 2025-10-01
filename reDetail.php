<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #C89091;
            --text-color: #7b4e48;
            --lightest-color: #fcfaf2;
            --light_pink: #e9d0cb;
            --medium_pink: #ddb2b1;
            --light_yellow: #f9f1e5;
            --white: #fff;
            --black: #222;
            --light-gray: #bbb;
            --medium-gray: #555;
            --shadow-color: rgba(0,0,0,0.1);
        }
        
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--lightest-color);
            color: var(--text-color);
        }
        .title-font {
            font-family: 'Playfair Display', serif;
        }
        .ingredient-item:hover {
            transform: translateX(5px);
            transition: transform 0.2s ease;
        }
        .instructions-container {
            counter-reset: step-counter;
        }
        .instruction-step {
            counter-increment: step-counter;
        }
        .instruction-step::before {
            content: counter(step-counter);
            background-color: var(--primary-color);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
            flex-shrink: 0;
        }
        .debug-info {
            background: #f8f9fa;
            border-left: 4px solid #C89091;
            padding: 15px;
            margin: 10px 0;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
        }
        .full-height-image {
            height: 100%;
            min-height: 600px;
        }
        @media (max-width: 768px) {
            .full-height-image {
                min-height: 400px;
            }
        }
    </style>
</head>
<body class="bg-[#fcfaf2]">
    <?php
    include('./configMysql.php');
    include('./function.php');
    
    // Start session for saving functionality
    session_start();
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Get recipe ID from URL parameter
    $recipeID = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    // Debug: Show what ID we're looking for
    echo "<!-- Debug: Looking for recipe ID: $recipeID -->";
    
    // Fetch recipe data from database
    $sql = "SELECT r.*, 
                   ft.foodType, 
                   ct.cuisineType, 
                   dl.difficultyName, 
                   dp.dietName
            FROM recipe r 
            LEFT JOIN foodType ft ON r.foodTypeID = ft.foodTypeID
            LEFT JOIN cuisineType ct ON r.cuisineTypeID = ct.cuisineTypeID
            LEFT JOIN difficultyLev dl ON r.difficultID = dl.difficultyID
            LEFT JOIN dietPreferences dp ON r.dietaryID = dp.dietID
            WHERE r.recipeID = ?";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo "<!-- Debug: Prepare failed: " . $conn->error . " -->";
        $recipe = null;
    } else {
        $stmt->bind_param("i", $recipeID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $recipe = $result->fetch_assoc();
            echo "<!-- Debug: Recipe found: " . htmlspecialchars($recipe['recipeName'] ?? 'No name') . " -->";
            
            // Check if recipe is saved
            $isSaved = isset($_SESSION['savedRecipe_' . $recipeID]);
            
            // Handle save/unsave action
            if (isset($_POST['save_action'])) {
                if ($_POST['save_action'] === 'save') {
                    $_SESSION['savedRecipe_' . $recipeID] = true;
                    $isSaved = true;
                } elseif ($_POST['save_action'] === 'unsave') {
                    unset($_SESSION['savedRecipe_' . $recipeID]);
                    $isSaved = false;
                }
            }
        } else {
            $recipe = null;
            echo "<!-- Debug: No recipe found with ID: $recipeID -->";
            
            // Debug: Let's check what recipes exist
            $debug_sql = "SELECT recipeID, recipeName FROM recipe LIMIT 5";
            $debug_result = $conn->query($debug_sql);
            if ($debug_result && $debug_result->num_rows > 0) {
                echo "<!-- Debug: Available recipes: ";
                while ($debug_row = $debug_result->fetch_assoc()) {
                    echo "ID: " . $debug_row['recipeID'] . " - " . $debug_row['recipeName'] . " | ";
                }
                echo " -->";
            }
        }
        $stmt->close();
    }
    
    $conn->close();
    ?>
    
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Debug Info (remove in production) -->
        <?php if (!$recipe): ?>
        <div class="debug-info mb-4">
            <strong>Debug Information:</strong><br>
            Recipe ID from URL: <?php echo $recipeID; ?><br>
            Recipe Found: <?php echo $recipe ? 'Yes' : 'No'; ?><br>
            Check if you're passing the correct ID in the URL: recipe-detail.php?id=1
        </div>
        <?php endif; ?>

        <!-- Header -->
        <header class="text-center mb-10">
            <?php if ($recipe): ?>
                <h1 class="title-font text-4xl md:text-5xl font-bold text-[#7b4e48] mb-4">
                    <?php echo htmlspecialchars($recipe['recipeName'] ?? 'Untitled Recipe'); ?>
                </h1>
            <?php else: ?>
                <h1 class="title-font text-4xl md:text-5xl font-bold text-[#7b4e48] mb-4">Recipe Not Found</h1>
                <p class="text-[#7b4e48] text-lg">The requested recipe could not be found.</p>
            <?php endif; ?>
        </header>

        <?php if ($recipe): ?>
        <!-- Recipe Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="md:flex">
                <!-- Full Height Image Section -->
                <div class="md:w-2/5 p-0">
                    <div class="full-height-image relative bg-[#e9d0cb]">
                        <div class="w-full h-full flex items-center justify-center overflow-hidden">
                            <?php if (!empty($recipe['image'])): ?>
                                <img src="<?php echo htmlspecialchars($recipe['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($recipe['recipeName']); ?>" 
                                     class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-[#ddb2b1]">
                                    <i class="fas fa-bread-slice text-[#C89091] text-8xl"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="absolute bottom-4 right-4 bg-[#C89091] text-white px-4 py-2 rounded-full shadow-lg">
                            <span class="font-bold">
                                <?php echo htmlspecialchars($recipe['cuisineType'] ?? 'Unknown'); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="md:w-3/5 p-8">
                    <!-- Recipe Info -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                        <div class="text-center">
                            <div class="text-[#C89091] mb-1">
                                <i class="fas fa-utensils text-2xl"></i>
                            </div>
                            <div class="font-bold text-[#7b4e48]">
                                <?php echo htmlspecialchars($recipe['foodType'] ?? 'Not specified'); ?>
                            </div>
                            <div class="text-sm text-[#7b4e48]">Food Type</div>
                        </div>
                        <div class="text-center">
                            <div class="text-[#C89091] mb-1">
                                <i class="far fa-clock text-2xl"></i>
                            </div>
                            <div class="font-bold text-[#7b4e48]">
                                <?php echo htmlspecialchars($recipe['difficultyName'] ?? 'Not specified'); ?>
                            </div>
                            <div class="text-sm text-[#7b4e48]">Difficulty Level</div>
                        </div>
                        <div class="text-center">
                            <div class="text-[#C89091] mb-1">
                                <i class="fas fa-globe text-2xl"></i>
                            </div>
                            <div class="font-bold text-[#7b4e48]">
                                <?php echo htmlspecialchars($recipe['cuisineType'] ?? 'Not specified'); ?>
                            </div>
                            <div class="text-sm text-[#7b4e48]">Cuisine Type</div>
                        </div>
                        <div class="text-center">
                            <div class="text-[#C89091] mb-1">
                                <i class="fas fa-leaf text-2xl"></i>
                            </div>
                            <div class="font-bold text-[#7b4e48]">
                                <?php echo htmlspecialchars($recipe['dietName'] ?? 'Not specified'); ?>
                            </div>
                            <div class="text-sm text-[#7b4e48]">Dietary Preferences</div>
                        </div>
                    </div>

                    <!-- Ingredients -->
                    <div class="mb-8">
                        <h2 class="title-font text-2xl font-bold text-[#7b4e48] mb-4 border-b border-[#e9d0cb] pb-2">Ingredients</h2>
                        <ul class="space-y-3">
                            <?php
                            if (!empty($recipe['ingredient'])) {
                                $ingredients = explode("\n", $recipe['ingredient']);
                                foreach ($ingredients as $ingredient) {
                                    if (trim($ingredient)) {
                                        echo '<li class="ingredient-item flex items-start">';
                                        echo '<span class="text-[#C89091] mr-2 mt-1"><i class="fas fa-square text-xs"></i></span>';
                                        echo '<span class="text-[#7b4e48]">' . htmlspecialchars(trim($ingredient)) . '</span>';
                                        echo '</li>';
                                    }
                                }
                            } else {
                                echo '<li class="text-[#C89091] text-center py-4">No ingredients listed</li>';
                            }
                            ?>
                        </ul>
                    </div>

                    <!-- Instructions -->
                    <div class="mb-8">
                        <h2 class="title-font text-2xl font-bold text-[#7b4e48] mb-4 border-b border-[#e9d0cb] pb-2">Instructions</h2>
                        <div class="instructions-container space-y-4">
                            <?php
                            // Use recipeDescription as instructions
                            if (!empty($recipe['recipeDescription'])) {
                                $instruction_lines = explode("\n", $recipe['recipeDescription']);
                                foreach ($instruction_lines as $instruction) {
                                    if (trim($instruction)) {
                                        echo '<div class="instruction-step flex items-start">';
                                        echo '<span class="text-[#7b4e48]">' . htmlspecialchars(trim($instruction)) . '</span>';
                                        echo '</div>';
                                    }
                                }
                            } else {
                                echo '<div class="text-[#C89091] text-center py-4">No instructions available</div>';
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex space-x-4">
                        <button onclick="window.print()" class="flex-1 bg-[#C89091] hover:bg-[#b37f80] text-white py-3 px-4 rounded-lg font-medium transition duration-300 flex items-center justify-center">
                            <i class="fas fa-print mr-2"></i> Print Recipe
                        </button>
                        
                        <form method="POST" class="flex-1">
                            <?php if ($isSaved): ?>
                                <input type="hidden" name="save_action" value="unsave">
                                <button type="submit" class="w-full bg-[#C89091] hover:bg-[#b37f80] text-white py-3 px-4 rounded-lg font-medium transition duration-300 flex items-center justify-center">
                                    <i class="fas fa-heart mr-2"></i> Saved
                                </button>
                            <?php else: ?>
                                <input type="hidden" name="save_action" value="save">
                                <button type="submit" class="w-full border border-[#C89091] text-[#C89091] hover:bg-[#f9f1e5] py-3 px-4 rounded-lg font-medium transition duration-300 flex items-center justify-center">
                                    <i class="far fa-heart mr-2"></i> Save
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="text-center mt-10 text-[#7b4e48] text-sm">
            <p>
                © <?php echo date('Y'); ?> <?php echo htmlspecialchars($recipe['recipeName']); ?> | 
                Made with <i class="fas fa-heart text-[#C89091]"></i>
            </p>
        </footer>
        <?php else: ?>
        <!-- Error Message -->
        <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
            <i class="fas fa-exclamation-triangle text-[#C89091] text-5xl mb-4"></i>
            <h2 class="text-2xl font-bold text-[#7b4e48] mb-4">Recipe Not Found</h2>
            <p class="text-[#7b4e48] mb-6">The recipe you're looking for doesn't exist or has been removed.</p>
            <a href="recipe-collection.php" class="bg-[#C89091] hover:bg-[#b37f80] text-white py-3 px-6 rounded-lg font-medium transition duration-300 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Recipes
            </a>
            
            <!-- Debug Help -->
            <div class="mt-6 p-4 bg-[#f9f1e5] rounded-lg text-left">
                <h3 class="font-bold text-[#7b4e48] mb-2">Troubleshooting:</h3>
                <ul class="text-sm text-[#7b4e48] list-disc list-inside">
                    <li>Make sure you're passing a valid recipe ID in the URL: recipe-detail.php?id=1</li>
                    <li>Check that the recipe exists in your database</li>
                    <li>Verify your database connection settings</li>
                </ul>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>