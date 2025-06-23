$(document).ready(function () {
  let docChart;

  function loadDocChart(date1 = '', date2 = '') {
    $.ajax({
      url: './data3.php',
      method: 'POST',
      data: {
        doc_date1: date1,
        doc_date2: date2
      },
      dataType: 'json',
      success: function (data) {
        const labels = data.map(item => item.Document_type);
        const counts = data.map(item => item.count);

        const chartData = {
          labels: labels,
          datasets: [{
            label: 'Document-type Count',
            backgroundColor: 'rgba(0,76,153,0.75)',
            borderColor: 'rgba(0,76,153,1)',
            hoverBackgroundColor: 'rgba(0,76,153,0.9)',
            hoverBorderColor: 'rgba(0,76,153,1)',
            data: counts
          }]
        };

        const ctx = document.getElementById('mycanvas3').getContext('2d');

        if (docChart) docChart.destroy(); // destroy previous instance
        docChart = new Chart(ctx, {
          type: 'bar',
          data: chartData,
          options: {
            responsive: true,
            plugins: {
              legend: { display: false }
            },
            scales: {
              x: {
                ticks: { autoSkip: false }
              },
              y: {
                beginAtZero: true
              }
            }
          }
        });
      }
    });
  }

  // Initial load for current month
	loadDocChart();	
  // Apply button
  $('#doc_btn_apply').on('click', function () {
    const d1 = $('#doc_date1').val();
    const d2 = $('#doc_date2').val();
    loadDocChart(d1, d2);
  });

  // Current Month
  $('#doc_btn_month').on('click', function () {
	const now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0];
    const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0).toISOString().split('T')[0];
    $('#doc_date1').val(firstDay);
    $('#doc_date2').val(lastDay);
    loadDocChart(firstDay, lastDay);
  });

  // Clear Filters
  $('#doc_btn_clear').on('click', function () {
    $('#doc_date1').val('');
    $('#doc_date2').val('');
    loadDocChart();
  });
});
