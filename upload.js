// Upload Recipe Page Functionality

// Global variables
let currentStep = 1
const totalSteps = 5
let quill // Rich text editor instance
let ingredientCounter = 1
const uploadedImages = {}

// Food type categories based on country/cuisine
const foodTypesByCountry = {
  italian: ["pasta", "pizza", "risotto", "soup", "salad", "dessert", "appetizer", "main-course", "bread"],
  chinese: ["stir-fry", "soup", "noodles", "rice", "dumpling", "appetizer", "main-course", "dessert", "tea"],
  japanese: ["sushi", "ramen", "tempura", "soup", "rice", "noodles", "appetizer", "main-course", "dessert"],
  korean: ["kimchi", "bbq", "soup", "rice", "noodles", "stir-fry", "appetizer", "main-course", "dessert"],
  thai: ["curry", "stir-fry", "soup", "salad", "noodles", "rice", "appetizer", "main-course", "dessert"],
  indian: ["curry", "rice", "bread", "lentils", "vegetarian", "appetizer", "main-course", "dessert", "beverage"],
  other: ["appetizer", "main-course", "dessert", "soup", "salad", "beverage", "snack", "bread"],
}

// Initialize page
document.addEventListener("DOMContentLoaded", () => {
  console.log("DOM loaded, initializing upload page...")

  // Add a longer delay to ensure all elements are rendered
  setTimeout(() => {
    initializeUploadPage()
  }, 500)
})

function initializeUploadPage() {
  console.log("Initializing upload page...")

  try {
    // Show first step immediately
    showStep(1)

    // Check if Quill is available
    if (typeof window.Quill !== "undefined") {
      initializeQuillEditor()
    } else {
      console.warn("Quill editor not loaded, will retry...")
      setTimeout(initializeQuillEditor, 1000)
    }

    // Set up form validation
    setupFormValidation()

    // Initialize character counter
    setupCharacterCounter()

    // Set up form submission
    setupFormSubmission()

    // Set up category change listeners
    setupCategoryListeners()

    console.log("Upload recipe page initialized successfully")
  } catch (error) {
    console.error("Error initializing upload page:", error)
  }
}

// Initialize Quill rich text editor
function initializeQuillEditor() {
  try {
    const editorElement = document.getElementById("instructionsEditor")
    if (!editorElement) {
      console.error("Instructions editor element not found")
      return
    }

    if (typeof window.Quill === "undefined") {
      console.error("Quill is not loaded")
      return
    }

    const toolbarOptions = [
      ["bold", "italic", "underline"],
      [{ list: "ordered" }, { list: "bullet" }],
      [{ header: [1, 2, 3, false] }],
      ["clean"],
    ]

    quill = new window.Quill("#instructionsEditor", {
      theme: "snow",
      modules: {
        toolbar: toolbarOptions,
      },
      placeholder: "Enter detailed cooking instructions...",
    })

    // Update hidden input when content changes
    quill.on("text-change", () => {
      const instructionsInput = document.getElementById("instructions")
      if (instructionsInput) {
        instructionsInput.value = quill.root.innerHTML
      }
    })

    console.log("Quill editor initialized successfully")
  } catch (error) {
    console.error("Error initializing Quill editor:", error)
  }
}

// Character counter for description
function setupCharacterCounter() {
  const descriptionField = document.getElementById("recipeDescription")
  const counter = document.getElementById("descriptionCounter")

  if (descriptionField && counter) {
    descriptionField.addEventListener("input", function () {
      const length = this.value.length
      counter.textContent = length

      const counterElement = counter.parentElement
      if (counterElement) {
        counterElement.classList.remove("warning", "error")

        if (length > 180) {
          counterElement.classList.add("warning")
        }
        if (length > 200) {
          counterElement.classList.add("error")
        }
      }
    })
  }
}

// Step navigation
function changeStep(direction) {
  const newStep = currentStep + direction

  if (direction > 0 && !validateCurrentStep()) {
    return
  }

  if (newStep >= 1 && newStep <= totalSteps) {
    showStep(newStep)
  }
}

