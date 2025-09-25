<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Cookbook - FoodFusion</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#C89091',
                        'text': '#7b4e48',
                        'lightest': '#fcfaf2',
                        'light-pink': '#e9d0cb',
                        'medium-pink': '#ddb2b1',
                        'light-yellow': '#f9f1e5',
                        'white': '#fff',
                        'black': '#222',
                        'light-gray': '#bbb',
                        'medium-gray': '#555',
                        'border': '#ccc',
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Custom animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(50px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .slide-up {
            animation: slideUp 0.5s ease-out;
        }
        
        /* Custom file upload styling */
        .file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        
        .file-upload input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            border: 2px dashed #ccc;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: #f9f9f9;
        }
        
        .file-upload-label:hover {
            border-color: #C89091;
            background-color: #fcfaf2;
        }
        
        .file-upload-label.has-file {
            border-color: #C89091;
            background-color: #f9f1e5;
        }
        
        .preview-container {
            display: none;
            margin-top: 1rem;
            position: relative;
        }
        
        .preview-container img, .preview-container video {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            object-fit: cover;
        }
        
        .remove-media {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
    </style>
</head>
<body class="bg-light-yellow min-h-screen flex flex-col">
    <!-- Navbar -->
  <?php include('navbar.php'); ?>

    <!-- Add padding to account for fixed navbar -->
    <div>
        <!-- Community Hero -->
        <section class="bg-gradient-to-br from-primary to-medium-pink text-white py-32 px-5 text-center">
            <div class="container mx-auto">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Community Cookbook</h1>
                <p class="text-xl opacity-90 max-w-2xl mx-auto">Share your culinary adventures with fellow food enthusiasts</p>
            </div>
        </section>

        <!-- Community Feed -->
        <section class="py-12 flex-grow">
            <div class="container mx-auto px-4">
                <div class="max-w-2xl mx-auto">
                    <!-- Create Post Widget -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white text-xl">
                                <i class="fas fa-user"></i>
                            </div>
                            <button id="shareRecipeBtn" class="flex-grow bg-light-yellow text-medium-gray text-left px-4 py-3 rounded-full hover:bg-light-pink transition-colors">
                                What's cooking today?
                            </button>
                        </div>
                    </div>

                    <!-- Posts List -->
                    <div id="postsList">
                        <!-- Posts will be populated by JavaScript -->
                        <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
                            <div class="p-6 pb-0">
                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="w-11 h-11 bg-primary rounded-full flex items-center justify-center text-white">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-black">Jane Smith</h4>
                                        <p class="text-sm text-medium-gray">2 hours ago</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-6 pt-0">
                                <h3 class="text-xl font-semibold text-black mb-4">Homemade Pasta Carbonara</h3>
                                <p class="text-text mb-4">Just tried this classic Italian dish and it turned out amazing! The secret is using fresh eggs and high-quality Parmesan.</p>
                                
                                <div class="mb-4">
                                    <img src="https://images.unsplash.com/photo-1621996346565-e3dbc353d2e5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1180&q=80" alt="Pasta Carbonara" class="w-full rounded-lg object-cover h-64">
                                </div>
                                
                                <div class="border-t border-border pt-4 flex justify-between items-center">
                                    <div class="flex space-x-4">
                                        <button class="like-btn flex items-center space-x-2 text-medium-gray hover:text-primary transition-colors">
                                            <i class="far fa-heart"></i>
                                            <span>Like</span>
                                        </button>
                                        <button class="flex items-center space-x-2 text-medium-gray hover:text-primary transition-colors">
                                            <i class="far fa-comment"></i>
                                            <span>Comment</span>
                                        </button>
                                    </div>
                                    <div class="text-sm text-medium-gray">15 likes • 3 comments</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Share Recipe Modal - Fixed positioning -->
<div id="shareRecipeModal" class="modal-overlay fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-3xl max-h-[75vh] overflow-y-auto p-6 md:p-8">
            <div class="p-6 border-b border-border">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-text">Share Your Creation</h2>
                    <button id="closeShareRecipe" class="text-light-gray text-2xl hover:text-black transition-colors">&times;</button>
                </div>
            </div>
            
<form id="recipeForm" class="p-6 space-y-4" enctype="multipart/form-data" action="post_action.php" method="POST">
    <div>
        <label class="block font-semibold text-text mb-2">Post Title</label>
        <input type="text" name="title" required class="w-full p-3 border-2 border-border rounded focus:border-primary outline-none transition-colors" placeholder="Give your post a catchy title">
    </div>
    
    <div>
        <label class="block font-semibold text-text mb-2">Description</label>
        <textarea name="description" rows="3" placeholder="Tell us about your culinary creation..." required class="w-full p-3 border-2 border-border rounded focus:border-primary outline-none transition-colors"></textarea>
    </div>
    
    <div>
        <label class="block font-semibold text-text mb-2">Add Media (Image or Video)</label>
        <div class="file-upload">
            <input type="file" id="mediaUpload" name="media" accept="image/*,video/*" class="file-input">
            <label for="mediaUpload" id="fileUploadLabel" class="file-upload-label">
                <i class="fas fa-cloud-upload-alt text-4xl text-primary mb-2"></i>
                <span class="font-semibold text-text">Click to upload an image or video</span>
                <span class="text-sm text-medium-gray mt-1">Supports JPG, PNG, MP4, MOV (Max 10MB)</span>
            </label>
        </div>
        <div id="previewContainer" class="preview-container">
            <button type="button" id="removeMedia" class="remove-media">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    
    <button type="submit" class="w-full bg-primary text-white p-4 rounded hover:bg-medium-pink transition-colors font-semibold text-lg">Share Post</button>
</form>

        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-border py-8 mt-auto">
        <div class="container mx-auto px-4 text-center">
            <div class="flex justify-center space-x-6 mb-4">
                <a href="#" class="text-medium-gray hover:text-primary transition-colors">About</a>
                <a href="#" class="text-medium-gray hover:text-primary transition-colors">Contact</a>
                <a href="#" class="text-medium-gray hover:text-primary transition-colors">Privacy Policy</a>
                <a href="#" class="text-medium-gray hover:text-primary transition-colors">Terms of Service</a>
            </div>
            <p class="text-medium-gray">&copy; 2023 FoodFusion. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Modal functionality
        const shareRecipeBtn = document.getElementById('shareRecipeBtn');
        const shareRecipeModal = document.getElementById('shareRecipeModal');
        const closeShareRecipe = document.getElementById('closeShareRecipe');
        const recipeForm = document.getElementById('recipeForm');
        const mediaUpload = document.getElementById('mediaUpload');
        const fileUploadLabel = document.getElementById('fileUploadLabel');
        const previewContainer = document.getElementById('previewContainer');
        const removeMedia = document.getElementById('removeMedia');
        
        shareRecipeBtn.addEventListener('click', () => {
            shareRecipeModal.classList.remove('hidden');
            shareRecipeModal.classList.add('flex');
            // Prevent body scrolling when modal is open
            document.body.style.overflow = 'hidden';
        });
        
        closeShareRecipe.addEventListener('click', () => {
            shareRecipeModal.classList.add('hidden');
            shareRecipeModal.classList.remove('flex');
            resetForm();
            // Restore body scrolling
            document.body.style.overflow = 'auto';
        });
        
        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === shareRecipeModal) {
                shareRecipeModal.classList.add('hidden');
                shareRecipeModal.classList.remove('flex');
                resetForm();
                // Restore body scrolling
                document.body.style.overflow = 'auto';
            }
        });
        
        // File upload preview functionality
        mediaUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileType = file.type.split('/')[0]; // 'image' or 'video'
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewContainer.innerHTML = '';
                    
                    if (fileType === 'image') {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Uploaded image';
                        previewContainer.appendChild(img);
                    } else if (fileType === 'video') {
                        const video = document.createElement('video');
                        video.src = e.target.result;
                        video.controls = true;
                        previewContainer.appendChild(video);
                    }
                    
                    // Add remove button
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'remove-media';
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                    removeBtn.addEventListener('click', function() {
                        mediaUpload.value = '';
                        previewContainer.style.display = 'none';
                        fileUploadLabel.classList.remove('has-file');
                        fileUploadLabel.innerHTML = `
                            <i class="fas fa-cloud-upload-alt text-4xl text-primary mb-2"></i>
                            <span class="font-semibold text-text">Click to upload an image or video</span>
                            <span class="text-sm text-medium-gray mt-1">Supports JPG, PNG, MP4, MOV (Max 10MB)</span>
                        `;
                    });
                    previewContainer.appendChild(removeBtn);
                    
                    previewContainer.style.display = 'block';
                    fileUploadLabel.classList.add('has-file');
                    fileUploadLabel.innerHTML = `
                        <i class="fas fa-check-circle text-4xl text-primary mb-2"></i>
                        <span class="font-semibold text-text">File selected: ${file.name}</span>
                        <span class="text-sm text-medium-gray mt-1">Click to change file</span>
                    `;
                };
                
                reader.readAsDataURL(file);
            }
        });
        
        // Remove media functionality
        removeMedia.addEventListener('click', function() {
            mediaUpload.value = '';
            previewContainer.style.display = 'none';
            fileUploadLabel.classList.remove('has-file');
            fileUploadLabel.innerHTML = `
                <i class="fas fa-cloud-upload-alt text-4xl text-primary mb-2"></i>
                <span class="font-semibold text-text">Click to upload an image or video</span>
                <span class="text-sm text-medium-gray mt-1">Supports JPG, PNG, MP4, MOV (Max 10MB)</span>
            `;
        });
        
        // Form submission
        recipeForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(recipeForm);
            const title = formData.get('title');
            const description = formData.get('description');
            const mediaFile = formData.get('media');
            
            // Create new post (in a real app, this would send to a server)
            createPost(title, description, mediaFile);
            
            // Reset form and close modal
            resetForm();
            shareRecipeModal.classList.add('hidden');
            shareRecipeModal.classList.remove('flex');
            // Restore body scrolling
            document.body.style.overflow = 'auto';
        });
        
        // Function to reset the form
        function resetForm() {
            recipeForm.reset();
            previewContainer.style.display = 'none';
            fileUploadLabel.classList.remove('has-file');
            fileUploadLabel.innerHTML = `
                <i class="fas fa-cloud-upload-alt text-4xl text-primary mb-2"></i>
                <span class="font-semibold text-text">Click to upload an image or video</span>
                <span class="text-sm text-medium-gray mt-1">Supports JPG, PNG, MP4, MOV (Max 10MB)</span>
            `;
        }
        
        // Function to create a new post
        function createPost(title, description, mediaFile) {
            const postsList = document.getElementById('postsList');
            
            // Create post element
            const postElement = document.createElement('div');
            postElement.className = 'bg-white rounded-lg shadow-md mb-6 overflow-hidden slide-up';
            
            let mediaHTML = '';
            if (mediaFile) {
                const fileType = mediaFile.type.split('/')[0];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    if (fileType === 'image') {
                        mediaHTML = `<div class="mb-4">
                            <img src="${e.target.result}" alt="${title}" class="w-full rounded-lg object-cover h-64">
                        </div>`;
                    } else if (fileType === 'video') {
                        mediaHTML = `<div class="mb-4">
                            <video src="${e.target.result}" controls class="w-full rounded-lg object-cover h-64"></video>
                        </div>`;
                    }
                    
                    postElement.innerHTML = `
                        <div class="p-6 pb-0">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-11 h-11 bg-primary rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-black">You</h4>
                                    <p class="text-sm text-medium-gray">Just now</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6 pt-0">
                            <h3 class="text-xl font-semibold text-black mb-4">${title}</h3>
                            <p class="text-text mb-4">${description}</p>
                            
                            ${mediaHTML}
                            
                            <div class="border-t border-border pt-4 flex justify-between items-center">
                                <div class="flex space-x-4">
                                    <button class="like-btn flex items-center space-x-2 text-medium-gray hover:text-primary transition-colors">
                                        <i class="far fa-heart"></i>
                                        <span>Like</span>
                                    </button>
                                    <button class="flex items-center space-x-2 text-medium-gray hover:text-primary transition-colors">
                                        <i class="far fa-comment"></i>
                                        <span>Comment</span>
                                    </button>
                                </div>
                                <div class="text-sm text-medium-gray"><span class="like-count">0</span> likes • 0 comments</div>
                            </div>
                        </div>
                    `;
                    
                    // Add like functionality
                    const likeBtn = postElement.querySelector('.like-btn');
                    const likeIcon = likeBtn.querySelector('i');
                    const likeCount = postElement.querySelector('.like-count');
                    
                    let isLiked = false;
                    let likes = 0;
                    
                    likeBtn.addEventListener('click', () => {
                        isLiked = !isLiked;
                        likes = isLiked ? likes + 1 : likes - 1;
                        
                        likeIcon.className = isLiked ? 'fas fa-heart text-primary' : 'far fa-heart';
                        likeCount.textContent = likes;
                    });
                    
                    // Add post to the top of the list
                    postsList.insertBefore(postElement, postsList.firstChild);
                };
                
                reader.readAsDataURL(mediaFile);
            } else {
                // If no media file, create post without media
                postElement.innerHTML = `
                    <div class="p-6 pb-0">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-11 h-11 bg-primary rounded-full flex items-center justify-center text-white">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-black">You</h4>
                                <p class="text-sm text-medium-gray">Just now</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 pt-0">
                        <h3 class="text-xl font-semibold text-black mb-4">${title}</h3>
                        <p class="text-text mb-4">${description}</p>
                        
                        <div class="border-t border-border pt-4 flex justify-between items-center">
                            <div class="flex space-x-4">
                                <button class="like-btn flex items-center space-x-2 text-medium-gray hover:text-primary transition-colors">
                                    <i class="far fa-heart"></i>
                                    <span>Like</span>
                                </button>
                                <button class="flex items-center space-x-2 text-medium-gray hover:text-primary transition-colors">
                                    <i class="far fa-comment"></i>
                                    <span>Comment</span>
                                </button>
                            </div>
                            <div class="text-sm text-medium-gray"><span class="like-count">0</span> likes • 0 comments</div>
                        </div>
                    </div>
                `;
                
                // Add like functionality
                const likeBtn = postElement.querySelector('.like-btn');
                const likeIcon = likeBtn.querySelector('i');
                const likeCount = postElement.querySelector('.like-count');
                
                let isLiked = false;
                let likes = 0;
                
                likeBtn.addEventListener('click', () => {
                    isLiked = !isLiked;
                    likes = isLiked ? likes + 1 : likes - 1;
                    
                    likeIcon.className = isLiked ? 'fas fa-heart text-primary' : 'far fa-heart';
                    likeCount.textContent = likes;
                });
                
                // Add post to the top of the list
                postsList.insertBefore(postElement, postsList.firstChild);
            }
        }
        
        // Like functionality for existing posts
        document.querySelectorAll('.like-btn').forEach(btn => {
            const likeIcon = btn.querySelector('i');
            const likeCount = btn.closest('.border-t').querySelector('.like-count');
            
            let isLiked = false;
            let likes = parseInt(likeCount.textContent);
            
            btn.addEventListener('click', () => {
                isLiked = !isLiked;
                likes = isLiked ? likes + 1 : likes - 1;
                
                likeIcon.className = isLiked ? 'fas fa-heart text-primary' : 'far fa-heart';
                likeCount.textContent = likes;
            });
        });
    </script>
</body>
</html>