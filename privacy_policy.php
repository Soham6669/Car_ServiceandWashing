<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Privacy Policy - Car Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .margin {
    margin-top: 100px;
}
    </style>
</head>
<body class="bg-light ">

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container pd-5">
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

    <div class="container my-5 mt-5 margin">
        <h2 class="text-center section-title margin">Privacy Policies</h2>
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <p>This Privacy Policy describes how your personal information is collected, used, and shared when you visit or make a booking from our website. We are committed to protecting your privacy.</p>
                
                <h4 class="mt-4">Information We Collect</h4>
                <p>We collect personal information such as your name, email address, and car model when you create an account or book a service. This information is necessary to process your booking and communicate with you.</p>

                <h4 class="mt-4">How We Use Your Information</h4>
                <p>The information we collect is used to:</p>
                <ul>
                    <li>Process your service bookings and payments.</li>
                    <li>Communicate with you regarding your booking status.</li>
                    <li>Provide customer support.</li>
                    <li>Improve our services.</li>
                </ul>

                <h4 class="mt-4">Data Security</h4>
                <p>We take reasonable measures to protect your personal information from unauthorized access, use, or disclosure. However, no method of transmission over the Internet or electronic storage is 100% secure. We cannot guarantee absolute security.</p>
                
                <p class="mt-4">By using our site, you agree to the collection and use of information in accordance with this policy. This policy is effective as of November 25, 2023.</p>
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