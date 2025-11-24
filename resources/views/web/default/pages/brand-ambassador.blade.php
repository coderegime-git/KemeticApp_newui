@extends(getTemplate().'.layouts.app')

@section('content')
<section class="bg-ambassador">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
            <h1 class="text-white  font40 mb-3">Brand Ambassadors</h1>
        <p class="">Transform Life's And Earn While doing it! Join the Kemetic.app Brand Ambassador Program</p>
        <p class="">Talk and post about us! And get paid</p>
        <p class="">Start Earning with 0 followers by sharing your unique affiliate link! some make up to $400 dollars a day using this method.</p>
        <a href="{{url('register')}}" class="btn btn-border-white btn-primary mt-4">Register here</a>
            </div>
        </div>
    </div>
</section>
<section class="mt80 container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0" >
                <h3 class="font-24 font-weight-bold text-dark">About Kemetic.app</h3>
                <p class="font-14">Kemetic.app is a revolutionary platform that connects individuals to the ancient wisdom and practices of Kemetic spirituality. Our mission is to empower and enlighten people by providing easy access to this rich cultural heritage.</p>
            </div>
            <div class="col-md-6"> 
                <img src="{{asset('assets/default/img/brand-1.webp')}}" class="about-kemetic-img">
            </div>
        </div>
</section>
<section class="mt80 py-40 bg-clr overflow-hidden">
    <div class="container">
    <h2 class="title-become text-center mb-35">Why Become a Brand Ambassador?</h2>
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
            <iframe class="iframe-video-brand" src="https://www.youtube.com/embed/g_UHnTp3xJI?si=UWLxH8gi1GLSkAr0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
            <div class="col-md-6" >
               <p class="mb-10">As a Kemetic.app Brand Ambassador, you’ll be at the forefront of our mission to spread knowledge and spirituality. You will have the opportunity to:</p>
               <ul class="ps-3">
                <li class="mb-1 font-14"><b>Impact Lives:</b> <span class="grayColor1 "> Share the wisdom of Kemetic spirituality and help others on their journey to self-discovery.</span></li>
                <li class="mb-1 font-14"><b>Exclusive Perks:</b> <span class="grayColor1 "> Share the wisdom of Kemetic spirituality and help others on their journey to self-discovery.</span></li>
                <li class="mb-1 font-14"><b>Community: </b> <span class="grayColor1 ">Connect with like-minded individuals and form a community of spiritual seekers.</span></li>
                <li class="mb-1 font-14"><b>Professional Growth: </b> <span class="grayColor1 ">Develop valuable skills in marketing, content creation, and community engagement.</span></li>
                <li class="mb-1 font-14"><b>Earn Commissions: </b> <span class="grayColor1 ">Receive commissions for referring new users to Kemetic.app.</span></li>
               </ul>
            
            </div>
        </div>
    </div>
</section>
<section class="mt80 overflow-hidden">
    <div class="container">
    <h2 class="title-become text-center mb-35">What We're Looking For</h2>
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0" >
                <ul class="pl-15">
                <li class="mb-1 list-ambassador">You Are enthusiastic about promoting Kemetic.app.</li>
                <li class="mb-1 list-ambassador">Embrace diversity and inclusivity.</li>
                <li class="mb-1 list-ambassador">Have an active presence on social media, Blog or website.</li>
                <li class="mb-1 list-ambassador">Are willing to learn and grow with us.</li>
               </ul>
            </div>
            <div class="col-md-6">
                <img src="{{asset('assets/default/img/brand-1.webp')}}" class="about-kemetic-img ">
            </div>
        </div>
    </div>
</section>

@endsection
