<?php
/**
 * Thematic Areas Management Tab
 */
if (!defined('ACTION_ADD')) {
    exit('Direct access not permitted.');
}

if ($action === 'add' || ($action === 'edit' && $id > 0)) {
    $item = [
        'id' => 0,
        'title' => '',
        'slug' => '',
        'intro' => '',
        'points' => '',
        'icon' => 'account_balance',
        'image_url' => '',
        'display_order' => 0,
        'published' => 1,
    ];

    if ($action === 'edit') {
        $found = getThematicAreaById($conn, $id);
        if ($found) {
            $item = $found;
        }
    }
?>
    <div class="panel-header">
        <h2 style="font-size: 20px; color: var(--primary);"><?php echo $action === 'edit' ? 'Edit Thematic Area' : 'Add New Thematic Area'; ?></h2>
        <a href="admin_dashboard.php?tab=thematic" class="btn btn-outline" style="padding: 6px 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
            <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>Back to Thematic Areas
        </a>
    </div>

    <form method="POST" action="admin_dashboard.php?tab=thematic" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo h(generateCsrfToken()); ?>">
        <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'thematic_edit' : 'thematic_add'; ?>">
        <input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>">

        <div class="form-grid" style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="thematic-title">Thematic Area Title <span style="color:var(--error);">*</span></label>
                <input class="form-control" type="text" id="thematic-title" name="title" value="<?php echo h($item['title']); ?>" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. Civic Technology & Digital Accountability">
            </div>

            <div class="form-group" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="thematic-slug">URL Slug (leave blank to auto-generate)</label>
                    <input class="form-control" type="text" id="thematic-slug" name="slug" value="<?php echo h($item['slug']); ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. civic-technology">
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="thematic-icon">Material Icon Name</label>
                    <input class="form-control" type="text" id="thematic-icon" name="icon" value="<?php echo h($item['icon'] ?: 'account_balance'); ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. account_balance, museum, hub, public">
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="thematic-order">Display Order</label>
                    <input class="form-control" type="number" id="thematic-order" name="display_order" value="<?php echo (int)$item['display_order']; ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
                </div>
            </div>

            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="thematic-intro">Overview / Summary <span style="color:var(--error);">*</span></label>
                <textarea class="form-control" id="thematic-intro" name="intro" rows="3" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="Concise overview of this thematic area..."><?php echo h($item['intro']); ?></textarea>
            </div>

            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="thematic-points">Key Focus Points / Initiatives (one per line)</label>
                <textarea class="form-control" id="thematic-points" name="points" rows="5" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="Point 1&#10;Point 2&#10;Point 3"><?php echo h($item['points']); ?></textarea>
            </div>

            <div class="form-group" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; align-items: start;">
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="thematic-image">Optional Cover Image / Banner</label>
                    <input class="form-control" type="file" id="thematic-image" name="image" accept="image/jpeg,image/png,image/webp">
                    <?php if (!empty($item['image_url'])): ?>
                        <div style="margin-top: 8px;">
                            <img src="<?php echo h(getImageDisplayPath($item['image_url'])); ?>" alt="Current image" style="max-height: 80px; border-radius: var(--radius-md); border: 1px solid var(--outline-variant);">
                            <p style="font-size: 11px; color: var(--on-surface-variant); margin-top: 4px;">Leave file empty to retain existing image.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div style="display:flex; align-items:center; gap:10px; padding-top: 24px;">
                    <input type="checkbox" id="thematic-published" name="published" value="1" <?php echo !empty($item['published']) ? 'checked' : ''; ?> style="width: 18px; height: 18px; cursor: pointer;">
                    <label for="thematic-published" style="font-weight: 600; cursor: pointer; margin: 0;">Publish to website immediately</label>
                </div>
            </div>

            <div style="display:flex; gap: 12px; margin-top: 10px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                    <?php echo $action === 'edit' ? 'Update Thematic Area' : 'Save Thematic Area'; ?>
                </button>
                <a href="admin_dashboard.php?tab=thematic" class="btn btn-outline" style="padding: 10px 20px;">Cancel</a>
            </div>
        </div>
    </form>
<?php
} else {
    $thematic_areas = getThematicAreas($conn, false);
?>
    <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="font-size: 20px; color: var(--primary); margin: 0;">Thematic Areas</h2>
            <p style="font-size: 13px; color: var(--on-surface-variant); margin: 4px 0 0 0;">Manage public programme and research thematic areas displayed across the website.</p>
        </div>
        <a href="admin_dashboard.php?tab=thematic&action=add" class="btn btn-primary" style="padding: 8px 20px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
            <span class="material-symbols-outlined" style="font-size:18px;">add</span>Add Thematic Area
        </a>
    </div>

    <?php if (!empty($thematic_areas)): ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">Order</th>
                        <th style="width: 70px;">Icon</th>
                        <th>Title &amp; Slug</th>
                        <th>Summary</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 130px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($thematic_areas as $row): ?>
                        <tr>
                            <td><span class="badge" style="background: var(--surface-variant); color: var(--on-surface);"><?php echo (int)$row['display_order']; ?></span></td>
                            <td>
                                <?php if (!empty($row['image_url'])): ?>
                                    <img src="<?php echo h(getImageDisplayPath($row['image_url'])); ?>" alt="Icon" style="width: 38px; height: 38px; object-fit: cover; border-radius: var(--radius-md);">
                                <?php else: ?>
                                    <div style="width: 38px; height: 38px; border-radius: var(--radius-md); background: var(--secondary-container); color: var(--on-secondary-container); display: flex; align-items: center; justify-content: center;">
                                        <span class="material-symbols-outlined" style="font-size: 20px;"><?php echo h($row['icon'] ?: 'account_balance'); ?></span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo h($row['title']); ?></strong>
                                <div style="font-size: 11px; color: var(--on-surface-variant); font-family: monospace;">/<?php echo h($row['slug']); ?></div>
                            </td>
                            <td>
                                <p style="font-size: 13px; color: var(--on-surface-variant); margin: 0; max-width: 350px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?php echo h($row['intro']); ?>
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
                                    <a href="admin_dashboard.php?tab=thematic&action=thematic_toggle_publish&id=<?php echo $row['id']; ?>" class="action-btn" title="<?php echo !empty($row['published']) ? 'Unpublish' : 'Publish'; ?>">
                                        <span class="material-symbols-outlined" style="font-size:18px; color: <?php echo !empty($row['published']) ? 'var(--secondary)' : 'var(--outline)'; ?>;">
                                            <?php echo !empty($row['published']) ? 'visibility' : 'visibility_off'; ?>
                                        </span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=thematic&action=edit&id=<?php echo $row['id']; ?>" class="action-btn" title="Edit area">
                                        <span class="material-symbols-outlined" style="font-size:18px;">edit</span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=thematic&action=thematic_delete&id=<?php echo $row['id']; ?>" class="action-btn action-btn-danger js-delete-btn" data-message="Are you sure you want to delete '<?php echo h(addslashes($row['title'])); ?>'? This will remove it from the website." title="Delete area">
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
        <p style="font-size: 14px; color: var(--on-surface-variant); text-align: center; padding: 40px 0;">No thematic areas found. Add your first thematic area to display it on the website!</p>
    <?php endif; ?>
<?php } ?>

