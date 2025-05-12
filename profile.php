<?php
include "connection.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo "<script>
            alert('Please log in first.');
            window.location.href = 'login.php';
          </script>";
    exit();
}

// Get the logged-in user's ID from session
$user_id = $_SESSION["user_id"];

// Prepare a query to fetch the user's information
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $dbhandle->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "<script>
            alert('User not found.');
            window.location.href = 'login.php';
          </script>";
    exit();
}
$currentPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$stmt->close();
$dbhandle->close();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/profile.css">
    
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
        <h1>My Profile</h1>
        
            
        
        <div class="profile-details">
            <p><strong>First Name:</strong> <?= htmlspecialchars($user['first_name']); ?></p>
            <p><strong>Middle Name:</strong> <?= htmlspecialchars($user['middle_name']); ?></p>
            <p><strong>Last Name:</strong> <?= htmlspecialchars($user['last_name']); ?></p>
            <p><strong>Date of Birth:</strong> <?= htmlspecialchars($user['date_of_birth']); ?></p>
            <p><strong>Contact Number:</strong> <?= htmlspecialchars($user['contact_number']); ?></p>
            <p><strong>Gender:</strong> <?= htmlspecialchars($user['gender']); ?></p>
            <p><strong>Username:</strong> <?= htmlspecialchars($user['username']); ?></p>


        </div>

        <div class="edit-btn-container">
            <a href="edit_user.php?id=<?= $user['id']; ?>&redirect=profile.php" class="edit-btn">✏️ Edit</a>
        </div>

</body>
</html>
