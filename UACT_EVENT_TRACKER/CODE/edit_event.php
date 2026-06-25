<?php
session_start();

// Check authentication first
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: login.php");
    exit;
}

// Hard security checkpoint rule: If they are NOT an admin, trigger styled browser alert and redirect
if ($_SESSION['role'] !== 'admin') {
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Access Denied</title>
        <script type="text/javascript">
            alert("Access Denied: This page is restricted, for administrators only.");
            window.location.href = "index.php";
        </script>
        <style>
            body {
                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                background-color: #F8FAFC;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .alert-box {
                background: white;
                padding: 30px;
                border-radius: 12px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
                border-top: 5px solid #EF4444;
                text-align: center;
                max-width: 450px;
            }
            h2 { color: #991B1B; margin-top: 0; }
            p { color: #374151; font-size: 0.95rem; line-height: 1.5; }
        </style>
    </head>
    <body>
        <div class="alert-box">
            <h2>Security Exception</h2>
            <p>You do not possess structural permissions to view this administrative asset. Redirecting to home terminal...</p>
        </div>
    </body>
    </html>';
    exit;
}

require_once 'db_connect.php';

// edit_event.php - CRUD Update Page with Dual-Layer Verification Safety Boundaries
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id         = intval($_POST['event_id']);
    $event_name = trim($_POST['event_name']);
    $event_type = trim($_POST['event_type']);
    $event_date = $_POST['event_date'];
    $capacity   = intval($_POST['capacity']);
    $status     = trim($_POST['status']);

    if(empty($event_name) || $capacity <= 0) {
        $err = "Server Validation Error: Rejected modification violating structural assertions.";
    } else {
        $update_stmt = $conn->prepare("UPDATE events SET event_name=?, event_type=?, event_date=?, capacity=?, status=? WHERE event_id=?");
        $update_stmt->bind_param("sssisi", $event_name, $event_type, $event_date, $capacity, $status, $id);
        if ($update_stmt->execute()) {
            header("Location: events_list.php");
            exit();
        }
        $update_stmt->close();
    }
}

$select_stmt = $conn->prepare("SELECT * FROM events WHERE event_id = ?");
$select_stmt->bind_param("i", $id);
$select_stmt->execute();
$event = $select_stmt->get_result()->fetch_assoc();
$select_stmt->close();
if (!$event) die("Target model matrix profile identity does not exist.");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Registry Record</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #F8FAFC; /* Background */
            color: #1F2937; /* Text Color */
            margin: 0;
            padding: 50px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .form-card {
            background: #ffffff;
            width: 100%;
            max-width: 550px; /* Made form wider and bigger */
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(15, 118, 110, 0.08), 0 8px 10px -6px rgba(15, 118, 110, 0.04);
            border-top: 6px solid #0F766E; /* Dark Teal top accent line */
            box-sizing: border-box;
        }

        .form-card h3 {
            margin: 0 0 30px 0;
            font-size: 1.6rem;
            color: #0F766E; /* Dark Teal */
            font-weight: 700;
            border-bottom: 2px solid #F1F5F9;
            padding-bottom: 15px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.88rem;
            margin-bottom: 8px;
            color: #1F2937; /* Accent Text */
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px; /* Larger form entry fields */
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            font-size: 1rem;
            background-color: #ffffff;
            color: #1F2937;
            box-sizing: border-box;
            font-family: inherit;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #14B8A6; /* Primary Teal Focus */
            box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.15);
        }

        .btn-commit {
            width: 100%;
            padding: 14px;
            background-color: #14B8A6; /* Primary Teal Button */
            color: white;
            font-size: 1.05rem;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(20, 184, 166, 0.2);
            transition: all 0.2s ease;
        }

        .btn-commit:hover {
            background-color: #0F766E; /* Dark Teal on hover state */
            box-shadow: 0 10px 15px -3px rgba(15, 118, 110, 0.25);
            transform: translateY(-1px);
        }

        .alert-danger {
            background-color: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FCA5A5;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 25px;
        }
    </style>
    <script type="text/javascript">
        function checkConstraints() {
            var title = document.getElementById('event_name').value.trim();
            var size = parseInt(document.getElementById('capacity').value, 10);
            if(title === "") { 
                alert("JS Error: Activity name header is a mandatory required field item."); 
                return false; 
            }
            if(isNaN(size) || size <= 0) { 
                alert("JS Error: Numerical bounds verification failed."); 
                return false; 
            }
            return true;
        }
    </script>
</head>
<body>

    <div class="form-card">
        <h3>Update Activity Profile</h3>

        <?php if (!empty($err)): ?>
            <div class="alert-danger">
                <?php echo htmlspecialchars($err); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="edit_event.php" onsubmit="return checkConstraints();">
            <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
            
            <div class="form-group">
                <label for="event_name">Title Name:</label>
                <input type="text" id="event_name" name="event_name" class="form-control" value="<?php echo htmlspecialchars($event['event_name']); ?>">
            </div>

            <div class="form-group">
                <label for="event_type">Classification Category:</label>
                <input type="text" id="event_type" name="event_type" class="form-control" value="<?php echo htmlspecialchars($event['event_type']); ?>">
            </div>

            <div class="form-group">
                <label for="event_date">Calendar Date:</label>
                <input type="date" id="event_date" name="event_date" class="form-control" value="<?php echo $event['event_date']; ?>">
            </div>

            <div class="form-group">
                <label for="capacity">Max Capacity Bounds:</label>
                <input type="number" id="capacity" name="capacity" class="form-control" value="<?php echo $event['capacity']; ?>">
            </div>

            <div class="form-group">
                <label for="status">Operational Status Track:</label>
                <select id="status" name="status" class="form-control">
                    <option value="scheduled" <?php if($event['status']=='scheduled') echo 'selected'; ?>>Scheduled</option>
                    <option value="completed" <?php if($event['status']=='completed') echo 'selected'; ?>>Completed</option>
                    <option value="cancelled" <?php if($event['status']=='cancelled') echo 'selected'; ?>>Cancelled</option>
                </select>
            </div>

            <button type="submit" class="btn-commit">Save Changes</button>
        </form>
    </div>

</body>
</html>