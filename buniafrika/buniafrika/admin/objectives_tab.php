<?php
/**
 * Organisational Objectives Management Tab
 */
if (!defined('ACTION_ADD')) {
    exit('Direct access not permitted.');
}

if ($action === 'add' || ($action === 'edit' && $id > 0)) {
    $item = [
        'id' => 0,
        'title' => '',
        'category' => 'General',
        'description' => '',
        'display_order' => 0,
        'published' => 1,
    ];

    if ($action === 'edit') {
        $found = getObjectiveById($conn, $id);
        if ($found) {
            $item = $found;
        }
    }
?>
    <div class="panel-header">
        <h2 style="font-size: 20px; color: var(--primary);"><?php echo $action === 'edit' ? 'Edit Objective' : 'Add New Organisational Objective'; ?></h2>
        <a href="admin_dashboard.php?tab=objectives" class="btn btn-outline" style="padding: 6px 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
            <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>Back to Objectives
        </a>
    </div>

    <form method="POST" action="admin_dashboard.php?tab=objectives">
        <input type="hidden" name="csrf_token" value="<?php echo h(generateCsrfToken()); ?>">
        <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'objective_edit' : 'objective_add'; ?>">
        <input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>">

        <div class="form-grid" style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="obj-title">Objective Statement / Title <span style="color:var(--error);">*</span></label>
                <input class="form-control" type="text" id="obj-title" name="title" value="<?php echo h($item['title']); ?>" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. Expand digital literacy and open-data tooling across community organizations">
            </div>

            <div class="form-group" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="obj-category">Category / Pillar</label>
                    <input class="form-control" type="text" id="obj-category" name="category" value="<?php echo h($item['category'] ?: 'General'); ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. Civic Technology, Heritage, Governance">
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="obj-order">Display Order</label>
                    <input class="form-control" type="number" id="obj-order" name="display_order" value="<?php echo (int)$item['display_order']; ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
                </div>
            </div>

            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="obj-desc">Detailed Description / Context</label>
                <textarea class="form-control" id="obj-desc" name="description" rows="4" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="Explain how the organization works toward this objective..."><?php echo h($item['description']); ?></textarea>
            </div>

            <div style="display:flex; align-items:center; gap:10px;">
                <input type="checkbox" id="obj-published" name="published" value="1" <?php echo !empty($item['published']) ? 'checked' : ''; ?> style="width: 18px; height: 18px; cursor: pointer;">
                <label for="obj-published" style="font-weight: 600; cursor: pointer; margin: 0;">Visible on public website</label>
            </div>

            <div style="display:flex; gap: 12px; margin-top: 10px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                    <?php echo $action === 'edit' ? 'Update Objective' : 'Save Objective'; ?>
                </button>
                <a href="admin_dashboard.php?tab=objectives" class="btn btn-outline" style="padding: 10px 20px;">Cancel</a>
            </div>
        </div>
    </form>
<?php
} else {
    $objectives = getObjectives($conn, false);
?>
    <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="font-size: 20px; color: var(--primary); margin: 0;">Organisational Objectives</h2>
            <p style="font-size: 13px; color: var(--on-surface-variant); margin: 4px 0 0 0;">Manage the core strategic and operational objectives shown on the About and Mission &amp; Vision pages.</p>
        </div>
        <a href="admin_dashboard.php?tab=objectives&action=add" class="btn btn-primary" style="padding: 8px 20px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
            <span class="material-symbols-outlined" style="font-size:18px;">add</span>Add Objective
        </a>
    </div>

    <?php if (!empty($objectives)): ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">Order</th>
                        <th style="width: 120px;">Category</th>
                        <th>Objective Statement</th>
                        <th>Description</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 130px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($objectives as $row): ?>
                        <tr>
                            <td><span class="badge" style="background: var(--surface-variant); color: var(--on-surface);"><?php echo (int)$row['display_order']; ?></span></td>
                            <td><span class="badge badge-info"><?php echo h($row['category'] ?: 'General'); ?></span></td>
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
                                    <a href="admin_dashboard.php?tab=objectives&action=objective_toggle_publish&id=<?php echo $row['id']; ?>" class="action-btn" title="<?php echo !empty($row['published']) ? 'Unpublish' : 'Publish'; ?>">
                                        <span class="material-symbols-outlined" style="font-size:18px; color: <?php echo !empty($row['published']) ? 'var(--secondary)' : 'var(--outline)'; ?>;">
                                            <?php echo !empty($row['published']) ? 'visibility' : 'visibility_off'; ?>
                                        </span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=objectives&action=edit&id=<?php echo $row['id']; ?>" class="action-btn" title="Edit objective">
                                        <span class="material-symbols-outlined" style="font-size:18px;">edit</span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=objectives&action=objective_delete&id=<?php echo $row['id']; ?>" class="action-btn action-btn-danger js-delete-btn" data-message="Are you sure you want to delete this objective?" title="Delete objective">
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
        <p style="font-size: 14px; color: var(--on-surface-variant); text-align: center; padding: 40px 0;">No objectives found. Add your first objective to display it on the website!</p>
    <?php endif; ?>
<?php } ?>

