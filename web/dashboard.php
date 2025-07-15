<?php
$page_title = 'Dashboard';
require_once 'includes/header.php';
?>

<div id="dashboard-stats">
    <p>Lade Statistiken...</p>
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

<?php require_once 'includes/footer.php'; ?>
