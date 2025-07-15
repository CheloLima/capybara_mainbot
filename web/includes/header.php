<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Capybara Crew Bot'; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="sidebar">
        <h1>Capybara Bot</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="modules.php">Modul-Verwaltung</a></li>
                <li><a href="config.php">Befehl & Rollen Konfig</a></li>
                <li><a href="logs.php">Log-Anzeige</a></li>
                <li><a href="json_editor.php">config.json Editor</a></li>
                <li><a href="logout.php">Abmelden</a></li>
            </ul>
        </nav>
    </div>
    <div id="main-content">
        <div class="container">
            <h2><?php echo $page_title ?? ''; ?></h2>
