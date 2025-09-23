document.addEventListener('DOMContentLoaded', function() {
    // Get recipe ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const recipeId = urlParams.get('id');

    // Sample recipe data (should match recipes.js)
    const recipes = [
        {
            id: 1,
            name: "Classic Margherita Pizza",
            cuisine: "italian",
            dietary: ["vegetarian"],
            difficulty: "medium",
            time: 45,
            servings: 4,
            image: "https://images.pexels.com/photos/315755/pexels-photo-315755.jpeg?auto=compress&cs=tinysrgb&w=800",
            description: "This traditional Italian pizza showcases the perfect harmony of simple, high-quality ingredients. Fresh mozzarella, ripe tomatoes, and aromatic basil come together on a crispy yet chewy crust to create a timeless classic that has delighted pizza lovers for generations.",
            rating: 4.8,
            comments: 23,
            ingredients: [
                "500g pizza dough (homemade or store-bought)",
                "200ml tomato sauce",
                "200g fresh mozzarella cheese, sliced",
                "Fresh basil leaves",
                "2 tbsp extra virgin olive oil",
                "Salt and pepper to taste",
                "Flour for dusting"
            ],
            instructions: [
                "Preheat your oven to 475°F (245°C). If using a pizza stone, place it in the oven while preheating.",
                "On a floured surface, roll out the pizza dough to your desired thickness, typically about 12 inches in diameter.",
                "Transfer the dough to a pizza pan or parchment paper if using a pizza stone.",
                "Spread the tomato sauce evenly over the dough, leaving a 1-inch border for the crust.",
                "Distribute the mozzarella slices evenly over the sauce.",
                "Drizzle with olive oil and season with salt and pepper.",
                "Bake for 10-12 minutes until the crust is golden and the cheese is bubbly and slightly browned.",
                "Remove from oven and immediately top with fresh basil leaves.",
                "Let cool for 2-3 minutes, then slice and serve hot."
            ]
        },
        {
            id: 2,
            name: "Kung Pao Chicken",
            cuisine: "chinese",
            dietary: ["gluten-free"],
            difficulty: "medium",
            time: 30,
            servings: 4,
            image: "https://images.pexels.com/photos/769969/pexels-photo-769969.jpeg?auto=compress&cs=tinysrgb&w=800",
            description: "A classic Sichuan dish featuring tender chicken, crunchy peanuts, and vibrant vegetables in a perfectly balanced sweet and spicy sauce. This authentic recipe brings the bold flavors of Chinese cuisine to your home kitchen.",
            rating: 4.6,
            comments: 18,
            ingredients: [
                "500g boneless chicken thighs, cut into cubes",
                "1/2 cup roasted peanuts",
                "1 red bell pepper, diced",
                "1 green bell pepper, diced",
                "3 dried red chilies",
                "3 cloves garlic, minced",
                "1 tbsp fresh ginger, minced",
                "2 green onions, chopped",
                "2 tbsp soy sauce",
                "1 tbsp rice wine or dry sherry",
                "1 tsp cornstarch",
                "2 tbsp vegetable oil"
            ],
            instructions: [
                "Marinate the chicken cubes with 1 tablespoon soy sauce, rice wine, and cornstarch for 15 minutes.",
                "Heat oil in a wok or large skillet over high heat.",
                "Add dried chilies and stir-fry for 30 seconds until fragrant.",
                "Add marinated chicken and cook for 3-4 minutes until browned.",
                "Add garlic and ginger, stir-fry for another minute.",
                "Add bell peppers and cook for 2-3 minutes until crisp-tender.",
                "Stir in remaining soy sauce and peanuts.",
                "Garnish with green onions and serve immediately with steamed rice."
            ]
        }
        // Add more recipes as needed
    ];

    // Find the recipe
    const recipe = recipes.find(r => r.id == recipeId);

    if (!recipe) {
        document.getElementById('recipe-detail').innerHTML = `
            <div class="container">
                <div style="text-align: center; padding: 4rem 0;">
                    <h1>Recipe not found</h1>
                    <p>The recipe you're looking for doesn't exist.</p>
                    <a href="recipes.html" style="color: #000; text-decoration: underline;">← Back to Recipes</a>
                </div>
            </div>
        `;
        return;
    }

    // Render recipe detail
    renderRecipeDetail(recipe);

    // Load comments
    loadComments(recipeId);

    // Mobile Navigation Toggle
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('nav-menu');

    hamburger.addEventListener('click', function() {
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('active');
    });

    // Close mobile menu when clicking on a link
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function() {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
        });
    });

    // Dropdown toggle for mobile
    const dropdown = document.querySelector('.dropdown');
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    
    if (dropdownToggle) {
        dropdownToggle.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                dropdown.classList.toggle('active');
            }
        });
    }

    // Comment submission
    const submitCommentBtn = document.getElementById('submitComment');
    const commentTextarea = document.getElementById('commentText');

    if (submitCommentBtn) {
        submitCommentBtn.addEventListener('click', function() {
            const commentText = commentTextarea.value.trim();
            if (commentText) {
                addComment(recipeId, commentText);
                commentTextarea.value = '';
            } else {
                alert('Please enter a comment before submitting.');
            }
        });
    }

    // Action buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.favorite-btn')) {
            toggleFavorite();
        } else if (e.target.closest('.share-btn')) {
            shareRecipe();
        }
    });

    function renderRecipeDetail(recipe) {
        const recipeDetailContainer = document.getElementById('recipeDetail');
        
        const html = `
            <div class="recipe-header">
                <div class="recipe-title-section">
                    <h1 class="recipe-title">${recipe.name}</h1>
                    <p class="recipe-subtitle">${recipe.description}</p>
                    <div class="recipe-meta-info">
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>${recipe.time} minutes</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-users"></i>
                            <span>Serves ${recipe.servings || 4}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-signal"></i>
                            <span>${recipe.difficulty}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-globe"></i>
                            <span>${recipe.cuisine}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="recipe-content">
                <div class="recipe-image-section">
                    <img src="${recipe.image}" alt="${recipe.name}" class="recipe-main-image">
                    <div class="recipe-actions">
                        <button class="action-btn favorite-btn" title="Add to favorites">
                            <i class="far fa-heart"></i>
                        </button>
                        <button class="action-btn share-btn" title="Share recipe">
                            <i class="fas fa-share"></i>
                        </button>
                    </div>
                </div>

                <div class="recipe-info-section">
                    <div class="recipe-tags">
                        ${recipe.dietary.map(diet => `<span class="recipe-tag">${diet}</span>`).join('')}
                        <span class="recipe-tag">${recipe.cuisine}</span>
                        <span class="recipe-tag">${recipe.difficulty}</span>
                    </div>

                    <div class="recipe-rating">
                        <div class="stars">${'★'.repeat(Math.floor(recipe.rating))}${'☆'.repeat(5-Math.floor(recipe.rating))}</div>
                        <span class="rating-text">${recipe.rating}/5 (${recipe.comments} reviews)</span>
                    </div>

                    <div class="recipe-description">
                        <p>${recipe.description}</p>
                    </div>
                </div>
            </div>

            <div class="ingredients-instructions">
                <div class="ingredients-section">
                    <h3><i class="fas fa-list"></i> Ingredients</h3>
                    <ul class="ingredients-list">
                        ${recipe.ingredients.map(ingredient => `<li>${ingredient}</li>`).join('')}
                    </ul>
                </div>

                <div class="instructions-section">
                    <h3><i class="fas fa-utensils"></i> Instructions</h3>
                    <ol class="instructions-list">
                        ${recipe.instructions.map(instruction => `<li>${instruction}</li>`).join('')}
                    </ol>
                </div>
            </div>
        `;

        recipeDetailContainer.innerHTML = html;
    }

    function loadComments(recipeId) {
        // Sample comments data
        const sampleComments = [
            {
                id: 1,
                author: "Sarah Johnson",
                date: "2025-01-10",
                text: "Amazing recipe! I followed the instructions exactly and it turned out perfect. My family loved it!"
            },
            {
                id: 2,
                author: "Mike Chen",
                date: "2025-01-08",
                text: "Great recipe but I added a bit more spice to suit my taste. Still delicious!"
            },
            {
                id: 3,
                author: "Emma Wilson",
                date: "2025-01-05",
                text: "This has become our go-to recipe for dinner parties. Always gets rave reviews!"
            }
        ];

        const commentsList = document.getElementById('commentsList');
        commentsList.innerHTML = sampleComments.map(comment => `
            <div class="comment">
                <div class="comment-header">
                    <span class="comment-author">${comment.author}</span>
                    <span class="comment-date">${comment.date}</span>
                </div>
                <div class="comment-text">${comment.text}</div>
            </div>
        `).join('');
    }

    function addComment(recipeId, commentText) {
        const commentsList = document.getElementById('commentsList');
        const newComment = document.createElement('div');
        newComment.className = 'comment';
        newComment.innerHTML = `
            <div class="comment-header">
                <span class="comment-author">You</span>
                <span class="comment-date">${new Date().toISOString().split('T')[0]}</span>
            </div>
            <div class="comment-text">${commentText}</div>
        `;
        
        commentsList.insertBefore(newComment, commentsList.firstChild);
        
        // Show success message
        alert('Comment added successfully!');
    }

    function toggleFavorite() {
        const favoriteBtn = document.querySelector('.favorite-btn i');
        if (favoriteBtn.classList.contains('far')) {
            favoriteBtn.classList.remove('far');
            favoriteBtn.classList.add('fas');
            alert('Recipe added to favorites!');
        } else {
            favoriteBtn.classList.remove('fas');
            favoriteBtn.classList.add('far');
            alert('Recipe removed from favorites!');
        }
    }

    function shareRecipe() {
        if (navigator.share) {
            navigator.share({
                title: recipe.name,
                text: recipe.description,
                url: window.location.href
            });
        } else {
            // Fallback for browsers that don't support Web Share API
            const shareText = `Check out this amazing recipe: ${recipe.name} - ${window.location.href}`;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(shareText);
                alert('Recipe link copied to clipboard!');
            } else {
                alert(`Share this recipe: ${shareText}`);
            }
        }
    }
});