<?php
session_start();

if (isset($_SESSION['company_id'])) {
    header("Location: dashboard.php");
    exit();
}

include 'connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email    = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";

    } else {
        $hashed_password = md5($password);

        $sql    = "SELECT id, company_name FROM companies
                   WHERE email = '$email' AND password = '$hashed_password'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['company_id']   = $row['id'];
            $_SESSION['company_name'] = $row['company_name'];

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Login - ComplaintHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-brand"><a href="home.php"> ComplaintHub</a></div>
        <div class="nav-links">
            <a href="register.php">Register</a>
            <a href="submit_complaint.php">Submit Complaint</a>
        </div>
    </nav>

    <div class="form-wrapper">
        <div class="form-card">
            <h2>Company Login</h2>
            <p class="form-subtitle">Access your complaint management dashboard.</p>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" id="loginForm" novalidate>

                <div class="form-group">
                    <label for="email">Company Email</label>
                    <input type="email" id="email" name="email"
                           placeholder="company@example.com"
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Your password">
                </div>

                <button type="submit" class="btn btn-primary btn-full"
                        onclick="return validateLogin()">Login</button>

            </form>

            <p class="form-footer">No account yet? <a href="register.php">Register your company</a></p>
        </div>
    </div>


    <script src="script.js"></script>
</body>
</html>
