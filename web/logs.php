<?php
$page_title = 'Log-Anzeige';
require_once 'includes/header.php';
require_once 'includes/db.php';

$log_type = isset($_GET['log_type']) ? $_GET['log_type'] : 'main_logs';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

$conn = get_db_connection();

$query = "SELECT * FROM {$log_type} ORDER BY timestamp DESC LIMIT ?, ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $offset, $per_page);
$stmt->execute();
$result = $stmt->get_result();
$logs = $result->fetch_all(MYSQLI_ASSOC);

$count_query = "SELECT COUNT(*) as total FROM {$log_type}";
$total_rows = $conn->query($count_query)->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $per_page);

$stmt->close();
$conn->close();
?>

<div class="log-nav">
    <a href="?log_type=main_logs">Haupt-Logs</a>
    <a href="?log_type=channel_logs">Channel-Logs</a>
    <a href="?log_type=punishment_logs">Straf-Logs</a>
    <a href="?log_type=user_logs">Benutzer-Logs</a>
    <a href="?log_type=audit_logs">Audit-Logs</a>
</div>

<table>
    <thead>
        <tr>
            <?php if ($logs): ?>
                <?php foreach (array_keys($logs[0]) as $key): ?>
                    <th><?php echo htmlspecialchars($key); ?></th>
                <?php endforeach; ?>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($logs as $log): ?>
            <tr>
                <?php foreach ($log as $value): ?>
                    <td><?php echo htmlspecialchars($value); ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?log_type=<?php echo $log_type; ?>&page=<?php echo $i; ?>" <?php if ($i === $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
    <?php endfor; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
