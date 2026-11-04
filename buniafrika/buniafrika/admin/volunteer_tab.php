<?php
/**
 * Volunteer Management Tab
 * Includes Opportunities Management and Applications Review
 */
if (!defined('ACTION_ADD')) {
    exit('Direct access not permitted.');
}

$subview = isset($_GET['subview']) && $_GET['subview'] === 'applications' ? 'applications' : 'opportunities';

if ($subview === 'opportunities') {
    if ($action === 'add' || ($action === 'edit' && $id > 0)) {
        $item = [
            'id' => 0,
            'title' => '',
            'slug' => '',
            'department' => 'Civic Technology',
            'description' => '',
            'requirements' => '',
            'location' => 'Mombasa / Remote',
            'commitment_type' => 'Flexible',
            'slots' => 2,
            'deadline' => date('Y-m-d', strtotime('+30 days')),
            'published' => 1,
        ];

        if ($action === 'edit') {
            $found = getVolunteerOpportunityById($conn, $id);
            if ($found) {
                $item = $found;
            }
        }
?>
        <div class="panel-header">
            <h2 style="font-size: 20px; color: var(--primary);"><?php echo $action === 'edit' ? 'Edit Volunteer Opportunity' : 'Post New Volunteer Opportunity'; ?></h2>
            <a href="admin_dashboard.php?tab=volunteers&subview=opportunities" class="btn btn-outline" style="padding: 6px 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>Back to Opportunities
            </a>
        </div>

        <form method="POST" action="admin_dashboard.php?tab=volunteers&subview=opportunities">
            <input type="hidden" name="csrf_token" value="<?php echo h(generateCsrfToken()); ?>">
            <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'volunteer_opp_edit' : 'volunteer_opp_add'; ?>">
            <input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>">

            <div class="form-grid" style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="opp-title">Role Title <span style="color:var(--error);">*</span></label>
                    <input class="form-control" type="text" id="opp-title" name="title" value="<?php echo h($item['title']); ?>" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. Community Data Documentation Fellow">
                </div>

                <div class="form-group" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label class="form-label" for="opp-department">Department / Focus Area</label>
                        <input class="form-control" type="text" id="opp-department" name="department" value="<?php echo h($item['department']); ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. Research, Civic Tech, Heritage">
                    </div>
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label class="form-label" for="opp-loc">Location</label>
                        <input class="form-control" type="text" id="opp-loc" name="location" value="<?php echo h($item['location']); ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. Mombasa / Remote">
                    </div>
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label class="form-label" for="opp-type">Commitment Type</label>
                        <select class="form-control" id="opp-type" name="commitment_type" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
                            <?php foreach (['Flexible', 'Part-time', 'Full-time', 'Project-based'] as $ct): ?>
                                <option value="<?php echo h($ct); ?>" <?php echo $item['commitment_type'] === $ct ? 'selected' : ''; ?>><?php echo h($ct); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label class="form-label" for="opp-slots">Available Slots</label>
                        <input class="form-control" type="number" id="opp-slots" name="slots" value="<?php echo (int)$item['slots']; ?>" min="1" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
                    </div>
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label class="form-label" for="opp-deadline">Application Deadline</label>
                        <input class="form-control" type="date" id="opp-deadline" name="deadline" value="<?php echo h($item['deadline']); ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
                    </div>
                </div>

                <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="opp-desc">Role Description <span style="color:var(--error);">*</span></label>
                    <textarea class="form-control" id="opp-desc" name="description" rows="4" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="Describe the responsibilities, project scope, and learning outcomes..."><?php echo h($item['description']); ?></textarea>
                </div>

                <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                    <label class="form-label" for="opp-req">Requirements &amp; Qualifications</label>
                    <textarea class="form-control" id="opp-req" name="requirements" rows="4" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="Skills, background, or equipment needed (e.g. laptop, familiarity with basic research)..."><?php echo h($item['requirements']); ?></textarea>
                </div>

                <div style="display:flex; align-items:center; gap:10px;">
                    <input type="checkbox" id="opp-published" name="published" value="1" <?php echo !empty($item['published']) ? 'checked' : ''; ?> style="width: 18px; height: 18px; cursor: pointer;">
                    <label for="opp-published" style="font-weight: 600; cursor: pointer; margin: 0;">Open for applications &amp; visible on website</label>
                </div>

                <div style="display:flex; gap: 12px; margin-top: 10px;">
                    <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                        <?php echo $action === 'edit' ? 'Update Opportunity' : 'Save Opportunity'; ?>
                    </button>
                    <a href="admin_dashboard.php?tab=volunteers&subview=opportunities" class="btn btn-outline" style="padding: 10px 20px;">Cancel</a>
                </div>
            </div>
        </form>
<?php
    } else {
        $opportunities = getVolunteerOpportunities($conn, false);
?>
        <div style="display: flex; gap: 12px; margin-bottom: 24px; border-bottom: 2px solid var(--outline-variant); padding-bottom: 8px;">
            <a href="admin_dashboard.php?tab=volunteers&subview=opportunities" style="font-weight: 700; color: var(--primary); text-decoration: none; border-bottom: 3px solid var(--primary); padding-bottom: 8px; margin-bottom: -10px; display: inline-flex; align-items: center; gap: 6px;">
                <span class="material-symbols-outlined" style="font-size: 18px;">work</span> Opportunities
            </a>
            <a href="admin_dashboard.php?tab=volunteers&subview=applications" style="font-weight: 600; color: var(--on-surface-variant); text-decoration: none; padding-bottom: 8px; display: inline-flex; align-items: center; gap: 6px;">
                <span class="material-symbols-outlined" style="font-size: 18px;">group</span> Applications
            </a>
        </div>

        <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
            <div>
                <h2 style="font-size: 20px; color: var(--primary); margin: 0;">Volunteer &amp; Fellowship Roles</h2>
                <p style="font-size: 13px; color: var(--on-surface-variant); margin: 4px 0 0 0;">Create and manage public volunteer callouts, fellowships, and youth opportunities.</p>
            </div>
            <a href="admin_dashboard.php?tab=volunteers&subview=opportunities&action=add" class="btn btn-primary" style="padding: 8px 20px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                <span class="material-symbols-outlined" style="font-size:18px;">add</span>Add Role
            </a>
        </div>

        <?php if (!empty($opportunities)): ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Role &amp; Department</th>
                            <th>Location / Type</th>
                            <th>Deadline</th>
                            <th style="width: 70px; text-align: center;">Slots</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 130px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($opportunities as $row): ?>
                            <tr>
                                <td>
                                    <strong><?php echo h($row['title']); ?></strong>
                                    <div style="font-size: 12px; color: var(--on-surface-variant); margin-top: 2px;">
                                        <span class="badge badge-info"><?php echo h($row['department']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-size: 13px;"><?php echo h($row['location']); ?></span>
                                    <div style="font-size: 11px; color: var(--on-surface-variant);"><?php echo h($row['commitment_type']); ?></div>
                                </td>
                                <td>
                                    <?php if (!empty($row['deadline'])): ?>
                                        <span style="font-size: 12px; font-weight: 600; <?php echo strtotime($row['deadline']) < time() ? 'color: var(--error);' : ''; ?>">
                                            <?php echo h($row['deadline']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span style="font-size: 12px; color: var(--on-surface-variant);">Rolling</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;"><span class="badge" style="background: var(--surface-variant); color: var(--on-surface);"><?php echo (int)$row['slots']; ?></span></td>
                                <td>
                                    <?php if (!empty($row['published'])): ?>
                                        <span class="badge badge-success">Open</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger" style="background-color: var(--outline); color: #ffffff;">Closed</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <div class="action-buttons" style="justify-content: center;">
                                        <a href="admin_dashboard.php?tab=volunteers&subview=opportunities&action=volunteer_opp_toggle_publish&id=<?php echo $row['id']; ?>" class="action-btn" title="<?php echo !empty($row['published']) ? 'Close Role' : 'Open Role'; ?>">
                                            <span class="material-symbols-outlined" style="font-size:18px; color: <?php echo !empty($row['published']) ? 'var(--secondary)' : 'var(--outline)'; ?>;">
                                                <?php echo !empty($row['published']) ? 'visibility' : 'visibility_off'; ?>
                                            </span>
                                        </a>
                                        <a href="admin_dashboard.php?tab=volunteers&subview=opportunities&action=edit&id=<?php echo $row['id']; ?>" class="action-btn" title="Edit role">
                                            <span class="material-symbols-outlined" style="font-size:18px;">edit</span>
                                        </a>
                                        <a href="admin_dashboard.php?tab=volunteers&subview=opportunities&action=volunteer_opp_delete&id=<?php echo $row['id']; ?>" class="action-btn action-btn-danger js-delete-btn" data-message="Are you sure you want to delete this volunteer role?" title="Delete role">
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
            <p style="font-size: 14px; color: var(--on-surface-variant); text-align: center; padding: 40px 0;">No volunteer opportunities created yet. Add one to invite community applicants!</p>
        <?php endif; ?>
<?php
    }
} else {
    // subview === 'applications'
    $filter_status = isset($_GET['status']) ? trim($_GET['status']) : '';
    $applications = getVolunteerApplications($conn, null, $filter_status ?: null);
?>
    <div style="display: flex; gap: 12px; margin-bottom: 24px; border-bottom: 2px solid var(--outline-variant); padding-bottom: 8px;">
        <a href="admin_dashboard.php?tab=volunteers&subview=opportunities" style="font-weight: 600; color: var(--on-surface-variant); text-decoration: none; padding-bottom: 8px; display: inline-flex; align-items: center; gap: 6px;">
            <span class="material-symbols-outlined" style="font-size: 18px;">work</span> Opportunities
        </a>
        <a href="admin_dashboard.php?tab=volunteers&subview=applications" style="font-weight: 700; color: var(--primary); text-decoration: none; border-bottom: 3px solid var(--primary); padding-bottom: 8px; margin-bottom: -10px; display: inline-flex; align-items: center; gap: 6px;">
            <span class="material-symbols-outlined" style="font-size: 18px;">group</span> Applications
        </a>
    </div>

    <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="font-size: 20px; color: var(--primary); margin: 0;">Volunteer Applications</h2>
            <p style="font-size: 13px; color: var(--on-surface-variant); margin: 4px 0 0 0;">Review submitted applications, cover notes, and manage candidate workflow statuses.</p>
        </div>
        <div>
            <select onchange="window.location.href='admin_dashboard.php?tab=volunteers&subview=applications' + (this.value ? '&status=' + encodeURIComponent(this.value) : '')" style="padding: 7px 12px; border-radius: var(--radius-md); border: 1px solid var(--outline-variant); font-size: 13px; background: var(--surface);">
                <option value="">All Statuses</option>
                <option value="pending" <?php echo $filter_status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="reviewed" <?php echo $filter_status === 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                <option value="accepted" <?php echo in_array($filter_status, ['accepted', 'approved'], true) ? 'selected' : ''; ?>>Accepted</option>
                <option value="rejected" <?php echo $filter_status === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
            </select>
        </div>
    </div>

    <?php if (!empty($applications)): ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Applicant Details</th>
                        <th>Role Applied For</th>
                        <th>Motivation &amp; Skills</th>
                        <th>Date Submitted</th>
                        <th style="width: 140px;">Status Workflow</th>
                        <th style="width: 80px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $app): ?>
                        <tr>
                            <td>
                                <strong><?php echo h($app['applicant_name']); ?></strong>
                                <div style="font-size: 12px; color: var(--on-surface-variant); margin-top: 2px;">
                                    <a href="mailto:<?php echo h($app['applicant_email']); ?>" style="color: var(--primary);"><?php echo h($app['applicant_email']); ?></a>
                                    <?php if (!empty($app['applicant_phone'])): ?>
                                        <br><span style="font-family: monospace;"><?php echo h($app['applicant_phone']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <strong><?php echo h($app['opportunity_title'] ?: 'General Application'); ?></strong>
                            </td>
                            <td>
                                <?php if (!empty($app['skills'])): ?>
                                    <div style="font-size: 11px; font-weight: 600; color: var(--secondary); margin-bottom: 2px;">Skills: <?php echo h($app['skills']); ?></div>
                                <?php endif; ?>
                                <p style="font-size: 12px; color: var(--on-surface-variant); margin: 0; max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?php echo h($app['motivation']); ?>
                                </p>
                                <?php if (!empty($app['resume_path'])): ?>
                                    <div style="margin-top: 4px;">
                                        <a href="../<?php echo h(ltrim($app['resume_path'], '/')); ?>" target="_blank" style="font-size: 11px; color: var(--primary); text-decoration: underline; display: inline-flex; align-items: center; gap: 2px;">
                                            <span class="material-symbols-outlined" style="font-size: 14px;">attachment</span> View CV / Resume
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><span style="font-size: 12px;"><?php echo date('M d, Y', strtotime($app['created_at'])); ?></span></td>
                            <td>
                                <form method="POST" action="admin_dashboard.php?tab=volunteers&subview=applications" style="display: flex; gap: 4px; align-items: center;">
                                    <input type="hidden" name="csrf_token" value="<?php echo h(generateCsrfToken()); ?>">
                                    <input type="hidden" name="action" value="volunteer_app_status">
                                    <input type="hidden" name="id" value="<?php echo (int)$app['id']; ?>">
                                    <select name="status" onchange="this.form.submit()" style="font-size: 11px; padding: 4px 8px; border-radius: var(--radius-sm); border: 1px solid var(--outline-variant); background: var(--surface);">
                                        <option value="pending" <?php echo $app['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="reviewed" <?php echo $app['status'] === 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                                        <option value="accepted" <?php echo in_array($app['status'], ['accepted', 'approved'], true) ? 'selected' : ''; ?>>Accepted</option>
                                        <option value="rejected" <?php echo $app['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                    </select>
                                </form>
                            </td>
                            <td style="text-align: center;">
                                <a href="admin_dashboard.php?tab=volunteers&subview=applications&action=volunteer_app_delete&id=<?php echo $app['id']; ?>" class="action-btn action-btn-danger js-delete-btn" data-message="Are you sure you want to delete this application from '<?php echo h(addslashes($app['applicant_name'])); ?>'?" title="Delete application">
                                    <span class="material-symbols-outlined" style="font-size:18px;">delete</span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="font-size: 14px; color: var(--on-surface-variant); text-align: center; padding: 40px 0;">No volunteer applications received yet.</p>
    <?php endif; ?>
<?php } ?>

