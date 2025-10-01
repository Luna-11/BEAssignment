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
    <style>
        :root {
            --primary-color: #C89091;
            --text-color: #7b4e48;
            --lightest-color: #fcfaf2;
            --light_pink: #e9d0cb;
            --medium_pink: #ddb2b1;
            --light_yellow: #f9f1e5;
            --white: #fff;
            --black: #222;
            --light-gray: #bbb;
            --medium-gray: #555;
            --shadow-color: rgba(0,0,0,0.1);
            --border-color: #ccc;
            --button-color: #333;
        }
        
        /* Additional styles for better spacing using your exact colors */
        .form-header {
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .form-header h1 {
            margin-bottom: 0.75rem;
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--text-color); /* Your text color */
        }
        
        .form-header p {
            color: var(--medium-gray); /* Your medium gray */
            line-height: 1.5;
            margin: 0;
        }
        
        .input-group {
            margin-bottom: 1.5rem;
        }
        
        .input-container {
            position: relative;
            margin-bottom: 1rem;
        }
        
        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: var(--light-gray); /* Your light gray for icon */
            z-index: 10;
        }
        
        .input-container input {
            width: 100%;
            padding: 14px 12px 14px 42px;
            border: 1px solid var(--border-color); /* Your border color */
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
            box-sizing: border-box;
            background-color: var(--white);
            color: var(--text-color);
        }
        
        .input-container input:focus {
            outline: none;
            border-color: var(--primary-color); /* Your primary color */
            box-shadow: 0 0 0 2px var(--light_pink); /* Light pink shadow */
        }
        
        .input-container input::placeholder {
            color: var(--light-gray); /* Your light gray for placeholder */
        }
        
        .login-btn {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-color); /* Your button color */
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease;
            margin-top: 0.5rem;
        }
        
        .login-btn:hover {
            background-color: var(--medium_pink);
        }
        
        .signup-link {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--medium-gray); /* Your medium gray */
        }
        
        .signup-link a {
            color: var(--primary-color); /* Your primary color */
            text-decoration: none;
            font-weight: 500;
        }
        
        .signup-link a:hover {
            text-decoration: underline;
            color: var(--text-color); /* Your text color on hover */
        }
        
        .error-message {
            background-color: var(--light_yellow); /* Your light yellow */
            border: 1px solid var(--medium_pink); /* Your medium pink */
            color: var(--text-color); /* Your text color */
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
            font-weight: 500;
        }
    </style>
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
                    <div class="error-message">
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