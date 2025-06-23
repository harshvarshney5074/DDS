<?php 
session_start();
include('dbcon.php');
include('checkUser.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DDS | Report</title>

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
	
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet" />
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.2.7/build/pdfmake.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.2.7/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>

    <style>
		.tile_count {
			padding-top: 10px;
		}
		body {
			background-color: lightblue;
		}
    </style>
  </head>
  <body>

	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
	<div class="container-fluid">
		<a class="navbar-brand" href="../home.php">Home</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" title="navbar-toggler">
		<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
		<ul class="navbar-nav me-auto mb-2 mb-lg-0">
			<li class="nav-item"><a class="nav-link" href="../index.php">Entries</a></li>
			<?php if($_SESSION['type']=='0' || $_SESSION['type']=='1'){ ?>
			<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Manage</a>
			<ul class="dropdown-menu">
				<li><a class="dropdown-item" href="../institutions/index.php">Institutions</a></li>
				<li><a class="dropdown-item" href="../journal/index.php">Document Sources</a></li>
				<li><a class="dropdown-item" href="../patrons/index.php">Patrons</a></li>
			</ul>
			</li>
			<li class="nav-item"><a class="nav-link" href="../orders.php">Requests</a></li>
			<?php } ?>
			<li class="nav-item"><a class="nav-link" href="../reports/charts/bargraph.php">Statistics</a></li>
			<?php if($_SESSION['type']=='0'){ ?>
			<li class="nav-item"><a class="nav-link" href="../users/index.php">Users</a></li>
			<?php } ?>
		</ul>
		<ul class="navbar-nav ms-auto">
			<?php if(isset($_SESSION['uid'])){ ?>
			<li class="nav-item"><a class="nav-link">User <?php echo $_SESSION['uid']; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
			<?php } ?>
		</ul>
		</div>
	</div>
	</nav>
<br><br><br>
<h1 class="text-center">DDS Reports</h1>

    <div class="container body">
      <div class="main_container">

        <!-- /top navigation -->
<?php 
	$status_counts = [
    'Total' => 0,
    'Pending' => 0,
    'Approached' => 0,
    'Received' => 0,
    'Complete' => 0,
    'Closed' => 0
];

// Get status-wise counts
$result = mysqli_query($conn, "SELECT Status, COUNT(*) as cnt FROM entry GROUP BY Status");
while ($row = mysqli_fetch_assoc($result)) {
    $status = $row['Status'];
    $count = $row['cnt'];
    if (isset($status_counts[$status])) {
        $status_counts[$status] = $count;
    }
    $status_counts['Total'] += $count;
}

// Patron type counts (category-wise)
$category_counts = [];
$cat_result = mysqli_query($conn, "SELECT Category, COUNT(*) as cnt FROM entry GROUP BY Category");
while ($row = mysqli_fetch_assoc($cat_result)) {
    $category_counts[$row['Category']] = $row['cnt'];
}
?>

<script>
  $.noConflict();
  jQuery(document).ready(function ($) {
	const tableCategory = $('#table_category').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: 'serverside/table_category.php',
			type: 'POST',
			data: function (d) {
			d.date1 = $('input[name="date1"]').val();
			d.date2 = $('input[name="date2"]').val();
			}
		},
		order: [[1, 'desc']],
		columns: [
			{ title: "Category" },
			{ title: "Total" },
			{ title: "Pending" },
			{ title: "Approached" },
			{ title: "Received" },
			{ title: "Complete" },
			{ title: "Closed" }
		],
		lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
		pageLength: 10,
		dom: 'Bflitp',
		buttons: ['excelHtml5', 'csvHtml5', 'pdfHtml5']
	});

	$('#category-filter-form').on('submit', function(e) {
		e.preventDefault();
		tableCategory.ajax.reload();
	});

	$('#currentMonthBtn').on('click', function () {
		const now = new Date();
		const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
		const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);

		// Format to YYYY-MM-DD
		const formatDate = (d) => d.toISOString().slice(0, 10);

		$('input[name="date1"]').val(formatDate(firstDay));
		$('input[name="date2"]').val(formatDate(lastDay));

		// Trigger DataTable reload
		tableCategory.ajax.reload();
	});

	$('#clearFiltersBtn').on('click', function () {
		$('input[name="date1"]').val('');
		$('input[name="date2"]').val('');
		tableCategory.ajax.reload();
	});

	const tableJournals = $('#table_journals').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: 'serverside/table_journals.php',
			type: 'POST',
			data: function (d) {
			d.date1 = $('input[name="journal_date1"]').val();
			d.date2 = $('input[name="journal_date2"]').val();
			}
		},
		order: [[2, 'desc']], // Sort by count
		columns: [
			{ title: "#" },
			{ title: "Journal Name" },
			{ title: "No of Requests" },
			{ title: "Pending" },
			{ title: "Approached" },
			{ title: "Received" },
			{ title: "Complete" },
			{ title: "Closed" }
		],
		lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
		pageLength: 5,
		dom: 'Bflitp',
		buttons: ['excelHtml5', 'csvHtml5', 'pdfHtml5']
	});

	$('#journal-filter-form').on('submit', function (e) {
		e.preventDefault();
		tableJournals.ajax.reload();
	});

		// Filter buttons
	$('#journal-current-month').on('click', function () {
		const now = new Date();
		const start = `${now.getFullYear()}/${(now.getMonth() + 1).toString().padStart(2, '0')}/01`;
		const end = `${now.getFullYear()}/${(now.getMonth() + 1).toString().padStart(2, '0')}/${new Date(now.getFullYear(), now.getMonth() + 1, 0).getDate()}`;
		$('input[name="journal_date1"]').val(start);
		$('input[name="journal_date2"]').val(end);
		tableJournals.ajax.reload();
	});

	$('#journal-clear-filters').on('click', function () {
		$('input[name="journal_date1"]').val('');
		$('input[name="journal_date2"]').val('');
		tableJournals.ajax.reload();
	});

	const tableLibraries = $('#table_libraries').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: 'serverside/table_libraries.php',
			type: 'POST',
			data: function (d) {
				d.lib_date1 = $('input[name="lib_date1"]').val();
				d.lib_date2 = $('input[name="lib_date2"]').val();
			},
			 dataSrc: function (json) {
				// Set totals in the page
				$('#total-sent').text(json.total_sent || 0);
				$('#total-received').text(json.total_received || 0);
				return json.data;
			}
		},
		order: [[1, 'desc']], // Sort by Send Count
		columns: [
			{ title: "Institute Name" },
			{ title: "No of Requests Sent" },
			{ title: "Receive Count" }
		],
		lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
		pageLength: 5,
		dom: 'Bflitp',
		buttons: ['excelHtml5', 'csvHtml5', 'pdfHtml5']
	});

	$('#library-filter-form').on('submit', function (e) {
		e.preventDefault();
		tableLibraries.ajax.reload();
	});

	// Filter buttons
	$('#library-current-month').on('click', function () {
		const now = new Date();
		const start = `${now.getFullYear()}/${(now.getMonth() + 1).toString().padStart(2, '0')}/01`;
		const end = `${now.getFullYear()}/${(now.getMonth() + 1).toString().padStart(2, '0')}/${new Date(now.getFullYear(), now.getMonth() + 1, 0).getDate()}`;
		$('input[name="lib_date1"]').val(start);
		$('input[name="lib_date2"]').val(end);
		tableLibraries.ajax.reload();
	});

	$('#library-clear-filters').on('click', function () {
		$('input[name="lib_date1"]').val('');
		$('input[name="lib_date2"]').val('');
		tableLibraries.ajax.reload();
	});

	const tableDocType = $('#table_doctype').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: 'serverside/table_doctype.php',
			type: 'POST',
			data: function(d) {
			d.date1 = $('input[name="doctype_date1"]').val();
			d.date2 = $('input[name="doctype_date2"]').val();
			}
		},
		order: [[2, 'desc']],
		columns: [
			{ title: "#" },
			{ title: "Document Type" },
			{ title: "No of Requests" },
			{ title: "Pending" },
			{ title: "Approached" },
			{ title: "Received" },
			{ title: "Complete" },
			{ title: "Closed" }
		],
		lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
		pageLength: 25,
		dom: 'Bflitp',
		buttons: ['excelHtml5', 'csvHtml5', 'pdfHtml5']
		});

		// Filter submit
		$('#doctype-filter-form').on('submit', function(e) {
		e.preventDefault();
		tableDocType.ajax.reload();
		});

		// Set current month range
		$('#doctype-current-month').on('click', function() {
			const now = new Date();
			const yyyy = now.getFullYear();
			const mm = (now.getMonth() + 1).toString().padStart(2, '0');
			const first = `${yyyy}-${mm}-01`;
			const last = `${yyyy}-${mm}-${new Date(yyyy, now.getMonth() + 1, 0).getDate()}`;
			$('input[name="doctype_date1"]').val(first);
			$('input[name="doctype_date2"]').val(last);
			tableDocType.ajax.reload();
		});

		// Clear filters
		$('#doctype-clear-filters').on('click', function() {
			$('input[name="doctype_date1"]').val('');
			$('input[name="doctype_date2"]').val('');
			tableDocType.ajax.reload();
		});

		const tableUsers = $('#table_users').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
				url: 'serverside/table_users.php',
				type: 'POST',
				data: function(d) {
				d.user_date1 = $('input[name="user_date1"]').val();
				d.user_date2 = $('input[name="user_date2"]').val();
				}
			},
			order: [[2, 'desc']], // Sort by count
			columns: [
				{ title: "#", orderable: false },
				{ title: "Patron" },
				{ title: "No of Requests" },
				{ title: "Pending" },
				{ title: "Approached" },
				{ title: "Received" },
				{ title: "Complete" },
				{ title: "Closed" }
			],
			lengthMenu: [[5,10,25,50,-1],[5,10,25,50,"All"]],
			pageLength: 5,
			dom: 'Bflitp',
			buttons: ['excelHtml5','csvHtml5','pdfHtml5']
		});

			// Filter form submit
			$('#user-filter-form').on('submit', function(e){
			e.preventDefault();
			tableUsers.ajax.reload();
		});

			// Current month button
			$('#user-current-month').on('click', function(){
			const now = new Date(),
					first = new Date(now.getFullYear(), now.getMonth(), 1),
					last = new Date(now.getFullYear(), now.getMonth()+1, 0),
					fmt = d => d.toISOString().slice(0,10);
			$('input[name="user_date1"]').val(fmt(first));
			$('input[name="user_date2"]').val(fmt(last));
			tableUsers.ajax.reload();
		});

			// Clear filters button
			$('#user-clear-filters').on('click', function(){
			$('input[name="user_date1"]').val('');
			$('input[name="user_date2"]').val('');
			tableUsers.ajax.reload();
		});

		const deliveryTable = $('#table_delivery').DataTable({
			processing: true,
			serverSide: true,
			searching: false,
			ordering: false,
			ajax: {
				url: 'serverside/table_delivery_time.php',
				type: 'POST',
				data: function (d) {
				d.del_date1 = $('input[name="del_date1"]').val();
				d.del_date2 = $('input[name="del_date2"]').val();
				}
			},
			columns: [
				{ data: 'range' },
				{ data: 'count' }
			],
			paging: false,
			info: false,
			dom: 'Bfrtip',
			buttons: ['excelHtml5', 'csvHtml5', 'pdfHtml5']
		});

			// Filter
			$('#delivery_submit').on('click', function (e) {
			e.preventDefault();
			deliveryTable.draw();
		});

			// Current Month
			$('#delivery_month').on('click', function (e) {
			e.preventDefault();
			const now = new Date();
			const first = new Date(now.getFullYear(), now.getMonth(), 1);
			const last = new Date(now.getFullYear(), now.getMonth() + 1, 0);
			$('input[name="del_date1"]').val(first.toISOString().split('T')[0]);
			$('input[name="del_date2"]').val(last.toISOString().split('T')[0]);
			deliveryTable.draw();
		});

			// Clear Filters
			$('#delivery_clear').on('click', function (e) {
			e.preventDefault();
			$('input[name="del_date1"]').val('');
			$('input[name="del_date2"]').val('');
			deliveryTable.draw();
		});


		const table_discipline = $('#table_discipline').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
			url: 'serverside/table_discipline.php',
			type: 'POST',
			data: function (d) {
				d.date1 = $('#date1_discipline').val();
				d.date2 = $('#date2_discipline').val();
			}
			},
			columns: [
				{ data: 0, orderable: false },
				{ data: 1 }, // Discipline
				{ data: 2 }, // Count
				{ data: 3, orderable: false },
				{ data: 4, orderable: false },
				{ data: 5, orderable: false },
				{ data: 6, orderable: false },
				{ data: 7, orderable: false }
			],
			order: [[2, 'desc']],
			lengthMenu: [[5,10,25,50,-1],[5,10,25,50,"All"]],
			pageLength: 5,
			dom: 'Bflitp',
			buttons: ['excelHtml5', 'csvHtml5', 'pdfHtml5'] 
		});

		$('#filter_discipline').on('submit', function (e) {
			e.preventDefault();
			table_discipline.draw();
		});

		$('#clear_discipline').on('click', function () {
			$('#date1_discipline').val('');
			$('#date2_discipline').val('');
			table_discipline.draw();
		});

		$('#current_month_discipline').on('click', function () {
			const now = new Date();
			const first = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0];
			const last = new Date(now.getFullYear(), now.getMonth() + 1, 0).toISOString().split('T')[0];
			$('#date1_discipline').val(first);
			$('#date2_discipline').val(last);
			table_discipline.draw();
		});

    // Modal trigger (customize if needed)
    $(document).on('click', '.view_data', function () {
      $.ajax({
        url: "", // Add your backend script URL here
        method: "POST",
        data: {}, // Add data to send
        success: function (data) {
          $('#employee_detail').html(data);
          $('#dataModal').modal('show');
        }
      });
    });
  });
