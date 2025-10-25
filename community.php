<?php
// Include the handler at the top
include('community_handler.php');

// Get comment counts for all posts
$postCommentCounts = [];
if (!empty($posts)) {
    foreach ($posts as $post) {
        $commentCountQuery = $conn->prepare("SELECT COUNT(*) as count FROM comment WHERE communityID = ?");
        $commentCountQuery->bind_param("i", $post['communityID']);
        $commentCountQuery->execute();
        $commentResult = $commentCountQuery->get_result();
        $commentCount = $commentResult->fetch_assoc()['count'];
        $commentCountQuery->close();
        
        $postCommentCounts[$post['communityID']] = $commentCount;
    }
}
?>
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
        .error-message {
            background-color: #fee;
            border: 1px solid #fcc;
            color: #c00;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        
        .success-message {
            background-color: #efe;
            border: 1px solid #cfc;
            color: #0c0;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
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
        
        .comment-count {
            background-color: #C89091;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            margin-left: 0.25rem;
        }
        
        .bg-cookbook {
            /* Replace with your actual image URL */
            background-image: url('./BEpics/bn1.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .bg-cookbook::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0.8;
            z-index: 1;
        }
        
        .bg-cookbook > * {
            position: relative;
            z-index: 2;
        }
        
        .file-upload-label:hover {
            border-color: #C89091;
            background-color: #fcfaf2;
        }
        
        .preview-container img, .preview-container video {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            object-fit: cover;
        }
        
        .preview-container {
            position: relative;
            display: inline-block;
            margin-top: 1rem;
        }
        
        .remove-media-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }
        
        .upload-status {
            margin-top: 0.5rem;
            font-size: 0.875rem;
        }
        
        /* Enhanced Scrollbar Styling for Create Post Modal */
        .modal-scroll-area {
            overflow-y: auto;
            max-height: 400px;
            padding-right: 8px;
            scrollbar-width: thin;
            scrollbar-color: #C89091 #f9f1e5;
        }
        
        .modal-scroll-area::-webkit-scrollbar {
            width: 8px;
        }
        
        .modal-scroll-area::-webkit-scrollbar-track {
            background: #f9f1e5;
            border-radius: 4px;
            margin: 4px 0;
        }
        
        .modal-scroll-area::-webkit-scrollbar-thumb {
            background-color: #C89091;
            border-radius: 4px;
            border: 2px solid #f9f1e5;
        }
        
        .modal-scroll-area::-webkit-scrollbar-thumb:hover {
            background-color: #ddb2b1;
        }
        
        /* Always show scrollbar for Webkit browsers */
        .modal-scroll-area::-webkit-scrollbar-thumb {
            visibility: visible;
        }
        
        /* Scrollbar for Comments Modal */
        #commentsList {
            scrollbar-width: thin;
            scrollbar-color: #C89091 #f9f1e5;
        }
        
        #commentsList::-webkit-scrollbar {
            width: 6px;
        }
        
        #commentsList::-webkit-scrollbar-track {
            background: #f9f1e5;
            border-radius: 4px;
        }
        
        #commentsList::-webkit-scrollbar-thumb {
            background-color: #C89091;
            border-radius: 4px;
        }
        
        #commentsList::-webkit-scrollbar-thumb:hover {
            background-color: #ddb2b1;
        }
        
        /* Custom styling to match your design */
        .recipe-format {
            font-size: 0.8rem;
            color: #555;
            margin-bottom: 0.1rem;
        }
        
        .char-count {
            font-size: 0.8rem;
            color: #555;
            text-align: right;
        }
    </style>
</head>
<body class="bg-light-yellow min-h-screen flex flex-col">
    <!-- Navbar -->    
    <?php include('navbar.php'); ?>
    <div>
        <!-- Community Hero -->
    <section class="bg-cookbook text-white py-14 px-5 text-center">
        <div class="container mx-auto">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Community Cookbook</h1>
            <p class="text-xl opacity-90 max-w-2xl mx-auto">Share your culinary adventures with fellow food enthusiasts</p>
        </div>
    </section>

