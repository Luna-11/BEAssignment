<?php
session_start();
require_once("configMysql.php");

// Initialize variables
$email = $password = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $email = htmlspecialchars(trim($_POST["email"]));
    $password = trim($_POST["password"]);
    
    // Validate inputs
    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields";
    } else {
        // Prepare SQL statement to prevent SQL injection
        $sql = "SELECT userID, name, password FROM user WHERE mail = ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($row = mysqli_fetch_assoc($result)) {
                // Verify password
                if (password_verify($password, $row['password'])) {
                    // Password is correct, start a new session
                    $_SESSION['userID'] = $row['userID'];
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['loggedin'] = true;
                    
                    // Redirect to home page
                    header('Location: index.php');
                    exit;
                } else {
                    $error = "Invalid email or password";
                }
            } else {
                $error = "Invalid email or password";
            }
            
            mysqli_stmt_close($stmt);
        } else {
            $error = "Database error. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodFusion Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <div class="container">
        <!-- Left Side - Food Photography -->
        <div class="left-side">

            <!-- Logo -->
            <div class="logo">
                <div class="logo-text">FoodFusion</div>
                <div class="logo-subtitle">Food RECIPE SHARING</div>
            </div>
            <!-- Background with food images -->
            <div class="food-background">
                <div class="overlay">
                <img src="BEpics/bg2.jpg" alt="Overlay Image" class="overlay-image">
                </div>
                
                <!-- Food Images Grid -->
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

            <!-- Decorative Leaf Patterns -->
            <img src="BEpics/test.png" alt="Leaf Pattern" class="leaf-patternsLeft">

        </div>

        <!-- Right Side - Login Form -->
        <div class="right-side">
            <!-- Background decorative elements -->
            <img src="BEpics/basil.png" alt="Leaf Pattern" class="leaf-patterns">

            <!-- Login Form -->
            <div class="form-container">
                <div class="form-header">
                    <h1>Log In Here!</h1>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="error-message" style="color: red; text-align: center; margin-bottom: 15px;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form class="login-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                    <!-- Email Input -->
                    <div class="input-group">
                        <div class="input-container">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <input type="email" placeholder="E-mail" id="email" name="email" value="<?php echo $email; ?>" required>
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="input-group">
                        <div class="input-container">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <circle cx="12" cy="16" r="1"/>
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

                    <!-- Forgot Password -->
                    <div class="forgot-password">
                        <a href="forgot_password.php">forgot password?</a>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="login-btn">Login</button>

                    <!-- Divider -->
                    <div class="divider">
                        <span>OR</span>
                    </div>

                    <!-- Sign Up Link -->
                    <div class="signup-link">
                        <span>Don't have an account? </span>
                        <a href="register.html">register here</a>
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