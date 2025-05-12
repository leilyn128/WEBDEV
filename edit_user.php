<?php
session_start();
include "connection.php";

// Get the user ID from the URL
$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $contact_number = $_POST['contact_number'];
    $gender = $_POST['gender'];
    $username = $_POST['username'];

    // Update the user details
    $sql = "UPDATE users 
            SET first_name = ?, middle_name = ?, last_name = ?, 
                date_of_birth = ?, contact_number = ?, gender = ?, username = ?
            WHERE id = ?";

    $stmt = $dbhandle->prepare($sql);
    $stmt->bind_param("sssssssi", $first_name, $middle_name, $last_name, $date_of_birth, $contact_number, $gender, $username, $id);

    if ($stmt->execute()) {
        // ✅ Redirect based on origin page
        $redirectPage = isset($_GET['redirect']) ? $_GET['redirect'] : 'view_users.php';
        echo "<script>
                alert('User updated successfully!');
                window.location.href = '$redirectPage';
              </script>";
        exit(); // make sure no other code runs
    } else {
        echo "Error updating record: " . $dbhandle->error;
    }

    $stmt->close();
} else {
    // Fetch the current user details
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $dbhandle->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="css/edit_user.css">
</head>
<body>

    <div class="container">
        <h2>Update Information</h2>
        <form method="POST">
            <label>First Name:</label>
            <input type="text" name="first_name" value="<?= $user['first_name']; ?>" required>

            <label>Middle Name:</label>
            <input type="text" name="middle_name" value="<?= $user['middle_name']; ?>" required>

            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?= $user['last_name']; ?>" required>

            <label>Date of Birth:</label>
            <input type="date" name="date_of_birth" value="<?= $user['date_of_birth']; ?>" required>

            <label>Contact Number:</label>
            <input type="text" name="contact_number" value="<?= $user['contact_number']; ?>" required>

            <label>Gender:</label>
            <select name="gender" required>
                <option value="Male" <?= $user['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?= $user['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                <option value="Prefer not to say" <?= $user['gender'] == 'Prefer not to say' ? 'selected' : ''; ?>>Prefer not to say</option>


            </select>

            <label>Username:</label>
            <input type="text" name="username" value="<?= $user['username']; ?>" required>

            <button type="submit" class="btn">Update</button>
            <a href="<?= isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : 'view_users.php'; ?>" 
            class="btn" style="background: #f44336;">Cancel</a>

            
        </form>
    </div>

</body>
</html>

<?php
$dbhandle->close();
?>