</script>

        <!-- page content -->
		<div id="container" class="container-fluid mt-3 pt-4">
		<div class="row g-3">
			<div class="col-6 col-md-2">
			<div class="p-3 bg-light border rounded text-center">
				<div class="text-muted"><i class="fa fa-plus-circle"></i> Total Entries</div>
				<div class="fs-5 fw-bold"><?= $status_counts['Total'] ?></div>
			</div>
			</div>
			<div class="col-6 col-md-2">
			<div class="p-3 bg-light border rounded text-center">
				<div class="text-muted"><i class="fa fa-clock"></i> Pending</div>
				<div class="fs-5 fw-bold text-danger"><?= $status_counts['Pending'] ?></div>
			</div>
			</div>
			<div class="col-6 col-md-2">
			<div class="p-3 bg-light border rounded text-center">
				<div class="text-muted"><i class="fa fa-arrow-circle-right"></i> Approached</div>
				<div class="fs-5 fw-bold text-info"><?= $status_counts['Approached'] ?></div>
			</div>
			</div>
			<div class="col-6 col-md-2">
			<div class="p-3 bg-light border rounded text-center">
				<div class="text-muted"><i class="fa fa-clock"></i> Received</div>
				<div class="fs-5 fw-bold text-danger"><?= $status_counts['Received'] ?></div>
			</div>
			</div>
			<div class="col-6 col-md-2">
			<div class="p-3 bg-light border rounded text-center">
				<div class="text-muted"><i class="fa fa-check-circle"></i> Completed</div>
				<div class="fs-5 fw-bold text-success"><?= $status_counts['Complete'] ?></div>
			</div>
			</div>
			<div class="col-6 col-md-2">
			<div class="p-3 bg-light border rounded text-center">
				<div class="text-muted"><i class="fa fa-window-close"></i> Closed</div>
				<div class="fs-5 fw-bold text-primary"><?= $status_counts['Closed'] ?></div>
			</div>
			</div>
			<div class="row row-cols-2 row-cols-md-4 g-3 align-items-stretch">
				<div class="col">
					<div class="p-3 bg-light border rounded text-center h-100 d-flex flex-column justify-content-center">
					<div class="text-muted"><i class="fa fa-user"></i> Students</div>
					<div class="fs-5 fw-bold"><?= $category_counts['Student'] ?? 0 ?></div>
					</div>
				</div>
				<div class="col">
					<div class="p-3 bg-light border rounded text-center h-100 d-flex flex-column justify-content-center">
					<div class="text-muted"><i class="fa fa-user"></i> Faculty</div>
					<div class="fs-5 fw-bold"><?= $category_counts['Faculty'] ?? 0 ?></div>
					</div>
				</div>
				<div class="col">
					<div class="p-3 bg-light border rounded text-center h-100 d-flex flex-column justify-content-center">
					<div class="text-muted"><i class="fa fa-user"></i> Staff</div>
					<div class="fs-5 fw-bold"><?= $category_counts['Staff'] ?? 0 ?></div>
					</div>
				</div>
				<div class="col">
					<div class="p-3 bg-light border rounded text-center h-100 d-flex flex-column justify-content-center">
					<div class="text-muted"><i class="fa fa-user"></i> Alumni</div>
					<div class="fs-5 fw-bold"><?= $category_counts['Alumni'] ?? 0 ?></div>
					</div>
				</div>
				<div class="col">
					<div class="p-3 bg-light border rounded text-center h-100 d-flex flex-column justify-content-center">
					<div class="text-muted"><i class="fa fa-user"></i> Individual Memberships</div>
					<div class="fs-5 fw-bold"><?= $category_counts['Individual Membership'] ?? 0 ?></div>
					</div>
				</div>
				<div class="col">
					<div class="p-3 bg-light border rounded text-center h-100 d-flex flex-column justify-content-center">
					<div class="text-muted"><i class="fa fa-user"></i> Institute Members</div>
					<div class="fs-5 fw-bold"><?= $category_counts['Institute Member'] ?? 0 ?></div>
					</div>
				</div>
				<div class="col">
					<div class="p-3 bg-light border rounded text-center h-100 d-flex flex-column justify-content-center">
						<div class="text-muted"><i class="fa fa-user"></i> Corporate Members</div>
						<div class="fs-5 fw-bold"><?= $category_counts['Corporate Member'] ?? 0 ?></div>
					</div>
				</div>
				<div class="col">
					<div class="p-3 bg-light border rounded text-center h-100 d-flex flex-column justify-content-center">
					<div class="text-muted"><i class="fa fa-user"></i> Other Institutions</div>
					<div class="fs-5 fw-bold"><?= $category_counts['Other Institution'] ?? 0 ?></div>
					</div>
				</div>
				</div>
			</div>
		</div>

          <!-- /top tiles -->

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="dashboard_graph mt-3">

