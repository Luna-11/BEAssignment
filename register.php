<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodFusion Registration</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<?php
require_once("configMysql.php");

// Initialize variables
$name = $mail = $password = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name = htmlspecialchars(trim($_POST["name"]));
    $mail = htmlspecialchars(trim($_POST["mail"]));
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm-password"]);
    
    // Validate password match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Use prepared statement to insert data
        $stmt = $conn->prepare("INSERT INTO users (username, mail, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $mail, $hashed_password);

        if ($stmt->execute()) {
            header('Location: logIn.html');
            exit;
        } else {
            $error = "Error saving data: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<div class="container">
    <!-- Left Side - Food Photography -->
    <div class="left-side">
        <div class="logo">
            <div class="logo-text">FoodFusion</div>
            <div class="logo-subtitle">Food RECIPE SHARING</div>
        </div>
        <div class="food-background">
            <div class="overlay">
                <img src="BEpics/bg2.jpg" alt="Overlay Image" class="overlay-image">
            </div>
            <div class="food-grid">
                <div class="food-item top-item">
                    <img src="BEpics/p1.png" alt="Fresh salad">
                </div>
                <div class="food-item middle-item">
                    <img src="BEpics/p3.png" alt="Healthy meal">
                </div>
                <div class="food-item bottom-item">
                    <img src="BEpics/p4.png" alt="Colorful bowl">
                </div>
            </div>
        </div>
        <img src="BEpics/test.png" alt="Leaf Pattern" class="leaf-patternsLeft">
    </div>

    <!-- Right Side - Registration Form -->
    <div class="right-side">
        <img src="BEpics/basil.png" alt="Leaf Pattern" class="leaf-patterns">
        <div class="form-container">
            <div class="form-header">
                <h1>Register Here!</h1>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error-message" style="color: red; text-align: center; margin-bottom: 15px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form class="login-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <!-- Name -->
                <div class="input-group">
                    <div class="input-container">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <input type="text" placeholder="Name" id="name" name="name" value="<?php echo $name; ?>" required>
                    </div>
                </div>

                <!-- Email -->
                <div class="input-group">
                    <div class="input-container">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <input type="email" placeholder="E-mail" id="mail" name="mail" value="<?php echo $mail; ?>" required>
                    </div>
                </div>

                <!-- Password -->
                <div class="input-group">
                    <div class="input-container">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <input type="password" placeholder="Password" id="password" name="password" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('password')">
                            <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="input-group">
                    <div class="input-container">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 9.9 1"/>
                            <path d="M11 14v4"/>
                            <path d="M15 14v4"/>
                        </svg>
                        <input type="password" placeholder="Confirm Password" id="confirm-password" name="confirm-password" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('confirm-password')">
                            <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Register Button -->
                <button type="submit" class="login-btn">Register</button>

                <!-- Divider -->
                <div class="divider">
                    <span>OR</span>
                </div>

                <!-- Login Link -->
                <div class="signup-link">
                    <span>Already have an account? </span>
                    <a href="logIn.html">Log In here</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePassword(fieldId) {
        const passwordField = document.getElementById(fieldId);
        if (passwordField.type === "password") {
            passwordField.type = "text";
        } else {
            passwordField.type = "password";
        }
    }
</script>
</body>
</html>
