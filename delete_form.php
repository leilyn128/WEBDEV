<?php
// Include your connection file
include 'connection.php';

// Get the ID from the URL
$id = $_GET['id'];

// Delete the record
$query = "DELETE FROM user_evaluation WHERE id = ?";
$stmt = mysqli_prepare($dbhandle, $query);
mysqli_stmt_bind_param($stmt, "i", $id);

if ($stmt->execute()) {
    echo "<script>
            alert('User deleted successfully!');
            window.location.href = 'view_form.php';
          </script>";
} else {
    echo "<script>
            alert('Error deleting user: " . $stmt->error . "');
            window.location.href = 'view_form.php';
          </script>";
}

mysqli_stmt_close($stmt);
mysqli_close($dbhandle);
?>