<?php
$statuses = ['Pending', 'Approached', 'Received', 'Complete', 'Closed'];
$data = []; // Final output structure: $data[patron_type][status] = count

// Choose date condition
if (isset($_POST['user_submit'])) {
	$date1 = $_POST['date1'];
	$date2 = $_POST['date2'];
	$date_condition = "Req_date BETWEEN '$date1' AND '$date2'";
} else {
	$date_condition = "MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)";
}

// Fetch all patron types
$types_result = mysqli_query($conn, "SELECT Patron_type FROM patron_type");
while ($row = mysqli_fetch_assoc($types_result)) {
	$type = $row['Patron_type'];
	
	// Get total for this type
	$total_query = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM entry WHERE Category='$type' AND $date_condition");
	$total = mysqli_fetch_assoc($total_query)['cnt'];
	$data[$type]['Total'] = $total;

	// For each status
	foreach ($statuses as $status) {
		$status_query = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM entry WHERE Category='$type' AND Status='$status' AND $date_condition");
		$count = mysqli_fetch_assoc($status_query)['cnt'];
		$data[$type][$status] = $count;
	}
}
?>

<?php 
	if(isset($_POST['submit'])){ 
?>
   <h3> Report:</h3>
  
		<div class="row">
		<div class="col-md-6">
		From:<span class="badge"><?php echo"$date1" ?></span> <br/>To:<span class="badge"><?php echo"$date2" ?> </span><br/>
		Status:<span class="badge"><?php echo"$status" ?> </span>
		</div>
		<div class="col-md-6">
		Count:<span class="badge"><?php echo"$sql1count" ?></span> <br/>
		Category:<span class="badge"><?php echo"$category" ?></span> <br/>
		</div>
		</div>
		<?php } ?>	
	<!--- new thing-->
