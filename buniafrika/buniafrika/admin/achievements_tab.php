<?php
/**
 * Achievements and Impact Metrics Management Tab
 */
if (!defined('ACTION_ADD')) {
    exit('Direct access not permitted.');
}

if ($action === 'add' || ($action === 'edit' && $id > 0)) {
    $item = [
        'id' => 0,
        'metric_value' => '',
        'label' => '',
        'unit' => '',
        'reporting_period' => date('Y'),
        'description' => '',
        'display_order' => 0,
        'published' => 1,
    ];

    if ($action === 'edit') {
        $found = getAchievementById($conn, $id);
        if ($found) {
            $item = $found;
        }
    }
?>
    <div class="panel-header">
        <h2 style="font-size: 20px; color: var(--primary);"><?php echo $action === 'edit' ? 'Edit Impact Metric' : 'Add New Impact Metric'; ?></h2>
        <a href="admin_dashboard.php?tab=achievements" class="btn btn-outline" style="padding: 6px 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
            <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>Back to Metrics
        </a>
    </div>

    <div style="background: var(--surface-variant); padding: 12px 16px; border-radius: var(--radius-md); border-left: 4px solid var(--secondary); margin-bottom: 20px;">
        <p style="margin: 0; font-size: 13px; color: var(--on-surface-variant);">
            <strong>Data Integrity Standard:</strong> Do not use fabricated numbers. All metrics displayed publicly must reflect verified baseline findings, active participant counts, or documented community milestones.
        </p>
    </div>

    <form method="POST" action="admin_dashboard.php?tab=achievements">
        <input type="hidden" name="csrf_token" value="<?php echo h(generateCsrfToken()); ?>">
        <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'achievement_edit' : 'achievement_add'; ?>">
        <input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>">

        <div class="form-grid" style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <div class="form-group" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="ach-val">Numerical Value / Figure <span style="color:var(--error);">*</span></label>
                    <input class="form-control" type="text" id="ach-val" name="metric_value" value="<?php echo h($item['metric_value']); ?>" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background); font-size: 18px; font-weight: 700;" placeholder="e.g. 500+ or 15 or 98%">
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="ach-unit">Unit / Suffix</label>
                    <input class="form-control" type="text" id="ach-unit" name="unit" value="<?php echo h($item['unit']); ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. Participants, Counties, Reports">
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="ach-period">Reporting Period</label>
                    <input class="form-control" type="text" id="ach-period" name="reporting_period" value="<?php echo h($item['reporting_period']); ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. 2023 - 2024">
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="ach-order">Display Order</label>
                    <input class="form-control" type="number" id="ach-order" name="display_order" value="<?php echo (int)$item['display_order']; ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
                </div>
            </div>

            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="ach-label">Metric Label / Title <span style="color:var(--error);">*</span></label>
                <input class="form-control" type="text" id="ach-label" name="label" value="<?php echo h($item['label']); ?>" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. Community Researchers and Youth Trained">
            </div>

            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="ach-desc">Context &amp; Measurement Description</label>
                <textarea class="form-control" id="ach-desc" name="description" rows="3" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="Brief background describing how this impact is calculated or verified..."><?php echo h($item['description']); ?></textarea>
            </div>

            <div style="display:flex; align-items:center; gap:10px;">
                <input type="checkbox" id="ach-published" name="published" value="1" <?php echo !empty($item['published']) ? 'checked' : ''; ?> style="width: 18px; height: 18px; cursor: pointer;">
                <label for="ach-published" style="font-weight: 600; cursor: pointer; margin: 0;">Publish to homepage and public impact counters</label>
            </div>

            <div style="display:flex; gap: 12px; margin-top: 10px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                    <?php echo $action === 'edit' ? 'Update Metric' : 'Save Metric'; ?>
                </button>
                <a href="admin_dashboard.php?tab=achievements" class="btn btn-outline" style="padding: 10px 20px;">Cancel</a>
            </div>
        </div>
    </form>
<?php
} else {
    $achievements = getAchievements($conn, false);
?>
    <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="font-size: 20px; color: var(--primary); margin: 0;">Achievements &amp; Impact Metrics</h2>
            <p style="font-size: 13px; color: var(--on-surface-variant); margin: 4px 0 0 0;">Manage verifiable organizational statistics and impact indicators displayed on the homepage and about page.</p>
        </div>
        <a href="admin_dashboard.php?tab=achievements&action=add" class="btn btn-primary" style="padding: 8px 20px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
            <span class="material-symbols-outlined" style="font-size:18px;">add</span>Add Metric
        </a>
    </div>

    <?php if (!empty($achievements)): ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">Order</th>
                        <th style="width: 130px;">Figure</th>
                        <th>Impact Indicator Label</th>
                        <th>Period</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 130px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($achievements as $row): ?>
                        <tr>
                            <td><span class="badge" style="background: var(--surface-variant); color: var(--on-surface);"><?php echo (int)$row['display_order']; ?></span></td>
                            <td>
                                <span style="font-size: 18px; font-weight: 800; color: var(--primary);">
                                    <?php echo h($row['metric_value']); ?>
                                </span>
                                <?php if (!empty($row['unit'])): ?>
                                    <span style="font-size: 12px; color: var(--on-surface-variant); font-weight: 600;"><?php echo h($row['unit']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo h($row['label']); ?></strong>
                                <?php if (!empty($row['description'])): ?>
                                    <p style="font-size: 12px; color: var(--on-surface-variant); margin: 2px 0 0 0; max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <?php echo h($row['description']); ?>
                                    </p>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge badge-info"><?php echo h($row['reporting_period'] ?: 'Active'); ?></span></td>
                            <td>
                                <?php if (!empty($row['published'])): ?>
                                    <span class="badge badge-success">Published</span>
                                <?php else: ?>
                                    <span class="badge badge-danger" style="background-color: var(--outline); color: #ffffff;">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <div class="action-buttons" style="justify-content: center;">
                                    <a href="admin_dashboard.php?tab=achievements&action=achievement_toggle_publish&id=<?php echo $row['id']; ?>" class="action-btn" title="<?php echo !empty($row['published']) ? 'Unpublish' : 'Publish'; ?>">
                                        <span class="material-symbols-outlined" style="font-size:18px; color: <?php echo !empty($row['published']) ? 'var(--secondary)' : 'var(--outline)'; ?>;">
                                            <?php echo !empty($row['published']) ? 'visibility' : 'visibility_off'; ?>
                                        </span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=achievements&action=edit&id=<?php echo $row['id']; ?>" class="action-btn" title="Edit metric">
                                        <span class="material-symbols-outlined" style="font-size:18px;">edit</span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=achievements&action=achievement_delete&id=<?php echo $row['id']; ?>" class="action-btn action-btn-danger js-delete-btn" data-message="Are you sure you want to delete '<?php echo h(addslashes($row['label'])); ?>'?" title="Delete metric">
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
        <p style="font-size: 14px; color: var(--on-surface-variant); text-align: center; padding: 40px 0;">No impact metrics registered yet. Add verifiable community indicators!</p>
    <?php endif; ?>
<?php } ?>

