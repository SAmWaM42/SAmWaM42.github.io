<?php
// Include database and leave request classes
include_once 'DatabaseConnection\Database.php';
include_once 'LeaveRequest.php';

// Instantiate database and leave request
$database = new Database();
$db = $database->getConnection();
$leaveRequest = new LeaveRequest($db);

// Assuming parameters are provided in the URL for filtering
$employee_id = isset($_GET['employee_id']) ? $_GET['employee_id'] : null; // Changed to employee_id
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

// Get leave requests with filters
$leaveRequests = $leaveRequest->getLeaveRequests($employee_id, $start_date, $end_date);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leave Requests</title>
    <script>
       // filters.js
// JavaScript functions to filter leave requests by employee ID and start date

function filterByEmployeeId() {
    const employeeId = prompt("Enter Employee ID:");
    if (employeeId) {
        // Redirect to the same page with the employee ID as a query parameter
        window.location.href = `leave_requests.php?employee_id=${encodeURIComponent(employeeId)}`;
    }
}

// Function to filter leave requests by start date
        function filterByStartDate() {
            const startDate = prompt("Enter start date (YYYY-MM-DD):");
            if (startDate) {
                // Redirect to the same page with the start date as a query parameter
                window.location.href = `leave_requests.php?start_date=${encodeURIComponent(startDate)}`;
            }
        }
    </script>
</head>
<body>
    <h1>Leave Requests</h1>
<!-- Add buttons to filter by employee ID and start date -->
    <button onclick="filterByEmployeeId()">Filter by Employee ID</button>
    <button onclick="filterByStartDate()">Filter by Start Date</button>

    <table >
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
</body>
</html>
