<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodFusion - About Us</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'food-primary': '#C89091',
                        'food-text': '#7b4e48',
                        'food-lightest': '#fcfaf2',
                        'food-light-pink': '#e9d0cb',
                        'food-light-yellow': '#f9f1e5',
                        'food-medium-pink': '#ddb2b1',
                    },

                }
            }
        }
    </script>
</head>
<body class="bg-food-light-yellow text-food-text font-poppins min-h-screen flex flex-col">
    <!-- Navigation -->
      <?php include 'navbar.php'; ?>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 flex-1">
        
        <!-- Why Join FoodFusion Section -->
        <section class="mb-16">
            <div class="text-center mb-10">
                <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-4">Why Join FoodFusion?</h2>
                
                <div class="max-w-4xl mx-auto mb-10">
                    <p class="text-lg mb-4">
                        FoodFusion is more than just a recipe site - it's a thriving community where cooking enthusiasts from around the world connect, share, and grow together. Whether you're a beginner cook or a seasoned chef, you'll find inspiration, friendship, and endless culinary ideas.
                    </p>
                    <p class="text-lg">
                        Our platform brings together people who believe that cooking is not just about nourishment, but about creativity, culture, and connection.
                    </p>
                </div>
                
                <div class="flex flex-col md:flex-row justify-center gap-8 mt-12">
                    <div class="bg-white p-6 rounded-lg shadow-lg border border-food-light-pink text-center max-w-xs transition-transform duration-300 hover:-translate-y-2 hover:shadow-xl">
                        <i class="fas fa-utensils text-4xl text-food-primary mb-4"></i>
                        <h3 class="text-xl font-bold mb-2">Recipe Sharing</h3>
                        <p class="text-gray-600">Share your culinary creations with detailed instructions, photos, and videos</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-lg border border-food-light-pink text-center max-w-xs transition-transform duration-300 hover:-translate-y-2 hover:shadow-xl">
                        <i class="fas fa-users text-4xl text-food-primary mb-4"></i>
                        <h3 class="text-xl font-bold mb-2">Cooking Community</h3>
                        <p class="text-gray-600">Connect with fellow food lovers, exchange tips, and make new friends</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-lg border border-food-light-pink text-center max-w-xs transition-transform duration-300 hover:-translate-y-2 hover:shadow-xl">
                        <i class="fas fa-calendar-alt text-4xl text-food-primary mb-4"></i>
                        <h3 class="text-xl font-bold mb-2">Cooking Events</h3>
                        <p class="text-gray-600">Join virtual and in-person cooking classes and workshops</p>
                    </div>
                </div>
            </div>
        </section>
        
        <div class="h-0.5 bg-gradient-to-r from-food-primary via-food-light-pink to-food-primary my-12 mx-auto w-4/5"></div>
        
        <!-- About Us Section -->
        <section class="mb-16">
            <div class="flex flex-col md:flex-row items-center gap-10">
                <div class="flex-1">
                    <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-6">About FoodFusion</h2>
                    <p class="text-lg text-gray-600 mb-4">
                        FoodFusion began in 2015 as a small blog where friends shared recipes. Today, we're a global community of over 100,000 cooking enthusiasts who believe that food brings people together. Our platform features recipes from home cooks and professional chefs, cooking events, and a supportive community forum.
                    </p>
                    <p class="text-lg text-gray-600 mb-6">
                        Whether you're looking for dinner inspiration, want to improve your skills, or simply enjoy connecting with other food lovers, FoodFusion is your culinary home.
                    </p>
                    <a href="#" class="inline-block bg-food-primary text-white px-6 py-3 rounded-full font-semibold shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:bg-food-medium-pink">
                        LEARN MORE ABOUT US
                    </a>
                </div>
                
                <div class="flex-1 bg-food-light-pink h-80 rounded-lg shadow-lg flex items-center justify-center text-center p-4 text-food-text font-semibold text-xl">
                    FoodFusion Community Since 2015
                </div>
            </div>
        </section>
    </div>
    
    <!-- Footer -->
    <footer class="bg-food-primary text-white py-8 text-center mt-auto">
        <div class="container mx-auto px-4">
            <p class="mb-4">&copy; 2025 FoodFusion. All rights reserved.</p>
            <div class="flex justify-center space-x-6">
                <a href="#" class="hover:text-food-light-pink transition duration-300"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="hover:text-food-light-pink transition duration-300"><i class="fab fa-instagram"></i></a>
                <a href="#" class="hover:text-food-light-pink transition duration-300"><i class="fab fa-pinterest"></i></a>
                <a href="#" class="hover:text-food-light-pink transition duration-300"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </footer>
    
    <script>
        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            const menu = document.getElementById('nav-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>