<?php
session_start();
require_once("configMysql.php");

if (!isset($_SESSION["reset_user_id"])) {
    header("Location: forgot_password.php");
    exit;
}

$error = "";
$user_id = $_SESSION["reset_user_id"];
$questions = [];

// Get security questions
$sql = "SELECT question1, question2, question3 FROM user_security_questions WHERE user_id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $q1, $q2, $q3);

if (mysqli_stmt_fetch($stmt)) {
    $questions = [$q1, $q2, $q3];
} else {
    $error = "Security questions not found for this account.";
}
mysqli_stmt_close($stmt);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["answers"])) {
    $answers = $_POST["answers"];
    
    // Verify answers
    $sql = "SELECT answer1, answer2, answer3 FROM user_security_questions WHERE user_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $hashed_a1, $hashed_a2, $hashed_a3);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    
    $all_correct = true;
    for ($i = 0; $i < 3; $i++) {
        if (!password_verify(trim($answers[$i]), ${"hashed_a" . ($i + 1)})) {
            $all_correct = false;
            break;
        }
    }
    
    if ($all_correct) {
        $_SESSION["security_verified"] = true;
        header("Location: reset_password.php");
        exit;
    } else {
        $error = "One or more answers are incorrect. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Questions - FoodFusion</title>
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
                    <h1>Security Questions</h1>
                    <p>Please answer your security questions to verify your identity</p>
                    <br>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="error-message" style="color: red; text-align: center; margin-bottom: 15px;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($questions)): ?>
                <form method="post" class="login-form">
                    <?php foreach ($questions as $index => $question): ?>
                    <div class="input-group">
                        <label style="display: block; margin-bottom: 8px; font-weight: bold;">
                            <?php echo htmlspecialchars($question); ?>
                        </label>
                        <div class="input-container">
                            <input type="text" name="answers[]" placeholder="Your answer" required>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <button type="submit" class="login-btn">Verify Answers</button>

                    <div class="signup-link">
                        <a href="forgot_password.php">← Back to Email</a>
                    </div>
                </form>
                <?php else: ?>
                    <div class="error-message" style="color: red; text-align: center;">
                        <?php echo $error; ?>
                    </div>
                    <div class="signup-link" style="text-align: center; margin-top: 20px;">
                        <a href="forgot_password.php">← Back to Email</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>