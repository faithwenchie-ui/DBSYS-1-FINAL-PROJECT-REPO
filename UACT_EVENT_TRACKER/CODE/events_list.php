<?php
session_start();

// From Code 2: Top security verification gateway checkpoint
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: log_in.php");
    exit;
}
// events_list.php - Core Event Directory with Search Filter & Inner Joins
require_once 'db_connect.php';

$user_role = $_SESSION['role']; 

// Server-side input sanitization processing via real_escape_string
$search = isset($_GET['search']) ? $conn->real_escape_string(trim($_GET['search'])) : '';

// INNER JOIN implementation drawing explicit alphanumeric values across foreign key targets
$sql = "SELECT e.event_id, e.event_name, e.event_type, e.event_date, e.status, 
               o.org_name, v.venue_name 
        FROM events e
        INNER JOIN organizations o ON e.organization_id = o.organization_id
        INNER JOIN venues v ON e.venue_id = v.venue_id";

if ($search !== '') {
    $sql .= " WHERE e.event_name LIKE '%$search%' OR o.org_name LIKE '%$search%'";
}
$sql .= " ORDER BY e.event_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAct Event Tracker - Event Registry</title>
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

        /* --- Left Sidebar Styling --- */
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
            background: #14B8A6; /* Primary */
        }

        .nav-bar a.active { 
            color: #ffffff; 
            background: #14B8A6; /* Primary */
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(20, 184, 166, 0.2);
        }

        /* --- Main Content Layout --- */
        .main-content {
            flex: 1;
            margin-left: 280px; 
            padding: 40px;
            box-sizing: border-box;
        }

        h2 { 
            font-size: 2rem; 
            margin-top: 0; 
            margin-bottom: 5px; 
            color: #1F2937; 
        }

        p { 
            color: #6B7280; 
            margin-top: 0; 
            margin-bottom: 30px; 
            font-size: 1rem; 
        }

        /* --- Search Block Customization --- */
        .search-container {
            margin-bottom: 25px; 
            display: flex; 
            gap: 10px;
        }

        .search-input { 
            padding: 12px 16px; 
            width: 320px; 
            border: 1px solid #CBD5E1; 
            border-radius: 8px; 
            font-size: 0.95rem; 
            background-color: #ffffff;
            color: #1F2937;
            outline: none;
            transition: border-color 0.2s;
        }

        .search-input:focus {
            border-color: #14B8A6; /* Primary Focus Frame */
        }

        .search-btn { 
            padding: 12px 24px; 
            background: #14B8A6; /* Primary */
            color: white; 
            border: none; 
            cursor: pointer; 
            border-radius: 8px; 
            font-weight: 600; 
            font-size: 0.95rem;
            transition: background 0.2s;
        }

        .search-btn:hover {
            background: #0F766E; /* Dark Teal Hover */
        }

        /* --- Modernized Core Table Element --- */
        .table-card {
            background: #ffffff; 
            border-radius: 16px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            padding: 10px;
            overflow: hidden;
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            text-align: left; 
        }

        th, td { 
            padding: 16px; 
            border-bottom: 1px solid #E2E8F0; 
        }

        th { 
            background-color: #0F766E; /* Dark Teal */
            color: white; 
            text-transform: uppercase; 
            font-size: 0.85rem; 
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        td {
            color: #374151;
            font-size: 0.95rem;
        }

        /* --- Row Management Tool Actions --- */
        .btn { 
            display: inline-block;
            padding: 8px 14px; 
            text-decoration: none; 
            border-radius: 6px; 
            color: white; 
            font-size: 0.85rem; 
            font-weight: 600; 
            text-align: center;
            white-space: nowrap;
            transition: opacity 0.2s;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .btn-edit { background-color: #14B8A6; } /* Linked clean teal option style */
        .btn-delete { background-color: #EF4444; } /* Vibrant Alert Crimson */

        /* Responsive Breakpoints */
        @media (max-width: 992px) {
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
            <a href="events_list.php" class="active">Events Directory</a>
            <?php if ($user_role === 'admin'): ?>
                <a href="new_event.php">Create New Event</a>
                <a href="Activity_Overview.php">Organization Activity Overview</a>
                <a href="manage_registrations.php">Review Registerees</a>
            <?php endif; ?>
            <a href="registration.php">Registration Terminal</a>
            <a href="log_in.php" style="margin-top: 20px; border: 1px solid rgba(226, 232, 240, 0.3); text-align: center; color: #FCA5A5;">
            Exit Portal
            </a>
        </div>
    </div>

    <div class="main-content">
        <h2>University Events Directory</h2>
        <p>Manage and browse all university events, schedules, and locations.</p>

        <form method="GET" action="events_list.php" class="search-container">
            <input type="text" name="search" class="search-input" placeholder="Search by title or organization..." value="<?php echo htmlspecialchars($search); ?>">
            <input type="submit" value="Filter Search" class="search-btn">
        </form>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Event Title Name</th>
                        <th>Hosting Organization</th>
                        <th>Assigned Venue Location</th>
                        <th>Classification</th>
                        <th>Scheduled Date</th>
                        <th>Status</th>
                        <th>Management Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['event_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['org_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['venue_name']); ?></td>
                                <td><?php echo htmlspecialchars(ucfirst($row['event_type'])); ?></td>
                                <td><?php echo htmlspecialchars($row['event_date']); ?></td>
                                <td>
                                    <?php 
                                        // Dynamically handle specific system execution colors states
                                        $status_color = '#F59E0B'; // Scheduled Warning Amber
                                        if ($row['status'] == 'completed') $status_color = '#10b94b'; // Success Green
                                        if ($row['status'] == 'cancelled') $status_color = '#EF4444'; // Red Crimson
                                    ?>
                                    <span style="font-weight: 700; color: <?php echo $status_color; ?>;">
                                        <?php echo strtoupper($row['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit_event.php?id=<?php echo $row['event_id']; ?>" class="btn btn-edit">Modify</a>
                                    <a href="delete_event.php?id=<?php echo $row['event_id']; ?>" class="btn btn-delete" onclick="return confirm('Permanently remove this entry record item from core tables?');">Remove</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" style="text-align:center; color:#6B7280; padding: 30px;">No operational log items matched parameters bounds.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>