<?php
// add.php for Nursery Dataset
include 'connection.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<pre>Command: $command</pre>";
echo "<pre>Python Output: $output</pre>";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   
    $parents_options = [
        "usual" => 0,
        "pretentious" => 1,
        "great_pret" => 2
    ];

    $has_nurs_options = [
        "proper" => 0,
        "less_proper" => 1,
        "improper" => 2,
        "critical" => 3,
        "very_critical" => 4
    ];

    $form_status_options = [
        "complete" => 0,
        "completed" => 1,
        "incomplete" => 2,
        "foster" => 3
    ];

    $children_options = [
        "1" => 0,
        "2" => 1,
        "3" => 2,
        "more" => 3
    ];

    $housing_options = [
        "convenient" => 0,
        "less_convenient" => 1,
        "critical" => 2
    ];

    $finance_options = [
        "convenient" => 0,
        "inconvenient" => 1
    ];

    $social_options = [
        "problematic" => 0,
        "slightly_problematic" => 1,
        "non_problematic" => 2
    ];

    $health_options = [
        "special_priority" => 0,
        "priority" => 1,
        "very_recom" => 2,
        "recommend" => 3,
        "not_recom" => 4
    ];

    // Retrieve user input from POST
    $parents = $_POST['parents'];
    $has_nurs = $_POST['has_nurs'];
    $form_status = $_POST['form_status'];
    $children = $_POST['children'];
    $housing = $_POST['housing'];
    $finance = $_POST['finance'];
    $social = $_POST['social'];
    $health = $_POST['health'];

    // Encode categorical inputs for the model
    $features = [
        $parents_options[$parents],
        $has_nurs_options[$has_nurs],
        $form_status_options[$form_status],
        $children_options[$children],
        $housing_options[$housing],
        $finance_options[$finance],
        $social_options[$social],
        $health_options[$health]
    ];

    // Build shell command for the Python script
    $pythonPath = escapeshellarg('C:\Users\User\AppData\Local\Microsoft\WindowsApps\python.exe');
    $scriptPath = escapeshellarg('C:\xampp\htdocs\Nursery-Prediction\classify.py');
    $modelPath = escapeshellarg('C:\xampp\htdocs\Nursery-Prediction\model.pkl');

    // Escape arguments
    $escapedArgs = array_map('escapeshellarg', $features);
    $command = "$pythonPath $scriptPath $modelPath " . implode(" ", $escapedArgs);

    error_log("Running command: $command"); // for debugging

    // Execute the Python script and capture the output
    $output = shell_exec($command . " 2>&1");

    // Check if output is empty or null
    if ($output === null || trim($output) === '') {
        exit("<pre>Error: Python script failed.\nCommand: $command\nOutput: $output</pre>");
    }

    //  Get prediction result
    $target = trim($output);

    // Insert form data + prediction into the database
    $sql = "INSERT INTO user_evaluation (
                parents, has_nurs, form_status, children, housing, finance, social, health, target
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssss",
        $parents,
        $has_nurs,
        $form_status,
        $children,
        $housing,
        $finance,
        $social,
        $health,
        $target
    );

    if (!$stmt->execute()) {
        die("Database insert failed: " . $stmt->error);
    }

    // Redirect to dataset list page
    header("Location: submitted_forms.php");
    exit();
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Form</title>
    <link rel="stylesheet" href="css/admission.css">
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
        <h1>Admission Form</h1>
        <form action="save_form.php" method="POST">
            <label for="parents">Parents</label>
            <select id="parents" name="parents" required>
                <option value="" disabled selected>Select parent's status</option>
                <option value="Usual">Usual</option>
                <option value="Pretentious">Pretentious</option>
                <option value="Great Pretentious">Great Pretentious</option>
            </select>

            <label for="has_nurs">Has Nursery</label>
            <select id="has_nurs" name="has_nurs" required>
                <option value="" disabled selected>Select if nursery is attended</option>
                <option value="Proper">Proper</option>
                <option value="Less Proper">Less Proper</option>
                <option value="Improper">Improper </option>
                <option value="Critical">Critical </option>
                <option value="Very Critical">Very Critical </option>

            </select>

            <label for="form">Form</label>
            <select id="form_status" name="form_status" required>
                <option value="" disabled selected>Select child’s form</option>
                <option value="Complete">Complete</option>
                <option value="Completed">Completed</option>
                <option value="Incomplete">Incomplete</option>
                <option value="Foster">Foster</option>

            </select>

            <label for="children">Children</label>
            <select id="children" name="children" required>
                <option value="" disabled selected>Select number of children</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="More than 3">More than 3</option>
            </select>

            <label for="housing">Housing</label>
            <select id="housing" name="housing" required>
                <option value="" disabled selected>Select housing condition</option>
                <option value="Convenient">Convenient</option>
                <option value="Less convenient">Less Convenient</option>
                <option value="Critical">Critical</option>
            </select>

            <label for="finance">Finance</label>
            <select id="finance" name="finance" required>
                <option value="" disabled selected>Select financial situation</option>
                <option value="Convenient">Convenient</option>
                <option value="Inconvenient">Inconvenient</option>
            </select>

            <label for="social">Social</label>
            <select id="social" name="social" required>
                <option value="" disabled selected>Select social condition</option>
                <option value="Problematic">Problematic</option>
                <option value="Slightly problematic">Slightly Problematic</option>
                <option value="Non_Problematic">Non-Problematic</option>

            </select>

            <label for="health">Health</label>
            <select id="health" name="health" required>
                <option value="" disabled selected>Select health status</option>
                <option value="Special Priority">Special Priority</option>
                <option value="Priority">Priority</option>
                <option value="Very Recommended">Very Recommended</option>
                <option value="Recommended">Recommended</option>
                <option value="Not Recommended">Not Recommended</option>
            </select>

            <button type="submit">Submit</button>


</form>
            </form>
        </div>
</div>

        </body>
        </html>
