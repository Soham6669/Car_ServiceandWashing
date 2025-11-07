<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Service & Washing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact_us.php">Contact</a></li>
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <li class="nav-item">
                            <span class="nav-link">Hello, <?= htmlspecialchars($_SESSION['name']) ?></span>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="book_service.php">Book Service</a></li>
                        <li class="nav-item"><a class="nav-link" href="my_bookings.php">My Bookings</a></li>
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

    <header class="hero-section">
        <div class="container d-flex flex-column align-items-center justify-content-center" style="min-height: 50vh;">
            <h1 class="display-3 fw-bold">Your Car, Our Passion.</h1>
            <p class="lead my-4">Expert car services and washing to keep your vehicle in perfect condition.</p>
            <a href="book_service.php" class="btn btn-primary btn-lg rounded-pill fw-bold">Book Your Service Now</a>
        </div>
    </header>

    <section class="py-5 bg-white" id="services">
        <div class="container">
            <h2 class="text-center section-title">Our Services</h2>
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="card p-4 h-100">
                        <div class="card-icon mb-3"><i class="fas fa-soap"></i></div>
                        <h5 class="card-title fw-bold">Car Washing</h5>
                        <p class="card-text text-muted">A sparkling clean finish, inside and out, using eco-friendly products.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 h-100">
                        <div class="card-icon mb-3"><i class="fas fa-wrench"></i></div>
                        <h5 class="card-title fw-bold">General Service</h5>
                        <p class="card-text text-muted">Comprehensive check-up and maintenance for optimal performance.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 h-100">
                        <div class="card-icon mb-3"><i class="fas fa-tools"></i></div>
                        <h5 class="card-title fw-bold">Full Checkup</h5>
                        <p class="card-text text-muted">In-depth diagnostics to identify and resolve any underlying issues.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="about-section py-5 bg-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <img src="https://media.istockphoto.com/id/1310978724/photo/the-washing-process-on-a-self-service-car-wash.jpg?s=2048x2048&w=is&k=20&c=i-HFeUACJhh7EZoGTh1_eDMaSRLK93G677v-x1iF7No=" class="img-fluid rounded shadow" alt="About Us">
            </div>
            <div class="col-md-6">
                <h2 class="section-title1">About Us</h2>
                <p class="lead">We are a team of passionate and certified mechanics dedicated to providing the best car care in the region.</p>
                <p>With years of experience, we have built a reputation for our commitment to quality, transparency, and customer satisfaction. We use the latest diagnostic tools and high-quality parts to ensure your vehicle is in peak condition.</p>
            </div>
        </div>
    </div>
    </section>

    <section class="why-choose-us py-5">
    <div class="container">
        <h2 class="text-center section-title">Why Choose Us?</h2>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="feature-box">
                    <div class="feature-icon"><i class="fas fa-tools"></i></div>
                    <h5 class="mt-3">Certified Mechanics</h5>
                    <p>Our team consists of highly skilled and certified professionals who know your car inside and out.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <div class="feature-icon"><i class="fas fa-thumbs-up"></i></div>
                    <h5 class="mt-3">Quality Guaranteed</h5>
                    <p>We use only genuine and high-quality parts to ensure lasting performance and reliability.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <div class="feature-icon"><i class="fas fa-clock"></i></div>
                    <h5 class="mt-3">Timely Service</h5>
                    <p>We value your time and are committed to providing fast and efficient service without compromising on quality.</p>
                </div>
            </div>
        </div>
    </div>
    </section>

    <section class="testimonials py-5 bg-white">
    <div class="container">
        <h2 class="text-center section-title">What Our Clients Say</h2>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card testimonial-card shadow-sm p-4 h-100">
                    <p class="mb-3 fst-italic">"The service was fantastic! They were professional and fixed my car's issue in no time. I highly recommend them."</p>
                    <div class="d-flex align-items-center">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"class="rounded-circle me-3" width="60">
                        <div>
                            <h6 class="mb-0">Ram Singh</h6>
                            <small class="text-muted">General Service</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card testimonial-card shadow-sm p-4 h-100">
                    <p class="mb-3 fst-italic">"My car looks brand new after the washing service. The attention to detail was amazing. Great job!"</p>
                    <div class="d-flex align-items-center">
                        <img src="https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?q=80&w=1964&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="rounded-circle me-3" width="60">
                        <div>
                            <h6 class="mb-0">Geeta Patil</h6>
                            <small class="text-muted">Car Washing</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>



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