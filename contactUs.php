<?php
session_start();
require_once 'configMysql.php'; // Your database connection

// Check if user is logged in and get user data
$userID = null;
$users = null;

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    
    // Fetch user details from database - UPDATED TO MATCH YOUR USERS TABLE
    $sql = "SELECT id, first_name, last_name, mail FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_assoc();
    $stmt->close();
}

// Check for contact form messages
$message = '';
$message_type = '';

if (isset($_SESSION['contact_message'])) {
    $message = $_SESSION['contact_message'];
    $message_type = $_SESSION['contact_message_type'];
    
    // Clear the session messages
    unset($_SESSION['contact_message']);
    unset($_SESSION['contact_message_type']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - FoodFusion</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --food-primary: #C89091;
      --food-text: #7b4e48;
      --food-lightest: #fcfaf2;
      --food-light-pink: #e9d0cb;
      --food-light-yellow: #f9f1e5;
      --food-medium-pink: #ddb2b1;
    }
    
    .food-primary-bg {
      background-color: var(--food-primary);
    }
    
    .food-light-pink-bg {
      background-color: var(--food-light-pink);
    }
    
    .food-light-yellow-bg {
      background-color: var(--food-light-yellow);
    }
    
    .food-medium-pink-bg {
      background-color: var(--food-medium-pink);
    }
    
    .food-text {
      color: var(--food-text);
    }
    
    .food-primary-text {
      color: var(--food-primary);
    }
  </style>
</head>
<body class="bg-[#fcfaf2] text-[#7b4e48] font-sans min-h-screen flex flex-col">

<!-- Navigation -->
    <?php include('navbar.php'); ?>

<!-- Hero Section -->
<section class="food-light-yellow-bg py-16 relative">
  <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('./BEpics/bannerFood.jpg');"></div>
  <div class="absolute inset-0 bg-black opacity-40"></div>
  <div class="container mx-auto px-4 text-center relative z-10">
    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Contact Us</h1>
    <p class="text-xl max-w-2xl mx-auto text-white">We'd love to hear from you! Reach out with enquiries, recipe requests, or feedback.</p>
  </div>
</section>

<!-- Contact Content -->
<section class="py-12">
  <div class="container mx-auto px-4">
    <div class="grid md:grid-cols-2 gap-12 max-w-6xl mx-auto">

      <!-- Contact Info -->
      <div class="food-light-pink-bg rounded-lg p-8">
        <h2 class="text-3xl font-bold food-text mb-6">Get in Touch</h2>
        <p class="text-lg mb-8">Whether you have questions about recipes, want to share feedback, or need cooking advice, we're here to help!</p>

        <div class="space-y-8">
          <!-- Email -->
          <div class="flex items-start">
            <div class="food-primary-text text-2xl mr-4 mt-1">
              <i class="fas fa-envelope"></i>
            </div>
            <div>
              <h3 class="text-xl font-bold food-text mb-2">Email Us</h3>
              <p class="mb-1">hello@foodfusion.com</p>
              <p>support@foodfusion.com</p>
            </div>
          </div>

          <!-- Phone -->
          <div class="flex items-start">
            <div class="food-primary-text text-2xl mr-4 mt-1">
              <i class="fas fa-phone"></i>
            </div>
            <div>
              <h3 class="text-xl font-bold food-text mb-2">Call Us</h3>
              <p class="mb-1">+1 (555) 123-4567</p>
              <p>Mon–Fri, 9AM–6PM EST</p>
            </div>
          </div>
        </div>

        <!-- Social Media -->
        <div class="mt-10">
          <h3 class="text-xl font-bold food-text mb-4">Connect With Us</h3>
          <div class="flex space-x-4">
            <a href="https://www.facebook.com/" class="food-primary-text text-2xl hover:opacity-80">
              <i class="fab fa-facebook"></i>
            </a>
            <a href="https://www.instagram.com/" class="food-primary-text text-2xl hover:opacity-80">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="https://x.com/?lang=en" class="food-primary-text text-2xl hover:opacity-80">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="https://www.youtube.com/" class="food-primary-text text-2xl hover:opacity-80">
              <i class="fab fa-youtube"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Contact Form -->
      <div class="food-light-yellow-bg rounded-lg p-8">
        <!-- Success/Error Message -->
        <?php if ($message): ?>
          <div class="p-4 mb-6 rounded <?= $message_type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
            <?= htmlspecialchars($message) ?>
          </div>
        <?php endif; ?>

        <form id="contactForm" class="contact-form" method="POST" action="contactSave.php">
          <h2 class="text-3xl font-bold food-text mb-6">Send us a Message</h2>

          <?php if (!$userID): ?>
            <!-- Guest user: must fill name + email -->
            <div class="grid md:grid-cols-2 gap-4 mb-4">
              <div>
                <label for="firstName" class="block food-text font-medium mb-2">First Name</label>
                <input type="text" id="firstName" name="firstName" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C89091] focus:border-transparent">
              </div>
              <div>
                <label for="lastName" class="block food-text font-medium mb-2">Last Name</label>
                <input type="text" id="lastName" name="lastName" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C89091] focus:border-transparent">
              </div>
            </div>

            <div class="mb-4">
              <label for="email" class="block food-text font-medium mb-2">Email Address</label>
              <input type="email" id="email" name="email" required 
                     class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C89091] focus:border-transparent">
            </div>
          <?php else: ?>
            <!-- Logged in user: autofill -->
            <?php if ($users): ?>
              <input type="hidden" name="userID" value="<?= htmlspecialchars($users['id']) ?>">
              <input type="hidden" name="firstName" value="<?= htmlspecialchars($users['first_name']) ?>">
              <input type="hidden" name="lastName" value="<?= htmlspecialchars($users['last_name']) ?>">
              <input type="hidden" name="email" value="<?= htmlspecialchars($users['mail']) ?>">
              <div class="mb-4 p-4 bg-[#e9d0cb] rounded-lg">
                <p class="food-text">
                  <strong>Logged in as:</strong><br>
                  <?= htmlspecialchars($users['first_name'] . " " . $users['last_name']) ?><br>
                  <?= htmlspecialchars($users['mail']) ?>
                </p>
              </div>
            <?php else: ?>
              <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">
                <p><strong>Error:</strong> User not found in database.</p>
              </div>
            <?php endif; ?>
          <?php endif; ?>

          <!-- Subject -->
          <div class="mb-4">
            <label for="subject" class="block food-text font-medium mb-2">Subject</label>
            <input type="text" id="subject" name="subject" required placeholder="Enter subject..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C89091] focus:border-transparent">
          </div>

          <!-- Message -->
          <div class="mb-6">
            <label for="message" class="block food-text font-medium mb-2">Message</label>
            <textarea id="message" name="message" rows="6" placeholder="Tell us how we can help you..." required 
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C89091] focus:border-transparent"></textarea>
          </div>

          <!-- Submit Button -->
          <button type="submit" 
                  class="w-full food-primary-bg text-white py-3 px-6 rounded-lg font-bold text-lg hover:bg-[#b58081] transition duration-300">
            Send Message
          </button>
          
          <p id="formResponse" class="mt-4 text-center"></p>
        </form>
      </div>

    </div>
  </div>
</section>

<!-- Footer -->
    <?php include('footer.php'); ?>

<script>
  
  document.getElementById('contactForm').addEventListener('submit', function(e) {
    const formData = new FormData(this);
    let isValid = true;
    
    // Check required fields
    for (let [key, value] of formData.entries()) {
      if (!value.trim() && key !== 'userID') { // userID can be empty for guests
        isValid = false;
        break;
      }
    }
    
    if (!isValid) {
      e.preventDefault();
      document.getElementById('formResponse').textContent = 'Please fill in all required fields.';
      document.getElementById('formResponse').className = 'mt-4 text-center text-red-600';
      return;
    }
    
    // Show loading message but allow form to submit
    document.getElementById('formResponse').textContent = 'Sending message...';
    document.getElementById('formResponse').className = 'mt-4 text-center text-blue-600';
  });
</script>

</body>
</html>