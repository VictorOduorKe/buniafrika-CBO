<?php
if (!defined('LOC_PROJECTS')) {
    exit('Direct access not permitted.');
}

if ($action === 'add' || ($action === 'edit' && $id > 0)) {
    $event = ['title'=>'', 'description'=>'', 'event_date'=>date('Y-m-d\TH:i'), 'location'=>'', 'location_link'=>'', 'meeting_link'=>''];
    if ($action === 'edit') {
        $stmt = $conn->prepare("SELECT * FROM upcoming_events WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows === 1) {
            $event = $res->fetch_assoc();
            $event['event_date'] = date('Y-m-d\TH:i', strtotime($event['event_date']));
        }
        $stmt->close();
    }
?>
    <div class="panel-header">
        <h2 style="font-size: 20px; color: var(--primary);"><?php echo $action === 'edit' ? 'Edit Event' : 'Add Upcoming Event'; ?></h2>
        <a href="admin_dashboard.php?tab=events" class="btn btn-outline" style="padding: 6px 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
            <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>Back to List
        </a>
    </div>

    <form method="POST" action="admin_dashboard.php?tab=events<?php echo $action === 'edit' ? ACTION_EDIT_ID.$id : ''; ?>">
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'event_edit' : 'event_add'; ?>">

        <div class="form-group" style="display:flex; flex-direction:column; gap:6px; margin-bottom: 16px;">
            <label class="form-label" for="event-title">Event Title</label>
            <input class="form-control" type="text" id="event-title" name="title" value="<?php echo h($event['title']); ?>" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. Annual Fundraiser">
        </div>

        <div class="form-grid">
            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="event-date">Event Date & Time</label>
                <input class="form-control" type="datetime-local" id="event-date" name="event_date" value="<?php echo h($event['event_date']); ?>" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);">
            </div>

            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="event-location">Location</label>
                <input class="form-control" type="text" id="event-location" name="location" value="<?php echo h($event['location']); ?>" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="e.g. Main Hall, Nairobi">
            </div>

            <div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
                <label class="form-label" for="event-location-link">Location Link (Optional Google Maps link)</label>
                <input class="form-control" type="url" id="event-location-link" name="location_link" value="<?php echo h($event['location_link'] ?? ''); ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="https://maps.google.com/...">
            </div>

            <div class="form-group" style="display:flex; flex-direction:column; gap:6px; grid-column: 1 / -1;">
                <label class="form-label" for="event-meeting-link">Meeting Link (Optional)</label>
                <input class="form-control" type="url" id="event-meeting-link" name="meeting_link" value="<?php echo h($event['meeting_link'] ?? ''); ?>" style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; background:var(--background); color:var(--on-background);" placeholder="https://zoom.us/j/123456789">
            </div>
        </div>

        <div class="form-group" style="display:flex; flex-direction:column; gap:6px; margin-top: 16px;">
            <label class="form-label" for="event-description">Description</label>
            <textarea class="form-control" id="event-description" name="description" required style="padding:10px; border:1px solid var(--outline-variant); border-radius:var(--radius-lg); width:100%; height:120px; background:var(--background); color:var(--on-background);" placeholder="Describe the event..."><?php echo h($event['description']); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 24px; padding:12px 28px; width:100%; max-width:300px;"><?php echo $action === 'edit' ? 'Update Event' : 'Add Event'; ?></button>
    </form>

<?php
} else {
?>
    <div class="panel-header">
        <h2 style="font-size: 20px; color: var(--primary);">Upcoming Events</h2>
        <a href="admin_dashboard.php?tab=events&action=add" class="btn btn-primary" style="padding: 8px 20px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
            <span class="material-symbols-outlined" style="font-size:16px;">add</span>Add Event
        </a>
    </div>

    <?php
    $result = $conn->query("SELECT * FROM upcoming_events ORDER BY event_date ASC");
    if ($result && $result->num_rows > 0) {
    ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Visibility</th>
                        <th style="width: 120px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><strong><?php echo h($row['title']); ?></strong></td>
                            <td style="white-space: nowrap;"><?php echo date('M d, Y H:i T', strtotime($row['event_date'])); ?></td>
                            <td>
                                <?php if (!empty($row['location_link'])): ?>
                                    <a href="<?php echo h($row['location_link']); ?>" target="_blank" style="color: var(--primary); font-weight: 600; text-decoration: underline;"><?php echo h($row['location']); ?></a>
                                <?php else: ?>
                                    <?php echo h($row['location']); ?>
                                <?php endif; ?>
                                <?php if (!empty($row['meeting_link'])): ?>
                                    <br>
                                    <a href="<?php echo h($row['meeting_link']); ?>" target="_blank" style="font-size: 11px; color: var(--primary); text-decoration: underline; font-weight: 600;">Join Meeting</a>
                                <?php endif; ?>
                            </td>
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
                                        <a href="admin_dashboard.php?tab=events&action=toggle_event_publish&id=<?php echo $row['id']; ?>" class="action-btn" title="Unpublish event" style="color: var(--secondary);">
                                            <span class="material-symbols-outlined" style="font-size:16px;">visibility</span>
                                        </a>
                                    <?php else: ?>
                                        <a href="admin_dashboard.php?tab=events&action=toggle_event_publish&id=<?php echo $row['id']; ?>" class="action-btn" title="Publish event" style="color: var(--outline);">
                                            <span class="material-symbols-outlined" style="font-size:16px;">visibility_off</span>
                                        </a>
                                    <?php endif; ?>
                                    <a href="admin_dashboard.php?tab=events&action=edit&id=<?php echo $row['id']; ?>" class="action-btn" title="Edit event">
                                        <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                                    </a>
                                    <a href="admin_dashboard.php?tab=events&action=delete_event&id=<?php echo $row['id']; ?>" class="action-btn action-btn-danger js-delete-btn" data-message="Are you sure you want to delete this event?" title="Delete event">
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
        <p style="font-size: 14px; color: var(--on-surface-variant); text-align: center; padding: 40px 0;">No upcoming events added yet.</p>
    <?php } ?>
<?php } ?>
