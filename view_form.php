<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo "User not logged in.";
    exit();
}

$user_id = $_SESSION["user_id"];

// Use prepared statement to get evaluations specific to logged-in user
$query = "SELECT * FROM user_evaluation WHERE user_id = ?";
$stmt = mysqli_prepare($dbhandle, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$currentPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Evaluation Records</title>
    <link rel="stylesheet" href="css/forms.css">
</head>
<body>

<nav>
    <div class="nav-logo">
        <form method="POST" action="add.php">
        <img src="img/favicon.png" alt="Logo">
    </div>
    <ul>
        <li><a href="dashboard.php" class="<?= ($currentPage === 'dashboard.php') ? 'active' : '' ?>">🏠 Home</a></li>
        <li><a href="profile.php" class="<?= ($currentPage === 'profile.php') ? 'active' : '' ?>">👤 Profile</a></li>
        <li><a href="admission_form.php" class="<?= ($currentPage === 'admission_form.php') ? 'active' : '' ?>">📝 Admission Form</a></li>
        <li><a href="submitted_forms.php" class="<?= ($currentPage === 'submitted_forms.php') ? 'active' : '' ?>">📤 Submitted Forms</a></li>
        <li><a href="status.php" class="<?= ($currentPage === 'status.php') ? 'active' : '' ?>">📈 Status Tracking</a></li>
        <li><a href="contact.php" class="<?= ($currentPage === 'contact.php') ? 'active' : '' ?>">📞 Contact</a></li>
        <li><a href="dashboard.php?logout=true">🚪 Logout</a></li>
    </ul>
</nav>

<main>
    <div class="evaluation-container">
        <h2>My Evaluation Records</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Parents</th>
                        <th>Nursery</th>
                        <th>Form Status</th>
                        <th>Children</th>
                        <th>Housing</th>
                        <th>Finance</th>
                        <th>Social</th>
                        <th>Health</th>
                        <th>Target</th> 
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= htmlspecialchars($row['parents']) ?></td>
                        <td><?= htmlspecialchars($row['has_nurs']) ?></td>
                        <td><?= htmlspecialchars($row['form_status']) ?></td>
                        <td><?= htmlspecialchars($row['children']) ?></td>
                        <td><?= htmlspecialchars($row['housing']) ?></td>
                        <td><?= htmlspecialchars($row['finance']) ?></td>
                        <td><?= htmlspecialchars($row['social']) ?></td>
                        <td><?= htmlspecialchars($row['health']) ?></td>
                        <td>
                            <?= htmlspecialchars($row['target'] ?? 'No Target Found') ?>
                        </td>
                        <td>
                            <a href="edit_forms.php?id=<?= $row['id']; ?>" style="color: #00cc66; font-weight: bold; text-decoration: none;">✏️ Edit</a> |
                            <a href="delete_form.php?id=<?= $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?');" style="color: red; font-weight: bold; text-decoration: none;">🗑️ Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-records">No records found!</p>
        <?php endif; ?>
    </div>
</main>

<?php
// Debugging: Check if data is being fetched correctly
if (mysqli_num_rows($result) > 0) {
    // Uncomment the following line for debugging
    // var_dump(mysqli_fetch_all($result, MYSQLI_ASSOC));
}

mysqli_stmt_close($stmt);
mysqli_close($dbhandle);
?>

</body>
</html>