function showStep(step) {
  console.log(`Showing step ${step}`)

  // Hide all steps first
  const allSteps = document.querySelectorAll(".form-step")
  allSteps.forEach((stepEl) => {
    stepEl.classList.remove("active")
    stepEl.style.display = "none"
  })

  // Show current step
  const currentStepEl = document.querySelector(`.form-step[data-step="${step}"]`)
  if (currentStepEl) {
    currentStepEl.classList.add("active")
    currentStepEl.style.display = "block"
    console.log(`Step ${step} is now visible`)
  } else {
    console.error(`Step element not found for step ${step}`)
  }

  // Update progress
  updateProgress(step)

  // Update navigation buttons
  updateNavigationButtons(step)

  currentStep = step

  // Scroll to top
  const uploadSection = document.querySelector(".upload-form-section")
  if (uploadSection) {
    uploadSection.scrollIntoView({ behavior: "smooth" })
  }
}

function updateProgress(step) {
  document.querySelectorAll(".progress-step").forEach((stepEl, index) => {
    stepEl.classList.remove("active", "completed")

    if (index + 1 === step) {
      stepEl.classList.add("active")
    } else if (index + 1 < step) {
      stepEl.classList.add("completed")
    }
  })
}

function updateNavigationButtons(step) {
  const prevBtn = document.getElementById("prevBtn")
  const nextBtn = document.getElementById("nextBtn")
  const submitBtn = document.getElementById("submitBtn")

  if (prevBtn) {
    prevBtn.style.display = step === 1 ? "none" : "flex"
  }

  if (nextBtn && submitBtn) {
    if (step === totalSteps) {
      nextBtn.style.display = "none"
      submitBtn.style.display = "flex"
    } else {
      nextBtn.style.display = "flex"
      submitBtn.style.display = "none"
    }
  }
}

// Form validation
function validateCurrentStep() {
  const currentStepEl = document.querySelector(`[data-step="${currentStep}"]`)
  if (!currentStepEl) return false

  const requiredFields = currentStepEl.querySelectorAll("[required]")
  let isValid = true

  requiredFields.forEach((field) => {
    if (!field.value.trim()) {
      field.classList.add("error")
      isValid = false
    } else {
      field.classList.remove("error")
    }
  })

  // Additional validation for specific steps
  switch (currentStep) {
    case 1:
      isValid = validateBasicInfo() && isValid
      break
    case 4:
      isValid = validateIngredients() && isValid
      break
    case 5:
      isValid = validateInstructions() && isValid
      break
  }

  if (!isValid) {
    showValidationError("Please fill in all required fields correctly.")
  }

  return isValid
}

function validateBasicInfo() {
  const description = document.getElementById("recipeDescription")
  if (description && description.value.length > 200) {
    showValidationError("Description must be 200 characters or less.")
    return false
  }
  return true
}

function validateIngredients() {
  const ingredientNames = document.querySelectorAll(".ingredient-name")
  let hasValidIngredient = false

  ingredientNames.forEach((input) => {
    if (input.value.trim()) {
      hasValidIngredient = true
    }
  })

  if (!hasValidIngredient) {
    showValidationError("Please add at least one ingredient.")
    return false
  }

  return true
}

function validateInstructions() {
  if (!quill) {
    showValidationError("Instructions editor not loaded.")
    return false
  }

  const instructions = quill.getText().trim()
  if (instructions.length < 50) {
    showValidationError("Instructions must be at least 50 characters long.")
    return false
  }
  return true
}

function showValidationError(message) {
  // Remove existing error
  const existingError = document.querySelector(".validation-error")
  if (existingError) {
    existingError.remove()
  }

  // Create new error message
  const errorEl = document.createElement("div")
  errorEl.className = "validation-error"
  errorEl.style.cssText = `
    background: #fee;
    color: #c33;
    padding: 10px 15px;
    border-radius: 6px;
    margin-bottom: 20px;
    border-left: 4px solid #c33;
  `
  errorEl.textContent = message

  const currentStepEl = document.querySelector(`[data-step="${currentStep}"]`)
  if (currentStepEl && currentStepEl.firstChild) {
    currentStepEl.insertBefore(errorEl, currentStepEl.firstChild.nextSibling)
  }

  // Remove error after 5 seconds
  setTimeout(() => {
    if (errorEl.parentNode) {
      errorEl.remove()
    }
  }, 5000)
}

