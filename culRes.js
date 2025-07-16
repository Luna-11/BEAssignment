// Culinary Resources functionality

// Resource category filtering
function showResourceCategory(category) {
  const resourceCards = document.querySelectorAll(
    ".resource-card, .video-card, .technique-card, .hack-item, .planning-card",
  )
  const categoryButtons = document.querySelectorAll(".category-btn")

  // Update active button
  categoryButtons.forEach((btn) => btn.classList.remove("active"))
  event.target.classList.add("active")

  // Show/hide resources based on category
  resourceCards.forEach((card) => {
    if (category === "all" || card.dataset.category === category) {
      card.style.display = "block"
      card.parentElement.style.display = "block"
    } else {
      card.style.display = "none"
    }
  })

  // Hide empty sections
  const sections = document.querySelectorAll(".resource-section")
  sections.forEach((section) => {
    const visibleCards = section.querySelectorAll('[style*="display: block"], [style=""]')
    if (category !== "all" && visibleCards.length === 0) {
      section.style.display = "none"
    } else {
      section.style.display = "block"
    }
  })
}

// Download resource function
function downloadResource(resourceId) {
  const resources = {
    "italian-classics": {
      name: "Italian Classics Recipe Cards",
      type: "PDF",
      size: "2.3 MB",
    },
    "asian-fusion": {
      name: "Asian Fusion Favorites",
      type: "PDF",
      size: "1.8 MB",
    },
    "healthy-meal-prep": {
      name: "Healthy Meal Prep Guide",
      type: "PDF",
      size: "3.1 MB",
    },
    "dessert-collection": {
      name: "Sweet Treats Collection",
      type: "PDF",
      size: "1.5 MB",
    },
    "kitchen-hacks-guide": {
      name: "Complete Kitchen Hacks Guide",
      type: "PDF",
      size: "4.2 MB",
    },
    "weekly-planner": {
      name: "Weekly Meal Planner Template",
      type: "PDF",
      size: "0.8 MB",
    },
    "grocery-list": {
      name: "Smart Grocery List Template",
      type: "PDF",
      size: "0.5 MB",
    },
    "meal-prep-guide": {
      name: "Meal Prep Guide",
      type: "PDF",
      size: "2.1 MB",
    },
    "budget-planning": {
      name: "Budget Meal Planning Guide",
      type: "PDF",
      size: "1.7 MB",
    },
  }

  const resource = resources[resourceId]
  if (resource) {
    // Simulate download
    console.log(`Downloading: ${resource.name} (${resource.type}, ${resource.size})`)
    alert(`Downloading ${resource.name}...\n\nThis would start the download in a real application.`)

    // Track download (in real app, this would be sent to analytics)
    trackDownload(resourceId, resource.name)
  } else {
    alert("Resource not found. Please try again.")
  }
}

// Download technique guide
function downloadTechnique(techniqueId) {
  const techniques = {
    grilling: "Grilling & BBQ Techniques Guide",
    sauteing: "Sautéing & Pan-Frying Guide",
    braising: "Braising & Slow Cooking Guide",
    roasting: "Roasting & Baking Guide",
  }

  const techniqueName = techniques[techniqueId]
  if (techniqueName) {
    console.log(`Downloading: ${techniqueName}`)
    alert(
      `Downloading ${techniqueName}...\n\nThis comprehensive guide includes step-by-step instructions, tips, and troubleshooting advice.`,
    )
    trackDownload(techniqueId, techniqueName)
  }
}

// Play video function
function playVideo(videoId) {
  const videos = {
    "knife-skills": {
      title: "Essential Knife Skills",
      duration: "12:45",
      description: "Learn proper knife techniques for safe and efficient cooking",
    },
    "pasta-making": {
      title: "Homemade Pasta from Scratch",
      duration: "18:30",
      description: "Master the art of making fresh pasta at home",
    },
    "bread-baking": {
      title: "Artisan Bread Baking",
      duration: "25:15",
      description: "Create bakery-quality bread in your home kitchen",
    },
    "sauce-making": {
      title: "5 Essential Mother Sauces",
      duration: "15:20",
      description: "Learn the foundation sauces every cook should know",
    },
  }

  const video = videos[videoId]
  if (video) {
    const modal = document.getElementById("videoModal")
    const videoPlayer = document.getElementById("video-player")

    videoPlayer.innerHTML = `
      <div style="text-align: center; color: white;">
        <h2>${video.title}</h2>
        <p>${video.description}</p>
        <p>Duration: ${video.duration}</p>
        <br>
        <p style="font-size: 1rem; opacity: 0.8;">
          Video player would load here in a real application
        </p>
        <button class="btn" onclick="closeVideoModal()" style="margin-top: 20px;">
          Close Video
        </button>
      </div>
    `

    modal.style.display = "block"

    // Track video play
    trackVideoPlay(videoId, video.title)
  }
}

// Close video modal
function closeVideoModal() {
  document.getElementById("videoModal").style.display = "none"
}

// Track download function (for analytics)
function trackDownload(resourceId, resourceName) {
  // In a real application, this would send data to analytics service
  console.log("Download tracked:", {
    resourceId: resourceId,
    resourceName: resourceName,
    timestamp: new Date().toISOString(),
    userId: "anonymous", // Placeholder for currentUser.email
  })
}

// Track video play function (for analytics)
function trackVideoPlay(videoId, videoTitle) {
  // In a real application, this would send data to analytics service
  console.log("Video play tracked:", {
    videoId: videoId,
    videoTitle: videoTitle,
    timestamp: new Date().toISOString(),
    userId: "anonymous", // Placeholder for currentUser.email
  })
}

// Initialize page
document.addEventListener("DOMContentLoaded", () => {
  console.log("Culinary Resources page loaded")

  // Add smooth scrolling to section links
  const sectionLinks = document.querySelectorAll('a[href^="#"]')
  sectionLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault()
      const targetId = this.getAttribute("href").substring(1)
      const targetElement = document.getElementById(targetId)
      if (targetElement) {
        targetElement.scrollIntoView({
          behavior: "smooth",
          block: "start",
        })
      }
    })
  })
})

// Search functionality for resources
function searchResources() {
  const searchTerm = document.getElementById("resource-search")
  if (!searchTerm) return

  const term = searchTerm.value.toLowerCase()
  const resourceCards = document.querySelectorAll(".resource-card, .video-card, .technique-card, .planning-card")

  resourceCards.forEach((card) => {
    const title = card.querySelector("h3").textContent.toLowerCase()
    const description = card.querySelector("p").textContent.toLowerCase()

    if (title.includes(term) || description.includes(term)) {
      card.style.display = "block"
    } else {
      card.style.display = "none"
    }
  })
}

// Add search functionality if search input exists
document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("resource-search")
  if (searchInput) {
    searchInput.addEventListener("input", () => {
      searchResources()
    })
  }
})

// Placeholder for currentUser and debounce
const currentUser = { email: "user@example.com" } // Replace with actual user data retrieval
function debounce(func, wait) {
  let timeout
  return function (...args) {
    
    clearTimeout(timeout)
    timeout = setTimeout(() => func.apply(this, args), wait)
  }
}
