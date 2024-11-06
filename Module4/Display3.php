<?php
session_start();
// if (!isset($_SESSION['employee_id'])) {
//     header("Location: login1.php");
//     exit();
// }

require_once 'Database.php';
$database = new Database();
$conn = $database->getConnection();
$employee_id =19;

$sql = "
    SELECT 
        leave_type,
        start_date,
        end_date,
        CASE WHEN approval_status = 1 THEN 'Approved' ELSE 'Pending' END AS approval_status
    FROM 
        employeeleaves
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
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        main {
            width: 90%;
        }
        nav a:hover {
            box-shadow: 0 0 10px 0 rgba(0,0,0,0.7);
        }
        .table-container {
            max-height: 220px;
            overflow-y: auto;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        .table-container thead {
            position: sticky;
            top: 0;
            z-index: 1;
            background-color: #f3f4f6;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Navigation Bar -->
    <nav class="bg-green-500 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <img src="Timeoff[1].jpg" alt="TimeOff Logo" class="w-10 h-10">
                    <span class="text-white text-2xl font-semibold">TimeOff</span>
                </div>
                <div class="flex space-x-4">
                    <a style="background-color:white;color:green;border-radius:10px;" href="filterByNameOrID.php" class="text-green-600 bg-white hover:bg-gray-200 px-3 py-2 rounded-md">
                        Search Employee Leave Records
                    </a>
                    <a style="background-color:white;color:green;border-radius:10px;" href="filterByMonthAndName.php" class="text-green-600 bg-white hover:bg-gray-200 px-3 py-2 rounded-md">
                        View Monthly Leave Summary
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden p-6">
            <h2 class="text-xl font-semibold mb-6">Your Leave History</h2>

            <!-- Display total leave count -->
            <div style="transform:translateX(800px); border-radius:20px 0 0 20px;padding-left:40px;" class="mb-4 bg-green-500">
                <p class="font-medium text-white">Total Leave Records: <span class="font-bold"><?= count($results) ?></span></p>
            </div>

            <!-- Table Display -->
            <div class="table-container overflow-x-auto">
                <table class="w-full border-collapse bg-white shadow-md rounded-lg">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Leave Type</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Start Date</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">End Date</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (!empty($results)): ?>
                            <?php foreach ($results as $row): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-200">
                                            <?php echo htmlspecialchars($row['leave_type']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['start_date']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['end_date']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['approval_status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">No leave history found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>