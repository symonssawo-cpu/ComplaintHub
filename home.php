<?php


session_start(); 

if (isset($_SESSION['company_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ComplaintHub - Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-brand">ComplaintHub</div>
        <div class="nav-links">
            <a href="login.php">Company Login</a>
            <a href="register.php" class="btn-nav">Register Company</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Voice Your Concerns,<br>Speak Change.</h1>
            <p>ComplaintHub connects customers with companies to resolve issues faster and build better service standards.</p>
            <div class="hero-buttons">
                <a href="submit_complaint.php" class="btn btn-primary">Submit a Complaint</a>
                <a href="register.php" class="btn btn-outline">Register Your Company</a>
            </div>
        </div>
    </section>

    
   


</body>
</html>
