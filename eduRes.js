// Educational Resources functionality

// Declare currentUser variable
const currentUser = { email: "user@example.com" } // Placeholder for actual user data

// Educational category filtering
function showEducationalCategory(category) {
  const educationCards = document.querySelectorAll(
    ".education-card, .science-card, .timeline-item, .culture-card, .sustainability-card, .safety-module",
  )
  const categoryButtons = document.querySelectorAll(".category-btn")

  // Update active button
  categoryButtons.forEach((btn) => btn.classList.remove("active"))
  event.target.classList.add("active")

  // Show/hide resources based on category
  educationCards.forEach((card) => {
    if (category === "all" || card.dataset.category === category) {
      card.style.display = "block"
    } else {
      card.style.display = "none"
    }
  })

  // Hide empty sections
  const sections = document.querySelectorAll(".educational-section")
  sections.forEach((section) => {
    const visibleCards = section.querySelectorAll('[style*="display: block"], [style=""]')
    if (category !== "all" && visibleCards.length === 0) {
      section.style.display = "none"
    } else {
      section.style.display = "block"
    }
  })
}

// Download educational resource
function downloadEducational(resourceId) {
  const educationalResources = {
    "macronutrients-guide": {
      name: "Understanding Macronutrients Infographic",
      type: "PDF",
      size: "1.2 MB",
    },
    "vitamins-minerals": {
      name: "Essential Vitamins & Minerals Chart",
      type: "PDF",
      size: "0.8 MB",
    },
    "healthy-cooking": {
      name: "Healthy Cooking Methods Guide",
      type: "PDF",
      size: "2.1 MB",
    },
    "portion-control": {
      name: "Portion Control Visual Guide",
      type: "PDF",
      size: "1.5 MB",
    },
    "heat-transfer": {
      name: "Heat Transfer in Cooking Guide",
      type: "PDF",
      size: "3.2 MB",
    },
    "chemical-reactions": {
      name: "Chemical Reactions in Food Guide",
      type: "PDF",
      size: "2.8 MB",
    },
    "protein-science": {
      name: "Protein Science Guide",
      type: "PDF",
      size: "2.4 MB",
    },
    "water-activity": {
      name: "Water Activity & Preservation Guide",
      type: "PDF",
      size: "1.9 MB",
    },
    "ancient-cooking": {
      name: "Ancient Cooking Methods History",
      type: "PDF",
      size: "3.5 MB",
    },
    "spice-trade": {
      name: "Spice Trade Routes Guide",
      type: "PDF",
      size: "4.1 MB",
    },
    "agricultural-revolution": {
      name: "Agricultural Revolution Impact",
      type: "PDF",
      size: "2.7 MB",
    },
    "industrial-food": {
      name: "Industrial Food Revolution",
      type: "PDF",
      size: "3.3 MB",
    },
    "asian-traditions": {
      name: "Asian Culinary Traditions Guide",
      type: "PDF",
      size: "5.2 MB",
    },
    "mediterranean-culture": {
      name: "Mediterranean Food Culture Guide",
      type: "PDF",
      size: "4.8 MB",
    },
    "indigenous-wisdom": {
      name: "Indigenous Cooking Wisdom",
      type: "PDF",
      size: "3.9 MB",
    },
    "food-waste": {
      name: "Reducing Food Waste Guide",
      type: "PDF",
      size: "2.3 MB",
    },
    "seasonal-eating": {
      name: "Seasonal & Local Eating Guide",
      type: "PDF",
      size: "3.1 MB",
    },
    "water-conservation": {
      name: "Water Conservation in Cooking",
      type: "PDF",
      size: "1.8 MB",
    },
    "carbon-footprint": {
      name: "Carbon Footprint of Food Guide",
      type: "PDF",
      size: "2.9 MB",
    },
    "temperature-guide": {
      name: "Food Safety Temperature Chart",
      type: "PDF",
      size: "0.6 MB",
    },
    "hygiene-practices": {
      name: "Kitchen Hygiene Practices Checklist",
      type: "PDF",
      size: "1.1 MB",
    },
    "storage-guide": {
      name: "Food Storage Guidelines",
      type: "PDF",
      size: "1.7 MB",
    },
  }

  const resource = educationalResources[resourceId]
  if (resource) {
    console.log(`Downloading: ${resource.name} (${resource.type}, ${resource.size})`)
    alert(`Downloading ${resource.name}...\n\nThis educational resource will help expand your culinary knowledge.`)
    trackEducationalDownload(resourceId, resource.name)
  } else {
    alert("Educational resource not found. Please try again.")
  }
}

