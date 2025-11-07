<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Terms of Service - Car Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .margin {
    margin-top: 100px;
}
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
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
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="book_service.php">Book Service</a></li>
                    <li class="nav-item"><a class="nav-link" href="my_bookings.php">My Bookings</a></li>
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <?php if ($_SESSION['role'] == 'admin') { ?>
                            <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Admin Dashboard</a></li>
                        <?php } ?>
                        <li class="nav-item"><a class="btn btn-outline-light" href="logout.php">Logout</a></li>
                    <?php } else { ?>
                        <li class="nav-item"><a class="btn btn-outline-light me-2" href="register.php">Register</a></li>
                        <li class="nav-item"><a class="btn btn-outline-light" href="login.php">Login</a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h2 class="text-center section-title margin">Terms of Service</h2>
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <p>By accessing or using our website and services, you agree to be bound by these Terms of Service. If you disagree with any part of the terms, you may not access the service.</p>

                <h4 class="mt-4">Account Responsibilities</h4>
                <p>If you create an account, you are responsible for maintaining the confidentiality of your account and password and for restricting access to your computer. You agree to accept responsibility for all activities that occur under your account or password.</p>

                <h4 class="mt-4">Service Use</h4>
                <p>Our service is provided "as is" and "as available." We do not guarantee that the service will be uninterrupted or error-free. The information on this site is for general informational purposes only.</p>

                <h4 class="mt-4">Intellectual Property</h4>
                <p>The content, features, and functionality are and will remain the exclusive property of Car Service. The service is protected by copyright, trademark, and other laws.</p>
                
                <p class="mt-4">We reserve the right to modify or replace these terms at any time. By continuing to access or use our service after those revisions become effective, you agree to be bound by the revised terms.</p>
            </div>
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