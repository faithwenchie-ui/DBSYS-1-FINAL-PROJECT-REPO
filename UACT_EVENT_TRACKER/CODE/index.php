<?php
// index.php - Integrated System Performance Command Center Dashboard Hub (Combined Code 1 & 2)
session_start();

// From Code 2: Top security verification gateway checkpoint
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: log_in.php");
    exit;
}

require_once 'db_connect.php';

// From Code 2: Store role in a quick variable for UI checks
$user_role = $_SESSION['role']; 

// From Code 1: 1. Live Aggregate Count Query: Calculate aggregate total user population
$member_query = $conn->query("SELECT COUNT(member_id) AS total_users FROM members");
$total_members = ($member_query) ? $member_query->fetch_assoc()['total_users'] : 0;

// From Code 1: 2. Live Group By Aggregate Query: Dynamically compile state classification tracking metrics
$group_by_res = $conn->query("SELECT status, COUNT(*) as tracking_count FROM events GROUP BY status");

// From Code 1: 3. Database VIEW Utilization: Load insights directly from virtual relation view schema layer
// Added o.event_id selection constraint to explicitly map targets to filter parameters
$view_query_sql = "SELECT event_id, event_name, org_name, total_registrations FROM most_attended_events ORDER BY total_registrations DESC LIMIT 4";
$view_res = $conn->query($view_query_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAct Event Tracker - Command Dashboard</title>
    <style>
        /* From Code 1: Master CSS Layout System Engine Stylesheet Definitions */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #F8FAFC; /* Updated Background */
            margin: 0; 
            padding: 0; 
            color: #1F2937; /* Updated Text Color */
            display: flex; 
            min-height: 100vh;
        }

        /* --- Left Sidebar Styling --- */
        .sidebar {
            width: 280px;
            background: #0F766E; /* Updated to Dark Teal */
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

        /* Nav links updated to match new palette */
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
            background: #14B8A6; /* Updated to Primary Teal */
        }

        .nav-bar a.active { 
            color: #ffffff; 
            background: #14B8A6; /* Updated to Primary Teal */
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

        .main-content h1 { 
            font-size: 2rem; 
            margin-top: 0; 
            margin-bottom: 5px; 
            color: #1F2937;  
        }

        .main-content .subtitle { 
            color: #6B7280; 
            margin-top: 0; 
            margin-bottom: 30px; 
            font-size: 1rem;
        }

        .grid-layout { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); 
            gap: 20px; 
            margin-bottom: 35px; 
        }

        .stat-card { 
            background: #ffffff; 
            padding: 25px; 
            border-radius: 12px; 
            text-align: center; 
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
            border-top: 5px solid #14B8A6; /* Updated to Primary Teal default border */
            transition: transform 0.2s, box-shadow 0.2s; 
        }

        .stat-card:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
        }

        .stat-card h3 { 
            color: #6B7280; 
            margin: 0 0 10px 0; 
            font-size: 0.85rem; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
        }

        .stat-card .value { 
            font-size: 1.8rem; 
            font-weight: 700; 
            color: #1F2937; /* Updated text color */
        }

        .table-card { 
            background: #ffffff; 
            border-radius: 16px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            padding: 25px; 
            margin-top: 20px; 
        }

        .table-card h2 { 
            font-size: 1.3rem; 
            margin-top: 0; 
            margin-bottom: 20px; 
            color: #1F2937; /* Updated text color */
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            text-align: left; 
        }

        th, td { 
            padding: 14px 16px; 
            border-bottom: 1px solid #E2E8F0; 
        } 

        th { 
            background-color: #0F766E; /* Updated Table Header to Dark Teal */
            color: #ffffff; 
            font-size: 0.85rem; 
            text-transform: uppercase; 
            font-weight: 600; 
            letter-spacing: 0.5px;
        }

        td {
            color: #374151;
        }

        .badge-counter { 
            background: #CCFBF1; /* Soft teal background for metrics */
            color: #0F766E; /* Dark teal font */
            padding: 6px 12px; 
            border-radius: 20px; 
            font-weight: 600; 
            font-size: 0.85rem; 
            display: inline-block; 
            transition: all 0.2s ease;
        }

        .badge-counter:hover {
            background: #14B8A6;
            color: #ffffff;
            box-shadow: 0 2px 4px rgba(20, 184, 166, 0.2);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
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
            <a href="index.php" class="active">Dashboard Home</a>
            
            <a href="events_list.php">Events Directory</a>
            
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
        <h1>UAct Event Tracker</h1>
        <p class="subtitle">Logged in as: <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong> (<?php echo ucfirst($user_role); ?> Mode)</p>

        <div class="grid-layout">
            <div class="stat-card" style="border-top-color: #10B981;"> 
                <h3>Total Campus Reach</h3>
                <div class="value"><?php echo htmlspecialchars($total_members); ?> Members</div>
            </div>
            <?php if ($group_by_res && $group_by_res->num_rows > 0): ?>
                <?php while($row = $group_by_res->fetch_assoc()): ?>
                    <?php 
                        $color_map = ['scheduled' => '#14B8A6', 'completed' => '#10B981', 'cancelled' => '#EF4444'];
                        $border_color = isset($color_map[strtolower($row['status'])]) ? $color_map[strtolower($row['status'])] : '#6B7280';
                    ?>
                    <div class="stat-card" style="border-top-color: <?php echo $border_color; ?>;">
                        <h3>Status: <?php echo htmlspecialchars(ucfirst($row['status'])); ?> Events</h3>
                        <div class="value"><?php echo htmlspecialchars($row['tracking_count']); ?> Records</div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

        <div class="table-card">
            <h2>Top Attended Campus Activities</h2>
            <table>
                <thead>
                    <tr>
                        <th>Target Event Activity Title</th>
                        <th>Hosting Student Organization Body</th>
                        <th>Total Signups</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($view_res && $view_res->num_rows > 0): ?>
                        <?php while($v_row = $view_res->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($v_row['event_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($v_row['org_name']); ?></td>
                                <td>
                                    <?php if ($user_role === 'admin'): ?>
                                        <a href="manage_registrations.php?event_id=<?php echo urlencode($v_row['event_id']); ?>" class="badge-counter" style="text-decoration: none; border: 1px solid #14B8A6;">
                                            <?php echo htmlspecialchars($v_row['total_registrations']); ?> Confirmed Attendees 🔍
                                        </a>
                                    <?php else: ?>
                                        <span class="badge-counter">
                                            <?php echo htmlspecialchars($v_row['total_registrations']); ?> Confirmed Attendees
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3" style="text-align:center; color: #6B7280; padding: 30px;">No metrics returned.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>