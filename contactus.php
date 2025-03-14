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
            background: url('assets/contact-us-cover.png') no-repeat center center/cover;
            height: 90vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .contact-text {
            color: white;
            text-align: left;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            width: 100%;
        }
        .form-section {
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh;
        }
        .form-section > div {
            width: 100%;
            max-width: 700px; /* Set a fixed width for the form container */
        }
    </style>
</head>
<body>

    <!-- Navbar -->
<?php include 'navbar.php'; ?>

<div class="container-fluid">
        <div class="row">
            <!-- Left Section -->
            <div class="col-md-4 contact-section">
                <div class="contact-text bg-dark bg-opacity-50 p-3 rounded">
                    <h1 class="fw-bold">Contact us</h1><br><br>
                    <h4 class="mb-1 lh-lg fs-6"><strong>Address:</strong><br>
                        Salcedo Village, LGO1, Herrera Tower<br>
                        Condo., 98 V.A. Rufino St., Cor, 1227<br>
                        Valero, Makati, Metro Manila</h4><br>
                    <h4 class="mb-1 lh-lg fs-6"><strong>Contact:</strong><br> +632 8133972 / +6328928701</h4><br>
                    <h4 class="mb-1 lh-lg fs-6"><strong>Email:</strong><br> smjsalesph@gmail.com</h4><br>
                </div>
            </div>

            <!-- Right Section -->
            <div class="col-md-8 form-section">
                <div class="w-100" style="max-width: 700px;">
                    <h1 class="mb-5 fw-bold">Send us a message</h1>

                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success"><?= $success_message; ?></div>
                    <?php elseif (isset($error_message)): ?>
                        <div class="alert alert-danger"><?= $error_message; ?></div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Your Name (required)</label>
                            <input type="text" class="form-control rounded-3" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Your Email (required)</label>
                            <input type="email" class="form-control rounded-3" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" class="form-control rounded-3" name="subject">
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Message</label>
                            <textarea class="form-control rounded-3" name="message" rows="6" style="resize: none; overflow: hidden"></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 rounded-3">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
    
</body>
</html>