function setupFormValidation() {
  // Remove error styling on input
  document.addEventListener("input", (e) => {
    if (e.target.classList.contains("error")) {
      e.target.classList.remove("error")
    }
  })
}

// Image upload handling
function handleImageUpload(slotNumber) {
  const input = document.getElementById(`image${slotNumber}`)
  if (!input || !input.files || !input.files[0]) return

  const file = input.files[0]

  // Validate file
  if (!validateImageFile(file)) {
    input.value = ""
    return
  }

  // Create preview
  const reader = new FileReader()
  reader.onload = (e) => {
    showImagePreview(slotNumber, e.target.result, file)
  }
  reader.readAsDataURL(file)
}

function validateImageFile(file) {
  // Check file type
  const allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/webp"]
  if (!allowedTypes.includes(file.type)) {
    alert("Please upload a valid image file (JPG, PNG, or WebP).")
    return false
  }

  // Check file size (5MB limit)
  const maxSize = 5 * 1024 * 1024 // 5MB in bytes
  if (file.size > maxSize) {
    alert("Image file size must be less than 5MB.")
    return false
  }

  return true
}

function showImagePreview(slotNumber, imageSrc, file) {
  const slot = document.querySelector(`[data-slot="${slotNumber}"]`)
  if (!slot) return

  const placeholder = slot.querySelector(".upload-placeholder")
  const preview = slot.querySelector(".image-preview")
  const img = preview.querySelector("img")

  if (img && placeholder && preview) {
    img.src = imageSrc
    placeholder.style.display = "none"
    preview.style.display = "block"

    // Store file reference
    uploadedImages[slotNumber] = file
  }
}

function removeImage(slotNumber) {
  const slot = document.querySelector(`[data-slot="${slotNumber}"]`)
  if (!slot) return

  const placeholder = slot.querySelector(".upload-placeholder")
  const preview = slot.querySelector(".image-preview")
  const input = document.getElementById(`image${slotNumber}`)

  if (input && preview && placeholder) {
    // Reset input and preview
    input.value = ""
    preview.style.display = "none"
    placeholder.style.display = "flex"

    // Remove from uploaded images
    delete uploadedImages[slotNumber]
  }
}

// Country and food type handling
function updateFoodTypes() {
  const countrySelect = document.getElementById("country")
  const foodTypeSelect = document.getElementById("foodType")

  if (!countrySelect || !foodTypeSelect) return

  const selectedCountry = countrySelect.value

  // Clear existing options
  foodTypeSelect.innerHTML = '<option value="">Select food type(s)</option>'

  if (selectedCountry && foodTypesByCountry[selectedCountry]) {
    const foodTypes = foodTypesByCountry[selectedCountry]

    foodTypes.forEach((type) => {
      const option = document.createElement("option")
      option.value = type
      option.textContent = type.charAt(0).toUpperCase() + type.slice(1).replace("-", " ")
      foodTypeSelect.appendChild(option)
    })
  }

  updateSelectedCategories()
}

function updateSelectedCategories() {
  const countrySelect = document.getElementById("country")
  const foodTypeSelect = document.getElementById("foodType")
  const display = document.getElementById("selectedCategoriesDisplay")

  if (!display) return

  const country = countrySelect ? countrySelect.value : ""
  const foodTypes = foodTypeSelect ? Array.from(foodTypeSelect.selectedOptions).map((option) => option.value) : []
  const dietary = Array.from(document.querySelectorAll('input[name="dietary"]:checked')).map((input) => input.value)

  display.innerHTML = ""

  let hasCategories = false

  // Add country
  if (country) {
    const tag = createCategoryTag(country, "country")
    display.appendChild(tag)
    hasCategories = true
  }

  // Add food types
  foodTypes.forEach((type) => {
    const tag = createCategoryTag(type, "food-type")
    display.appendChild(tag)
    hasCategories = true
  })

  // Add dietary restrictions
  dietary.forEach((diet) => {
    const tag = createCategoryTag(diet, "dietary")
    display.appendChild(tag)
    hasCategories = true
  })

  if (!hasCategories) {
    display.innerHTML = '<span class="no-selection">No categories selected yet</span>'
  }
}

