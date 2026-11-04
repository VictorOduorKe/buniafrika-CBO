<?php
/**
 * BuniAfrika Public Community Events and Forums
 * Displays scheduled community forums, workshops, webinars, and conferences.
 */

$page_title = 'Community Events & Forums | BuniAfrika CBO';
$meta_desc = 'Discover and participate in upcoming civic tech forums, community town halls, research webinars, and workshops organized by BuniAfrika CBO.';

require_once __DIR__ . '/includes/header.php';

// Retrieve published upcoming events from database
$events = getUpcomingEvents($conn);
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-overlay"></div>
    <img class="hero-bg" style="opacity: 0.75;" src="<?php echo h($base_path); ?>assets/news0.jpeg" alt="Community gathering"/>
    <div class="hero-content">
        <h1 class="hero-title poppins-bold">Events &amp; Community Gatherings</h1>
        <p class="hero-desc poppins-medium">Civic dialogues, research briefings, webinars, and participatory workshops organized by BuniAfrika.</p>
        <div style="margin-top: 16px; display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <a href="news.php" class="btn btn-secondary" style="font-size: 13px; padding: 8px 18px; display: inline-flex; align-items: center; gap: 6px;">
                <span class="material-symbols-outlined" style="font-size: 16px;">article</span>
                Read Research &amp; News &rarr;
            </a>
            <a href="volunteer.php" class="btn btn-outline" style="font-size: 13px; padding: 8px 18px; display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.15); color: #fff; border-color: rgba(255,255,255,0.4);">
                <span class="material-symbols-outlined" style="font-size: 16px;">volunteer_activism</span>
                Get Involved
            </a>
        </div>
    </div>
</section>

