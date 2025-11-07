<?php
session_start();
include "db.php";

// 1. Authorization Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    // If not authorized, send them back to the login page or index
    header("Location: index.php");
    exit;
}

// 2. Input Validation
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = strtolower(trim($_GET['status']));

    $allowed_status = ['pending', 'in_progress', 'completed'];
    if (in_array($status, $allowed_status)) {
        
        // 3. Database Update
        $sql = "UPDATE bookings SET status=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            // Handle prepare error (e.g., table/column name is wrong)
            error_log("Failed to prepare statement: " . $conn->error);
        } else {
            $stmt->bind_param("si", $status, $id);
            
            if ($stmt->execute()) {
                // Success - the database row was updated
                error_log("Booking ID $id updated to status: $status successfully.");
            } else {
                // Handle execute error
                error_log("Failed to execute update for Booking ID $id: " . $stmt->error);
            }
            $stmt->close();
        }
    } else {
        error_log("Invalid status requested: " . $status);
    }
}

// 4. Redirection (MANDATORY for dashboard refresh)
// This must happen after the database operation to ensure the dashboard reflects the new count.
header("Location: admin_dashboard.php");
exit;
?>
