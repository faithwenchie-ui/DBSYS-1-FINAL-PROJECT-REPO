<?php
session_start();

// From Code 2: Top security verification gateway checkpoint
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: log_in.php");
    exit;
}

// Activity_Overview.php - Structural Queries Evaluation Grid Page Layout Matrix
require_once 'db_connect.php';

$user_role = $_SESSION['role'];

// 1. Core LEFT JOIN Query Execution Process Flow
$left_join_res = $conn->query("SELECT o.org_name, e.event_name FROM organizations o LEFT JOIN events e ON o.organization_id = e.organization_id ORDER BY o.org_name ASC");

// 2. Complex Group By + HAVING Aggregate Optimization Extraction Query Rules Block
$having_res = $conn->query("SELECT m.first_name, m.last_name, COUNT(r.registration_id) AS signups FROM members m INNER JOIN registrations r ON m.member_id = r.member_id GROUP BY m.member_id HAVING signups > 1");

// 3. Nested Isolated Structural Evaluation Subquery Execution Configuration Logic
$subquery_res = $conn->query("SELECT venue_name, building FROM venues WHERE venue_id NOT IN (SELECT DISTINCT venue_id FROM events)");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Activity Overview</title>
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

        /* --- Dashboard Content Block Cards --- */
        .card { 
            background: #ffffff; 
            padding: 30px; 
            border-radius: 16px; 
            margin-bottom: 30px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05); 
        }

        h3 { 
            font-size: 1.25rem;
            margin-top: 0;
            margin-bottom: 20px;
            border-bottom: 2px solid #E2E8F0; 
            padding-bottom: 12px; 
            color: #0F766E; /* Dark Teal Headers */
        }

        /* --- Standardized Structured Tables --- */
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
            background: #0F766E; /* Dark Teal Header Rows */
            color: #ffffff; 
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            color: #374151;
            font-size: 0.95rem;
        }

        /* --- Clean List Styling Configuration --- */
        ul {
            list-style-type: none;
            padding-left: 0;
            margin: 0;
        }

        ul li {
            padding: 12px 16px;
            background: #F8FAFC;
            border-left: 4px solid #14B8A6; /* Primary Accent Strip */
            margin-bottom: 8px;
            border-radius: 0 8px 8px 0;
            color: #374151;
            font-size: 0.95rem;
        }

        /* Metric Accent Text Formatting */
        .success-text {
            color: #10B981; /* Success Green */
            font-weight: 700;
        }

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
            <a href="events_list.php">Events Directory</a>

            <?php if ($user_role === 'admin'): ?>
                <a href="new_event.php">Create New Event</a>
                <a href="Activity_Overview.php" class="active">Organization Activity Overview</a>
                <a href="manage_registrations.php">Review Registerees</a>
            <?php endif; ?>
            <a href="registration.php">Registration Terminal</a>
            <a href="log_in.php" style="margin-top: 20px; border: 1px solid rgba(226, 232, 240, 0.3); text-align: center; color: #FCA5A5;">
            Exit Portal
            </a>
        </div>
    </div>

    <div class="main-content">
        <h2>Organization Activity Overview</h2>
        <p>View a complete directory of registered student organizations and their currently assigned campus activities.</p>

        <div class="card">
            <h3>1. All Registered Groups and Assigned Activities</h3>
            <table>
                <thead>
                    <tr>
                        <th>Organization Name</th>
                        <th>Associated Events</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $left_join_res->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['org_name']); ?></strong></td>
                            <td><?php echo $row['event_name'] ? htmlspecialchars($row['event_name']) : '<em style="color:#9CA3AF;">Zero Program Tracks Published</em>'; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h3>2. High Engagement Sign-ups</h3>
            <table>
                <thead>
                    <tr>
                        <th>Student Identity Name</th>
                        <th>Total Active Verified Forms Registrations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $having_res->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><span class="success-text"><?php echo $row['signups']; ?> Accounts Booked</span></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h3>3. Available Campus Venues</h3>
            <ul>
                <?php if($subquery_res && $subquery_res->num_rows > 0): ?>
                    <?php while($row = $subquery_res->fetch_assoc()): ?>
                        <li>Available Empty Target Location Space asset: <strong><?php echo htmlspecialchars($row['venue_name']); ?></strong> (<?php echo htmlspecialchars($row['building']); ?> Hub Building)</li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li style="color:#6B7280; border-left-color: #6B7280;">All venues are currently booked.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

</body>
</html>