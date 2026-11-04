<?php
/**
 * Publications and Reports Management Tab
 */
if (!defined('ACTION_ADD')) {
    exit('Direct access not permitted.');
}

if ($action === 'add' || ($action === 'edit' && $id > 0)) {
    $item = [
        'id' => 0,
        'title' => '',
        'slug' => '',
        'category' => 'Research Report',
        'summary' => '',
        'file_path' => '',
        'file_size' => '',
        'cover_image' => '',
        'author' => 'BuniAfrika Research Team',
        'published_date' => date('Y-m-d'),
        'download_count' => 0,
        'display_order' => 0,
        'published' => 1,
    ];

    if ($action === 'edit') {
        $found = getPublicationById($conn, $id);
        if ($found) {
            $item = $found;
        }
    }
?>
    <div class="panel-header">
        <h2 style="font-size: 20px; color: var(--primary);"><?php echo $action === 'edit' ? 'Edit Publication' : 'Add New Publication / Report'; ?></h2>
        <a href="admin_dashboard.php?tab=publications" class="btn btn-outline" style="padding: 6px 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
            <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>Back to Publications
        </a>
    </div>

    <form method="POST" action="admin_dashboard.php?tab=publications" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo h(generateCsrfToken()); ?>">
        <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'publication_edit' : 'publication_add'; ?>">
        <input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>">
        <input type="hidden" name="existing_file_path" value="<?php echo h($item['file_path'] ?? ($item['file_url'] ?? '')); ?>">
        <input type="hidden" name="existing_file_size" value="<?php echo h($item['file_size'] ?? ''); ?>">
        <input type="hidden" name="existing_cover_image" value="<?php echo h($item['cover_image'] ?? ($item['cover_image_url'] ?? '')); ?>">

        <div class="form-grid" style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="pub-title">Publication Title <span style="color:var(--error);">*</span></label>
                <input class="form-control" type="text" id="pub-title" name="title" value="<?php echo h($item['title']); ?>" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. Community Data Governance in Coastal Kenya: 2024 Baseline Report">
            </div>

            <div class="form-group" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="pub-category">Category</label>
                    <select class="form-control" id="pub-category" name="category" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
                        <?php
                        $cats = ['Research Report', 'Policy Brief', 'Whitepaper', 'Annual Report', 'Case Study', 'Tool & Framework'];
                        foreach ($cats as $cat):
                        ?>
                            <option value="<?php echo h($cat); ?>" <?php echo $item['category'] === $cat ? 'selected' : ''; ?>><?php echo h($cat); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="pub-author">Author / Contributors</label>
                    <input class="form-control" type="text" id="pub-author" name="author" value="<?php echo h($item['author']); ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. BuniAfrika Research Team">
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="pub-date">Publication Date</label>
                    <input class="form-control" type="date" id="pub-date" name="published_date" value="<?php echo h($item['published_date']); ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="pub-order">Display Order</label>
                    <input class="form-control" type="number" id="pub-order" name="display_order" value="<?php echo (int)$item['display_order']; ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
                </div>
            </div>

            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="pub-summary">Executive Summary / Abstract <span style="color:var(--error);">*</span></label>
                <textarea class="form-control" id="pub-summary" name="summary" rows="4" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="Summary of the publication findings, methodologies, and actionable recommendations..."><?php echo h($item['summary']); ?></textarea>
            </div>

            <div class="form-group" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="pub-file">
                        Document PDF File (.pdf) <?php echo empty($item['file_path']) ? '<span style="color:var(--error);">*</span>' : ''; ?>
                    </label>
                    <input class="form-control" type="file" id="pub-file" name="pdf_file" accept="application/pdf" <?php echo empty($item['file_path']) ? 'required' : ''; ?>>
                    <?php if (!empty($item['file_path'])): ?>
                        <div style="font-size: 12px; color: var(--on-surface-variant); margin-top: 6px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            <span class="material-symbols-outlined" style="font-size: 16px; color: var(--primary);">description</span>
                            <span>Current file: <strong><?php echo h(basename($item['file_path'])); ?></strong> (<?php echo h($item['file_size']); ?>)</span>
                            <span style="color: var(--on-surface-variant); font-size: 11px;">(Leave empty to keep existing file)</span>
                            <a href="../download-publication.php?id=<?php echo (int)$item['id']; ?>" target="_blank" style="color: var(--primary); text-decoration: underline; margin-left: auto;">Download test</a>
                        </div>
                    <?php endif; ?>
                </div>

                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="pub-cover">Cover Image Thumbnail (Optional)</label>
                    <input class="form-control" type="file" id="pub-cover" name="cover_image" accept="image/jpeg,image/png,image/webp">
                    <?php if (!empty($item['cover_image'])): ?>
                        <div style="margin-top: 6px;">
                            <img src="<?php echo h(getImageDisplayPath($item['cover_image'])); ?>" alt="Cover" style="max-height: 70px; border-radius: var(--radius-md); border: 1px solid var(--outline-variant);">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div style="display:flex; align-items:center; gap:10px;">
                <input type="checkbox" id="pub-published" name="published" value="1" <?php echo !empty($item['published']) ? 'checked' : ''; ?> style="width: 18px; height: 18px; cursor: pointer;">
                <label for="pub-published" style="font-weight: 600; cursor: pointer; margin: 0;">Publish to website &amp; enable public download</label>
            </div>

            <div style="display:flex; gap: 12px; margin-top: 10px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                    <?php echo $action === 'edit' ? 'Update Publication' : 'Save Publication'; ?>
                </button>
                <a href="admin_dashboard.php?tab=publications" class="btn btn-outline" style="padding: 10px 20px;">Cancel</a>
            </div>
        </div>
    </form>
<?php
} else {
    $publications = getPublications($conn, false);
?>
    <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="font-size: 20px; color: var(--primary); margin: 0;">Publications &amp; Reports</h2>
            <p style="font-size: 13px; color: var(--on-surface-variant); margin: 4px 0 0 0;">Manage downloadable research papers, policy briefs, and community reports.</p>
        </div>
        <a href="admin_dashboard.php?tab=publications&action=add" class="btn btn-primary" style="padding: 8px 20px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
            <span class="material-symbols-outlined" style="font-size:18px;">add</span>Upload Publication
        </a>
    </div>

    <?php if (!empty($publications)): ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">Cover</th>
                        <th>Title &amp; Author</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th style="width: 90px; text-align: center;">Downloads</th>
                        <th style="width: 90px;">Status</th>
                        <th style="width: 140px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($publications as $row): ?>
                        <tr>
                            <td>
                                <?php if (!empty($row['cover_image'])): ?>
                                    <img src="<?php echo h(getImageDisplayPath($row['cover_image'])); ?>" alt="Cover" style="width: 44px; height: 56px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--outline-variant);">
                                <?php else: ?>
                                    <div style="width: 44px; height: 56px; border-radius: var(--radius-sm); background: var(--surface-variant); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                                        <span class="material-symbols-outlined" style="font-size: 22px;">picture_as_pdf</span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo h($row['title']); ?></strong>
                                <div style="font-size: 12px; color: var(--on-surface-variant); margin-top: 2px;">
                                    <span><?php echo h($row['author']); ?></span>
                                    <?php if (!empty($row['file_size'])): ?>
                                        &bull; <span style="font-family: monospace;"><?php echo h($row['file_size']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td><span class="badge badge-info"><?php echo h($row['category']); ?></span></td>
                            <td><span style="font-size: 12px;"><?php echo h($row['published_date']); ?></span></td>
                            <td style="text-align: center;">
                                <span class="badge" style="background: var(--surface-variant); color: var(--on-surface); font-weight: 700;">
                                    <?php echo number_format((int)$row['download_count']); ?>
                                </span>
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
                                    <a href="../download-publication.php?id=<?php echo $row['id']; ?>" target="_blank" class="action-btn" title="Download / Test PDF" style="color: var(--primary);">
                                        <span class="material-symbols-outlined" style="font-size:18px;">download</span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=publications&action=publication_toggle_publish&id=<?php echo $row['id']; ?>" class="action-btn" title="<?php echo !empty($row['published']) ? 'Unpublish' : 'Publish'; ?>">
                                        <span class="material-symbols-outlined" style="font-size:18px; color: <?php echo !empty($row['published']) ? 'var(--secondary)' : 'var(--outline)'; ?>;">
                                            <?php echo !empty($row['published']) ? 'visibility' : 'visibility_off'; ?>
                                        </span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=publications&action=edit&id=<?php echo $row['id']; ?>" class="action-btn" title="Edit publication">
                                        <span class="material-symbols-outlined" style="font-size:18px;">edit</span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=publications&action=publication_delete&id=<?php echo $row['id']; ?>" class="action-btn action-btn-danger js-delete-btn" data-message="Are you sure you want to delete '<?php echo h(addslashes($row['title'])); ?>'? This will remove the file from the server." title="Delete publication">
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
        <p style="font-size: 14px; color: var(--on-surface-variant); text-align: center; padding: 40px 0;">No publications found. Upload your first research paper or report!</p>
    <?php endif; ?>
<?php } ?>

