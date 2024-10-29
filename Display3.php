<?php
session_start(); // Start the session

// Check if the employee is logged in
if (!isset($_SESSION['employee_id'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Include the Database class
require_once 'DatabaseConnection\Database.php';

// Create a new Database instance and get the connection
$database = new Database();
$conn = $database->getConnection();

// Get the employee ID from the session
$employee_id = $_SESSION['employee_id'];

// SQL query to fetch leave history for the logged-in employee
$sql = "
    SELECT 
        leave_type,
        start_date,
        end_date,
        CASE WHEN approval_status = 1 THEN 'Approved' ELSE 'Pending' END AS approval_status
    FROM 
        EmployeeLeaves
    WHERE 
        employee_id = :employee_id
    ORDER BY 
        start_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave History</title>
    <style>
        table {
            width: 70%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
    </style>
</head>
<body>

<h2>Your Leave History</h2>

<table>
    <thead>
        <tr>
            <th>Leave Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Approval Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($results)): ?>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['leave_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['approval_status']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No leave history found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
