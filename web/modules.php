<?php
$page_title = 'Modul-Verwaltung';
require_once 'includes/header.php';
?>

<form id="module-form">
    <!-- Cogs werden hier dynamisch geladen -->
</form>

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

<?php require_once 'includes/footer.php'; ?>
