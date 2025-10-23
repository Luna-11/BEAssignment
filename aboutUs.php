<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - FoodFusion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --food-primary: #C89091;
            --food-text: #7b4e48;
            --food-lightest: #fcfaf2;
            --food-light-pink: #e9d0cb;
            --food-light-yellow: #f9f1e5;
            --food-medium-pink: #ddb2b1;
        }
        
        body {
            color: var(--food-text);
            background-color: var(--food-lightest);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .food-primary-bg {
            background-color: var(--food-primary);
        }
        
        .food-light-pink-bg {
            background-color: var(--food-light-pink);
        }
        
        .food-light-yellow-bg {
            background-color: var(--food-light-yellow);
        }
        
        .food-medium-pink-bg {
            background-color: var(--food-medium-pink);
        }
        
        .food-text {
            color: var(--food-text);
        }
        
        .food-primary-text {
            color: var(--food-primary);
        }
        
        /* Slideshow Styles */
        .slideshow-container {
            position: relative;
            max-width: 100%;
            margin: auto;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .mySlides {
            display: none;
            position: relative;
        }
        
        .mySlides img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        
        .slide-text {
            position: absolute;
            bottom: 0;
            background: rgba(123, 78, 72, 0.85);
            color: white;
            width: 100%;
            padding: 15px;
            text-align: center;
        }
        
        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            padding: 16px;
            margin-top: -22px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
            user-select: none;
            background-color: rgba(0,0,0,0.3);
        }
        
        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }
        
        .prev:hover, .next:hover {
            background-color: rgba(0,0,0,0.8);
        }
        
        .dot-container {
            text-align: center;
            padding: 20px;
        }
        
        .dot {
            cursor: pointer;
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: var(--food-light-pink);
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.6s ease;
        }
        
        .active, .dot:hover {
            background-color: var(--food-primary);
        }
        
        .fade {
            animation-name: fade;
            animation-duration: 1.5s;
        }
        
        @keyframes fade {
            from {opacity: .4} 
            to {opacity: 1}
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <!-- Navigation Bar -->
  <?php include 'navbar.php'; ?>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8 flex-grow">
        <!-- Page Header -->
        <section class="text-center mb-12">
            <h1 class="text-4xl font-bold food-text mb-4">About FoodFusion</h1>
            <p class="text-xl max-w-3xl mx-auto">Discover the story behind our passion for bringing food enthusiasts together through shared culinary experiences.</p>
        </section>

        <!-- Our Mission Section -->
        <section class="food-light-yellow-bg rounded-lg p-8 mb-12">
            <h2 class="text-3xl font-bold food-text mb-6 text-center">Our Mission</h2>
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <p class="text-lg mb-4">At FoodFusion, we believe that cooking is more than just preparing meals—it's a creative expression that brings people together. Our mission is to empower home cooks of all skill levels to explore diverse cuisines, share their culinary creations, and connect with a global community of food lovers.</p>
                    <p class="text-lg">We're dedicated to making cooking accessible, enjoyable, and educational for everyone, from beginners taking their first steps in the kitchen to seasoned chefs looking for new inspiration.</p>
                </div>
                <div class="flex justify-center">
                    <div class="food-medium-pink-bg rounded-full w-64 h-64 flex items-center justify-center">
                        <span class="text-white text-2xl font-bold text-center p-4">Bringing Food<br>Enthusiasts<br>Together</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Food Slideshow Section -->
        <section class="mb-12">
            <h2 class="text-3xl font-bold food-text mb-8 text-center">Culinary Inspiration</h2>
            <div class="slideshow-container">
                <!-- Slide 1 -->
                <div class="mySlides fade">
                    <img src="BEpics/a1.jpg" alt="Delicious Pasta Dish">
                    <div class="slide-text">
                        <h3 class="text-xl font-bold">Global Cuisine Collection</h3>
                        <p>Explore recipes from around the world in our extensive collection</p>
                    </div>
                </div>
                
                <!-- Slide 2 -->
                <div class="mySlides fade">
                    <img src="BEpics/a2.jpg" alt="Fresh Pizza">
                    <div class="slide-text">
                        <h3 class="text-xl font-bold">Community Recipes</h3>
                        <p>Discover creations shared by our vibrant community of home cooks</p>
                    </div>
                </div>
                
                <!-- Slide 3 -->
                <div class="mySlides fade">
                    <img src="BEpics/a3.jpg" alt="Healthy Salad">
                    <div class="slide-text">
                        <h3 class="text-xl font-bold">Healthy Options</h3>
                        <p>Find nutritious meals for every dietary preference and lifestyle</p>
                    </div>
                </div>
                
                <!-- Slide 4 -->
                <div class="mySlides fade">
                    <img src="BEpics/a4.jpg" alt="Dessert">
                    <div class="slide-text">
                        <h3 class="text-xl font-bold">Sweet Treats</h3>
                        <p>Indulge in delightful desserts and baked goods from our community</p>
                    </div>
                </div>
                
                <!-- Navigation arrows -->
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
            </div>
            
            <!-- Dots indicator -->
            <div class="dot-container">
                <span class="dot" onclick="currentSlide(1)"></span>
                <span class="dot" onclick="currentSlide(2)"></span>
                <span class="dot" onclick="currentSlide(3)"></span>
                <span class="dot" onclick="currentSlide(4)"></span>
            </div>
        </section>

        <!-- Our Values Section -->
        <section class="mb-12">
            <h2 class="text-3xl font-bold food-text mb-8 text-center">Our Values</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="food-light-pink-bg rounded-lg p-6 text-center">
                    <div class="food-primary-text text-5xl mb-4">👨‍🍳</div>
                    <h3 class="text-xl font-bold mb-3">Culinary Creativity</h3>
                    <p>We celebrate innovation in the kitchen and encourage our community to experiment with flavors, techniques, and ingredients from around the world.</p>
                </div>
                <div class="food-light-pink-bg rounded-lg p-6 text-center">
                    <div class="food-primary-text text-5xl mb-4">🌍</div>
                    <h3 class="text-xl font-bold mb-3">Cultural Diversity</h3>
                    <p>Food is a universal language that connects cultures. We honor culinary traditions while embracing fusion and cross-cultural exchanges.</p>
                </div>
                <div class="food-light-pink-bg rounded-lg p-6 text-center">
                    <div class="food-primary-text text-5xl mb-4">🤝</div>
                    <h3 class="text-xl font-bold mb-3">Community First</h3>
                    <p>Our platform thrives on the contributions of our members. We believe in creating a supportive, inclusive space where everyone can share and learn.</p>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
  <?php include 'footer.php'; ?>

    <!-- Slideshow JavaScript -->
    <script>
        let slideIndex = 1;
        showSlides(slideIndex);
        
        // Auto-advance slides every 5 seconds
        setInterval(function() {
            plusSlides(1);
        }, 5000);
        
        function plusSlides(n) {
            showSlides(slideIndex += n);
        }
        
        function currentSlide(n) {
            showSlides(slideIndex = n);
        }
        
        function showSlides(n) {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            let dots = document.getElementsByClassName("dot");
            
            if (n > slides.length) {slideIndex = 1}
            if (n < 1) {slideIndex = slides.length}
            
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            
            slides[slideIndex-1].style.display = "block";
            dots[slideIndex-1].className += " active";
        }
    </script>
</body>
</html>