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
    <title>Capybara Crew Bot - Befehl & Rollen Konfiguration</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Slash-Befehl & Self-Role Konfiguration</h1>

        <h2>Slash-Befehl Berechtigungen</h2>
        <form id="permissions-form">
            <!-- Berechtigungen werden hier dynamisch geladen -->
        </form>

        <hr>

        <h2>Self-Roles</h2>
        <form id="roles-form">
            <!-- Rollen werden hier dynamisch geladen -->
        </form>

        <br>
        <a href="dashboard.php">Zurück zum Dashboard</a>
    </div>

    <script>
        async function fetchConfig() {
            const response = await fetch('api.php?web_config=true');
            const config = await response.json();

            const permsForm = document.getElementById('permissions-form');
            permsForm.innerHTML = '';
            for (const [command, role] of Object.entries(config.slash_command_permissions)) {
                const label = document.createElement('label');
                label.for = command;
                label.innerText = `${command}:`;
                const input = document.createElement('input');
                input.type = 'text';
                input.id = command;
                input.name = `permissions[${command}]`;
                input.value = role;
                permsForm.appendChild(label);
                permsForm.appendChild(input);
                permsForm.appendChild(document.createElement('br'));
            }
            const permsSubmit = document.createElement('input');
            permsSubmit.type = 'submit';
            permsSubmit.value = 'Berechtigungen speichern';
            permsForm.appendChild(document.createElement('br'));
            permsForm.appendChild(permsSubmit);

            const rolesForm = document.getElementById('roles-form');
            rolesForm.innerHTML = '';
            for (const [name, id] of Object.entries(config.self_roles)) {
                const label = document.createElement('label');
                label.for = `role_${id}`;
                label.innerText = `${name}:`;
                const input = document.createElement('input');
                input.type = 'text';
                input.id = `role_${id}`;
                input.name = `roles[${id}]`;
                input.value = id;
                rolesForm.appendChild(label);
                rolesForm.appendChild(input);
                rolesForm.appendChild(document.createElement('br'));
            }
            const rolesSubmit = document.createElement('input');
            rolesSubmit.type = 'submit';
            rolesSubmit.value = 'Rollen speichern';
            rolesForm.appendChild(document.createElement('br'));
            rolesForm.appendChild(rolesSubmit);
        }

        document.getElementById('permissions-form').addEventListener('submit', async (event) => {
            event.preventDefault();
            const formData = new FormData(event.target);
            const permissions = {};
            for (const [key, value] of formData.entries()) {
                const command = key.replace('permissions[', '').replace(']', '');
                permissions[command] = value;
            }

            const response = await fetch('api.php?web_config=true');
            const config = await response.json();
            config.slash_command_permissions = permissions;

            await fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ web_config: config })
            });

            alert('Berechtigungen gespeichert!');
        });

        document.getElementById('roles-form').addEventListener('submit', async (event) => {
            event.preventDefault();
            const formData = new FormData(event.target);
            const roles = {};
            for (const [key, value] of formData.entries()) {
                const id = key.replace('roles[', '').replace(']', '');
                // This is a simplification, we would need a way to edit role names too
                roles[`Rolle für ${id}`] = value;
            }

            const response = await fetch('api.php?web_config=true');
            const config = await response.json();
            config.self_roles = roles;

            await fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ web_config: config })
            });

            alert('Rollen gespeichert!');
        });

        fetchConfig();
    </script>
</body>
</html>
