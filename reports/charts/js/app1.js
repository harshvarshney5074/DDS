function loadInstitutionChart(date1 = '', date2 = '') {
    $.ajax({
        url: "./data1.php",
        method: "GET",
        data: { date1: date1, date2: date2 },
        success: function(data) {
            const labels = [];
            const sendCounts = [];
            const receiveCounts = [];

            data.forEach(row => {
                labels.push(row.institute_name);
                sendCounts.push(row.send_count);
                receiveCounts.push(row.receive_count);
            });

            const chartData = {
                labels: labels,
                datasets: [
                    {
                        label: 'Send Count',
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        data: sendCounts
                    },
                    {
                        label: 'Receive Count',
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        data: receiveCounts
                    }
                ]
            };

            const ctx = $('#mycanvas1');
            if (window.myInstitutionChart) {
                window.myInstitutionChart.destroy();
            }
            window.myInstitutionChart = new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Top 10 Institutes by Receive Count'
                        }
                    },
                    scales: {
                        x: { stacked: false },
                        y: { beginAtZero: true }
                    }
                }
            });
        },
        error: function(err) {
            console.error("Chart load error:", err);
        }
    });
}

$(document).ready(function() {
    // Initial load
    loadInstitutionChart();

    $('#applyFilter').click(function() {
        const date1 = $('#date1').val();
        const date2 = $('#date2').val();
        if (date1 && date2) {
            loadInstitutionChart(date1, date2);
        } else {
            alert("Please select both dates.");
        }
    });

    $('#clearFilter').click(function() {
        $('#date1').val('');
        $('#date2').val('');
        loadInstitutionChart();
    });

    $('#currentMonth').click(function() {
        const now = new Date();
        const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
        const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);

        const date1 = firstDay.toISOString().split('T')[0];
        const date2 = lastDay.toISOString().split('T')[0];

        $('#date1').val(date1);
        $('#date2').val(date2);

        loadInstitutionChart(date1, date2);
    });
});
