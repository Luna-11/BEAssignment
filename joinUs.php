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
          <div class="input-container">
            <input type="text" id="last_name" name="last_name" placeholder="Last Name" required>
          </div>
        </div>
        <div class="input-group">
          <div class="input-container">
            <input type="email" id="email" name="email" placeholder="Email" required>
          </div>
        </div>
        <div class="input-group">
          <div class="input-container">
            <input type="password" id="password" name="password" placeholder="Password" required>
          </div>
          <div id="passwordError" class="error"></div>
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

    // Password Validation
    function validatePassword(password) {
      const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[a-z]).{8,}$/;
      return passwordRegex.test(password);
    }

    document.getElementById('registrationForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const password = document.getElementById('password').value;
      const passwordError = document.getElementById('passwordError');
      passwordError.textContent = "";

      if (!validatePassword(password)) {
        passwordError.textContent = "Password must be at least 8 characters, include one uppercase, one lowercase, and one number.";
        return;
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
      .then(response => response.json())
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
          setTimeout(() => closePopup(), 2000);
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
      });
    });
  </script>

</body>
</html>
