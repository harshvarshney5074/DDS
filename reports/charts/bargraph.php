<?php 
include('dbcon.php');
session_start();

if (isset($_POST['user_submit'])) {
    $date1 = $_POST['date1'];
    $date2 = $_POST['date2'];
    $date_condition = "Req_date BETWEEN '$date1' AND '$date2'";
    $custom_date = true;
} else {
    $date_condition = "MONTH(Req_date) = MONTH(CURRENT_DATE()) AND YEAR(Req_date) = YEAR(CURRENT_DATE())";
    $custom_date = false;
}

// Query 1: Category-wise & Status-wise counts
$query = "
    SELECT Category, Status, COUNT(*) as count
    FROM entry
    WHERE $date_condition
    GROUP BY Category, Status
";
$result = mysqli_query($conn, $query);

// Store counts in associative array
$counts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $cat = $row['Category'];
    $status = $row['Status'];
    $counts[$cat][$status] = $row['count'];
}

// Query 2: Status-wise total counts (all categories)
$query_total = "
    SELECT Status, COUNT(*) as count
    FROM entry
    WHERE $date_condition
    GROUP BY Status
";
$result2 = mysqli_query($conn, $query_total);
$status_counts = [];
while ($row = mysqli_fetch_assoc($result2)) {
    $status_counts[$row['Status']] = $row['count'];
}

// Extract values safely
function getCount($arr, $cat, $status) {
    return isset($arr[$cat][$status]) ? $arr[$cat][$status] : 0;
}

function getTotalCategory($arr, $cat) {
    return isset($arr[$cat]) ? array_sum($arr[$cat]) : 0;
}

// Students
$R1 = getTotalCategory($counts, 'Student');
$R2 = getCount($counts, 'Student', 'Pending');
$R3 = getCount($counts, 'Student', 'Approached');
$R4 = getCount($counts, 'Student', 'Complete');
$R5 = getCount($counts, 'Student', 'Closed');

// Faculty
$S1 = getTotalCategory($counts, 'Faculty');
$S2 = getCount($counts, 'Faculty', 'Pending');
$S3 = getCount($counts, 'Faculty', 'Approached');
$S4 = getCount($counts, 'Faculty', 'Complete');
$S5 = getCount($counts, 'Faculty', 'Closed');

// Other Institutions
$T1 = getTotalCategory($counts, 'Other Institution');
$T2 = getCount($counts, 'Other Institution', 'Pending');
$T3 = getCount($counts, 'Other Institution', 'Approached');
$T4 = getCount($counts, 'Other Institution', 'Complete');
$T5 = getCount($counts, 'Other Institution', 'Closed');

// Corporate Members
$U1 = getTotalCategory($counts, 'Corporate Member');
$U2 = getCount($counts, 'Corporate Member', 'Pending');
$U3 = getCount($counts, 'Corporate Member', 'Approached');
$U4 = getCount($counts, 'Corporate Member', 'Complete');
$U5 = getCount($counts, 'Corporate Member', 'Closed');

// Institute Members
$V1 = getTotalCategory($counts, 'Institute Member');
$V2 = getCount($counts, 'Institute Member', 'Pending');
$V3 = getCount($counts, 'Institute Member', 'Approached');
$V4 = getCount($counts, 'Institute Member', 'Complete');
$V5 = getCount($counts, 'Institute Member', 'Closed');

// Staff
$P1 = getTotalCategory($counts, 'Staff');
$P2 = getCount($counts, 'Staff', 'Pending');
$P3 = getCount($counts, 'Staff', 'Approached');
$P4 = getCount($counts, 'Staff', 'Complete');
$P5 = getCount($counts, 'Staff', 'Closed');

// Indvidual Membership
$W1 = getTotalCategory($counts, 'Individual Membership');
$W2 = getCount($counts, 'Individual Membership', 'Pending');
$W3 = getCount($counts, 'Individual Membership', 'Approached');
$W4 = getCount($counts, 'Individual Membership', 'Complete');
$W5 = getCount($counts, 'Individual Membership', 'Closed');

