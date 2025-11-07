<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$services = [
    'Car Washing' => 500,
    'General Service' => 2500,
    'Full Checkup' => 5000,
    'Interior Deep Clean' => 3000,
    'Full Body Polish' => 4500
];

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CRITICAL FIX: Ensure user_id is explicitly cast to an integer 
    // to prevent foreign key constraint issues if the session value 
    // is non-numeric or malformed.
    $uid         = intval($_SESSION['user_id']); 
    
    // Safety check for invalid user ID
    if ($uid <= 0) {
        $error = "Authentication error: Invalid user session ID. Please log out and log back in.";
    }
    
    // Only proceed if no auth error
    if (empty($error)) {
        $car_model     = $_POST['car_model'];
        $issue_details = $_POST['issue_details'];
        $selected_services = isset($_POST['service_type']) ? $_POST['service_type'] : []; 
        $bookingdate = $_POST['booking_date'];
        
        $charge = 0;
        $service_list = [];

        if (empty($selected_services)) {
            $error = "Please select at least one service.";
        } else {
            foreach ($selected_services as $service) {
                if (isset($services[$service])) {
                    $charge += $services[$service];
                    $service_list[] = $service;
                } else {
                    $error = "Invalid service selected: " . htmlspecialchars($service);
                    break;
                }
            }
        }
        
        // Final validation before DB insertion
        if (empty($error) && empty($car_model)) {
            $error = "Car Model is required.";
        }
        if (empty($error) && empty($bookingdate)) {
            $error = "Booking Date is required.";
        }


        if (empty($error)) {
            $service_type_str = implode(', ', $service_list);
            $status = 'pending'; // Default status for new booking

            // SQL Injection protection using prepared statement (GOOD)
            $sql = "INSERT INTO bookings (user_id, service_type, booking_date, car_model, issue_details, status, charge) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                // Handle prepare error
                error_log("Database error (prepare): " . $conn->error);
                $error = "A system error occurred. Please try again.";
            } else {
                // Line 72: bind_param
                // Ensure $uid is the first parameter bound as 'i' (integer)
                $stmt->bind_param("issssid", $uid, $service_type_str, $bookingdate, $car_model, $issue_details, $status, $charge);
                
                // Line 74: execute() - This is where the error originally occurred
                if ($stmt->execute()) {
                    $success = "Service successfully booked! Your estimated charge is Rs. " . number_format($charge, 0) . ". We will contact you shortly.";
                    // Clear post data to prevent resubmission on refresh
                    $_POST = array(); 
                } else {
                    // Handle execute error
                    // Log the detailed error for debugging
                    error_log("Booking execution failed for UID: $uid. Error: " . $stmt->error);
                    
                    // Specific check for Foreign Key error text (though the fatal error usually skips this)
                    if (strpos($stmt->error, 'foreign key constraint fails') !== false) {
                        $error = "Booking failed due to a foreign key error. This usually means your user account ID could not be found. Please log out and log back in.";
                    } else {
                        $error = "Booking failed. Please check your data and try again.";
                    }
                }
                $stmt->close();
            }
        }
    }
}

