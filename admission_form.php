<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
$currentPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

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
                <li><a href="admission_form.php" class="<?= ($currentPage === 'add.php') ? 'active' : '' ?>">📝 Admission Form</a></li>
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
        <option value="usual">Usual</option>
        <option value="pretentious">Pretentious</option>
        <option value="great_pret">Great Pretentious</option>
    </select>

    <label for="has_nurs">Has Nursery</label>
    <select id="has_nurs" name="has_nurs" required>
        <option value="" disabled selected>Select if nursery is attended</option>
        <option value="proper">Proper</option>
        <option value="less_proper">Less Proper</option>
        <option value="improper">Improper</option>
        <option value="critical">Critical</option>
        <option value="very_critical">Very Critical</option>
    </select>

    <label for="form_status">Form</label>
    <select id="form_status" name="form_status" required>
        <option value="" disabled selected>Select child’s form</option>
        <option value="complete">Complete</option>
        <option value="completed">Completed</option>
        <option value="incomplete">Incomplete</option>
        <option value="foster">Foster</option>
    </select>

    <label for="children">Children</label>
    <select id="children" name="children" required>
        <option value="" disabled selected>Select number of children</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="more">More than 3</option>
    </select>

    <label for="housing">Housing</label>
    <select id="housing" name="housing" required>
        <option value="" disabled selected>Select housing condition</option>
        <option value="convenient">Convenient</option>
        <option value="less_convenient">Less Convenient</option>
        <option value="critical">Critical</option>
    </select>

    <label for="finance">Finance</label>
    <select id="finance" name="finance" required>
        <option value="" disabled selected>Select financial situation</option>
        <option value="convenient">Convenient</option>
        <option value="inconvenient">Inconvenient</option>
    </select>

    <label for="social">Social</label>
    <select id="social" name="social" required>
        <option value="" disabled selected>Select social condition</option>
        <option value="problematic">Problematic</option>
        <option value="slightly_problematic">Slightly Problematic</option>
        <option value="non_problematic">Non-Problematic</option>
    </select>

    <label for="health">Health</label>
    <select id="health" name="health" required>
        <option value="" disabled selected>Select health status</option>
        <option value="special_priority">Special Priority</option>
        <option value="priority">Priority</option>
        <option value="very_recom">Very Recommended</option>
        <option value="recommend">Recommended</option>
        <option value="not_recom">Not Recommended</option>
    </select>

    <button type="submit">Submit</button>

</form>

        </div>
</div>

        </body>
        </html>