function createCategoryTag(value, type) {
  const tag = document.createElement("span")
  tag.className = "category-tag"
  tag.innerHTML = `
    ${value.charAt(0).toUpperCase() + value.slice(1).replace("-", " ")}
    <button type="button" class="remove-tag" onclick="removeCategory('${value}', '${type}')">×</button>
  `
  return tag
}

function removeCategory(value, type) {
  switch (type) {
    case "country":
      const countrySelect = document.getElementById("country")
      if (countrySelect) {
        countrySelect.value = ""
        updateFoodTypes()
      }
      break
    case "food-type":
      const foodTypeSelect = document.getElementById("foodType")
      if (foodTypeSelect) {
        Array.from(foodTypeSelect.options).forEach((option) => {
          if (option.value === value) {
            option.selected = false
          }
        })
      }
      break
    case "dietary":
      const checkbox = document.querySelector(`input[name="dietary"][value="${value}"]`)
      if (checkbox) checkbox.checked = false
      break
  }
  updateSelectedCategories()
}

// Set up category change listeners
function setupCategoryListeners() {
  // Food type selection
  const foodTypeSelect = document.getElementById("foodType")
  if (foodTypeSelect) {
    foodTypeSelect.addEventListener("change", updateSelectedCategories)
  }

  // Dietary checkboxes
  document.querySelectorAll('input[name="dietary"]').forEach((checkbox) => {
    checkbox.addEventListener("change", updateSelectedCategories)
  })
}

// Ingredients management
function addIngredient() {
  ingredientCounter++
  const ingredientsList = document.getElementById("ingredientsList")

  if (!ingredientsList) return

  const ingredientItem = document.createElement("div")
  ingredientItem.className = "ingredient-item"
  ingredientItem.setAttribute("data-index", ingredientCounter)

  ingredientItem.innerHTML = `
    <div class="ingredient-input-group">
      <input type="text" class="ingredient-amount" placeholder="1 cup" name="ingredientAmount[]">
      <input type="text" class="ingredient-name" placeholder="ingredient name" name="ingredientName[]" required>
      <button type="button" class="remove-ingredient" onclick="removeIngredient(${ingredientCounter})">
        <i class="fas fa-trash"></i>
      </button>
    </div>
  `

  ingredientsList.appendChild(ingredientItem)

  // Focus on the new ingredient name field
  const newNameField = ingredientItem.querySelector(".ingredient-name")
  if (newNameField) {
    newNameField.focus()
  }
}

function removeIngredient(index) {
  const ingredientItem = document.querySelector(`[data-index="${index}"]`)
  if (ingredientItem) {
    ingredientItem.remove()
  }

  // Ensure at least one ingredient remains
  const remainingIngredients = document.querySelectorAll(".ingredient-item")
  if (remainingIngredients.length === 0) {
    addIngredient()
  }
}

// Form submission
function setupFormSubmission() {
  const form = document.getElementById("uploadRecipeForm")
  if (form) {
    form.addEventListener("submit", (e) => {
      e.preventDefault()
      showPreviewModal()
    })
  }
}

function showPreviewModal() {
  const formData = collectFormData()
  const previewHTML = generatePreviewHTML(formData)

  const previewElement = document.getElementById("recipePreview")
  const modal = document.getElementById("previewModal")

  if (previewElement && modal) {
    previewElement.innerHTML = previewHTML
    modal.style.display = "block"
  }
}

