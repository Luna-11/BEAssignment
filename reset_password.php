<?php
session_start();
require_once("configMysql.php");

if (!isset($_SESSION["reset_user_id"]) || !isset($_SESSION["security_verified"])) {
    header("Location: forgot_password.php");
    exit;
}

$error = "";
$user_id = $_SESSION["reset_user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    
    // Validate passwords
    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($new_password) < 8) {
        $error = "Password must be at least 8 characters long";
    } else {
            $passwordRegex = "/^(?=.*[A-Z])(?=.*\d).{8,}$/";
        if (!preg_match($passwordRegex, $new_password)) {
            $error = "Password must contain at least one uppercase letter and one digit";
        } else {
            // Hash new password and update
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            $sql = "UPDATE users SET password = ?, failed_attempts = 0, last_attempt_time = NULL WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "si", $hashed_password, $user_id);
            
            if (mysqli_stmt_execute($stmt)) {
                // Clear session and redirect to login
                session_destroy();
                header("Location: logIn.php?reset=success");
                exit;
            } else {
                $error = "Error updating password. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - FoodFusion</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
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

        <div class="right-side">
            <img src="BEpics/basil.png" alt="Leaf Pattern" class="leaf-patterns">
            <div class="form-container">
                <div class="form-header">
                    <h1>Create New Password</h1>
                    <p>Enter your new password below</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="error-message" style="color: red; text-align: center; margin-bottom: 15px;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="post" class="login-form" id="reset-form">
                    <div class="input-group">
                        <div class="input-container">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            <input type="password" placeholder="New Password" name="password" id="password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="input-group">
                        <div class="input-container">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 9.9 1"/>
                                <path d="M11 14v4"/>
                                <path d="M15 14v4"/>
                            </svg>
                            <input type="password" placeholder="Confirm New Password" name="confirm_password" id="confirm_password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                                <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="login-btn">Reset Password</button>

                    <div class="signup-link">
                        <a href="security_questions_verification.php">← Back to Questions</a>
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

        document.getElementById("reset-form").addEventListener("submit", function (e) {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm_password").value;
            const passwordRegex = /^(?=.*[A-Z])(?=.*\d).{8,}$/;

            if (!passwordRegex.test(password)) {
                e.preventDefault();
                alert("Password must be at least 8 characters long, contain at least one uppercase letter, and one digit.");
                return;
            }

            if (password !== confirmPassword) {
                e.preventDefault();
                alert("Passwords do not match!");
                return;
            }
        });
    </script>
</body>
</html>