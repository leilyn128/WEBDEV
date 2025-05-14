<?php
// add.php for Nursery Dataset
include 'connection.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Mapping options for categorical inputs
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
    $scriptPath = escapeshellarg('classify.py');
    $modelPath = escapeshellarg('model.pkl');

    // Escape arguments
    $escapedArgs = array_map('escapeshellarg', $features);
    $command = "$pythonPath $scriptPath $modelPath " . implode(" ", $features);

    error_log("Running command: $command"); // for debugging

    // Execute the Python script and capture the outputs
    $output = shell_exec($command . " 2>&1");

    // Check if output is empty or null
    if ($output === null || trim($output) === '') {
        exit("<pre>Error: Python script failed.\nCommand: $command\nOutput: $output</pre>");
    }

    // Trim the output to get the prediction result
    $target = trim($output);

    // Log the Python output for debugging purposes
    error_log("Python Output: " . $output);

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