<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
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
</style>
</head>

<body>
<!-- Navbar -->
    <?php include 'navbar.php'; ?>
    
<!-- Hero Section -->
<div class="container my-5 text-center">
<h1 style="border-bottom: 3px solid #ED4135;" class="fw-bold d-inline-block pb-2 w-25 mb-5">
    About us
</h1>
    <div class="row align-items-center mt-3">
    <div class="col-md-6">
        <img src="assets/aboutus1.jpg" class="img-fluid shadow-lg" alt="About Us 1">
    </div>
    <div class="col-md-6">
        <h5>SMJ Furnishings was set up in 1988 and specialises in carpet tiles, broadloom carpets and luxury vinyl tiles. With an established reputation and track record, SMJ Furnishings is   arguably   the   top    largest   leading   premier   flooring    specialists   serving  the commercial and institutional sectors in Asia.</h5><br>
        <h5>Headquartered  in  Singapore,   SMJ  manufacturers  and  distributes  globally  a wide range of premier floorings.</h5><br>
        <h5>Despite   being   the   market   leader,   SMJ  continues  to  stay close to  the  ground. SMJ   understands    global    flooring   trends    and    continuously    develops   and produces new designs which are  well  received  by our customers. To  date,  SMJ  is the  only company  that  offers  the  widest  selection  of   top   quality   carpet   tiles,  broadloom carpets and vinyl tiles globally with the most competitive prices.</h5><br>
        <h5>At SMJ, we have an insatiable passion and belief in high quality floorings with ultimate style.  With   varieties    and   performance   as   our   top   priority,   it   is  our  business  to  make your working space look good.</h5><br>
    </div>
</div>
</div>

<!-- Line -->
<div class="container text-center my-5">
    <br>
    <hr class="border border-black border-0"> 
    <br>
</div>

<!-- SMJ Map -->
<div class="container my-5">
    <div class="row align-items-center mt-3">
            <img src="assets/SMJ_Map_Phil.png" class="img-fluid" alt="SMJ Map">
</div>
</div>

<!-- Line -->
<div class="container text-center my-5">
    <br>
    <hr class="border border-black border-0"> 
    <br>
</div>

<!-- Global Distribution -->
<div class="container-fluid my-5 bg-danger text-white p-5">
    <div class="row align-items-center mt-3">
    <div class="col-md-6">
        <h1 class="fw-bold text-center">Global Distribution</h1>
     </div>
    <div class="col-md-6">
        <h3>SMJ sell and distribute globally a wide range of premier carpets markted under our proprietary “SMJ” brand. We have established a wide distributin network of more than 260 distributors in more than 20 countries.</h3>
    </div>
</div>
</div>

<!-- Line -->
<div class="container text-center my-5">
    <br>
    <hr class="border border-black border-0"> 
    <br>
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

<!-- Line -->
<div class="container text-center my-5">
    <br>
    <hr class="border border-black border-0"> 
    <br>
</div>

<!-- Footer -->
<?php include 'Footer.php'; ?>
   
</body>
</html>