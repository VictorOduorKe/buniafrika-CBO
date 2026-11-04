<?php
if (!defined('LOC_PROJECTS')) {
    exit('Direct access not permitted.');
}

if ($action === 'add' || ($action === 'edit' && $id > 0)) {
    $item = ['title'=>'', 'category'=>'education', 'caption'=>'', 'image_url'=>'', 'video_url'=>''];
    if ($action === 'edit') {
        $stmt = $conn->prepare("SELECT g.*, c.name AS category FROM gallery_items g LEFT JOIN categories c ON g.category_id = c.id WHERE g.id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows === 1) {
            $item = $res->fetch_assoc();
        }
        $stmt->close();
    }
?>
    <div class="panel-header">
        <h2 style="font-size: 20px; color: var(--primary);"><?php echo $action === 'edit' ? 'Edit Gallery Item' : 'Add Gallery Item'; ?></h2>
        <a href="admin_dashboard.php?tab=gallery" class="btn btn-outline" style="padding: 6px 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
            <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>Back to List
        </a>
    </div>

    <form method="POST" action="admin_dashboard.php?tab=gallery<?php echo $action === 'edit' ? ACTION_EDIT_ID.$id : ''; ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'gallery_edit' : 'gallery_add'; ?>">

        <div class="form-grid">
            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="gallery-title">Title</label>
                <input class="form-control" type="text" id="gallery-title" name="title" value="<?php echo h($item['title']); ?>" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. Classroom Study">
            </div>

            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="gallery-category">Category</label>
<?php $gal_cats = getDistinctCategories($conn, 'gallery_items'); ?>
                <select class="form-control" id="gallery-category" name="category" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" onchange="document.getElementById('gal-cat-custom').style.display=this.value==='__other__'?'block':'none'">
                    <?php if (empty($gal_cats)): ?>
                        <option value="education"   <?php echo $item['category']==='education'?'selected':''; ?>><?php echo h(ucfirst('education')); ?></option>
                        <option value="health"      <?php echo $item['category']==='health'?'selected':''; ?>><?php echo h(ucfirst('health')); ?></option>
                        <option value="community"   <?php echo $item['category']==='community'?'selected':''; ?>><?php echo h(ucfirst('community')); ?></option>
                        <option value="events"      <?php echo $item['category']==='events'?'selected':''; ?>><?php echo h(ucfirst('events')); ?></option>
                        <option value="environment" <?php echo $item['category']==='environment'?'selected':''; ?>><?php echo h(ucfirst('environment')); ?></option>
                        <option value="empowerment" <?php echo $item['category']==='empowerment'?'selected':''; ?>><?php echo h(ucfirst('empowerment')); ?></option>
                    <?php else: ?>
                        <?php foreach ($gal_cats as $cat): ?>
                            <option value="<?php echo h($cat); ?>" <?php echo $item['category']===$cat?'selected':''; ?>><?php echo h(ucfirst($cat)); ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <option value="__other__">+ Add new category…</option>
                </select>
                <input type="text" id="gal-cat-custom" name="category_custom" placeholder="Type new category name" style="display:none; margin-top:6px; padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
            </div>

            <div class="form-group form-col-full" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="caption">Caption</label>
                <textarea class="form-control" id="caption" name="caption" rows="4" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; font-family:var(--font-body); background:var(--background); color:var(--on-background); resize:vertical;" placeholder="Write a detailed descriptive caption for accessibility..."><?php echo h($item['caption']); ?></textarea>
            </div>

            <div class="form-group form-col-full" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label">Media Type</label>
                <div class="view-toggle-container" style="align-self: flex-start; margin-bottom: 8px;">
                    <input type="hidden" name="media_type" id="media_type_input" value="<?php echo !empty($item['video_url']) ? 'video' : 'image'; ?>">
                    <button type="button" class="view-toggle-btn <?php echo empty($item['video_url']) ? 'active' : ''; ?>" id="toggle-media-image" onclick="setMediaType('image')">Image</button>
                    <button type="button" class="view-toggle-btn <?php echo !empty($item['video_url']) ? 'active' : ''; ?>" id="toggle-media-video" onclick="setMediaType('video')">Video</button>
                </div>
            </div>

            <!-- Image Upload Fields -->
            <div id="media-fields-image" class="form-group form-col-full" style="display: <?php echo empty($item['video_url']) ? 'flex' : 'none'; ?>; flex-direction:column; gap:6px;">
                <label class="form-label" for="gallery-image-url">Image URL</label>
                <input class="form-control" type="text" id="gallery-image-url" name="image_url" value="<?php echo h($item['image_url']); ?>" placeholder="Enter image URL" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background); margin-bottom:8px;">
                <div style="font-size: 12px; font-weight:700; text-align:center; color: var(--on-surface-variant)">OR UPLOAD NEW FILE</div>
                <input type="file" name="image_file" accept="image/*" style="margin-top:4px;">
            </div>

            <!-- Video Upload Fields -->
            <div id="media-fields-video" class="form-group form-col-full" style="display: <?php echo !empty($item['video_url']) ? 'flex' : 'none'; ?>; flex-direction:column; gap:6px;">
                <label class="form-label" for="gallery-video-url">Video URL</label>
                <input class="form-control" type="text" id="gallery-video-url" name="video_url" value="<?php echo h($item['video_url']); ?>" placeholder="Enter video URL" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background); margin-bottom:8px;">
                <div style="font-size: 12px; font-weight:700; text-align:center; color: var(--on-surface-variant)">OR UPLOAD NEW FILE</div>
                <input type="file" name="video_file" accept="video/*" style="margin-top:4px;">
            </div>
        </div>

        <?php if (!empty($item['image_url'])) { ?>
            <div class="image-preview-container img-preview-container" style="display: <?php echo empty($item['video_url']) ? 'block' : 'none'; ?>">
                <span style="font-size:11px; font-weight:700; color:var(--on-surface-variant)">Current Active Image Preview</span>
                <img class="image-preview-img" src="<?php echo h(getImageDisplayPath($item['image_url'])); ?>" alt="Gallery Preview">
            </div>
        <?php } ?>

        <?php if (!empty($item['video_url'])) { ?>
            <div class="image-preview-container video-preview-container" style="display: <?php echo !empty($item['video_url']) ? 'block' : 'none'; ?>">
                <span style="font-size:11px; font-weight:700; color:var(--on-surface-variant)">Current Active Video Preview</span>
                <video class="image-preview-img" src="<?php echo h(getImageDisplayPath($item['video_url'])); ?>" controls style="max-height: 250px; display: block; border-radius: var(--radius-lg); border: 1px solid var(--outline-variant); background:#000; margin-top:8px;"></video>
            </div>
        <?php } ?>

        <script>
        function setMediaType(type) {
            document.getElementById('media_type_input').value = type;
            const imgPreview = document.querySelector('.img-preview-container');
            const vidPreview = document.querySelector('.video-preview-container');
            if (type === 'image') {
                document.getElementById('toggle-media-image').classList.add('active');
                document.getElementById('toggle-media-video').classList.remove('active');
                document.getElementById('media-fields-image').style.display = 'flex';
                document.getElementById('media-fields-video').style.display = 'none';
                if (imgPreview) imgPreview.style.display = 'block';
                if (vidPreview) vidPreview.style.display = 'none';
            } else {
                document.getElementById('toggle-media-image').classList.remove('active');
                document.getElementById('toggle-media-video').classList.add('active');
                document.getElementById('media-fields-image').style.display = 'none';
                document.getElementById('media-fields-video').style.display = 'flex';
                if (imgPreview) imgPreview.style.display = 'none';
                if (vidPreview) vidPreview.style.display = 'block';
            }
        }
        </script>

        <button type="submit" class="btn btn-primary" style="margin-top: 24px; padding:12px 28px; width:100%; max-width:300px;">Save Gallery Item</button>
    </form>

