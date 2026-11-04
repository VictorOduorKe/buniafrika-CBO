<?php
/**
 * Testimonials Management Tab
 */
if (!defined('ACTION_ADD')) {
    exit('Direct access not permitted.');
}

if ($action === 'add' || ($action === 'edit' && $id > 0)) {
    $item = [
        'id' => 0,
        'author_name' => '',
        'author_role' => '',
        'author_organization' => '',
        'author_image' => '',
        'quote' => '',
        'display_order' => 0,
        'published' => 1,
    ];

    if ($action === 'edit') {
        $found = getTestimonialById($conn, $id);
        if ($found) {
            $item = $found;
        }
    }
?>
    <div class="panel-header">
        <h2 style="font-size: 20px; color: var(--primary);"><?php echo $action === 'edit' ? 'Edit Testimonial' : 'Add New Testimonial'; ?></h2>
        <a href="admin_dashboard.php?tab=testimonials" class="btn btn-outline" style="padding: 6px 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
            <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>Back to Testimonials
        </a>
    </div>

    <form method="POST" action="admin_dashboard.php?tab=testimonials" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo h(generateCsrfToken()); ?>">
        <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'testimonial_edit' : 'testimonial_add'; ?>">
        <input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>">

        <div class="form-grid" style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <div class="form-group" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="test-author">Author Full Name <span style="color:var(--error);">*</span></label>
                    <input class="form-control" type="text" id="test-author" name="author_name" value="<?php echo h($item['author_name']); ?>" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. Amina Hassan">
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="test-role">Role / Designation</label>
                    <input class="form-control" type="text" id="test-role" name="author_role" value="<?php echo h($item['author_role']); ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. Grassroots Organizer">
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="test-org">Organisation / Community Affiliation</label>
                    <input class="form-control" type="text" id="test-org" name="author_organization" value="<?php echo h($item['author_organization']); ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. Mombasa Youth Action">
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="test-order">Display Order</label>
                    <input class="form-control" type="number" id="test-order" name="display_order" value="<?php echo (int)$item['display_order']; ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
                </div>
            </div>

            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="test-quote">Quote / Testimonial Statement <span style="color:var(--error);">*</span></label>
                <textarea class="form-control" id="test-quote" name="quote" rows="4" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="Enter the testimonial or feedback statement..."><?php echo h($item['quote']); ?></textarea>
            </div>

            <div class="form-group" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; align-items: start;">
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="test-image">Author Portrait Photo (Optional)</label>
                    <input class="form-control" type="file" id="test-image" name="image" accept="image/jpeg,image/png,image/webp">
                    <?php if (!empty($item['author_image'])): ?>
                        <div style="margin-top: 8px;">
                            <img src="<?php echo h(getImageDisplayPath($item['author_image'])); ?>" alt="Author portrait" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%; border: 2px solid var(--outline-variant);">
                        </div>
                    <?php endif; ?>
                </div>

                <div style="display:flex; align-items:center; gap:10px; padding-top: 24px;">
                    <input type="checkbox" id="test-published" name="published" value="1" <?php echo !empty($item['published']) ? 'checked' : ''; ?> style="width: 18px; height: 18px; cursor: pointer;">
                    <label for="test-published" style="font-weight: 600; cursor: pointer; margin: 0;">Visible on website</label>
                </div>
            </div>

            <div style="display:flex; gap: 12px; margin-top: 10px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                    <?php echo $action === 'edit' ? 'Update Testimonial' : 'Save Testimonial'; ?>
                </button>
                <a href="admin_dashboard.php?tab=testimonials" class="btn btn-outline" style="padding: 10px 20px;">Cancel</a>
            </div>
        </div>
    </form>
<?php
} else {
    $testimonials = getTestimonials($conn, false);
?>
    <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="font-size: 20px; color: var(--primary); margin: 0;">Community Testimonials</h2>
            <p style="font-size: 13px; color: var(--on-surface-variant); margin: 4px 0 0 0;">Manage quotes and testimonials from community partners, participants, and fellows.</p>
        </div>
        <a href="admin_dashboard.php?tab=testimonials&action=add" class="btn btn-primary" style="padding: 8px 20px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
            <span class="material-symbols-outlined" style="font-size:18px;">add</span>Add Testimonial
        </a>
    </div>

    <?php if (!empty($testimonials)): ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">Portrait</th>
                        <th>Author &amp; Affiliation</th>
                        <th>Quote</th>
                        <th style="width: 60px;">Order</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 130px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($testimonials as $row): ?>
                        <tr>
                            <td>
                                <?php if (!empty($row['author_image'])): ?>
                                    <img src="<?php echo h(getImageDisplayPath($row['author_image'])); ?>" alt="Avatar" style="width: 44px; height: 44px; object-fit: cover; border-radius: 50%; border: 1px solid var(--outline-variant);">
                                <?php else: ?>
                                    <div style="width: 44px; height: 44px; border-radius: 50%; background: var(--secondary-container); color: var(--on-secondary-container); display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                        <?php echo h(strtoupper(substr($row['author_name'], 0, 1))); ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo h($row['author_name']); ?></strong>
                                <div style="font-size: 12px; color: var(--on-surface-variant);">
                                    <?php echo h($row['author_role']); ?>
                                    <?php if (!empty($row['author_organization'])): ?>
                                        &bull; <?php echo h($row['author_organization']); ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <p style="font-size: 13px; font-style: italic; color: var(--on-surface-variant); margin: 0; max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    &ldquo;<?php echo h($row['quote']); ?>&rdquo;
                                </p>
                            </td>
                            <td><span class="badge" style="background: var(--surface-variant); color: var(--on-surface);"><?php echo (int)$row['display_order']; ?></span></td>
                            <td>
                                <?php if (!empty($row['published'])): ?>
                                    <span class="badge badge-success">Published</span>
                                <?php else: ?>
                                    <span class="badge badge-danger" style="background-color: var(--outline); color: #ffffff;">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <div class="action-buttons" style="justify-content: center;">
                                    <a href="admin_dashboard.php?tab=testimonials&action=testimonial_toggle_publish&id=<?php echo $row['id']; ?>" class="action-btn" title="<?php echo !empty($row['published']) ? 'Unpublish' : 'Publish'; ?>">
                                        <span class="material-symbols-outlined" style="font-size:18px; color: <?php echo !empty($row['published']) ? 'var(--secondary)' : 'var(--outline)'; ?>;">
                                            <?php echo !empty($row['published']) ? 'visibility' : 'visibility_off'; ?>
                                        </span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=testimonials&action=edit&id=<?php echo $row['id']; ?>" class="action-btn" title="Edit testimonial">
                                        <span class="material-symbols-outlined" style="font-size:18px;">edit</span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=testimonials&action=testimonial_delete&id=<?php echo $row['id']; ?>" class="action-btn action-btn-danger js-delete-btn" data-message="Are you sure you want to delete this testimonial from '<?php echo h(addslashes($row['author_name'])); ?>'?" title="Delete testimonial">
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
        <p style="font-size: 14px; color: var(--on-surface-variant); text-align: center; padding: 40px 0;">No testimonials found. Add feedback or quotes from community partners!</p>
    <?php endif; ?>
<?php } ?>

