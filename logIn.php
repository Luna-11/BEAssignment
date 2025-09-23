<?php
session_start();
require_once("configMysql.php");

// Initialize variables
$mail = $password = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $mail = htmlspecialchars(trim($_POST["mail"]));
    $password = trim($_POST["password"]);
    
    // Validate inputs
    if (empty($mail) || empty($password)) {
        $error = "Please fill in all fields";
    } else {
        // Prepare SQL statement to get user data including failed attempts
        $sql = "SELECT id, first_name, last_name, password, failed_attempts, last_attempt_time FROM users WHERE mail = ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $mail);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            
            if (mysqli_stmt_num_rows($stmt) === 0) {
                // User with this email doesn't exist
                $error = "No account found with this email address";
            } else {
                mysqli_stmt_bind_result($stmt, $userID, $first_name, $last_name, $hashed_password, $failed_attempts, $last_attempt_time);
                mysqli_stmt_fetch($stmt);

                $full_name = $first_name . ' ' . $last_name;
                $now = time();
                $lock_duration = 180; // 3 minutes lockout

                // Check if account is locked
                if ($failed_attempts >= 3 && $last_attempt_time !== null) {
                    $last_time = strtotime($last_attempt_time);
                    $elapsed = $now - $last_time;

                    if ($elapsed < $lock_duration) {
                        $wait = $lock_duration - $elapsed;
                        $minutes = ceil($wait / 60);
                        $error = "Account locked due to too many failed attempts. Please try again in $minutes minute(s).";
                    } else {
                        // Unlock account after lock duration has passed
                        $update_sql = "UPDATE users SET failed_attempts = 0 WHERE id = ?";
                        $update_stmt = mysqli_prepare($conn, $update_sql);
                        mysqli_stmt_bind_param($update_stmt, "i", $userID);
                        mysqli_stmt_execute($update_stmt);
                        mysqli_stmt_close($update_stmt);
                        $failed_attempts = 0;
                    }
                }

                // Only attempt login if account isn't locked
                if ($failed_attempts < 3) {
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, start a new session
                        $_SESSION['userID'] = $userID;
                        $_SESSION['username'] = $full_name;
                        $_SESSION['mail'] = $mail;
                        $_SESSION['loggedin'] = true;
                        
                        // Reset failed attempts
                        $update_sql = "UPDATE users SET failed_attempts = 0, last_attempt_time = NULL WHERE id = ?";
                        $update_stmt = mysqli_prepare($conn, $update_sql);
                        mysqli_stmt_bind_param($update_stmt, "i", $userID);
                        mysqli_stmt_execute($update_stmt);
                        mysqli_stmt_close($update_stmt);
                        
                        // Redirect to home page
                        header('Location: index.php');
                        exit;
                    } else {
                        // Increment failed attempts
                        $failed_attempts++;
                        $now_str = date("Y-m-d H:i:s");
                        
                        $update_sql = "UPDATE users SET failed_attempts = ?, last_attempt_time = ? WHERE id = ?";
                        $update_stmt = mysqli_prepare($conn, $update_sql);
                        mysqli_stmt_bind_param($update_stmt, "isi", $failed_attempts, $now_str, $userID);
                        mysqli_stmt_execute($update_stmt);
                        mysqli_stmt_close($update_stmt);

                        if ($failed_attempts >= 3) {
                            $error = "Too many failed attempts. Your account has been locked for 3 minutes.";
                        } else {
                            $remaining = 3 - $failed_attempts;
                            $error = "Incorrect password. You have $remaining attempt(s) remaining.";
                        }
                    }
                }
            }
            
            mysqli_stmt_close($stmt);
        } else {
            $error = "Database error. Please try again later.";
            error_log("MySQL prepare error: " . mysqli_error($conn));
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
                            <input type="email" placeholder="E-mail" id="mail" name="mail" value="<?php echo htmlspecialchars($mail); ?>" required>
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
                        <a href="register.php">register here</a>
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