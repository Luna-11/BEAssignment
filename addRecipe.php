<?php
session_start(); // Add session start at the top

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    $_SESSION['error_message'] = 'Please log in to upload recipes';
    header("Location: login.php"); // Redirect to your login page
    exit;
}

include('./configMysql.php');
include('./function.php');

// Fetch categories from database
$cuisineTypes = getCuisineType($conn);
$foodTypes = getFoodType($conn);
$difficultyLevels = getDifficultyLevels($conn);
$dietPreferences = getDietPref($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Recipe - FoodFusion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Rich Text Editor -->
    <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.snow.css">
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
        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }

        .hidden {
            display: none !important;
        }
        
        canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }
        
        /* Style for ingredients textarea */
        #ingredients {
            min-height: 200px;
            resize: vertical;
        }

        /* Quill editor custom styles */
        .ql-editor {
            min-height: 300px;
            font-size: 16px;
            line-height: 1.6;
        }

        .ql-toolbar.ql-snow {
            border-top: 1px solid #ccc;
            border-left: 1px solid #ccc;
            border-right: 1px solid #ccc;
            border-bottom: none;
        }

        .ql-container.ql-snow {
            border: 1px solid #ccc;
            border-top: none;
        }

        /* Required field styling */
        .required-field::after {
            content: " *";
            color: #ef4444;
        }
    </style>
