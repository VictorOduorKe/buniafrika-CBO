<?php
if (!defined('LOC_PROJECTS')) {
    exit('Direct access not permitted.');
}
$role_data_query = $conn->query("SELECT role, COUNT(*) as count FROM registrations GROUP BY role");
$roles_labels = [];
$roles_counts = [];
if ($role_data_query) {
    while ($row = $role_data_query->fetch_assoc()) {
        $roles_labels[] = ucfirst($row['role']);
        $roles_counts[] = (int)$row['count'];
    }
}

$activity_data_query = $conn->query("SELECT activity, COUNT(*) as count FROM activity_logs GROUP BY activity ORDER BY count DESC LIMIT 5");
$activity_labels = [];
$activity_counts = [];
if ($activity_data_query) {
    while ($row = $activity_data_query->fetch_assoc()) {
        $activity_labels[] = $row['activity'];
        $activity_counts[] = (int)$row['count'];
    }
}

$days = [];
$registration_by_day = [];
$contact_by_day = [];
$mailing_by_day = [];

for ($i = 6; $i >= 0; $i--) {
    $date_str = date('Y-m-d', strtotime("-$i days"));
    $display_str = date('D (m/d)', strtotime("-$i days"));
    $days[$date_str] = $display_str;
    $registration_by_day[$date_str] = 0;
    $contact_by_day[$date_str] = 0;
    $mailing_by_day[$date_str] = 0;
}

$reg_trend_query = $conn->query("SELECT DATE(created_at) as date, COUNT(*) as count FROM registrations WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) GROUP BY DATE(created_at)");
if ($reg_trend_query) {
    while ($row = $reg_trend_query->fetch_assoc()) {
        $date = $row['date'];
        if (isset($registration_by_day[$date])) {
            $registration_by_day[$date] = (int)$row['count'];
        }
    }
}

$contact_trend_query = $conn->query("SELECT DATE(created_at) as date, COUNT(*) as count FROM contact_submissions WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) GROUP BY DATE(created_at)");
if ($contact_trend_query) {
    while ($row = $contact_trend_query->fetch_assoc()) {
        $date = $row['date'];
        if (isset($contact_by_day[$date])) {
            $contact_by_day[$date] = (int)$row['count'];
        }
    }
}

$mailing_trend_query = $conn->query("SELECT DATE(created_at) as date, COUNT(*) as count FROM mailing_list WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) GROUP BY DATE(created_at)");
if ($mailing_trend_query) {
    while ($row = $mailing_trend_query->fetch_assoc()) {
        $date = $row['date'];
        if (isset($mailing_by_day[$date])) {
            $mailing_by_day[$date] = (int)$row['count'];
        }
    }
}

$weekly_labels = array_values($days);
$weekly_registrations = array_values($registration_by_day);
$weekly_contacts = array_values($contact_by_day);
$weekly_mailing = array_values($mailing_by_day);

$roles_labels_json = json_encode($roles_labels);
$roles_counts_json = json_encode($roles_counts);
$activity_labels_json = json_encode($activity_labels);
$activity_counts_json = json_encode($activity_counts);
$weekly_labels_json = json_encode($weekly_labels);
$weekly_registrations_json = json_encode($weekly_registrations);
$weekly_contacts_json = json_encode($weekly_contacts);
$weekly_mailing_json = json_encode($weekly_mailing);
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.charts-grid-two {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--spacing-lg);
}
@media (min-width: 1024px) {
    .charts-grid-two {
        grid-template-columns: 1fr 1fr;
    }
}
</style>

<h2 style="font-size: 20px; margin-bottom: var(--spacing-md); color: var(--primary);">System Overview</h2>

