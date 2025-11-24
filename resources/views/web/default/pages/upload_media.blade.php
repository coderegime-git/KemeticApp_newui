

<?php
$cards = [
    ["title" => "Yoga & Wellness", "desc" => "Discover the best yoga techniques and practices for a healthier lifestyle.", "link" => "#", "video" => "https://videos.pexels.com/video-files/31153636/13311084_2560_1440_30fps.mp4"],
    ["title" => "Meditation Guide", "desc" => "A complete guide to mindfulness meditation for mental clarity and peace.", "link" => "#", "video" => "https://videos.pexels.com/video-files/31153636/13311084_2560_1440_30fps.mp4"],
    ["title" => "Holistic Healing", "desc" => "Learn about alternative healing methods such as Ayurveda and acupuncture.", "link" => "#", "video" => "https://videos.pexels.com/video-files/31153636/13311084_2560_1440_30fps.mp4"],
    ["title" => "Spiritual Awakening", "desc" => "Explore deep spiritual practices and self-discovery techniques.", "link" => "#", "video" => "https://www.youtube.com/embed/aJ0w9wxYoLY"],
    ["title" => "Nutrition & Diet", "desc" => "Healthy eating tips, diet plans, and superfoods for balanced nutrition.", "link" => "#", "video" => "https://www.youtube.com/embed/wFEOA07EzdM"],
    ["title" => "Mindfulness & Meditation", "desc" => "Understand the power of mindfulness and how it can transform your daily life.", "link" => "#", "video" => "https://www.youtube.com/embed/dcBXmj1nMTQ"],
    ["title" => "Holistic Practices", "desc" => "Explore natural wellness practices including herbal remedies and energy healing.", "link" => "#", "video" => "https://www.youtube.com/embed/8iPEnn-ltC8"],
    ["title" => "Health & Wellness", "desc" => "A comprehensive guide to maintaining physical and mental well-being.", "link" => "#", "video" => "https://www.youtube.com/embed/f5d8pVg3Qtg"],
    ["title" => "Spiritual Growth", "desc" => "Unlock deeper spiritual knowledge and personal enlightenment.", "link" => "#", "video" => "https://www.youtube.com/embed/8z9xHbHNoJs"],
    ["title" => "Courses & PDFs", "desc" => "Access high-quality learning materials on health, wellness, and spirituality.", "link" => "#", "video" => "https://www.youtube.com/embed/7Q8drCyl9bA"]
];
?>
<?php
session_start();
$videoPath = isset($_SESSION['uploaded_video']) ? $_SESSION['uploaded_video'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['video'])) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $videoFile = $uploadDir . basename($_FILES['video']['name']);
    $videoType = strtolower(pathinfo($videoFile, PATHINFO_EXTENSION));
    $allowedTypes = ['mp4', 'webm', 'ogg'];

    if (in_array($videoType, $allowedTypes) && mime_content_type($_FILES['video']['tmp_name']) === "video/$videoType") {
        if (move_uploaded_file($_FILES['video']['tmp_name'], $videoFile)) {
            $_SESSION['uploaded_video'] = $videoFile;
            $videoPath = $videoFile;
        } else {
            echo "<script>alert('Error uploading video');</script>";
        }
    } else {
        echo "<script>alert('Invalid video format. Only MP4, WEBM, and OGG allowed');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affiliate Media Kit</title>
    <link href="assets/default/css/app.css" rel="stylesheet" />
    <link href="assets/assets/css/media-style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    
     .form-container {
        max-width: 800px;
        margin: auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-bottom:32px;
    }
   
    </style>
</head>
<body>
    <nav class="navbar navbar-light navbar-expand-lg sticky">
        <div class="container px-0">
            <a class="navbar-brand" href="#">
                <img class="img-cover" src="https://kemetic.app/store/1/default_images/website-logo.png" alt="Logo" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                  
                    <li class="nav-item" ><a class="nav-link" id="media_kit_nav_items" href="#">Health & Wellness</a></li>
                    <li class="nav-item" ><a class="nav-link" id="media_kit_nav_items" href="#">Spiritual Growth</a></li>
                    <li class="nav-item" ><a class="nav-link" id="media_kit_nav_items" href="#">Holistic Practices</a></li>
                    <li class="nav-item" ><a class="nav-link" id="media_kit_nav_items" href="#">Mindfulness & Meditation</a></li>
                    <li class="nav-item" ><a class="nav-link" id="media_kit_nav_items" href="#">Courses & PDFs</a></li>
                </ul>
            </div>
        </div>
    </nav>
  
    <!--<div class="container my-4 pt-100">-->
    <!--    <div class="">-->
    <!--        <h2 class="text-secondary font-weight-bold px-15 mb-20 text-center">Upload content</h2>-->
    <!--    </div>-->
        
    <!--    <div class="form-container">-->
        <!--<h2>Upload Your Video</h2>-->
    <!--    <form action="" method="POST" enctype="multipart/form-data">-->
    <!--       <label for="category" class="upload-kit-label">Category:</label>-->
    <!--        <select id="category" name="category" class="upload-kit-input">-->
    <!--            <option value="Health & Wellness">Health & Wellness</option>-->
    <!--            <option value="Spiritual Growth">Spiritual Growth</option>-->
    <!--            <option value="Holistic Practices">Holistic Practices</option>-->
    <!--            <option value="Mindfulness & Meditation">Mindfulness & Meditation</option>-->
    <!--            <option value="Courses & PDFs">Courses & PDFs</option>-->
    <!--        </select>-->
            
    <!--        <label for="title" class="upload-kit-label">Title:</label>-->
    <!--        <input type="text" id="title" name="title" class="upload-kit-input" required>-->
            
    <!--        <label for="description" class="upload-kit-label">Description:</label>-->
    <!--        <textarea id="description" name="description" class="upload-kit-input" rows="4" required></textarea>-->
            
    <!--        <label for="video" class="upload-kit-label">Upload Video:</label>-->
    <!--        <input type="file" id="video" name="video" class="upload-kit-input" accept="video/mp4,video/webm,video/ogg">-->

    
    <!--        <div class="video-preview <?= $videoPath ? '' : 'hidden' ?>" id="videoPreview">-->
    <!--            <button type="button" class="close-btn" onclick="removeVideo()">×</button>-->
    <!--            <video id="preview" controls>-->
    <!--                <source id="videoSource" src="<?= $videoPath ?>" type="video/mp4">-->
    <!--                Your browser does not support the video tag.-->
    <!--            </video>-->
    <!--        </div>-->
    <!--        <div class="text-center">-->
    <!--             <button type="submit" class="btn btn-primary" style="margin-top:10px;">-->
    <!--              <i class="fas fa-upload"></i> Upload-->
    <!--            </button>-->
    <!--        </div>-->
         

    <!--    </form>-->
    <!--</div>-->
    <div class="container my-4 pt-100">
        <div class="text-center">
            <h2 class="text-secondary font-weight-bold px-15 mb-20">Upload Content</h2>
        </div>
    
        <div class="form-container">
            <form action="/create-media" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <label for="category" class="upload-kit-label">Category:</label>
                        <select id="category" name="category" class="upload-kit-input form-control" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->title}}</option>
                            @endforeach
                        </select>
                    </div>
    
                    <div class="col-md-6 col-12">
                        <label for="title" class="upload-kit-label">Title:</label>
                        <input type="text" id="title" name="title" class="upload-kit-input form-control" required>
                    </div>
    
                    <div class="col-12">
                        <label for="description" class="upload-kit-label">Description:</label>
                        <textarea id="description" name="description" class="upload-kit-input form-control" rows="4" required></textarea>
                    </div>
    
                    <div class="col-12">
                         <label for="courseLink" class="upload-kit-label">Course Link:</label>
                        <input type="text" id="courseLink" name="courseLink" class="upload-kit-input form-control" required>
                    </div>
                    <div class="col-12">
                        <label for="video" class="upload-kit-label">Upload Video:</label>
                        <input type="file" required id="video" name="video" class="upload-kit-input form-control" accept="video/mp4,video/webm,video/ogg">
                    </div>
                    
    
                    <div class="col-12 text-center">
                        <div class="video-preview <?= $videoPath ? '' : 'hidden' ?>" id="videoPreview">
                            <button type="button" class="close-btn" onclick="removeVideo()">×</button>
                            <video id="preview" controls>
                                <source id="videoSource" src="<?= $videoPath ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </div>
    
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

        
        <!--<div class="row">-->
        <!--    <div class="col-12 content-area">-->
        <!--        <div class="row">-->
        <!--            <?php foreach ($cards as $card) : ?>-->
        <!--                <div class="col-md-6 col-lg-4 mb-3">-->
        <!--                    <div class="card h-100 minh-300 webinar-card shadow-sm">-->
        <!--                        <div class="card-body pb-0">-->
        <!--                            <h5 class="mb-10"><?= htmlspecialchars($card['title']) ?></h5>-->
        <!--                            <p class="mb-1"><?= htmlspecialchars($card['desc']) ?></p>-->
                                                                        <!-- Learn More Link -->
        <!--                            <a href="<?= $card['link'] ?>" class="mb-1">Click here...</a>-->
                                    
                                    <!-- Video Embed -->
        <!--                          <video class="w-100" height="200" controls>-->
        <!--                                <source src="<?= htmlspecialchars($card['video']) ?>" type="video/mp4">-->
        <!--                                Your browser does not support the video tag.-->
        <!--                            </video>-->
                                    

        <!--                        </div>-->
        <!--                        <div class="card-footer bg-transparent border-top d-flex justify-content-between gap10">-->
        <!--                            <button class="btn btn-primary w-50">-->
        <!--                                <i class="fas fa-download"></i> Download-->
        <!--                            </button>-->
        <!--                            <button class="btn btn-secondary w-50">-->
        <!--                               <i class="fa-solid fa-share"></i> Share-->
        <!--                            </button>-->
        <!--                        </div>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            <?php endforeach; ?>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
    </div>
    
    <!--<aside class="sidebar d-none px-1 d-lg-block">-->
    <!--    <div class="vertical_text h-100">-->
    <!--        <h5 class="text-center h-100 "> <span class="my-10"><i class="fa-solid fa-screwdriver-wrench"></i></span>Tools & Resources</h5>-->
    <!--        <p class="h-100">-->
    <!--            <span>-->
    <!--                <i class="fas fa-pen-nib"></i> -->
    <!--                <i class="fas fa-video"></i> -->
    <!--                <i class="fas fa-bullhorn"></i>-->
    <!--                <i class="fas fa-envelope"></i> -->
    <!--            </span>-->
              
    <!--        </p>-->
    <!--    </div>-->
        
    <!--    <ul class="list-unstyled d-flex flex-wrap gap10 flex-column">-->

    <!--        <li><i class="fas fa-pen-nib"></i> AI Writing</li>-->
    <!--        <li><i class="fas fa-video"></i> Video Editing</li>-->
    <!--        <li><i class="fas fa-bullhorn"></i> Marketing Tools</li>-->
    <!--        <li><i class="fas fa-envelope"></i> Email Templates</li>-->
    <!--    </ul>-->
    <!--</aside>-->
    
         <!--Footer Sidebar for Mobile -->
         
    <!--<footer class="d-lg-none mt-4 footer-sidebar py-20">-->
    <!--    <div class="container">-->
    <!--        <div class="footerbar">-->
    <!--        <h5 class="font-weight-bold mb-20"> <span class="mr-1"><i class="fa-solid fa-screwdriver-wrench"></i></span>Tools & Resources</h5>-->
    <!--        <ul class="list-unstyled d-flex flex-wrap gap10 justify-content-between ">-->
    <!--            <li><i class="fas fa-pen-nib"></i> AI Writing</li>-->
    <!--            <li><i class="fas fa-video"></i> Video Editing</li>-->
    <!--            <li><i class="fas fa-bullhorn"></i> Marketing Tools</li>-->
    <!--            <li><i class="fas fa-envelope"></i> Email Templates</li>-->
    <!--        </ul>-->
    <!--    </div>-->
    <!--    </div>-->
        
    <!--</footer>-->

    
    
</body>
<script>
    function previewVideo(event) {
        const file = event.target.files[0];
        if (file) {
            const videoURL = URL.createObjectURL(file);
            document.getElementById('videoSource').src = videoURL;
            document.getElementById('preview').load();
            document.getElementById('videoPreview').classList.remove('hidden');
        }
    }

    function removeVideo() {
        document.getElementById('video').value = ''; // Clear file input
        document.getElementById('videoSource').src = '';
        document.getElementById('preview').load();
        document.getElementById('videoPreview').classList.add('hidden');
    }
</script>
</html>

