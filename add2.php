<?php
// add.php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Inputs
    $discharge = $_POST['discharge'];
    $feelings_and_urge = $_POST['feelings_and_urge'];
    $pain_and_infection = $_POST['pain_and_infection'];
    $physical_conditions = $_POST['physical_conditions'];
    $critical_feelings = $_POST['critical_feelings'];
    $critical = $_POST['critical'];

    // Label encoding
    $discharge_options = [
        "Difficulties in stooling" => 0,
        "Frequent Urination" => 1,
        "Painful Urination" => 2,
        "Yellow,green discharge" => 3
    ];

    $feelings_options = [
        "Fever" => 0,
        "Hunger" => 1,
        "Tired" => 2,
        "Urge to urinate" => 3
    ];

    $pain_options = [
        "Abdominal Pain" => 0,
        "Anal Itching and Pain" => 1,
        "Blurred Vision" => 2,
        "Frequent Infection" => 3,
        "Headache" => 4,
        "Muscle Aches" => 5,
        "Swollen genitals" => 6
    ];

    $physical_options = [
        "Bloody diarrhea" => 0,
        "Excessive Urination and Thirst" => 1,
        "NIL" => 2,
        "Rashes" => 3,
        "Rose spots" => 4,
        "Swollen testicles" => 5
    ];

    $critical_feelings_options = [
        "Confusion" => 0,
        "Cough and breathing difficulties" => 1,
        "Disorientation" => 2,
        "NIL" => 3,
        "Seizures" => 4,
        "Severe pelvic pain" => 5,
        "Slow heart rate and pulse" => 6
    ];

    $critical_options = [
        "Critical" => 0,
        "Not Critical" => 1
    ];

    // Encode inputs
    $encoded_discharge = $discharge_options[$discharge];
    $encoded_feelings = $feelings_options[$feelings_and_urge];
    $encoded_pain = $pain_options[$pain_and_infection];
    $encoded_physical = $physical_options[$physical_conditions];
    $encoded_critical_feelings = $critical_feelings_options[$critical_feelings];
    $encoded_critical = $critical_options[$critical];

    // Script and Model Path
    $pythonPath = 'C:\Program Files\Python313\python.exe';
    $pythonScript = 'C:\xampp\htdocs\Diseases-Prediction-System\classify.py';
    $modelFile = 'C:\xampp\htdocs\Diseases-Prediction-System\rf_model_bundle.pkl';
    $data_to_classify = implode(" ", [
        $encoded_discharge,
        $encoded_feelings,
        $encoded_pain,
        $encoded_physical,
        $encoded_critical_feelings,
        $encoded_critical
    ]);

    // Execute the model
    $command = "\"$pythonPath\" \"$pythonScript\" \"$modelFile\" $data_to_classify 2>&1";
    $output = shell_exec($command);
    $predicted_disease = trim($output);

    // Insert into DB
    $sql = "INSERT INTO diseases (
                discharge, feelings_and_urge, pain_and_infection,
                physical_conditions, critical_feelings, critical, disease
            ) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssss",
        $discharge,
        $feelings_and_urge,
        $pain_and_infection,
        $physical_conditions,
        $critical_feelings,
        $critical,
        $predicted_disease
    );

    if ($stmt->execute()) {
        header("Location: ../pages/info_management/datasets.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    echo "<pre>$command</pre>";
    echo "<pre>$output</pre>";
    exit();
}
?>