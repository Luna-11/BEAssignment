<?php
// process_add_recipe.php
session_start();

// Include database configuration and functions
include('./configMysql.php');
include('./function.php');

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    $_SESSION['error_message'] = 'Please log in to upload recipes';
    header("Location: addRecipe.php");
    exit;
}

// Check if the request is a POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error_message'] = 'Method not allowed';
    header("Location: addRecipe.php");
    exit;
}

// Get the action from the request
$action = $_POST['action'] ?? '';

if ($action === 'addRecipe') {
    // Handle image uploads first
// Handle image upload with original filename
$imagePath = null;
if (isset($_FILES['image1']) && $_FILES['image1']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . "/uploads/recipes/";
    
    // Create directory if needed
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $imageFile = $_FILES['image1'];
    $originalName = $imageFile['name'];
    
    // Sanitize filename
    $sanitizedName = preg_replace("/[^a-zA-Z0-9\._-]/", "_", $originalName);
    $sanitizedName = substr($sanitizedName, 0, 100);
    $targetFile = $uploadDir . $sanitizedName;
    
    // Handle duplicates
    $counter = 1;
    $nameWithoutExt = pathinfo($sanitizedName, PATHINFO_FILENAME);
    $fileExtension = pathinfo($sanitizedName, PATHINFO_EXTENSION);
    
    while (file_exists($targetFile)) {
        $sanitizedName = $nameWithoutExt . '_' . $counter . '.' . $fileExtension;
        $targetFile = $uploadDir . $sanitizedName;
        $counter++;
    }
    
    // Upload file
    if (move_uploaded_file($imageFile['tmp_name'], $targetFile)) {
        $imagePath = "uploads/recipes/" . $sanitizedName;
    }
}
    
    // Convert names to IDs using your existing functions
    $cuisineTypeID = getCuisineTypeID($conn, $_POST['country'] ?? '');
    
    // Handle multiple selections - take the first one
    $foodTypes = $_POST['foodType'] ?? [];
    $dietPrefs = $_POST['dietPref'] ?? [];
    
    $foodTypeID = null;
    if (!empty($foodTypes) && is_array($foodTypes)) {
        $foodTypeID = getFoodTypeID($conn, $foodTypes[0]);
    }
    
    $dietaryID = null;
    if (!empty($dietPrefs) && is_array($dietPrefs)) {
        $dietaryID = getDietPrefID($conn, $dietPrefs[0]);
    }
    
    // Collect form data
    $data = [
        'recipeName' => $_POST['recipeTitle'] ?? '',
        'difficultID' => $_POST['difficulty'] ?? '',
        'userID' => $_SESSION['userID'],
        'image' => $imagePath,
        'recipeDescription' => $_POST['recipeDescription'] ?? '',
        'cuisineTypeID' => $cuisineTypeID,
        'foodTypeID' => $foodTypeID,
        'dietaryID' => $dietaryID,
        'ingredient' => $_POST['ingredient'] ?? ''
    ];

    // Validate required fields
    if (empty($data['recipeName']) || empty($data['difficultID']) || empty($data['recipeDescription']) || empty($data['ingredient'])) {
        $_SESSION['error_message'] = 'Recipe title, difficulty, ingredients, and instructions are required';
        header("Location: addRecipe.php");
        exit;
    }

    // Call the addRecipe function
    $result = addRecipe($conn, $data);

    if ($result['success']) {
        $_SESSION['success_message'] = 'Recipe uploaded successfully!';
        header("Location: re.php");
        exit;
    } else {
        $_SESSION['error_message'] = $result['message'] ?? 'Failed to upload recipe';
        header("Location: addRecipe.php");
        exit;
    }
} else {
    $_SESSION['error_message'] = 'Invalid action';
    header("Location: addRecipe.php");
    exit;
}
?>