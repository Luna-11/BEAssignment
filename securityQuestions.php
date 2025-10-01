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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Questions - FoodFusion</title>
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
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--lightest-color);
            color: var(--text-color);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        .form-container {
            width: 100%;
            max-width: 600px;
            background-color: var(--white);
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px var(--shadow-color);
        }
        
        .form-header {
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .form-header h1 {
            margin-bottom: 0.75rem;
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--text-color);
        }
        
        .form-header p {
            color: var(--medium-gray);
            line-height: 1.4;
            margin: 0;
        }
        
        .input-container {
            position: relative;
            margin-bottom: 1.25rem;
        }
        
        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: var(--light-gray);
            z-index: 10;
        }
        
        .input-container select,
        .input-container input {
            width: 100%;
            padding: 14px 12px 14px 42px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
            box-sizing: border-box;
            background-color: var(--white);
            color: var(--text-color);
        }
        
        .input-container select:focus,
        .input-container input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px var(--light_pink);
        }
        
        .input-container select::placeholder,
        .input-container input::placeholder {
            color: var(--light-gray);
        }
        
        .login-btn {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-color);
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
            color: var(--medium-gray);
        }
        
        .signup-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .signup-link a:hover {
            text-decoration: underline;
            color: var(--text-color);
        }
        
        .security-question-group {
            margin-bottom: 2rem;
            padding: 1.5rem;
            border-radius: 8px;
            background-color: var(--light_yellow);
        }
        
        .security-question-group label {
            display: block;
            margin-bottom: 0.3rem;
            font-weight: 500;
            color: var(--text-color);
        }
        
        .question-number {
            font-size: 0.9rem;
            color: var(--medium-gray);
            margin-bottom: 0.75rem;
            font-weight: 500;
        }
        
        /* Responsive styles */
        @media (max-width: 768px) {
            .form-container {
                padding: 2rem;
                border-radius: 12px;
            }
            
            .form-header h1 {
                font-size: 1.5rem;
            }
            
            .security-question-group {
                padding: 1.25rem;
            }
        }
        
        @media (max-width: 480px) {
            body {
                padding: 0.5rem;
            }
            
            .form-container {
                padding: 1.5rem;
                border-radius: 10px;
            }
            
            .form-header h1 {
                font-size: 1.35rem;
            }
            
            .form-header p {
                font-size: 0.9rem;
            }
            
            .input-container select,
            .input-container input {
                padding: 12px 10px 12px 38px;
                font-size: 0.95rem;
            }
            
            .login-btn {
                padding: 12px;
                font-size: 0.95rem;
            }
            
            .security-question-group {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1>Set Up Security Questions</h1>
            <p>Choose and answer three security questions for account recovery</p>
        </div>

        <form method="post" class="login-form">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_GET['user_id']); ?>">

            <div class="security-question-group">
                <div class="question-number">Question 1</div>
                <div class="input-container">

                    <select name="question1" required>
                        <option value="">Select a question</option>
                        <option value="What is your favorite food?">What is your favorite food?</option>
                        <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
                        <option value="What is the name of your first pet?">What is the name of your first pet?</option>
                    </select>
                </div>
                <div class="input-container">
                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <input type="text" name="answer1" placeholder="Your answer" required>
                </div>
            </div>

            <div class="security-question-group">
                <div class="question-number">Question 2</div>
                <div class="input-container">
                    <select name="question2" required>
                        <option value="">Select a question</option>
                        <option value="What city were you born in?">What city were you born in?</option>
                        <option value="What is your favorite teacher's name?">What is your favorite teacher's name?</option>
                        <option value="What is your dream job?">What is your dream job?</option>
                    </select>
                </div>
                <div class="input-container">
                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <input type="text" name="answer2" placeholder="Your answer" required>
                </div>
            </div>

            <div class="security-question-group">
                <div class="question-number">Question 3</div>
                <div class="input-container">
                    <select name="question3" required>
                        <option value="">Select a question</option>
                        <option value="What was the name of your first school?">What was the name of your first school?</option>
                        <option value="What is your favorite movie?">What is your favorite movie?</option>
                        <option value="What was your childhood nickname?">What was your childhood nickname?</option>
                    </select>
                </div>
                <div class="input-container">
                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <input type="text" name="answer3" placeholder="Your answer" required>
                </div>
            </div>

            <button type="submit" class="login-btn">Save Security Questions</button>

            <div class="signup-link">
                <span>Already have security questions set up? </span>
                <a href="logIn.php">Back to Login</a>
            </div>
        </form>
    </div>
</body>
</html>