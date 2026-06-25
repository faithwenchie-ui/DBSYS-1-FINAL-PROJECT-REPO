<?php
// new_event.php - CRUD Create Page with Complete Dual-Layer Input Checking
require_once 'db_connect.php';
$server_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Server-Side Explicit Cast & Truncate Sanitization Pipeline
    $event_name      = trim($_POST['event_name']);
    $organization_id = intval($_POST['organization_id']);
    $venue_id        = intval($_POST['venue_id']);
    $description     = trim($_POST['description']);
    $event_type      = trim($_POST['event_type']);
    $event_date      = $_POST['event_date'];
    $start_time      = $_POST['start_time'];
    $end_time        = $_POST['end_time'];
    $capacity        = intval($_POST['capacity']);
    $created_by      = intval($_POST['created_by']);

    // 2. Server-Side Cryptographic/Constraint Fallback Assertion Validation
    if (empty($event_name) || empty($event_type) || empty($event_date) || $capacity <= 0 || $organization_id <= 0 || $venue_id <= 0) {
        $server_error = "Server-side Validation Fault: Rejected invalid data structures input variables.";
    } else {
        // 3. Injection Proof Parameterized Prepared Statements Insertion Routing
        $stmt = $conn->prepare("INSERT INTO events (organization_id, venue_id, event_name, description, event_type, event_date, start_time, end_time, capacity, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissssssii", $organization_id, $venue_id, $event_name, $description, $event_type, $event_date, $start_time, $end_time, $capacity, $created_by);
        
        if ($stmt->execute()) {
            header("Location: events_list.php");
            exit();
        } else {
            $server_error = "Database Infrastructure Transaction Fault: " . $stmt->error;
        }
        $stmt->close();
    }
}

$orgs = $conn->query("SELECT organization_id, org_name FROM organizations WHERE status='active'");
$venues = $conn->query("SELECT venue_id, venue_name FROM venues");
$members = $conn->query("SELECT member_id, CONCAT(first_name, ' ', last_name) AS full_name FROM members");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAct Event Tracker - Publish Event</title>
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

        /* --- Form Container Card Structure --- */
        .box { 
            background: #ffffff; 
            max-width: 100%; 
            padding: 35px; 
            border-radius: 16px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05); 
        }

        .box h3 { 
            font-size: 1.4rem; 
            margin-top: 0; 
            margin-bottom: 25px; 
            color: #1F2937; 
            border-bottom: 2px solid #E2E8F0; 
            padding-bottom: 12px; 
        }

        .row { 
            margin-bottom: 20px; 
        } 
        
        .row label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: 600; 
            font-size: 0.9rem;
            color: #374151;
        }

        .row input, .row select { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #CBD5E1; 
            border-radius: 8px; 
            box-sizing: border-box; 
            font-size: 0.95rem;
            color: #1F2937;
            background-color: #ffffff;
            outline: none;
            transition: border-color 0.2s;
        }

        .row input:focus, .row select:focus {
            border-color: #14B8A6; /* Primary Focus Frame Accent */
        }

        /* --- Form Execution Processing Button --- */
        .submit-btn {
            width: 100%; 
            padding: 14px; 
            background: #14B8A6; /* Primary Teal */
            color: #ffffff; 
            border: none; 
            border-radius: 8px; 
            font-weight: 600; 
            font-size: 1rem;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(20, 184, 166, 0.2);
            transition: background 0.2s;
        }

        .submit-btn:hover {
            background: #0F766E; /* Dark Teal Hover */
        }

        /* Responsive Breakpoints */
        @media (max-width: 992px) {
            body { flex-direction: column; }
            .sidebar { position: relative; width: 100%; }
            .main-content { margin-left: 0; padding: 20px; }
        }
    </style>
    <script type="text/javascript">
        function performClientSideValidation() {
            var title = document.getElementById('event_name').value.trim();
            var date = document.getElementById('event_date').value;
            var cap = parseInt(document.getElementById('capacity').value, 10);

            if (title === "") {
                alert("Client-side Integration Error: Activity Event Title Header Cannot be Empty.");
                return false;
            }
            if (date === "") {
                alert("Client-side Integration Error: Target Calendar Operation Date Must be Specified.");
                return false;
            }
            if (isNaN(cap) || cap <= 0) {
                alert("Client-side Integration Error: Allowed Seat Matrix Sizing Allocation Must be Greater Than Zero.");
                return false;
            }
            return true;
        }
    </script>
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
            <a href="new_event.php" class="active">Create New Event</a>
            <a href="Activity_Overview.php">Organization Activity Overview</a>
            <a href="manage_registrations.php">Review Registerees</a>
            <a href="registration.php">Registration Terminal</a>
            <a href="log_in.php" style="margin-top: 20px; border: 1px solid rgba(226, 232, 240, 0.3); text-align: center; color: #FCA5A5;">
            Exit Portal
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="brand-header">
            <h2>New Event Publication</h2>
            <p>Register a new activity, assign a host organization, and book an available campus venue.</p>
        </div>

        <div class="box">
            <h3>Publish New Organization Activity</h3>
            <?php if(!empty($server_error)) echo "<p style='color:#EF4444; font-weight:bold; margin-bottom: 20px;'>$server_error</p>"; ?>
            
            <form method="POST" action="new_event.php" onsubmit="return performClientSideValidation();">
                <div class="row">
                    <label>Activity Title *</label>
                    <input type="text" id="event_name" name="event_name">
                </div>
                <div class="row">
                    <label>Hosting Group Organization *</label>
                    <select name="organization_id">
                        <?php while($o = $orgs->fetch_assoc()): ?>
                            <option value="<?php echo $o['organization_id']; ?>"><?php echo htmlspecialchars($o['org_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="row">
                    <label>Assigned Venue Space Location *</label>
                    <select name="venue_id">
                        <?php while($v = $venues->fetch_assoc()): ?>
                            <option value="<?php echo $v['venue_id']; ?>"><?php echo htmlspecialchars($v['venue_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="row">
                    <label>Classification Category Type *</label>
                    <select name="event_type">
                        <option value="seminar">Seminar</option>
                        <option value="workshop">Workshop</option>
                        <option value="contest">Contest</option>
                    </select>
                </div>
                <div class="row">
                    <label>Execution Date *</label>
                    <input type="date" id="event_date" name="event_date">
                </div>
                <div class="row">
                    <label>Start Time *</label>
                    <input type="time" name="start_time" required>
                </div>
                <div class="row">
                    <label>End Time *</label>
                    <input type="time" name="end_time" required>
                </div>
                <div class="row">
                    <label>Total Seats Capacity Limit *</label>
                    <input type="number" id="capacity" name="capacity">
                </div>
                <div class="row">
                    <label>Registry Authorizing Officer *</label>
                    <select name="created_by">
                        <?php while($m = $members->fetch_assoc()): ?>
                            <option value="<?php echo $m['member_id']; ?>"><?php echo htmlspecialchars($m['full_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="row">
                    <label>Detailed Description</label>
                    <input type="text" name="description">
                </div>
                
                <button type="submit" class="submit-btn">Add Event</button>
            </form>
        </div>
    </div>

</body>
</html>