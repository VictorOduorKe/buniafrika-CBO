<?php
/**
 * Our Story / Milestones Management Tab
 */
if (!defined('ACTION_ADD')) {
    exit('Direct access not permitted.');
}

if ($action === 'add' || ($action === 'edit' && $id > 0)) {
    $item = [
        'id' => 0,
        'year' => date('Y'),
        'milestone_date' => date('Y-m-d'),
        'title' => '',
        'description' => '',
        'image_url' => '',
        'display_order' => 0,
        'published' => 1,
    ];

    if ($action === 'edit') {
        $found = getStoryMilestoneById($conn, $id);
        if ($found) {
            $item = $found;
        }
    }
?>
    <div class="panel-header">
        <h2 style="font-size: 20px; color: var(--primary);"><?php echo $action === 'edit' ? 'Edit Milestone' : 'Add New Story Milestone'; ?></h2>
        <a href="admin_dashboard.php?tab=story" class="btn btn-outline" style="padding: 6px 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
            <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>Back to Story Milestones
        </a>
    </div>

    <form method="POST" action="admin_dashboard.php?tab=story" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo h(generateCsrfToken()); ?>">
        <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'story_edit' : 'story_add'; ?>">
        <input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>">

        <div class="form-grid" style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="story-title">Milestone Title <span style="color:var(--error);">*</span></label>
                <input class="form-control" type="text" id="story-title" name="title" value="<?php echo h($item['title']); ?>" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. Inception and Community Registration">
            </div>

            <div class="form-group" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="story-year">Year / Timeframe <span style="color:var(--error);">*</span></label>
                    <input class="form-control" type="text" id="story-year" name="year" value="<?php echo h($item['year']); ?>" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. 2023 or 2023 - 2024">
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="story-date">Exact Milestone Date (Optional)</label>
                    <input class="form-control" type="date" id="story-date" name="milestone_date" value="<?php echo h($item['milestone_date']); ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="story-order">Display Order</label>
                    <input class="form-control" type="number" id="story-order" name="display_order" value="<?php echo (int)$item['display_order']; ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
                </div>
            </div>

            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="story-desc">Milestone Description <span style="color:var(--error);">*</span></label>
                <textarea class="form-control" id="story-desc" name="description" rows="4" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="Narrative describing this organizational journey milestone..."><?php echo h($item['description']); ?></textarea>
            </div>

            <div class="form-group" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; align-items: start;">
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="story-image">Milestone Archival Photo (Optional)</label>
                    <input class="form-control" type="file" id="story-image" name="image" accept="image/jpeg,image/png,image/webp">
                    <?php if (!empty($item['image_url'])): ?>
                        <div style="margin-top: 8px;">
                            <img src="<?php echo h(getImageDisplayPath($item['image_url'])); ?>" alt="Current milestone" style="max-height: 80px; border-radius: var(--radius-md); border: 1px solid var(--outline-variant);">
                        </div>
                    <?php endif; ?>
                </div>

                <div style="display:flex; align-items:center; gap:10px; padding-top: 24px;">
                    <input type="checkbox" id="story-published" name="published" value="1" <?php echo !empty($item['published']) ? 'checked' : ''; ?> style="width: 18px; height: 18px; cursor: pointer;">
                    <label for="story-published" style="font-weight: 600; cursor: pointer; margin: 0;">Visible on timeline</label>
                </div>
            </div>

            <div style="display:flex; gap: 12px; margin-top: 10px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                    <?php echo $action === 'edit' ? 'Update Milestone' : 'Save Milestone'; ?>
                </button>
                <a href="admin_dashboard.php?tab=story" class="btn btn-outline" style="padding: 10px 20px;">Cancel</a>
            </div>
        </div>
    </form>
<?php
} else {
    $milestones = getOurStory($conn, false);
?>
    <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="font-size: 20px; color: var(--primary); margin: 0;">Our Story &amp; Journey Milestones</h2>
            <p style="font-size: 13px; color: var(--on-surface-variant); margin: 4px 0 0 0;">Manage the chronological history, foundational milestones, and impact journey of BuniAfrika.</p>
        </div>
        <a href="admin_dashboard.php?tab=story&action=add" class="btn btn-primary" style="padding: 8px 20px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
            <span class="material-symbols-outlined" style="font-size:18px;">add</span>Add Milestone
        </a>
    </div>

    <?php if (!empty($milestones)): ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Year</th>
                        <th style="width: 80px;">Photo</th>
                        <th>Milestone Title</th>
                        <th>Narrative Summary</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 130px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($milestones as $row): ?>
                        <tr>
                            <td><span class="badge" style="background: var(--primary); color: #ffffff; font-weight: 700;"><?php echo h($row['year']); ?></span></td>
                            <td>
                                <?php if (!empty($row['image_url'])): ?>
                                    <img src="<?php echo h(getImageDisplayPath($row['image_url'])); ?>" alt="Photo" style="width: 50px; height: 40px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--outline-variant);">
                                <?php else: ?>
                                    <div style="width: 50px; height: 40px; border-radius: var(--radius-sm); background: var(--surface-variant); display: flex; align-items: center; justify-content: center; color: var(--on-surface-variant);">
                                        <span class="material-symbols-outlined" style="font-size: 18px;">flag</span>
                                    </div>
                                <?php endif; ?>
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
                                    <a href="admin_dashboard.php?tab=story&action=story_toggle_publish&id=<?php echo $row['id']; ?>" class="action-btn" title="<?php echo !empty($row['published']) ? 'Unpublish' : 'Publish'; ?>">
                                        <span class="material-symbols-outlined" style="font-size:18px; color: <?php echo !empty($row['published']) ? 'var(--secondary)' : 'var(--outline)'; ?>;">
                                            <?php echo !empty($row['published']) ? 'visibility' : 'visibility_off'; ?>
                                        </span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=story&action=edit&id=<?php echo $row['id']; ?>" class="action-btn" title="Edit milestone">
                                        <span class="material-symbols-outlined" style="font-size:18px;">edit</span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=story&action=story_delete&id=<?php echo $row['id']; ?>" class="action-btn action-btn-danger js-delete-btn" data-message="Are you sure you want to delete '<?php echo h(addslashes($row['title'])); ?>'?" title="Delete milestone">
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
        <p style="font-size: 14px; color: var(--on-surface-variant); text-align: center; padding: 40px 0;">No journey milestones found. Add milestones to narrate your founding story!</p>
    <?php endif; ?>
<?php } ?>

