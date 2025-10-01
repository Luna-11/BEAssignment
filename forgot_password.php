<?php
session_start();
require_once("configMysql.php");

$error = "";
$mail = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["mail"])) {
    $mail = htmlspecialchars(trim($_POST["mail"]));

    // Find user
    $sql = "SELECT id FROM users WHERE mail=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $mail);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $user_id);
    
    if (mysqli_stmt_fetch($stmt)) {
        $_SESSION["reset_user_id"] = $user_id;
        $_SESSION["reset_email"] = $mail;
        mysqli_stmt_close($stmt);
        header("Location: security_questions_verification.php");
        exit;
    } else {
        $error = "No account found with that email.";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - FoodFusion</title>
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
                    <h1>Reset Your Password</h1>
                    <p>Enter your email to start the password reset process</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="error-message" style="color: red; text-align: center; margin-bottom: 15px;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="post" class="login-form">
                    <div class="input-group">
                        <div class="input-container">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <input type="email" placeholder="E-mail" name="mail" value="<?php echo htmlspecialchars($mail); ?>" required>
                        </div>
                    </div>

                    <button type="submit" class="login-btn">Continue</button>

                    <div class="signup-link">
                        <span>Remember your password? </span>
                        <a href="logIn.php">Back to Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>