<?php
session_start();
require_once "../load.php";
class history {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    public function getEmployeeInfo($emp_id) {
        $sql = "SELECT name FROM employee WHERE ID = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$emp_id]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result; // Employee found
        } else {
            // Handle case where no employee is found
            return null; // Or throw an exception
        }
    }

    // Fetch leave balances for the employee
    public function getLeaveBalances($emp_id) {
        $sql = "SELECT annual_leave_balance, sick_leave_balance, maternity_leave_balance FROM leave_balance WHERE emp_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$emp_id]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result; // Leave balances found
        } else {
            // Handle case where no leave balances are found
            return null; // Or throw an exception
        }
    }

    // Fetch leave requests for the employee
    public function getLeaveRequests($emp_id) {
        $sql = "SELECT leave_type, status FROM leave_requests WHERE employee_ID = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$emp_id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Will return an empty array if no requests found
    }
    public function getLeavetype($emp_id){
        $sql = "SELECT type FROM leave_requests WHERE employee_ID = ?";
        $stmt = $this ->pdo ->prepare($sql);
        $stmt->execute([$emp_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTotalDaysTaken($emp_id) {
        $sql = "SELECT DATEDIFF(end_date, start_date) AS days_taken FROM leave_record WHERE employee_ID = ? ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(1, $emp_id, PDO::PARAM_INT);

        $stmt->execute();
    
        // Fetch all the records
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        
        $totalDaysTaken = 0;
    
        // Iterate over all the results and sum up the days_taken
        foreach ($results as $row) {
            $totalDaysTaken += $row['days_taken'];  // Add the days_taken for each record
        }
    
        return $totalDaysTaken;
    }
    public function getDaysAvailable() {
        // SQL query to fetch leave types and available days
        $sql2 = "SELECT name, days FROM type_values";
        $stmt2 = $this->pdo->prepare($sql2);
        $stmt2->execute();
    
        // Fetch all leave types and their available days as an associative array
        $leaveTypes = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
        // Initialize a variable to sum the total available days
        $totalDaysAvailable = 0;
    
        // Iterate over the leave types and sum the available days
        foreach ($leaveTypes as $row) {
            $totalDaysAvailable += $row['days']; // Add the 'days' value to the total
        }
    
        // Optionally return or output the total available days
        return $totalDaysAvailable; // Return the total available days
    }
    public function getTypes($emp_id) {
        // Prepare the SQL statement to fetch employee name and leave balances
        $sql = "SELECT e.name, lb.annual_leave_balance, lb.sick_leave_balance, lb.maternity_leave_balance, lb.paternity_leave_balance, lb.compassionate_leave_balance
                FROM employee e
                JOIN leave_balance lb ON e.ID = lb.emp_id
                WHERE e.ID = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(1, $emp_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            $employeeName = $result['name'];
            $leaveBalances = [
                'Annual Leave' => $result['annual_leave_balance'],
                'Sick Leave' => $result['sick_leave_balance'],
                'Maternity Leave' => $result['maternity_leave_balance'],
                'Paternity Leave' => $result['paternity_leave_balance'],
                'Compassionate Leave' => $result['compassionate_leave_balance']
            ];
    
            // Output the HTML and JavaScript for the chart
            echo '<div class="sick_stats">
                    <h2 class="chart_heading">Type of Leave</h2>
                    <div class="chart_container">
                        <canvas id="leaveChart" width="400" height="400" aria-label="Type of leave" role="img"></canvas>
                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                var employeeName = "' . htmlspecialchars($employeeName) . '";
                                var leaveBalances = ' . json_encode(array_values($leaveBalances)) . '; // Get values only for chart
                                var leaveTypes = ' . json_encode(array_keys($leaveBalances)) . '; // Get keys for labels
    
                                // Extract leave balances for the chart
                                var sickdays = leaveBalances[1]; // Assuming Sick Leave is at index 1
                                var maternity = leaveBalances[2]; // Assuming Maternity Leave is at index 2
                                var paternity = leaveBalances[3]; // Assuming Paternity Leave is at index 3
                                var compassionate = leaveBalances[4]; // Assuming Compassionate Leave is at index 4
    
                                // Create the pie chart
                                const ctx = document.getElementById("leaveChart").getContext("2d");
                                
                                const myChart = new Chart(ctx, {
                                    type: "pie",
                                    data: {
                                        labels: ["Sick Days", "Maternity Leave", "Paternity Leave", "Compassionate Leave"],
                                        datasets: [{
                                            data: [sickdays, maternity, paternity, compassionate],
                                            backgroundColor: [
                                                "rgba(255, 99, 13, 0.6)", // Red for Sick days
                                                "rgba(54, 162, 235, 0.6)", // Blue for Maternity Leave
                                                "rgba(255, 0, 0, 0.6)", // Black for Paternity Leave
                                                "rgba(0, 255, 0, 0.6)" // Green for Compassionate leave
                                            ],
                                            borderColor: [
                                                "rgba(255, 99, 132, 1)",
                                                "rgba(54, 162, 235, 1)",
                                                "rgba(255, 0, 0, 0.6)",
                                                "rgba(0, 255, 0, 0.6)"
                                            ],
                                            borderWidth: 1
                                        }],
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: {
                                            legend: {
                                                display: true,
                                                position: "top",
                                            },
                                            tooltip: {
                                                callbacks: {
                                                    label: function(tooltipItem) {
                                                        return tooltipItem.label + ": " + tooltipItem.raw + " days";
                                                    }
                                                }
                                            }
                                        }
                                    }
                                });
                            });
                        </script>
                    </div>
    
                    <div class="details">
                        <ul>
                            <li>Sick Days remaining: <span class="sickdaysRemaining percentage"></span></li>
                            <li>Sick Days taken: <span class="sickdaysTaken percentage"></span></li>
                        </ul>
                    </div>
                </div>';
        } else {
            // Handle case where employee is not found
            echo "Employee not found.";
        }
    }
    
    

    
    
}
$his=new history($conn->get_pdo_connection());
    $daysAvailable = $his->getDaysAvailable(); // Fetch available leave days

