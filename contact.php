<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
$currentPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="css/contact.css">
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
    <h1>📞 Contact Us</h1>
    <p>If you have any questions, concerns, or feedback, feel free to reach out through the following channels:</p>

    <div class="contact-info">
        <p><strong>📧 Email:</strong> LittleSteps@gmail.com</p>
        <p><strong>📍 Address:</strong> Bohol Island State University</p>
        <p><strong>📱 Phone:</strong> +63 912 345 6789</p>
        <p><strong>🕒 Office Hours:</strong> Monday to Friday, 9:00 AM – 5:00 PM</p>
    </div>

    <div class="note">
        <p>We aim to respond to all inquiries within 1–2 business days. Thank you for your patience!</p>
    </div>
</div>

</body>
</html>
