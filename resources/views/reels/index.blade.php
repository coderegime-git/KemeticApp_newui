<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemetic Reels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6a1b9a;
            --secondary-color: #4a148c;
        }

        .header {
            background: var(--primary-color);
            padding: 1rem;
        }

        .nav-link {
            color: white !important;
        }

        .nav-link.active {
            border-bottom: 2px solid white;
        }

        .reels-container {
            max-width: 400px;
            margin: 0 auto;
            height: calc(100vh - 70px);
            overflow-y: auto;
            scroll-snap-type: y mandatory;
        }

        .reel-card {
            height: calc(100vh - 70px);
            position: relative;
            scroll-snap-align: start;
            background: #000;
        }

        .reel-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .reel-overlay {
            position: absolute;
            bottom: 0;
            right: 0;
            padding: 1rem;
            color: white;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            width: 100%;
        }

        .reel-actions {
            position: absolute;
            right: 20px;
            bottom: 100px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .action-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            text-align: center;
        }

        .action-btn i {
            font-size: 24px;
        }

        .liked {
            color: #ff4081;
        }

        .create-reel-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 24px;
        }

        #uploadModal .preview-video {
            max-width: 100%;
            max-height: 300px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand" href="#">
                    <img src="/images/logo.png" alt="Kemetic Logo" height="40">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Reels</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Store</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <!-- Reels Container -->
    <div class="reels-container" id="reelsContainer"></div>

    <!-- Create Reel Button -->
    <button class="create-reel-btn" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="fas fa-plus"></i>
    </button>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Reel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadForm">
                        <div class="mb-3">
                            <label class="form-label">Video</label>
                            <input type="file" class="form-control" id="videoInput" accept="video/*" required>
                            <video class="preview-video mt-2" id="previewVideo" controls style="display: none;"></video>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" id="titleInput" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Caption</label>
                            <textarea class="form-control" id="captionInput" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="uploadButton">Upload Reel</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Add JavaScript code here
        const reelsContainer = document.getElementById('reelsContainer');
        let page = 1;
        let loading = false;

        // Setup axios defaults
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.baseURL = '/api/development';

        // Load reels
        async function loadReels() {
            if (loading) return;
            loading = true;

            try {
                const response = await axios.get(`/reels?page=${page}`);
                const reels = response.data.data;
                
                reels.forEach(reel => {
                    const reelElement = createReelElement(reel);
                    reelsContainer.appendChild(reelElement);
                });

                setupIntersectionObserver();
                page++;
            } catch (error) {
                console.error('Error loading reels:', error);
            } finally {
                loading = false;
            }
        }

        // Create reel element
        function createReelElement(reel) {
            const div = document.createElement('div');
            div.className = 'reel-card';
            div.innerHTML = `
                <video class="reel-video" src="/store/reels/videos/${reel.video_path}" loop></video>
                <div class="reel-actions">
                    <button class="action-btn like-btn" data-reel-id="${reel.id}">
                        <i class="far fa-heart"></i>
                        <span>${reel.likes_count || 0}</span>
                    </button>
                    <button class="action-btn comment-btn" data-reel-id="${reel.id}">
                        <i class="far fa-comment"></i>
                        <span>${reel.comments_count || 0}</span>
                    </button>
                    <button class="action-btn report-btn" data-reel-id="${reel.id}">
                        <i class="far fa-flag"></i>
                    </button>
                </div>
                <div class="reel-overlay">
                    <h6>${reel.user?.name || 'Unknown User'}</h6>
                    <p>${reel.caption}</p>
                </div>
            `;

            // Add event listeners
            const video = div.querySelector('video');
            video.addEventListener('click', () => togglePlay(video));

            const likeBtn = div.querySelector('.like-btn');
            likeBtn.addEventListener('click', () => toggleLike(reel.id, likeBtn));

            return div;
        }

        // Setup Intersection Observer for autoplay
        function setupIntersectionObserver() {
            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.play();
                        } else {
                            entry.target.pause();
                        }
                    });
                },
                { threshold: 0.5 }
            );

            document.querySelectorAll('.reel-video').forEach(video => {
                observer.observe(video);
            });
        }

        // Toggle video play/pause
        function togglePlay(video) {
            if (video.paused) {
                video.play();
            } else {
                video.pause();
            }
        }

        // Toggle like
        async function toggleLike(reelId, button) {
            try {
                const response = await axios.post(`/reels/${reelId}/like`);
                const { liked, likes_count } = response.data.data;
                
                const icon = button.querySelector('i');
                const count = button.querySelector('span');
                
                icon.className = liked ? 'fas fa-heart liked' : 'far fa-heart';
                count.textContent = likes_count;
            } catch (error) {
                console.error('Error toggling like:', error);
            }
        }

        // Handle file input change
        document.getElementById('videoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const video = document.getElementById('previewVideo');
                video.src = URL.createObjectURL(file);
                video.style.display = 'block';
            }
        });

        // Handle reel upload
        document.getElementById('uploadButton').addEventListener('click', async function() {
            const formData = new FormData();
            formData.append('video', document.getElementById('videoInput').files[0]);
            formData.append('title', document.getElementById('titleInput').value);
            formData.append('caption', document.getElementById('captionInput').value);

            try {
                await axios.post('/reels', formData);
                bootstrap.Modal.getInstance(document.getElementById('uploadModal')).hide();
                reelsContainer.innerHTML = '';
                page = 1;
                loadReels();
            } catch (error) {
                console.error('Error uploading reel:', error);
            }
        });

        // Initial load
        loadReels();

        // Infinite scroll
        reelsContainer.addEventListener('scroll', () => {
            if (reelsContainer.scrollTop + reelsContainer.clientHeight >= reelsContainer.scrollHeight - 100) {
                loadReels();
            }
        });
    </script>
</body>
</html>