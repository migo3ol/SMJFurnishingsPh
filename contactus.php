<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>

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
        .contact-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
            url('assets/contact-us-cover.png') no-repeat center center/cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding:30px 15px;
            max-width: 90%;
            width: 100%;
        }
        .contact-text {
            color: white;
            text-align: left;
            padding: 30px;
            border-radius: 10px;
            max-width: 80%;
            width: 100%;
            position: relative;
            margin-top: -150px; 
            padding: 20px; 
            line-height: 1.6;
        }    
        .contact-text h1 {
            position: absolute; 
            top: 10px; 
            margin-bottom: 10px;
        }  
        .form-section {
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            
        }
        
        .form-section input,
        .form-section textarea {
            border: 1px solid #ccc !important;
            border-radius: 5px !important;
            padding: 12px !important;
            transition: 0.3s !important;
        }
       .form-section input:focus,
       .form-section textarea:focus {
            border-color: #ED4135 !important;
            box-shadow: #ED4135 !important;
        }       
        .form-control {
            border-radius: 10px;
            transition: all 0.3s ease-in-out;
        }
        .form-control:focus {
            box-shadow: #ED4135;
            border-color: #ED4135;
        }
        .form-control, .btn-danger {
            border-radius: 8px;
        }
        .btn-danger {
            background-color: #ED4135 !important;
            border: none;
            transition: 0.3s; 
        }
        .btn-danger:hover {
            background-color: #ED4135 !important;
            transition: 0.3s ease-in-out;
            width: 100%; 
            transform: scale(1.02); 
        }
        .send-btn {
            background-color: #ED4135 !important;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            font-size: 16px;
            transition: 0.3s ease-in-out;
            width: 100%; 
        }

       .send-btn:hover {
            background-color: #ED4135  !important;
            box-shadow: #ED4135  !important;
            transform: scale(1.02); 
       }

       /* Responsive Design */
       @media (max-width: 992px) {   
       .form-section {
            width: 100%;
            padding: 20px;
            text-align: left;
       }
       .form-section h1{
            display: block;  
            text-align: left !important; 
            width: 100%;
            padding: 20px;
            margin: 0 auto; 
            position: relative; 
       }
       .form-section form {
            max-width: 90%;
            margin: auto;
       }
    }

    </style>
</head>
<body>

    <!-- Navbar -->
<?php include 'navbar.php'; ?>

<div class="container-fluid">
        <div class="row">
            <!-- Left Section -->
            <div class="col-lg-4 col-md-5 contact-section">
                <div class="contact-text">
                    <h1 class="fw-bold">Contact us</h1><br><br>
                    <hr class="border border-light">
                    <h5><strong>Address:</strong></h5>
                    <p>Salcedo Village, LGO1, Herrera Tower<br>98 V.A. Rufino St., Cor, 1227
                    <br>Valero, Makati, Metro Manila</p>
                    <h5><strong>Contact:</strong></h5>
                    <p>+632 8133972 <br> +6328928701</p>
                    <h5><strong>Email:</strong></h5>
                    <p>smjsalesph@gmail.com</p>
                </div>
            </div>

            <!-- Right Section -->
            <div class="col-lg-8 col-md-7 form-section">
                <div class="w-100" style="max-width: 700px;">
                    <h1 class="mb-4 fw-bold">Send us a message</h1>

                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success"><?= $success_message; ?></div>
                    <?php elseif (isset($error_message)): ?>
                        <div class="alert alert-danger"><?= $error_message; ?></div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Your Name (required)</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Your Email (required)</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" class="form-control" name="subject">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" name="message" rows="5"></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger send-btn sw-100">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
    
</body>
</html>