function closePreviewModal() {
  const modal = document.getElementById("previewModal")
  if (modal) {
    modal.style.display = "none"
  }
}

function collectFormData() {
  const form = document.getElementById("uploadRecipeForm")
  if (!form) return {}

  const formData = new FormData(form)
  const data = {}

  // Basic form data
  for (const [key, value] of formData.entries()) {
    if (data[key]) {
      if (Array.isArray(data[key])) {
        data[key].push(value)
      } else {
        data[key] = [data[key], value]
      }
    } else {
      data[key] = value
    }
  }

  // Instructions from Quill editor
  if (quill) {
    data.instructions = quill.root.innerHTML
  }

  // Ingredients
  data.ingredients = []
  const amounts = document.querySelectorAll(".ingredient-amount")
  const names = document.querySelectorAll(".ingredient-name")

  for (let i = 0; i < names.length; i++) {
    if (names[i].value.trim()) {
      data.ingredients.push({
        amount: amounts[i].value.trim(),
        name: names[i].value.trim(),
      })
    }
  }

  // Images
  data.images = uploadedImages

  return data
}

function generatePreviewHTML(data) {
  const totalTime = (Number.parseInt(data.prepTime) || 0) + (Number.parseInt(data.cookTime) || 0)

  let imagesHTML = ""
  if (Object.keys(data.images).length > 0) {
    imagesHTML = '<div class="preview-images">'
    Object.values(data.images).forEach((file) => {
      imagesHTML += `<img src="${URL.createObjectURL(file)}" alt="Recipe image" style="width: 200px; height: 150px; object-fit: cover; margin: 5px; border-radius: 8px;">`
    })
    imagesHTML += "</div>"
  }

  let ingredientsHTML = "<ul>"
  data.ingredients.forEach((ingredient) => {
    ingredientsHTML += `<li>${ingredient.amount} ${ingredient.name}</li>`
  })
  ingredientsHTML += "</ul>"

  return `
    <div class="recipe-preview-content">
      <h3>${data.recipeTitle || "Untitled Recipe"}</h3>
      <p><strong>Description:</strong> ${data.recipeDescription || "No description provided"}</p>
      
      ${imagesHTML}
      
      <div class="recipe-meta">
        <p><strong>Prep Time:</strong> ${data.prepTime || 0} minutes</p>
        <p><strong>Cook Time:</strong> ${data.cookTime || 0} minutes</p>
        <p><strong>Total Time:</strong> ${totalTime} minutes</p>
        <p><strong>Servings:</strong> ${data.servings || "Not specified"}</p>
        <p><strong>Difficulty:</strong> ${data.difficulty || "Not specified"}</p>
        <p><strong>Cuisine:</strong> ${data.country || "Not specified"}</p>
      </div>
      
      <div class="ingredients-section">
        <h4>Ingredients:</h4>
        ${ingredientsHTML}
      </div>
      
      <div class="instructions-section">
        <h4>Instructions:</h4>
        <div>${data.instructions || "No instructions provided"}</div>
      </div>
      
      ${data.cookingTips ? `<div class="tips-section"><h4>Tips:</h4><p>${data.cookingTips}</p></div>` : ""}
    </div>
  `
}

function submitRecipe() {
  const formData = collectFormData()

  // In a real application, this would send data to the server
  console.log("Recipe submitted:", formData)

  // Clear draft after successful submission
  clearDraftRecipe()

  // Simulate successful submission
  alert("Recipe submitted successfully! It will be reviewed and published to the community soon.")

  // Redirect to community page
  window.location.href = "community.html"
}

// Clear draft after successful submission
function clearDraftRecipe() {
  localStorage.removeItem("draftRecipe")
}

// Make functions globally available
window.changeStep = changeStep
window.handleImageUpload = handleImageUpload
window.removeImage = removeImage
window.updateFoodTypes = updateFoodTypes
window.removeCategory = removeCategory
window.addIngredient = addIngredient
window.removeIngredient = removeIngredient
window.closePreviewModal = closePreviewModal
window.submitRecipe = submitRecipe
