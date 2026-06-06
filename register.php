<?php

session_start();

if (isset($_SESSION['company_id'])) {
    header("Location: dashboard.php");
    exit();
}

include 'connect.php';

$error   = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $company_name = trim(mysqli_real_escape_string($conn, $_POST['company_name']));
    $email        = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password     = trim($_POST['password']);
    $confirm_pass = trim($_POST['confirm_password']);

    if (empty($company_name) || empty($email) || empty($password)) {
        $error = "All fields are required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";

    } elseif ($password !== $confirm_pass) {
        $error = "Passwords do not match.";

    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";

    } else {
        $check_sql = "SELECT id FROM companies WHERE email = '$email'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            $error = "This email is already registered. Please login.";
        } else {
            $hashed_password = md5($password);

            // Insert new company into the database
            $sql = "INSERT INTO companies (company_name, email, password)
                    VALUES ('$company_name', '$email', '$hashed_password')";

            if (mysqli_query($conn, $sql)) {
                $success = "Registration successful! You can now <a href='login.php'>login here</a>.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Company - ComplaintHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-brand"><a href="home.php"> ComplaintHub</a></div>
        <div class="nav-links">
            <a href="login.php">Login</a>
            <a href="submit_complaint.php">Submit Complaint</a>
        </div>
    </nav>

    <!-- Registration Form -->
    <div class="form-wrapper">
        <div class="form-card">
            <h2>Register Your Company</h2>
            <p class="form-subtitle">Create an account to manage your complaints.</p>

            <!-- Display error message if any -->
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php 
                    echo $error; 
                    ?>
                    </div>
            <?php endif; ?>

            <!-- Display success message if any -->
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php
                     echo $success; ?></div>
            <?php endif; ?>

            <!-- Registration Form -->
            <form action="register.php" method="POST" id="registerForm" novalidate>

                <div class="form-group">
                    <label for="company_name">Company Name</label>
                    <input type="text" id="company_name" name="company_name"
                           placeholder="e.g. Safaricom PLC"
                           value="<?php echo isset($_POST['company_name']) ? htmlspecialchars($_POST['company_name']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="email">Company Email</label>
                    <input type="email" id="email" name="email"
                           placeholder="company@example.com"
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Min. 6 characters">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat your password">
                </div>

                <button type="submit" class="btn btn-primary btn-full"
                        onclick="return validateRegister()">Create Account</button>

            </form>

            <p class="form-footer">Already registered? <a href="login.php">Login here</a></p>
        </div>
    </div>

   
    <script src="script.js"></script>
</body>
</html>
