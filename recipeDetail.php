<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodFusion - Recipe Detail</title>
    <link rel="stylesheet" href="recipeDetail.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <?php include 'navbar.php'; ?>

    <!-- Recipe Detail Content -->
    <main class="recipe-detail" id="recipe-detail">
        <!-- Content will be populated by JavaScript -->
    </main>

    <!-- Comments Section -->
    <section class="comments-section">
        <div class="container">
            <h3>Comments</h3>
            <div class="comment-form">
                <textarea placeholder="Share your thoughts about this recipe..." id="commentText"></textarea>
                <button class="comment-btn" id="submitComment">Post Comment</button>
            </div>
            <div class="comments-list" id="commentsList">
                <!-- Comments will be populated by JavaScript -->
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>FoodFusion</h3>
                    <p>Bringing food lovers together through culinary creativity.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <a href="about.html">About Us</a>
                    <a href="recipes.html">Recipes</a>
                    <a href="community.html">Community</a>
                    <a href="contact.html">Contact</a>
                </div>
                <div class="footer-section">
                    <h3>Legal</h3>
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="recipeDetail.js"></script>
</body>
</html>