// Alumni
$X1 = getTotalCategory($counts, 'Alumni');
$X2 = getCount($counts, 'Alumni', 'Pending');
$X3 = getCount($counts, 'Alumni', 'Approached');
$X4 = getCount($counts, 'Alumni', 'Complete');
$X5 = getCount($counts, 'Alumni', 'Closed');

// Overall Status Counts (all categories)
$count1 = $status_counts['Pending'] ?? 0;
$count2 = $status_counts['Approached'] ?? 0;
$count3 = $status_counts['Complete'] ?? 0;
$count4 = $status_counts['Closed'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery 3.7.1 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        #chart-container {
            width: 100%;
            height: auto;
        }
		body {
			background-color: lightblue;
		}
    </style>

    <!-- Google Chart: Status Pie -->
    <script src="https://www.gstatic.com/charts/loader.js"></script>
	<script>
		$(document).ready(function () {
			google.charts.load('current', { 'packages': ['corechart'] });
			google.charts.setOnLoadCallback(drawStatusChart);

			// Initial draw with no filters
			function drawStatusChart(date1, date2) {
				$.ajax({
					url: 'status_chart_data.php',
					method: 'POST',
					data: { status_date1: date1, status_date2: date2 },
					dataType: 'json',
					success: function (data) {
					const chartData = google.visualization.arrayToDataTable([
						['Status', 'Count'],
						['Pending',    data.Pending ?? 0],
						['Approached', data.Approached ?? 0],
						['Received',   data.Received ?? 0],
						['Complete',   data.Complete ?? 0],
						['Closed',     data.Closed ?? 0],
					]);

					const options = {
						is3D: true,
						chartArea: { width: '90%', height: '80%' }
					};

					const chart = new google.visualization.PieChart(document.getElementById('piechart'));
					chart.draw(chartData, options);
					}
				});
			}

			// Handle Apply
			$('#status_chart_filters').on('click', function (e) {
				e.preventDefault();
				const date1 = $('#status_date1').val();
				const date2 = $('#status_date2').val();
				drawStatusChart(date1, date2);
			});

			// Handle Current Month
			$('#status_current_month').on('click', function () {
				const now = new Date();
				const firstDay = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0];
				const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0).toISOString().split('T')[0];
				$('#status_date1').val(firstDay);
				$('#status_date2').val(lastDay);
				drawStatusChart(firstDay, lastDay);
			});

			// Handle Clear
			$('#status_clear_filters').on('click', function () {
				$('#status_date1').val('');
				$('#status_date2').val('');
				drawStatusChart();
			});
		})
	</script>

	<script type="text/javascript">
		$(document).ready(function () {
		google.charts.load('current', { packages: ['corechart'] });
		google.charts.setOnLoadCallback(drawCategoryChart);

		function drawCategoryChart(date1 = '', date2 = '') {
			console.log("category chart");
			$.ajax({
			url: 'category_chart_data.php',
			type: 'POST',
			data: {
				category_date1: date1,
				category_date2: date2
			},
			dataType: 'json',
			success: function (response) {
				const chartData = new google.visualization.DataTable();
				chartData.addColumn('string', 'Category');
				chartData.addColumn('number', 'Queries');
				chartData.addRows(response);

				const options = {
				is3D: true,
				chartArea: { width: '90%', height: '80%' }
				};

				const chart = new google.visualization.PieChart(document.getElementById('piechart1'));
				chart.draw(chartData, options);
			}
			});
		}

		$('#category_chart_filters').on('click', function () {
			const date1 = $('#category_date1').val();
			const date2 = $('#category_date2').val();
			drawCategoryChart(date1, date2);
		});

		$('#category_current_month').on('click', function () {
			const now = new Date();
			const firstDay = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0];
			const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0).toISOString().split('T')[0];
			$('#category_date1').val(firstDay);
			$('#category_date2').val(lastDay);
			drawCategoryChart(firstDay, lastDay);
		});

		$('#category_clear_filters').on('click', function () {
			$('#category_date1').val('');
			$('#category_date2').val('');
			drawCategoryChart();
		});
		});
	</script>