<!-- Create Post Modal -->
<div id="createPostModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
  <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">

    <!-- Header (fixed) -->
    <div class="p-4 border-b border-border flex justify-between items-center shrink-0">
      <h2 class="text-xl font-bold text-text">Share Your Recipe</h2>
      <button id="closePostModal" class="text-light-gray text-xl hover:text-black">&times;</button>
    </div>

    <!-- Scrollable Form Area - UPDATED -->
    <form id="createPostForm" method="POST" enctype="multipart/form-data" 
          class="flex-1 p-6 flex flex-col">
      
      <div class="modal-scroll-area flex-1 space-y-4">
        <!-- Post Content -->
        <div>
          <label for="post_content" class="block text-sm font-medium text-text mb-2">What's cooking?</label>
          <textarea 
            id="post_content" 
            name="post_content" 
            rows="2" 
            placeholder="Share your recipe, cooking tips, or food experience..."
            required
            class="w-full p-1 border-2 border-border rounded focus:border-primary outline-none resize-none"
            maxlength="1000"></textarea>
          <div class="recipe-format">
            <strong>Format: Recipe Title. Description</strong>
          </div>
          <div class="char-count">
            <span id="postCharCount">0/1000</span>
          </div>
        </div>

        <!-- File Upload -->
        <div>
          <label class="block text-sm font-medium text-text mb-2">Add Media (Optional)</label>
          <input type="file" id="media" name="media" accept="image/*,video/*" class="hidden">
          <label for="media" class="file-upload-label cursor-pointer">
            <i class="fas fa-cloud-upload-alt text-xl text-primary mb-2"></i>
            <span class="text-text font-medium">Click to upload image or video</span>
            <span class="text-sm text-medium-gray mt-1">Supports JPG, PNG, GIF, MP4, MOV</span>
          </label>

          <!-- Preview Container -->
          <div id="previewContainer" class="preview-container hidden">
            <button type="button" id="removeMedia" class="remove-media-btn">
              <i class="fas fa-times"></i>
            </button>
            <div id="mediaPreview"></div>
          </div>

          <!-- Upload Status -->
          <div id="uploadStatus" class="upload-status text-sm text-medium-gray"></div>
        </div>
      </div>

    </form>

    <!-- Footer Buttons (fixed) -->
    <div class="p-6 border-t border-border bg-white shrink-0 flex space-x-4">
      <button type="button" id="cancelPost" 
              class="flex-1 bg-gray-200 text-gray-700 p-3 rounded hover:bg-gray-300 font-semibold transition-colors">
        Cancel
      </button>
      <button type="submit" form="createPostForm" name="submit_post"
              class="flex-1 bg-primary text-white p-3 rounded hover:bg-medium-pink font-semibold transition-colors">
        Share Post
      </button>
    </div>

  </div>