<!-- Main Events Layout -->
<section class="section container" id="events-main">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--outline-variant);">
        <div>
            <h2 class="poppins-bold" style="font-size: 24px; color: var(--primary); margin: 0;">Scheduled Gatherings</h2>
            <p class="section-desc" style="font-size: 14px; margin: 4px 0 0 0;">Open to community members, partners, researchers, and fellows.</p>
        </div>

        <?php if (!empty($events)): ?>
            <div class="view-toggle-container" style="display: inline-flex; background: var(--surface-container); border-radius: var(--radius-full); padding: 4px; border: 1px solid var(--outline-variant);">
                <button id="btn-events-list" class="view-toggle-btn active" onclick="switchEventsPageTab('list')" style="padding: 6px 16px; border-radius: var(--radius-full); font-size: 13px; font-weight: 600; cursor: pointer; border: none; background: var(--primary); color: var(--on-primary); transition: all 0.2s;">
                    List View
                </button>
                <button id="btn-events-calendar" class="view-toggle-btn" onclick="switchEventsPageTab('calendar')" style="padding: 6px 16px; border-radius: var(--radius-full); font-size: 13px; font-weight: 600; cursor: pointer; border: none; background: transparent; color: var(--on-surface-variant); transition: all 0.2s;">
                    Calendar View
                </button>
            </div>
        <?php endif; ?>
    </div>

    <?php if (empty($events)): ?>
        <div class="empty-state" style="text-align: center; padding: 64px 20px; background: var(--surface); border: 1px solid var(--outline-variant); border-radius: var(--radius-xl); box-shadow: var(--shadow-sm); max-width: 680px; margin: 0 auto 32px auto;">
            <span class="material-symbols-outlined" style="font-size: 56px; color: var(--on-surface-variant); margin-bottom: 12px; display: block;" aria-hidden="true">event_busy</span>
            <h3 style="font-size: 20px; font-weight: 700; color: var(--primary); margin: 0 0 8px 0;">Content Not Available</h3>
            <p style="color: var(--on-surface-variant); font-size: 15px; max-width: 480px; margin: 0 auto; line-height: 1.6;">No public events or community forums are scheduled at this time. Please check back later or subscribe to our newsletter to receive event announcements.</p>
            <div style="margin-top: 24px;">
                <a href="index.php" class="btn btn-primary" style="padding: 8px 20px; font-size: 13px;">Return to Homepage</a>
            </div>
        </div>
    <?php else: ?>
        <!-- List View -->
        <div id="events-page-list-view" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px; margin-bottom: 40px;">
            <?php foreach ($events as $event): 
                $timestamp = strtotime($event['event_date']);
                $dayNumber = date('d', $timestamp);
                $monthAbbr = date('M', $timestamp);
                $yearVal = date('Y', $timestamp);
                $timeFormatted = date('h:i A T', $timestamp);
            ?>
                <article class="bento-card" style="display: flex; flex-direction: column; justify-content: space-between; border: 1px solid var(--outline-variant); border-radius: var(--radius-xl); padding: 24px; background: var(--surface); box-shadow: var(--shadow-sm); transition: transform 0.2s, box-shadow 0.2s;">
                    <div>
                        <div style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 16px;">
                            <div style="background: var(--primary-container); color: var(--on-primary-container); border-radius: var(--radius-lg); padding: 10px 14px; text-align: center; min-width: 64px; flex-shrink: 0; box-shadow: var(--shadow-xs);">
                                <span style="display: block; font-size: 11px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;"><?php echo h($monthAbbr); ?></span>
                                <span style="display: block; font-size: 24px; font-weight: 800; line-height: 1.1; margin: 2px 0;"><?php echo h($dayNumber); ?></span>
                                <span style="display: block; font-size: 11px; opacity: 0.85;"><?php echo h($yearVal); ?></span>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 4px;">
                                <div style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--on-surface-variant);">
                                    <span class="material-symbols-outlined" style="font-size: 15px; color: var(--primary);" aria-hidden="true">schedule</span>
                                    <span><?php echo h($timeFormatted); ?></span>
                                </div>
                                <h3 class="poppins-bold" style="font-size: 17px; color: var(--primary); margin: 4px 0 0 0; line-height: 1.35;">
                                    <?php echo h($event['title']); ?>
                                </h3>
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px; font-size: 13px; color: var(--on-surface-variant);">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span class="material-symbols-outlined" style="font-size: 16px; color: var(--secondary);" aria-hidden="true">location_on</span>
                                <span>
                                    <?php if (!empty($event['location_link'])): ?>
                                        <a href="<?php echo h($event['location_link']); ?>" target="_blank" rel="noopener noreferrer" style="color: inherit; text-decoration: underline; font-weight: 600;">
                                            <?php echo h($event['location']); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php echo h($event['location']); ?>
                                    <?php endif; ?>
                                </span>
                            </div>

                            <?php if (!empty($event['meeting_link'])): ?>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <span class="material-symbols-outlined" style="font-size: 16px; color: var(--primary);" aria-hidden="true">videocam</span>
                                    <a href="<?php echo h($event['meeting_link']); ?>" target="_blank" rel="noopener noreferrer" style="color: var(--primary); text-decoration: underline; font-weight: 600;">
                                        Join Virtual Meeting
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <p style="font-size: 13px; color: var(--on-surface); line-height: 1.55; margin: 0 0 16px 0;">
                            <?php 
                            $shortDesc = mb_strlen($event['description']) > 140
                                ? mb_substr($event['description'], 0, 140) . '...'
                                : $event['description'];
                            echo h($shortDesc);
                            ?>
                        </p>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 14px; border-top: 1px solid var(--outline-variant); flex-wrap: wrap; gap: 10px;">
                        <button class="btn btn-outline" style="padding: 6px 14px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;" onclick='showPageEventDetails(
                            <?php echo json_encode($event["title"]); ?>,
                            <?php echo json_encode(date("M d, Y - h:i A T", $timestamp)); ?>,
                            <?php echo json_encode($event["location"]); ?>,
                            <?php echo json_encode($event["location_link"] ?? ""); ?>,
                            <?php echo json_encode($event["meeting_link"] ?? ""); ?>,
                            <?php echo json_encode($event["description"]); ?>
                        )'>
                            <span class="material-symbols-outlined" style="font-size: 16px;">info</span>
                            Event Details
                        </button>

                        <div class="social-share" style="display: flex; gap: 8px; align-items: center;">
                            <span style="font-size: 11px; color: var(--on-surface-variant); font-weight: 600;">Share:</span>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://buniafrika.co.ke/events.php?event=' . $event['id']); ?>" target="_blank" rel="noopener noreferrer" style="color: #1877F2; display: flex; align-items: center;" title="Share on Facebook">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="https://api.whatsapp.com/send?text=<?php echo urlencode('Join this event with BuniAfrika: ' . $event['title'] . ' - https://buniafrika.co.ke/events.php'); ?>" target="_blank" rel="noopener noreferrer" style="color: #25D366; display: flex; align-items: center;" title="Share on WhatsApp">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12.012 2c5.506 0 9.988 4.478 9.988 9.985 0 5.508-4.482 9.985-9.988 9.985-1.745 0-3.39-.447-4.836-1.233l-5.176 1.353 1.383-5.048A9.914 9.914 0 012.024 11.985C2.024 6.478 6.506 2 12.012 2zm0 1.666C7.432 3.666 3.69 7.412 3.69 11.985c0 1.802.57 3.518 1.624 4.935l.17.228-.823 3.003 3.082-.807.222.13c1.36.797 2.923 1.218 4.547 1.218 4.58 0 8.322-3.746 8.322-8.318 0-4.573-3.742-8.318-8.322-8.318zM8.884 7.62c-.22-.004-.467.08-.667.275-.54.526-.645 1.355-.262 2.054.49 1.01 1.272 1.95 2.152 2.662 1.487 1.2 2.983 2.015 4.567 2.37.525.118 1.07.054 1.488-.236.425-.295.66-.757.722-1.242.062-.486-.23-.9-.675-1.12l-1.95-.956c-.36-.176-.788-.08-1.05.234l-.608.73c-.15.18-.396.23-.604.126-1.15-.56-2.275-1.428-2.92-2.32-.113-.157-.09-.373.056-.51l.525-.518c.24-.236.31-.58.172-.888l-.75-1.897c-.174-.442-.647-.69-1.11-.635z"/></svg>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Calendar View -->
        <div id="events-page-calendar-view" style="display: none; background: var(--surface); border: 1px solid var(--outline-variant); border-radius: var(--radius-xl); padding: 24px; box-shadow: var(--shadow-sm); margin-bottom: 40px;">
            <div id="page-events-calendar"></div>
        </div>
    <?php endif; ?>
