document.getElementById('login-form')?.addEventListener('submit', function (e) {
  e.preventDefault();
  alert('Login successful!');
});

// Global variables
let currentUser = null
const loadRecipes = null // Declare loadRecipes variable
const loadCommunityPosts = null // Declare loadCommunityPosts variable

// DOM Content Loaded
document.addEventListener("DOMContentLoaded", () => {
  // Show cookie consent if not already accepted
  if (!localStorage.getItem("cookieConsent")) {
    showCookieConsent()
  }

  // Initialize page-specific functionality
  initializePage()
})

// Cookie Consent Functions
function showCookieConsent() {
  const cookieConsent = document.getElementById("cookie-consent")
  if (cookieConsent) {
    cookieConsent.classList.add("show")
  }
}

function hideCookieConsent() {
  const cookieConsent = document.getElementById("cookie-consent")
  if (cookieConsent) {
    cookieConsent.classList.remove("show")
  }
}

// Cookie consent event listeners
document.addEventListener("DOMContentLoaded", () => {
  const acceptBtn = document.getElementById("accept-cookies")
  const declineBtn = document.getElementById("decline-cookies")
  const settingsBtn = document.getElementById("cookie-settings")

  if (acceptBtn) {
    acceptBtn.addEventListener("click", () => {
      localStorage.setItem("cookieConsent", "accepted")
      hideCookieConsent()
    })
  }

  if (declineBtn) {
    declineBtn.addEventListener("click", () => {
      localStorage.setItem("cookieConsent", "declined")
      hideCookieConsent()
    })
  }

  if (settingsBtn) {
    settingsBtn.addEventListener("click", () => {
      showCookieSettings()
    })
  }
})

// Modal Functions
function openJoinModal() {
  document.getElementById("joinModal").style.display = "block"
}

function closeJoinModal() {
  document.getElementById("joinModal").style.display = "none"
}

function openLoginModal() {
  document.getElementById("loginModal").style.display = "block"
}

function closeLoginModal() {
  document.getElementById("loginModal").style.display = "none"
}

function switchToJoin() {
  closeLoginModal()
  openJoinModal()
}

function switchToLogin() {
  closeJoinModal()
  openLoginModal()
}

// Close modals when clicking outside
window.onclick = (event) => {
  const modals = document.querySelectorAll(".modal")
  modals.forEach((modal) => {
    if (event.target === modal) {
      modal.style.display = "none"
    }
  })
}

// Form Submissions
document.addEventListener("DOMContentLoaded", () => {
  // Join form submission
  const joinForm = document.getElementById("joinForm")
  if (joinForm) {
    joinForm.addEventListener("submit", (e) => {
      e.preventDefault()
      handleJoinSubmission()
    })
  }

  // Login form submission
  const loginForm = document.getElementById("loginForm")
  if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
      e.preventDefault()
      handleLoginSubmission()
    })
  }

  // Contact form submission
  const contactForm = document.getElementById("contactForm")
  if (contactForm) {
    contactForm.addEventListener("submit", (e) => {
      e.preventDefault()
      handleContactSubmission()
    })
  }
})

function handleJoinSubmission() {
  const formData = new FormData(document.getElementById("joinForm"))
  const userData = {
    firstName: formData.get("firstName"),
    lastName: formData.get("lastName"),
    email: formData.get("email"),
    password: formData.get("password"),
  }

  // Simulate user registration
  console.log("User registration:", userData)
  alert("Welcome to FoodFusion! Your account has been created successfully.")
  closeJoinModal()

  // Store user data (in real app, this would be sent to server)
  currentUser = userData
  updateUIForLoggedInUser()
}

function handleLoginSubmission() {
  const formData = new FormData(document.getElementById("loginForm"))
  const loginData = {
    email: formData.get("loginEmail"),
    password: formData.get("loginPassword"),
  }

  // Simulate login
  console.log("User login:", loginData)
  alert("Welcome back to FoodFusion!")
  closeLoginModal()

  // Simulate successful login
  currentUser = { email: loginData.email, firstName: "User" }
  updateUIForLoggedInUser()
}

function handleContactSubmission() {
  const formData = new FormData(document.getElementById("contactForm"))
  const contactData = {
    name: formData.get("contactName"),
    email: formData.get("contactEmail"),
    subject: formData.get("contactSubject"),
    message: formData.get("contactMessage"),
    newsletter: formData.get("newsletter"),
  }

  console.log("Contact submission:", contactData)
  alert("Thank you for your message! We'll get back to you within 24 hours.")
  document.getElementById("contactForm").reset()
}

function updateUIForLoggedInUser() {
  // Update navigation icons or user interface
  const loginIcon = document.querySelector('.nav-icons a[onclick="openLoginModal()"]')
  if (loginIcon && currentUser) {
    loginIcon.innerHTML = '<i class="fa-solid fa-user"></i>'
    loginIcon.setAttribute("onclick", "showUserMenu()")
  }
}

function showUserMenu() {
  // Show user dropdown menu
  alert(`Welcome, ${currentUser.firstName}!`)
}

// FAQ Functions
function toggleFAQ(element) {
  const answer = element.nextElementSibling
  const icon = element.querySelector("i")

  answer.classList.toggle("active")

  if (answer.classList.contains("active")) {
    icon.style.transform = "rotate(180deg)"
  } else {
    icon.style.transform = "rotate(0deg)"
  }
}

// Privacy and Cookie Policy Functions
function showPrivacyPolicy() {
  alert(
    "Privacy Policy: FoodFusion respects your privacy and is committed to protecting your personal data. We collect information to provide better services and improve user experience. Your data is never shared with third parties without consent.",
  )
}

function showCookiePolicy() {
  alert(
    "Cookie Policy: We use cookies to enhance your browsing experience, analyze site traffic, and personalize content. You can control cookie settings in your browser preferences.",
  )
}

function showCookieSettings() {
  alert(
    "Cookie Settings: You can manage your cookie preferences here. Essential cookies are required for basic functionality, while optional cookies help us improve your experience.",
  )
}

// Initialize page-specific functionality
function initializePage() {
  const currentPage = window.location.pathname.split("/").pop()

  switch (currentPage) {
    case "recipes.html":
      initializeRecipePage()
      break
    case "community.html":
      initializeCommunityPage()
      break
    case "contact.html":
      initializeContactPage()
      break
    default:
      initializeHomePage()
  }
}

function initializeHomePage() {
  // Home page specific initialization
  console.log("Home page initialized")
}

function initializeRecipePage() {
  if (typeof loadRecipes === "function") {
    loadRecipes()
  }
}

function initializeCommunityPage() {
  if (typeof loadCommunityPosts === "function") {
    loadCommunityPosts()
  }
}

function initializeContactPage() {
  console.log("Contact page initialized")
}

// Utility Functions
function formatDate(date) {
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  })
}

function formatTime(minutes) {
  if (minutes < 60) {
    return `${minutes} min`
  } else {
    const hours = Math.floor(minutes / 60)
    const remainingMinutes = minutes % 60
    return remainingMinutes > 0 ? `${hours}h ${remainingMinutes}min` : `${hours}h`
  }
}

// Search functionality
function debounce(func, wait) {
  let timeout
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout)
      func(...args)
    }
    clearTimeout(timeout)
    timeout = setTimeout(later, wait)
  }
}

