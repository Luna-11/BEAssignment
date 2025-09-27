<?php
// process_add_recipe.php
session_start();

// Include database configuration and functions
include('./configMysql.php');
include('./function.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
        $imagePath = uploadImage($_FILES['image1']);
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
    
    // Collect form data - CORRECTED field mapping
    $data = [
        'recipeName' => $_POST['recipeTitle'] ?? '',
        'difficultID' => $_POST['difficulty'] ?? '',
        'userID' => $_SESSION['userID'],
        'image' => $imagePath,
        'recipeDescription' => $_POST['recipeDescription'] ?? '', // This will go to 'text' column
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
        header("Location: re.php"); // Adjust this to your success page
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