<?php
require('load.php'); // Include your database connection
$pdo = $ObjDb->connection();
$Objbalance = new LeaveBalanceTracking($pdo);

// Assuming you have the employee ID from a previous part of your code
$emp_id = 1; // Replace with the actual employee ID you want to fetch

// Fetch the employee name and leave balances
$sql = "SELECT e.emp_name, lb.annual_leave_balance, lb.sick_leave_balance, lb.maternity_leave_balance
        FROM employess e
        JOIN leave_balance lb ON e.emp_id = lb.emp_id
        WHERE e.emp_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $emp_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Prepare data for the chart
if ($result) {
    $employeeName = $result['emp_name'];
    $leaveBalances = [
        'Annual Leave' => $result['annual_leave_balance'],
        'Sick Leave' => $result['sick_leave_balance'],
        'Maternity Leave' => $result['maternity_leave_balance']
    ];
} else {
    // Handle case where employee is not found
    echo "Employee not found.";
    exit;
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Leave Balances Pie Chart for <?php echo htmlspecialchars($employeeName); ?></title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="chart.css">
</head>
<body>
    <canvas id="myChart" width="200" height="150"></canvas>
    <script>
        // Embed PHP data into JavaScript
        var employeeName = "<?php echo htmlspecialchars($employeeName); ?>";
        var leaveBalances = <?php echo json_encode(array_values($leaveBalances)); ?>; // Get values only for chart
        var leaveTypes = <?php echo json_encode(array_keys($leaveBalances)); ?>; // Get keys for labels

        // Calculate total and percentages
        var totalLeave = leaveBalances.reduce((acc, val) => acc + val, 0);
        var percentages = leaveBalances.map(balance => (balance / totalLeave) * 100);

        // Create the pie chart
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: leaveTypes,
                datasets: [{
                    data: percentages,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                    hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Leave Balances for ' + employeeName
                }
            }
        });
    </script>
</body>
</html>
