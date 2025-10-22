<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Popup</title>
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
    }
    
    .popup {
      display: flex;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.6);
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    .popup-content {
      background: var(--lightest-color);
      padding: 30px;
      border-radius: 20px;
      width: 350px;
      text-align: center;
      position: relative;
      box-shadow: 0 10px 30px var(--shadow-color);
    }

    .close {
      position: absolute;
      top: 15px;
      right: 20px;
      font-size: 20px;
      color: var(--medium-gray);
      cursor: pointer;
    }

    .form-header h1 {
      font-size: 28px;
      margin-bottom: 25px;
      color: var(--black);
    }

    .input-group {
      margin-bottom: 20px;
      text-align: left;
    }

    .input-container {
      position: relative;
      margin-bottom: 5px;
    }

    .input-container input {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid var(--light_pink);
      border-radius: 30px;
      font-size: 16px;
      outline: none;
      transition: border 0.3s;
      box-sizing: border-box;
    }

    .input-container input:focus {
      border-color: var(--primary-color);
    }

    .input-container input.error-border {
      border-color: #d9534f;
    }

    .input-container input.success-border {
      border-color: #5cb85c;
    }

    .error {
      color: #d9534f;
      font-size: 14px;
      margin-top: 5px;
      text-align: left;
      padding-left: 15px;
    }

    .success {
      color: #5cb85c;
      font-size: 16px;
      margin: 15px 0;
      padding: 10px;
      background-color: #dff0d8;
      border-radius: 5px;
    }

    .submit-btn {
      width: 100%;
      padding: 12px;
      background: var(--medium_pink);
      color: var(--white);
      font-size: 16px;
      border: none;
      border-radius: 30px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .submit-btn:hover {
      background: var(--primary-color);
    }

    .submit-btn:disabled {
      background: var(--light-gray);
      cursor: not-allowed;
    }

    .signup-link {
      margin-top: 15px;
      font-size: 14px;
      color: var(--text-color);
    }

    .signup-link a {
      color: var(--primary-color);
      text-decoration: none;
    }

    .signup-link a:hover {
      text-decoration: underline;
    }

    .spinner {
      display: none;
      border: 4px solid rgba(0, 0, 0, 0.1);
      border-left-color: var(--primary-color);
      border-radius: 50%;
      width: 20px;
      height: 20px;
      animation: spin 1s linear infinite;
      margin: 0 auto;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .password-match {
      color: #5cb85c;
      font-size: 14px;
      margin-top: 5px;
      text-align: left;
      padding-left: 15px;
    }
  </style>
</head>
<body>

  <div class="popup" id="registerPopup">
    <div class="popup-content">
      <span class="close" onclick="closePopup()">&times;</span>
      <div class="form-header">
        <h1>Sign Up Now</h1>
      </div>
      
      <div id="message"></div>
      
      <form id="registrationForm">
        <div class="input-group">
          <div class="input-container">
            <input type="text" id="first_name" name="first_name" placeholder="First Name" required>
          </div>
          <div id="firstNameError" class="error"></div>
          <div class="input-container">
            <input type="text" id="last_name" name="last_name" placeholder="Last Name" required>
          </div>
          <div id="lastNameError" class="error"></div>
        </div>
        <div class="input-group">
          <div class="input-container">
            <input type="email" id="email" name="email" placeholder="Email" required>
          </div>
          <div id="emailError" class="error"></div>
        </div>
        <div class="input-group">
          <div class="input-container">
            <input type="password" id="password" name="password" placeholder="Password" required>
          </div>
          <div id="passwordError" class="error"></div>
        </div>
        <div class="input-group">
          <div class="input-container">
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
          </div>
          <div id="confirmPasswordError" class="error"></div>
          <div id="passwordMatch" class="password-match"></div>
        </div>
        <button type="submit" class="submit-btn" id="submitBtn">
          <span id="btnText">Sign Up</span>
          <div class="spinner" id="spinner"></div>
        </button>
      </form>
      
      <div class="signup-link">
        Already have an account? <a href="logIn.php" onclick="showLogin()">Sign in</a>
      </div>
    </div>
  </div>

  <script>
    function closePopup() {
      document.getElementById('registerPopup').style.display = 'none';
    }

    window.onclick = function(event) {
      const popup = document.getElementById('registerPopup');
      if (event.target === popup) {
        closePopup();
      }
    }

    function showLogin() {
      alert('Login functionality would go here');
    }

    // Field Validation Functions
    function validateNotEmpty(value, fieldName) {
      return value.trim() !== '';
    }

    function validateEmail(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
    }

    function validatePassword(password) {
      // Updated to match PHP validation (minimum 6 characters)
      return password.length >= 6;
    }

    function validatePasswordMatch(password, confirmPassword) {
      return password === confirmPassword;
    }

    function updatePasswordMatchStatus() {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirm_password').value;
      const passwordMatchDiv = document.getElementById('passwordMatch');
      const confirmPasswordError = document.getElementById('confirmPasswordError');
      const confirmPasswordInput = document.getElementById('confirm_password');

      // Clear previous messages
      passwordMatchDiv.textContent = '';
      confirmPasswordError.textContent = '';

      // Remove previous border classes
      confirmPasswordInput.classList.remove('error-border', 'success-border');

      if (confirmPassword === '') {
        return false;
      }

      if (validatePasswordMatch(password, confirmPassword)) {
        passwordMatchDiv.textContent = '✓ Passwords match';
        confirmPasswordInput.classList.add('success-border');
        return true;
      } else {
        confirmPasswordError.textContent = 'Passwords do not match';
        confirmPasswordInput.classList.add('error-border');
        return false;
      }
    }

    function validateAllFields() {
      const firstName = document.getElementById('first_name').value;
      const lastName = document.getElementById('last_name').value;
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirm_password').value;
      
      let isValid = true;

      // Clear previous errors
      document.getElementById('firstNameError').textContent = '';
      document.getElementById('lastNameError').textContent = '';
      document.getElementById('emailError').textContent = '';
      document.getElementById('passwordError').textContent = '';
      document.getElementById('confirmPasswordError').textContent = '';

      // Remove error borders
      document.getElementById('first_name').classList.remove('error-border');
      document.getElementById('last_name').classList.remove('error-border');
      document.getElementById('email').classList.remove('error-border');
      document.getElementById('password').classList.remove('error-border');
      document.getElementById('confirm_password').classList.remove('error-border');

      // Validate First Name
      if (!validateNotEmpty(firstName, 'First Name')) {
        document.getElementById('firstNameError').textContent = 'First name is required';
        document.getElementById('first_name').classList.add('error-border');
        isValid = false;
      }

      // Validate Last Name
      if (!validateNotEmpty(lastName, 'Last Name')) {
        document.getElementById('lastNameError').textContent = 'Last name is required';
        document.getElementById('last_name').classList.add('error-border');
        isValid = false;
      }

      // Validate Email
      if (!validateNotEmpty(email, 'Email')) {
        document.getElementById('emailError').textContent = 'Email is required';
        document.getElementById('email').classList.add('error-border');
        isValid = false;
      } else if (!validateEmail(email)) {
        document.getElementById('emailError').textContent = 'Please enter a valid email address';
        document.getElementById('email').classList.add('error-border');
        isValid = false;
      }

      // Validate Password
      if (!validateNotEmpty(password, 'Password')) {
        document.getElementById('passwordError').textContent = 'Password is required';
        document.getElementById('password').classList.add('error-border');
        isValid = false;
      } else if (!validatePassword(password)) {
        document.getElementById('passwordError').textContent = 'Password must be at least 6 characters long';
        document.getElementById('password').classList.add('error-border');
        isValid = false;
      }

      // Validate Password Match
      if (!validateNotEmpty(confirmPassword, 'Confirm Password')) {
        document.getElementById('confirmPasswordError').textContent = 'Please confirm your password';
        document.getElementById('confirm_password').classList.add('error-border');
        isValid = false;
      } else if (!validatePasswordMatch(password, confirmPassword)) {
        document.getElementById('confirmPasswordError').textContent = 'Passwords do not match';
        document.getElementById('confirm_password').classList.add('error-border');
        isValid = false;
      }

      return isValid;
    }

    function redirectToSecurityQuestions(userId) {
      if (userId) {
        window.location.href = `securityQuestions.php?user_id=${userId}`;
      } else {
        console.error('No user ID provided for redirect');
        // Fallback to login page
        window.location.href = 'logIn.php';
      }
    }

    // Event listeners for real-time validation
    document.getElementById('password').addEventListener('input', updatePasswordMatchStatus);
    document.getElementById('confirm_password').addEventListener('input', updatePasswordMatchStatus);

    // Real-time validation for other fields on blur
    document.getElementById('first_name').addEventListener('blur', function() {
      if (!validateNotEmpty(this.value, 'First Name')) {
        document.getElementById('firstNameError').textContent = 'First name is required';
        this.classList.add('error-border');
      } else {
        document.getElementById('firstNameError').textContent = '';
        this.classList.remove('error-border');
      }
    });

    document.getElementById('last_name').addEventListener('blur', function() {
      if (!validateNotEmpty(this.value, 'Last Name')) {
        document.getElementById('lastNameError').textContent = 'Last name is required';
        this.classList.add('error-border');
      } else {
        document.getElementById('lastNameError').textContent = '';
        this.classList.remove('error-border');
      }
    });

    document.getElementById('email').addEventListener('blur', function() {
      if (!validateNotEmpty(this.value, 'Email')) {
        document.getElementById('emailError').textContent = 'Email is required';
        this.classList.add('error-border');
      } else if (!validateEmail(this.value)) {
        document.getElementById('emailError').textContent = 'Please enter a valid email address';
        this.classList.add('error-border');
      } else {
        document.getElementById('emailError').textContent = '';
        this.classList.remove('error-border');
      }
    });

    document.getElementById('registrationForm').addEventListener('submit', function(e) {
      e.preventDefault();

      // Validate all fields before submission
      if (!validateAllFields()) {
        return; // Stop submission if validation fails
      }
      
      const formData = new FormData(this);

      // Show loading state
      document.getElementById('submitBtn').disabled = true;
      document.getElementById('btnText').style.display = 'none';
      document.getElementById('spinner').style.display = 'block';
      
      // Send data to PHP file using Fetch API
      fetch('registerPopUp.php', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        document.getElementById('submitBtn').disabled = false;
        document.getElementById('btnText').style.display = 'block';
        document.getElementById('spinner').style.display = 'none';
        
        const messageDiv = document.getElementById('message');
        messageDiv.innerHTML = '';

        if (data.success) {
          messageDiv.className = 'success';
          messageDiv.innerHTML = data.message;
          document.getElementById('registrationForm').reset();
          
          // Redirect to security questions after successful registration
          setTimeout(() => {
            if (data.user_id) {
              redirectToSecurityQuestions(data.user_id);
            } else if (data.redirect_url) {
              window.location.href = data.redirect_url;
            } else {
              // Fallback: redirect to login page
              window.location.href = 'logIn.php';
            }
          }, 1500);
        } else {
          messageDiv.className = 'error';
          messageDiv.innerHTML = data.message || 'An error occurred. Please try again.';
        }
      })
      .catch(error => {
        document.getElementById('submitBtn').disabled = false;
        document.getElementById('btnText').style.display = 'block';
        document.getElementById('spinner').style.display = 'none';
        
        const messageDiv = document.getElementById('message');
        messageDiv.className = 'error';
        messageDiv.innerHTML = 'Network error: ' + error.message;
        console.error('Registration error:', error);
      });
    });
  </script>

</body>
</html>