<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Cookbook - FoodFusion</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="community.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <!-- Community Hero -->
    <section class="community-hero">
        <div class="container">
            <h1>Community Cookbook</h1>
            <p>Share your culinary adventures with fellow food enthusiasts</p>
        </div>
    </section>

    <!-- Community Feed -->
    <section class="community-feed">
        <div class="container">
            <div class="feed-container">
                <div class="create-post-widget">
                    <div class="post-creator">
                        <div class="creator-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <button class="share-recipe-btn" id="shareRecipeBtn">What's cooking today?</button>
                    </div>
                </div>

                <div class="posts-list" id="postsList">
                    <!-- Posts will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </section>
<!-- Share Recipe Modal -->
    <div class="modal" id="shareRecipeModal">
        <div class="modal-content">
            <span class="close" id="closeShareRecipe">&times;</span>
            <h2>Share Your Recipe</h2>
            <form class="recipe-form" id="recipeForm">
                <div class="form-group">
                    <label>Recipe Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" placeholder="Brief description of the recipe" required></textarea>
                </div>
                <div class="form-group">
                    <label>Cuisine Type</label>
                    <select name="cuisine" required>
                        <option value="">Select Cuisine</option>
                        <option value="italian">Italian</option>
                        <option value="chinese">Chinese</option>
                        <option value="mexican">Mexican</option>
                        <option value="indian">Indian</option>
                        <option value="american">American</option>
                        <option value="french">French</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Dietary Preferences</label>
                    <select name="dietary" required>
                        <option value="">Select Dietary Preference</option>
                        <option value="vegetarian">Vegetarian</option>
                        <option value="vegan">Vegan</option>
                        <option value="gluten-free">Gluten-Free</option>
                        <option value="keto">Keto</option>
                        <option value="paleo">Paleo</option>
                        <option value="dairy-free">Dairy-Free</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Difficulty Level</label>
                    <select name="difficulty" required>
                        <option value="">Select Difficulty</option>
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Cooking Time (minutes)</label>
                    <input type="number" name="time" required>
                </div>
                <div class="form-group">
                    <label>Ingredients</label>
                    <textarea name="ingredients" rows="4" placeholder="List ingredients, one per line" required></textarea>
                </div>
                <div class="form-group">
                    <label>Instructions</label>
                    <textarea name="instructions" rows="6" placeholder="Step-by-step cooking instructions" required></textarea>
                </div>
                <button type="submit" class="submit-btn">Share Recipe</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>FoodFusion</h3>
                    <p>Bringing culinary enthusiasts together through the love of cooking.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="#privacy">Privacy Policy</a></li>
                        <li><a href="#terms">Terms of Service</a></li>
                        <li><a href="#cookies">Cookie Policy</a></li>
                    </ul>
                </div>
                
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 FoodFusion. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="community.js"></script>
</body>
</html>