</section>

<!-- Accessible Event Details Modal -->
<div id="page-event-modal" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 1000; align-items: center; justify-content: center; padding: 20px;" role="dialog" aria-modal="true" aria-labelledby="page-event-modal-title">
    <div style="background: var(--surface); border-radius: var(--radius-xl); max-width: 580px; width: 100%; border: 1px solid var(--outline-variant); box-shadow: var(--shadow-lg); overflow: hidden; animation: modalFadeIn 0.25s ease-out;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; border-bottom: 1px solid var(--outline-variant); background: var(--surface-container-low);">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="material-symbols-outlined" style="color: var(--primary);" aria-hidden="true">event</span>
                <h3 id="page-event-modal-title" class="poppins-bold" style="font-size: 18px; color: var(--primary); margin: 0;">Event Details</h3>
            </div>
            <button onclick="closePageEventModal()" type="button" aria-label="Close dialog" style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--on-surface-variant); display: flex; align-items: center; justify-content: center; padding: 4px; border-radius: var(--radius-md);">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <div style="padding: 24px; display: flex; flex-direction: column; gap: 16px;">
            <div style="display: flex; flex-direction: column; gap: 8px; background: var(--surface-container); border-radius: var(--radius-lg); padding: 14px 18px;">
                <div style="display: flex; align-items: center; gap: 8px; font-size: 13px;">
                    <span class="material-symbols-outlined" style="font-size: 18px; color: var(--primary);" aria-hidden="true">schedule</span>
                    <strong style="color: var(--on-surface);">Time:</strong>
                    <span id="page-event-modal-date" style="color: var(--on-surface-variant);"></span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px; font-size: 13px;">
                    <span class="material-symbols-outlined" style="font-size: 18px; color: var(--secondary);" aria-hidden="true">location_on</span>
                    <strong style="color: var(--on-surface);">Location:</strong>
                    <span id="page-event-modal-location" style="color: var(--on-surface-variant);"></span>
                </div>
                <div id="page-event-modal-meeting-wrap" style="display: none; align-items: center; gap: 8px; font-size: 13px;">
                    <span class="material-symbols-outlined" style="font-size: 18px; color: var(--primary);" aria-hidden="true">videocam</span>
                    <strong style="color: var(--on-surface);">Meeting:</strong>
                    <a id="page-event-modal-meeting-link" href="#" target="_blank" rel="noopener noreferrer" style="color: var(--primary); text-decoration: underline; font-weight: 600;">Join Virtual Link</a>
                </div>
            </div>

            <div>
                <h4 style="font-size: 14px; font-weight: 700; color: var(--primary); margin: 0 0 6px 0;">Description</h4>
                <p id="page-event-modal-desc" style="font-size: 14px; color: var(--on-surface); line-height: 1.6; margin: 0; white-space: pre-wrap;"></p>
            </div>
        </div>

        <div style="padding: 16px 24px; border-top: 1px solid var(--outline-variant); display: flex; justify-content: flex-end; gap: 12px; background: var(--surface-container-low);">
            <button onclick="closePageEventModal()" type="button" class="btn btn-outline" style="padding: 8px 20px; font-size: 13px;">Close</button>
        </div>
    </div>