</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
	<div class="container-fluid">
		<a class="navbar-brand" href="../../home.php">Home</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" title="navbar-toggler">
		<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
		<ul class="navbar-nav me-auto mb-2 mb-lg-0">
			<li class="nav-item"><a class="nav-link" href="../../index.php">Entries</a></li>
			<?php if($_SESSION['type']=='0' || $_SESSION['type']=='1'){ ?>
			<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
				Manage
			</a>
			<ul class="dropdown-menu">
				<li><a class="dropdown-item" href="../../institutions/index.php">Institutions</a></li>
				<li><a class="dropdown-item" href="../../journal/index.php">Document Sources</a></li>
				<li><a class="dropdown-item" href="../../patrons/index.php">Patrons</a></li>
			</ul>
			</li>
			<li class="nav-item"><a class="nav-link" href="../../orders.php">Requests</a></li>
			<?php } ?>
			<li class="nav-item"><a class="nav-link" href="../../reports/index.php">Reports</a></li>
			<?php if($_SESSION['type']=='0'){ ?>
			<li class="nav-item"><a class="nav-link" href="../../users/index.php">Users</a></li>
			<?php } ?>
		</ul>
		<ul class="navbar-nav ms-auto">
			<?php if(isset($_SESSION['uid'])){ ?>
			<li class="nav-item"><a class="nav-link">User <?php echo $_SESSION['uid']; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="../../logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
			<?php } ?>
		</ul>
		</div>
	</div>
	</nav>
