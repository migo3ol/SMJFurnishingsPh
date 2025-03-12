<?php

include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $subject = htmlspecialchars($_POST["subject"]);
    $message = htmlspecialchars($_POST["message"]);

    // Save to Database
    $stmt = $conn->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);
    $stmt->execute();
    $stmt->close();

    // Send Email to Admin
    $admin_email = "smjsalesph@gmail.com";
    $email_subject = "New Contact Message from $name";
    $email_body = "Name: $name\nEmail: $email\nSubject: $subject\nMessage:\n$message";
    $headers = "From: $email\r\nReply-To: $email\r\n";

    if (mail($admin_email, $email_subject, $email_body, $headers)) {
        $success_message = "Message sent and saved successfully!";
    } else {
        $error_message = "Message saved, but email sending failed.";
    }
}
?>

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
            color: white;
            padding: 100px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            text-align: left;
            height: 100vh;
        }
        .contact-section h2 {
            font-weight: 600;
        }
        .contact-section p {
            margin: 5px 0;
            font-size: 16px;
        }
        .form-section {
            padding: 50px;
        }
        .form-section h2 {
            font-weight: 600;
        }
        .form-control {
            max-width: 500px;
            align-items: center;
            border-radius: 10px;
        }
        .btn-custom {
            max-width: 500px;
            width: 100%;
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
<?php include 'navbar.php'; ?>

<div class="container-fluid">
        <div class="row">
            <!-- Left Section -->
            <div class="col-md-6 contact-section">
                <h1><strong>Contact us</h1></strong><br><br>
                <h4><strong>Address:</strong><br>
                    Salcedo Village, LGO1, Herrera Tower<br>
                    Condo., 98 V.A. Rufino St., Cor, 1227<br>
                    Valero, Makati, Metro Manila</h4><br>
                <h4><strong>Contact:</strong><br> +632 8133972 / +6328928701</h4><br>
                <h4><strong>Email:</strong><br> smjsalesph@gmail.com</h4><br>
            </div>

            <!-- Right Section -->
            <div class="col-md-6 form-section">
                <h2>Send us a message</h2>

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
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" name="message" rows="4"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-custom">Send</button>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>