// Start quiz function
function startQuiz(quizType) {
  const quizzes = {
    nutrition: {
      title: "Nutrition Knowledge Quiz",
      questions: 15,
      duration: 5,
      description: "Test your understanding of basic nutrition principles",
    },
    "food-safety": {
      title: "Food Safety Assessment",
      questions: 20,
      duration: 8,
      description: "Evaluate your food safety knowledge and practices",
    },
    sustainability: {
      title: "Sustainability Challenge",
      questions: 12,
      duration: 6,
      description: "Learn about sustainable cooking practices",
    },
  }

  const quiz = quizzes[quizType]
  if (quiz) {
    alert(
      `Starting ${quiz.title}!\n\n${quiz.description}\n\nQuestions: ${quiz.questions}\nEstimated time: ${quiz.duration} minutes\n\nThis would launch the interactive quiz in a real application.`,
    )

    // Track quiz start
    trackQuizStart(quizType, quiz.title)

    // In a real application, this would navigate to the quiz interface
    console.log(`Quiz started: ${quiz.title}`)
  }
}

// Track educational download
function trackEducationalDownload(resourceId, resourceName) {
  console.log("Educational download tracked:", {
    resourceId: resourceId,
    resourceName: resourceName,
    category: "educational",
    timestamp: new Date().toISOString(),
    userId: currentUser ? currentUser.email : "anonymous",
  })
}

// Track quiz start
function trackQuizStart(quizId, quizTitle) {
  console.log("Quiz start tracked:", {
    quizId: quizId,
    quizTitle: quizTitle,
    timestamp: new Date().toISOString(),
    userId: currentUser ? currentUser.email : "anonymous",
  })
}

// Initialize educational resources page
document.addEventListener("DOMContentLoaded", () => {
  console.log("Educational Resources page loaded")

  // Add animation to timeline items on scroll
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = "1"
        entry.target.style.transform = "translateY(0)"
      }
    })
  }, observerOptions)

  // Observe timeline items
  const timelineItems = document.querySelectorAll(".timeline-item")
  timelineItems.forEach((item) => {
    item.style.opacity = "0"
    item.style.transform = "translateY(30px)"
    item.style.transition = "opacity 0.6s ease, transform 0.6s ease"
    observer.observe(item)
  })

  // Observe education cards
  const educationCards = document.querySelectorAll(".education-card, .science-card, .sustainability-card")
  educationCards.forEach((card) => {
    card.style.opacity = "0"
    card.style.transform = "translateY(20px)"
    card.style.transition = "opacity 0.5s ease, transform 0.5s ease"
    observer.observe(card)
  })
})

// Add progress tracking for educational content
function trackProgress(section) {
  const progress = JSON.parse(localStorage.getItem("educationalProgress") || "{}")
  progress[section] = {
    visited: true,
    timestamp: new Date().toISOString(),
  }
  localStorage.setItem("educationalProgress", JSON.stringify(progress))
}

// Check if user has completed certain educational milestones
function checkEducationalMilestones() {
  const progress = JSON.parse(localStorage.getItem("educationalProgress") || "{}")
  const downloads = JSON.parse(localStorage.getItem("educationalDownloads") || "[]")

  const milestones = {
    first_download: downloads.length >= 1,
    nutrition_expert: progress.nutrition && downloads.includes("macronutrients-guide"),
    safety_conscious: progress["food-safety"] && downloads.includes("temperature-guide"),
    sustainability_advocate: progress.sustainability && downloads.includes("food-waste"),
  }

  return milestones
}

// Show achievement notification
function showAchievement(achievement) {
  const achievements = {
    first_download: "Knowledge Seeker - Downloaded your first educational resource!",
    nutrition_expert: "Nutrition Expert - Mastered the basics of nutrition!",
    safety_conscious: "Safety First - Committed to food safety practices!",
    sustainability_advocate: "Eco Warrior - Champion of sustainable cooking!",
  }

  if (achievements[achievement]) {
    // In a real app, this would show a nice notification
    console.log(`Achievement unlocked: ${achievements[achievement]}`)
  }
}
