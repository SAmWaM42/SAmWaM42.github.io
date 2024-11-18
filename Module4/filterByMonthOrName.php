<?php
include '../load.php';


$connection = $conn->get_pdo_connection();

try {
    // Retrieve filter values from POST request, defaulting to 0 for month and an empty string for employee name
    $month = isset($_POST['month']) ? (int)$_POST['month'] : 0;
    $employee_name = isset($_POST['employee_name']) ? trim($_POST['employee_name']) : '';

    // Query for retrieving records
    $sql = "SELECT lr.type, lr.employee_ID, lr.start_date, lr.end_date, e.name 
            FROM leave_requests lr 
            JOIN employee e ON lr.employee_ID = e.ID 
            WHERE 1=1";
    if ($month > 0 && $month <= 12) {
        $sql .= " AND month(lr.start_date) = :month";
    }
    if (!empty($employee_name)) {
        $sql .= " AND e.name LIKE :employee_name";
    }
    $stmt = $connection->prepare($sql);
    if ($month > 0 && $month <= 12) {
        $stmt->bindParam(':month', $month, PDO::PARAM_INT);
    }
    if (!empty($employee_name)) {
        $name_param = "%$employee_name%";
        $stmt->bindParam(':employee_name', $name_param, PDO::PARAM_STR);
    }
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch results as associative array

    // Query to count total leaves filtered by month and employee name
    $countSql = "SELECT COUNT(*) 
                 FROM leave_requests lr 
                 JOIN employee e ON lr.employee_ID = e.ID 
                 WHERE 1=1";
    if ($month > 0 && $month <= 12) {
        $countSql .= " AND month(lr.start_date) = :month";
    }
    if (!empty($employee_name)) {
        $countSql .= " AND e.name LIKE :employee_name";
    }
    $countStmt = $connection->prepare($countSql);
    if ($month > 0 && $month <= 12) {
        $countStmt->bindParam(':month', $month, PDO::PARAM_INT);
    }
    if (!empty($employee_name)) {
        $countStmt->bindParam(':employee_name', $name_param, PDO::PARAM_STR);
    }
    $countStmt->execute();
    $leaveCount = $countStmt->fetchColumn();

    // Get the month name for display purposes; defaults to 'All Months' if no specific month is selected
    $monthName = $month > 0 ? date('F', mktime(0, 0, 0, $month, 1)) : 'All Months';
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Leave Summary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
         main{
            width: 90%;
        }
        nav a:hover{
            box-shadow: 0 0 10px 0 rgba(0,0,0,0.7);
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <nav class="navbar">
    <div class="navbar__container">
        <a href="#" id="navbar__logo" color: = "black"> <img src= "Images/Timeoff[1].jpg" width="65px"> TimeOff</a>
        <div class="navbar__toggle" id="mobile-menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
        <ul class="navbar__menu">
            <li class="navbar__item">
                <a href="/" class="navbar__links">
                    Home
                </a>
            </li>
            <li class="navbar__item">
                <a href="/" class="navbar__links">
                    About
                </a>
            </li>
            <li class="navbar__btn">
                <a href="login_employee.php" class="button">
                    Login
                </a>
            </li>
        </ul>
    </div>
</nav>
    <nav class="navbar">
    <div class="navbar__container">
        <a href="#" id="navbar__logo" color: = "black"> <img src= "Images/Timeoff[1].jpg" width="65px"> TimeOff</a>
        <div class="navbar__toggle" id="mobile-menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
        <ul class="navbar__menu">
            <li class="navbar__item">
                <a href="/" class="navbar__links">
                    Home
                </a>
            </li>
            <li class="navbar__item">
                <a href="/" class="navbar__links">
                    About
                </a>
            </li>
            <li class="navbar__btn">
                <a href="login_employee.php" class="button">
                    Login
                </a>
            </li>
        </ul>
    </div>
</nav>
    <nav class="bg-green-500 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <img src="Mod1\Timeoff.jpg" alt="TimeOff Logo" class="w-10 h-10">
                    <span class="text-white text-2xl font-semibold">TimeOff</span>
                </div>
                <div class="flex space-x-4">
                    <a style="background-color:white;color:green;border-radius:10px;" href="filterByNameOrID.php" class="text-white bg-white hover:bg-gray-200 px-3 py-2 rounded-md text-green-600">
                        Search Employee Leave Records
                    </a>
                    <a style="background-color:white;color:green;border-radius:10px;" href="filterByMonthOrName.php" class="text-white bg-white hover:bg-gray-200 px-3 py-2 rounded-md text-green-600">
                        View Monthly Leave Summary
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden p-6">
            <h2 class="text-xl font-semibold mb-6">Filter Leave Records by Month and Name</h2>
            <!-- Filter Form -->
            <form method="POST" class="mb-6 flex items-center space-x-4">
                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700">Select a month:</label>
                    <select name="month" id="month" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                        <option value="0" <?= $month === 0 ? 'selected' : '' ?>>All Months</option>
                        <?php
                        for ($i = 1; $i <= 12; $i++) {
                            $selected = ($i == $month) ? 'selected' : '';
                            echo "<option value=\"$i\" $selected>" . date('F', mktime(0, 0, 0, $i, 1)) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="employee_name" class="block text-sm font-medium text-gray-700">Employee Name:</label>
                    <input style="width:200px" placeholder="Search Employee By Name" type="text" name="employee_name" id="employee_name" value="<?= htmlspecialchars($employee_name) ?>"
                           class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                </div>
                <div class="flex items-end">
                    <button style="transform:translateY(13px);border-radius:0 20px 20px 0;" type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-500 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Search
                    </button>
                </div>
            </form>
            <!-- Display Month and Leave Count -->
            <div style="transform:translateX(800px); border-radius:20px 0 0 20px;padding-left:40px;" class="mb-4 bg-green-500">
                <p class="font-medium text-left">Selected Month: <span class=""><?= htmlspecialchars($monthName) ?></span></p>
                <p class="font-medium">Total Leaves in Month: <span class=""><?= htmlspecialchars($leaveCount) ?></span></p>
            </div>
            <!-- Table Display -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse bg-white shadow-md rounded-lg">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Employee ID</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Employee Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Leave Type</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Start Date</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">End Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (count($rows) > 0): ?>
                            <?php foreach ($rows as $row): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row["employee_ID"]); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row["name"]); ?></td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <?php echo htmlspecialchars($row["type"]); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row["start_date"]); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row["end_date"]); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">No records found for this month</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
