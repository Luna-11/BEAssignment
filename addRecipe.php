<?php
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
        canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
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

            <form id="uploadRecipeForm" class="p-6 sm:p-8">
                <!-- Step 1: Recipe Details -->
                <div class="form-step active" data-step="1">
                    <h2 class="text-primary text-2xl mb-6 flex items-center gap-2"><i class="fas fa-info-circle"></i> Recipe Details</h2>
                    
                    <div class="mb-6">
                        <label for="recipeTitle" class="block font-semibold mb-2">Recipe Title *</label>
                        <input type="text" id="recipeTitle" name="recipeTitle" required 
                               placeholder="Enter your recipe title" class="w-full p-3 border border-border-color rounded-lg bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/50">
                        <div class="text-medium-gray text-sm mt-1">Give your recipe a catchy and descriptive title</div>
                    </div>

                    <div class="mb-6">
                        <label for="difficulty" class="block font-semibold mb-2">Difficulty Level *</label>
                        <select id="difficulty" name="difficulty" required class="w-full p-3 border border-border-color rounded-lg bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/50">
                            <option value="">Select difficulty</option>
                            <option value="easy">Easy</option>
                            <option value="medium">Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                    </div>

                    <!-- Image Upload Section -->
                    <h2 class="text-primary text-2xl mb-6 flex items-center gap-2"><i class="fas fa-images"></i> Recipe Images</h2>
                    <p class="text-medium-gray mb-6">Upload up to 3 high-quality images of your recipe</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
                        <div class="image-upload-slot border-2 border-dashed border-border-color rounded-lg h-48 flex items-center justify-center relative overflow-hidden cursor-pointer hover:border-primary" data-slot="1">
                            <input type="file" id="image1" name="image1" accept="image/*" class="absolute w-full h-full opacity-0 cursor-pointer">
                            <div class="upload-placeholder text-center text-medium-gray">
                                <i class="fas fa-camera text-5xl mb-2 text-light-gray"></i>
                                <span class="block">Main Image</span>
                                <small>Click to upload</small>
                            </div>
                            <div class="image-preview hidden absolute inset-0">
                                <img src="/placeholder.svg" alt="Preview" class="w-full h-full object-cover">
                                <button type="button" class="remove-image absolute top-1 right-1 bg-black/70 text-white rounded-full w-8 h-8 flex items-center justify-center">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="image-upload-slot border-2 border-dashed border-border-color rounded-lg h-48 flex items-center justify-center relative overflow-hidden cursor-pointer hover:border-primary" data-slot="2">
                            <input type="file" id="image2" name="image2" accept="image/*" class="absolute w-full h-full opacity-0 cursor-pointer">
                            <div class="upload-placeholder text-center text-medium-gray">
                                <i class="fas fa-camera text-5xl mb-2 text-light-gray"></i>
                                <span class="block">Additional Image</span>
                                <small>Click to upload</small>
                            </div>
                            <div class="image-preview hidden absolute inset-0">
                                <img src="/placeholder.svg" alt="Preview" class="w-full h-full object-cover">
                                <button type="button" class="remove-image absolute top-1 right-1 bg-black/70 text-white rounded-full w-8 h-8 flex items-center justify-center">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="image-upload-slot border-2 border-dashed border-border-color rounded-lg h-48 flex items-center justify-center relative overflow-hidden cursor-pointer hover:border-primary" data-slot="3">
                            <input type="file" id="image3" name="image3" accept="image/*" class="absolute w-full h-full opacity-0 cursor-pointer">
                            <div class="upload-placeholder text-center text-medium-gray">
                                <i class="fas fa-camera text-5xl mb-2 text-light-gray"></i>
                                <span class="block">Additional Image</span>
                                <small>Click to upload</small>
                            </div>
                            <div class="image-preview hidden absolute inset-0">
                                <img src="/placeholder.svg" alt="Preview" class="w-full h-full object-cover">
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
                            <li>First image will be used as the main recipe photo</li>
                        </ul>
                    </div>

                    <!-- Categories Section -->
                    <h2 class="text-primary text-2xl my-6 flex items-center gap-2"><i class="fas fa-tags"></i> Categories & Tags</h2>
                    
                    <div class="mb-6">
                        <label for="country" class="block font-semibold mb-2">Country/Cuisine *</label>
                        <select id="country" name="country" required class="w-full p-3 border border-border-color rounded-lg bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/50">
                            <option value="">Select country/cuisine</option>
                            <option value="italian">Italian</option>
                            <option value="chinese">Chinese</option>
                            <option value="japanese">Japanese</option>
                            <option value="korean">Korean</option>
                            <option value="thai">Thai</option>
                            <option value="indian">Indian</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="foodType" class="block font-semibold mb-2">Food Type *</label>
                        <select id="foodType" name="foodType" required multiple class="w-full p-3 border border-border-color rounded-lg bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/50">
                            <option value="">Select food type(s)</option>
                            <!-- Options will be populated based on country selection -->
                        </select>
                        <div class="text-medium-gray text-sm mt-1">Hold Ctrl/Cmd to select multiple types</div>
                    </div>

                    <div class="selected-categories mb-6">
                        <h4 class="font-semibold mb-2">Selected Categories:</h4>
                        <div id="selectedCategoriesDisplay" class="flex flex-wrap gap-2">
                            <span class="no-selection italic text-medium-gray">No categories selected yet</span>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Ingredients & Instructions -->
                <div class="form-step hidden" data-step="2">
                    <h2 class="text-primary text-2xl mb-6 flex items-center gap-2"><i class="fas fa-list"></i> Ingredients</h2>
                    <p class="text-medium-gray mb-6">List all ingredients with quantities and measurements</p>
                    
                    <div class="mb-6">
                        <div class="ingredients-list" id="ingredientsList">
                            <div class="ingredient-item flex flex-col sm:flex-row gap-4 mb-4" data-index="1">
                                <input type="text" class="ingredient-amount flex-1 p-3 border border-border-color rounded-lg bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/50" placeholder="1 cup" name="ingredientAmount[]">
                                <input type="text" class="ingredient-name flex-1 p-3 border border-border-color rounded-lg bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/50" placeholder="all-purpose flour" name="ingredientName[]" required>
                                <button type="button" class="remove-ingredient bg-red-500 text-white p-3 rounded-lg w-full sm:w-auto flex items-center justify-center">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        
                        <button type="button" class="add-ingredient-btn bg-green-500 text-white px-4 py-3 rounded-lg font-medium flex items-center gap-2 hover:bg-green-600 transition-colors">
                            <i class="fas fa-plus"></i> Add Ingredient
                        </button>
                    </div>

                    <h2 class="text-primary text-2xl mb-6 flex items-center gap-2"><i class="fas fa-clipboard-list"></i> Cooking Instructions</h2>
                    <p class="text-medium-gray mb-6">Provide detailed step-by-step cooking instructions</p>
                    
                    <div class="mb-6">
                        <label for="instructions" class="block font-semibold mb-2">Instructions *</label>
                        <div id="instructionsEditor" class="h-96 mb-2"></div>
                        <input type="hidden" id="instructions" name="instructions" required>
                        <div class="text-medium-gray text-sm mt-1">Use the rich text editor to format your instructions with bold, italics, lists, and more</div>
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

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 bg-black/50 hidden z-50 overflow-auto flex items-center justify-center">
        <div class="bg-lightest m-4 p-6 sm:p-8 rounded-2xl w-full max-w-xl sm:max-w-2xl relative">
            <span class="close absolute top-4 right-4 text-3xl font-bold cursor-pointer text-medium-gray">&times;</span>
            <h2 class="text-primary text-2xl mb-6">Recipe Preview</h2>
            <div id="recipePreview" class="text-text-color">
                <!-- Preview content will be generated here -->
            </div>
            <div class="preview-actions flex justify-end gap-4 mt-6">
                <button type="button" class="btn bg-light-pink text-text-color px-6 py-3 rounded-lg font-medium hover:bg-medium-pink transition-colors">Edit Recipe</button>
                <button type="button" class="btn bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-medium-pink transition-colors">Confirm & Share</button>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    
    <!-- Scripts -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        // Upload Recipe Page Functionality

        // Global variables
        let currentStep = 1;
        const totalSteps = 2;
        let quill = null; // Rich text editor instance
        let ingredientCounter = 1;
        const uploadedImages = {};

        // Food type categories based on country/cuisine
        const foodTypesByCountry = {
            italian: ["pasta", "pizza", "risotto", "soup", "salad", "dessert", "appetizer", "main-course", "bread"],
            chinese: ["stir-fry", "soup", "noodles", "rice", "dumpling", "appetizer", "main-course", "dessert", "tea"],
            japanese: ["sushi", "ramen", "tempura", "soup", "rice", "noodles", "appetizer", "main-course", "dessert"],
            korean: ["kimchi", "bbq", "soup", "rice", "noodles", "stir-fry", "appetizer", "main-course", "dessert"],
            thai: ["curry", "stir-fry", "soup", "salad", "noodles", "rice", "appetizer", "main-course", "dessert"],
            indian: ["curry", "rice", "bread", "lentils", "vegetarian", "appetizer", "main-course", "dessert", "beverage"],
            other: ["appetizer", "main-course", "dessert", "soup", "salad", "beverage", "snack", "bread"],
        };

        // Initialize page when DOM is loaded
        document.addEventListener("DOMContentLoaded", function() {
            initializeUploadPage();
        });

        function initializeUploadPage() {
            console.log("Initializing upload page...");

            try {
                // Show first step immediately
                showStep(1);

                // Initialize Quill editor
                initializeQuillEditor();

                // Set up form validation
                setupFormValidation();

                // Set up form submission
                setupFormSubmission();

                // Set up category change listeners
                setupCategoryListeners();

                // Set up image upload handlers
                setupImageUploadHandlers();

                // Set up ingredient management
                setupIngredientManagement();

                // Set up navigation buttons
                setupNavigationButtons();

                console.log("Upload recipe page initialized successfully");
            } catch (error) {
                console.error("Error initializing upload page:", error);
            }
        }

        // Initialize Quill rich text editor
        function initializeQuillEditor() {
            try {
                const editorElement = document.getElementById("instructionsEditor");
                if (!editorElement) {
                    console.error("Instructions editor element not found");
                    return;
                }

                if (typeof window.Quill === "undefined") {
                    console.error("Quill is not loaded");
                    return;
                }

                const toolbarOptions = [
                    ["bold", "italic", "underline"],
                    [{ list: "ordered" }, { list: "bullet" }],
                    [{ header: [1, 2, 3, false] }],
                    ["clean"],
                ];

                quill = new window.Quill("#instructionsEditor", {
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

                console.log("Quill editor initialized successfully");
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
            console.log(`Showing step ${step}`);

            // Hide all steps first
            const allSteps = document.querySelectorAll(".form-step");
            allSteps.forEach(function(stepEl) {
                stepEl.classList.remove("active");
                stepEl.classList.add("hidden");
            });

            // Show current step
            const currentStepEl = document.querySelector(`.form-step[data-step="${step}"]`);
            if (currentStepEl) {
                currentStepEl.classList.add("active");
                currentStepEl.classList.remove("hidden");
                console.log(`Step ${step} is now visible`);
            } else {
                console.error(`Step element not found for step ${step}`);
            }

            // Update progress
            updateProgress(step);

            // Update navigation buttons
            updateNavigationButtons(step);

            currentStep = step;

            // Scroll to top
            const uploadSection = document.querySelector(".py-10");
            if (uploadSection) {
                uploadSection.scrollIntoView({ behavior: "smooth" });
            }
        }

        function updateProgress(step) {
            document.querySelectorAll(".progress-step").forEach(function(stepEl, index) {
                const stepNumber = stepEl.querySelector(".step-number");
                stepEl.classList.remove("active", "completed");
                if (stepNumber) stepNumber.classList.remove("bg-primary", "text-white", "bg-green-500");

                if (index + 1 === step) {
                    stepEl.classList.add("active");
                    if (stepNumber) stepNumber.classList.add("bg-primary", "text-white");
                } else if (index + 1 < step) {
                    stepEl.classList.add("completed");
                    if (stepNumber) stepNumber.classList.add("bg-green-500", "text-white");
                } else {
                    if (stepNumber) stepNumber.classList.add("bg-white", "text-primary");
                }
            });
        }

        function updateNavigationButtons(step) {
            const prevBtn = document.getElementById("prevBtn");
            const nextBtn = document.getElementById("nextBtn");
            const submitBtn = document.getElementById("submitBtn");

            if (prevBtn) {
                prevBtn.classList.toggle("hidden", step === 1);
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
            const currentStepEl = document.querySelector(`[data-step="${currentStep}"]`);
            if (!currentStepEl) return false;

            const requiredFields = currentStepEl.querySelectorAll("[required]");
            let isValid = true;

            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    field.classList.add("border-red-500");
                    isValid = false;
                } else {
                    field.classList.remove("border-red-500");
                }
            });

            // Additional validation for specific steps
            switch (currentStep) {
                case 2:
                    isValid = validateIngredients() && isValid;
                    break;
            }

            if (!isValid) {
                showValidationError("Please fill in all required fields correctly.");
            }

            return isValid;
        }

        function validateIngredients() {
            const ingredientNames = document.querySelectorAll(".ingredient-name");
            let hasValidIngredient = false;

            ingredientNames.forEach(function(input) {
                if (input.value.trim()) {
                    hasValidIngredient = true;
                }
            });

            if (!hasValidIngredient) {
                showValidationError("Please add at least one ingredient.");
                return false;
            }

            return true;
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
            errorEl.textContent = message;

            const currentStepEl = document.querySelector(`[data-step="${currentStep}"]`);
            if (currentStepEl && currentStepEl.firstChild) {
                currentStepEl.insertBefore(errorEl, currentStepEl.firstChild.nextSibling);
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
                if (e.target.classList.contains("border-red-500")) {
                    e.target.classList.remove("border-red-500");
                }
            });
        }

        // Image upload handling
        function setupImageUploadHandlers() {
            // Set up file input change handlers
            for (let i = 1; i <= 3; i++) {
                const input = document.getElementById(`image${i}`);
                if (input) {
                    input.addEventListener('change', function() {
                        handleImageUpload(i);
                    });
                }
                
                // Set up remove image button handlers
                const removeBtn = document.querySelector(`[data-slot="${i}"] .remove-image`);
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        removeImage(i);
                    });
                }
            }
        }

        function handleImageUpload(slotNumber) {
            const input = document.getElementById(`image${slotNumber}`);
            if (!input || !input.files || !input.files[0]) return;

            const file = input.files[0];

            // Validate file
            if (!validateImageFile(file)) {
                input.value = "";
                return;
            }

            // Create preview
            const reader = new FileReader();
            reader.onload = function(e) {
                showImagePreview(slotNumber, e.target.result, file);
            };
            reader.readAsDataURL(file);
        }

        function validateImageFile(file) {
            // Check file type
            const allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
            if (!allowedTypes.includes(file.type)) {
                alert("Please upload a valid image file (JPG, PNG, or WebP).");
                return false;
            }

            // Check file size (5MB limit)
            const maxSize = 5 * 1024 * 1024; // 5MB in bytes
            if (file.size > maxSize) {
                alert("Image file size must be less than 5MB.");
                return false;
            }

            return true;
        }

        function showImagePreview(slotNumber, imageSrc, file) {
            const slot = document.querySelector(`[data-slot="${slotNumber}"]`);
            if (!slot) return;

            const placeholder = slot.querySelector(".upload-placeholder");
            const preview = slot.querySelector(".image-preview");
            const img = preview.querySelector("img");

            if (img && placeholder && preview) {
                img.src = imageSrc;
                placeholder.classList.add("hidden");
                preview.classList.remove("hidden");

                // Store file reference
                uploadedImages[slotNumber] = file;
            }
        }

        function removeImage(slotNumber) {
            const slot = document.querySelector(`[data-slot="${slotNumber}"]`);
            if (!slot) return;

            const placeholder = slot.querySelector(".upload-placeholder");
            const preview = slot.querySelector(".image-preview");
            const input = document.getElementById(`image${slotNumber}`);

            if (input && preview && placeholder) {
                // Reset input and preview
                input.value = "";
                preview.classList.add("hidden");
                placeholder.classList.remove("hidden");

                // Remove from uploaded images
                delete uploadedImages[slotNumber];
            }
        }

        // Country and food type handling
        function updateFoodTypes() {
            const countrySelect = document.getElementById("country");
            const foodTypeSelect = document.getElementById("foodType");

            if (!countrySelect || !foodTypeSelect) return;

            const selectedCountry = countrySelect.value;

            // Clear existing options
            foodTypeSelect.innerHTML = '<option value="">Select food type(s)</option>';

            if (selectedCountry && foodTypesByCountry[selectedCountry]) {
                const foodTypes = foodTypesByCountry[selectedCountry];

                foodTypes.forEach(function(type) {
                    const option = document.createElement("option");
                    option.value = type;
                    option.textContent = type.charAt(0).toUpperCase() + type.slice(1).replace("-", " ");
                    foodTypeSelect.appendChild(option);
                });
            }

            updateSelectedCategories();
        }

        function updateSelectedCategories() {
            const countrySelect = document.getElementById("country");
            const foodTypeSelect = document.getElementById("foodType");
            const display = document.getElementById("selectedCategoriesDisplay");

            if (!display) return;

            const country = countrySelect ? countrySelect.value : "";
            const foodTypes = foodTypeSelect ? Array.from(foodTypeSelect.selectedOptions).map(function(option) {
                return option.value;
            }) : [];

            display.innerHTML = "";

            let hasCategories = false;

            // Add country
            if (country) {
                const tag = createCategoryTag(country, "country");
                display.appendChild(tag);
                hasCategories = true;
            }

            // Add food types
            foodTypes.forEach(function(type) {
                const tag = createCategoryTag(type, "food-type");
                display.appendChild(tag);
                hasCategories = true;
            });

            if (!hasCategories) {
                display.innerHTML = '<span class="no-selection italic text-medium-gray">No categories selected yet</span>';
            }
        }

        function createCategoryTag(value, type) {
            const tag = document.createElement("span");
            tag.className = "category-tag bg-light-pink text-text-color px-3 py-1 rounded-full text-sm flex items-center gap-1";
            tag.innerHTML = `
                ${value.charAt(0).toUpperCase() + value.slice(1).replace("-", " ")}
                <button type="button" class="remove-tag text-text-color">×</button>
            `;
            
            // Add event listener to remove button
            const removeBtn = tag.querySelector('.remove-tag');
            removeBtn.addEventListener('click', function() {
                removeCategory(value, type);
            });
            
            return tag;
        }

        function removeCategory(value, type) {
            switch (type) {
                case "country":
                    const countrySelect = document.getElementById("country");
                    if (countrySelect) {
                        countrySelect.value = "";
                        updateFoodTypes();
                    }
                    break;
                case "food-type":
                    const foodTypeSelect = document.getElementById("foodType");
                    if (foodTypeSelect) {
                        Array.from(foodTypeSelect.options).forEach(function(option) {
                            if (option.value === value) {
                                option.selected = false;
                            }
                        });
                    }
                    break;
            }
            updateSelectedCategories();
        }

        // Set up category change listeners
        function setupCategoryListeners() {
            // Country selection
            const countrySelect = document.getElementById("country");
            if (countrySelect) {
                countrySelect.addEventListener("change", updateFoodTypes);
            }

            // Food type selection
            const foodTypeSelect = document.getElementById("foodType");
            if (foodTypeSelect) {
                foodTypeSelect.addEventListener("change", updateSelectedCategories);
            }
        }

        // Ingredients management
        function setupIngredientManagement() {
            // Add ingredient button
            const addBtn = document.querySelector('.add-ingredient-btn');
            if (addBtn) {
                addBtn.addEventListener('click', addIngredient);
            }
            
            // Set up initial remove button
            const initialRemoveBtn = document.querySelector('.remove-ingredient');
            if (initialRemoveBtn) {
                initialRemoveBtn.addEventListener('click', function() {
                    removeIngredient(1);
                });
            }
        }

        function addIngredient() {
            ingredientCounter++;
            const ingredientsList = document.getElementById("ingredientsList");

            if (!ingredientsList) return;

            const ingredientItem = document.createElement("div");
            ingredientItem.className = "ingredient-item flex flex-col sm:flex-row gap-4 mb-4";
            ingredientItem.setAttribute("data-index", ingredientCounter);

            ingredientItem.innerHTML = `
                <input type="text" class="ingredient-amount flex-1 p-3 border border-border-color rounded-lg bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/50" placeholder="1 cup" name="ingredientAmount[]">
                <input type="text" class="ingredient-name flex-1 p-3 border border-border-color rounded-lg bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/50" placeholder="ingredient name" name="ingredientName[]" required>
                <button type="button" class="remove-ingredient bg-red-500 text-white p-3 rounded-lg w-full sm:w-auto flex items-center justify-center">
                    <i class="fas fa-trash"></i>
                </button>
            `;

            ingredientsList.appendChild(ingredientItem);

            // Add event listener to the new remove button
            const removeBtn = ingredientItem.querySelector('.remove-ingredient');
            removeBtn.addEventListener('click', function() {
                removeIngredient(ingredientCounter);
            });

            // Focus on the new ingredient name field
            const newNameField = ingredientItem.querySelector(".ingredient-name");
            if (newNameField) {
                newNameField.focus();
            }
        }

        function removeIngredient(index) {
            const ingredientItem = document.querySelector(`[data-index="${index}"]`);
            if (ingredientItem) {
                ingredientItem.remove();
            }

            // Ensure at least one ingredient remains
            const remainingIngredients = document.querySelectorAll(".ingredient-item");
            if (remainingIngredients.length === 0) {
                addIngredient();
            }
        }

        // Form submission
        function setupFormSubmission() {
            const form = document.getElementById("uploadRecipeForm");
            if (form) {
                form.addEventListener("submit", function(e) {
                    e.preventDefault();
                    showPreviewModal();
                });
            }
        }

        function showPreviewModal() {
            const formData = collectFormData();
            const previewHTML = generatePreviewHTML(formData);

            const previewElement = document.getElementById("recipePreview");
            const modal = document.getElementById("previewModal");

            if (previewElement && modal) {
                previewElement.innerHTML = previewHTML;
                modal.classList.remove("hidden");
            }
        }

        function closePreviewModal() {
            const modal = document.getElementById("previewModal");
            if (modal) {
                modal.classList.add("hidden");
            }
        }

        function collectFormData() {
            const form = document.getElementById("uploadRecipeForm");
            if (!form) return {};

            const formData = new FormData(form);
            const data = {};

            // Basic form data
            for (const [key, value] of formData.entries()) {
                if (data[key]) {
                    if (Array.isArray(data[key])) {
                        data[key].push(value);
                    } else {
                        data[key] = [data[key], value];
                    }
                } else {
                    data[key] = value;
                }
            }

            // Instructions from Quill editor
            if (quill) {
                data.instructions = quill.root.innerHTML;
            }

            // Ingredients
            data.ingredients = [];
            const amounts = document.querySelectorAll(".ingredient-amount");
            const names = document.querySelectorAll(".ingredient-name");

            for (let i = 0; i < names.length; i++) {
                if (names[i].value.trim()) {
                    data.ingredients.push({
                        amount: amounts[i].value.trim(),
                        name: names[i].value.trim(),
                    });
                }
            }

            // Images
            data.images = uploadedImages;

            return data;
        }

        function generatePreviewHTML(data) {
            let imagesHTML = "";
            if (Object.keys(data.images).length > 0) {
                imagesHTML = '<div class="preview-images grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">';
                Object.values(data.images).forEach(function(file) {
                    imagesHTML += `<img src="${URL.createObjectURL(file)}" alt="Recipe image" class="w-full h-40 object-cover rounded-lg">`;
                });
                imagesHTML += "</div>";
            }

            let ingredientsHTML = "<ul class='list-disc pl-5'>";
            data.ingredients.forEach(function(ingredient) {
                ingredientsHTML += `<li>${ingredient.amount} ${ingredient.name}</li>`;
            });
            ingredientsHTML += "</ul>";

            return `
                <div class="recipe-preview-content space-y-6">
                    <h3 class="text-2xl font-bold text-primary">${data.recipeTitle || "Untitled Recipe"}</h3>
                    
                    ${imagesHTML}
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <p><strong>Difficulty:</strong> ${data.difficulty || "Not specified"}</p>
                        <p><strong>Cuisine:</strong> ${data.country || "Not specified"}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-xl font-semibold mb-2">Ingredients:</h4>
                        ${ingredientsHTML}
                    </div>
                    
                    <div>
                        <h4 class="text-xl font-semibold mb-2">Instructions:</h4>
                        <div>${data.instructions || "No instructions provided"}</div>
                    </div>
                </div>
            `;
        }

        function submitRecipe() {
            const formData = collectFormData();

            // In a real application, this would send data to the server
            console.log("Recipe submitted:", formData);

            // Clear draft after successful submission
            clearDraftRecipe();

            // Simulate successful submission
            alert("Recipe submitted successfully! It will be reviewed and published to the community soon.");

            // Redirect to community page
            window.location.href = "community.html";
        }

        // Clear draft after successful submission
        function clearDraftRecipe() {
            localStorage.removeItem("draftRecipe");
        }

        // Set up navigation buttons
        function setupNavigationButtons() {
            const prevBtn = document.getElementById("prevBtn");
            const nextBtn = document.getElementById("nextBtn");
            const closeBtn = document.querySelector('.close');
            const editBtn = document.querySelector('.preview-actions .bg-light-pink');
            const confirmBtn = document.querySelector('.preview-actions .bg-primary');

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
            
            if (closeBtn) {
                closeBtn.addEventListener('click', closePreviewModal);
            }
            
            if (editBtn) {
                editBtn.addEventListener('click', closePreviewModal);
            }
            
            if (confirmBtn) {
                confirmBtn.addEventListener('click', submitRecipe);
            }
        }
    </script>

    <script>
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