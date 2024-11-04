<?php
require_once 'DatabaseConnection\Database.php'; // Include your Database class

// Create a new Database instance and get the connection
$database = new Database();
$conn = $database->getConnection();

// SQL query to fetch leave counts by month
$sql = "
    WITH RECURSIVE MonthIntervals AS (
        SELECT 
            employee_id,
            DATE_FORMAT(start_date, '%Y-%m') AS month,
            start_date,
            end_date
        FROM 
            EmployeeLeaves
        WHERE 
            approval_status = TRUE
        
        UNION ALL
        
        SELECT 
            employee_id,
            DATE_FORMAT(DATE_ADD(start_date, INTERVAL 1 MONTH), '%Y-%m') AS month,
            DATE_ADD(start_date, INTERVAL 1 MONTH),
            end_date
        FROM 
            MonthIntervals
        WHERE 
            DATE_ADD(start_date, INTERVAL 1 MONTH) <= end_date
    )
    SELECT 
        month,
        COUNT(*) AS number_of_requests
    FROM 
        MonthIntervals
    GROUP BY 
        month
    ORDER BY 
        month;
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Leave Requests</title>
    <style>
        table {
            width: 50%;
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

<h2>Monthly Leave Requests</h2>

<table>
    <thead>
        <tr>
            <th>Month</th>
            <th>Number of Requests</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($results)): ?>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['month']); ?></td>
                    <td><?php echo htmlspecialchars($row['number_of_requests']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="2">No leave records found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
