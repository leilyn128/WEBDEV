<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Show login success alert once
if (isset($_SESSION['login_success'])) {
    echo "<script>alert('" . $_SESSION['login_success'] . "');</script>";
    unset($_SESSION['login_success']);
}

// User info with fallback
$firstName = $_SESSION['first_name'] ?? 'First';
$lastName = $_SESSION['last_name'] ?? 'Last';
$username = $_SESSION['username'] ?? 'user';

$user = [
    'name' => $firstName . " " . $lastName,
    'username' => $username,
    'email' => $username . "@example.com",
    'joined' => 'January 2024',
];

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Determine current page
$currentPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Sample data (replace with DB values later)
$totalUsers = 124;
$submittedForms = 87;
$pendingStatuses = 14;
$recentActivities = [
    ['message' => 'Submitted a new form', 'timestamp' => 'Apr 14, 2025'],
    ['message' => 'Updated profile info', 'timestamp' => 'Apr 13, 2025'],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css" />
</head>
<body>
    <nav>
        <div class="nav-logo">
            <img src="img/favicon.png" alt="Logo">
        </div>
        <ul>
            <li><a href="dashboard.php" class="<?= ($currentPage === 'dashboard.php') ? 'active' : '' ?>">🏠 Home</a></li>
            <li><a href="view_users.php" class="<?= ($currentPage === 'view_users.php') ? 'active' : '' ?>">🗂️ View Users</a></li>
            <li><a href="admission_form.php" class="<?= ($currentPage === 'admission_form.php') ? 'active' : '' ?>">📝 Admission Form</a></li>
            <li><a href="submitted_forms.php" class="<?= ($currentPage === 'submitted_forms.php') ? 'active' : '' ?>">📤 Submitted Forms</a></li>
            <li><a href="status.php" class="<?= ($currentPage === 'status.php') ? 'active' : '' ?>">📈 Status Tracking</a></li>
            <li><a href="profile.php" class="<?= ($currentPage === 'profile.php') ? 'active' : '' ?>">👤 Profile</a></li>
            <li><a href="contact.php" class="<?= ($currentPage === 'contact.php') ? 'active' : '' ?>">📞 Contact</a></li>
            <li><a href="dashboard.php?logout=true">🚪 Logout</a></li>
        </ul>
    </nav>

    <div class="container">
    <h1>Welcome, <?= htmlspecialchars($user['name']) ?>!</h1>
    <p class="subtext">Here's a summary of your dashboard activity.</p>

    <div class="dashboard-cards">
        <div class="card">
            <h2>📁 Total Users</h2>
            <p>124</p>
        </div>
        <div class="card">
            <h2>📤 Forms Submitted</h2>
            <p>87</p>
        </div>
        <div class="card">
            <h2>⏳ Pending Status</h2>
            <p>14</p>
        </div>
    </div>

    <div class="section">
        <h3>📌 Recent Activity</h3>
        <ul class="activity-feed">
            <li>Submitted a new form <span class="timestamp">– April 14, 2025</span></li>
            <li>Updated profile info <span class="timestamp">– April 13, 2025</span></li>
        </ul>
    </div>

    <div class="section">
        <h3>⚡ Quick Actions</h3>
        <div class="quick-links">
            <a href="admission_form.php">📝 New Form</a>
            <a href="submitted_forms.php">📄 My Submissions</a>
            <a href="profile.php">👤 Edit Profile</a>
        </div>
    </div>
</div>
