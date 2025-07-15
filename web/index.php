<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capybara Crew Bot - Anmeldung</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <div style="text-align: center;">
            <h1>Capybara Crew Bot</h1>
            <p>Bitte melde dich mit Discord an, um fortzufahren.</p>
            <br>
            <a href="login.php" style="background-color: #7289da; color: white; padding: 15px 30px; border-radius: 5px; text-decoration: none;">Mit Discord anmelden</a>
        </div>
    </div>
</body>
</html>
