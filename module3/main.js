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
