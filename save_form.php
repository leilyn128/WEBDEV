<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];

    // Raw inputs from the form
    $parents = $_POST['parents'];
    $has_nurs = $_POST['has_nurs'];
    $form_status = $_POST['form_status'];
    $children = $_POST['children'];
    $housing = $_POST['housing'];
    $finance = $_POST['finance'];
    $social = $_POST['social'];
    $health = $_POST['health'];

    // Encoding logic
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

    // Apply encoding using the choices from the form
    $encoded_parents = $parents_options[$parents];
    $encoded_has_nurs = $has_nurs_options[$has_nurs];
    $encoded_form_status = $form_status_options[$form_status];
    $encoded_children = $children_options[$children];
    $encoded_housing = $housing_options[$housing];
    $encoded_finance = $finance_options[$finance];
    $encoded_social = $social_options[$social];
    $encoded_health = $health_options[$health];

    // Prepare data for prediction
    $pythonPath = 'C:\Users\torre\AppData\Local\Programs\Python\Python312\python.exe';
    $pythonScript = 'C:\xampp\htdocs\WEBDEV\classify.py';
    $modelFile = 'C:\xampp\htdocs\WEBDEV\model.pkl';

    // Join the encoded data for prediction
    $data_to_classify = implode(" ", [
        $encoded_parents,
        $encoded_has_nurs,
        $encoded_form_status,
        $encoded_children,
        $encoded_housing,
        $encoded_finance,
        $encoded_social,
        $encoded_health
    ]);

    // Call the Python script with encoded data
    $command = "\"$pythonPath\" \"$pythonScript\" \"$modelFile\" $data_to_classify 2>&1";
    $output = shell_exec($command);
    $prediction = trim($output);

    // Store the data into the database
    $query = "INSERT INTO user_evaluation 
        (user_id, parents, has_nurs, form_status, children, housing, finance, social, health, target)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($dbhandle, $query);
    mysqli_stmt_bind_param(
        $stmt,
        "isssssssss",
        $user_id,
        $parents,
        $has_nurs,
        $form_status,
        $children,
        $housing,
        $finance,
        $social,
        $health,
        $prediction
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: view_form.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($dbhandle);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($dbhandle);
} else {
    echo "Invalid request.";
}
?>