<?php
} else {
?>
    <div class="panel-header">
        <h2 style="font-size: 20px; color: var(--primary);">Gallery Catalog</h2>
        <a href="admin_dashboard.php?tab=gallery&action=add" class="btn btn-primary" style="padding: 8px 20px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
            <span class="material-symbols-outlined" style="font-size:16px;">add</span>Add Gallery Item
        </a>
    </div>

    <?php
    $result = $conn->query("SELECT g.*, c.name AS category FROM gallery_items g LEFT JOIN categories c ON g.category_id = c.id ORDER BY CASE WHEN LOWER(c.name) = 'education' THEN 0 ELSE 1 END ASC, g.created_at DESC");
    if ($result && $result->num_rows > 0) {
    ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 100px;">Photo</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Caption Preview</th>
                        <th>Visibility</th>
                        <th style="width: 120px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td>
                                <?php if (!empty($row['video_url'])): ?>
                                    <div style="position: relative; width: 80px; height: 50px; border-radius: var(--radius-default); border: 1px solid var(--outline-variant); background: #000; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                        <video src="<?php echo h(getImageDisplayPath($row['video_url'])); ?>" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.8;"></video>
                                        <span class="material-symbols-outlined" style="position: absolute; color: #fff; font-size: 24px; pointer-events: none; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">play_circle</span>
                                    </div>
                                <?php else: ?>
                                    <img src="<?php echo h(getImageDisplayPath($row['image_url'])); ?>" alt="img" style="width: 80px; height: 50px; object-fit: cover; border-radius: var(--radius-default); border: 1px solid var(--outline-variant);">
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo h($row['title']); ?></strong></td>
                            <td><span class="badge badge-success"><?php echo h(ucfirst($row['category'])); ?></span></td>
                            <td style="max-width: 350px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size:13px; color:var(--on-surface-variant);"><?php echo h($row['caption']); ?></td>
                            <td>
                                <?php if ($row['published'] == 1): ?>
                                    <span class="badge badge-success">Published</span>
                                <?php else: ?>
                                    <span class="badge badge-danger" style="background-color: var(--outline); color: #ffffff;">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <div class="action-buttons" style="justify-content: center;">
                                    <?php if ($row['published'] == 1): ?>
                                        <a href="admin_dashboard.php?tab=gallery&action=toggle_gallery_publish&id=<?php echo $row['id']; ?>" class="action-btn" title="Unpublish item" style="color: var(--secondary);">
                                            <span class="material-symbols-outlined" style="font-size:16px;">visibility</span>
                                        </a>
                                    <?php else: ?>
                                        <a href="admin_dashboard.php?tab=gallery&action=toggle_gallery_publish&id=<?php echo $row['id']; ?>" class="action-btn" title="Publish item" style="color: var(--outline);">
                                            <span class="material-symbols-outlined" style="font-size:16px;">visibility_off</span>
                                        </a>
                                    <?php endif; ?>
                                    <a href="admin_dashboard.php?tab=gallery&action=edit&id=<?php echo $row['id']; ?>" class="action-btn" title="Edit item">
                                        <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=gallery&action=delete_gallery&id=<?php echo $row['id']; ?>" class="action-btn action-btn-danger js-delete-btn" data-message="Are you sure you want to delete this gallery item?" title="Delete item">
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
        <p style="font-size: 14px; color: var(--on-surface-variant); text-align: center; padding: 40px 0;">No gallery items found. Let's upload something!</p>
    <?php } ?>
<?php } ?>
