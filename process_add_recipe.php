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
    
    // DEBUG: Log all POST data
    error_log("POST data received: " . print_r($_POST, true));
    
    // Get the IDs directly from the form (they're already IDs from the dropdown)
    $cuisineTypeID = $_POST['country'] ?? null;
    $foodTypeID = $_POST['foodType'] ?? null; // This is already an ID
    $dietaryID = $_POST['dietPref'] ?? null; // This is already an ID
    
    // DEBUG: Log the IDs
    error_log("Cuisine Type ID: " . $cuisineTypeID);
    error_log("Food Type ID: " . $foodTypeID);
    error_log("Dietary ID: " . $dietaryID);
    
    // Validate that we have valid IDs
    if (empty($foodTypeID) || $foodTypeID === '') {
        $_SESSION['error_message'] = 'Please select a valid food type';
        header("Location: addRecipe.php");
        exit;
    }
    
    if (empty($dietaryID) || $dietaryID === '') {
        $_SESSION['error_message'] = 'Please select a valid diet preference';
        header("Location: addRecipe.php");
        exit;
    }
    
    // Collect form data
    $data = [
        'recipeName' => $_POST['recipeTitle'] ?? '',
        'difficultID' => $_POST['difficulty'] ?? '',
        'userID' => $_SESSION['userID'],
        'image' => $imagePath,
        'recipeDescription' => $_POST['recipeDescription'] ?? '',
        'cuisineTypeID' => $cuisineTypeID,
        'foodTypeID' => $foodTypeID, // Use the ID directly
        'dietaryID' => $dietaryID, // Use the ID directly
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