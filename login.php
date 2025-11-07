<?php
session_start();
include "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // CRITICAL FIX: Use prepared statements to prevent SQL Injection
    // Fetch user data securely
    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // WARNING: This compares passwords in plain text as requested by
        // removing hashing in the registration process. This is insecure.
        if ($password === $row['password']) { 
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = $row['role'];
            
            // Redirect based on user role
            if ($row['role'] == 'admin') {
                // If user is an admin, redirect directly to the admin dashboard
                header("Location: admin_dashboard.php");
            } else {
                // Regular users go to the index page
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No account found with this email!";
    }
    $stmt->close(); // Close the statement
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Car Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php">
    <img src="https://plus.unsplash.com/premium_photo-1725872220665-f7ace74b0091?q=80&w=1932&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Logo" class="navbar-logo">
    <span>Car Service</span>
</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="form-page-background">
    <div class="card shadow-lg p-4" style="max-width: 450px; width: 100%;">
        <div class="text-center mb-4">
            <div class="card-icon"><i class="fas fa-user-lock"></i></div>
            <h2 class="mt-2">Login to Your Account</h2>
        </div>
        
        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php } ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="text" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-2">Login</button>
        </form>

        <p class="text-center mt-3 mb-0">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>
</div>

<footer class="bg-dark text-white text-center py-4 mt-5">
    <div class="container m0-auto">
        <p class="mb-2">© 2025 Car Service & Washing | All Rights Reserved by Soham Vedpathak</p>
        <ul class="list-inline mb-0">
            <li class="list-inline-item footer-link"><a href="privacy_policy.php">Privacy Policy</a></li>
            <li class="list-inline-item footer-link"><a href="terms_of_service.php">Terms of Service</a></li>
            <li class="list-inline-item footer-link"><a href="contact_us.php">Contact Us</a></li>
        </ul>
    </div>
</footer>


</body>
</html>
