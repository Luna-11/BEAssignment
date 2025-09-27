<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Popup</title>
  <style>
    /* ===== GLOBAL STYLES ===== */
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
    /* ===== POPUP BACKGROUND ===== */
    .popup {
      display: flex; /* visible on load */
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.6);
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    /* ===== POPUP BOX ===== */
    .popup-content {
      background: var(--lightest-color);
      padding: 30px;
      border-radius: 20px;
      width: 350px;
      text-align: center;
      position: relative;
      box-shadow: 0 10px 30px var(--shadow-color);
    }

    /* Close button */
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

    /* Input fields */
    .input-group {
      margin-bottom: 20px;
      text-align: left;
    }

    .input-container {
      position: relative;
    }

    .input-container input {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid var(--light_pink);
      border-radius: 30px;
      font-size: 16px;
      outline: none;
      transition: border 0.3s;
    }

    .input-container input:focus {
      border-color: var(--primary-color);
    }

    /* Submit button */
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

    /* Sign in link */
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

  </style>
</head>
<body>

  <!-- Popup -->
  <div class="popup" id="registerPopup">
    <div class="popup-content">
      <span class="close" onclick="closePopup()">&times;</span>
      <div class="form-header">
        <h1>Create Account</h1>
      </div>
      <form>
        <div class="input-group">
          <div class="input-container">
            <input type="text" placeholder="Username" required>
          </div>
        </div>
        <div class="input-group">
          <div class="input-container">
            <input type="email" placeholder="Email" required>
          </div>
        </div>
        <div class="input-group">
          <div class="input-container">
            <input type="password" placeholder="Password" required>
          </div>
        </div>
        <button type="submit" class="submit-btn">Sign Up</button>
      </form>
      <div class="signup-link">
        Already have an account? <a href="#">Login</a>
      </div>
    </div>
  </div>

  <script>
    function closePopup() {
      // hide overlay + box
      document.getElementById('registerPopup').style.display = 'none';
    }

    // Close if user clicks outside the box
    window.onclick = function(event) {
      const popup = document.getElementById('registerPopup');
      if (event.target === popup) {
        closePopup();
      }
    }
  </script>

</body>
</html>