<div class="charts-container" style="display: flex; flex-direction: column; gap: var(--spacing-lg); margin-bottom: var(--spacing-lg);">
    <!-- Chart 1: Dual Line chart for trends -->
    <div class="bento-card" style="box-shadow: none; border-radius: var(--radius-lg); padding: var(--spacing-md); min-height: 320px; border: 1px solid var(--outline-variant); background: var(--surface);">
        <h3 style="font-size: 16px; margin-top: 0; margin-bottom: var(--spacing-sm); color: var(--primary); display: flex; align-items: center; gap: 8px;">
            <span class="material-symbols-outlined" style="color: var(--secondary);">trending_up</span> Weekly Engagement Trends (Last 7 Days)
        </h3>
        <div style="position: relative; height: 250px; width: 100%;">
            <canvas id="engagementTrendChart"></canvas>
        </div>
    </div>

    <!-- Group 2: Two charts side-by-side on desktop -->
    <div class="charts-grid-two">
        <div class="bento-card" style="box-shadow: none; border-radius: var(--radius-lg); padding: var(--spacing-md); min-height: 320px; border: 1px solid var(--outline-variant); background: var(--surface);">
            <h3 style="font-size: 16px; margin-top: 0; margin-bottom: var(--spacing-sm); color: var(--primary); display: flex; align-items: center; gap: 8px;">
                <span class="material-symbols-outlined" style="color: var(--secondary);">pie_chart</span> Registrants by Role
            </h3>
            <div style="position: relative; height: 250px; width: 100%; display: flex; justify-content: center; align-items: center;">
                <canvas id="rolesDistributionChart"></canvas>
            </div>
        </div>

        <div class="bento-card" style="box-shadow: none; border-radius: var(--radius-lg); padding: var(--spacing-md); min-height: 320px; border: 1px solid var(--outline-variant); background: var(--surface);">
            <h3 style="font-size: 16px; margin-top: 0; margin-bottom: var(--spacing-sm); color: var(--primary); display: flex; align-items: center; gap: 8px;">
                <span class="material-symbols-outlined" style="color: var(--secondary);">bar_chart</span> Top Activity Types
            </h3>
            <div style="position: relative; height: 250px; width: 100%;">
                <canvas id="activityLogChart"></canvas>
            </div>
        </div>
    </div>
