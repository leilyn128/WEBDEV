<?php
// logout.php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Logout</title>
    <script>
        window.onload = function() {
            const confirmLogout = confirm("Are you sure you want to log out?");
            if (confirmLogout) {
                // Proceed to logout by reloading the page with ?confirm=yes
                window.location.href = "logout.php?confirm=yes";
            } else {
                // Cancel logout and go back to dashboard
                window.location.href = "dashboard.php";
            }
        };
    </script>
</head>
<body>
<?php
// If user confirmed logout
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    session_unset();
    session_destroy();
    header("Location: index.php"); // Redirect to login page after logout
    exit();
}
?>
</body>
</html>
