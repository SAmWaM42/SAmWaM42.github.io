<?php
// Include database connection and LeaveBalanceTracking class
require_once('load.php'); // Assuming load.php initializes $pdo connection
require_once('leavebalancetracking.php');
require_once('retrieve.php');

$Objretrieve = new Retrieve($pdo);

// Sample employee ID for display (replace with session variable or dynamic ID in a real app)
$emp_id = 1; // Ideally, this should come from session or user input
$leaveType = $Objretrieve ->getLeavetype($emp_id);

// Fetch employee info
$employeeInfo = $Objretrieve->getEmployeeInfo($emp_id);

// Fetch leave balances
$leaveBalances = $Objretrieve->getLeaveBalances($emp_id);

// Fetch leave requests
$leaveRequests = $Objretrieve->getLeaveRequests($emp_id);
$Objbalance->findBalance($emp_id, $leaveType);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Balance Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard">
    <!-- Sidebar Menu -->
    <div class="sidebar">
        <div class="menu-item"><i class="icon-dashboard">🏠</i></div>
        <div class="menu-item"><i class="icon-profile">👤</i></div>
        <div class="menu-item"><i class="icon-settings">⚙️</i></div>
        //add 4 more buttons set to direct to leave_request,leave_status,admin_statistics
        //add a display condition on admin statistics using js to ensure only HR and higher have this button available
        //comment the functions of your pages so people know which one to use for what 
        //special conditions do not subtract
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <h1>Leave Balance Dashboard</h1>
        
        <!-- Employee Info -->
        <div class="employee-info">
            <p><strong>Employee Name:</strong> <?php echo htmlspecialchars($employeeInfo['emp_name']); ?></p>
        </div>

        <!-- Leave Requests and Approvals Section -->
        <div class="leave-requests">
            <h2>Leave Requests and Approvals</h2>
            <?php foreach ($leaveRequests as $request): ?>
                <div class="leave-request">
                    <?php echo htmlspecialchars($request['leave_type']); ?> - 
                    <?php echo htmlspecialchars($request['status']); ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Leave Categories Section -->
        <div class="leave-categories">
            <h2>Leave Categories</h2>
            <div class="leave-category">Annual Leave</div>
            <div class="leave-category">Sick Leave</div>
            <div class="leave-category">Maternity Leave</div>
            <a href="#" class="see-all">See All</a>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div class="right-sidebar">
        <!-- Remaining Days -->
        <div class="remaining-days">
            <h3>Remaining Days</h3>
            <?php foreach ($leaveBalances as $type => $balance): ?>
                <p><strong><?php echo ucfirst($type); ?>:</strong> <?php echo htmlspecialchars($balance); ?> days</p>
            <?php endforeach; ?>
            <button>Request Leave</button>
        </div>

        <!-- Historical Data -->
         <form action="history.php">
        <div class="historical-data">
            <h3>Historical Data</h3>
            <p>14 days</p>
            <button>View History</button>
        </div>
            </form>

        <button class="apply-leave">Apply for Leave</button>
    </div>
</div>

</body>
</html>
