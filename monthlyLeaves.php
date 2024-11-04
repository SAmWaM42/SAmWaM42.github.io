<?php
require_once 'DatabaseConnection\Database.php';

$database = new Database();
$conn = $database->getConnection();

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
    <title>Monthly Leave Requests - TimeOff</title>
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
            margin-bottom: 2rem;
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

        .content-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: linear-gradient(135deg, #a8c9a1 0%, #86a886 100%);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #1a3a1a;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.8rem;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 5px;
            overflow: hidden;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(26, 58, 26, 0.1);
        }

        th {
            background-color: #1a3a1a;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.95);
        }

        .empty-message {
            text-align: center;
            padding: 2rem;
            color: #666;
            font-style: italic;
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

            .content-container {
                margin: 2rem 1rem;
                padding: 1rem;
            }

            th, td {
                padding: 0.8rem;
                font-size: 0.9rem;
            }
        }
    </style>
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

    <div class="content-container">
        <h2>Monthly Leave Requests</h2>
        <div class="table-container">
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
                            <td colspan="2" class="empty-message">No leave records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>