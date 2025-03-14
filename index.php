<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMJ Furnishings</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="index.css">
</head>

<body>

<!-- Navbar -->
<?php include 'navbar.php'; ?>

<!-- Hero Section -->
<div class="container">
<div id="imageSlider" class="carousel slide position-relative">
    <!-- Text Overlay - Positioned relative to the carousel -->
    <div class="text-center position-absolute top-50 start-50 translate-middle text-white" style="z-index: 10;">
        <!-- Main heading with improved letter spacing and text shadow -->
        <h1 class="fw-bold display-4" style="letter-spacing: 1px; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">SMJ Furnishings</h1>
        
        <!-- Smaller subheading -->
        <h2 class="fw-semibold fs-3" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">Philippines Inc.</h2>
        
        <!-- Added tagline -->
        <p class="mt-2 fs-5" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">Premier Flooring Solutions</p>
    </div>
        <!-- Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#imageSlider" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#imageSlider" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#imageSlider" data-bs-slide-to="2"></button>
        </div>

        <!-- Image Slides -->
        <div class="carousel-inner">
        <div class="carousel-item active" style="height: 630px;">
            <img src="assets/header1.png" class="d-block w-100 h-100" alt="Slide 1" style="object-fit: cover;">
        </div>
        <div class="carousel-item active" style="height: 630px;">
            <img src="assets/header2.png" class="d-block w-100 h-100" alt="Slide 1" style="object-fit: cover;">
        </div>
        <div class="carousel-item active" style="height: 630px;">
            <img src="assets/header3.png" class="d-block w-100 h-100" alt="Slide 1" style="object-fit: cover;">
        </div>
        </div>

        <!-- Navigation Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#imageSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#imageSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</div>

<!-- Introduction Section -->
<div class="container text-center my-5">
    <h1 class="fw-bold mb-4">Your one-stop carpet service provider.</h1>
    <h5 class="text-muted lh-lg w-75 mx-auto">
        SMJ Group is one of the leading premier carpet specialists serving the <br> 
        commercial and institutional sectors in Asia.
    </h5> 
</div>

<!-- Line -->
<div class="container text-center my-5">
    <br><br> 
    <hr class="border border-black border-1"> 
    <br><br>
</div>

<!-- Featured Products -->
<div class="container text-center my-5">
    <h1 class="fw-bold">Featured Products</h1><br>
    <div class="row g-4 mt-3">
        <div class="col-md-3"><img src="assets/bluestone-sq.jpg" class="img-fluid" alt="Bluestone SQ"><h5 class="mt-2 fs-6">Bluestone SQ</h5></div>
        <div class="col-md-3"><img src="assets/brightstone-sq.jpg" class="img-fluid" alt="Brightstone SQ"><h5 class="mt-2 fs-6">Brightstone SQ</h5></div>
        <div class="col-md-3"><img src="assets/cadence-sq.jpg" class="img-fluid" alt="Candence SQ"><h5 class="mt-2 fs-6">Candence SQ</h5></div>
        <div class="col-md-3"><img src="assets/camborne-sq.jpg" class="img-fluid" alt="Camborne SQ"><h5 class="mt-2 fs-6">Camborne SQ</h5></div>
    </div>
    <br>
    <a class="btn btn-dark btn-lg mt-3">See more</a>
</div>

<!-- Line -->
<div class="container text-center my-5">
    <br><br> 
    <hr class="border border-black border-1"> 
    <br><br>
</div>

