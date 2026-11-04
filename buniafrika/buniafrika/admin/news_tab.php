<?php
if (!defined('LOC_PROJECTS')) {
    exit('Direct access not permitted.');
}
if ($action === 'add' || ($action === 'edit' && $id > 0)) {
    $article = ['title'=>'', 'category'=>'Project Update', 'summary'=>'', 'content'=>'', 'published_date'=>date('Y-m-d'), 'author'=>'BuniAfrika CBO', 'image_url'=>'', 'video_url'=>''];
    if ($action === 'edit') {
        $stmt = $conn->prepare("SELECT n.*, c.name AS category FROM news_articles n LEFT JOIN categories c ON n.category_id = c.id WHERE n.id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows === 1) {
            $article = $res->fetch_assoc();
        }
        $stmt->close();
    }
?>
    <div class="panel-header">
        <h2 style="font-size: 20px; color: var(--primary);"><?php echo $action === 'edit' ? 'Edit News Article' : 'Write News Article'; ?></h2>
        <a href="admin_dashboard.php?tab=news" class="btn btn-outline" style="padding: 6px 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
            <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>Back to List
        </a>
    </div>

    <form method="POST" action="admin_dashboard.php?tab=news<?php echo $action === 'edit' ? ACTION_EDIT_ID.$id : ''; ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'news_edit' : 'news_add'; ?>">

        <div class="form-grid">
            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="news-title">Article Title</label>
                <input class="form-control" type="text" id="news-title" name="title" value="<?php echo h($article['title']); ?>" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. New Water System Operational">
            </div>

            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="news-category">Category</label>
<?php $news_cats = getDistinctCategories($conn, 'news_articles'); ?>
                <select class="form-control" id="news-category" name="category" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" onchange="document.getElementById('news-cat-custom').style.display=this.value==='__other__'?'block':'none'">
                    <?php if (empty($news_cats)): ?>
                        <option value="Project Update" <?php echo $article['category']==='Project Update'?'selected':''; ?>>Project Update</option>
                        <option value="Press Release" <?php echo $article['category']==='Press Release'?'selected':''; ?>>Press Release</option>
                        <option value="EDUCATION" <?php echo $article['category']==='EDUCATION'?'selected':''; ?>>Education</option>
                        <option value="HEALTHCARE" <?php echo $article['category']==='HEALTHCARE'?'selected':''; ?>>Healthcare</option>
                        <option value="AGRICULTURE" <?php echo $article['category']==='AGRICULTURE'?'selected':''; ?>>Agriculture</option>
                    <?php else: ?>
                        <?php foreach ($news_cats as $cat): ?>
                            <option value="<?php echo h($cat); ?>" <?php echo $article['category']===$cat?'selected':''; ?>><?php echo h(ucfirst($cat)); ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <option value="__other__">+ Add new category…</option>
                </select>
                <input type="text" id="news-cat-custom" name="category_custom" placeholder="Type new category name" style="display:none; margin-top:6px; padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
            </div>

            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="published_date">Publish Date</label>
                <input class="form-control" type="date" id="published_date" name="published_date" value="<?php echo h($article['published_date']); ?>" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
            </div>

            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="author">Author</label>
                <input class="form-control" type="text" id="author" name="author" value="<?php echo h($article['author']); ?>" placeholder="BuniAfrika CBO" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
            </div>

            <div class="form-group form-col-full" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="summary">Short Summary</label>
                <input class="form-control" type="text" id="summary" name="summary" value="<?php echo h($article['summary']); ?>" required placeholder="A brief one-sentence hook/summary of the news item..." style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
            </div>

            <div class="form-group form-col-full" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="content">Full Article Content</label>
                <textarea id="content" name="content" style="display:none;"><?php echo h($article['content']); ?></textarea>
                <div id="news-content-editor" style="min-height:240px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); background:var(--background); color:var(--on-background);"></div>
            </div>

            <div class="form-group form-col-full" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="news-image-url">Image Resource URL</label>
                <input class="form-control" type="text" id="news-image-url" name="image_url" value="<?php echo h($article['image_url']); ?>" placeholder="Enter image URL" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background); margin-bottom:8px;">
                <div style="font-size: 12px; font-weight:700; text-align:center; color: var(--on-surface-variant);">OR UPLOAD NEW IMAGE FILE</div>
                <input type="file" name="image_file" accept="image/*" style="margin-top:4px;">
            </div>

            <div class="form-group form-col-full" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="news-video-url">Video Resource URL (Optional)</label>
                <input class="form-control" type="text" id="news-video-url" name="video_url" value="<?php echo h($article['video_url'] ?? ''); ?>" placeholder="Enter external video URL or path" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background); margin-bottom:8px;">
                <div style="font-size: 12px; font-weight:700; text-align:center; color: var(--on-surface-variant);">OR UPLOAD NEW VIDEO FILE</div>
                <input type="file" name="video_file" accept="video/*" style="margin-top:4px;">
            </div>

            <?php
            $featured_media_arr = [];
            if (!empty($article['featured_media'])) {
                $featured_media_arr = json_decode($article['featured_media'], true);
                if (!is_array($featured_media_arr)) {
                    $featured_media_arr = [];
                }
            }
            $has_featured_media = !empty($featured_media_arr);
            ?>
            <div class="form-group form-col-full" style="display:flex; flex-direction:column; gap:10px; margin-top:16px; border: 1px dashed var(--outline-variant); padding: 16px; border-radius: var(--radius-lg); background: var(--surface-container-low);">
                <div style="display:flex; align-items:center; justify-content:space-between;">
                    <label class="form-label" style="font-weight:700; margin:0; display:flex; align-items:center; gap:8px;">
                        <span class="material-symbols-outlined" style="color:var(--primary); vertical-align:middle;">photo_library</span>
                        Featured Media Gallery
                    </label>
                    <label class="switch" style="position: relative; display: inline-block; width: 48px; height: 24px;">
                        <input type="checkbox" id="toggle-featured-media" name="enable_featured_media" value="1" <?php echo $has_featured_media ? 'checked' : ''; ?> style="opacity: 0; width: 0; height: 0;" onchange="toggleFeaturedMediaSection()">
                        <span class="slider round"></span>
                    </label>
                </div>
                
                <div id="featured-media-section" style="<?php echo $has_featured_media ? 'display:block;' : 'display:none;'; ?> margin-top: 10px;">
                    <div style="font-size: 13px; color: var(--on-surface-variant); margin-bottom: 12px;">
                        Upload up to 5 featured images (max 12MB) or videos (max 40MB) each.
                    </div>
                    
                    <?php if (!empty($featured_media_arr)): ?>
                        <div style="font-weight:700; font-size:12px; text-transform:uppercase; color:var(--on-surface-variant); margin-bottom:8px;">Currently Saved Featured Media:</div>
                        <div style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:16px;" id="current-featured-list">
                            <?php foreach ($featured_media_arr as $idx => $media_path): ?>
                                <div class="featured-media-thumb-container" style="position:relative; width:100px; height:80px; border-radius:var(--radius-default); border:1px solid var(--outline-variant); overflow:hidden; background:#000;" data-path="<?php echo h($media_path); ?>">
                                    <?php if (strpos(strtolower($media_path), '.mp4') !== false || strpos(strtolower($media_path), '.webm') !== false || strpos(strtolower($media_path), '.ogg') !== false || strpos(strtolower($media_path), '.mov') !== false): ?>
                                        <video src="<?php echo h(getImageDisplayPath($media_path)); ?>" style="width:100%; height:100%; object-fit:cover;"></video>
                                    <?php else: ?>
                                        <img src="<?php echo h(getImageDisplayPath($media_path)); ?>" style="width:100%; height:100%; object-fit:cover;" alt="Featured Media">
                                    <?php endif; ?>
                                    <button type="button" onclick="removeExistingFeaturedMedia(this)" style="position:absolute; top:2px; right:2px; background:rgba(255,0,0,0.8); color:#fff; border:none; border-radius:50%; width:20px; height:20px; font-size:12px; cursor:pointer; display:flex; align-items:center; justify-content:center; padding:0; line-height:1;">×</button>
                                    <input type="hidden" name="existing_featured_media[]" value="<?php echo h($media_path); ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <input type="file" name="featured_media_files[]" id="featured-media-files" multiple accept="image/*,video/*" style="margin-top:8px;" onchange="validateFeaturedMediaFiles(this)">
                    <div id="featured-media-error" style="color:var(--error); font-size:12px; margin-top:6px; display:none; font-weight:700;"></div>
                    
                    <div id="new-featured-previews" style="display:flex; gap:12px; flex-wrap:wrap; margin-top:12px;"></div>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 16px;">
            <?php if (!empty($article['image_url'])) { ?>
                <div class="image-preview-container" style="flex: 1; min-width: 250px;">
                    <span style="font-size:11px; font-weight:700; color:var(--on-surface-variant)">Current Active Image Preview</span>
                    <img class="image-preview-img" src="<?php echo h(getImageDisplayPath($article['image_url'])); ?>" alt="News Preview">
                </div>
            <?php } ?>

            <?php if (!empty($article['video_url'])) { ?>
                <div class="video-preview-container" style="flex: 1; min-width: 250px;">
                    <span style="font-size:11px; font-weight:700; color:var(--on-surface-variant); display:block; margin-bottom:4px;">Current Active Video Preview</span>
                    <video src="<?php echo h(getImageDisplayPath($article['video_url'])); ?>" controls style="max-width: 100%; max-height: 150px; display: block; border-radius: var(--radius-lg); border: 1px solid var(--outline-variant);"></video>
                </div>
            <?php } ?>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 24px; padding:12px 28px; width:100%; max-width:300px;">Publish Article</button>
    </form>

<?php
} else {
?>
    <div class="panel-header">
        <h2 style="font-size: 20px; color: var(--primary);">News Publications</h2>
        <a href="admin_dashboard.php?tab=news&action=add" class="btn btn-primary" style="padding: 8px 20px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
            <span class="material-symbols-outlined" style="font-size:16px;">add</span>Write News
        </a>
    </div>

    <?php
    $result = $conn->query("SELECT n.*, c.name AS category FROM news_articles n LEFT JOIN categories c ON n.category_id = c.id ORDER BY n.published_date DESC, n.created_at DESC");
    if ($result && $result->num_rows > 0) {
    ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 100px;">Feature Image</th>
                        <th>Article Title</th>
                        <th>Category</th>
                        <th>Publish Date</th>
                        <th>Author</th>
                        <th>Visibility</th>
                        <th style="width: 120px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td>
                                <img src="<?php echo h(getImageDisplayPath($row['image_url'])); ?>" alt="img" style="width: 80px; height: 50px; object-fit: cover; border-radius: var(--radius-default); border: 1px solid var(--outline-variant);">
                            </td>
                            <td><strong><?php echo h($row['title']); ?></strong></td>
                            <td><span class="badge badge-warning"><?php echo h(ucfirst($row['category'])); ?></span></td>
                            <td style="white-space: nowrap;"><?php echo h($row['published_date']); ?></td>
                            <td><?php echo h($row['author']); ?></td>
                            <td>
                                <?php if ($row['published'] == 1): ?>
                                    <span class="badge badge-success">Published</span>
                                <?php else: ?>
                                    <span class="badge badge-warning" style="background-color: var(--tertiary-container); color: var(--on-tertiary-container);">Pending Approval</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <div class="action-buttons" style="justify-content: center;">
                                    <?php if ($row['published'] == 1): ?>
                                        <a href="admin_dashboard.php?tab=news&action=toggle_news_publish&id=<?php echo $row['id']; ?>" class="action-btn" title="Unpublish article" style="color: var(--secondary);">
                                            <span class="material-symbols-outlined" style="font-size:16px;">visibility</span>
                                        </a>
                                    <?php else: ?>
                                        <a href="admin_dashboard.php?tab=news&action=toggle_news_publish&id=<?php echo $row['id']; ?>" class="action-btn" title="Approve & Publish article" style="color: var(--on-tertiary-container);">
                                            <span class="material-symbols-outlined" style="font-size:16px;">publish</span>
                                        </a>
                                    <?php endif; ?>
                                    <a href="admin_dashboard.php?tab=news&action=edit&id=<?php echo $row['id']; ?>" class="action-btn" title="Edit article">
                                        <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=news&action=delete_news&id=<?php echo $row['id']; ?>" class="action-btn action-btn-danger js-delete-btn" data-message="Are you sure you want to delete this news article?" title="Delete article">
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
        <p style="font-size: 14px; color: var(--on-surface-variant); text-align: center; padding: 40px 0;">No news articles published yet.</p>
    <?php } ?>
<?php } ?>