// ... (Rest of HTML and PHP code)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Service - Car Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .service-list-card {
            max-width: 600px;
            margin: 20px auto;
        }
        .service-list-item {
            cursor: pointer;
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s;
        }
        .service-list-item:hover {
            background-color: #f8f9fa;
        }
        .service-list-item:last-child {
            border-bottom: none;
        }
        .service-checkbox {
            cursor: pointer;
        }
        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }
        .booking-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .navbar-logo {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 10px;
        }
        .navbar-brand {
            font-weight: bold;
            display: flex;
            align-items: center;
        }
        .navbar-dark {
            background-color: #0d6efd !important; /* Primary color */
        }
        .footer-link a {
            color: #ccc;
            text-decoration: none;
        }
        .footer-link a:hover {
            color: #fff;
            text-decoration: underline;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Navbar included for consistency -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="https://plus.unsplash.com/premium_photo-1725872220665-f7ace74b0091?q=80&w=1932&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
                     onerror="this.onerror=null;this.src='https://placehold.co/40x40/0d6efd/ffffff?text=CS';" 
                     alt="Logo" class="navbar-logo">
                <span>Car Service</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="book_service.php">Book Service</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="my_bookings.php">My Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card booking-card p-4">
                    <h2 class="card-title text-center mb-4 text-primary">Book Your Service</h2>
                    
                    <?php if (!empty($error)) { ?>
                        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
                    <?php } ?>

                    <?php if (!empty($success)) { ?>
                        <div class="alert alert-success text-center"><?= htmlspecialchars($success) ?></div>
                    <?php } ?>

                    <form method="post" action="book_service.php">
                        <!-- Car Model -->
                        <div class="mb-3">
                            <label for="car_model" class="form-label">Car Model</label>
                            <input type="text" class="form-control" id="car_model" name="car_model" 
                                   value="<?= htmlspecialchars($_POST['car_model'] ?? '') ?>" required
                                   placeholder="e.g., Maruti Swift, Hyundai i20">
                        </div>

                        <!-- Booking Date -->
                        <div class="mb-3">
                            <label for="booking_date" class="form-label">Preferred Booking Date</label>
                            <input type="date" class="form-control" id="booking_date" name="booking_date" 
                                   value="<?= htmlspecialchars($_POST['booking_date'] ?? date('Y-m-d', strtotime('+1 day'))) ?>" 
                                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                        </div>

                        <hr>
                        <h5 class="mb-3 text-secondary">Select Services</h5>
                        <!-- Services Checkboxes -->
                        <div class="card bg-light mb-3">
                            <div class="card-body p-0">
                                <?php foreach ($services as $name => $price): ?>
                                    <label class="d-flex justify-content-between align-items-center service-list-item">
                                        <span>
                                            <input type="checkbox" name="service_type[]" value="<?= htmlspecialchars($name) ?>" 
                                                   class="form-check-input me-2 service-checkbox" data-price="<?= $price ?>"
                                                   <?= in_array($name, $selected_services ?? []) ? 'checked' : '' ?>>
                                            <?= htmlspecialchars($name) ?>
                                        </span>
                                        <span class="fw-bold text-success">Rs. <?= number_format($price, 0) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Issue Details -->
                        <div class="mb-3">
                            <label for="issue_details" class="form-label">Describe the Issue (Optional)</label>
                            <textarea class="form-control" id="issue_details" name="issue_details" rows="3"
                                      placeholder="Any specific issues or requests? (e.g., noisy brakes, AC low)">
                                <?= htmlspecialchars($_POST['issue_details'] ?? '') ?>
                            </textarea>
                        </div>
                        
                        <!-- Dynamic Charge Display -->
                        <div class="mb-4 p-3 bg-info-subtle border border-info rounded">
                            <h4 id="charge_display" class="mb-0 text-info">Estimated Charge: Rs. 0</h4>
                            <small class="text-muted">This is an estimate. Final charges will be confirmed after inspection.</small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">Confirm Booking</button>
                    </form>

                </div>
            </div>
        </div>
    </main>

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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
        const chargeDisplay = document.getElementById('charge_display');

        function updateChargeDisplay() {
            let totalCharge = 0;
            
            serviceCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const price = checkbox.getAttribute('data-price');
                    if (price) {
                        totalCharge += Number(price);
                    }
                }
            });
            
            const formattedPrice = Number(totalCharge).toLocaleString('en-IN', {
                style: 'currency',
                currency: 'INR',
                maximumFractionDigits: 0
            }).replace('₹', 'Rs. '); // Format to "Rs. X,XXX"
            
            chargeDisplay.textContent = `Estimated Charge: ${formattedPrice}`;
        }

        serviceCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateChargeDisplay);
        });

        updateChargeDisplay(); // Initial display update
        
        // Disable past dates and today's date (only allow tomorrow onwards)
        const bookingDateInput = document.getElementById('booking_date');
        const today = new Date();
        // Set min date to tomorrow
        today.setDate(today.getDate() + 1); 
        const tomorrowString = today.toISOString().split('T')[0];
        bookingDateInput.min = tomorrowString;
        
        // Set default value if it's earlier than tomorrow (only happens on first load)
        if (bookingDateInput.value < tomorrowString) {
             bookingDateInput.value = tomorrowString;
        }
    });
</script>

</body>
</html>