<!-- Clients Section -->
<div class="container-fluid text-center my-5 p-3 mb-2 bg-light">
    <div class="container">
        <h1 class="fw-bold">Our Clients</h1><br><br>
        <div class="scroll-container">
            <div class="scroll-content d-flex justify-content-start align-items-center gap-5 mb-5">
                <img src="assets/clients/accenture.png" alt="Client 1" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/alorica.png" alt="Client 2" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/bpi.png" alt="Client 3" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/concentrix.png" alt="Client 4" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/fullybooked.png" alt="Client 5" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/hsbc.png" alt="Client 6" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/nordic.png" alt="Client 7" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/novotel.png" alt="Client 8" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/novotel.png" alt="Client 9" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/pg.png" alt="Client 10" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/realpage.png" alt="Client 11" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/spglobal.png" alt="Client 12" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/sunlife.png" alt="Client 13" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/sutherland.png" alt="Client 14" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/sykes.png" alt="Client 15" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/teletech.png" alt="Client 16" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/vertiv.png" alt="Client 17" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/visa.png" alt="Client 18" class="img-fluid mx-2" style="height: 72px;">
                <!-- Duplicate the logos for continuous looping -->
                <img src="assets/clients/accenture.png" alt="Client 1" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/alorica.png" alt="Client 2" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/bpi.png" alt="Client 3" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/concentrix.png" alt="Client 4" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/fullybooked.png" alt="Client 5" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/hsbc.png" alt="Client 6" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/nordic.png" alt="Client 7" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/novotel.png" alt="Client 8" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/novotel.png" alt="Client 9" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/pg.png" alt="Client 10" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/realpage.png" alt="Client 11" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/spglobal.png" alt="Client 12" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/sunlife.png" alt="Client 13" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/sutherland.png" alt="Client 14" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/sykes.png" alt="Client 15" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/teletech.png" alt="Client 16" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/vertiv.png" alt="Client 17" class="img-fluid mx-2" style="height: 72px;">
                <img src="assets/clients/visa.png" alt="Client 18" class="img-fluid mx-2" style="height: 72px;">
            </div>
        </div>
    </div>
</div>

<!-- Line -->
<div class="container text-center my-5">
    <br><br> 
    <hr class="border border-black border-1"> 
    <br><br>
</div>

<!-- Latest Projects -->
<div class="container text-center my-5">
    <h1 class="fw-bold">Latest Projects</h1><br>
    <div class="row g-4 mt-3">
        <div class="col-12 col-md-4">
            <img src="assets/project1.jpg" class="img-fluid project-img" alt="Project 1" style="height: 500px;">
            <h5 class="mt-2 fw-bold fs-6">@ Taguig City</h5>
        </div>
        <div class="col-12 col-md-4">
            <img src="assets/project2.jpg" class="img-fluid project-img" alt="Project 2"style="height: 500px;">
            <h5 class="mt-2 fw-bold fs-6">@ Makati City</h5>
        </div>
        <div class="col-12 col-md-4">
            <img src="assets/project3.jpg" class="img-fluid project-img" alt="Project 3"style="height: 500px;">
            <h5 class="mt-2 fw-bold fs-6">@ Taguig City</h5>
        </div>
    </div>
    <br>
    <a class="btn btn-dark btn-lg mt-3">See more</a>
</div>

<!-- Line -->
<div class="container text-center my-5">
    <br><br> 
    <hr class="border border-black border-1"> 
    <br><br>
</div>

<!-- About Section -->
<div class="container my-5">
    <h1 class="fw-bold text-center">About us</h1><br>
    <div class="row align-items-center mt-3">
        <!-- Text on the left -->
        <div class="col-md-6 pe-md-5 text-start">
            <h5 class="mb-1 lh-lg fs-6" style="text-align: justify;">
                SMJ Furnishings was set up in 1986 and specializes in carpet tiles, broadloom carpets, and luxury vinyl tiles. With an established reputation and track record, SMJ Furnishings is arguably the top largest leading premier flooring specialist serving the commercial and institutional sectors in Asia.
            </h5>
            <a class="btn btn-outline-dark mt-3" href="aboutus.php">Learn more</a>
        </div>

        <!-- Image on the right -->
        <div class="col-md-6 ps-md-5">
            <img src="assets/aboutus.jpg" class="img-fluid" alt="About Us">
        </div>
    </div>
</div>

<!-- Footer -->
<?php include 'footer.php'; ?>


</body>
</html>