// Get the total days taken (example for 'Annual Leave')
     // You can dynamically set this value
    $daysTaken = $his->getTotalDaysTaken(
        
        $_SESSION["user_id"]
    );
 $Objlbt->assignBalance();

   

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Days Chart</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <h2>Leave Days Chart</h2>
    <div class="chart-container">
        <canvas id="leaveChart" width="400" height="400"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Pass PHP values to JavaScript
            var daysAvailable = <?php echo $daysAvailable; ?>;
            var daysTaken = <?php echo $daysTaken; ?>;

            // Calculate remaining days
            var daysRemaining = daysAvailable - daysTaken;

            const ctx = document.getElementById('leaveChart').getContext('2d');

            const leaveChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Days Taken', 'Days Remaining'], // Labels for the pie chart
                    datasets: [{
                        data: [daysTaken, daysRemaining], // Data for the pie chart
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)', // Red for Days Taken
                            'rgba(54, 162, 235, 0.6)'  // Blue for Days Remaining
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw + ' days';
                                }
                            }
                        }
                    }
                }
            });

            // Optional: Updating details in the HTML (for example if you want to show them as text)
            document.querySelector('#daysRemaining').textContent = 'Days Remaining: ' + daysRemaining;
            document.querySelector('#daysTaken').textContent = 'Days Taken: ' + daysTaken;
        });
    </script>
            
        </div>

        <div class="details">
            <ul>
                <li>Days remaining: <span class="percentage"></span></li>
                <li>Days taken: <span class="percentage"></span></li>
            </ul>
        </div>
    </div>
   
</div>
<?php 
    $his->getTypes($_SESSION["user_id"]);
    ?>
  
    <!--<div class="sick_stats">
        <h2 class="chart_heading">Type of Leave</h2>
        <div class="chart_container">
            <canvas class="my_chart2" width="400" height="400" aria-label=" Type of leave" role="img"></canvas>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    
                    var employeeName = "<?php echo htmlspecialchars($employeeName); ?>";
                    var leaveBalances = <?php echo json_encode(array_values($leaveBalances)); ?>; // Get values only for chart
                    var leaveTypes = <?php echo json_encode(array_keys($leaveBalances)); ?>; // Get keys for labels

    // Create the pie chart
    const ctx = document.getElementById('leaveChart').getContext('2d');
    
    const myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: [ 'Sick Days ', 'maternity','paternity' ,'Compassionate'],
            datasets: [{
                data: [annual , sickdays ,maternity, paternity],
                backgroundColor: [
                    'rgba(255, 99, 13, 2, 0.6)', // Red for Sick days
                    'rgba(54, 162, 235, 0.6)', // Blue for Maternity Leave
                   ' rgba(255, 0, 0, 0.6)',//Black for Prternity leave
                   'rgba(0, 255, 0, 0.6)'//Green for Compassionate leave


                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 0, 0, 0.6)',
                    'rgba(0, 255, 0, 0.6)'


                ]
            }],
                borderWidth: 1
            
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw + ' days';
                        }
                    }
                }
            }
        }
    });

    // Update the details in the HTML
    document.querySelector('sickdaysRemaining').textContent = sickdaysRemaining;
    document.querySelector('sickdaysTaken').textContent = sickdaysTaken;
});

            </script>
        </div>

        <div class="details">
            <ul>
                <li>Sick Days remaining: <span class="percentage"></span></li>
                <li>Sick Days taken: <span class="percentage"></span></li>
            </ul>
        </div>
    </div>-->
</div>
</div>
</body>
</html>