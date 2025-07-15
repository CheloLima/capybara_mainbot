<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// This is a placeholder for the actual cog management logic
$cogs = array(
    'logging_system' => true,
    'temp_voice' => true,
    'self_roles' => true,
    'admin_tools' => true,
    'web_panel_connector' => true,
);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capybara Crew Bot - Modul-Verwaltung</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Modul-Verwaltung</h1>
        <form id="module-form">
            <!-- Cogs werden hier dynamisch geladen -->
        </form>
        <br>
        <a href="dashboard.php">Zurück zum Dashboard</a>
    </div>

    <script>
        async function fetchCogs() {
            const response = await fetch('api.php');
            const data = await response.json();
            const form = document.getElementById('module-form');
            form.innerHTML = ''; // Clear existing form

            const allCogs = ['logging_system', 'temp_voice', 'self_roles', 'admin_tools', 'web_panel_connector'];

            allCogs.forEach(cog => {
                const isEnabled = data.cogs.includes(cog);
                const label = document.createElement('label');
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.name = 'cogs[]';
                checkbox.value = cog;
                checkbox.checked = isEnabled;

                label.appendChild(checkbox);
                label.appendChild(document.createTextNode(` ${cog}`));
                form.appendChild(label);
                form.appendChild(document.createElement('br'));
            });

            const submitButton = document.createElement('input');
            submitButton.type = 'submit';
            submitButton.value = 'Änderungen speichern';
            form.appendChild(document.createElement('br'));
            form.appendChild(submitButton);
        }

        document.getElementById('module-form').addEventListener('submit', async (event) => {
            event.preventDefault();
            const formData = new FormData(event.target);
            const enabledCogs = formData.getAll('cogs[]');

            const response = await fetch('api.php');
            const data = await response.json();
            const loadedCogs = data.cogs;

            const toLoad = enabledCogs.filter(c => !loadedCogs.includes(c));
            const toUnload = loadedCogs.filter(c => !enabledCogs.includes(c));

            for (const cog of toLoad) {
                await fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'load', cog: cog })
                });
            }

            for (const cog of toUnload) {
                await fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'unload', cog: cog })
                });
            }

            alert('Änderungen gespeichert!');
            fetchCogs(); // Refresh the list
        });

        fetchCogs();
    </script>
</body>
</html>
