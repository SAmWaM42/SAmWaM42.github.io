<?php
class retrieve {
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Days Chart</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
</head>
<body>
    <div class="all">
<div class="Anual">
    <h2 class="chart_heading">Leave Days</h2>
    <div class="leave_stats">
        <div class="chart_container">
            <canvas class="my_chart" width="400" height="400" aria-label="Leave days Chart" role="img"></canvas>
            <script> 
            document.addEventListener('DOMContentLoaded', () => {
    const totalDays = 21;
const daysTaken = 10;
const daysRemaining = totalDays - daysTaken;
    const ctx = document.querySelector('.my_chart').getContext('2d');

    // Create the pie chart
    const myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Days Taken', 'Days Remaining'],
            datasets: [{
                data: [daysTaken, daysRemaining],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)', // Red for Days Taken
                    'rgba(54, 162, 235, 0.6)' // Blue for Days Remaining
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

    // Update the details in the HTML
    document.querySelector('days Remaining').textContent = daysRemaining;
    document.querySelector('daysTaken').textContent = daysTaken;
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
  
    <div class="sick_stats">
        <h2 class="chart_heading">Type of Leave</h2>
        <div class="chart_container">
            <canvas class="my_chart2" width="400" height="400" aria-label=" Type of leave" role="img"></canvas>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const sicktotalDays = 15;
const sickdaysTaken = 8;
maternity = 8;
    // Create the pie chart
    const myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: [' Sick Days Taken', 'Sick Days Remaining', 'maternity'],
            datasets: [{
                data: [sicktotalDays, sickdaysTaken, maternity],
                backgroundColor: [
                    'rgba(255, 99, 13, 2, 0.6)', // Red for Days Taken
                    'rgba(54, 162, 235, 0.6)', // Blue for Days Remaining
                   ' rgba(255, 0, 0, 0.6)'//Black

                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 0, 0, 0.6)'

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
    </div>
</div>
</div>
</body>
</html>