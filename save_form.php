<?php
session_start(); // Needed if you're using $_SESSION to get user_id

// Include your connection file
include 'connection.php';

// Make sure user_id is set (e.g., from login session)
$user_id = $_SESSION['user_id']; // Or replace with a fixed ID for testing

// Get form values
$parents = $_POST['parents'];
$has_nurs = $_POST['has_nurs'];
$form_status = $_POST['form_status'];
$children = $_POST['children'];
$housing = $_POST['housing'];
$finance = $_POST['finance'];
$social = $_POST['social'];
$health = $_POST['health'];
//$target = $_POST['target']; // Make sure to include the target field if you want to store it

// Prepare SQL query
$query = "INSERT INTO user_evaluation 
    (user_id, parents, has_nurs, form_status, children, housing, finance, social, health, target)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($dbhandle, $query);

// Bind parameters
mysqli_stmt_bind_param($stmt, "isssssssss", $user_id, $parents, $has_nurs, $form_status, $children, $housing, $finance, $social, $health, $target);

// Execute
if (mysqli_stmt_execute($stmt)) {
    // If successful, redirect to the view form page
    header("Location: view_form.php"); // Change 'view_form.php' to the actual page you want to redirect to
    exit(); // Ensure no further code is executed
} else {
    echo "Error: " . mysqli_error($dbhandle);
}

mysqli_stmt_close($stmt);
mysqli_close($dbhandle);
?>
