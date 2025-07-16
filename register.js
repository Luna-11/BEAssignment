document.getElementById("register-form").addEventListener("submit", function (e) {
  e.preventDefault();

  const passwordInput = document.querySelector('input[type="password"]');
  const password = passwordInput.value;

  // Password regex: at least one uppercase, one digit, min 8 characters
  const passwordRegex = /^(?=.*[A-Z])(?=.*\d).{8,}$/;

  if (!passwordRegex.test(password)) {
    alert("Password must be at least 8 characters long, contain at least one uppercase letter, and one digit.");
    return;
  }

  alert("Registration Successful!");
});
