<?php
session_start();
include('../dbcon.php');
include('../checkUser.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>DDS - Sources</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
     <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"/>

     <style>
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
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Manage
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="../institutions/index.php">Institutions</a></li>
            <li><a class="dropdown-item" href="../journal/index.php">Document Sources</a></li>
            <li><a class="dropdown-item" href="../patrons/index.php">Patrons</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="../orders.php">Requests</a></li>
        <li class="nav-item"><a class="nav-link" href="../reports/index.php">Reports</a></li>
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

<div class="container mt-5 pt-4">
  <h1 class="text-center mt-4">Master List of Sources</h1>
  <div class="d-flex justify-content-end my-3">
    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#add_data_Modal">Add Source</button>
  </div>
  <table id="employee_data" class="table table-striped" style="width:100%">
    <thead class="table-dark">
      <tr>
        <th>Name of Source</th>
        <th>ISSN/ISBN</th>
        <th>E-ISSN</th>
        <th>Name of Publisher</th>
        <th>Publication Country</th>
        <th>Actions</th>
      </tr>
    </thead>
  </table>
</div>

<!-- Modal for adding data -->
<div class="modal fade" id="add_data_Modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Entry</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" title="close-btn"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="" id="insert_form">
          <div class="mb-3">
            <label for="journal_name" class="form-label">Name of Source</label>
            <input type="text" class="form-control" name="journal_name" id="journal_name" required>
          </div>
          <div class="mb-3">
            <label for="print_issn" class="form-label">ISSN/ISBN</label>
            <input type="text" class="form-control" name="print_issn" id="print_issn" required>
          </div>
          <div class="mb-3">
            <label for="e_issn" class="form-label">E-ISSN</label>
            <input type="text" class="form-control" name="e_issn" id="e_issn">
          </div>
          <div class="mb-3">
            <label for="pub_name" class="form-label">Name of Publisher</label>
            <input type="text" class="form-control" name="pub_name" id="pub_name" required>
          </div>
          <div class="mb-3">
            <label for="pub_country" class="form-label">Publication Country</label>
            <input type="text" class="form-control" name="pub_country" id="pub_country">
          </div>
          <div class="modal-footer">
            <input type="submit" name="insert" id="insert" value="Insert" class="btn btn-success">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
if (isset($_POST['insert'])) {
  $jour = $_POST['journal_name'];
  $print = $_POST['print_issn'];
  $eissn = $_POST['e_issn'];
  $pub_name = $_POST['pub_name'];
  $pub_country = $_POST['pub_country'];

  $query = mysqli_query($conn, "INSERT INTO journal_list (Journal_name, Print_ISSN, E_ISSN, Pub_name, Pub_country) VALUES ('$jour','$print','$eissn','$pub_name','$pub_country')");
}
?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
  $('#employee_data').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "fetch_journals.php",
            type: "POST"
        },
        "columns": [
            { "data": "Journal_name" },
            { "data": "Print_ISSN" },
            { "data": "E_ISSN" },
            { "data": "Pub_name" },
            { "data": "Pub_country" },
            { "data": "Actions", "orderable": false, "searchable": false }
        ]
    });
});
</script>
</body>
</html>