</head>
<body class="bg-light-yellow text-text-color font-segoe min-h-screen relative">
    <canvas id="leavesCanvas"></canvas>

    <!-- Upload Recipe Header -->
    <section class="bg-gradient-to-r from-primary to-medium-pink text-white py-12 sm:py-16 text-center">
        <div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4">Share Your Recipe</h1>
            <p class="text-lg sm:text-xl opacity-90">Share your culinary creations with the FoodFusion community</p>
        </div>
    </section>

    <!-- Recipe Upload Form -->
    <section class="py-10">
        <div class="max-w-4xl mx-auto bg-lightest rounded-2xl shadow-2xl overflow-hidden w-[90%] sm:w-[95%] lg:w-full">
            <div class="flex flex-col sm:flex-row bg-light-pink p-5 sm:p-6">
                <div class="flex-1 text-center sm:text-left mb-4 sm:mb-0 relative progress-step" data-step="1">
                    <div class="step-number w-10 h-10 rounded-full bg-white text-primary flex items-center justify-center mx-auto sm:mx-0 mb-2 font-bold">1</div>
                    <span class="text-sm font-semibold">Recipe Details</span>
                </div>
                <div class="flex-1 text-center sm:text-left relative progress-step" data-step="2">
                    <div class="step-number w-10 h-10 rounded-full bg-white text-primary flex items-center justify-center mx-auto sm:mx-0 mb-2 font-bold">2</div>
                    <span class="text-sm font-semibold">Ingredients & Instructions</span>
                </div>
            </div>

            <!-- Display Messages -->
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative m-4">
                    <span class="block sm:inline"><?php echo $_SESSION['success_message']; ?></span>
                    <?php unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative m-4">
                    <span class="block sm:inline"><?php echo $_SESSION['error_message']; ?></span>
                    <?php unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <form id="uploadRecipeForm" class="p-6 sm:p-8" method="POST" action="process_add_recipe.php" enctype="multipart/form-data">
                <input type="hidden" name="action" value="addRecipe">
                
                <!-- Step 1: Recipe Details -->
                <div class="form-step active" data-step="1">
                    <h2 class="text-primary text-2xl mb-6 flex items-center gap-2"><i class="fas fa-info-circle"></i> Recipe Details</h2>
                    
                    <div class="mb-6">
                        <label for="recipeTitle" class="block font-semibold mb-2 required-field">Recipe Title</label>
                        <input type="text" id="recipeTitle" name="recipeTitle" required 
                               placeholder="Enter your recipe title" class="w-full p-3 border border-border-color rounded-lg bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/50">
                        <div class="text-medium-gray text-sm mt-1">Give your recipe a catchy and descriptive title</div>
                    </div>

                    <div class="mb-6">
                        <label for="difficulty" class="block font-semibold mb-2 required-field">Difficulty Level</label>
                        <select id="difficulty" name="difficulty" required class="w-full p-3 border border-border-color rounded-lg bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/50">
                            <option value="">Select difficulty</option>
                            <?php foreach ($difficultyLevels as $level): ?>
                                <option value="<?php echo htmlspecialchars($level['difficultyID']); ?>">
                                    <?php echo htmlspecialchars($level['difficultyName']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Image Upload Section -->
                    <h2 class="text-primary text-2xl mb-6 flex items-center gap-2"><i class="fas fa-images"></i> Recipe Images</h2>
                    <p class="text-medium-gray mb-6">Upload high-quality image of your recipe</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
                        <div class="image-upload-slot border-2 border-dashed border-border-color rounded-lg h-48 flex items-center justify-center relative overflow-hidden cursor-pointer hover:border-primary" data-slot="1">
                            <input type="file" id="image1" name="image1" accept="image/*" required class="absolute w-full h-full opacity-0 cursor-pointer">
                            <div class="upload-placeholder text-center text-medium-gray">
                                <i class="fas fa-camera text-5xl mb-2 text-light-gray"></i>
                                <span class="block required-field">Main Image</span>
                                <small>Click to upload</small>
                            </div>
                            <div class="image-preview hidden absolute inset-0">
                                <img src="" alt="Preview" class="w-full h-full object-cover">
                                <button type="button" class="remove-image absolute top-1 right-1 bg-black/70 text-white rounded-full w-8 h-8 flex items-center justify-center">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-light-pink p-4 rounded-lg text-sm text-medium-gray">
                        <h4 class="font-semibold mb-2">Image Guidelines:</h4>
                        <ul class="list-disc pl-5">
                            <li>Maximum file size: 5MB per image</li>
                            <li>Supported formats: JPG, PNG, WebP</li>
                            <li>Recommended resolution: 1200x800 pixels</li>
                            <li>All three images are required</li>
                        </ul>
                    </div>

                    <!-- Categories Section -->
                    <h2 class="text-primary text-2xl my-6 flex items-center gap-2"><i class="fas fa-tags"></i> Categories & Tags</h2>
                    
<div class="mb-6">
    <label for="country" class="block font-semibold mb-2 required-field">Country/Cuisine</label>
    <select id="country" name="country" required class="w-full p-3 border border-border-color rounded-lg bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/50">
        <option value="">Select country/cuisine</option>
        <?php foreach ($cuisineTypes as $cuisine): ?>
            <!-- FIX: Use cuisineTypeID instead of cuisineType -->
            <option value="<?php echo htmlspecialchars($cuisine['cuisineTypeID']); ?>">
                <?php echo htmlspecialchars($cuisine['cuisineType']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>  

<div class="mb-6">
    <label for="foodType" class="block font-semibold mb-2 required-field">Food Type</label>
    <select id="foodType" name="foodType" required class="w-full p-3 border border-border-color rounded-lg bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/50">
        <option value="">Select food type</option>
        <?php foreach ($foodTypes as $foodType): ?>
            <!-- Change this line to use ID instead of name -->
            <option value="<?php echo htmlspecialchars($foodType['foodTypeID']); ?>">
                <?php echo htmlspecialchars($foodType['foodType']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mb-6">
    <label for="dietPref" class="block font-semibold mb-2 required-field">Diet Preferences</label>
    <select id="dietPref" name="dietPref" required class="w-full p-3 border border-border-color rounded-lg bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/50">
        <option value="">Select diet preference</option>
        <?php foreach ($dietPreferences as $diet): ?>
            <!-- Change this line to use ID instead of name -->
            <option value="<?php echo htmlspecialchars($diet['dietID']); ?>">
                <?php echo htmlspecialchars($diet['dietName']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
                </div>

                <!-- Step 2: Ingredients & Instructions -->
                <div class="form-step hidden" data-step="2">
                    <h2 class="text-primary text-2xl mb-6 flex items-center gap-2"><i class="fas fa-list"></i> Ingredients</h2>
                    <p class="text-medium-gray mb-6">List all ingredients with quantities and measurements (one ingredient per line)</p>
                    
                    <div class="mb-6">
                        <label for="ingredients" class="block font-semibold mb-2 required-field">Ingredients</label>
                        <textarea id="ingredients" name="ingredient" required 
                            placeholder="Example:&#10;1 cup all-purpose flour&#10;2 large eggs&#10;1/2 teaspoon salt&#10;1 tablespoon olive oil"
                            class="w-full p-3 border border-border-color rounded-lg bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/50"></textarea>
                    </div>

                    <h2 class="text-primary text-2xl mb-6 flex items-center gap-2"><i class="fas fa-clipboard-list"></i> Cooking Instructions</h2>
                    <p class="text-medium-gray mb-6">Provide detailed step-by-step cooking instructions</p>
                    
                    <div class="mb-6">
                        <label for="recipeDescription" class="block font-semibold mb-2 required-field">Instructions</label>
                        <textarea id="recipeDescription" name="recipeDescription" required 
                            placeholder="Enter step-by-step cooking instructions...
                    Example:
                    1. Preheat oven to 350°F
                    2. Mix dry ingredients
                    3. Add wet ingredients and mix well
                    4. Bake for 30 minutes"
                            class="w-full p-3 border border-border-color rounded-lg bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/50 h-96"></textarea>
                        <div class="text-medium-gray text-sm mt-1">Enter detailed step-by-step cooking instructions</div>
                    </div>


                </div>

                <!-- Form Navigation -->
                <div class="flex justify-between mt-8 pt-5 border-t border-border-color">
                    <button type="button" id="prevBtn" class="nav-btn bg-light-pink text-text-color px-6 py-3 rounded-lg font-medium flex items-center gap-2 hover:bg-medium-pink transition-colors">
                        <i class="fas fa-arrow-left"></i> Previous
                    </button>
                    <button type="button" id="nextBtn" class="nav-btn bg-primary text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 hover:bg-medium-pink transition-colors">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="submit" id="submitBtn" class="nav-btn bg-primary text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 hover:bg-medium-pink transition-colors hidden">
                        <i class="fas fa-upload"></i> Share Recipe
                    </button>
                </div>
            </form>
        </div>
    </section>

    <?php include 'footer.php'; ?>
    
    <!-- Scripts -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        // Upload Recipe Page Functionality
        let currentStep = 1;
        const totalSteps = 2;
        let quill = null;

        // Initialize page when DOM is loaded
        document.addEventListener("DOMContentLoaded", function() {
            initializeUploadPage();
        });

        function initializeUploadPage() {
            showStep(1);
            initializeQuillEditor();
            setupFormValidation();
            setupFormSubmission();
            setupImageUploadHandlers();
            setupNavigationButtons();
        }

        // Initialize Quill rich text editor
        function initializeQuillEditor() {
            try {
                const toolbarOptions = [
                    ["bold", "italic", "underline"],
                    [{ list: "ordered" }, { list: "bullet" }],
                    [{ header: [1, 2, 3, false] }],
                    ["clean"],
                ];

                quill = new Quill("#instructionsEditor", {
                    theme: "snow",
                    modules: {
                        toolbar: toolbarOptions,
                    },
                    placeholder: "Enter detailed cooking instructions...",
                });

                // Update hidden input when content changes
                quill.on("text-change", function() {
                    const instructionsInput = document.getElementById("instructions");
                    if (instructionsInput) {
                        instructionsInput.value = quill.root.innerHTML;
                    }
                });
            } catch (error) {
                console.error("Error initializing Quill editor:", error);
            }
        }

        // Step navigation
        function changeStep(direction) {
            const newStep = currentStep + direction;

            if (direction > 0 && !validateCurrentStep()) {
                return;
            }

            if (newStep >= 1 && newStep <= totalSteps) {
                showStep(newStep);
            }
        }

        function showStep(step) {
            // Hide all steps first
            const allSteps = document.querySelectorAll(".form-step");
            allSteps.forEach(function(stepEl) {
                stepEl.classList.add("hidden");
                stepEl.classList.remove("active");
            });

            // Show current step
            const currentStepEl = document.querySelector(`.form-step[data-step="${step}"]`);
            if (currentStepEl) {
                currentStepEl.classList.remove("hidden");
                currentStepEl.classList.add("active");
            }

            // Update progress
            updateProgress(step);
            updateNavigationButtons(step);
            currentStep = step;

            // Scroll to top of form
            const formSection = document.querySelector(".form-step.active");
            if (formSection) {
                formSection.scrollIntoView({ behavior: "smooth", block: "start" });
            }
        }

        function updateProgress(step) {
            document.querySelectorAll(".progress-step").forEach(function(stepEl, index) {
                const stepNumber = stepEl.querySelector(".step-number");
                
                // Reset all steps first
                stepEl.classList.remove("active", "completed");
                if (stepNumber) {
                    stepNumber.classList.remove("bg-primary", "text-white", "bg-green-500");
                    stepNumber.classList.add("bg-white", "text-primary");
                }

                // Set current step
                if (index + 1 === step) {
                    stepEl.classList.add("active");
                    if (stepNumber) {
                        stepNumber.classList.remove("bg-white", "text-primary");
                        stepNumber.classList.add("bg-primary", "text-white");
                    }
                } 
                // Set completed steps
                else if (index + 1 < step) {
                    stepEl.classList.add("completed");
                    if (stepNumber) {
                        stepNumber.classList.remove("bg-white", "text-primary");
                        stepNumber.classList.add("bg-green-500", "text-white");
                    }
                }
            });
        }

        function updateNavigationButtons(step) {
            const prevBtn = document.getElementById("prevBtn");
            const nextBtn = document.getElementById("nextBtn");
            const submitBtn = document.getElementById("submitBtn");

            if (prevBtn) {
                if (step === 1) {
                    prevBtn.classList.add("hidden");
                } else {
                    prevBtn.classList.remove("hidden");
                }
            }

            if (nextBtn && submitBtn) {
                if (step === totalSteps) {
                    nextBtn.classList.add("hidden");
                    submitBtn.classList.remove("hidden");
                } else {
                    nextBtn.classList.remove("hidden");
                    submitBtn.classList.add("hidden");
                }
            }
        }

        // Form validation
        function validateCurrentStep() {
            const currentStepEl = document.querySelector(`.form-step.active`);
            if (!currentStepEl) return false;

            const requiredFields = currentStepEl.querySelectorAll("[required]");
            let isValid = true;

            requiredFields.forEach(function(field) {
                if (field.type === 'file') {
                    // File input validation
                    if (!field.files || field.files.length === 0) {
                        markFieldAsInvalid(field);
                        isValid = false;
                    } else {
                        markFieldAsValid(field);
                    }
                } else if (!field.value.trim()) {
                    markFieldAsInvalid(field);
                    isValid = false;
                } else {
                    markFieldAsValid(field);
                }
            });

            // Additional validation for specific steps
            switch (currentStep) {
                case 1:
                    // Additional validation for step 1 if needed
                    break;
                case 2:
                    // Validate ingredients textarea
                    const ingredientsTextarea = document.getElementById("ingredients");
                    if (ingredientsTextarea && !ingredientsTextarea.value.trim()) {
                        markFieldAsInvalid(ingredientsTextarea);
                        isValid = false;
                    } else if (ingredientsTextarea) {
                        markFieldAsValid(ingredientsTextarea);
                    }
                    
                    // Validate Quill content
                    const instructionsInput = document.getElementById("instructions");
                    if (quill && quill.getText().trim().length === 0) {
                        showValidationError("Please add cooking instructions in the rich text editor.");
                        isValid = false;
                    } else if (instructionsInput) {
                        markFieldAsValid(instructionsInput);
                    }
                    break;
            }

            if (!isValid) {
                showValidationError("Please fill in all required fields correctly.");
            }

            return isValid;
        }

        function markFieldAsInvalid(field) {
            field.classList.add("border-red-500", "ring-2", "ring-red-500");
            // For file inputs, also mark the parent container
            if (field.type === 'file') {
                field.closest('.image-upload-slot').classList.add("border-red-500", "ring-2", "ring-red-500");
            }
        }

        function markFieldAsValid(field) {
            field.classList.remove("border-red-500", "ring-2", "ring-red-500");
            // For file inputs, also mark the parent container
            if (field.type === 'file') {
                field.closest('.image-upload-slot').classList.remove("border-red-500", "ring-2", "ring-red-500");
            }
        }

        function showValidationError(message) {
            // Remove existing error
            const existingError = document.querySelector(".validation-error");
            if (existingError) {
                existingError.remove();
            }

            // Create new error message
            const errorEl = document.createElement("div");
            errorEl.className = "validation-error bg-red-100 text-red-600 p-3 rounded-lg mb-5 border-l-4 border-red-600";
            errorEl.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i> ${message}`;

            const currentStepEl = document.querySelector(".form-step.active");
            if (currentStepEl) {
                currentStepEl.insertBefore(errorEl, currentStepEl.firstChild);
            }

            // Remove error after 5 seconds
            setTimeout(function() {
                if (errorEl.parentNode) {
                    errorEl.remove();
                }
            }, 5000);
        }

        function setupFormValidation() {
            // Remove error styling on input
            document.addEventListener("input", function(e) {
                if (e.target.hasAttribute('required')) {
                    markFieldAsValid(e.target);
                }
            });

            // Remove error styling on file change
            document.addEventListener("change", function(e) {
                if (e.target.type === 'file' && e.target.hasAttribute('required')) {
                    if (e.target.files && e.target.files.length > 0) {
                        markFieldAsValid(e.target);
                    }
                }
            });
        }

        // Image upload handling
        function setupImageUploadHandlers() {
            for (let i = 1; i <= 3; i++) {
                const input = document.getElementById(`image${i}`);
                if (input) {
                    input.addEventListener('change', function() {
                        handleImageUpload(i);
                    });
                }
                
                const removeBtn = document.querySelector(`[data-slot="${i}"] .remove-image`);
                if (removeBtn) {
                    removeBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        removeImage(i);
                    });
                }
            }
        }

        function handleImageUpload(slotNumber) {
            const input = document.getElementById(`image${slotNumber}`);
            if (!input || !input.files || !input.files[0]) return;

            const file = input.files[0];

            if (!validateImageFile(file)) {
                input.value = "";
                markFieldAsInvalid(input);
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                showImagePreview(slotNumber, e.target.result);
                markFieldAsValid(input);
            };
            reader.readAsDataURL(file);
        }

        function validateImageFile(file) {
            const allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
            if (!allowedTypes.includes(file.type)) {
                alert("Please upload a valid image file (JPG, PNG, or WebP).");
                return false;
            }

            const maxSize = 5 * 1024 * 1024;
            if (file.size > maxSize) {
                alert("Image file size must be less than 5MB.");
                return false;
            }

            return true;
        }

        function showImagePreview(slotNumber, imageSrc) {
            const slot = document.querySelector(`[data-slot="${slotNumber}"]`);
            if (!slot) return;

            const placeholder = slot.querySelector(".upload-placeholder");
            const preview = slot.querySelector(".image-preview");
            const img = preview.querySelector("img");

            if (img && placeholder && preview) {
                img.src = imageSrc;
                placeholder.classList.add("hidden");
                preview.classList.remove("hidden");
            }
        }

        function removeImage(slotNumber) {
            const slot = document.querySelector(`[data-slot="${slotNumber}"]`);
            if (!slot) return;

            const placeholder = slot.querySelector(".upload-placeholder");
            const preview = slot.querySelector(".image-preview");
            const input = document.getElementById(`image${slotNumber}`);

            if (input && preview && placeholder) {
                input.value = "";
                preview.classList.add("hidden");
                placeholder.classList.remove("hidden");
                markFieldAsInvalid(input);
            }
        }

        // Form submission
        function setupFormSubmission() {
            const form = document.getElementById("uploadRecipeForm");
            if (form) {
                form.addEventListener("submit", function(e) {
                    // Validate before submitting
                    if (!validateBeforeSubmit()) {
                        e.preventDefault();
                        return;
                    }
                    
                    // Show loading state
                    const submitBtn = document.getElementById("submitBtn");
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
                        submitBtn.disabled = true;
                    }
                });
            }
        }

        function validateBeforeSubmit() {
            // Validate all steps
            for (let step = 1; step <= totalSteps; step++) {
                const stepEl = document.querySelector(`.form-step[data-step="${step}"]`);
                if (stepEl) {
                    const requiredFields = stepEl.querySelectorAll("[required]");
                    let stepValid = true;
                    
                    requiredFields.forEach(function(field) {
                        if (field.type === 'file') {
                            if (!field.files || field.files.length === 0) {
                                markFieldAsInvalid(field);
                                stepValid = false;
                            }
                        } else if (!field.value.trim()) {
                            markFieldAsInvalid(field);
                            stepValid = false;
                        }
                    });
                    
                    if (!stepValid) {
                        showStep(step);
                        showValidationError("Please complete all required fields before submitting.");
                        return false;
                    }
                }
            }
            
            // Final validation for ingredients
            const ingredientsTextarea = document.getElementById("ingredients");
            if (ingredientsTextarea && !ingredientsTextarea.value.trim()) {
                showStep(2);
                showValidationError("Please add ingredients.");
                return false;
            }
            
            // Final validation for Quill editor
            if (quill && quill.getText().trim().length === 0) {
                showStep(2);
                showValidationError("Please add cooking instructions in the rich text editor.");
                return false;
            }
            
            return true;
        }

        // Set up navigation buttons
        function setupNavigationButtons() {
            const prevBtn = document.getElementById("prevBtn");
            const nextBtn = document.getElementById("nextBtn");

            if (prevBtn) {
                prevBtn.addEventListener('click', function() {
                    changeStep(-1);
                });
            }
            
            if (nextBtn) {
                nextBtn.addEventListener('click', function() {
                    changeStep(1);
                });
            }
        }

    </script>
</body>
</html>