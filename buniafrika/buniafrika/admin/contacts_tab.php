<?php
if (!defined('LOC_PROJECTS')) {
    exit('Direct access not permitted.');
}
?>
<h2 style="font-size: 20px; margin-bottom: var(--spacing-md); color: var(--primary);">Contact Enquiries Log</h2>
<?php
$result = $conn->query("SELECT * FROM contact_submissions ORDER BY created_at DESC");
if ($result && $result->num_rows > 0) {
?>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Received</th>
                    <th>Name</th>
                    <th>Contact Info</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th style="width: 80px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td style="white-space: nowrap;"><?php echo date(DATE_FORMAT_YMD_HI, strtotime($row['created_at'])); ?></td>
                        <td><strong><?php echo h($row['name']); ?></strong></td>
                        <td>
                            <div style="font-weight: 500;"><a href="mailto:<?php echo h($row['email']); ?>" style="color: var(--primary); text-decoration: underline;"><?php echo h($row['email']); ?></a></div>
                            <?php if (!empty($row['phone'])): ?>
                                <div style="font-size: 12px; color: var(--on-surface-variant); margin-top: 4px; display: flex; align-items: center; gap: 4px;">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">call</span>
                                    <span><?php echo h($row['phone']); ?></span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo h($row['subject'] ?: '-'); ?></td>
                        <td style="max-width: 300px; word-wrap: break-word; font-size:13px; color: var(--on-surface-variant);">
                            <?php echo nl2br(h($row['message'])); ?>
                        </td>
                        <td style="text-align: center;">
                            <div class="action-buttons" style="justify-content: center;">
                                <a href="admin_dashboard.php?tab=contacts&action=delete_contact&id=<?php echo $row['id']; ?>" class="action-btn action-btn-danger js-delete-btn" data-message="Are you sure you want to delete this message?" title="Delete message">
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
    <p style="font-size: 14px; color: var(--on-surface-variant); text-align: center; padding: 40px 0;">No submissions found.</p>
<?php } ?>
