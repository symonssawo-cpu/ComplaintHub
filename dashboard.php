<?php



session_start();

if (!isset($_SESSION['company_id'])) {
    header("Location: login.php");
    exit();
}

include 'connect.php';

$company_id   = $_SESSION['company_id'];
$company_name = $_SESSION['company_name'];

$sql    = "SELECT * FROM complaints
           WHERE company_id = $company_id
           ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

$total_sql    = "SELECT COUNT(*) AS total FROM complaints WHERE company_id = $company_id";
$total_result = mysqli_query($conn, $total_sql);
$total_row    = mysqli_fetch_assoc($total_result);
$total        = $total_row['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo htmlspecialchars($company_name); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-brand"><a href="home.php"> ComplaintHub</a></div>
        <div class="nav-links">
            <span class="nav-user"> <?php echo htmlspecialchars($company_name); ?></span>
            <a href="logout.php" class="btn-nav btn-danger">Logout</a>
        </div>
    </nav>

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="container">
            <h1>Welcome, <?php echo htmlspecialchars($company_name); ?></h1>
            <p>Here are all complaints submitted against your company.</p>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="stats-bar">
        <div class="container">
            <div class="stat-card">
                <span class="stat-number"><?php echo $total; ?></span>
                <span class="stat-label">Total Complaints</span>
            </div>
        </div>
    </div>

    <!-- Complaints Table -->
    <div class="container dashboard-body">

        <?php if ($total == 0): ?>
            <!-- Show message if no complaints exist -->
            <div class="no-complaints">
                <div class="no-complaints-icon"></div>
                <h3>No complaints yet!</h3>
                <p>Your company has not received any complaints. Keep up the great work!</p>
            </div>

        <?php else: ?>
            <!-- Complaints Table -->
            <div class="table-wrapper">
                <table class="complaints-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer Name</th>
                            <th>Customer Email</th>
                            <th>Complaint</th>
                            <th>Date Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 1;
                        while ($row = mysqli_fetch_assoc($result)):
                        ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                            <td>
                                <a href="mailto:<?php echo htmlspecialchars($row['user_email']); ?>">
                                    <?php echo htmlspecialchars($row['user_email']); ?>
                                </a>
                            </td>
                            <td class="complaint-text">
                                <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                            </td>
                            <td class="date-cell">
                                <?php echo date("d M Y, H:i", strtotime($row['created_at'])); ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>
    </div>


</body>
</html>
