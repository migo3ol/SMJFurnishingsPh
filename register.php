<?php
include 'database.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security

    // Handle file upload
    $pfp = null;
    if (isset($_FILES['pfp']) && $_FILES['pfp']['error'] == 0) {
        $pfp = 'uploads/' . basename($_FILES['pfp']['name']);
        move_uploaded_file($_FILES['pfp']['tmp_name'], $pfp);
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO user_admins (firstname, middlename, surname, email, phone, username, password, pfp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $firstname, $middlename, $surname, $email, $phone, $username, $password, $pfp);

    // Execute the statement
    if ($stmt->execute()) {
        $message = "New record created successfully";
    } else {
        $message = "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMJF Registration</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .password-container {
            position: relative;
        }
        .toggle-password {
            background: transparent;
            border: none;
            cursor: pointer;
        }
        .input-group-text {
            background: transparent;
            border: none;
        }
        .input-group-text i {
            color: #000;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card p-4 shadow-lg" style="width: 600px;">
        <h3 class="text-center mb-5">Account Registration</h3>

        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Registration Form -->
        <form action="register.php" method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" class="form-control mb-3" id="firstName" name="firstname" placeholder="First Name" required>
                </div>
                <div class="col-md-4">
                    <label for="middleName" class="form-label">Middle Name</label>
                    <input type="text" class="form-control mb-3" id="middleName" name="middlename" placeholder="Middle Name">
                </div>
                <div class="col-md-4">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" class="form-control mb-3" id="lastName" name="surname" placeholder="Last Name" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control mb-3" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <label for="contactNo" class="form-label">Contact No.</label>
                <input type="text" class="form-control mb-3" id="contactNo" name="phone" placeholder="Contact No." required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control mb-3" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <span class="input-group-text toggle-password"><i class="fas fa-eye"></i></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                        <span class="input-group-text toggle-password"><i class="fas fa-eye"></i></span>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="pfp" class="form-label">Profile Picture</label>
                <input type="file" class="form-control" id="pfp" name="pfp">
            </div>
            <button type="submit" class="btn btn-danger w-100 mt-3">Create account</button>
            <div class="text-center mt-3">
                Already have an account? <a href="login.php" class="text-decoration-none">Log in</a>
            </div>
        </form>
    </div>
    <script>
        document.querySelectorAll('.toggle-password').forEach(item => {
            item.addEventListener('click', function() {
                let input = this.previousElementSibling;
                if (input.type === "password") {
                    input.type = "text";
                    this.querySelector('i').classList.replace("fa-eye", "fa-eye-slash");
                } else {
                    input.type = "password";
                    this.querySelector('i').classList.replace("fa-eye-slash", "fa-eye");
                }
            });
        });
    </script>
</body>
</html>