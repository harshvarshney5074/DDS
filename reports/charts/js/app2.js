$(document).ready(function () {
    let chart2 = null;

    function fetchJournalChart(date1 = '', date2 = '') {
        $.ajax({
            url: './data2.php',
            method: 'POST',
            data: {
                date1: date1,
                date2: date2
            },
            success: function (data) {
                const labels = data.map(row => row.Journal_name);
                const counts = data.map(row => row.Count);

                const chartdata = {
                    labels: labels,
                    datasets: [{
                        label: 'Article Count',
                        backgroundColor: 'rgba(0,76,153,0.75)',
                        borderColor: 'rgba(0,76,153,0.75)',
                        hoverBackgroundColor: 'rgba(0,76,153,1)',
                        hoverBorderColor: 'rgba(0,76,153,1)',
                        data: counts
                    }]
                };

                if (chart2) chart2.destroy();

                const ctx = $('#mycanvas2');
                chart2 = new Chart(ctx, {
                    type: 'bar',
                    data: chartdata
                });
            },
            error: function (xhr) {
                console.error('Error loading journal data', xhr);
            }
        });
    }

    // Initial load with no filters
    fetchJournalChart();

    // Apply button
    $('#journal_btn_apply').on('click', function () {
        const d1 = $('#journal_date1').val();
        const d2 = $('#journal_date2').val();
        if (d1 && d2) {
            fetchJournalChart(d1, d2);
        }
    });

    // Current month button
    $('#journal_btn_month').on('click', function () {
        const now = new Date();
        const first = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().slice(0, 10);
        const last = new Date(now.getFullYear(), now.getMonth() + 1, 0).toISOString().slice(0, 10);
        $('#journal_date1').val(first);
        $('#journal_date2').val(last);
        fetchJournalChart(first, last);
    });

    // Clear filters
    $('#journal_btn_clear').on('click', function () {
        $('#journal_date1').val('');
        $('#journal_date2').val('');
        fetchJournalChart();
    });

});
