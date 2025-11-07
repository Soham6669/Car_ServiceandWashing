<?php
session_start();
include "db.php";

$error = "";
$success = "";

// Ensure an email is available for verification
if (!isset($_SESSION['verification_email'])) {
    // If somehow the user lands here without starting registration, redirect them
    header("Location: register_otp.php"); 
    exit;
}

$email_to_verify = $_SESSION['verification_email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = trim($_POST['otp']);

    if (empty($user_otp) || strlen($user_otp) != 6 || !ctype_digit($user_otp)) {
        $error = "Please enter a valid 6-digit OTP.";
    } else {
        // 1. Fetch the unverified user details and OTP securely
        $stmt = $conn->prepare("SELECT name, mobile, password_hash, otp FROM unverified_users WHERE email = ?");
        $stmt->bind_param("s", $email_to_verify);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            // 2. Compare OTPs
            if ($user_otp == $row['otp']) {
                // OTP MATCHES: Complete Registration
                $name = $row['name'];
                $mobile = $row['mobile'];
                $password_hash = $row['password_hash'];
                $role = 'user'; // Default role

                // 3. Move user data to the permanent 'users' table
                // Assumes 'users' table has (name, email, number, password, role) columns
                $insert_stmt = $conn->prepare("INSERT INTO users (name, email, number, password, role) VALUES (?, ?, ?, ?, ?)");
                $insert_stmt->bind_param("sssss", $name, $email_to_verify, $mobile, $password_hash, $role);

                if ($insert_stmt->execute()) {
                    // 4. Clean up: Delete temporary data from unverified_users
                    $delete_stmt = $conn->prepare("DELETE FROM unverified_users WHERE email = ?");
                    $delete_stmt->bind_param("s", $email_to_verify);
                    $delete_stmt->execute();
                    $delete_stmt->close();
                    
                    // 5. Auto-login the user
                    $_SESSION['user_id'] = $insert_stmt->insert_id;
                    $_SESSION['name'] = $name;
                    $_SESSION['role'] = $role;
                    unset($_SESSION['verification_email']); // Clear session variables
                    unset($_SESSION['verification_mobile']);

                    // Redirect to home page
                    header("Location: index.php");
                    exit;

                } else {
                    $error = "Registration failed (Error moving data to main table): " . $insert_stmt->error;
                }
                $insert_stmt->close();
            } else {
                $error = "Invalid OTP. Please check your email and try again.";
            }
        } else {
            $error = "Verification record not found. Please try registering again.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Email - Car Service</title>
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
                <li class="nav-item"><a class="nav-link" href="register_otp.php">Register</a></li>
                <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
            </ul>
        </div>
    </div>
</nav>
    
<div class="form-page-background">
    <div class="card shadow-lg p-4" style="max-width: 450px; width: 100%;">
        <div class="text-center mb-4">
            <div class="card-icon"><i class="fas fa-envelope-open-text"></i></div>
            <h2 class="mt-2">Verify Your Email</h2>
            <p class="text-muted">A 6-digit code has been sent to <strong><?= htmlspecialchars($email_to_verify) ?></strong>.</p>
        </div>
        
        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
        <?php } ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Enter OTP</label>
                <input type="text" name="otp" class="form-control form-control-lg text-center" 
                       maxlength="6" pattern="\d{6}" inputmode="numeric" required>
            </div>

            <button type="submit" class="btn btn-success w-100 mt-3">Verify Account</button>
        </form>

        <p class="text-center mt-3 mb-0">
            Did not receive the code? <a href="register_otp.php">Resend OTP / Start Over</a>
            <!-- Added a static warning message instead of a blocking JS alert -->
            <small class="d-block text-danger mt-1">Starting a new registration will generate a new OTP and invalidate the previous one.</small>
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

</body>
</html>
