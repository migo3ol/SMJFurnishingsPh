<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMJ Furnishings</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="index.css">
    <style>

        /* Project Section */
        .projects-section .project-card {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .projects-section .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }
        .projects-section .project-card img {
            width: 100%;
            height: 350px;
            object-fit: cover;
        }
        .projects-section .view-btn {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            padding: 10px 30px;
            letter-spacing: 2px;
            opacity: 0;
            font-weight: 600;
            transition: opacity 0.3s ease;
            z-index: 3;
        }
        .projects-section .project-card:hover .view-btn {
            opacity: 1;
        }
        /* Picture button styling */
        .picture-btn {
            background-color: #333;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 1.25rem;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .picture-btn:hover {
            background-color: #555;
        }

        /* Featured Products */
        .btn-dark {
            background-color: #333;
            border: none;
            padding: 10px 15px;
            font-size: 1.25rem;
            transition: background-color 0.3s ease;
        }
        .btn-dark:hover {
            background-color: #555;
        }
        #productContainer {
            transition: transform 0.5s ease, opacity 0.5s ease;
        }
        #productContainer.slide-left {
            transform: translateX(-50px);
            opacity: 0;
        }
        #productContainer.slide-right {
            transform: translateX(50px);
            opacity: 0;
        }
        #productContainer.reset {
            transform: translateX(0);
            opacity: 1;
        }
        #productContainer a {
            text-decoration: none; /* Remove underline from links */
        }
        #productContainer h5 {
            font-size: 1.25rem; /* Adjust the font size */
            color: #212529;
        }
        .project-img-container {
            overflow: hidden;
            position: relative;
        }   
        .project-img-container img {
            transition: transform 0.5s ease;
            object-fit: cover;
        }
        .project-img-container:hover img {
            transform: scale(1.2);
        }

    </style>
