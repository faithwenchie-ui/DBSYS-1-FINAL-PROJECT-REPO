<?php
// manage_registrations.php - Registration Management Terminal
require_once 'db_connect.php';

$message = '';
$message_type = '';

// Check if an event filter has been passed from the index.php dashboard button
$filtered_event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

// Handle Administrative Confirmation Status Changes (Accept / Deny Actions)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['registration_id'])) {
    $reg_id = intval($_POST['registration_id']);
    $action = $_POST['action'];
    
    // Map actions to matching table configuration enum constraints
    $new_status = '';
    if ($action === 'accept') {
        $new_status = 'confirmed';
    } elseif ($action === 'deny') {
        $new_status = 'denied';
    }

    if (!empty($new_status) && $reg_id > 0) {
        $update_sql = "UPDATE registrations SET confirmation_status = ? WHERE registration_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $new_status, $reg_id);
        
        if ($stmt->execute()) {
            $message = "Registration record #" . $reg_id . " status successfully updated to '" . $new_status . "'.";
            $message_type = "success";
        } else {
            $message = "Database management constraint failure: " . $conn->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}

// Base Query String Construction mapping details across tables
$query = "SELECT 
            r.registration_id, 
            r.registration_date, 
            r.confirmation_status, 
            r.attendance_status,
            m.student_id, 
            CONCAT(m.first_name, ' ', m.last_name) AS student_name, 
            m.email AS school_email, 
            m.course, 
            m.year_level,
            e.event_name 
          FROM registrations r
          JOIN members m ON r.member_id = m.member_id
          JOIN events e ON r.event_id = e.event_id";

// Dynamic Condition Injection: If filtering by event ID, narrow row metrics scope safely
if ($filtered_event_id > 0) {
    $query .= " WHERE r.event_id = ? ORDER BY r.registration_date DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $filtered_event_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $query .= " ORDER BY r.registration_date DESC";
    $result = $conn->query($query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAct Event Tracker - Review Registerees</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #F8FAFC; /* Background */
            margin: 0; 
            padding: 0; 
            color: #1F2937; /* Text */
            display: flex;
            min-height: 100vh;
        }

        /* --- Fixed Left Sidebar Styling --- */
        .sidebar {
            width: 280px;
            background: #0F766E; /* Dark Teal */
            color: #ffffff;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            box-shadow: 4px 0 10px rgba(15, 118, 110, 0.1);
            position: fixed; 
            top: 0;
            bottom: 0;
            left: 0;
            box-sizing: border-box;
            z-index: 100;
        }

        .sidebar h1 { 
            font-size: 2rem; 
            justify-content: center;
            text-align: center;
            margin-top: 0; 
            margin-bottom: 0; 
            color: #ffffff; 
        }

        .title h1{
            font-size: 1.6rem; 
            margin-top: 0; 
            margin-bottom: 30px; 
            color: #ffffff; 
        }

        .nav-bar { 
            display: flex; 
            flex-direction: column; 
            gap: 8px; 
        }

        .nav-bar a { 
            color: #E2E8F0; 
            text-decoration: none; 
            font-weight: 500; 
            font-size: 0.95rem; 
            padding: 12px 15px;
            border-radius: 6px;
            transition: all 0.2s ease; 
        }

        .nav-bar a:hover { 
            color: #ffffff; 
            background: #14B8A6; /* Primary Teal */
        }

        .nav-bar a.active { 
            color: #ffffff; 
            background: #14B8A6; /* Primary Teal */
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(20, 184, 166, 0.2);
        }

        /* --- Main Content Area Layout --- */
        .main-content {
            flex: 1;
            margin-left: 280px; 
            padding: 40px;
            box-sizing: border-box;
        }

        .brand-header h2 { 
            font-size: 2rem; 
            margin-top: 0; 
            margin-bottom: 5px; 
            color: #1F2937; 
        }

        .brand-header p { 
            color: #6B7280; 
            margin-top: 0; 
            margin-bottom: 30px; 
            font-size: 1rem; 
        }

        /* --- Table View Container Card --- */
        .data-card { 
            background: #ffffff; 
            padding: 35px; 
            border-radius: 16px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05); 
        }

        .data-card h3 { 
            font-size: 1.4rem; 
            margin-top: 0; 
            margin-bottom: 25px; 
            color: #1F2937; 
            border-bottom: 2px solid #E2E8F0; 
            padding-bottom: 12px; 
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .clear-filter-link {
            font-size: 0.85rem;
            color: #14B8A6;
            text-decoration: none;
            font-weight: 600;
            border: 1px solid #14B8A6;
            padding: 4px 12px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .clear-filter-link:hover {
            background-color: #14B8A6;
            color: white;
        }

        /* --- Admin Interactive Structural Response Tables --- */
        .table-responsive { 
            overflow-x: auto; 
            margin-top: 15px; 
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            text-align: left; 
            font-size: 0.95rem; 
        }

        th, td { 
            padding: 14px 16px; 
            border-bottom: 1px solid #E2E8F0; 
            vertical-align: middle; 
        }

        th { 
            background-color: #0F766E; /* Dark Teal Table Headers */
            color: #ffffff; 
            font-weight: 600; 
            text-transform: uppercase; 
            font-size: 0.85rem; 
            letter-spacing: 0.5px; 
        }

        tr:hover td { 
            background-color: #F8FAFC; 
        }

        /* --- Dynamic Confirmation Status Labels Badges --- */
        .badge { 
            padding: 6px 14px; 
            border-radius: 50px; 
            font-weight: 700; 
            font-size: 0.75rem; 
            display: inline-block; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
        }

        .badge-pending { 
            background-color: #FEF3C7; 
            color: #D97706; 
        }

        .badge-confirmed { 
            background-color: #D1FAE5; 
            color: #10B981; /* Success Green Dynamic */
        }

        .badge-denied { 
            background-color: #FEE2E2; 
            color: #EF4444; 
        }

        /* --- Interactive Action Controls --- */
        .action-cell { 
            display: flex; 
            gap: 8px; 
            justify-content: center;
        }

        .btn-action { 
            padding: 8px 14px; 
            border: none; 
            border-radius: 6px; 
            font-weight: 600; 
            font-size: 0.85rem; 
            cursor: pointer; 
            transition: background-color 0.2s, transform 0.1s; 
            color: white; 
        }

        .btn-action:active {
            transform: scale(0.96);
        }

        .btn-accept { 
            background-color: #10B981; /* Success Green Action */
        }

        .btn-accept:hover { 
            background-color: #059669; 
        }

        .btn-deny { 
            background-color: #EF4444; 
        }

        .btn-deny:hover { 
            background-color: #DC2626; 
        }

        /* --- System Hub Notification Alerts --- */
        .alert { 
            padding: 15px 20px; 
            border-radius: 8px; 
            margin-bottom: 25px; 
            font-weight: 600; 
            font-size: 0.95rem; 
        }

        .alert-success { 
            background-color: #D1FAE5; 
            color: #065F46; 
            border: 1px solid #A7F3D0; 
        }

        .alert-error { 
            background-color: #FEE2E2; 
            color: #991B1B; 
            border: 1px solid #FCA5A5; 
        }

        /* Responsive Configuration Grid Breakpoints */
        @media (max-width: 1200px) {
            body { flex-direction: column; }
            .sidebar { position: relative; width: 100%; }
            .main-content { margin-left: 0; padding: 20px; }
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h1>UAct</h1>
        <div class="title">
            <h1>Event Tracker</h1>
        </div>

        <div class="nav-bar">
            <a href="index.php">Dashboard Home</a>
            <a href="events_list.php">Events Directory</a>
            <a href="new_event.php">Create New Event</a>
            <a href="Activity_Overview.php">Organization Activity Overview</a>
            <a href="manage_registrations.php" class="active">Review Registerees</a>
            <a href="registration.php">Registration Terminal</a>
            <a href="log_in.php" style="margin-top: 20px; border: 1px solid rgba(226, 232, 240, 0.3); text-align: center; color: #FCA5A5;">
                Exit Portal
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="brand-header">
            <h2>Review Registerees</h2>
            <p>Approve or deny submitted application logs for university operations.</p>
        </div>

        <div class="data-card">
            <h3>
                <span><?php echo ($filtered_event_id > 0) ? "Filtered Event Records" : "Pending & Historic Directory"; ?></span>
                <?php if ($filtered_event_id > 0): ?>
                    <a href="manage_registrations.php" class="clear-filter-link">Clear Filter ✕</a>
                <?php endif; ?>
            </h3>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $message_type === 'success' ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <?php if ($result && $result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Reg ID</th>
                                <th>Student Info</th>
                                <th>Academic Track</th>
                                <th>Target Event Title</th>
                                <th>Timestamp</th>
                                <th>Confirmation Status</th>
                                <th style="text-align: center;">Terminal Operations</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><strong>#<?php echo $row['registration_id']; ?></strong></td>
                                    <td>
                                        <div style="font-weight: 600; color: #1F2937;"><?php echo htmlspecialchars($row['student_name']); ?></div>
                                        <div style="font-size: 0.85rem; color: #4B5563;"><?php echo htmlspecialchars($row['student_id']); ?> | <?php echo htmlspecialchars($row['school_email']); ?></div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 500; color: #374151;"><?php echo htmlspecialchars($row['course']); ?></div>
                                        <div style="font-size: 0.85rem; color: #6B7280;">Year Level: <?php echo htmlspecialchars($row['year_level']); ?></div>
                                    </td>
                                    <td><span style="color: #0F766E; font-weight: 600;"><?php echo htmlspecialchars($row['event_name']); ?></span></td>
                                    <td style="font-size: 0.85rem; color: #374151;"><?php echo htmlspecialchars($row['registration_date']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo htmlspecialchars($row['confirmation_status']); ?>">
                                            <?php echo htmlspecialchars($row['confirmation_status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($row['confirmation_status'] === 'pending'): ?>
                                            <div class="action-cell">
                                                <form method="POST" action="manage_registrations.php<?php echo ($filtered_event_id > 0) ? '?event_id=' . $filtered_event_id : ''; ?>" style="margin: 0;">
                                                    <input type="hidden" name="registration_id" value="<?php echo $row['registration_id']; ?>">
                                                    <input type="hidden" name="action" value="accept">
                                                    <button type="submit" class="btn-action btn-accept">Accept</button>
                                                </form>
                                                <form method="POST" action="manage_registrations.php<?php echo ($filtered_event_id > 0) ? '?event_id=' . $filtered_event_id : ''; ?>" style="margin: 0;">
                                                    <input type="hidden" name="registration_id" value="<?php echo $row['registration_id']; ?>">
                                                    <input type="hidden" name="action" value="deny">
                                                    <button type="submit" class="btn-action btn-deny">Deny</button>
                                                </form>
                                            </div>
                                        <?php else: ?>
                                            <div style="text-align: center; font-size: 0.85rem; color: #9CA3AF; font-style: italic;">Processed</div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #6B7280; font-weight: 600;">
                        No registration entries match this criteria.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>