<div class="container-fluid py-4">

	<!-- Request by Category -->
	<div class="card shadow mb-4">
		<div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
		<h5 class="mb-0">Requests by Category</h5>
		<form class="d-flex gap-2" id="category-filter-form">
			<input type="date" class="form-control form-control-sm" name="date1">
			<input type="date" class="form-control form-control-sm" name="date2">
			<button type="submit" name="user_submit" class="btn btn-light btn-sm">
			<i class="fas fa-search"></i>
			</button>
			<button type="button" id="currentMonthBtn" class="btn btn-secondary btn-sm">
				Current Month
			</button>
			<button type="button" id="clearFiltersBtn" class="btn btn-secondary btn-sm bg-red">
				Clear Filters
			</button>
		</form>
		</div>
		<div class="card-body">
		<table id="table_category" class="table table-bordered table-hover table-sm" style="width:100%">
		<thead class="table-light">
			<tr>
			<th>Category</th>
			<th>Total</th>
			<th>Pending</th>
			<th>Approached</th>
			<th>Received</th>
			<th>Complete</th>
			<th>Closed</th>
			</tr>
		</thead>
		</table>
		</div>
	</div>

	<!-- Request by Journals -->
	<div class="card shadow mb-4">
		<div class="card-header d-flex justify-content-between align-items-center bg-success text-white">
			<h5 class="mb-0">Requests by Journal</h5>
			<form class="d-flex gap-2" id="journal-filter-form">
				<input type="date" class="form-control form-control-sm" name="journal_date1">
  				<input type="date" class="form-control form-control-sm" name="journal_date2">
				<button type="submit" class="btn btn-light btn-sm">
					<i class="fas fa-search"></i>
				</button>
				<button type="button" class="btn btn-secondary btn-sm" id="journal-current-month">
					Current Month
				</button>
				<button type="button" class="btn btn-secondary btn-sm" id="journal-clear-filters">
					Clear Filters
				</button>
			</form>
		</div>
		<div class="card-body">
			<table id="table_journals" class="table table-bordered table-hover table-sm w-100">
			<thead class="table-light">
				<tr>
				<th>#</th>
				<th>Journal Name</th>
				<th>No of Requests</th>
				<th>Pending</th>
				<th>Approached</th>
				<th>Received</th>
				<th>Complete</th>
				<th>Closed</th>
				</tr>
			</thead>
			<tbody></tbody>
			</table>
		</div>
	</div>

	<!-- Request by Libraries -->
	<div class="card shadow mb-4">
		<div class="card-header d-flex justify-content-between align-items-center bg-warning text-dark">
		<h5 class="mb-0">Requests by Libraries</h5>
		<form class="d-flex gap-2" id="library-filter-form">
			<input type="date" class="form-control form-control-sm" name="lib_date1">
			<input type="date" class="form-control form-control-sm" name="lib_date2">
			<button type="submit" name="institute_submit" class="btn btn-light btn-sm">
			<i class="fas fa-search"></i>
			</button>
			<button type="button" id="library-current-month" class="btn btn-secondary btn-sm">
				Current Month
			</button>
			<button type="button" id="library-clear-filters" class="btn btn-secondary btn-sm">
				Clear Filters
			</button>
		</form>
		</div>
		<div class="card-body">
		<table id="table_libraries" class="table table-bordered table-hover table-sm">
			<thead class="table-light">
			<tr><th>Institute Name</th><th>No of Requests Sent</th><th>Receive Count</th></tr>
			</thead>
		</table>
		</div>
		<div id="library-totals" class="mt-1 text-center fw-semibold text-secondary" style="font-size: 0.95rem;">
			Total Requests Sent: <span id="total-sent">0</span> |
			Total Received: <span id="total-received">0</span>
		</div>
	</div>
	
	<!-- Request by Document Type -->
		<div class="card shadow mb-4">
			<div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
				<h5 class="mb-0">Requests by Document Type</h5>
				<form class="d-flex gap-2" id="doctype-filter-form">
				<input type="date" class="form-control form-control-sm" name="doctype_date1">
  				<input type="date" class="form-control form-control-sm" name="doctype_date2">
				<button type="submit" class="btn btn-light btn-sm">
					<i class="fas fa-search"></i>
				</button>
				<button type="button" id="doctype-current-month" class="btn btn-secondary btn-sm">
					Current Month
				</button>
				<button type="button" id="doctype-clear-filters" class="btn btn-secondary btn-sm">
					Clear Filters
				</button>
				</form>
			</div>
			<div class="card-body">
				<table id="table_doctype" class="table table-bordered table-hover table-sm w-100">
				<thead class="table-light">
					<tr>
					<th>#</th>
					<th>Document Type</th>
					<th>No of Requests</th>
					<th>Pending</th>
					<th>Approached</th>
					<th>Received</th>
					<th>Complete</th>
					<th>Closed</th>
					</tr>
				</thead>
				<tbody></tbody>
				</table>
			</div>
		</div>

	
	<!-- Request by Patrons -->
		<div class="card shadow mb-4">
			<div class="card-header d-flex justify-content-between align-items-center bg-success text-white">
				<h5 class="mb-0">Requests by Patrons</h5>
				<form id="user-filter-form" class="d-flex gap-2 ">
				<input type="date" name="user_date1" class="form-control form-control-sm">
				<input type="date" name="user_date2" class="form-control form-control-sm">
				<button type="submit" class="btn btn-light btn-sm"><i class="fas fa-search"></i></button>
				<button type="button" id="user-current-month" class="btn btn-secondary btn-sm">
					Current Month
				</button>
				<button type="button" id="user-clear-filters" class="btn btn-secondary btn-sm">
					Clear Filters
				</button>
				</form>
			</div>
			<div class="card-body">
				<table id="table_users" class="table table-bordered table-hover table-sm w-100">
				<thead class="table-light"><tr>
					<th>#</th><th>Patron</th><th>No of Requests</th>
					<th>Pending</th><th>Approached</th><th>Received</th>
					<th>Complete</th><th>Closed</th>
				</tr></thead>
				<tbody></tbody>
				</table>
			</div>
		</div>

