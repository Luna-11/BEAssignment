<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Cards with Floating Leaves</title>
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

        .floating-leaf {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Custom styles for better visual feedback */
        .filter-section {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .recipe-card {
            transition: all 0.3s ease;
        }
        
        .recipe-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .filter-select {
            transition: all 0.3s ease;
        }
        
        .filter-select:focus {
            box-shadow: 0 0 0 3px rgba(200, 144, 145, 0.3);
        }
        
        .no-results {
            display: none;
            text-align: center;
            padding: 2rem;
            font-size: 1.2rem;
            color: #7b4e48;
        }
    </style>
</head>
<body class="bg-[#f9f1e5] text-[#7b4e48]">
    <?php include 'navbar.php'; ?>
    <canvas id="leavesCanvas" class="fixed top-2/5 left-0 w-1/2 h-full z-10 pointer-events-none"></canvas>
    
    <div class="mx-auto w-full relative z-20">
        <!-- Filter Section -->
        <section class="filter-section rounded-2xl p-6 my-5 mx-4 shadow-lg">
            <h2 class="text-2xl font-bold text-[#7b4e48] mb-5 text-center">Filter Recipes</h2>
            <div class="flex flex-wrap gap-5 justify-center">
                <div class="flex-1 min-w-[200px]">
                    <label class="block mb-2 font-semibold text-[#C89091]" for="food-type">Food Type</label>
                    <select id="food-type" class="filter-select w-full p-3 rounded-xl border border-[#e9d0cb] bg-white text-[#7b4e48] text-base cursor-pointer transition-all focus:outline-none focus:border-[#C89091]">
                        <option value="all">All Types</option>
                        <?php
                            include('configMysql.php');
                        
                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        
                        // Fetch food types from database
                        $sql = "SELECT * FROM foodType";
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row["foodType"] . "'>" . $row["foodType"] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block mb-2 font-semibold text-[#C89091]" for="cuisine-type">Cuisine Type</label>
                    <select id="cuisine-type" class="filter-select w-full p-3 rounded-xl border border-[#e9d0cb] bg-white text-[#7b4e48] text-base cursor-pointer transition-all focus:outline-none focus:border-[#C89091]">
                        <option value="all">All Cuisines</option>
                        <?php
                        // Fetch cuisine types from database
                        $sql = "SELECT * FROM cuisineType";
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row["cuisineType"] . "'>" . $row["cuisineType"] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block mb-2 font-semibold text-[#C89091]" for="difficulty">Difficulty Level</label>
                    <select id="difficulty" class="filter-select w-full p-3 rounded-xl border border-[#e9d0cb] bg-white text-[#7b4e48] text-base cursor-pointer transition-all focus:outline-none focus:border-[#C89091]">
                        <option value="all">All Levels</option>
                        <?php
                        // Fetch difficulty levels from difficultyLev table
                        $sql = "SELECT * FROM difficultyLev";
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row["difficultyName"] . "'>" . ucfirst($row["difficultyName"]) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block mb-2 font-semibold text-[#C89091]" for="diet">Diet Preference</label>
                    <select id="diet" class="filter-select w-full p-3 rounded-xl border border-[#e9d0cb] bg-white text-[#7b4e48] text-base cursor-pointer transition-all focus:outline-none focus:border-[#C89091]">
                        <option value="all">All Diets</option>
                        <?php
                        // Fetch diet preferences from database
                        $sql = "SELECT * FROM dietPreferences";
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row["dietName"] . "'>" . $row["dietName"] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <button id="reset-filters" class="block mx-auto mt-5 px-5 py-2.5 bg-[#C89091] text-white border-none rounded-xl text-base font-semibold cursor-pointer transition-all hover:bg-[#b37d7e] hover:-translate-y-0.5">
                Reset Filters
            </button>
        </section>
        <!-- Floating leaves over cards -->
        <div class="floating-leaf absolute z-30 pointer-events-none w-24 h-24 opacity-85 filter drop-shadow-[2px_3px_2px_rgba(0,0,0,0.2)] top-[90%] left-0">
            <img src="./BEpics/leaf.png" alt="Leaf decoration" class="w-full h-full object-contain">
        </div>
        <div class="floating-leaf absolute z-30 pointer-events-none w-24 h-24 opacity-85 filter drop-shadow-[2px_3px_2px_rgba(0,0,0,0.2)] top-[65%] right-[20%] rotate-40" style="animation-delay: 0.6s;">
            <img src="./BEpics/leaf.png" alt="Leaf decoration" class="w-full h-full object-contain">
        </div>
        <div class="floating-leaf absolute z-30 pointer-events-none w-24 h-24 opacity-85 filter drop-shadow-[2px_3px_2px_rgba(0,0,0,0.2)] top-[40%] left-[29%] -rotate-20" style="animation-delay: 0.8s;">
            <img src="./BEpics/leaf.png" alt="Leaf decoration" class="w-full h-full object-contain">
        </div>
        <div class="floating-leaf absolute z-30 pointer-events-none w-24 h-24 opacity-85 filter drop-shadow-[2px_3px_2px_rgba(0,0,0,0.2)] top-[35%] right-0 rotate-30" style="animation-delay: 1s;">
            <img src="./BEpics/leaf.png" alt="Leaf decoration" class="w-full h-full object-contain">
        </div>
        <div class="floating-leaf absolute z-30 pointer-events-none w-24 h-24 opacity-85 filter drop-shadow-[2px_3px_2px_rgba(0,0,0,0.2)] top-[50%] left-[65%] -rotate-15" style="animation-delay: 1s;">
            <img src="./BEpics/leaf.png" alt="Leaf decoration" class="w-full h-full object-contain">
        </div>
        <div class="floating-leaf absolute z-30 pointer-events-none w-24 h-24 opacity-85 filter drop-shadow-[2px_3px_2px_rgba(0,0,0,0.2)] top-[95%] left-[85%] rotate-60" style="animation-delay: 0.6s;">
            <img src="./BEpics/leaf.png" alt="Leaf decoration" class="w-full h-full object-contain">
        </div>

        <!-- Recipe Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 p-10 relative">
            <?php
            // Fetch recipes from database
            $sql = "SELECT r.*, 
                           ft.foodType, 
                           ct.cuisineType, 
                           dl.difficultyName, 
                           dp.dietName,
                           r.image as imageUrl,
                           r.recipeName as title,
                           r.recipeDescription as description
                    FROM recipe r 
                    LEFT JOIN foodType ft ON r.foodTypeID = ft.foodTypeID
                    LEFT JOIN cuisineType ct ON r.cuisineTypeID = ct.cuisineTypeID
                    LEFT JOIN difficultyLev dl ON r.difficultID = dl.difficultyID
                    LEFT JOIN dietPreferences dp ON r.dietaryID = dp.dietID
                    WHERE r.recipeName IS NOT NULL";

            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="recipe-card bg-white/85 rounded-2xl p-6 shadow-lg relative overflow-hidden border border-white/50 transition-all duration-300 z-20" 
                         data-food-type="<?php echo htmlspecialchars($row["foodType"] ?? ''); ?>" 
                         data-cuisine-type="<?php echo htmlspecialchars($row["cuisineType"] ?? ''); ?>" 
                         data-difficulty="<?php echo htmlspecialchars($row["difficultyName"] ?? ''); ?>" 
                         data-diet="<?php echo htmlspecialchars($row["dietName"] ?? ''); ?>">
                        <div class="recipe-image w-full h-48 rounded-xl overflow-hidden mb-5 relative shadow-lg">
                            <img src="<?php echo htmlspecialchars($row["imageUrl"] ?? './BEpics/placeholder.jpg'); ?>" 
                                 alt="<?php echo htmlspecialchars($row["title"] ?? 'Recipe'); ?>" 
                                 class="w-full h-full object-cover transition-transform duration-500">
                        </div>
                        <div class="recipe-content px-1">
                            <h3 class="recipe-title text-xl font-bold text-[#7b4e48] mb-2">
                                <?php echo htmlspecialchars($row["title"] ?? 'Untitled Recipe'); ?>
                            </h3>
                            <div class="recipe-meta flex flex-wrap gap-2 mb-4 text-sm text-[#555]">
                                <span class="recipe-tag bg-[#e9d0cb] px-3 py-1 rounded-full text-xs">
                                    <?php echo htmlspecialchars($row["foodType"] ?? 'Not specified'); ?>
                                </span>
                                <span class="recipe-tag bg-[#e9d0cb] px-3 py-1 rounded-full text-xs">
                                    <?php echo htmlspecialchars($row["cuisineType"] ?? 'Not specified'); ?>
                                </span>
                                <span class="recipe-tag bg-[#e9d0cb] px-3 py-1 rounded-full text-xs">
                                    <?php echo htmlspecialchars(ucfirst($row["difficultyName"] ?? 'Medium')); ?>
                                </span>
                                <span class="recipe-tag bg-[#e9d0cb] px-3 py-1 rounded-full text-xs">
                                    <?php echo htmlspecialchars($row["dietName"] ?? 'Not specified'); ?>
                                </span>
                            </div>
                            <p class="recipe-description text-[#555] text-sm leading-relaxed mb-5 min-h-[70px]">
                                <?php 
                                $description = $row["description"] ?? 'No description available.';
                                if (strlen($description) > 150) {
                                    $description = substr($description, 0, 150) . '...';
                                }
                                echo htmlspecialchars($description); 
                                ?>
                            </p>

                            <a href="reDetail.php?id=<?php echo $row['recipeID']; ?>" 
                            class="w-full p-4 bg-[#C89091] text-white border-none rounded-xl text-base font-semibold cursor-pointer transition-all shadow-lg hover:bg-[#b37d7e] hover:-translate-y-0.5 hover:shadow-xl flex justify-center items-center gap-2">
                                View Detail
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-3 text-center py-10">
                    <i class="fas fa-utensils fa-3x text-[#C89091] mb-4"></i>
                    <h3 class="text-xl font-bold text-[#7b4e48]">No recipes found</h3>
                    <p class="text-[#555]">There are no recipes in the database yet.</p>
                </div>
            <?php endif; ?>
            <?php $conn->close(); ?>
        </div>

        <!-- No Results Message -->
        <div id="no-results" class="no-results">
            <i class="fas fa-search fa-2x mb-4 text-[#C89091]"></i>
            <h3 class="text-xl font-bold mb-2">No recipes found</h3>
            <p>Try adjusting your filters to see more results.</p>
        </div>
                    <?php include 'footer.php'; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter functionality
            const foodTypeFilter = document.getElementById('food-type');
            const cuisineTypeFilter = document.getElementById('cuisine-type');
            const difficultyFilter = document.getElementById('difficulty');
            const dietFilter = document.getElementById('diet');
            const resetButton = document.getElementById('reset-filters');
            const recipeCards = document.querySelectorAll('.recipe-card');
            const noResultsMessage = document.getElementById('no-results');

            function filterRecipes() {
                const selectedFoodType = foodTypeFilter.value;
                const selectedCuisineType = cuisineTypeFilter.value;
                const selectedDifficulty = difficultyFilter.value;
                const selectedDiet = dietFilter.value;

                let visibleCount = 0;

                recipeCards.forEach(card => {
                    const cardFoodType = card.getAttribute('data-food-type');
                    const cardCuisineType = card.getAttribute('data-cuisine-type');
                    const cardDifficulty = card.getAttribute('data-difficulty');
                    const cardDiet = card.getAttribute('data-diet');

                    const showCard = 
                        (selectedFoodType === 'all' || selectedFoodType === cardFoodType) &&
                        (selectedCuisineType === 'all' || selectedCuisineType === cardCuisineType) &&
                        (selectedDifficulty === 'all' || selectedDifficulty === cardDifficulty) &&
                        (selectedDiet === 'all' || selectedDiet === cardDiet);

                    if (showCard) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Show/hide no results message
                if (visibleCount === 0) {
                    noResultsMessage.style.display = 'block';
                } else {
                    noResultsMessage.style.display = 'none';
                }
            }

            // Add event listeners to filters
            foodTypeFilter.addEventListener('change', filterRecipes);
            cuisineTypeFilter.addEventListener('change', filterRecipes);
            difficultyFilter.addEventListener('change', filterRecipes);
            dietFilter.addEventListener('change', filterRecipes);

            // Reset filters
            resetButton.addEventListener('click', function() {
                foodTypeFilter.value = 'all';
                cuisineTypeFilter.value = 'all';
                difficultyFilter.value = 'all';
                dietFilter.value = 'all';
                filterRecipes();
            });

            // Apply filters on page load
            filterRecipes();
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
                this.size = Math.random() * 30 + 20;
                this.speed = Math.random() * 1 + 0.5;
                this.angle = Math.random() * Math.PI * 2;
                this.spin = (Math.random() - 0.5) * 0.05;
                this.color = Math.random() > 0.5 ? '#A8D5BA' : '#4A7043';
            }

            update() {
                this.y += this.speed;
                this.x += Math.sin(this.angle) * 0.5;
                this.angle += this.spin;

                if (this.y > canvas.height + this.size) {
                    this.y = -this.size;
                    this.x = Math.random() * canvas.width;
                    this.speed = Math.random() * 1 + 0.5;
                    this.angle = Math.random() * Math.PI * 2;
                    this.size = Math.random() * 30 + 20;
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
        for (let i = 0; i < 40; i++) {
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