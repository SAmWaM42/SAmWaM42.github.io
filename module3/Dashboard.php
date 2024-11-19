<?php
// Include database connection and LeaveBalanceTracking class
 // Assuming load.php initializes $pdo connection
require_once('../load.php');
require_once('leavebalancetracking.php');
require_once('retrieve.php');

$pdo=$conn->get_pdo_connection();

$Objretrieve = new Retrieve($pdo);
$Objbalance=new leavebalancetracking($pdo);

// Sample employee ID for display (replace with session variable or dynamic ID in a real app)
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: ../Mod1/login_employee.php');
    exit();
}
$user_id = $_SESSION['user_id']; // Ideally, this should come from session or user input
$leaveType = $Objretrieve ->getLeavetype($user_id);

// Fetch employee info
$employeeInfo = $Objretrieve->getEmployeeInfo($user_id);

// Fetch leave balances
$leaveBalances = $Objretrieve->getLeaveBalances($user_id);

// Fetch leave requests
$leaveRequests = $Objretrieve->getLeaveRequests($user_id);
$Objbalance->findBalance($user_id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Balance Dashboard</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="../Mod1/stylee.css">
    
</head>
<body>
    <?php 
        $includes ->nav_bar();
        $includes ->inner_nav("matter");
        ?>

<!---
    <div class="sidebar">
        <div class="menu-item"><i class="icon-dashboard">🏠</i></div>
        <div class="menu-item"><i class="icon-profile">👤</i></div>
        <div class="menu-item"><i class="icon-settings">⚙️</i></div>
        <div class="menu-item"><a href="../module2/requests.php"><i class="icon-leave-request">📅</i></a></div>
        <div class="menu-item"><a href="../leave_status.php"><i class="icon-leave-status">📋</i> </a></div>
        <div class="menu-item"><a href="../admin_statistics.php"><i class="icon-statistics">📊</i> </a></div>
        -//add 4 more buttons set to direct to leave_request,leave_status,admin_statistics
        //add a display condition on admin statistics using js to ensure only HR and higher have this button available
        //comment the functions of your pages so people know which one to use for what 
        //special conditions do not subtract
    </div>-->

    <!-- Main Content Area -->
    <div class="main-content">
        <h1>Leave Balance Dashboard</h1>
        
        <!-- Employee Info -->
        <div class="employee-info">
            <p><strong>Employee Name:</strong> <?php echo htmlspecialchars($employeeInfo['name']); ?></p>
        </div>

        <!-- Leave Requests and Approvals Section -->
        <div class="leave-requests">
            <h2>Leave Requests and Approvals</h2>
            <?php foreach ($leaveRequests as $request): ?>
                <div class="leave-request">
                    <?php echo htmlspecialchars($request['type']); ?> - 
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
            <div class="leave-category">Compassionate Leave</div>
            <a href="#" class="see-all">See All</a>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div class="right-sidebar">
        <!-- Remaining Days -->
        <div class="remaining-days">
            <h3>Remaining Days</h3>
            <?php 
            if($leaveBalances!=null)
            {
            foreach ($leaveBalances as $type => $balance): ?>
                <p><strong><?php echo ucfirst($type); ?>:</strong> <?php echo htmlspecialchars($balance); ?> days</p>
            <?php endforeach;
            }
            else
            {
               ?>
             <p>No leave days taken yet</p>
            
            <?php
            }
             ?>
            <button>Request Leave</button>
        </div>

        <!-- Historical Data -->
         
        <div class="historical-data">
            <h3>Historical Data</h3>
            <p>14 days</p><!-- sample days-->
            <button>View History</button>
        </div>
            

        <button class="apply-leave">Apply for Leave</button>
    </div>
</div>

</body>
</html>
