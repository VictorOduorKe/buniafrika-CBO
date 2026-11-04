<?php
/**
 * Core Values Management Tab
 */
if (!defined('ACTION_ADD')) {
    exit('Direct access not permitted.');
}

if ($action === 'add' || ($action === 'edit' && $id > 0)) {
    $item = [
        'id' => 0,
        'title' => '',
        'description' => '',
        'icon' => 'verified',
        'display_order' => 0,
        'published' => 1,
    ];

    if ($action === 'edit') {
        $found = getCoreValueById($conn, $id);
        if ($found) {
            $item = $found;
        }
    }
?>
    <div class="panel-header">
        <h2 style="font-size: 20px; color: var(--primary);"><?php echo $action === 'edit' ? 'Edit Core Value' : 'Add New Core Value'; ?></h2>
        <a href="admin_dashboard.php?tab=values" class="btn btn-outline" style="padding: 6px 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
            <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>Back to Core Values
        </a>
    </div>

    <form method="POST" action="admin_dashboard.php?tab=values">
        <input type="hidden" name="csrf_token" value="<?php echo h(generateCsrfToken()); ?>">
        <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'value_edit' : 'value_add'; ?>">
        <input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>">

        <div class="form-grid" style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <div class="form-group" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="val-title">Value Title <span style="color:var(--error);">*</span></label>
                    <input class="form-control" type="text" id="val-title" name="title" value="<?php echo h($item['title']); ?>" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. Integrity and Public Accountability">
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="val-icon">Material Icon Name</label>
                    <input class="form-control" type="text" id="val-icon" name="icon" value="<?php echo h($item['icon'] ?: 'verified'); ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. verified, diversity_3, balance, lightbulb, handshake">
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="val-order">Display Order</label>
                    <input class="form-control" type="number" id="val-order" name="display_order" value="<?php echo (int)$item['display_order']; ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
                </div>
            </div>

            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="val-desc">Description / Institutional Meaning <span style="color:var(--error);">*</span></label>
                <textarea class="form-control" id="val-desc" name="description" rows="4" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="Explain how this value directs the team's conduct and community partnerships..."><?php echo h($item['description']); ?></textarea>
            </div>

            <div style="display:flex; align-items:center; gap:10px;">
                <input type="checkbox" id="val-published" name="published" value="1" <?php echo !empty($item['published']) ? 'checked' : ''; ?> style="width: 18px; height: 18px; cursor: pointer;">
                <label for="val-published" style="font-weight: 600; cursor: pointer; margin: 0;">Visible on website About &amp; Purpose sections</label>
            </div>

            <div style="display:flex; gap: 12px; margin-top: 10px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                    <?php echo $action === 'edit' ? 'Update Value' : 'Save Value'; ?>
                </button>
                <a href="admin_dashboard.php?tab=values" class="btn btn-outline" style="padding: 10px 20px;">Cancel</a>
            </div>
        </div>
    </form>
<?php
} else {
    $values = getCoreValues($conn, false);
?>
    <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="font-size: 20px; color: var(--primary); margin: 0;">Core Organisational Values</h2>
            <p style="font-size: 13px; color: var(--on-surface-variant); margin: 4px 0 0 0;">Define the ethical principles and operational standards that anchor BuniAfrika.</p>
        </div>
        <a href="admin_dashboard.php?tab=values&action=add" class="btn btn-primary" style="padding: 8px 20px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
            <span class="material-symbols-outlined" style="font-size:18px;">add</span>Add Value
        </a>
    </div>

    <?php if (!empty($values)): ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">Order</th>
                        <th style="width: 60px;">Icon</th>
                        <th>Value Title</th>
                        <th>Description</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 130px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($values as $row): ?>
                        <tr>
                            <td><span class="badge" style="background: var(--surface-variant); color: var(--on-surface);"><?php echo (int)$row['display_order']; ?></span></td>
                            <td>
                                <div style="width: 38px; height: 38px; border-radius: var(--radius-md); background: var(--secondary-container); color: var(--on-secondary-container); display: flex; align-items: center; justify-content: center;">
                                    <span class="material-symbols-outlined" style="font-size: 20px;"><?php echo h($row['icon'] ?: 'verified'); ?></span>
                                </div>
                            </td>
                            <td><strong><?php echo h($row['title']); ?></strong></td>
                            <td>
                                <p style="font-size: 13px; color: var(--on-surface-variant); margin: 0; max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?php echo h($row['description']); ?>
                                </p>
                            </td>
                            <td>
                                <?php if (!empty($row['published'])): ?>
                                    <span class="badge badge-success">Published</span>
                                <?php else: ?>
                                    <span class="badge badge-danger" style="background-color: var(--outline); color: #ffffff;">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <div class="action-buttons" style="justify-content: center;">
                                    <a href="admin_dashboard.php?tab=values&action=value_toggle_publish&id=<?php echo $row['id']; ?>" class="action-btn" title="<?php echo !empty($row['published']) ? 'Unpublish' : 'Publish'; ?>">
                                        <span class="material-symbols-outlined" style="font-size:18px; color: <?php echo !empty($row['published']) ? 'var(--secondary)' : 'var(--outline)'; ?>;">
                                            <?php echo !empty($row['published']) ? 'visibility' : 'visibility_off'; ?>
                                        </span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=values&action=edit&id=<?php echo $row['id']; ?>" class="action-btn" title="Edit value">
                                        <span class="material-symbols-outlined" style="font-size:18px;">edit</span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=values&action=value_delete&id=<?php echo $row['id']; ?>" class="action-btn action-btn-danger js-delete-btn" data-message="Are you sure you want to delete '<?php echo h(addslashes($row['title'])); ?>'?" title="Delete value">
                                        <span class="material-symbols-outlined" style="font-size:18px;">delete</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="font-size: 14px; color: var(--on-surface-variant); text-align: center; padding: 40px 0;">No core values defined yet. Add the ethical principles that guide your work!</p>
    <?php endif; ?>
<?php } ?>