</div>

<?php
$js_events = [];
foreach ($events as $ev) {
    $js_events[] = [
        'id' => (int)$ev['id'],
        'title' => $ev['title'],
        'start' => $ev['event_date'],
        'extendedProps' => [
            'description' => $ev['description'],
            'location' => $ev['location'],
            'location_link' => $ev['location_link'] ?? '',
            'meeting_link' => $ev['meeting_link'] ?? '',
            'formatted_date' => date('M d, Y - h:i A T', strtotime($ev['event_date'])),
        ]
    ];
}
?>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
const pageEventsData = <?php echo json_encode($js_events); ?>;
let pageCalendarInstance = null;

function switchEventsPageTab(viewType) {
    const listView = document.getElementById('events-page-list-view');
    const calendarView = document.getElementById('events-page-calendar-view');
    const btnList = document.getElementById('btn-events-list');
    const btnCalendar = document.getElementById('btn-events-calendar');

    if (!listView || !calendarView || !btnList || !btnCalendar) return;

    if (viewType === 'list') {
        listView.style.display = 'grid';
        calendarView.style.display = 'none';
        btnList.style.background = 'var(--primary)';
        btnList.style.color = 'var(--on-primary)';
        btnCalendar.style.background = 'transparent';
        btnCalendar.style.color = 'var(--on-surface-variant)';
    } else {
        listView.style.display = 'none';
        calendarView.style.display = 'block';
        btnCalendar.style.background = 'var(--primary)';
        btnCalendar.style.color = 'var(--on-primary)';
        btnList.style.background = 'transparent';
        btnList.style.color = 'var(--on-surface-variant)';

        if (!pageCalendarInstance) {
            initPageCalendar();
        } else {
            pageCalendarInstance.updateSize();
        }
    }
}

function initPageCalendar() {
    const calendarEl = document.getElementById('page-events-calendar');
    if (!calendarEl || typeof FullCalendar === 'undefined') return;

    pageCalendarInstance = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        events: pageEventsData,
        eventClick: function(info) {
            const ep = info.event.extendedProps;
            showPageEventDetails(
                info.event.title,
                ep.formatted_date || '',
                ep.location || '',
                ep.location_link || '',
                ep.meeting_link || '',
                ep.description || ''
            );
        },
        height: 'auto',
        contentHeight: 'auto',
        aspectRatio: 1.35
    });
    pageCalendarInstance.render();
}

function showPageEventDetails(title, dateStr, location, locationLink, meetingLink, desc) {
    const modal = document.getElementById('page-event-modal');
    if (!modal) return;

    document.getElementById('page-event-modal-title').textContent = title;
    document.getElementById('page-event-modal-date').textContent = dateStr;

    const locEl = document.getElementById('page-event-modal-location');
    if (locationLink && locationLink.trim() !== '') {
        locEl.innerHTML = `<a href="${encodeURI(locationLink)}" target="_blank" rel="noopener noreferrer" style="color: inherit; text-decoration: underline;">${titleSafe(location)}</a>`;
    } else {
        locEl.textContent = location || 'Location to be announced';
    }

    const meetingWrap = document.getElementById('page-event-modal-meeting-wrap');
    const meetingLinkEl = document.getElementById('page-event-modal-meeting-link');
    if (meetingLink && meetingLink.trim() !== '') {
        meetingWrap.style.display = 'flex';
        meetingLinkEl.href = meetingLink;
    } else {
        meetingWrap.style.display = 'none';
    }

    document.getElementById('page-event-modal-desc').textContent = desc || '';

    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closePageEventModal() {
    const modal = document.getElementById('page-event-modal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

function titleSafe(str) {
    const p = document.createElement('p');
    p.textContent = str;
    return p.innerHTML;
}

window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePageEventModal();
    }
});

const pageModalOverlay = document.getElementById('page-event-modal');
if (pageModalOverlay) {
    pageModalOverlay.addEventListener('click', function(e) {
        if (e.target === this) {
            closePageEventModal();
        }
    });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

