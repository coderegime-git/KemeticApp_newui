@extends(getTemplate().'.layouts.app1')

@section('content')

    <link href="assets/default/css/app.css" rel="stylesheet" />
    <link href="assets/assets/css/media-style3.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    

    <style>
        .nav-pills .nav-link.active {
                background-color: var(--primary) !important;
            }
            #cardsContainer{
                    margin-right: 60px;
            }
            @media (max-Width:991px){
                    #cardsContainer{
                        margin-right: 0px;
                }
            }
            .main_media_section{
                    min-height: 100vh;
            }
            
            #uploadModal{
                z-index:99999;
            }

    </style>

    <div class="sticky-btn-container btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal" id="stickyBtn"  style="position: absolute; right: 70px;">
        <i class="fas fa-plus"></i> Add Media
    </div>


    <div class="container-fluid my-4 pt-30 position-relative">
    <div class="row main_media_section">
        <!-- Left Sidebar (Categories) -->
        <div class="col-4 col-md-2 position-absolute top-0 start-0 h-100 overflow-auto bg-light p-2" style="width: 250px;min-height:100vh">
            <ul class="nav nav-pills flex-column" id="tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-tab="all" href="#">All</a>
                </li>
                @foreach($categories as $category)
                    <li class="nav-item">
                        <a class="nav-link" data-tab="cat-{{ $category->id }}" href="#">{{ $category->title }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Right Content Section -->
        <div class="col-md-10 col-8 offset-4 offset-md-2 ps-4" >
            <div class="row" id="cardsContainer" >
                    <div class="col-12 text-center mt-5 d-none" id="noData">
                        <h4 class="text-muted">No data found</h4>
                    </div>
                @foreach($mediaKit as $media)
                    <div class="col-md-6 col-lg-4 mb-3 card-item" data-category="cat-{{ $media->category_id }}">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body pb-0">
                                <h5 class="mb-10">{{ $media->title }}</h5>
                                <p class="mb-1">{{ \Illuminate\Support\Str::limit($media->description, 50, '...') }}</p>
                                <a class="text-decoration-underline text-primary" href="{{ $media->course_link }}">Course Link</a>

                                @if(Str::contains($media->video_path, 'youtube.com'))
                                    <iframe class="w-100" height="200" src="{{ asset($media->video_path) }}" frameborder="0" allowfullscreen></iframe>
                                @else
                                    <video class="w-100" height="200" controls>
                                        <source src="{{ $media->video_path }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                            </div>
                            <div class="card-footer bg-transparent border-top d-flex justify-content-between gap10">
                                <button class="btn btn-primary btn-sm w-50 download-btn" data-url="{{ asset($media->video_path) }}">
                                    <i class="fas fa-download"></i> Download
                                </button>

                                <button class="js-share-blog icon-box btn btn-secondary btn-sm w-50"><i class="fa-solid fa-share"></i> Share</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


    <!-- Sidebar -->
    <aside class="sidebar d-none px-1 d-lg-block" id="sidebar" style="position: absolute; right: 0; top: 203px;">
        <div class="vertical_text h-100">
            <h5 class="text-center h-100">
                <span class="my-10"><i class="fa-solid fa-screwdriver-wrench"></i></span> Tools & Resources
            </h5>
            <p class="h-100">
                <span>
                    @foreach($mediaTools as $mediaIcon)
                    <i class="{{$mediaIcon->icon}}"></i> 
                    @endforeach
                </span>
            </p>
        </div>
        <ul class="list-unstyled d-flex flex-wrap gap10 flex-column">
            @foreach($mediaTools as $mediaTool)
            {{-- <li><i class="fas fa-pen-nib"></i> AI Writing</li>
            <li><i class="fas fa-video"></i> Video Editing</li>
            <li><i class="fas fa-bullhorn"></i> Marketing Tools</li>
            <li><i class="fas fa-envelope"></i> Email Templates</li> --}}
            <li><i class="{{$mediaTool->icon}}"></i><a href="{{$mediaTool->link}}" class="text-white"> {{$mediaTool->name}}</a></li>
            @endforeach
        </ul>
    </aside>
    
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Media</h5>
                    <button type="button" class="btn-close border-0 bg-transparent" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form Inside Modal -->
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
                        </div>
    
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Upload
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Sidebar for Mobile -->
    <footer class="d-lg-none mt-4 footer-sidebar py-20">
        <div class="container">
            <div class="footerbar">
                <h5 class="font-weight-bold mb-20">
                    <span class="mr-1"><i class="fa-solid fa-screwdriver-wrench"></i></span> Tools & Resources
                </h5>
                <ul class="list-unstyled d-flex flex-wrap gap10 justify-content-between">
                    <li><i class="fas fa-pen-nib"></i> AI Writing</li>
                    <li><i class="fas fa-video"></i> Video Editing</li>
                    <li><i class="fas fa-bullhorn"></i> Marketing Tools</li>
                    <li><i class="fas fa-envelope"></i> Email Templates</li>
                </ul>
            </div>
        </div>
    </footer>

@endsection
@include('web.default.blog.share_media_kit_modal')
<script>
   document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll(".nav-link");
    const cards = document.querySelectorAll(".card-item");
    const noData = document.getElementById("noData");

    tabs.forEach(tab => {
        tab.addEventListener("click", function (event) {
            event.preventDefault();
            let selectedTab = this.getAttribute("data-tab");

            // Remove 'active' class from all tabs and add to the clicked tab
            tabs.forEach(t => t.classList.remove("active"));
            this.classList.add("active");

            let hasVisibleCards = false; // Track if any card is visible

            cards.forEach(card => {
                if (selectedTab === "all" || card.getAttribute("data-category") === selectedTab) {
                    card.style.display = "block";
                    hasVisibleCards = true; // At least one card is shown
                } else {
                    card.style.display = "none";
                }
            });

            // Show "No Data Found" only if no cards are visible
            noData.classList.toggle("d-none", hasVisibleCards);
        });
    });
});



// Sidebar & Sticky Button Scroll Effect
window.addEventListener("scroll", function () {
    const sidebar = document.getElementById("sidebar");
    const stickyBtn = document.getElementById("stickyBtn");
    const scrollY = window.scrollY;
    const triggerPoint = 130;
    const isMobile = window.innerWidth < 991;

    if (scrollY >= triggerPoint) {
        sidebar.style.position = "fixed";
        sidebar.style.top = isMobile ? "80px" : "80px";
        stickyBtn.style.position = "fixed";
        stickyBtn.style.top = isMobile ? "80px" : "80px";
    } else {
        sidebar.style.position = "absolute";
        sidebar.style.top = isMobile ? "250px" : "203px"; 
        stickyBtn.style.position = "absolute";
        stickyBtn.style.top = isMobile ? "250px" : "203px";
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const downloadButtons = document.querySelectorAll(".download-btn");

    downloadButtons.forEach(button => {
        button.addEventListener("click", function () {
            const fileUrl = this.getAttribute("data-url");

            if (fileUrl) {
                const link = document.createElement("a");
                link.href = fileUrl;
                link.download = fileUrl.split('/').pop(); // Extract filename
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                alert("File not found!");
            }
        });
    });
});

</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="/assets/default/js/parts/blog.min.js"></script>