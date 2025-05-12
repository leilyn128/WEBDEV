<?php
session_start();
include "connection.php";

$sql = "SELECT id, first_name, middle_name, last_name, date_of_birth, contact_number, gender, username FROM users";
$result = $dbhandle->query($sql);
$currentPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>
    <link rel="stylesheet" href="css/view_users.css">
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
            <h2>Registered Users</h2>

            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Date of Birth</th>
                            <th>Contact Number</th>
                            <th>Gender</th>
                            <th>Username</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id']; ?></td>
                                <td><?= $row['last_name'].  ", " .$row['first_name']. " ".$row['middle_name']; ?></td>
                                <td><?= $row['date_of_birth']; ?></td>
                                <td><?= $row['contact_number']; ?></td>
                                <td><?= $row['gender']; ?></td>
                                <td><?= $row['username']; ?></td>
                                <td>
                                    <!-- Edit Button -->
                                    <a href="edit_user.php?id=<?= $row['id']; ?>" 
                                    style="color: #00cc66; font-weight: bold; text-decoration: none;">
                                    ✏️ Edit
                                    </a> 
                                    |
                                    <!-- Delete Button -->
                                    <a href="delete_user.php?id=<?= $row['id']; ?>" 
                                    onclick="return confirm('Are you sure you want to delete this user?');"
                                    style="color: red; font-weight: bold; text-decoration: none;">
                                    🗑️ Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No users found.</p>
            <?php endif; ?>

        <!--<a href="login.php" class="btn">Back to Login</a>-->
        </div>

        </body>
        </html>

        <?php
        $dbhandle->close();
        ?>
