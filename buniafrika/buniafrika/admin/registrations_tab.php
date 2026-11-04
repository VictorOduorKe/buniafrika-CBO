<?php
if (!defined('LOC_PROJECTS')) {
    exit('Direct access not permitted.');
}
?>
<h2 style="font-size: 20px; margin-bottom: var(--spacing-md); color: var(--primary);">Join Us Registrations</h2>
<?php
$result = $conn->query("SELECT * FROM registrations ORDER BY created_at DESC");
if ($result && $result->num_rows > 0) {
?>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Message / Note</th>
                    <th style="width: 80px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td style="white-space: nowrap;"><?php echo date(DATE_FORMAT_YMD_HI, strtotime($row['created_at'])); ?></td>
                        <td><strong><?php echo h($row['first_name'] . ' ' . $row['last_name']); ?></strong></td>
                        <td>
                            <?php
                            $role_badge = 'badge-warning';
                            if ($row['role'] === 'volunteer') {
                                $role_badge = 'badge-success';
                            }
                            ?>
                            <span class="badge <?php echo $role_badge; ?>"><?php echo h(ucfirst($row['role'])); ?></span>
                        </td>
                        <td><a href="mailto:<?php echo h($row['email']); ?>" style="color: var(--primary); text-decoration: underline;"><?php echo h($row['email']); ?></a></td>
                        <td>
                            <?php if (!empty($row['phone'])): ?>
                                <div style="display: flex; align-items: center; gap: 4px; white-space: nowrap;">
                                    <span class="material-symbols-outlined" style="font-size: 14px; color: var(--on-surface-variant);">call</span>
                                    <span><?php echo h($row['phone']); ?></span>
                                </div>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td style="max-width: 250px; word-wrap: break-word; font-size:13px; color: var(--on-surface-variant);">
                            <?php echo nl2br(h($row['message'])); ?>
                        </td>
                        <td style="text-align: center;">
                            <div class="action-buttons" style="justify-content: center;">
                                <a href="admin_dashboard.php?tab=registrations&action=delete_registration&id=<?php echo $row['id']; ?>" class="action-btn action-btn-danger js-delete-btn" data-message="Are you sure you want to delete this registration?" title="Delete record">
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
    <p style="font-size: 14px; color: var(--on-surface-variant); text-align: center; padding: 40px 0;">No registrations found.</p>
<?php } ?>
