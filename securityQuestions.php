<?php
require_once("configMysql.php");

// Check if user_id is provided and valid
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    die("Invalid request. User ID is required. Please complete registration first.");
}

$user_id = intval($_GET['user_id']);

// Validate that the user exists
$check_user = $conn->prepare("SELECT id, first_name FROM users WHERE id = ?");
$check_user->bind_param("i", $user_id);
$check_user->execute();
$check_user->store_result();

if ($check_user->num_rows == 0) {
    die("Invalid user ID. Please register first.");
}

$check_user->bind_result($user_id, $first_name);
$check_user->fetch();
$check_user->close();

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify user_id from form matches the one from URL
    $posted_user_id = intval($_POST["user_id"]);
    if ($posted_user_id !== $user_id) {
        $error_message = "User ID mismatch. Please try again.";
    } else {
        // Validate all fields are filled
        if (empty($_POST["question1"]) || empty($_POST["answer1"]) || 
            empty($_POST["question2"]) || empty($_POST["answer2"]) || 
            empty($_POST["question3"]) || empty($_POST["answer3"])) {
            $error_message = "All security questions and answers are required.";
        } else {
            $q1 = trim($_POST["question1"]);
            $a1 = password_hash(trim($_POST["answer1"]), PASSWORD_DEFAULT);
            $q2 = trim($_POST["question2"]);
            $a2 = password_hash(trim($_POST["answer2"]), PASSWORD_DEFAULT);
            $q3 = trim($_POST["question3"]);
            $a3 = password_hash(trim($_POST["answer3"]), PASSWORD_DEFAULT);

            // Check if questions are unique
            if ($q1 === $q2 || $q1 === $q3 || $q2 === $q3) {
                $error_message = "Please select different security questions.";
            } else {
                $stmt = $conn->prepare("INSERT INTO user_security_questions 
                    (user_id, question1, answer1, question2, answer2, question3, answer3)
                    VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issssss", $user_id, $q1, $a1, $q2, $a2, $q3, $a3);

                if ($stmt->execute()) {
                    $success_message = "Security questions saved successfully! Redirecting to login...";
                    // Redirect after 2 seconds
                    header("refresh:2;url=logIn.php");
                } else {
                    $error_message = "Error saving security questions: " . $stmt->error;
                }
                $stmt->close();
            }
        }
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
            --error-color: #d9534f;
            --success-color: #5cb85c;
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
        
        .welcome-message {
            background-color: var(--light_yellow);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
            border-left: 4px solid var(--primary-color);
        }
        
        .message {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
            font-weight: 500;
        }
        
        .message.error {
            background-color: #f8d7da;
            color: var(--error-color);
            border-left: 4px solid var(--error-color);
        }
        
        .message.success {
            background-color: #d4edda;
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
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
            appearance: none;
        }
        
        .input-container select:focus,
        .input-container input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px var(--light_pink);
        }
        
        .input-container select.error,
        .input-container input.error {
            border-color: var(--error-color);
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
        
        .login-btn:disabled {
            background-color: var(--light-gray);
            cursor: not-allowed;
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
            border: 1px solid transparent;
            transition: border-color 0.2s ease;
        }
        
        .security-question-group.error {
            border-color: var(--error-color);
            background-color: #f8d7da;
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
        
        .error-text {
            color: var(--error-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }
        
        .spinner {
            display: none;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 2px solid var(--white);
            width: 16px;
            height: 16px;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .btn-content {
            display: flex;
            align-items: center;
            justify-content: center;
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

        <?php if (!empty($first_name)): ?>
            <div class="welcome-message">
                Welcome, <strong><?php echo htmlspecialchars($first_name); ?></strong>! Please set up your security questions.
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <form method="post" class="login-form" id="securityQuestionsForm">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">

            <div class="security-question-group" id="questionGroup1">
                <div class="question-number">Question 1</div>
                <div class="input-container">
                    <select name="question1" id="question1" required>
                        <option value="">Select a question</option>
                        <option value="What is your favorite food?">What is your favorite food?</option>
                        <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
                        <option value="What is the name of your first pet?">What is the name of your first pet?</option>
                        <option value="What city were you born in?">What city were you born in?</option>
                        <option value="What is your favorite teacher's name?">What is your favorite teacher's name?</option>
                    </select>
                </div>
                <div class="input-container">
                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <input type="text" name="answer1" id="answer1" placeholder="Your answer" required>
                </div>
                <div class="error-text" id="error1"></div>
            </div>

            <div class="security-question-group" id="questionGroup2">
                <div class="question-number">Question 2</div>
                <div class="input-container">
                    <select name="question2" id="question2" required>
                        <option value="">Select a question</option>
                        <option value="What is your dream job?">What is your dream job?</option>
                        <option value="What was the name of your first school?">What was the name of your first school?</option>
                        <option value="What is your favorite movie?">What is your favorite movie?</option>
                        <option value="What was your childhood nickname?">What was your childhood nickname?</option>
                        <option value="What is the name of your favorite childhood friend?">What is the name of your favorite childhood friend?</option>
                    </select>
                </div>
                <div class="input-container">
                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <input type="text" name="answer2" id="answer2" placeholder="Your answer" required>
                </div>
                <div class="error-text" id="error2"></div>
            </div>

            <div class="security-question-group" id="questionGroup3">
                <div class="question-number">Question 3</div>
                <div class="input-container">
                    <select name="question3" id="question3" required>
                        <option value="">Select a question</option>
                        <option value="In what city did your parents meet?">In what city did your parents meet?</option>
                        <option value="What is the first name of your best friend?">What is the first name of your best friend?</option>
                        <option value="What is your favorite book?">What is your favorite book?</option>
                        <option value="What is the name of the street you grew up on?">What is the name of the street you grew up on?</option>
                        <option value="What is your favorite sports team?">What is your favorite sports team?</option>
                    </select>
                </div>
                <div class="input-container">
                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <input type="text" name="answer3" id="answer3" placeholder="Your answer" required>
                </div>
                <div class="error-text" id="error3"></div>
            </div>

            <button type="submit" class="login-btn" id="submitBtn">
                <div class="btn-content">
                    <div class="spinner" id="spinner"></div>
                    <span id="btnText">Save Security Questions</span>
                </div>
            </button>

            <div class="signup-link">
                <span>Already have security questions set up? </span>
                <a href="logIn.php">Back to Login</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('securityQuestionsForm');
            const submitBtn = document.getElementById('submitBtn');
            const spinner = document.getElementById('spinner');
            const btnText = document.getElementById('btnText');
            
            const questions = [
                document.getElementById('question1'),
                document.getElementById('question2'),
                document.getElementById('question3')
            ];
            
            const answers = [
                document.getElementById('answer1'),
                document.getElementById('answer2'),
                document.getElementById('answer3')
            ];
            
            const questionGroups = [
                document.getElementById('questionGroup1'),
                document.getElementById('questionGroup2'),
                document.getElementById('questionGroup3')
            ];
            
            const errors = [
                document.getElementById('error1'),
                document.getElementById('error2'),
                document.getElementById('error3')
            ];

            // Validate unique questions
            function validateUniqueQuestions() {
                const selectedQuestions = questions.map(q => q.value).filter(q => q !== '');
                const uniqueQuestions = new Set(selectedQuestions);
                
                // Clear previous errors
                errors.forEach(error => error.style.display = 'none');
                questionGroups.forEach(group => group.classList.remove('error'));
                
                if (selectedQuestions.length !== uniqueQuestions.size) {
                    // Find duplicates
                    const duplicates = selectedQuestions.filter((q, index) => selectedQuestions.indexOf(q) !== index);
                    
                    questions.forEach((question, index) => {
                        if (duplicates.includes(question.value)) {
                            errors[index].textContent = 'Please select a different question';
                            errors[index].style.display = 'block';
                            questionGroups[index].classList.add('error');
                        }
                    });
                    return false;
                }
                return true;
            }

            // Validate form
            function validateForm() {
                let isValid = true;
                
                // Clear previous errors
                errors.forEach(error => error.style.display = 'none');
                questionGroups.forEach(group => group.classList.remove('error'));
                
                // Check if all questions are selected
                questions.forEach((question, index) => {
                    if (!question.value) {
                        errors[index].textContent = 'Please select a security question';
                        errors[index].style.display = 'block';
                        questionGroups[index].classList.add('error');
                        isValid = false;
                    }
                });
                
                // Check if all answers are provided
                answers.forEach((answer, index) => {
                    if (!answer.value.trim()) {
                        errors[index].textContent = 'Please provide an answer';
                        errors[index].style.display = 'block';
                        questionGroups[index].classList.add('error');
                        isValid = false;
                    }
                });
                
                // Check for unique questions
                if (!validateUniqueQuestions()) {
                    isValid = false;
                }
                
                return isValid;
            }

            // Add event listeners for real-time validation
            questions.forEach(question => {
                question.addEventListener('change', validateUniqueQuestions);
            });
            
            answers.forEach(answer => {
                answer.addEventListener('input', function() {
                    const index = answers.indexOf(this);
                    if (this.value.trim()) {
                        errors[index].style.display = 'none';
                        questionGroups[index].classList.remove('error');
                    }
                });
            });

            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!validateForm()) {
                    return;
                }
                
                // Show loading state
                submitBtn.disabled = true;
                spinner.style.display = 'inline-block';
                btnText.textContent = 'Saving...';
                
                // Submit the form
                this.submit();
            });
            
            // Auto-validate on page load if there were previous errors
            validateUniqueQuestions();
        });
    </script>
</body>
</html>