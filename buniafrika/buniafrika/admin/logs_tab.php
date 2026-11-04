<?php
if (!defined('LOC_PROJECTS')) {
    exit('Direct access not permitted.');
}

$limit = 25;
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($page < 1) {
    $page = 1;
}
$offset = ($page - 1) * $limit;

$total_result = $conn->query("SELECT COUNT(*) FROM activity_logs");
$total_rows = $total_result ? (int)$total_result->fetch_row()[0] : 0;
$total_pages = ceil($total_rows / $limit);

$sql = "SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$logs = [];
if ($stmt) {
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $logs[] = $row;
        }
    }
    $stmt->close();
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-md); flex-wrap: wrap; gap: var(--spacing-sm);">
    <h2 style="font-size: 20px; color: var(--primary); margin: 0;">System & User Activity Logs</h2>
    <div style="display: flex; gap: var(--spacing-sm);">
        <a href="admin_dashboard.php?tab=logs&action=download" class="btn btn-primary js-download-btn download-icon" style="display: flex; align-items: center; gap: 8px; font-size: 13px; padding: 10px 18px; border-radius: var(--radius-full); text-decoration: none;">
            <span class="material-symbols-outlined" style="font-size: 18px;">download</span>
            Download CSV
        </a>
        <?php if ($total_rows > 0): ?>
            <a href="admin_dashboard.php?tab=logs&action=delete_all_logs" class="btn btn-outline js-delete-btn delete-icon" data-message="Are you sure you want to delete ALL activity logs? This action cannot be undone." style="display: flex; align-items: center; gap: 8px; font-size: 13px; padding: 10px 18px; border-radius: var(--radius-full); border: 1px solid var(--error); color: var(--error); text-decoration: none;">
                <span class="material-symbols-outlined" style="font-size: 18px;">delete_sweep</span>
                Clear All Logs
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if ($total_rows > 0): ?>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 150px;">Timestamp</th>
                    <th style="width: 100px;">User Type</th>
                    <th style="width: 120px;">Username</th>
                    <th>Activity</th>
                    <th>Details</th>
                    <th style="width: 120px;">IP Address</th>
                    <th style="width: 80px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $row) { 
                    $badge_class = 'badge-info';
                    if ($row['user_type'] === 'admin') {
                        $badge_class = 'badge-success';
                    } elseif ($row['user_type'] === 'user') {
                        $badge_class = 'badge-warning';
                    }
                    
                    $detail_parts = explode(' | UA: ', $row['details'] ?? '');
                    $main_details = $detail_parts[0];
                    $user_agent = isset($detail_parts[1]) ? $detail_parts[1] : '';
                ?>
                    <tr>
                        <td style="white-space: nowrap; font-size: 13px;"><?php echo date('Y-m-d H:i:s T', strtotime($row['created_at'])); ?></td>
                        <td><span class="badge <?php echo $badge_class; ?>"><?php echo h(ucfirst($row['user_type'])); ?></span></td>
                        <td>
                            <strong><?php echo h($row['username'] ?: '-'); ?></strong>
                            <?php if ($row['user_id'] > 0): ?>
                                <span style="font-size: 10px; opacity: 0.6;">(ID: <?php echo $row['user_id']; ?>)</span>
                            <?php endif; ?>
                        </td>
                        <td><span style="font-weight: 600; color: var(--primary);"><?php echo h($row['activity']); ?></span></td>
                        <td style="max-width: 300px; word-wrap: break-word; font-size: 13px; color: var(--on-surface-variant);">
                            <div><?php echo h($main_details ?: '-'); ?></div>
                            <?php if (!empty($user_agent)): ?>
                                <div style="font-size: 11px; opacity: 0.7; margin-top: 4px;" title="<?php echo h($user_agent); ?>">
                                    <strong>UA:</strong> <?php echo h(mb_strimwidth($user_agent, 0, 80, '...')); ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td style="font-family: monospace; font-size: 12px;"><?php echo h($row['ip_address'] ?: '-'); ?></td>
                        <td style="text-align: center;">
                            <div class="action-buttons" style="justify-content: center;">
                                <a href="admin_dashboard.php?tab=logs&action=delete_log&id=<?php echo $row['id']; ?>" class="action-btn action-btn-danger js-delete-btn" data-message="Are you sure you want to delete this activity log entry?" title="Delete Log">
                                    <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Controls -->
    <?php if ($total_pages > 1): ?>
        <div style="display: flex; justify-content: center; align-items: center; gap: 12px; margin-top: var(--spacing-lg);">
            <?php if ($page > 1): ?>
                <a href="admin_dashboard.php?tab=logs&p=<?php echo $page - 1; ?>" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px; display: flex; align-items: center; text-decoration: none;">
                    <span class="material-symbols-outlined" style="font-size: 16px;">chevron_left</span> Back
                </a>
            <?php endif; ?>
            
            <span style="font-size: 13px; color: var(--on-surface-variant);">Page <strong><?php echo $page; ?></strong> of <?php echo $total_pages; ?></span>
            
            <?php if ($page < $total_pages): ?>
                <a href="admin_dashboard.php?tab=logs&p=<?php echo $page + 1; ?>" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px; display: flex; align-items: center; text-decoration: none;">
                    Next <span class="material-symbols-outlined" style="font-size: 16px;">chevron_right</span>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

<?php else: ?>
    <p style="font-size: 14px; color: var(--on-surface-variant); text-align: center; padding: 40px 0;">No activity logs found.</p>
<?php endif; ?>
