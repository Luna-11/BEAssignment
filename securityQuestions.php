<?php
require_once("configMysql.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = intval($_POST["user_id"]);
    $q1 = $_POST["question1"];
    $a1 = password_hash(trim($_POST["answer1"]), PASSWORD_DEFAULT);
    $q2 = $_POST["question2"];
    $a2 = password_hash(trim($_POST["answer2"]), PASSWORD_DEFAULT);
    $q3 = $_POST["question3"];
    $a3 = password_hash(trim($_POST["answer3"]), PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO user_security_questions 
        (user_id, question1, answer1, question2, answer2, question3, answer3)
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $user_id, $q1, $a1, $q2, $a2, $q3, $a3);

    if ($stmt->execute()) {
        header("Location: logIn.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Security Questions</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="container">
    <div class="form-container">
        <h1>Set Up Security Questions</h1>
        <form method="post">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_GET['user_id']); ?>">

            <label>Question 1</label>
            <select name="question1" required>
                <option value="What is your favorite food?">What is your favorite food?</option>
                <option value="What is your mother’s maiden name?">What is your mother’s maiden name?</option>
                <option value="What is the name of your first pet?">What is the name of your first pet?</option>
            </select>
            <input type="text" name="answer1" placeholder="Answer" required>

            <label>Question 2</label>
            <select name="question2" required>
                <option value="What city were you born in?">What city were you born in?</option>
                <option value="What is your favorite teacher’s name?">What is your favorite teacher’s name?</option>
                <option value="What is your dream job?">What is your dream job?</option>
            </select>
            <input type="text" name="answer2" placeholder="Answer" required>

            <label>Question 3</label>
            <select name="question3" required>
                <option value="What was the name of your first school?">What was the name of your first school?</option>
                <option value="What is your favorite movie?">What is your favorite movie?</option>
                <option value="What was your childhood nickname?">What was your childhood nickname?</option>
            </select>
            <input type="text" name="answer3" placeholder="Answer" required>

            <button type="submit" class="login-btn">Save Questions</button>
        </form>
    </div>
</div>
</body>
</html>
