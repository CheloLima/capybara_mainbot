<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capybara Crew Bot - Anmeldung</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Capybara Crew Bot</h1>
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Willkommen, <?php echo $_SESSION['username']; ?>!</p>
            <a href="dashboard.php">Zum Dashboard</a>
            <a href="logout.php">Abmelden</a>
        <?php else: ?>
            <a href="login.php">Mit Discord anmelden</a>
        <?php endif; ?>
    </div>
</body>
</html>
