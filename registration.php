<?php
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = $_POST["first_name"];
    $middle_name = $_POST["middle_name"];
    $last_name = $_POST["last_name"];
    $date_of_birth = $_POST["date_of_birth"];
    $contact_number = $_POST["contact_number"];
    $gender = $_POST["gender"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password !== $confirm_password) {
        echo "<script>
                alert('Passwords do not match. Please try again.');
                window.location.href = 'registration.php';
              </script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (first_name, middle_name, last_name, date_of_birth, contact_number, gender, username, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $dbhandle->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $dbhandle->error);
    }

    $stmt->bind_param("ssssssss", $first_name, $middle_name, $last_name, $date_of_birth, $contact_number, $gender, $username, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>
                alert('Registration Successful!');
                window.location.href = 'login.php';
              </script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
$dbhandle->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nursery</title>
    <link rel="stylesheet" href="css/registration.css">

</head>
<body>
    <div class="container">
        <h1>Nursery Admission System</h1>
        
        <form action="registration.php" method="post">

            <label for="first_name">First Name</label>
            <input type="text" name="first_name" required>

            <label for="middle_name">Middle Name</label>
            <input type="text" name="middle_name" required>

            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" required>

            <label for="date_of_birth">Date of Birth</label>
            <input type="date" name="date_of_birth" required>

            <label for="contact_number">Contact Number</label>
            <input type="text" name="contact_number" required>

            <label for="gender">Gender</label>
            <select name="gender" required>
                <option value="" disabled selected>Select Gender</option>
                <option value="Female">Female</option>
                <option value="Male">Male</option>
                <option value="Prefer not to say">Prefer not to say</option>
            </select>

            <label for="username">Username</label>
            <input type="email" name="username" required>

            <label for="password">Password</label>
            <input type="password" name="password"  required>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" required>

            <button type="submit">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
