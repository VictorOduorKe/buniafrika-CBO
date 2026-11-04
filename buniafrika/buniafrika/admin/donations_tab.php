<?php
if (!defined('LOC_PROJECTS')) {
    exit('Direct access not permitted.');
}
?>
<h2 style="font-size: 20px; margin-bottom: var(--spacing-md); color: var(--primary);">Donations Log</h2>
<?php
$result = $conn->query("SELECT * FROM donations ORDER BY created_at DESC");
if ($result && $result->num_rows > 0) {
?>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Donor Name</th>
                    <th>Contact Info</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Reference</th>
                    <th>Message / Notes</th>
                    <th style="width: 100px; text-align: center;">Status</th>
                    <th style="width: 120px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { 
                    $status = strtolower($row['status']);
                    $statusColor = 'var(--on-surface-variant)';
                    $statusBg = 'var(--surface-container-high)';
                    if ($status === 'completed') {
                        $statusColor = '#2e7d32';
                        $statusBg = '#e8f5e9';
                    } elseif ($status === 'failed') {
                        $statusColor = '#c62828';
                        $statusBg = '#ffebee';
                    } elseif ($status === 'pending') {
                        $statusColor = '#b78103';
                        $statusBg = '#fff8e1';
                    }
                ?>
                    <tr>
                        <td style="white-space: nowrap;"><?php echo date(DATE_FORMAT_YMD_HI, strtotime($row['created_at'])); ?></td>
                        <td><strong><?php echo h($row['name']); ?></strong></td>
                        <td>
                            <div style="font-weight: 500;"><a href="mailto:<?php echo h($row['email']); ?>" style="color: var(--primary); text-decoration: underline;"><?php echo h($row['email']); ?></a></div>
                            <?php if (!empty($row['phone'])): ?>
                                <div style="font-size: 11px; color: var(--on-surface-variant); margin-top: 4px; display: flex; align-items: center; gap: 4px;">
                                    <span class="material-symbols-outlined" style="font-size: 13px;">call</span>
                                    <span><?php echo h($row['phone']); ?></span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td style="white-space: nowrap; font-weight: 700; color: var(--primary);">
                            <?php echo h($row['currency']) . ' ' . number_format($row['amount'], 2); ?>
                        </td>
                        <td style="white-space: nowrap;"><?php echo h($row['payment_method']); ?></td>
                        <td><code style="background: var(--background); padding: 2px 6px; border-radius: var(--radius-default); border: 1px solid var(--outline-variant); font-family: monospace; font-size: 12px; font-weight: 700;"><?php echo h($row['transaction_reference'] ?: '-'); ?></code></td>
                        <td style="max-width: 200px; word-wrap: break-word; font-size: 13px; color: var(--on-surface-variant);">
                            <?php echo nl2br(h($row['message'])); ?>
                        </td>
                        <td style="text-align: center;">
                            <span style="display: inline-block; padding: 4px 8px; border-radius: var(--radius-lg); font-size: 11px; font-weight: 700; text-transform: uppercase; color: <?php echo $statusColor; ?>; background-color: <?php echo $statusBg; ?>;">
                                <?php echo h($row['status']); ?>
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <div class="action-buttons" style="justify-content: center; gap: 6px;">
                                <?php if ($status !== 'completed'): ?>
                                    <a href="admin_dashboard.php?tab=donations&action=complete_donation&id=<?php echo $row['id']; ?>" class="action-btn" style="color: #2e7d32; border-color: #2e7d32; background: #e8f5e9;" title="Mark Completed">
                                        <span class="material-symbols-outlined" style="font-size:16px;">check</span>
                                    </a>
                                <?php endif; ?>
                                <?php if ($status !== 'failed'): ?>
                                    <a href="admin_dashboard.php?tab=donations&action=fail_donation&id=<?php echo $row['id']; ?>" class="action-btn" style="color: #c62828; border-color: #c62828; background: #ffebee;" title="Mark Failed">
                                        <span class="material-symbols-outlined" style="font-size:16px;">close</span>
                                    </a>
                                <?php endif; ?>
                                <a href="admin_dashboard.php?tab=donations&action=delete_donation&id=<?php echo $row['id']; ?>" class="action-btn action-btn-danger js-delete-btn" data-message="Are you sure you want to delete this donation record?" title="Delete record">
                                    <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php } else { ?>
    <p style="font-size: 14px; color: var(--on-surface-variant); text-align: center; padding: 40px 0;">No donations logged.</p>
<?php } ?>
