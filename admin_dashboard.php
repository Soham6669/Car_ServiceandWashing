<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); 
    exit;
}

$sql = "SELECT b.id, b.service_type, b.booking_date, b.car_model, b.status, b.charge, b.issue_details, u.name, u.email
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        ORDER BY b.id DESC";
$db_result = $conn->query($sql);

$pending_count = 0;
$completed_count = 0;
$all_bookings = []; 

if ($db_result) {
    while ($row = $db_result->fetch_assoc()) {
        $all_bookings[] = $row; 
        $status = strtolower(trim($row['status']));
        
        if ($status === 'pending') {
            $pending_count++;
        } elseif ($status === 'completed') {
            $completed_count++;
        }
    }
}


$filter_status = strtolower(trim($_GET['filter_status'] ?? 'all'));
$display_bookings = [];
$filter_title = "All Bookings Overview";

if ($filter_status === 'all') {
    $display_bookings = $all_bookings;
} elseif ($filter_status === 'pending' || $filter_status === 'completed') {
    foreach ($all_bookings as $booking) {
        if (strtolower(trim($booking['status'])) === $filter_status) {
            $display_bookings[] = $booking;
        }
    }
    $filter_title = ucwords(str_replace('_', ' ', $filter_status)) . " Bookings";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Car Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .dashboard-card {
            border-radius: 1rem;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer; 
            border: none;
            text-align: center; 
        }
        .dashboard-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.15) !important;
        }
        .icon-square {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin: 0 auto 0.75rem auto;
        }
        .issue-cell {
            max-width: 200px; 
            white-space: normal;
            word-wrap: break-word;
            font-size: 0.85rem;
        }
        .card.active-filter {
            border: 4px solid #1f2937 !important; 
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="admin_dashboard.php">Car Service - Admin</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">
                Hello, <?= htmlspecialchars($_SESSION['name']); ?>
            </span>
            <a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4 text-primary">Administrator Dashboard</h2>

    <div class="row g-4 mb-5 justify-content-center">

        <div class="col-md-4 col-sm-12">
            <a href="?filter_status=all" class="text-decoration-none">
                <div class="card dashboard-card h-100 shadow bg-light <?= ($filter_status === 'all' ? 'active-filter' : 'border-secondary'); ?>">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center flex-column">
                            <div class="icon-square bg-dark text-white shadow-sm">
                                <i class="fas fa-list-alt"></i>
                            </div>
                            <div>
                                <p class="text-uppercase fw-bold mb-0 text-dark opacity-75">All Bookings</p>
                                <h3 class="display-6 fw-bold mb-0 text-dark"><?= count($all_bookings); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-sm-12">
            <a href="?filter_status=pending" class="text-decoration-none">
                <div class="card dashboard-card bg-warning text-dark h-100 shadow <?= ($filter_status === 'pending' ? 'active-filter' : ''); ?>">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center flex-column">
                            <div class="icon-square bg-white text-warning shadow-sm">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <p class="text-uppercase fw-bold mb-0 opacity-75">Pending</p>
                                <h3 class="display-6 fw-bold mb-0"><?= $pending_count; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-sm-12">
            <a href="?filter_status=completed" class="text-decoration-none">
                <div class="card dashboard-card bg-success text-white h-100 shadow <?= ($filter_status === 'completed' ? 'active-filter' : ''); ?>">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center flex-column">
                            <div class="icon-square bg-white text-success shadow-sm">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <p class="text-uppercase fw-bold mb-0 opacity-75">Completed</p>
                                <h3 class="display-6 fw-bold mb-0"><?= $completed_count; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <h3 class="mb-4"><?= $filter_title; ?></h3>
    <?php if (!empty($display_bookings)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow-sm align-middle bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>User Name & Email</th>
                        <th>Car Model</th>
                        <th>Issue Details</th>
                        <th>Service</th>
                        <th>Charge (₹)</th> 
                        <th>Date</th>
                        <th>Status</th>
                        <th style="min-width: 150px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($display_bookings as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']); ?></td>
                        <td>
                            <?= htmlspecialchars($row['name']); ?><br>
                            <small class="text-muted"><?= htmlspecialchars($row['email']); ?></small>
                        </td>
                        <td><?= htmlspecialchars($row['car_model']); ?></td>
                        <td class="issue-cell">
                            <?= nl2br(htmlspecialchars($row['issue_details'] ?? 'N/A')); ?>
                        </td>

                        <td><?= htmlspecialchars($row['service_type']); ?></td>
                        <td class="fw-bold text-success">
                            ₹ <?= number_format($row['charge'] ?? 0, 0); ?>
                        </td>
                        <td><?= htmlspecialchars($row['booking_date']); ?></td>
                        <td>
                            <?php
                            $status = strtolower(trim($row['status'])); 
                            $display_text = ucwords(str_replace('_', ' ', $status));
                            $status_class = '';

                            switch ($status) {
                                case 'pending':
                                    $status_class = 'bg-warning text-dark';
                                    break;
                                case 'completed':
                                    $status_class = 'bg-success';
                                    break;
                                default:
                                    $status_class = 'bg-secondary';
                                    $display_text = "Other";
                                    break;
                            }
                            echo '<span class="badge ' . htmlspecialchars($status_class) . '">' . htmlspecialchars($display_text) . '</span>';
                            ?>
                        </td>
                        <td>
                            <a href="update_booking.php?id=<?= $row['id']; ?>&status=completed"
                               class="btn btn-sm btn-success mb-1 w-100">Complete</a>
    
                            <a href="update_booking.php?id=<?= $row['id']; ?>&status=pending"
                               class="btn btn-sm btn-warning mb-1 text-dark w-100">Set Pending</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">No <?= str_replace('_', ' ', $filter_status); ?> bookings found.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
