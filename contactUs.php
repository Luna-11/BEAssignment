<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
include('configMysql.php');

// Try different paths for functions.php
$functionPaths = [
    './functions.php',
    'functions.php',
    __DIR__ . '/functions.php',
    dirname(__FILE__) . '/functions.php'
];

$functionsLoaded = false;
foreach ($functionPaths as $path) {
    if (file_exists($path)) {
        include($path);
        $functionsLoaded = true;
        break;
    }
}

// If functions.php still not found, handle gracefully
if (!$functionsLoaded) {
    function showUser($userID) {
        return [];
    }
}

// Fetch user data if logged in
$userData = [];
if (isset($_SESSION['userID'])) {
    $userData = showUser($_SESSION['userID']);
    $userData = !empty($userData) ? $userData[0] : [];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['contactFirstName'])) {
    // Sanitize input
    $firstName = trim(filter_var($_POST['contactFirstName'], FILTER_SANITIZE_STRING));
    $lastName = trim(filter_var($_POST['contactLastName'], FILTER_SANITIZE_STRING));
    $email = trim(filter_var($_POST['contactEmail'], FILTER_SANITIZE_EMAIL));
    $subject = trim(filter_var($_POST['contactSubject'], FILTER_SANITIZE_STRING));
    $message = trim(filter_var($_POST['contactMessage'], FILTER_SANITIZE_STRING));

    // Validate required fields
    if (empty($firstName) || empty($lastName) || empty($email) || empty($subject) || empty($message)) {
        $errorMessage = 'All fields are required';
    }
    // Validate email format
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'Invalid email format';
    } else {
        // Set userID (NULL if not logged in)
        $userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : NULL;

        try {
            // Prepare SQL statement
            $stmt = $conn->prepare("INSERT INTO contact_form (userID, FirstName, LastName, email, subject, message, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            
            if ($stmt) {
                $stmt->bind_param("isssss", $userID, $firstName, $lastName, $email, $subject, $message);
                
                if ($stmt->execute()) {
                    $successMessage = 'Thank you for your message! We will get back to you soon.';
                    // Clear form fields after successful submission
                    $_POST = array();
                } else {
                    $errorMessage = 'Failed to send message. Please try again.';
                }
                $stmt->close();
            } else {
                $errorMessage = 'Database error. Please try again.';
            }
        } catch (Exception $e) {
            $errorMessage = 'Sorry, there was an error sending your message. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - FoodFusion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
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
            --shadow-color: rgba(0,0,0,0.1);
            --border-color: #ccc;
            --button-color: #333;
        }

        /* Modal animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom styles for modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            background-color: var(--white);
            margin: 5% auto;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            position: relative;
            box-shadow: 0 10px 30px var(--shadow-color);
            animation: slideIn 0.3s ease;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            right: 20px;
            top: 15px;
        }

        .close:hover {
            color: var(--text-color);
        }

        /* Custom form styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-color);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
            font-family: inherit;
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .btn {
            background: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn:hover {
            background: var(--medium_pink);
            transform: translateY(-2px);
        }

        .modal-link {
            text-align: center;
            margin-top: 15px;
        }

        .modal-link a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .modal-link a:hover {
            text-decoration: underline;
        }

        /* Success/Error message styles */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 8px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body class="bg-[#fcfaf2] font-sans">
    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>

    <!-- Contact Header -->
    <section class="bg-cover bg-center bg-no-repeat py-20" style="background-image: url('./BEpics/banner3.jpg');">
        <div class="container mx-auto px-4 text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Contact Us</h1>
            <p class="text-xl md:text-2xl opacity-90 max-w-2xl mx-auto">We'd love to hear from you! Get in touch with any questions, recipe requests, or feedback.</p>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="py-16">
        <div class="container mx-auto px-4 max-w-6xl">
            <!-- Display success/error messages -->
            <?php if (isset($successMessage)): ?>
                <div class="alert alert-success mb-6">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($errorMessage)): ?>
                <div class="alert alert-error mb-6">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Contact Info -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                        <i class="fas fa-envelope text-4xl text-[#C89091] mb-4"></i>
                        <h3 class="text-xl font-bold text-[#7b4e48] mb-3">Email Us</h3>
                        <p class="text-gray-600">hello@foodfusion.com</p>
                        <p class="text-gray-600">support@foodfusion.com</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                        <i class="fas fa-phone text-4xl text-[#C89091] mb-4"></i>
                        <h3 class="text-xl font-bold text-[#7b4e48] mb-3">Call Us</h3>
                        <p class="text-gray-600">+1 (555) 123-4567</p>
                        <p class="text-gray-600">Mon-Fri: 9AM-6PM EST</p>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-[#7b4e48] mb-6 text-center">Send us a Message</h2>
                    <form id="contactForm" action="contactUs.php" method="POST" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="form-group">
                                <label for="contactFirstName">First Name</label>
                                <input type="text" id="contactFirstName" name="contactFirstName" 
                                       value="<?php echo isset($_POST['contactFirstName']) ? htmlspecialchars($_POST['contactFirstName']) : (isset($userData['firstName']) ? htmlspecialchars($userData['firstName']) : ''); ?>" 
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="contactLastName">Last Name</label>
                                <input type="text" id="contactLastName" name="contactLastName" 
                                       value="<?php echo isset($_POST['contactLastName']) ? htmlspecialchars($_POST['contactLastName']) : (isset($userData['lastName']) ? htmlspecialchars($userData['lastName']) : ''); ?>" 
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="contactEmail">Email Address</label>
                                <input type="email" id="contactEmail" name="contactEmail" 
                                       value="<?php echo isset($_POST['contactEmail']) ? htmlspecialchars($_POST['contactEmail']) : (isset($userData['email']) ? htmlspecialchars($userData['email']) : ''); ?>" 
                                       required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="contactSubject">Subject</label>
                            <select id="contactSubject" name="contactSubject" required>
                                <option value="">Select a subject</option>
                                <option value="general" <?php echo (isset($_POST['contactSubject']) && $_POST['contactSubject'] == 'general') ? 'selected' : ''; ?>>General Inquiry</option>
                                <option value="recipe-request" <?php echo (isset($_POST['contactSubject']) && $_POST['contactSubject'] == 'recipe-request') ? 'selected' : ''; ?>>Recipe Request</option>
                                <option value="feedback" <?php echo (isset($_POST['contactSubject']) && $_POST['contactSubject'] == 'feedback') ? 'selected' : ''; ?>>Feedback</option>
                                <option value="technical" <?php echo (isset($_POST['contactSubject']) && $_POST['contactSubject'] == 'technical') ? 'selected' : ''; ?>>Technical Support</option>
                                <option value="partnership" <?php echo (isset($_POST['contactSubject']) && $_POST['contactSubject'] == 'partnership') ? 'selected' : ''; ?>>Partnership</option>
                                <option value="other" <?php echo (isset($_POST['contactSubject']) && $_POST['contactSubject'] == 'other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="contactMessage">Message</label>
                            <textarea id="contactMessage" name="contactMessage" rows="6" required placeholder="Tell us how we can help you..."><?php echo isset($_POST['contactMessage']) ? htmlspecialchars($_POST['contactMessage']) : ''; ?></textarea>
                        </div>
                        <button type="submit" class="btn w-full">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Join Us Modal -->
    <div id="joinModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeJoinModal()">&times;</span>
            <h2 class="text-2xl font-bold text-[#7b4e48] mb-4">Join FoodFusion Community</h2>
            <form id="joinForm" class="space-y-4">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="lastName" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn w-full">Join Us</button>
            </form>
        </div>
    </div>

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeLoginModal()">&times;</span>
            <h2 class="text-2xl font-bold text-[#7b4e48] mb-4">Login to FoodFusion</h2>
            <form id="loginForm" class="space-y-4">
                <div class="form-group">
                    <label for="loginEmail">Email</label>
                    <input type="email" id="loginEmail" name="loginEmail" required>
                </div>
                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <input type="password" id="loginPassword" name="loginPassword" required>
                </div>
                <button type="submit" class="btn w-full">Login</button>
                <p class="modal-link">Don't have an account? <a href="#" onclick="switchToJoin()">Join us</a></p>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script>
        // Modal functionality
        function openJoinModal() {
            document.getElementById('joinModal').style.display = 'block';
            document.getElementById('loginModal').style.display = 'none';
        }

        function closeJoinModal() {
            document.getElementById('joinModal').style.display = 'none';
        }

        function openLoginModal() {
            document.getElementById('loginModal').style.display = 'block';
            document.getElementById('joinModal').style.display = 'none';
        }

        function closeLoginModal() {
            document.getElementById('loginModal').style.display = 'none';
        }

        function switchToJoin() {
            closeLoginModal();
            openJoinModal();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const joinModal = document.getElementById('joinModal');
            const loginModal = document.getElementById('loginModal');
            
            if (event.target === joinModal) {
                closeJoinModal();
            }
            if (event.target === loginModal) {
                closeLoginModal();
            }
        }

        // Modal form handlers (prevent actual submission for demo)
        document.getElementById('joinForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Welcome to FoodFusion! Your account has been created.');
            closeJoinModal();
            this.reset();
        });

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Successfully logged in!');
            closeLoginModal();
            this.reset();
        });
    </script>
</body>
</html>