<?php
session_start();
include "db.php";

// All PHPMailer includes and the send_otp_email function have been removed
// as OTP verification is no longer required.

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    // Standardized to 'mobile' in both PHP and HTML
    $mobile = trim($_POST['mobile']); 
    $password = $_POST['password'];
    
    // WARNING: Password hashing has been removed as requested. 
    // The password is now stored in PLAIN TEXT. This is a severe security risk. 
    $plain_password = $password; 

    // Basic Validation
    if (empty($name) || empty($email) || empty($mobile) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // 1. Check if email already exists in the permanent 'users' table
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();
        
        if ($stmt_check->num_rows > 0) {
            $error = "This email is already registered. Please <a href='login.php'>Login</a>.";
            $stmt_check->close();
        } else {
            $stmt_check->close();

            // 2. Direct Insertion into the 'users' table (Bypassing OTP and unverified_users)
            // Assign a default role
            $role = 'user'; 
            
            // NOTE: The column for the password is assumed to be 'password' 
            // in the final 'users' table.
            $sql = "INSERT INTO users (name, email, mobile, password, role) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql);
            
            // Bind parameters: name, email, mobile, plain_password, role
            // The password is bound as plain text here.
            $stmt_insert->bind_param("sssss", $name, $email, $mobile, $password, $role);
            
            if ($stmt_insert->execute()) {
                // Success: Redirect to login page
                header("Location: login.php");
                exit;
            } else {
                $error = "Registration failed due to a database error. Please try again. Error: " . $stmt_insert->error;
                error_log("Insert into users failed: " . $stmt_insert->error);
            }
            $stmt_insert->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Car Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .form-page-background {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 80px; 
            padding-bottom: 50px;
        }
        .register-card {
            max-width: 450px; 
            width: 100%;
        }
    </style>
</head>
<body class="bg-light">

<!-- Navbar Placeholder -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="index.php">Car Service</a>
        <div class="d-flex">
            <a class="btn btn-outline-light btn-sm me-2" href="login.php">Login</a>
            <a class="btn btn-primary btn-sm" href="index.php">Home</a>
        </div>
    </div>
</nav>
    
<div class="form-page-background">
    <div class="card shadow-lg p-4 register-card">
        <div class="text-center mb-4">
            <h2 class="mt-2 text-primary">Create Account</h2>
            <p class="text-muted">Register to book your car service appointment.</p>
        </div>
        
        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
        <?php } ?>

        <!-- Registration Form -->
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Mobile Number</label>
                <input type="tel" name="mobile" class="form-control" required value="<?= htmlspecialchars($_POST['mobile'] ?? '') ?>">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Password</label>
                <!-- IMPORTANT UI FIX: Changed input type from 'text' to 'password' -->
                <input type="password" name="password" class="form-control" required>
            </div>
            
            <!-- Updated button text to reflect instant registration -->
            <button type="submit" class="btn btn-primary w-100 mt-2">Register</button>
        </form>
        
        <p class="text-center mt-3 mb-0">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>
</div>

<footer class="bg-dark text-white text-center py-4 mt-5">
    <div class="container">
        <p class="mb-2">© 2025 Car Service & Washing | All Rights Reserved by Soham Vedpathak</p>
        <ul class="list-inline mb-0">
            <li class="list-inline-item footer-link"><a href="privacy_policy.php">Privacy Policy</a></li>
            <li class="list-inline-item footer-link"><a href="terms_of_service.php">Terms of Service</a></li>
            <li class="list-inline-item footer-link"><a href="contact_us.php">Contact Us</a></li>
        </ul>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
