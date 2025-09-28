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
    </style>
</head>
<body class="bg-light-yellow min-h-screen flex flex-col">
    <!-- Navbar -->    
    <?php include('navbar.php'); ?>

    <!-- Add padding to account for fixed navbar -->
    <div>
        <!-- Community Hero -->
        <section class="bg-gradient-to-br from-primary to-medium-pink text-white py-14 px-5 text-center">
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
                                                <button class="like-btn flex items-center space-x-2 text-medium-gray hover:text-primary transition-colors">
                                                    <i class="far fa-heart"></i>
                                                    <span>Like</span>
                                                </button>
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
                                                0 likes • 
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
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[80vh] overflow-hidden">
            <div class="p-6 border-b border-border">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-text">Comments</h2>
                    <button id="closeComments" class="text-light-gray text-2xl hover:text-black">&times;</button>
                </div>
                <div id="commentsPostTitle" class="text-sm text-medium-gray mt-2"></div>
            </div>
            
            <!-- Comments List -->
            <div id="commentsList" class="p-6 overflow-y-auto max-h-96">
                <div class="text-center text-medium-gray">Loading comments...</div>
            </div>
            
            <!-- Add Comment Form -->
            <div class="p-6 border-t border-border">
                <form id="addCommentForm" class="space-y-4">
                    <input type="hidden" id="currentCommunityID" name="communityID">
                    <div>
                        <textarea id="commentText" name="comment" rows="3" 
                                  placeholder="Write your comment..." required
                                  class="w-full p-3 border-2 border-border rounded focus:border-primary outline-none"
                                  maxlength="300"></textarea>
                        <div class="text-sm text-medium-gray mt-1 flex justify-between">
                            <span>Max 300 characters</span>
                            <span id="charCount">0/300</span>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-primary text-white p-3 rounded hover:bg-medium-pink font-semibold transition-colors">
                        Post Comment
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Share Recipe Modal -->
    <div id="shareRecipeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl max-h-[75vh] overflow-y-auto">
            <div class="p-6 border-b border-border">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-text">Share Your Creation</h2>
                    <button id="closeShareRecipe" class="text-light-gray text-2xl hover:text-black">&times;</button>
                </div>
            </div>
            
            <form id="recipeForm" class="p-6 space-y-4" enctype="multipart/form-data" method="POST">
                <div>
                    <label class="block font-semibold text-text mb-2">Post Title *</label>
                    <input type="text" name="title" required 
                           class="w-full p-3 border-2 border-border rounded focus:border-primary outline-none" 
                           placeholder="Give your post a catchy title" 
                           value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>"
                           maxlength="100">
                    <div class="text-sm text-medium-gray mt-1">Max 100 characters</div>
                </div>
                
                <div>
                    <label class="block font-semibold text-text mb-2">Description *</label>
                    <textarea name="description" rows="3" 
                              placeholder="Tell us about your culinary creation..." required
                              class="w-full p-3 border-2 border-border rounded focus:border-primary outline-none"><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
                </div>
                
                <div>
                    <label class="block font-semibold text-text mb-2">Add Media (Image or Video - Optional)</label>
                    <div class="file-upload">
                        <input type="file" id="mediaUpload" name="media" accept="image/*,video/*" class="hidden">
                        <label for="mediaUpload" id="fileUploadLabel" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt text-4xl text-primary mb-2"></i>
                            <span class="font-semibold text-text">Click to upload an image or video</span>
                            <span class="text-sm text-medium-gray mt-1">Supports JPG, PNG, GIF, WebP, MP4, WebM, OGG (Max 10MB)</span>
                        </label>
                    </div>
                    <div id="fileInfo" class="upload-status hidden"></div>
                    <div id="previewContainer" class="preview-container hidden">
                        <!-- Preview will be inserted here -->
                    </div>
                </div>
                
                <div class="pt-4">
                    <button type="submit" class="w-full bg-primary text-white p-4 rounded hover:bg-medium-pink font-semibold text-lg transition-colors">
                        Share Post
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Include the external JavaScript files -->
    <script src="community_script.js"></script>
    
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