</div>
<div style="display: grid; grid-template-columns: 1fr; gap: var(--spacing-lg);">

    <div class="bento-card" style="box-shadow: none; border-radius: var(--radius-lg); padding: var(--spacing-md);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:var(--spacing-sm);">
            <h3 style="font-size: 16px;">Recent Registration Requests</h3>
            <a href="admin_dashboard.php?tab=registrations" style="font-size: 12px; color: var(--primary); font-weight:700; text-decoration: underline;">View All</a>
        </div>
        <?php
        $result = $conn->query("SELECT * FROM registrations ORDER BY created_at DESC LIMIT 5");
        if ($result && $result->num_rows > 0) {
        ?>
            <div class="table-responsive" style="box-shadow:none; margin-bottom:0;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><strong><?php echo h($row['first_name'] . ' ' . $row['last_name']); ?></strong></td>
                                <td>
                                    <?php
                                    $role_badge = 'badge-warning';
                                    if ($row['role'] === 'volunteer') {
                                        $role_badge = 'badge-success';
                                    }
                                    ?>
                                    <span class="badge <?php echo $role_badge; ?>"><?php echo h(ucfirst($row['role'])); ?></span>
                                </td>
                                <td><?php echo h($row['email']); ?></td>
                                <td><?php echo date(DATE_FORMAT_YMD_HI, strtotime($row['created_at'])); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <p style="font-size: 14px; color: var(--on-surface-variant); text-align: center; padding: 20px 0;">No registrations logged yet.</p>
        <?php } ?>
    </div>

    <div class="bento-card" style="box-shadow: none; border-radius: var(--radius-lg); padding: var(--spacing-md);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:var(--spacing-sm);">
            <h3 style="font-size: 16px;">Recent Contact Inquiries</h3>
            <a href="admin_dashboard.php?tab=contacts" style="font-size: 12px; color: var(--primary); font-weight:700; text-decoration: underline;">View All</a>
        </div>
        <?php
        $result = $conn->query("SELECT * FROM contact_submissions ORDER BY created_at DESC LIMIT 5");
        if ($result && $result->num_rows > 0) {
        ?>
            <div class="table-responsive" style="box-shadow:none; margin-bottom:0;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Sender Name</th>
                            <th>Subject</th>
                            <th>Email</th>
                            <th>Date Received</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><strong><?php echo h($row['name']); ?></strong></td>
                                <td><?php echo h($row['subject'] ?: '(No Subject)'); ?></td>
                                <td><?php echo h($row['email']); ?></td>
                                <td><?php echo date(DATE_FORMAT_YMD_HI, strtotime($row['created_at'])); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <p style="font-size: 14px; color: var(--on-surface-variant); text-align: center; padding: 20px 0;">No messages received yet.</p>
        <?php } ?>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const primaryColor = '#002045';
    const secondaryColor = '#3b6934';
    const tertiaryColor = '#f57d32';
    const errorColor = '#ba1a1a';
    const textColor = '#43474e';

    const ctxTrend = document.getElementById('engagementTrendChart').getContext('2d');
    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: <?php echo $weekly_labels_json; ?>,
            datasets: [
                {
                    label: 'Registrations/Involvements',
                    data: <?php echo $weekly_registrations_json; ?>,
                    borderColor: secondaryColor,
                    backgroundColor: 'rgba(59, 105, 52, 0.08)',
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: secondaryColor,
                    pointHoverRadius: 6
                },
                {
                    label: 'Contact Inquiries',
                    data: <?php echo $weekly_contacts_json; ?>,
                    borderColor: primaryColor,
                    backgroundColor: 'rgba(0, 32, 69, 0.08)',
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: primaryColor,
                    pointHoverRadius: 6
                },
                {
                    label: 'Mailing List Signups',
                    data: <?php echo $weekly_mailing_json; ?>,
                    borderColor: tertiaryColor,
                    backgroundColor: 'rgba(245, 125, 50, 0.08)',
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: tertiaryColor,
                    pointHoverRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: { family: 'Public Sans', size: 12 }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: { family: 'Public Sans' }
                    }
                },
                x: {
                    ticks: {
                        font: { family: 'Public Sans' }
                    }
                }
            }
        }
    });

    const ctxRoles = document.getElementById('rolesDistributionChart').getContext('2d');
    const rolesLabels = <?php echo $roles_labels_json; ?>;
    const rolesCounts = <?php echo $roles_counts_json; ?>;

    const displayRolesLabels = rolesLabels.length ? rolesLabels : ['No Data'];
    const displayRolesCounts = rolesCounts.length ? rolesCounts : [1];
    const rolesColors = rolesLabels.length ? [secondaryColor, primaryColor, tertiaryColor, '#74777f'] : ['#e5e9eb'];

    new Chart(ctxRoles, {
        type: 'doughnut',
        data: {
            labels: displayRolesLabels,
            datasets: [{
                data: displayRolesCounts,
                backgroundColor: rolesColors,
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { family: 'Public Sans', size: 12 }
                    }
                }
            },
            cutout: '65%'
        }
    });

    const ctxActivities = document.getElementById('activityLogChart').getContext('2d');
    const activityLabels = <?php echo $activity_labels_json; ?>;
    const activityCounts = <?php echo $activity_counts_json; ?>;

    const displayActivityLabels = activityLabels.length ? activityLabels : ['No Action'];
    const displayActivityCounts = activityCounts.length ? activityCounts : [0];

    new Chart(ctxActivities, {
        type: 'bar',
        data: {
            labels: displayActivityLabels,
            datasets: [{
                label: 'Action Log Count',
                data: displayActivityCounts,
                backgroundColor: primaryColor,
                borderRadius: 6,
                borderWidth: 0,
                barThickness: 18
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: { family: 'Public Sans' }
                    }
                },
                y: {
                    ticks: {
                        font: { family: 'Public Sans', size: 11 }
                    }
                }
            }
        }
    });
});
</script>
