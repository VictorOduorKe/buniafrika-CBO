<?php
/**
 * Frequently Asked Questions (FAQs) Management Tab
 */
if (!defined('ACTION_ADD')) {
    exit('Direct access not permitted.');
}

if ($action === 'add' || ($action === 'edit' && $id > 0)) {
    $item = [
        'id' => 0,
        'question' => '',
        'answer' => '',
        'detailed_answer' => '',
        'page_slug' => 'programmes',
        'related_link_url' => '',
        'related_link_text' => '',
        'category' => 'General',
        'display_order' => 0,
        'published' => 1,
    ];

    if ($action === 'edit') {
        $found = getFaqById($conn, $id);
        if ($found) {
            $item = array_merge($item, $found);
        }
    }
?>
    <div class="panel-header">
        <h2 style="font-size: 20px; color: var(--primary);"><?php echo $action === 'edit' ? 'Edit FAQ' : 'Add New FAQ'; ?></h2>
        <a href="admin_dashboard.php?tab=faqs" class="btn btn-outline" style="padding: 6px 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
            <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>Back to FAQs
        </a>
    </div>

    <form method="POST" action="admin_dashboard.php?tab=faqs">
        <input type="hidden" name="csrf_token" value="<?php echo h(generateCsrfToken()); ?>">
        <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'faq_edit' : 'faq_add'; ?>">
        <input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>">

        <div class="form-grid" style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="faq-question">Question <span style="color:var(--error);">*</span></label>
                <input class="form-control" type="text" id="faq-question" name="question" value="<?php echo h($item['question']); ?>" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. How does BuniAfrika protect community cultural data?">
            </div>

            <div class="form-group" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="faq-category">Category</label>
                    <select class="form-control" id="faq-category" name="category" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
                        <?php
                        $faq_cats = ['General', 'Programmes', 'Civic Technology', 'Cultural Heritage', 'Partnerships', 'Volunteering', 'Donations'];
                        foreach ($faq_cats as $fc):
                        ?>
                            <option value="<?php echo h($fc); ?>" <?php echo $item['category'] === $fc ? 'selected' : ''; ?>><?php echo h($fc); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="faq-page-slug">Target Page</label>
                    <select class="form-control" id="faq-page-slug" name="page_slug" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
                        <?php
                        $pages = ['programmes' => 'Programmes & Thematic Areas', 'about' => 'About Us', 'volunteer' => 'Volunteer & Fellowships', 'contact' => 'Contact Us', 'all' => 'All Public Pages'];
                        foreach ($pages as $pVal => $pLabel): ?>
                            <option value="<?php echo h($pVal); ?>" <?php echo ($item['page_slug'] ?? 'programmes') === $pVal ? 'selected' : ''; ?>><?php echo h($pLabel); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="faq-order">Display Order</label>
                    <input class="form-control" type="number" id="faq-order" name="display_order" value="<?php echo (int)$item['display_order']; ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
                </div>
            </div>

            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="faq-answer">Direct Concise Answer (for AI Answers &amp; Snippets) <span style="color:var(--error);">*</span></label>
                <textarea class="form-control" id="faq-answer" name="answer" rows="3" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="1–2 sentences providing a direct, complete, factual answer..."><?php echo h($item['answer']); ?></textarea>
                <small style="color: var(--on-surface-variant); font-size: 11px;">AEO Best Practice: Answer directly in the first sentence without preamble or fluff.</small>
            </div>

            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="faq-detailed-answer">Detailed Supporting Explanation (Optional)</label>
                <textarea class="form-control" id="faq-detailed-answer" name="detailed_answer" rows="4" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="In-depth background, operational context, policy details, or bullet points..."><?php echo h($item['detailed_answer'] ?? ''); ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="faq-related-url">Related Citation URL (Optional)</label>
                    <input class="form-control" type="text" id="faq-related-url" name="related_link_url" value="<?php echo h($item['related_link_url'] ?? ''); ?>" placeholder="e.g. publications.php or https://..." style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
                </div>
                <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="faq-related-text">Citation Anchor Text (Optional)</label>
                    <input class="form-control" type="text" id="faq-related-text" name="related_link_text" value="<?php echo h($item['related_link_text'] ?? ''); ?>" placeholder="e.g. Read our Open Data Policy" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
                </div>
            </div>

            <div style="display:flex; align-items:center; gap:10px;">
                <input type="checkbox" id="faq-published" name="published" value="1" <?php echo !empty($item['published']) ? 'checked' : ''; ?> style="width: 18px; height: 18px; cursor: pointer;">
                <label for="faq-published" style="font-weight: 600; cursor: pointer; margin: 0;">Visible on website accordion &amp; Schema.org FAQPage</label>
            </div>

            <div style="display:flex; gap: 12px; margin-top: 10px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                    <?php echo $action === 'edit' ? 'Update FAQ' : 'Save FAQ'; ?>
                </button>
                <a href="admin_dashboard.php?tab=faqs" class="btn btn-outline" style="padding: 10px 20px;">Cancel</a>
            </div>
        </div>
    </form>
<?php
} else {
    $filter_cat = isset($_GET['category']) ? trim($_GET['category']) : '';
    $faqs = getFaqs($conn, false, $filter_cat ?: null);
    $all_categories = getFaqCategories($conn);
?>
    <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="font-size: 20px; color: var(--primary); margin: 0;">Frequently Asked Questions (FAQs)</h2>
            <p style="font-size: 13px; color: var(--on-surface-variant); margin: 4px 0 0 0;">Manage answers to questions displayed on Programmes, About, and Contact pages.</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <?php if (!empty($all_categories)): ?>
                <select onchange="window.location.href='admin_dashboard.php?tab=faqs' + (this.value ? '&category=' + encodeURIComponent(this.value) : '')" style="padding: 7px 12px; border-radius: var(--radius-md); border: 1px solid var(--outline-variant); font-size: 13px; background: var(--surface);">
                    <option value="">All Categories</option>
                    <?php foreach ($all_categories as $c): ?>
                        <option value="<?php echo h($c); ?>" <?php echo $filter_cat === $c ? 'selected' : ''; ?>><?php echo h($c); ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <a href="admin_dashboard.php?tab=faqs&action=add" class="btn btn-primary" style="padding: 8px 20px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                <span class="material-symbols-outlined" style="font-size:18px;">add</span>Add FAQ
            </a>
        </div>
    </div>

    <?php if (!empty($faqs)): ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">Order</th>
                        <th style="width: 120px;">Category</th>
                        <th>Question</th>
                        <th>Answer Preview</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 130px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($faqs as $row): ?>
                        <tr>
                            <td><span class="badge" style="background: var(--surface-variant); color: var(--on-surface);"><?php echo (int)$row['display_order']; ?></span></td>
                            <td><span class="badge badge-info"><?php echo h($row['category'] ?: 'General'); ?></span></td>
                            <td><strong><?php echo h($row['question']); ?></strong></td>
                            <td>
                                <p style="font-size: 13px; color: var(--on-surface-variant); margin: 0; max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?php echo h($row['answer']); ?>
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
                                    <a href="admin_dashboard.php?tab=faqs&action=faq_toggle_publish&id=<?php echo $row['id']; ?>" class="action-btn" title="<?php echo !empty($row['published']) ? 'Unpublish' : 'Publish'; ?>">
                                        <span class="material-symbols-outlined" style="font-size:18px; color: <?php echo !empty($row['published']) ? 'var(--secondary)' : 'var(--outline)'; ?>;">
                                            <?php echo !empty($row['published']) ? 'visibility' : 'visibility_off'; ?>
                                        </span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=faqs&action=edit&id=<?php echo $row['id']; ?>" class="action-btn" title="Edit FAQ">
                                        <span class="material-symbols-outlined" style="font-size:18px;">edit</span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=faqs&action=faq_delete&id=<?php echo $row['id']; ?>" class="action-btn action-btn-danger js-delete-btn" data-message="Are you sure you want to delete this FAQ?" title="Delete FAQ">
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
        <p style="font-size: 14px; color: var(--on-surface-variant); text-align: center; padding: 40px 0;">No FAQs found. Add common questions and answers for your community!</p>
    <?php endif; ?>
<?php } ?>

