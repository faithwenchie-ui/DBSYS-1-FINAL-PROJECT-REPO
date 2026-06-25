<?php
// Members page. Displays the member list and supports search filtering.
// Requires administrator access and loads members from the database.

require_once 'admin_check.php';
require_once 'db_connect.php';

// Search term is optionally supplied via query string.
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {

    // Use a prepared statement to search name, email, or phone safely.
    $search_like = "%" . $search . "%";

    $sql = "SELECT * FROM members
            WHERE full_name LIKE ?
            OR email LIKE ?
            OR phone LIKE ?
            ORDER BY member_id ASC";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("sss", $search_like, $search_like, $search_like);

    $stmt->execute();

    $result = $stmt->get_result();

} else {

    $sql = "SELECT * FROM members ORDER BY member_id ASC";

    $result = $conn->query($sql);

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Members</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="layout">

        <aside class="sidebar">
            <div class="sidebar-nav">

                <a href="index.php" class="dashboard-link">
                    Dashboard
                </a>

                <div class="dropdown">
                    <div class="dropdown-title" onclick="toggleMenu('memberMenu', this)">
                        ▶ MEMBER MANAGEMENT
                    </div>

                    <div class="dropdown-content" id="memberMenu">
                        <a href="members.php" class="active">
                            Members
                        </a>

                        <a href="enrollments.php">
                            Enrollments
                        </a>

                        <a href="enroll_member_form.php">
                            Enroll Member
                        </a>
                    </div>
                </div>

                <div class="dropdown">
                    <div class="dropdown-title" onclick="toggleMenu('classMenu', this)">
                        ▶ CLASS MANAGEMENT
                    </div>

                    <div class="dropdown-content" id="classMenu" style="display:none;">
                        <a href="classes.php">
                            Classes
                        </a>
                    </div>
                </div>

                <div class="dropdown">
                    <div class="dropdown-title" onclick="toggleMenu('attendanceMenu', this)">
                        ▶ ATTENDANCE
                    </div>

                    <div class="dropdown-content" id="attendanceMenu" style="display:none;">
                        <a href="attendance_records.php">
                            Attendance Log
                        </a>

                        <a href="attendance_form.php">
                            Mark Attendance
                        </a>
                    </div>
                </div>

                <div class="dropdown">
                    <div class="dropdown-title" onclick="toggleMenu('reportsMenu', this)">
                        ▶ REPORTS
                    </div>

                    <div class="dropdown-content" id="reportsMenu" style="display:none;">
                        <a href="class_summary.php">
                            Class Summary
                        </a>

                        <a href="most_popular_class.php">
                            Most Popular
                        </a>

                        <a href="popular_classes.php">
                            Popular Classes
                        </a>

                        <a href="member_enrollment_report.php">
                            Member Report
                        </a>
                    </div>
                </div>

                <div class="dropdown">
                    <div class="dropdown-title" onclick="toggleMenu('accountMenu', this)">
                        ▶ ACCOUNT
                    </div>

                    <div class="dropdown-content" id="accountMenu" style="display:none;">
                        <a href="logout.php" onclick="return showConfirmAction(event, 'Are you sure you want to log out?', 'logout.php');">
                            Logout
                        </a>
                    </div>
                </div>

            </div>
        </aside>

        <main class="main-content">
            <div class="top-bar">

                <div>

                    <h1>Gym Members List</h1>

                    <p>
                        Manage all registered gym members.
                    </p>

                </div>

                <a href="add_member_form.php"
                class="add-btn">
                + Add Member
                </a>

            </div>

            <form method="GET"
                class="search-form"
                action="members.php">

                <input
                    type="text"
                    name="search"
                    placeholder="Search name, email, or phone..."
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">

                <button type="submit">
                    Search
                </button>

                <a href="members.php"
                class="action-btn danger-btn">
                    Clear
                </a>

            </form>

            <table>
                <tr>
                    <th>Member ID</th>
                    <th>Full Name</th>
                    <th>Email Address</th>
                    <th>Phone No.</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>

                <?php
                // Check if member records exist before rendering rows.
                if ($result->num_rows > 0) {

                    // Loop through each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['member_id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                        echo "<td class='action-cell'>";

                        echo "<a class='action-btn'href='edit_member_form.php?id=" .$row['member_id'] ."'>Edit</a>";
                        echo "<form method='POST' action='php/delete_member.php' style='display:inline;' onsubmit='return showConfirmDelete(event, \"Are you sure you want to delete this member?\", this)'>";
                        echo "<input type='hidden' name='id' value='" . (int)$row['member_id'] . "'>";
                        echo "<button type='submit' class='action-btn danger-btn'>Delete</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }

                } else {
                    echo "<tr>";
                    echo "<td colspan='6'>No members found.</td>";
                    echo "</tr>";
                }
                ?>
            </table>

        </main>
    </div>
<script src="js/sidebar.js"></script>
</body>
</html>