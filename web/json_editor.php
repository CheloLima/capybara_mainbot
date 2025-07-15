<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$config_path = __DIR__ . '/../config/config.json';
$config = json_decode(file_get_contents($config_path), true);

// Don't display sensitive values
$config['bot_token'] = '********';
$config['mysql']['password'] = '********';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission to update the config
    $new_config = json_decode($_POST['config'], true);

    // Preserve the sensitive values
    $original_config = json_decode(file_get_contents($config_path), true);
    $new_config['bot_token'] = $original_config['bot_token'];
    $new_config['mysql']['password'] = $original_config['mysql']['password'];

    file_put_contents($config_path, json_encode($new_config, JSON_PRETTY_PRINT));
    header('Location: json_editor.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capybara Crew Bot - config.json Editor</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>config.json Editor</h1>
        <form action="json_editor.php" method="post">
            <textarea name="config" rows="20" cols="80"><?php echo json_encode($config, JSON_PRETTY_PRINT); ?></textarea>
            <br><br>
            <input type="submit" value="Änderungen speichern">
        </form>
        <br>
        <a href="dashboard.php">Zurück zum Dashboard</a>
    </div>
</body>
</html>
