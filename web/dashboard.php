<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capybara Crew Bot - Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Willkommen, <?php echo $_SESSION['username']; ?>!</h1>
        <div id="dashboard-stats">
            <p>Lade Statistiken...</p>
        </div>
        <nav>
            <ul>
                <li><a href="modules.php">Modul-Verwaltung</a></li>
                <li><a href="config.php">Slash-Befehl & Self-Role Konfiguration</a></li>
                <li><a href="logs.php">Log-Anzeige</a></li>
                <li><a href="json_editor.php">config.json Editor</a></li>
            </ul>
        </nav>
        <a href="logout.php">Abmelden</a>
    </div>

    <script>
        async function fetchStats() {
            const response = await fetch('api.php');
            const data = await response.json();

            const statsDiv = document.getElementById('dashboard-stats');
            statsDiv.innerHTML = `
                <p>Server: ${data.guild_name}</p>
                <p>Mitglieder: ${data.member_count}</p>
                <p>Bot-Ping: ${data.bot_ping}ms</p>
                <p>Geladene Cogs: ${data.cogs.join(', ')}</p>
            `;
        }

        fetchStats();
        setInterval(fetchStats, 60000);
    </script>
</body>
</html>