</head>
<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <div class="container">
        <div id="imageSlider" class="carousel slide position-relative" data-bs-ride="carousel" data-bs-interval="3000">
            <!-- Hero Text -->
            <div class="hero-text text-center position-absolute top-50 start-50 translate-middle text-white" 
                 style="z-index: 10; text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.7);">
                <h1 class="fw-bold display-4" style="letter-spacing: 1px;">SMJ Furnishings</h1>
                <h2 class="fw-semibold fs-3">Philippines Inc.</h2>
                <p class="mt-2 fs-5">Premier Flooring Solutions</p>
            </div>
            <!-- Indicators -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#imageSlider" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#imageSlider" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#imageSlider" data-bs-slide-to="2"></button>
                <button type="button" data-bs-target="#imageSlider" data-bs-slide-to="3"></button>
                <button type="button" data-bs-target="#imageSlider" data-bs-slide-to="4"></button>
            </div>
            <!-- Image Slides -->
            <div class="carousel-inner">
                <div class="carousel-item active" style="height: 600px;">
                    <img src="assets/h1.png" class="d-block w-100 h-100" alt="Slide 0" style="object-fit: cover;">
                </div>
                <div class="carousel-item" style="height: 600px;">
                    <img src="assets/h2.png" class="d-block w-100 h-100" alt="Slide 1" style="object-fit: cover;">
                </div>
                <div class="carousel-item" style="height: 600px;">
                    <img src="assets/h3.png" class="d-block w-100 h-100" alt="Slide 2" style="object-fit: cover;">
                </div>
                <div class="carousel-item" style="height: 600px;">
                    <img src="assets/h4.png" class="d-block w-100 h-100" alt="Slide 3" style="object-fit: cover;">
                </div>
                <div class="carousel-item" style="height: 600px;">
                    <img src="assets/h5.png" class="d-block w-100 h-100" alt="Slide 4" style="object-fit: cover;">
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
        <br><h1 class="fw-bold mb-4">Your one-stop carpet service provider.</h1>
        <h5 class="text-muted lh-lg w-75 mx-auto">
            SMJ Group is one of the leading premier carpet specialists serving the <br> 
            commercial and institutional sectors in Asia.
        </h5> 
    </div>

    <!-- Line -->
    <div class="container text-center my-5">
        <br><br> 
        <div style="border-bottom: 1px solid #ED4135; width: 90%; max-width: 750px; margin: 10px auto;"></div>
        <br><br>
    </div>

    <!-- Featured Products -->
    <div class="container text-center my-5">
        <h1 class="fw-bold">Featured Products</h1><br>
        <div class="d-flex justify-content-between align-items-center">
            <button id="prevButton" class="btn btn-dark"><i class="fas fa-chevron-left"></i></button>
            <div id="productContainer" class="row g-4 mt-3 flex-grow-1 mx-3"></div>
            <button id="nextButton" class="btn btn-dark"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>

    <!-- Line -->   
    <div class="container text-center my-5">
        <br><br> 
        <div style="border-bottom: 1px solid #ED4135; width: 90%; max-width: 750px; margin: 10px auto;"></div>
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
                    <!-- Duplicate logos for continuous looping -->
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
        <div style="border-bottom: 1px solid #ED4135; width: 90%; max-width: 750px; margin: 10px auto;"></div>
        <br><br>
    </div>

    <!-- Latest Projects -->
    <div class="container projects-section text-center my-5">
        <h1 class="fw-bold">Latest Projects</h1><br>
        <div class="row g-4 mt-3">
            <?php
            include 'database.php';
            $query = "SELECT * FROM projects ORDER BY created_at DESC LIMIT 3";
            $result = $conn->query($query);
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    $images = json_decode($row['images'], true);
                    $firstImage = $images[0] ?? 'default.jpg';
            ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="project-card">
                            <img src="Uploads/projects/<?= htmlspecialchars($firstImage) ?>" alt="Project Image" class="card-img-top">
                            <a href="project_details.php?id=<?= $row['id'] ?>" class="view-btn">View</a>
                        </div>
                        <div class="text-center mt-3">
                            <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                        </div>
                    </div>
            <?php
                endwhile;
            else:
            ?>
                <p class="text-muted">No projects available at the moment.</p>
            <?php endif; ?>
        </div>
        <br>
        <a href="projects.php" class="btn btn-dark btn-lg mt-3" style="background-color: #333; color: white; transition: all 0.3s ease;">See more</a>
    </div>

    <!-- Line -->
    <div class="container text-center my-5">
        <br><br> 
        <div style="border-bottom: 1px solid #ED4135; width: 90%; max-width: 750px; margin: 10px auto;"></div>
        <br><br>
    </div>

    <!-- About Us -->
    <div class="container my-5">
        <h1 class="fw-bold text-center">About us</h1><br>
        <div class="row flex-column-reverse flex-md-row align-items-center mt-3">
            <!-- Text Column -->
            <div class="col-12 col-md-6 pe-md-4 mt-3 mt-md-0">
                <h5 class="mb-1 lh-lg text-justify fs-10" style="text-align: justify;">
                    SMJ Furnishings was set up in 1986 and specializes in carpet tiles, broadloom carpets,
                    and luxury vinyl tiles. With an established reputation and track record, 
                    SMJ Furnishings is arguably the top largest leading premier flooring specialist serving the commercial and institutional sectors in Asia.
                </h5>
                <a class="btn btn-outline-dark mt-3" href="aboutus.php">Learn more</a>
            </div>
            <!-- Image Column -->
            <div class="col-12 col-md-6 ps-md-4 transition-zoom-slow">
                <img src="assets/aboutus.jpg" class="img-fluid shadow-sm" alt="About Us">
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Hero Section Animation
        const heroText = document.querySelector(".hero-text");
        setTimeout(() => {
            heroText.classList.add("show");
        }, 500);
        document.getElementById("imageSlider").addEventListener("slide.bs.carousel", function () {
            heroText.classList.remove("show");
        });
        document.getElementById("imageSlider").addEventListener("slid.bs.carousel", function () {
            setTimeout(() => {
                heroText.classList.add("show");
            }, 500);
        });

        // Featured Products Navigation
        const products = [
            { image: "assets/BLUESTONE SQ Room Scene 1.jpg", alt: "Bluestone SQ", title: "Nylon Tiles", link: "nylon.php" },
            { image: "assets/BASICAL SQ Room Scene 1.jpg", alt: "Basical SQ", title: "Polypropylene Tiles", link: "polypropylene.php" },
            { image: "assets/Cloudy Ridge Plank.jpg", alt: "Cloudy Ridge Plank", title: "Colordot Collection", link: "colordot.php" },
            { image: "assets/Infinity-Dye-Collection.jpg", alt: "Infinity Dye", title: "Infinity Dye Collection", link: "infinitydye.php" },
            { image: "assets/Print-Broadloom-Collection.jpg", alt: "Print Broadloom", title: "Print Broadloom Collection", link: "printbroadloom.php" },
            { image: "assets/LVT SERIES 1 Room Scene 1.jpg", alt: "LVT Series 1", title: "Luxury Vinyl Collection", link: "luxuryvinyl.php" },
            { image: "assets/Frieze-Panel.jpg", alt: "Frieze Panel", title: "Broadloom", link: "broadloom.php" }
        ];

        let currentIndex = 0;
        const productsPerPage = 3;
        const productContainer = document.getElementById("productContainer");
        const prevButton = document.getElementById("prevButton");
        const nextButton = document.getElementById("nextButton");

        function displayProducts(direction = null) {
            if (direction) {
                productContainer.classList.add(`slide-${direction}`);
                setTimeout(() => {
                    productContainer.classList.remove(`slide-${direction}`);
                    productContainer.classList.add("reset");
                    updateContent();
                }, 500);
            } else {
                updateContent();
            }
        }

        function updateContent() {
            productContainer.innerHTML = "";
            const endIndex = Math.min(currentIndex + productsPerPage, products.length);
            for (let i = currentIndex; i < endIndex; i++) {
                const product = products[i];
                productContainer.innerHTML += `
                    <div class="col-md-4 col-sm-6">
                        <a href="${product.link}" class="d-block">
                            <div class="project-img-container">
                                <img src="${product.image}" class="img-fluid" alt="${product.alt}" style=" width: 350px; height: 467px; object-fit: cover;">
                            </div>
                            <h5 class="mt-2 fs-6 fw-600">${product.title}</h5>
                        </a>
                    </div>
                `;
            }
            productContainer.classList.add("reset");
        }

        prevButton.addEventListener("click", function () {
            currentIndex -= productsPerPage;
            if (currentIndex < 0) {
                currentIndex = Math.max(0, products.length - productsPerPage);
            }
            displayProducts("right");
        });

        nextButton.addEventListener("click", function () {
            currentIndex += productsPerPage;
            if (currentIndex >= products.length) {
                currentIndex = 0;
            }
            displayProducts("left");
        });

        // Initial display
        displayProducts();
    });
    </script>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>