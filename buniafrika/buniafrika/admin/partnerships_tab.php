<?php
/**
 * Partnerships Management Tab
 */
if (!defined('ACTION_ADD')) {
    exit('Direct access not permitted.');
}

if ($action === 'add' || ($action === 'edit' && $id > 0)) {
    $item = [
        'id' => 0,
        'name' => '',
        'logo_url' => '',
        'website_url' => '',
        'description' => '',
        'partner_type' => 'Institutional',
        'display_order' => 0,
        'published' => 1,
    ];

    if ($action === 'edit') {
        $found = getPartnerById($conn, $id);
        if ($found) {
            $item = $found;
        }
    }
?>
    <div class="panel-header">
        <h2 style="font-size: 20px; color: var(--primary);"><?php echo $action === 'edit' ? 'Edit Partner' : 'Add New Partner'; ?></h2>
        <a href="admin_dashboard.php?tab=partners" class="btn btn-outline" style="padding: 6px 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
            <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>Back to Partners
        </a>
    </div>

    <form method="POST" action="admin_dashboard.php?tab=partners" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo h(generateCsrfToken()); ?>">
        <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'partner_edit' : 'partner_add'; ?>">
        <input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>">

        <div class="form-grid" style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <div class="form-group" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="partner-name">Partner Organisation Name <span style="color:var(--error);">*</span></label>
                    <input class="form-control" type="text" id="partner-name" name="name" value="<?php echo h($item['name']); ?>" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. UNESCO Kenya Youth Network">
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="partner-type">Partnership Category</label>
                    <select class="form-control" id="partner-type" name="partner_type" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
                        <?php
                        $ptypes = ['Institutional', 'Technology & Research', 'Funding / Donor', 'Community & Civil Society', 'Academic'];
                        foreach ($ptypes as $pt):
                        ?>
                            <option value="<?php echo h($pt); ?>" <?php echo $item['partner_type'] === $pt ? 'selected' : ''; ?>><?php echo h($pt); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="partner-url">Official Website URL</label>
                    <input class="form-control" type="url" id="partner-url" name="website_url" value="<?php echo h($item['website_url']); ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="https://example.org">
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="partner-order">Display Order</label>
                    <input class="form-control" type="number" id="partner-order" name="display_order" value="<?php echo (int)$item['display_order']; ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
                </div>
            </div>

            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="partner-desc">Partnership Description / Scope</label>
                <textarea class="form-control" id="partner-desc" name="description" rows="3" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="Describe the joint initiative, MOU, or collaborative focus..."><?php echo h($item['description']); ?></textarea>
            </div>

            <div class="form-group" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; align-items: start;">
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="partner-logo">Partner Logo</label>
                    <input class="form-control" type="file" id="partner-logo" name="logo" accept="image/jpeg,image/png,image/webp,image/svg+xml">
                    <?php if (!empty($item['logo_url'])): ?>
                        <div style="margin-top: 8px; padding: 10px; background: var(--surface-variant); display: inline-block; border-radius: var(--radius-md);">
                            <img src="<?php echo h(getImageDisplayPath($item['logo_url'])); ?>" alt="Current logo" style="max-height: 48px; max-width: 140px; object-fit: contain;">
                        </div>
                    <?php endif; ?>
                </div>

                <div style="display:flex; align-items:center; gap:10px; padding-top: 24px;">
                    <input type="checkbox" id="partner-published" name="published" value="1" <?php echo !empty($item['published']) ? 'checked' : ''; ?> style="width: 18px; height: 18px; cursor: pointer;">
                    <label for="partner-published" style="font-weight: 600; cursor: pointer; margin: 0;">Display on website partner grid</label>
                </div>
            </div>

            <div style="display:flex; gap: 12px; margin-top: 10px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                    <?php echo $action === 'edit' ? 'Update Partner' : 'Save Partner'; ?>
                </button>
                <a href="admin_dashboard.php?tab=partners" class="btn btn-outline" style="padding: 10px 20px;">Cancel</a>
            </div>
        </div>
    </form>
<?php
} else {
    $partners = getPartners($conn, false);
?>
    <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="font-size: 20px; color: var(--primary); margin: 0;">Institutional &amp; Community Partnerships</h2>
            <p style="font-size: 13px; color: var(--on-surface-variant); margin: 4px 0 0 0;">Manage collaborating institutions, funding partners, and research allies.</p>
        </div>
        <a href="admin_dashboard.php?tab=partners&action=add" class="btn btn-primary" style="padding: 8px 20px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
            <span class="material-symbols-outlined" style="font-size:18px;">add</span>Add Partner
        </a>
    </div>

    <?php if (!empty($partners)): ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">Logo</th>
                        <th>Partner Name</th>
                        <th>Category</th>
                        <th>Website Link</th>
                        <th style="width: 60px;">Order</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 130px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($partners as $row): ?>
                        <tr>
                            <td>
                                <?php if (!empty($row['logo_url'])): ?>
                                    <div style="width: 50px; height: 40px; background: #ffffff; padding: 4px; border-radius: var(--radius-sm); border: 1px solid var(--outline-variant); display: flex; align-items: center; justify-content: center;">
                                        <img src="<?php echo h(getImageDisplayPath($row['logo_url'])); ?>" alt="Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    </div>
                                <?php else: ?>
                                    <div style="width: 50px; height: 40px; border-radius: var(--radius-sm); background: var(--surface-variant); display: flex; align-items: center; justify-content: center; color: var(--on-surface-variant);">
                                        <span class="material-symbols-outlined" style="font-size: 20px;">handshake</span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo h($row['name']); ?></strong>
                                <?php if (!empty($row['description'])): ?>
                                    <p style="font-size: 12px; color: var(--on-surface-variant); margin: 2px 0 0 0; max-width: 280px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <?php echo h($row['description']); ?>
                                    </p>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge badge-info"><?php echo h($row['partner_type']); ?></span></td>
                            <td>
                                <?php if (!empty($row['website_url'])): ?>
                                    <a href="<?php echo h($row['website_url']); ?>" target="_blank" rel="noopener noreferrer" style="font-size: 13px; color: var(--primary); text-decoration: underline; display: inline-flex; align-items: center; gap: 4px;">
                                        <span>Visit Link</span>
                                        <span class="material-symbols-outlined" style="font-size: 14px;">open_in_new</span>
                                    </a>
                                <?php else: ?>
                                    <span style="font-size: 12px; color: var(--on-surface-variant);">&mdash;</span>
                                <?php endif; ?>
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
                                    <a href="admin_dashboard.php?tab=partners&action=partner_toggle_publish&id=<?php echo $row['id']; ?>" class="action-btn" title="<?php echo !empty($row['published']) ? 'Unpublish' : 'Publish'; ?>">
                                        <span class="material-symbols-outlined" style="font-size:18px; color: <?php echo !empty($row['published']) ? 'var(--secondary)' : 'var(--outline)'; ?>;">
                                            <?php echo !empty($row['published']) ? 'visibility' : 'visibility_off'; ?>
                                        </span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=partners&action=edit&id=<?php echo $row['id']; ?>" class="action-btn" title="Edit partner">
                                        <span class="material-symbols-outlined" style="font-size:18px;">edit</span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=partners&action=partner_delete&id=<?php echo $row['id']; ?>" class="action-btn action-btn-danger js-delete-btn" data-message="Are you sure you want to delete '<?php echo h(addslashes($row['name'])); ?>'?" title="Delete partner">
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
        <p style="font-size: 14px; color: var(--on-surface-variant); text-align: center; padding: 40px 0;">No partners found. Add your first collaborating organization or partner!</p>
    <?php endif; ?>
<?php } ?>

