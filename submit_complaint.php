<?php

include 'connect.php';

$error   = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize all inputs
    $user_name  = trim(mysqli_real_escape_string($conn, $_POST['user_name']));
    $user_email = trim(mysqli_real_escape_string($conn, $_POST['user_email']));
    $company_id = intval($_POST['company_id']); // Cast to int for safety
    $message    = trim(mysqli_real_escape_string($conn, $_POST['message']));

    // Server-side validation
    if (empty($user_name) || empty($user_email) || empty($message) || $company_id == 0) {
        $error = "All fields are required. Please fill in every field.";

    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";

    } elseif (strlen($message) < 20) {
        $error = "Your complaint must be at least 20 characters long.";

    } else {
        // Verify the selected company exists
        $check = mysqli_query($conn, "SELECT id FROM companies WHERE id = $company_id");

        if (mysqli_num_rows($check) == 0) {
            $error = "Selected company not found. Please choose a valid company.";
        } else {
            // Insert complaint into database
            $sql = "INSERT INTO complaints (company_id, user_name, user_email, message)
                    VALUES ('$company_id', '$user_name', '$user_email', '$message')";

            if (mysqli_query($conn, $sql)) {
                $success = "Your complaint has been submitted successfully! The company will be notified.";
            } else {
                $error = "Something went wrong. Please try again later.";
            }
        }
    }
}

$companies_result = mysqli_query($conn, "SELECT id, company_name FROM companies ORDER BY company_name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint - ComplaintHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-brand"><a href="home.php"> ComplaintHub</a></div>
        <div class="nav-links">
            <a href="login.php">Company Login</a>
            <a href="register.php">Register</a>
        </div>
    </nav>

    <!-- Complaint Form -->
    <div class="form-wrapper">
        <div class="form-card form-card-wide">
            <h2>Submit a Complaint</h2>
            <p class="form-subtitle">Tell us about your experience. No account required.</p>

            <!-- Alert messages -->
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form action="submit_complaint.php" method="POST" id="complaintForm" novalidate>

                <div class="form-row">
                    <div class="form-group">
                        <label for="user_name">Your Full Name</label>
                        <input type="text" id="user_name" name="user_name"
                               placeholder="e.g. Jane Wanjiku"
                               value="<?php echo isset($_POST['user_name']) ? htmlspecialchars($_POST['user_name']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="user_email">Your Email Address</label>
                        <input type="email" id="user_email" name="user_email"
                               placeholder="you@example.com"
                               value="<?php echo isset($_POST['user_email']) ? htmlspecialchars($_POST['user_email']) : ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="company_id">Select Company</label>
                    <select id="company_id" name="company_id">
                        <option value="0">-- Choose a company --</option>
                        <?php
                        while ($company = mysqli_fetch_assoc($companies_result)):
                            $selected = (isset($_POST['company_id']) && $_POST['company_id'] == $company['id']) ? 'selected' : '';
                        ?>
                            <option value="<?php echo $company['id']; ?>" <?php echo $selected; ?>>
                                <?php echo htmlspecialchars($company['company_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="message">Complaint Details</label>
                    <textarea id="message" name="message" rows="6"
                              placeholder="Describe your complaint in detail (minimum 20 characters)..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    <!-- Character counter powered by script.js -->
                    <small id="charCount" class="char-count">0 characters</small>
                </div>

                <button type="submit" class="btn btn-primary btn-full"
                        onclick="return validateComplaint()">Submit Complaint</button>

            </form>
        </div>
    </div>



    <script src="script.js"></script>
</body>
</html>
