<?php
// Include your connection file
include 'connection.php';

// Start session (optional if you're using $_SESSION for user ID)
session_start();

// If the form is submitted (for editing/updating)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $id = $_POST['id'];
    $parents = $_POST['parents'];
    $has_nurs = $_POST['has_nurs'];
    $form_status = $_POST['form_status'];
    $children = $_POST['children'];
    $housing = $_POST['housing'];
    $finance = $_POST['finance'];
    $social = $_POST['social'];
    $health = $_POST['health'];
    //$target = $_POST['target'];

    // Update the record in the database
    $query = "UPDATE user_evaluation 
              SET parents = ?, has_nurs = ?, form_status = ?, children = ?, housing = ?, finance = ?, social = ?, health = ?, target = ? 
              WHERE id = ?";
    $stmt = mysqli_prepare($dbhandle, $query);
    mysqli_stmt_bind_param($stmt, "sssssssssi", $parents, $has_nurs, $form_status, $children, $housing, $finance, $social, $health, $target, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Form updated successfully!'); window.location.href = 'view_form.php';</script>";
    } else {
        echo "Error updating record: " . $dbhandle->error;
    }
    mysqli_stmt_close($stmt);
} else {
    // If it's not a POST request, fetch the record for editing
    $id = $_GET['id']; // Get the ID from the URL

    // Fetch the record to edit
    $query = "SELECT * FROM user_evaluation WHERE id = ?";
    $stmt = mysqli_prepare($dbhandle, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Forms</title>
    <link rel="stylesheet" href="css/edit_form.css">
</head>
<body>
    <div class="container">
        <h2>Update Inputs</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
            
            <label for="parents">Parents:</label>
                <select id="parents" name="parents" required>
                    <option value="Usual" <?php if ($row['parents'] == 'Usual') echo 'selected'; ?>>Usual</option>
                    <option value="Pretentious" <?php if ($row['parents'] == 'Pretentious') echo 'selected'; ?>>Pretentious</option>
                    <option value="Great Pretentious" <?php if ($row['parents'] == 'Great Pretentious') echo 'selected'; ?>>Great Pretentious</option>
                </select>

                <label for="has_nurs">Has Nursery:</label>
                <select id="has_nurs" name="has_nurs" required>
                    <option value="Proper" <?php if ($row['has_nurs'] == 'Proper') echo 'selected'; ?>>Proper</option>
                    <option value="Less Proper" <?php if ($row['has_nurs'] == 'Less Proper') echo 'selected'; ?>>Less Proper</option>
                    <option value="Improper" <?php if ($row['has_nurs'] == 'Improper') echo 'selected'; ?>>Improper</option>
                    <option value="Critical" <?php if ($row['has_nurs'] == 'Critical') echo 'selected'; ?>>Critical</option>
                    <option value="Very Critical" <?php if ($row['has_nurs'] == 'Very Critical') echo 'selected'; ?>>Very Critical</option>
                </select>

                <label for="form_status">Form:</label>
                <select id="form_status" name="form_status" required>
                    <option value="Complete" <?php if ($row['form_status'] == 'Complete') echo 'selected'; ?>>Complete</option>
                    <option value="Completed" <?php if ($row['form_status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                    <option value="Incomplete" <?php if ($row['form_status'] == 'Incomplete') echo 'selected'; ?>>Incomplete</option>
                    <option value="Foster" <?php if ($row['form_status'] == 'Foster') echo 'selected'; ?>>Foster</option>
                </select>

                <label for="children">Children:</label>
                <select id="children" name="children" required>
                    <option value="1" <?php if ($row['children'] == '1') echo 'selected'; ?>>1</option>
                    <option value="2" <?php if ($row['children'] == '2') echo 'selected'; ?>>2</option>
                    <option value="3" <?php if ($row['children'] == '3') echo 'selected'; ?>>3</option>
                    <option value="More than 3" <?php if ($row['children'] == 'More than 3') echo 'selected'; ?>>More than 3</option>
                </select>

                <label for="housing">Housing:</label>
                <select id="housing" name="housing" required>
                    <option value="Convenient" <?php if ($row['housing'] == 'Convenient') echo 'selected'; ?>>Convenient</option>
                    <option value="Lessconvenient" <?php if ($row['housing'] == 'Lessconvenient') echo 'selected'; ?>>Less Convenient</option>
                    <option value="Critical" <?php if ($row['housing'] == 'Critical') echo 'selected'; ?>>Critical</option>
                </select>

                <label for="finance">Finance:</label>
                <select id="finance" name="finance" required>
                    <option value="Convenient" <?php if ($row['finance'] == 'Convenient') echo 'selected'; ?>>Convenient</option>
                    <option value="Inconvenient" <?php if ($row['finance'] == 'Inconvenient') echo 'selected'; ?>>Inconvenient</option>
                </select>

                <label for="social">Social:</label>
                <select id="social" name="social" required>
                    <option value="Problematic" <?php if ($row['social'] == 'Problematic') echo 'selected'; ?>>Problematic</option>
                    <option value="Slightly problematic" <?php if ($row['social'] == 'Slightly problematic') echo 'selected'; ?>>Slightly Problematic</option>
                    <option value="Non_Problematic" <?php if ($row['social'] == 'Non_Problematic') echo 'selected'; ?>>Non-Problematic</option>
                </select>

                <label for="health">Health:</label>
                <select id="health" name="health" required>
                    <option value="Special Priority" <?php if ($row['health'] == 'Special Priority') echo 'selected'; ?>>Special Priority</option>
                    <option value="Priority" <?php if ($row['health'] == 'Priority') echo 'selected'; ?>>Priority</option>
                    <option value="Very Recommended" <?php if ($row['health'] == 'Very Recommended') echo 'selected'; ?>>Very Recommended</option>
                    <option value="Recommended" <?php if ($row['health'] == 'Recommended') echo 'selected'; ?>>Recommended</option>
                    <option value="Not Recommended" <?php if ($row['health'] == 'Not Recommended') echo 'selected'; ?>>Not Recommended</option>
                </select>

                <button type="submit" class="btn">Update</button>
                <a href="view_form.php" class="btn" style="background: #f44336;">Cancel</a>
        </form>

     </div>
</body>
    <?php
        } else {
            echo "Record not found!";
        }

        mysqli_stmt_close($stmt);
    }
    mysqli_close($dbhandle);
    ?>
