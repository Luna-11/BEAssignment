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
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            line-height: 1.6;
            background-color: var(--light_yellow);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image: linear-gradient(135deg, #f9f1e5 0%, #e9d0cb 100%);
            padding: 20px;
            position: relative;
            overflow-x: hidden;
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
            top: 20%;
            left: 0%;
            animation-delay: 0s;
        }

        .float-leaf-2 {
            top: 55%;
            right: 30%;
            animation-delay: 0.6s;
            transform: rotate(40deg);
        }

        .float-leaf-3 {
            bottom: 20%;
            left: 29%;
            animation-delay: 0.8s;
            transform: rotate(-20deg);
        }

        .float-leaf-4 {
            bottom: 75%;
            right: 0%;
            animation-delay: 1s;
            transform: rotate(30deg);
        }

        .float-leaf-5 {
            top: 40%;
            left: 5%;
            animation-delay: 1s;
            transform: rotate(-15deg);
        }

        .float-leaf-6 {
            top: 75%;
            left: 95%;
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

        /* Footer */
        footer {
            text-align: center;
            padding: 30px 0;
            margin-top: 50px;
            color: var(--text-color);
            font-size: 0.9rem;
            border-top: 1px solid rgba(123, 78, 72, 0.2);
            position: relative;
            z-index: 2;
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
    <div class="container">
        <header>
            <h1>Gourmet Recipe Cards</h1>
            <p class="subtitle">Discover our delicious menu with healthy and flavorful options</p>
        </header>

        <!-- Background Leaf Decorations - FIXED -->
        <div class="leaf-decoration leaf-1">
            <img src="./BEpics/basil.png" alt="Basil leaf decoration">
        </div>
        <div class="leaf-decoration leaf-2">
            <img src="./BEpics/basil.png" alt="Leaf decoration">
        </div>
        <div class="leaf-decoration leaf-3">
            <img src="./BEpics/basil.png" alt="Leaf decoration">
        </div>
        <div class="leaf-decoration leaf-4">
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
            <div class="recipe-card">
                <div class="recipe-image">
                    <img src="https://images.unsplash.com/photo-1606728035253-49e8a23146de?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Chicken Slice">
                    <div class="recipe-time">25 mins</div>
                </div>
                <div class="recipe-content">
                    <h3 class="recipe-title">Chicken Slice</h3>
                    <p class="recipe-subtitle">Real chicken</p>
                    <p class="recipe-description">Tender chicken slices with special herbs and spices, served with fresh vegetables.</p>
                    <p class="recipe-price">$12.00</p>
                    <button class="add-to-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                </div>
            </div>

            <!-- Recipe Card 2 -->
            <div class="recipe-card">
                <div class="recipe-image">
                    <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Eggs Curry">
                    <div class="recipe-time">30 mins</div>
                </div>
                <div class="recipe-content">
                    <h3 class="recipe-title">Eggs Curry</h3>
                    <p class="recipe-subtitle">Chef's Special</p>
                    <p class="recipe-description">Eggs Curry with tomato and cucumbers our chefs special healthy and fat free dish for those who want to lose weight.</p>
                    <p class="recipe-price">$15.00</p>
                    <button class="add-to-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                </div>
            </div>

            <!-- Recipe Card 3 -->
            <div class="recipe-card">
                <div class="recipe-image">
                    <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Margherita Pizza">
                    <div class="recipe-time">20 mins</div>
                </div>
                <div class="recipe-content">
                    <h3 class="recipe-title">Margherita Pizza</h3>
                    <p class="recipe-subtitle">Classic Italian</p>
                    <p class="recipe-description">Traditional pizza with tomato sauce, fresh mozzarella, and basil leaves on a thin crust.</p>
                    <p class="recipe-price">$14.50</p>
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
        });
    </script>
</body>
</html>