</div>


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

                    <!-- Display Messages -->
                    <?php if (!empty($error)): ?>
                        <div class="error-message mb-4">
                            <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
                            <?php if (strpos($error, 'permission') !== false): ?>
                                <br><small>Please ensure the 'uploads/post' directory exists and has write permissions (chmod 755).</small>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success)): ?>
                        <div class="success-message mb-4"><?php echo htmlspecialchars($success); ?></div>
                    <?php endif; ?>

                    <!-- Posts List -->
                    <div id="postsList">
                        <?php if (!empty($posts)): ?>
                            <?php foreach ($posts as $post): ?>
                                <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
                                    <div class="p-6 pb-0">
                                        <!-- In the posts display section of community.php -->
                                        <div class="flex items-center space-x-4 mb-4">
                                            <div class="w-11 h-11 bg-primary rounded-full flex items-center justify-center text-white">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-black">
                                                    <?php 
                                                    if (isset($post['first_name'])) {
                                                        echo htmlspecialchars($post['first_name']);
                                                    } elseif (isset($post['username'])) {
                                                        echo htmlspecialchars($post['username']);
                                                    } elseif (isset($post['userID'])) {
                                                        echo "User " . htmlspecialchars($post['userID']);
                                                    } else {
                                                        echo "User";
                                                    }
                                                    ?>
                                                </h4>
                                                <p class="text-sm text-medium-gray">
                                                    <?php echo date('F j, Y g:i A', strtotime($post['postDate'])); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="p-6 pt-0">
                                        <?php
                                        $postContent = $post['post'];
                                        $titleEnd = strpos($postContent, ':');
                                        if ($titleEnd !== false) {
                                            $displayTitle = substr($postContent, 0, $titleEnd);
                                            $displayDescription = substr($postContent, $titleEnd + 1);
                                        } else {
                                            $displayTitle = $postContent;
                                            $displayDescription = '';
                                        }
                                        ?>
                                        
                                        <h3 class="text-xl font-semibold text-black mb-4">
                                            <?php echo htmlspecialchars(trim($displayTitle)); ?>
                                        </h3>
                                        <p class="text-text mb-4">
                                            <?php echo htmlspecialchars(trim($displayDescription)); ?>
                                        </p>
                                        
                                        <?php if (!empty($post['media'])): ?>
                                            <div class="mb-6 relative">
                                                <?php if ($post['mediaType'] == 'image'): ?>
                                                    <img src="uploads/post/<?php echo htmlspecialchars($post['media']); ?>" 
                                                         alt="<?php echo htmlspecialchars($displayTitle); ?>" 
                                                         class="w-full rounded-lg object-cover h-88"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                                    <div class="error-message hidden">Error loading image</div>
                                                <?php elseif ($post['mediaType'] == 'video'): ?>
                                                    <video controls class="w-full rounded-lg object-cover h-64">
                                                        <source src="uploads/post/<?php echo htmlspecialchars($post['media']); ?>" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-sm text-medium-gray mb-4">
                                                No media attached to this post
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="border-t border-border pt-4 flex justify-between items-center">
                                            <div class="flex space-x-4">
                                                <button class="comment-btn flex items-center space-x-2 text-medium-gray hover:text-primary transition-colors" 
                                                        data-post-id="<?php echo $post['communityID']; ?>">
                                                    <i class="far fa-comment"></i>
                                                    <span>Comment</span>
                                                    <?php if (isset($postCommentCounts[$post['communityID']]) && $postCommentCounts[$post['communityID']] > 0): ?>
                                                        <span class="comment-count"><?php echo $postCommentCounts[$post['communityID']]; ?></span>
                                                    <?php endif; ?>
                                                </button>
                                            </div>
                                            <div class="text-sm text-medium-gray">
                                                <?php echo isset($postCommentCounts[$post['communityID']]) ? $postCommentCounts[$post['communityID']] : 0; ?> 
                                                comments
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                                <i class="fas fa-utensils text-4xl text-primary mb-4"></i>
                                <h3 class="text-xl font-semibold text-text mb-2">No posts yet</h3>
                                <p class="text-medium-gray">Be the first to share your culinary creation!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>

<!-- Comments Modal -->
<div id="commentsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
  <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[80vh] flex flex-col overflow-hidden">
    
    <!-- Header -->
    <div class="p-6 border-b border-border flex justify-between items-center">
      <div>
        <h2 class="text-2xl font-bold text-text">Comments</h2>
        <div id="commentsPostTitle" class="text-sm text-medium-gray mt-2"></div>
      </div>
      <button id="closeComments" class="text-light-gray text-2xl hover:text-black">&times;</button>
    </div>

    <!-- Scrollable Comments List -->
    <div id="commentsList" class="flex-1 p-6 overflow-y-auto bg-light-yellow">
      <div class="text-center text-medium-gray">Loading comments...</div>
    </div>

    <!-- Add Comment Form -->
    <div class="p-6 border-t border-border bg-white">
      <form id="addCommentForm" class="space-y-4">
        <input type="hidden" id="currentCommunityID" name="communityID">

        <div>
          <textarea 
            id="commentText" 
            name="comment" 
            rows="3" 
            placeholder="Write your comment..." 
            required
            class="w-full p-3 border-2 border-border rounded focus:border-primary outline-none resize-none"
            maxlength="300"></textarea>

          <div class="text-sm text-medium-gray mt-1 flex justify-between">
            <span>Max 300 characters</span>
            <span id="charCount">0/300</span>
          </div>
        </div>

        <button 
          type="submit" 
          class="w-full bg-primary text-white p-3 rounded hover:bg-medium-pink font-semibold transition-colors">
          Post Comment
        </button>
      </form>
    </div>
  </div>
