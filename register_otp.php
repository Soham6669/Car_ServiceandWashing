<?php
session_start();
include "db.php";

$error = "";
$success = "";

// Ensure an email is available for verification. If not, redirect to registration start.
if (!isset($_SESSION['verification_email']) || empty($_SESSION['verification_email'])) {
    header("Location: register.php"); 
    exit;
}

$email_to_verify = $_SESSION['verification_email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = trim($_POST['otp']);

    if (empty($user_otp) || strlen($user_otp) != 6 || !ctype_digit($user_otp)) {
        $error = "Please enter a valid 6-digit OTP.";
    } else {
        // 1. Fetch the unverified user details and OTP securely
        // NOTE: If using PDO/prepared statements, you must still ensure the query
        // is exactly as expected, but the use of bind_param helps mitigate injection.
        $stmt = $conn->prepare("SELECT name, mobile, password, otp FROM unverified_users WHERE email = ?");
        
        if ($stmt === false) {
            $error = "Database error during preparation: " . $conn->error;
        } else {
            $stmt->bind_param("s", $email_to_verify);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                
                // 2. Compare OTPs
                if ($user_otp == $row['otp']) {
                    // OTP MATCHES: Complete Registration
                    
                    // Start Transaction
                    $conn->begin_transaction();
                    
                    try {
                        // 3. Insert user into the final 'users' table
                        $insert_user_sql = "INSERT INTO users (name, email, mobile, password) VALUES (?, ?, ?, ?)";
                        $stmt_insert = $conn->prepare($insert_user_sql);
                        $stmt_insert->bind_param("ssss", $row['name'], $email_to_verify, $row['mobile'], $row['password']);
                        $stmt_insert->execute();
                        
                        if ($stmt_insert->affected_rows === 1) {
                            // 4. Delete entry from 'unverified_users' table
                            $delete_unverified_sql = "DELETE FROM unverified_users WHERE email = ?";
                            $stmt_delete = $conn->prepare($delete_unverified_sql);
                            $stmt_delete->bind_param("s", $email_to_verify);
                            $stmt_delete->execute();
                            
                            // 5. Commit transaction and clear session data
                            $conn->commit();
                            unset($_SESSION['verification_email']);
                            unset($_SESSION['temp_name']);
                            unset($_SESSION['temp_mobile']);
                            unset($_SESSION['temp_password']);
                            
                            // Redirect to login with success message (or auto-login)
                            $_SESSION['success_message'] = "Registration successful! You can now log in.";
                            header("Location: login.php");
                            exit;
                        } else {
                            $conn->rollback();
                            $error = "Failed to create user account. Please try again.";
                        }
                    } catch (Exception $e) {
                        $conn->rollback();
                        $error = "An unexpected error occurred during registration: " . $e->getMessage();
                    }

                } else {
                    $error = "Invalid OTP entered. Please try again.";
                }
            } else {
                // Should not happen if session is set, but handles stale data
                $error = "Verification details not found. Please register again.";
                header("Location: register.php"); // Start registration over
                exit;
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP - Car Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .otp-container {
            max-width: 400px;
            margin-top: 50px;
        }
    </style>
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

<div class="card shadow-lg p-4 otp-container">
    <div class="card-body">
        <h2 class="card-title text-center mb-4">OTP Verification</h2>
        <p class="text-center text-muted mb-4">
            A 6-digit OTP has been sent to **<?= htmlspecialchars($email_to_verify) ?>**. Please check your email.
        </p>
        
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
            Did not receive the code? <a href="register.php" class="text-danger">Resend OTP / Start Over (A new OTP will be sent)</a>
        </p>
    </div>
</div>

<footer class="bg-dark text-white text-center py-4 mt-5 fixed-bottom">
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
