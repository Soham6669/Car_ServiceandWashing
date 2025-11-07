<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Selecting the new 'charge' column
$sql = "SELECT id, service_type, booking_date, car_model, charge, status 
        FROM bookings 
        WHERE user_id = ? 
        ORDER BY booking_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings - Car Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1; /* Makes main content take remaining space */
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="index.php">Car Service</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">
                Hello, <?= htmlspecialchars($_SESSION['name']); ?>
            </span>
            <a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<main>
    <div class="container mt-5">
        <h2 class="mb-4 text-primary">My Bookings</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Service</th>
                            <th>Car Model</th>
                            <th>Date</th>
                            <!-- NEW COLUMN ADDED HERE -->
                            <th>Charge (₹)</th> 
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']); ?></td>
                            <td><?= htmlspecialchars($row['service_type']); ?></td>
                            <td><?= htmlspecialchars($row['car_model']); ?></td>
                            <td><?= htmlspecialchars($row['booking_date']); ?></td>
                            <!-- DISPLAYING THE CHARGE -->
                            <td class="fw-bold text-success">
                                ₹ <?= number_format($row['charge'], 0); ?>
                            </td>
                            <td>
                                <?php
                                $status = strtolower(trim($row['status'])); // Standardize status format
                                $status_class = '';
                                $display_text = ucwords(str_replace('_', ' ', $status));

                                switch ($status) {
                                    case 'pending':
                                        $status_class = 'bg-warning text-dark';
                                        break;
                                    case 'in_progress':
                                        $status_class = 'bg-info text-dark';
                                        break;
                                    case 'completed':
                                        $status_class = 'bg-success';
                                        break;
                                    case 'cancelled':
                                        $status_class = 'bg-danger';
                                        break;
                                    default:
                                        $status_class = 'bg-secondary';
                                        break;
                                }
                                echo '<span class="badge ' . htmlspecialchars($status_class) . '">' . htmlspecialchars($display_text) . '</span>';
                                ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">You have no bookings yet. Go <a href="book_service.php" class="alert-link">book a service</a>!</div>
        <?php endif; ?>
    </div>
</main>

<footer class="bg-dark text-white text-center py-4 mt-auto">
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
