
<?php
// Include database and leave request classes
include_once 'Database.php';
include_once 'LeaveRequest.php';

// Instantiate database and leave request
$database = new Database();
$db = $database->getConnection();
$leaveRequest = new LeaveRequest($db);

// Assuming parameters are provided in the URL for filtering
$employee_id = isset($_GET['employee_id']) ? $_GET['employee_id'] : null;
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

// Get leave requests with filters
$leaveRequests = $leaveRequest->getLeaveRequests($employee_id, $start_date, $end_date);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimeOff Management - Leave Requests</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
        }

        .navbar {
            background-color: #1a3a1a;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            width: 40px;
            height: 40px;
        }

        .brand-name {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #a8c9a1;
        }

        .filter-container {
            margin: 2rem auto;
            max-width: 800px;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .filter-container button {
            padding: 0.5rem 1rem;
            background-color: #1a3a1a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .filter-container button:hover {
            background-color: #a8c9a1;
        }

        .table-container {
            margin: 2rem auto;
            max-width: 800px;
            background: linear-gradient(135deg, #a8c9a1 0%, #86a886 100%);
            padding: 1rem;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #1a3a1a;
            color: white;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                padding: 1rem;
                gap: 1rem;
            }

            .nav-links {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }

            .filter-container {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
    <script>
        function filterByEmployeeId() {
            const employeeId = prompt("Enter Employee ID:");
            if (employeeId) {
                window.location.href = `leave_requests.php?employee_id=${encodeURIComponent(employeeId)}`;
            }
        }

        function filterByStartDate() {
            const startDate = prompt("Enter start date (YYYY-MM-DD):");
            if (startDate) {
                window.location.href = `leave_requests.php?start_date=${encodeURIComponent(startDate)}`;
            }
        }
    </script>
</head>
<body>
    <nav class="navbar">
        <div class="logo-container">
            <img src="Timeoff[1].jpg" alt="TimeOff Logo" class="logo">
            <span class="brand-name">TimeOff</span>
        </div>
        <div class="nav-links">
        <a href="filterRequests.php">Filter requests</a>
        <a href="LeaveRequest.php">Total leaves in a month</a>
            <a href="#history">Leave History</a>
        </div>
    </nav>

    <div class="filter-container">
        <button onclick="filterByEmployeeId()">Filter by Employee ID</button>
        <button onclick="filterByStartDate()">Filter by Start Date</button>
    </div>

    <div class="table-container">
        <table>
            <tr>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Approval Status</th>
            </tr>
            <?php foreach ($leaveRequests as $leave): ?>
                <tr>
                    <td><?php echo htmlspecialchars($leave['employee_id']); ?></td>
                    <td><?php echo htmlspecialchars($leave['employee_name']); ?></td>
                    <td><?php echo htmlspecialchars($leave['leave_type']); ?></td>
                    <td><?php echo htmlspecialchars($leave['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($leave['end_date']); ?></td>
                    <td><?php echo $leave['approval_status'] ? 'Approved' : 'Pending'; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
