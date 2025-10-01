<?php
require_once("configMysql.php");
session_start();

if (!isset($_SESSION["reset_user_id"])) {
    header("Location: forgot_password.php");
    exit;
}

$user_id = $_SESSION["reset_user_id"];
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $a1 = strtolower(trim($_POST["answer1"]));
    $a2 = strtolower(trim($_POST["answer2"]));
    $a3 = strtolower(trim($_POST["answer3"]));

    $sql = "SELECT answer1, answer2, answer3 FROM user_security WHERE user_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $db_a1, $db_a2, $db_a3);

    if (mysqli_stmt_fetch($stmt)) {
        if ($a1 === strtolower($db_a1) && $a2 === strtolower($db_a2) && $a3 === strtolower($db_a3)) {
            // Correct → go reset
            header("Location: reset_password.php");
            exit;
        } else {
            $error = "Incorrect answers. Please try again.";
        }
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html>
<head><title>Verify Security</title></head>
<body>
    <h2>Verify Security Questions</h2>
    <p style="color:red;"><?php echo $error; ?></p>
    <a href="forgot_password.php">Go back</a>
</body>
</html>
