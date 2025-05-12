<?php
session_start();
include "connection.php";

// Check if the ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare delete statement
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $dbhandle->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "<script>
                    alert('User deleted successfully!');
                    window.location.href = 'view_users.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error deleting user: " . $stmt->error . "');
                    window.location.href = 'view_users.php';
                  </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
                alert('Failed to prepare statement!');
                window.location.href = 'view_users.php';
              </script>";
    }

    $dbhandle->close();
} else {
    echo "<script>
            alert('Invalid ID!');
            window.location.href = 'view_users.php';
          </script>";
}
?>