</div>



    <!-- Include the external JavaScript files -->
    <script src="community_script.js"></script>
    
    <!-- JavaScript for Modal Functionality and Scroll Bars -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Create Post Modal Elements
            const createPostModal = document.getElementById('createPostModal');
            const shareRecipeBtn = document.getElementById('shareRecipeBtn');
            const closePostModal = document.getElementById('closePostModal');
            const cancelPost = document.getElementById('cancelPost');
            const mediaInput = document.getElementById('media');
            const previewContainer = document.getElementById('previewContainer');
            const mediaPreview = document.getElementById('mediaPreview');
            const removeMediaBtn = document.getElementById('removeMedia');
            const uploadStatus = document.getElementById('uploadStatus');
            const postContent = document.getElementById('post_content');
            const postCharCount = document.getElementById('postCharCount');
            const commentText = document.getElementById('commentText');
            const charCount = document.getElementById('charCount');

            // Comments Modal Elements
            const commentsModal = document.getElementById('commentsModal');
            const closeComments = document.getElementById('closeComments');
            const commentBtns = document.querySelectorAll('.comment-btn');

            // Open Create Post Modal
            if (shareRecipeBtn) {
                shareRecipeBtn.addEventListener('click', function() {
                    createPostModal.classList.remove('hidden');
                    createPostModal.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                });
            }

            // Close Create Post Modal
            function closeCreatePostModal() {
                createPostModal.classList.remove('flex');
                createPostModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                // Reset form
                document.getElementById('createPostForm').reset();
                previewContainer.classList.add('hidden');
                uploadStatus.textContent = '';
                postCharCount.textContent = '0/1000';
            }

            if (closePostModal) {
                closePostModal.addEventListener('click', closeCreatePostModal);
            }

            if (cancelPost) {
                cancelPost.addEventListener('click', closeCreatePostModal);
            }

            // Character count for post content
            if (postContent) {
                postContent.addEventListener('input', function() {
                    const count = this.value.length;
                    postCharCount.textContent = `${count}/1000`;
                });
            }

            // Character count for comment
            if (commentText) {
                commentText.addEventListener('input', function() {
                    const count = this.value.length;
                    charCount.textContent = `${count}/300`;
                });
            }

            // Media upload preview
            if (mediaInput) {
                mediaInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const fileType = file.type.split('/')[0];
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            mediaPreview.innerHTML = '';
                            
                            if (fileType === 'image') {
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.alt = 'Preview';
                                mediaPreview.appendChild(img);
                            } else if (fileType === 'video') {
                                const video = document.createElement('video');
                                video.src = e.target.result;
                                video.controls = true;
                                mediaPreview.appendChild(video);
                            }
                            
                            previewContainer.classList.remove('hidden');
                            uploadStatus.textContent = `Selected: ${file.name}`;
                        };
                        
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Remove media
            if (removeMediaBtn) {
                removeMediaBtn.addEventListener('click', function() {
                    mediaInput.value = '';
                    previewContainer.classList.add('hidden');
                    uploadStatus.textContent = '';
                });
            }

            // Comments Modal functionality
            if (commentBtns) {
                commentBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const postId = this.getAttribute('data-post-id');
                        // You would typically load comments for this post ID here
                        commentsModal.classList.remove('hidden');
                        commentsModal.classList.add('flex');
                        document.body.style.overflow = 'hidden';
                    });
                });
            }

            if (closeComments) {
                closeComments.addEventListener('click', function() {
                    commentsModal.classList.remove('flex');
                    commentsModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                });
            }

            // Close modals when clicking outside
            window.addEventListener('click', function(e) {
                if (e.target === createPostModal) {
                    closeCreatePostModal();
                }
                if (e.target === commentsModal) {
                    commentsModal.classList.remove('flex');
                    commentsModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
            });
        });
    </script>
    
    <?php if (!empty($success)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.location.href = window.location.href.split('?')[0];
            }, 2000);
        });
    </script>
    <?php endif; ?>

</body>
</html>