<br><br><br>
<h1 class="text-center mb-4">DDS Statistics</h1>
<div class="container mt-4">
  <div class="row g-4">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">Report based on Institutions (Top 10)</div>
        <div class="card-body">
			<div class="row mb-3 align-items-end">
				<div class="col-auto">
					<label for="date1" class="form-label">From</label>
					<input type="date" id="date1" class="form-control form-control-sm">
				</div>
				<div class="col-auto">
					<label for="date2" class="form-label">To</label>
					<input type="date" id="date2" class="form-control form-control-sm">
				</div>
				<div class="col-auto">
					<button id="applyFilter" class="btn btn-primary btn-sm">Apply</button>
					<button id="currentMonth" class="btn btn-info btn-sm">Current Month</button>
					<button id="clearFilter" class="btn btn-secondary btn-sm">Clear Filters</button>
				</div>
			</div>
          <div id="chart-container"><canvas id="mycanvas1"></canvas></div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">Report based on Journals (Top 10)</div>
        <div class="card-body">
			<div class="row mb-3 align-items-end">
				<div class="col-auto">
					<label for="journal_date1" class="form-label mb-1">From:</label>
					<input type="date" id="journal_date1" class="form-control form-control-sm">
				</div>
				<div class="col-auto">
					<label for="journal_date2" class="form-label mb-1">To:</label>
					<input type="date" id="journal_date2" class="form-control form-control-sm">
				</div>
				<div class="col-auto">
					<button id="journal_btn_apply" class="btn btn-primary btn-sm">Apply</button>
					<button id="journal_btn_month" class="btn btn-info btn-sm">Current Month</button>
					<button id="journal_btn_clear" class="btn btn-secondary btn-sm">Clear Filters</button>
				</div>
			</div>
          <div id="chart-container"><canvas id="mycanvas2"></canvas></div>
        </div>
      </div>
    </div>
  </div>
  <div class="row g-4 mt-2">
  <div class="col-md-6">
    <div class="card shadow-sm mt-4">
      <div class="card-header bg-success text-white">
        Report based on Status
      </div>
      <div class="card-body">
        <div class="row mb-3 align-items-end">
          <div class="col-auto">
            <label for="status_date1" class="form-label mb-1">From:</label>
            <input type="date" id="status_date1" class="form-control form-control-sm">
          </div>
          <div class="col-auto">
            <label for="status_date2" class="form-label mb-1">To:</label>
            <input type="date" id="status_date2" class="form-control form-control-sm">
          </div>
          <div class="col-auto">
            <button id="status_chart_filters" class="btn btn-success btn-sm">Apply</button>
            <button id="status_current_month" class="btn btn-info btn-sm">Current Month</button>
            <button id="status_clear_filters" class="btn btn-secondary btn-sm">Clear Filters</button>
          </div>
        </div>
        <div id="piechart" style="width:100%;height:270px;"></div>
      </div>
    </div>
  </div>

	<div class="col-md-6">
		<div class="card shadow-sm mt-4">
		<div class="card-header bg-success text-white">Report based on Category</div>
		<div class="card-body">
			<div class="row mb-3 align-items-end">
				<div class="col-auto">
					<label for="category_date1" class="form-label mb-1">From:</label>
					<input type="date" id="category_date1" class="form-control form-control-sm">
				</div>
				<div class="col-auto">
					<label for="category_date2" class="form-label mb-1">To:</label>
					<input type="date" id="category_date2" class="form-control form-control-sm">
				</div>
				<div class="col-auto">
					<button id="category_chart_filters" class="btn btn-success btn-sm">Apply</button>
					<button id="category_current_month" class="btn btn-info btn-sm">Current Month</button>
					<button id="category_clear_filters" class="btn btn-secondary btn-sm">Clear Filters</button>
				</div>
			</div>
			<div id="piechart1" style="width: 100%; height: 270px;"></div>
		</div>
		</div>
	</div>
	</div>

  <div class="row g-4 mt-2">
    <div class="col-md-6">
		<div class="card shadow-sm mt-4">
			<div class="card-header bg-info text-white">Report based on Document-type</div>
			<div class="card-body">
			<div class="row mb-3 align-items-end">
				<div class="col-auto">
				<label for="doc_date1" class="form-label mb-1">From:</label>
				<input type="date" id="doc_date1" class="form-control form-control-sm">
				</div>
				<div class="col-auto">
				<label for="doc_date2" class="form-label mb-1">To:</label>
				<input type="date" id="doc_date2" class="form-control form-control-sm">
				</div>
				<div class="col-auto">
				<button id="doc_btn_apply" class="btn btn-info btn-sm">Apply</button>
				<button id="doc_btn_month" class="btn btn-primary btn-sm">Current Month</button>
				<button id="doc_btn_clear" class="btn btn-secondary btn-sm">Clear Filters</button>
				</div>
			</div>
			<div id="chart-container"><canvas id="mycanvas3"></canvas></div>
			</div>
		</div>
	</div>

    <div class="col-md-6">
		<div class="card shadow-sm mt-4">
			<div class="card-header bg-info text-white">Report based on Department (Top 10)</div>
			<div class="card-body">
			<div class="row mb-3 align-items-end">
				<div class="col-auto">
				<label for="discipline_date1" class="form-label mb-1">From:</label>
				<input type="date" id="discipline_date1" class="form-control form-control-sm">
				</div>
				<div class="col-auto">
				<label for="discipline_date2" class="form-label mb-1">To:</label>
				<input type="date" id="discipline_date2" class="form-control form-control-sm">
				</div>
				<div class="col-auto">
				<button id="discipline_btn_apply" class="btn btn-info btn-sm">Apply</button>
				<button id="discipline_btn_month" class="btn btn-primary btn-sm">Current Month</button>
				<button id="discipline_btn_clear" class="btn btn-secondary btn-sm">Clear Filters</button>
				</div>
			</div>
			<div id="chart-container"><canvas id="mycanvas5"></canvas></div>
			</div>
		</div>
	</div>

  </div>

</div>

<!-- Chart.js and your custom chart apps -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="js/app1.js"></script>
<script src="js/app2.js"></script>
<script src="js/app3.js"></script>
<script src="js/app5.js"></script>
</body>
</html>
