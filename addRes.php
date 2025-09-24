<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Resources</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'primary': '#C89091',
            'text-color': '#7b4e48',
            'lightest': '#fcfaf2',
            'light-pink': '#e9d0cb',
            'medium-pink': '#ddb2b1',
            'light-yellow': '#f9f1e5',
            'white': '#fff',
            'black': '#222',
            'light-gray': '#bbb',
            'medium-gray': '#555',
            'shadow-color': 'rgba(0,0,0,0.1)',
            'border-color': '#ccc',
            'button-color': '#333'
          },
          fontFamily: {
            'segoe': ['Segoe UI', 'Tahoma', 'Geneva', 'Verdana', 'sans-serif'],
            'pacifico': ['Pacifico', 'cursive']
          }
        }
      }
    }
  </script>
  <style>
    canvas {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      pointer-events: none;
    }
  </style>
</head>
<body class="bg-light-yellow text-text-color font-segoe min-h-screen relative">
  <canvas id="leavesCanvas"></canvas>
  <?php include('nav.php'); ?>

  <div class="pb-12 relative z-10">
    <header class="bg-primary text-white text-center py-12 sm:py-16 rounded-b-2xl mb-8">
      <h1 class="font-pacifico text-4xl sm:text-5xl mb-2">Add a Resource</h1>
      <p class="text-lg sm:text-xl opacity-90">Upload an Educational or Culinary resource with an optional PDF and video.</p>
    </header>

    <div class="max-w-2xl mx-auto w-[90%] bg-lightest rounded-2xl shadow-2xl p-6 sm:p-8">
      <form action="resourcesAction.php" method="post" enctype="multipart/form-data">
        <div class="mb-6">
          <label for="resourceTitle" class="block font-semibold text-text-color mb-2">Title</label>
          <input type="text" class="w-full p-3 border border-border-color rounded-lg bg-white text-text-color focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/50" id="resourceTitle" name="resourceTitle" placeholder="e.g. Mastering Knife Skills" required>
        </div>

        <div class="mb-6">
          <label for="description" class="block font-semibold text-text-color mb-2">Description</label>
          <textarea class="w-full p-3 border border-border-color rounded-lg bg-white text-text-color focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/50" id="description" name="description" rows="4" placeholder="Brief summary of the resource..." required></textarea>
          <div class="text-medium-gray opacity-90 text-sm mt-1">Max 200 characters recommended.</div>
        </div>

        <div class="mb-6">
          <label for="resourceType" class="block font-semibold text-text-color mb-2">Resource Type</label>
          <select class="w-full p-3 border border-border-color rounded-lg bg-white text-text-color focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/50" id="resourceType" name="resourceType" required>
            <option value="">Select type...</option>
            <option value="1">Educational</option>
            <option value="2">Culinary</option>
          </select>
        </div>

        <div class="mb-6">
          <label for="resourcesImage" class="block font-semibold text-text-color mb-2">Resource Image</label>
          <input class="w-full p-3 border border-border-color rounded-lg bg-white text-text-color file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-light-pink file:text-text-color" type="file" id="resourcesImage" name="resourcesImage" accept="image/*" required>
        </div>

        <div class="mb-6">
          <label for="PDFfile" class="block font-semibold text-text-color mb-2">PDF (optional)</label>
          <input class="w-full p-3 border border-border-color rounded-lg bg-white text-text-color file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-light-pink file:text-text-color" type="file" id="PDFfile" name="PDFfile" accept="application/pdf">
          <div class="text-medium-gray opacity-90 text-sm mt-1">Only PDF up to ~10MB.</div>
        </div>

        <div class="mb-6">
          <label for="Video" class="block font-semibold text-text-color mb-2">Video (optional)</label>
          <input class="w-full p-3 border border-border-color rounded-lg bg-white text-text-color file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-light-pink file:text-text-color" type="file" id="Video" name="Video" accept="video/mp4,video/webm,video/ogg">
          <div class="text-medium-gray opacity-90 text-sm mt-1">Formats: MP4, WebM, Ogg. Up to ~50MB.</div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <button type="submit" class="bg-primary text-white px-8 py-3 rounded-lg font-medium hover:bg-medium-pink transition-colors">Submit</button>
          <a href="educational.php" class="border border-border-color text-text-color px-8 py-3 rounded-lg font-medium hover:bg-light-pink transition-colors text-center">Back to Educational</a>
          <a href="culinary.php" class="border border-border-color text-text-color px-8 py-3 rounded-lg font-medium hover:bg-light-pink transition-colors text-center">Back to Culinary</a>
        </div>
      </form>
    </div>
  </div>

  <?php include('footer.php'); ?>

  <script>
    // Floating Leaves Animation
    const canvas = document.getElementById('leavesCanvas');
    const ctx = canvas.getContext('2d');

    // Set canvas size
    function resizeCanvas() {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
    }
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    // Leaf object
    class Leaf {
      constructor() {
        this.x = Math.random() * canvas.width;
        this.y = Math.random() * -canvas.height;
        this.size = Math.random() * 30 + 20; // Larger leaves: 20-50px
        this.speed = Math.random() * 1 + 0.5; // Slower speed: 0.5-1.5px/frame
        this.angle = Math.random() * Math.PI * 2;
        this.spin = (Math.random() - 0.5) * 0.05;
        this.color = Math.random() > 0.5 ? '#A8D5BA' : '#4A7043'; // Light and medium green
      }

      update() {
        this.y += this.speed;
        this.x += Math.sin(this.angle) * 0.5;
        this.angle += this.spin;

        // Reset leaf when it goes off-screen
        if (this.y > canvas.height + this.size) {
          this.y = -this.size;
          this.x = Math.random() * canvas.width;
          this.speed = Math.random() * 1 + 0.5;
          this.angle = Math.random() * Math.PI * 2;
          this.size = Math.random() * 30 + 20; // Ensure reset leaves are also 20-50px
        }
      }

      draw() {
        ctx.save();
        ctx.translate(this.x, this.y);
        ctx.rotate(this.angle);
        ctx.fillStyle = this.color;
        ctx.beginPath();
        ctx.ellipse(0, 0, this.size / 2, this.size / 4, 0, 0, Math.PI * 2);
        ctx.fill();
        ctx.restore();
      }
    }

    // Create leaves
    const leaves = [];
    for (let i = 0; i < 20; i++) {
      leaves.push(new Leaf());
    }

    // Animation loop
    function animate() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      leaves.forEach(leaf => {
        leaf.update();
        leaf.draw();
      });
      requestAnimationFrame(animate);
    }

    animate();
  </script>
</body>
</html>