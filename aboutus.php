<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMJ Furnishings PH</title>
     <!-- Bootstrap CSS -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
     <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Poppins', sans-serif;
    }
    .scroll-container {
        overflow: hidden;
        white-space: nowrap;
        width: 100%;
        background: #f8f9fa;
        padding: 20px 0;
    }
         /* About Section */
         .about-text {
            text-align: justify;
            line-height: 1.8;
            font-size: 1rem;
        }

        .image-container {
    max-width: 100%;
    height: auto;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-in-out;
}

        .image-container:hover {
            transform: scale(1.03);
        }

        .about-image {
            width: 100%;
            height: auto;
            object-fit: cover;
            max-height: 550px;
        }

        /* Global Distribution Section */
        .global-distribution {
            background: linear-gradient(135deg, #ED4135 0%, #c1352b 100%);
            padding: 4rem 2rem;
            color: #fff;
            border-radius: 15px;
        }

        .global-distribution h1 {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .global-distribution h3 {
            font-size: 1.25rem;
            line-height: 1.6;
            font-weight: 400;
        }

        /* Services Section */
        .services-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
        }

        .service-card {
            background-color: #fff;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .service-card img {
            max-height: 150px;
            object-fit: contain;
            margin-bottom: 1rem;
        }

        .service-card h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
        }

        /* Responsive Adjustments */
        @media (max-width: 991.98px) {
            .about-text {
                padding: 0 15px;
                margin-top: 2rem;
            }

            .image-container {
                max-width: 95%;
                max-height: 70vh;
            }

            .about-image {
                max-height: 70vh;
            }

            .global-distribution h1 {
                font-size: 2rem;
                margin-bottom: 1.5rem;
            }

            .global-distribution h3 {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 575.98px) {
            .hero-section h1 {
                font-size: 2rem;
            }

            .image-container {
                max-width: 100%;
            }

            .global-distribution {
                padding: 2rem 1rem;
            }

            .global-distribution h1 {
                font-size: 1.75rem;
            }

            .global-distribution h3 {
                font-size: 1rem;
            }

            .services-section h1 {
                font-size: 2rem;
            }

            .service-card h2 {
                font-size: 1.1rem;
            }
        }

        
    </style>
</head>

<body>
     <!-- Navbar -->
        <?php include 'navbar.php'; ?>
    
<!-- Hero Section -->
<div class="container my-5 my-md-5 my-sm-3 text-center">
    <div class="row justify-content-center">
        <div class="col-12">
        <h1 class="fw-bold position-relative d-inline-block pb-2 mb-lg-5 mb-md-3 mb-2">
                About us
                <span class="position-absolute start-50 translate-middle-x bottom-0" 
                      style="height: 1.5px; background-color: #ED4135; width: 175%;"></span>
            </h1>
        </div>
    </div>
</div>

<!-- About us Section -->
<div class="container mt-lg-3 mt-2 pb-5 pb-lg-7">
    <div class="row align-items-center">
        <!-- Image Section - Centered and Responsive -->
        <div class="col-lg-6 order-lg-1 order-2 text-center mb-4 mb-lg-0">
            <div class="image-container mx-auto">
                <img src="assets/aboutus1.jpg" class="about-image img-fluid" alt="About Us">
            </div>
        </div>

        <!-- Text Section - Adjusted for Better Balance -->
        <div class="col-lg-6 order-lg-2 order-1 px-3 px-md-5">
            <div class="about-text-container">
               <br><p class="about-text">
                SMJ Furnishings was set up in 1988 and specialises in carpet tiles, broadloom carpets, and luxury vinyl tiles. 
                With an established reputation and track record, SMJ Furnishings is arguably the top largest leading premier flooring specialists serving the commercial and institutional sectors in Asia.
                </p>
                <p class="about-text">
                    Headquartered in Singapore, SMJ manufactures and distributes globally a wide range of premier floorings.
                </p>
                <p class="about-text">
                Despite   being   the   market   leader,   SMJ  continues  to  stay close to  the  ground. SMJ   understands    global    flooring   trends    and    continuously    develops   
                and produces new designs which are  well  received  by our customers. To  date,  SMJ  is the  only company  that  offers  the  widest  selection  of   top   quality   carpet   tiles,  
                broadloom carpets and vinyl tiles globally with the most competitive prices.
                </p>
                <p class="about-text" style="border-left: 3px solid #ED4135; padding-left: 15px; font-style: italic;">
                At SMJ, we have an insatiable passion and belief in high quality floorings with ultimate style.  With   varieties    and   performance   as   our   top   priority,  
                 it   is  our  business  to  make your working space look good.
                </p> <br> 
            </div>
        </div>
    </div>
</div>




<!-- SMJ Map -->
<div class="container my-5">
    <div class="row align-items-center mt-3">
            <img src="assets/SMJ_Map_Phil.png" class="img-fluid" alt="SMJ Map">
</div>
</div>

<!-- Global Distribution -->
<div class="container-fluid my-5 bg-danger text-white p-5">
    <div class="row align-items-center mt-3">
    <div class="col-md-6">
        <h1 class="fw-bold text-center">Global Distribution</h1>
     </div>
    <div class="col-md-6">
        <h3 class="mb-1 lh-lg fs-4" style="text-align: justify;">SMJ sell and distribute globally a wide range of premier carpets markted under our proprietary “SMJ” brand. We have established a wide distributin network of more than 260 distributors in more than 20 countries.</h3>
    </div>
</div>
</div>

<!-- Services Section -->
<div class="container text-center my-5">
    <h1 class="fw-bold">Services</h1><br>
    <div class="row g-4 mt-3">
        <div class="col-md-4"><img src="assets/supply-chain.png" class="img-fluid" alt="Project 1"><br><br><h2>Supply of carpet tiles & broadloom carpets</h2></div>
        <div class="col-md-4"><img src="assets/install.png" class="img-fluid" alt="Project 2"><br><br><h2>Installation and project management of products supplied</h2></div>
        <div class="col-md-4"><img src="assets/advice.png" class="img-fluid" alt="Project 3"><br><br><h2>Professional advise on carpet investment</h2></div>
    </div>
</div>

<!-- Footer -->
<?php include 'Footer.php'; ?>
   
</body>
</html>