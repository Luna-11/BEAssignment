<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Recipe</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkfTOGFV+tQ3vGNnXQpA9RT9I8Ra7C9xjz5bQ+6hE6WZl4xYx4S+4GZIQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
  <!-- Custom Styles -->
  <style>
    :root {
      --ocean: #1e3a8a;
      --light-ocean: #3b82f6;
      --sky: #dbeafe;
      --accent: #06b6d4;
      --vanilla: #fff8ddff;
    }

    body {
      background: linear-gradient(to bottom right, var(--sky), var(--light-ocean));
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
    }

    .form-container {
      background: white;
      padding: 2rem;
      border-radius: 16px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
      max-width: 980px;
      margin: 2rem auto;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .form-container:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    }

    .form-container h1 {
      background: linear-gradient(to right, var(--ocean), var(--accent));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      font-weight: 700;
      text-align: center;
      margin-bottom: 1.5rem;
      font-size: 2rem;
    }

    .form-label {
      font-weight: 600;
      color: var(--ocean);
    }

    .form-control, .form-select {
      border: 2px solid var(--light-ocean);
      border-radius: 10px;
      padding: 0.6rem;
      font-size: 1rem;
      transition: 0.3s;
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--accent);
      box-shadow: 0 0 8px var(--sky);
    }

    textarea {
      resize: none;
    }

    .btn-submit {
      background: var(--ocean);
      color: white;
      padding: 0.7rem 1.5rem;
      font-size: 1.1rem;
      font-weight: 600;
      border-radius: 30px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .btn-submit:hover {
      background: var(--light-ocean);
      transform: scale(1.05);
      box-shadow: 0 6px 18px rgba(0,0,0,0.25);
    }

    /* Section headers */
    .section-title {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 700;
      color: var(--ocean);
      margin-bottom: 1rem;
    }

    .section-title i {
      color: var(--accent);
    }



    @media (max-width: 576px) {
      .form-container {
        padding: 1.2rem;
      }

      h1 {
        font-size: 1.6rem;
      }
    }
    
    .alert {
      border-radius: 10px;
    }
  </style>
</head>
   
<body>
<?php include 'nav.php'; ?>

  <div class="container-fluids">
    <div class="form-container">
      <h1>Add New Recipe</h1>
      
      <?php if (!empty($message)): ?>
      <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php endif; ?>
      
      <form id="recipeForm" action="recipeAction.php" method="POST" enctype="multipart/form-data">
        <div class="row g-4">
          <div class="col-12 col-lg-7">
            <div class="section-title"><i class="fa-solid fa-utensils"></i> Recipe Details</div>
            <!-- Recipe Name -->
            <div class="mb-3">
              <label for="recipeName" class="form-label">Recipe Name</label>
              <input type="text" class="form-control" id="recipeName" name="recipeName" placeholder="Enter recipe name" required>
            </div>

            <!-- Description -->
            <div class="mb-3">
              <label for="description" class="form-label">Short Description</label>
              <textarea class="form-control" id="description" name="description" rows="2" placeholder="Write a short description" required></textarea>
            </div>
            <div class="mb-3">
              <label for="ingredients" class="form-label">Ingredients</label>
              <textarea class="form-control" id="ingredients" name="ingredients" rows="2" placeholder="Write aIngredients" required></textarea>
            </div>
            <!-- Instructions -->
            <div class="mb-3">
              <label for="instructions" class="form-label">Instructions</label>
              <textarea class="form-control" id="instructions" name="instructions" rows="6" placeholder="Write detailed instructions" required></textarea>
            </div>
          </div>

          <div class="col-12 col-lg-5">
            <div class="section-title"><i class="fa-solid fa-sliders"></i> Settings</div>
            <!-- Difficulty Level -->
            <div class="mb-3">
              <label for="difficulty" class="form-label">Difficulty Level</label>
              <select class="form-select" id="difficulty" name="difficulty" required>
                <option selected disabled value="">Select difficulty</option>
              <?php foreach($difficult as $diff):?>
                <option value="<?= htmlspecialchars($diff['difficultyID'] ?? '') ?>"><?= htmlspecialchars($diff['difficulty'] ?? '') ?></option>
              <?php endforeach;?>
              </select>
            </div>

            <!-- Category -->
            <div class="mb-3">
              <label for="category" class="form-label">Category</label>
              <select class="form-select" id="category" name="category" required>
                <option selected disabled value="">Select category</option>
              <?php foreach($category as $cat):?>
                <option value="<?= htmlspecialchars($cat['categoryID'] ?? '') ?>"><?= htmlspecialchars($cat['categoryName'] ?? '') ?></option>
              <?php endforeach;?>
              </select>
            </div>

            <!-- Cuisine Type -->
            <div class="mb-3">
              <label for="cuisine" class="form-label">Cuisine Type</label>
              <select class="form-select" id="cuisine" name="cuisine" required>
                <option selected disabled value="">Select cuisine</option>
              <?php foreach($cuisines as $cuisine):?>
                <option value="<?= htmlspecialchars($cuisine['cuisineTypeID'] ?? '') ?>"><?= htmlspecialchars($cuisine['cuisineType'] ?? '') ?></option>
              <?php endforeach;?>
              </select>
            </div>

            <!-- Dietary Preferences -->
            <div class="mb-3">
              <label for="dietary" class="form-label">Dietary Preference</label>
              <select class="form-select" id="dietary" name="dietary" required>
                <option selected disabled value="">Select dietary preference</option>
              <?php foreach($diets as $diet):?>
                <option value="<?= htmlspecialchars($diet['dietaryID'] ?? '') ?>"><?= htmlspecialchars($diet['dietaryName'] ?? '') ?></option>
              <?php endforeach;?>
              </select>
            </div>

            <!-- Image Upload with Dropzone -->
           <!-- Image Upload (Simple File Input) -->
            <div class="mb-3">
              <label for="image" class="form-label">Upload Image</label>
              <input class="form-control" type="file" id="image" name="image" accept="image/*" required>
              <small class="text-muted">Max 4MB — JPG, PNG, GIF</small>
            </div>


          <!-- Submit Button -->
          <div class="col-12 text-center mt-2">
            <button type="submit" class="btn btn-submit">Submit Recipe</button>
          </div>
        </div>
      </form>
    </div>

  </div>
<?php include 'footer.php'; ?>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" defer></script>

</body>

</html>
