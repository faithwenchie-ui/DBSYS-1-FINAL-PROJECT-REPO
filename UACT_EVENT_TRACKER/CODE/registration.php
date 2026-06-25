<?php
session_start();

// From Code 2: Top security verification gateway checkpoint
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: log_in.php");
    exit;
}

// registration.php - Registration Terminal Form & Processor
require_once 'db_connect.php';

$user_role = $_SESSION['role']; 

$message = '';
$message_type = '';

// Handle Registration Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize basic text strings
    $student_name = isset($_POST['student_name']) ? $conn->real_escape_string(trim($_POST['student_name'])) : '';
    $year_level   = isset($_POST['year_level']) ? $conn->real_escape_string(trim($_POST['year_level'])) : '';
    $course       = isset($_POST['course']) ? $conn->real_escape_string(trim($_POST['course'])) : '';
    $phone_number = isset($_POST['phone_number']) ? $conn->real_escape_string(trim($_POST['phone_number'])) : '';
    
    // Validate email formatting
    $school_email = isset($_POST['school_email']) ? trim($_POST['school_email']) : '';
    $event_id     = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;

    if (empty($student_name) || empty($year_level) || empty($course) || empty($school_email) || empty($phone_number) || $event_id === 0) {
        $message = "All validation entry parameters must be filled entirely.";
        $message_type = "error";
    } elseif (!filter_var($school_email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please input a valid School Email Address format structure.";
        $message_type = "error";
    } else {
        $school_email = $conn->real_escape_string($school_email);
        
        // Example Insertion Query (Adjust table/column names to match your precise schema)
        $sql = "INSERT INTO registrations (student_name, year_level, course, school_email, phone_number, event_id) 
                VALUES ('$student_name', '$year_level', '$course', '$school_email', '$phone_number', $event_id)";
        
        if ($conn->query($sql)) {
            $message = "Registration entry counter matrix submitted successfully!";
            $message_type = "success";
        } else {
            $message = "Operational terminal system execution error: " . $conn->error;
            $message_type = "error";
        }
    }
}

// Fetch active events for the Target Event Activity Title dropdown selector
$events_sql = "SELECT event_id, event_name FROM events WHERE status != 'cancelled' ORDER BY event_name ASC";
$events_result = $conn->query($events_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAct Event Tracker - Registration Terminal</title>
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

        /* --- Form Card Component Structure --- */
        .form-card { 
            background: #ffffff; 
            max-width: 100%;
            padding: 35px; 
            border-radius: 16px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05); 
        }

        .form-card h3 { 
            font-size: 1.4rem; 
            margin-top: 0; 
            margin-bottom: 25px; 
            color: #1F2937; 
            border-bottom: 2px solid #E2E8F0; 
            padding-bottom: 12px; 
        }

        .form-group { 
            margin-bottom: 20px; 
        }

        .form-group label { 
            display: block; 
            font-weight: 600; 
            margin-bottom: 8px; 
            color: #374151; 
            font-size: 0.9rem; 
        }

        .form-control { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #CBD5E1; 
            border-radius: 8px; 
            font-size: 0.95rem; 
            box-sizing: border-box;
            font-family: inherit;
            color: #1F2937;
            background-color: #ffffff;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-control:focus { 
            border-color: #14B8A6; /* Primary Teal Accent Focus Frame */
        }

        .form-row { 
            display: flex; 
            gap: 20px; 
        }

        .form-row .form-group { 
            flex: 1; 
        }

        /* --- Action Submission Button --- */
        .submit-btn { 
            width: 100%; 
            padding: 14px; 
            background: #14B8A6; /* Primary Teal */
            color: #ffffff; 
            border: none; 
            cursor: pointer; 
            border-radius: 8px; 
            font-weight: 600; 
            font-size: 1rem;
            box-shadow: 0 4px 6px -1px rgba(20, 184, 166, 0.2);
            transition: background 0.2s;
        }

        .submit-btn:hover { 
            background: #0F766E; /* Dark Teal Hover Override */
        }

        /* --- System Banner Component Messaging Elements --- */
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

        /* Responsive Breakpoints */
        @media (max-width: 992px) {
            body { flex-direction: column; }
            .sidebar { position: relative; width: 100%; }
            .main-content { margin-left: 0; padding: 20px; }
            .form-row { flex-direction: column; gap: 0; }
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
                <a href="Activity_Overview.php">Organization Activity Overview</a>
                <a href="manage_registrations.php">Review Registerees</a>
            <?php endif; ?>

            <a href="registration.php" class="active">Registration Terminal</a>
            <a href="log_in.php" style="margin-top: 20px; border: 1px solid rgba(226, 232, 240, 0.3); text-align: center; color: #FCA5A5;">
            Exit Portal
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="brand-header">
            <h2>Registration Terminal</h2>
            <p>Register for upcoming university events, workshops, and seminars.</p>
        </div>

        <div class="form-card">
            <h3>Event Registration Form Submission</h3>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $message_type === 'success' ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="registration.php">
                <div class="form-group">
                    <label for="student_name">Student Name</label>
                    <input type="text" id="student_name" name="student_name" class="form-control" placeholder="First Name Last Name" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="year_level">Year Level</label>
                        <select id="year_level" name="year_level" class="form-control" required>
                            <option value="" disabled selected>Select Level...</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="course">Course</label>
                        <input type="text" id="course" name="course" class="form-control" placeholder="e.g., BSCS" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="school_email">School Email Address</label>
                    <input type="email" id="school_email" name="school_email" class="form-control" placeholder="username@university.edu" required>
                </div>

                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" id="phone_number" name="phone_number" class="form-control" placeholder="e.g., 09123456789" required>
                </div>

                <div class="form-group">
                    <label for="event_id">Target Event Activity Title</label>
                    <select id="event_id" name="event_id" class="form-control" required>
                        <option value="" disabled selected>Select target event</option>
                        <?php if ($events_result && $events_result->num_rows > 0): ?>
                            <?php while($event = $events_result->fetch_assoc()): ?>
                                <option value="<?php echo $event['event_id']; ?>">
                                    <?php echo htmlspecialchars($event['event_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <option value="" disabled>No active operations found in logs</option>
                        <?php endif; ?>
                    </select>
                </div>

                <button type="submit" class="submit-btn">Register</button>
            </form>
        </div>
    </div>

</body>
</html>