<?php
session_start();
include 'database.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Fetch user details from the database
        $sql = "SELECT * FROM users_admin WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $hashed_password = $user['password'];

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Store user details in the session
                $_SESSION['user_id'] = $user['id']; // Store the user's ID
                $_SESSION['username'] = $user['username'];
                $_SESSION['pfp'] = $user['pfp']; // Profile picture (optional)

                // Redirect to the dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $message = 'Invalid password!';
            }
        } else {
            $message = 'User not found!';
        }
    } catch (mysqli_sql_exception $e) {
        $message = 'Database error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { 
            font-family: 'Poppins', sans-serif;
            display: flex; 
            height: 100vh; 
            align-items: center; 
            justify-content: center; 
            background: #f8f9fa; 
        }
        .login-container {
            width: 400px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #ED4135;
            border: none;
        }
        .btn-primary:hover {
            background-color: #ED4135;
        }
        .password-container {
            position: relative;
        }   
        .toggle-password {
            background: transparent;
            border: none;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>

<!-- Log in Form --> 
<div class="login-container text-center">
    <img src="assets/footer-logo.png" alt="Company Logo" width="120">
    <h3 class="mt-3 fw-bold">Log in</h3>

    <?php if ($message): ?>
        <div class="alert alert-danger"><?php echo $message; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="mb-3 text-start">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required placeholder="Enter your username">
        </div>
        <div class="mb-3 text-start">
            <label class="form-label">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" required placeholder="Enter password">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">👁</button>
            </div>
        </div>
        <p class="text-end"><a href="#">Forgot password?</a></p>
        <button type="submit" class="btn btn-primary w-100">Log in</button>
    </form>
    <button class="btn btn-outline-danger w-100 mt-2" onclick="window.location.href='register.php'">Register</button>
</div>

<script>
function togglePassword() {
    var pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>