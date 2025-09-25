<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Cards with Floating Leaves</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ===== GLOBAL STYLES ===== */
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
        }
    

        body {
            background-color: var(--light_yellow);
            color: var(--text-color);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            position: relative;
            z-index: 2;
        }

        header {
            text-align: center;
            padding: 30px 0;
        }

        h1 {
            font-size: 2.8rem;
            color: var(--text-color);
            margin-bottom: 15px;
            font-weight: 700;
        }

        .subtitle {
            font-size: 1.2rem;
            color: var(--primary-color);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Filter Section */
        .filter-section {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 20px;
            padding: 25px;
            margin: 20px 0 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .filter-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 20px;
            text-align: center;
        }

        .filter-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--primary-color);
        }

        .filter-select {
            width: 100%;
            padding: 12px 15px;
            border-radius: 12px;
            border: 1px solid var(--light_pink);
            background-color: white;
            color: var(--text-color);
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(200, 144, 145, 0.3);
        }

        .filter-reset {
            display: block;
            margin: 20px auto 0;
            padding: 10px 20px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-reset:hover {
            background: #b37d7e;
            transform: translateY(-2px);
        }

        /* Recipe Cards Grid */
        .recipes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
            padding: 40px 0;
            position: relative;
        }

        /* Recipe Card */
        .recipe-card {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            z-index: 2;
        }

        .recipe-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .recipe-image {
            width: 100%;
            height: 200px;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 20px;
            position: relative;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .recipe-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .recipe-card:hover .recipe-image img {
            transform: scale(1.05);
        }

        .recipe-time {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.9);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--primary-color);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .recipe-content {
            padding: 0 5px;
        }

        .recipe-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 8px;
        }

        .recipe-subtitle {
            font-size: 1rem;
            color: var(--primary-color);
            margin-bottom: 15px;
            font-weight: 500;
        }

        .recipe-description {
            font-size: 0.95rem;
            line-height: 1.6;
            color: var(--medium-gray);
            margin-bottom: 20px;
            min-height: 70px;
        }

        .recipe-price {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 20px;
            text-align: right;
        }

        .recipe-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 0.85rem;
            color: var(--medium-gray);
        }

        .recipe-tag {
            background: var(--light_pink);
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
        }

        .add-to-cart {
            width: 100%;
            padding: 15px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(200, 144, 145, 0.3);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .add-to-cart:hover {
            background: #b37d7e;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(200, 144, 145, 0.4);
        }

        /* Leaf Decorations - FIXED */
        .leaf-decoration {
            position: fixed;
            z-index: 1;
            opacity: 0.7;
            pointer-events: none;
            width: 500px;
            height: 400px;
            filter: drop-shadow(2px 4px 3px rgba(0,0,0,0.1));
        }

        .leaf-decoration img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Leaf positions */
        .leaf-1 {
            top: 15%;
            left: 5%;
            transform: rotate(-15deg);
        }

        .leaf-2 {
            top: 20%;
            right: 8%;
            transform: scaleX(-1) rotate(25deg);
        }

        .leaf-3 {
            bottom: 5%;
            left: 7%;
            transform: rotate(-30deg);
            width: 700px;
            height: 370px;
        }

        .leaf-4 {
            bottom: 15%;
            right: 6%;
            transform: scaleX(-1) rotate(40deg);
            width: 650px;
            height: 605px;
        }

        /* Floating leaves over cards */
        .floating-leaf {
            position: absolute;
            z-index: 3;
            pointer-events: none;
            width: 90px;
            height: 90px;
            opacity: 0.85;
            filter: drop-shadow(2px 3px 2px rgba(0,0,0,0.2));
            animation: float 6s ease-in-out infinite;
        }

        .floating-leaf img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Individual floating leaf positions */
        .float-leaf-1 {
            top: 90%;
            left: 0%;
            animation-delay: 0s;
        }

        .float-leaf-2 {
            top: 65%;
            right: 20%;
            animation-delay: 0.6s;
            transform: rotate(40deg);
        }

        .float-leaf-3 {
            top: 40%;
            left: 29%;
            animation-delay: 0.8s;
            transform: rotate(-20deg);
        }

        .float-leaf-4 {
            top: 35%;
            right: 0%;
            animation-delay: 1s;
            transform: rotate(30deg);
        }

        .float-leaf-5 {
            top: 50%;
            left: 65%;
            animation-delay: 1s;
            transform: rotate(-15deg);
        }

        .float-leaf-6 {
            top: 95%;
            left: 85%;
            animation-delay: 0.6s;
            transform: rotate(60deg);
        }

        /* Floating animation */
        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-15px) rotate(5deg);
            }
            100% {
                transform: translateY(0) rotate(0deg);
            }
        }

        /* Canvas for animated leaves */
        #leavesCanvas {
            position: fixed;
            top: 40%;
            left: 0;
            width: 50%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .recipes-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 20px;
            }
            
            h1 {
                font-size: 2.2rem;
            }
            
            .subtitle {
                font-size: 1rem;
            }
            
            .filter-container {
                flex-direction: column;
            }
            
            .leaf-decoration {
                display: none;
            }
            
            .floating-leaf {
                display: none;
            }
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
    <canvas id="leavesCanvas"></canvas>

    
    <div class="container">
        <!-- Filter Section -->
        <section class="filter-section">
            <h2 class="filter-title">Filter Recipes</h2>
            <div class="filter-container">
                <div class="filter-group">
                    <label class="filter-label" for="food-type">Food Type</label>
                    <select id="food-type" class="filter-select">
                        <option value="all">All Types</option>
                        <option value="meat">Meat</option>
                        <option value="vegetarian">Vegetarian</option>
                        <option value="vegan">Vegan</option>
                        <option value="seafood">Seafood</option>
                        <option value="pasta">Pasta</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label" for="difficulty">Difficulty Level</label>
                    <select id="difficulty" class="filter-select">
                        <option value="all">All Levels</option>
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label" for="diet">Diet Preference</label>
                    <select id="diet" class="filter-select">
                        <option value="all">All Diets</option>
                        <option value="keto">Keto</option>
                        <option value="gluten-free">Gluten-Free</option>
                        <option value="low-carb">Low-Carb</option>
                        <option value="paleo">Paleo</option>
                    </select>
                </div>
            </div>
            <button class="filter-reset" id="reset-filters">Reset Filters</button>
        </section>

        <!-- Background Leaf Decorations - FIXED -->
        <div class="leaf-decoration leaf-3">
            <img src="./BEpics/basil.png" alt="Leaf decoration">
        </div>
        <div class="leaf-decoration leaf-3">
            <img src="./BEpics/basil.png" alt="Leaf decoration">
        </div>

        <!-- Floating leaves over cards -->
        <div class="floating-leaf float-leaf-1">
            <img src="./BEpics/leaf.png" alt="Leaf decoration">
        </div>
        <div class="floating-leaf float-leaf-2">
            <img src="./BEpics/leaf.png" alt="Leaf decoration">
        </div>
        <div class="floating-leaf float-leaf-3">
            <img src="./BEpics/leaf.png" alt="Leaf decoration">
        </div>
        <div class="floating-leaf float-leaf-4">
            <img src="./BEpics/leaf.png" alt="Leaf decoration">
        </div>
        <div class="floating-leaf float-leaf-5">
            <img src="./BEpics/leaf.png" alt="Leaf decoration">
        </div>
        <div class="floating-leaf float-leaf-6">
            <img src="./BEpics/leaf.png" alt="Leaf decoration">
        </div>

        <div class="recipes-grid">
            <!-- Recipe Card 1 -->
            <div class="recipe-card" data-food-type="meat" data-difficulty="medium" data-diet="low-carb">
                <div class="recipe-image">
                    <img src="https://images.unsplash.com/photo-1606728035253-49e8a23146de?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Chicken Slice">
                    <div class="recipe-time">25 mins</div>
                </div>
                <div class="recipe-content">
                    <h3 class="recipe-title">Chicken Slice</h3>
                    <p class="recipe-subtitle">Real chicken</p>
                    <div class="recipe-meta">
                        <span class="recipe-tag">Meat</span>
                        <span class="recipe-tag">Medium</span>
                        <span class="recipe-tag">Low-Carb</span>
                    </div>
                    <p class="recipe-description">Tender chicken slices with special herbs and spices, served with fresh vegetables.</p>
                    <p class="recipe-price">$12.00</p>
                    <button class="add-to-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                </div>
            </div>

            <!-- Recipe Card 2 -->
            <div class="recipe-card" data-food-type="vegetarian" data-difficulty="easy" data-diet="gluten-free">
                <div class="recipe-image">
                    <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Eggs Curry">
                    <div class="recipe-time">30 mins</div>
                </div>
                <div class="recipe-content">
                    <h3 class="recipe-title">Eggs Curry</h3>
                    <p class="recipe-subtitle">Chef's Special</p>
                    <div class="recipe-meta">
                        <span class="recipe-tag">Vegetarian</span>
                        <span class="recipe-tag">Easy</span>
                        <span class="recipe-tag">Gluten-Free</span>
                    </div>
                    <p class="recipe-description">Eggs Curry with tomato and cucumbers our chefs special healthy and fat free dish for those who want to lose weight.</p>
                    <p class="recipe-price">$15.00</p>
                    <button class="add-to-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                </div>
            </div>

            <!-- Recipe Card 3 -->
            <div class="recipe-card" data-food-type="vegetarian" data-difficulty="easy" data-diet="vegetarian">
                <div class="recipe-image">
                    <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Margherita Pizza">
                    <div class="recipe-time">20 mins</div>
                </div>
                <div class="recipe-content">
                    <h3 class="recipe-title">Margherita Pizza</h3>
                    <p class="recipe-subtitle">Classic Italian</p>
                    <div class="recipe-meta">
                        <span class="recipe-tag">Vegetarian</span>
                        <span class="recipe-tag">Easy</span>
                        <span class="recipe-tag">Vegetarian</span>
                    </div>
                    <p class="recipe-description">Traditional pizza with tomato sauce, fresh mozzarella, and basil leaves on a thin crust.</p>
                    <p class="recipe-price">$14.50</p>
                    <button class="add-to-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                </div>
            </div>

            <!-- Recipe Card 4 -->
            <div class="recipe-card" data-food-type="seafood" data-difficulty="medium" data-diet="paleo">
                <div class="recipe-image">
                    <img src="https://images.unsplash.com/photo-1559715745-e1b33a271c8f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Grilled Salmon">
                    <div class="recipe-time">35 mins</div>
                </div>
                <div class="recipe-content">
                    <h3 class="recipe-title">Grilled Salmon</h3>
                    <p class="recipe-subtitle">Fresh Atlantic</p>
                    <div class="recipe-meta">
                        <span class="recipe-tag">Seafood</span>
                        <span class="recipe-tag">Medium</span>
                        <span class="recipe-tag">Paleo</span>
                    </div>
                    <p class="recipe-description">Fresh Atlantic salmon grilled to perfection with lemon butter sauce and seasonal vegetables.</p>
                    <p class="recipe-price">$18.50</p>
                    <button class="add-to-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                </div>
            </div>

            <!-- Recipe Card 5 -->
            <div class="recipe-card" data-food-type="vegan" data-difficulty="hard" data-diet="vegan">
                <div class="recipe-image">
                    <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Vegan Buddha Bowl">
                    <div class="recipe-time">40 mins</div>
                </div>
                <div class="recipe-content">
                    <h3 class="recipe-title">Vegan Buddha Bowl</h3>
                    <p class="recipe-subtitle">Plant Power</p>
                    <div class="recipe-meta">
                        <span class="recipe-tag">Vegan</span>
                        <span class="recipe-tag">Hard</span>
                        <span class="recipe-tag">Vegan</span>
                    </div>
                    <p class="recipe-description">A colorful bowl packed with quinoa, roasted vegetables, avocado, and tahini dressing.</p>
                    <p class="recipe-price">$13.75</p>
                    <button class="add-to-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                </div>
            </div>

            <!-- Recipe Card 6 -->
            <div class="recipe-card" data-food-type="pasta" data-difficulty="easy" data-diet="keto">
                <div class="recipe-image">
                    <img src="https://images.unsplash.com/photo-1621996346565-e3dbc353d2e5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Zucchini Pasta">
                    <div class="recipe-time">15 mins</div>
                </div>
                <div class="recipe-content">
                    <h3 class="recipe-title">Zucchini Pasta</h3>
                    <p class="recipe-subtitle">Keto Friendly</p>
                    <div class="recipe-meta">
                        <span class="recipe-tag">Pasta</span>
                        <span class="recipe-tag">Easy</span>
                        <span class="recipe-tag">Keto</span>
                    </div>
                    <p class="recipe-description">Spiralized zucchini noodles with pesto, cherry tomatoes, and parmesan cheese.</p>
                    <p class="recipe-price">$11.25</p>
                    <button class="add-to-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                </div>
            </div>
        </div>

        <footer>
            <p>© 2023 Gourmet Recipes | Made with <i class="fas fa-heart" style="color: var(--primary-color);"></i></p>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add to cart functionality
            const addToCartButtons = document.querySelectorAll('.add-to-cart');
            addToCartButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const card = this.closest('.recipe-card');
                    const title = card.querySelector('.recipe-title').textContent;
                    
                    this.innerHTML = '<i class="fas fa-check"></i> Added to Cart';
                    this.style.background = '#7b4e48';
                    
                    setTimeout(() => {
                        this.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
                        this.style.background = '';
                    }, 2000);
                    
                    console.log(`Added ${title} to cart`);
                });
            });

            // Filter functionality
            const foodTypeFilter = document.getElementById('food-type');
            const difficultyFilter = document.getElementById('difficulty');
            const dietFilter = document.getElementById('diet');
            const resetButton = document.getElementById('reset-filters');
            const recipeCards = document.querySelectorAll('.recipe-card');

            function filterRecipes() {
                const selectedFoodType = foodTypeFilter.value;
                const selectedDifficulty = difficultyFilter.value;
                const selectedDiet = dietFilter.value;

                recipeCards.forEach(card => {
                    const cardFoodType = card.getAttribute('data-food-type');
                    const cardDifficulty = card.getAttribute('data-difficulty');
                    const cardDiet = card.getAttribute('data-diet');

                    const showCard = 
                        (selectedFoodType === 'all' || selectedFoodType === cardFoodType) &&
                        (selectedDifficulty === 'all' || selectedDifficulty === cardDifficulty) &&
                        (selectedDiet === 'all' || selectedDiet === cardDiet);

                    card.style.display = showCard ? 'block' : 'none';
                });
            }

            // Add event listeners to filters
            foodTypeFilter.addEventListener('change', filterRecipes);
            difficultyFilter.addEventListener('change', filterRecipes);
            dietFilter.addEventListener('change', filterRecipes);

            // Reset filters
            resetButton.addEventListener('click', function() {
                foodTypeFilter.value = 'all';
                difficultyFilter.value = 'all';
                dietFilter.value = 'all';
                filterRecipes();
            });
        });

        // Floating Leaves Animation
        const canvas = document.getElementById('leavesCanvas');
        const ctx = canvas.getContext('2d');

        // Set canvas size
        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        // Leaf object
        class Leaf {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * -canvas.height;
                this.size = Math.random() * 30 + 20; // Larger leaves: 20-50px
                this.speed = Math.random() * 1 + 0.5; // Slower speed: 0.5-1.5px/frame
                this.angle = Math.random() * Math.PI * 2;
                this.spin = (Math.random() - 0.5) * 0.05;
                this.color = Math.random() > 0.5 ? '#A8D5BA' : '#4A7043'; // Light and medium green
            }

            update() {
                this.y += this.speed;
                this.x += Math.sin(this.angle) * 0.5;
                this.angle += this.spin;

                // Reset leaf when it goes off-screen
                if (this.y > canvas.height + this.size) {
                    this.y = -this.size;
                    this.x = Math.random() * canvas.width;
                    this.speed = Math.random() * 1 + 0.5;
                    this.angle = Math.random() * Math.PI * 2;
                    this.size = Math.random() * 30 + 20; // Ensure reset leaves are also 20-50px
                }
            }

            draw() {
                ctx.save();
                ctx.translate(this.x, this.y);
                ctx.rotate(this.angle);
                ctx.fillStyle = this.color;
                ctx.beginPath();
                ctx.ellipse(0, 0, this.size / 2, this.size / 4, 0, 0, Math.PI * 2);
                ctx.fill();
                ctx.restore();
            }
        }

        // Create leaves
        const leaves = [];
        for (let i = 0; i < 20; i++) {
            leaves.push(new Leaf());
        }

        // Animation loop
        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            leaves.forEach(leaf => {
                leaf.update();
                leaf.draw();
            });
            requestAnimationFrame(animate);
        }

        animate();
    </script>
</body>
</html>