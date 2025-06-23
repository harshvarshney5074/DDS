$(document).ready(function () {
  let disciplineChart;

  function loadDisciplineChart(date1 = '', date2 = '') {
    $.ajax({
      url: './data5.php',
      method: 'POST',
      data: {
        discipline_date1: date1,
        discipline_date2: date2
      },
      dataType: 'json',
      success: function (data) {
        const labels = data.map(item => item.Discipline);
        const counts = data.map(item => item.count);

        const chartData = {
          labels: labels,
          datasets: [{
            label: 'Discipline Count',
            backgroundColor: 'rgba(0,76,153,0.75)',
            borderColor: 'rgba(0,76,153,1)',
            hoverBackgroundColor: 'rgba(0,76,153,0.9)',
            hoverBorderColor: 'rgba(0,76,153,1)',
            data: counts
          }]
        };

        const ctx = document.getElementById('mycanvas5').getContext('2d');

        if (disciplineChart) disciplineChart.destroy();
        disciplineChart = new Chart(ctx, {
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

  // Initial load (all data)
  loadDisciplineChart();

  $('#discipline_btn_apply').on('click', function () {
    const d1 = $('#discipline_date1').val();
    const d2 = $('#discipline_date2').val();
    loadDisciplineChart(d1, d2);
  });

  $('#discipline_btn_month').on('click', function () {
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0];
    const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0).toISOString().split('T')[0];
    $('#discipline_date1').val(firstDay);
    $('#discipline_date2').val(lastDay);
    loadDisciplineChart(firstDay, lastDay);
  });

  $('#discipline_btn_clear').on('click', function () {
    $('#discipline_date1').val('');
    $('#discipline_date2').val('');
    loadDisciplineChart();
  });
});