<!-- Report on Average Time -->
		<div class="card shadow mb-4">
		<div class="card-header d-flex justify-content-between align-items-center bg-info text-white">
			<h5 class="mb-0">Requests by Delivery Time</h5>
			<form id="deliveryForm" class="d-flex gap-2 mb-0">
			<input type="date" name="del_date1" class="form-control form-control-sm">
			<input type="date" name="del_date2" class="form-control form-control-sm">
			<button type="submit" class="btn btn-light btn-sm" id="delivery_submit">
				<i class="fas fa-search"></i>
			</button>
			<button type="button" class="btn btn-secondary btn-sm" id="delivery_month">
				Current Month
			</button>
			<button type="button" class="btn btn-secondary btn-sm" id="delivery_clear">
				Clear Filters
			</button>
			</form>
		</div>
		<div class="card-body">
			<table id="table_delivery" class="table table-bordered table-hover table-sm w-100">
			<thead class="table-light">
				<tr>
				<th>Time Period (days)</th>
				<th>No. of Requests</th>
				</tr>
			</thead>
			</table>
		</div>
		</div>


<!-- Report on Discipline -->
	<div class="card shadow mb-4">
		<div class="card-header d-flex justify-content-between align-items-center bg-warning text-white">
			<h5 class="mb-0">Requests by Department</h5>
			<form id="filter_discipline" class="d-flex gap-2 mb-0">
			<input type="date" id="date1_discipline" class="form-control form-control-sm">
			<input type="date" id="date2_discipline" class="form-control form-control-sm">
			<button type="submit" class="btn btn-light btn-sm" title="Filter">
				<i class="fas fa-search"></i>
			</button>
			<button type="button" id="current_month_discipline" class="btn btn-secondary btn-sm" title="Current Month">
				Current Month
			</button>
			<button type="button" id="clear_discipline" class="btn btn-secondary btn-sm" title="Clear Filters">
				Clear Filters
			</button>
			</form>
		</div>
		<div class="card-body">
			<table id="table_discipline" class="table table-bordered table-hover table-sm">
			<thead class="table-light">
				<tr>
				<th>Sno</th>
				<th>Discipline Name</th>
				<th>No of Requests</th>
				<th>Pending</th>
				<th>Approached</th>
				<th>Received</th>
				<th>Complete</th>
				<th>Closed</th>
				</tr>
			</thead>
			</table>
		</div>
	</div>

</div>				
	
  </body>
</html>