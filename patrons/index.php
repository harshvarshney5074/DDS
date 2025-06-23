<?php  
session_start();
include('dbcon.php');
include('checkUser.php');
$query = "SELECT * FROM patrons";
$result = mysqli_query($conn, $query);
?>  
<!DOCTYPE html>
<html lang="en">  
<head>  
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>DDS - Patrons</title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

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
        <?php if ($_SESSION['type'] === '0' || $_SESSION['type'] === '1'): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
              Manage
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="../institutions/index.php">Institutions</a></li>
              <li><a class="dropdown-item" href="../journal/index.php">Document Sources</a></li>
              <li><a class="dropdown-item" href="../patrons/index.php">Patrons</a></li>
            </ul>
          </li>
        <li class="nav-item"><a class="nav-link" href="../orders.php">Requests</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="../reports/index.php">Reports</a></li>
        <?php if ($_SESSION['type'] === '0'): ?>
          <li class="nav-item"><a class="nav-link" href="../users/index.php">Users</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['uid'])): ?>
          <li class="nav-item"><a class="nav-link">User <?= $_SESSION['uid'] ?></a></li>
          <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5 pt-4">
  <h1 class="text-center mt-4">Patrons List</h1>
  <div class="d-flex justify-content-end mb-2">
    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#add_data_Modal">Add Patron</button>
  </div>

  <div class="table-responsive">
    <table id="employee_data" class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Roll No/ID</th>
          <th>Display Name</th>
          <th>Email ID</th>
          <th>Department</th>
          <th>Program/Designation</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="add_data_Modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addPatronForm">
          <div class="modal-header">
               <h5 class="modal-title">Add Patron</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
               <input class="form-control mb-2" type="text" name="roll_no" placeholder="Roll No/ID" required>
               <input class="form-control mb-2" type="text" name="display_name" placeholder="Display Name" required>
               <input class="form-control mb-2" type="email" name="email_id" placeholder="Email ID" required>
               <input class="form-control mb-2" type="text" name="discipline" placeholder="Department" required>
               <input class="form-control mb-2" type="text" name="program_name" placeholder="Program/Designation" required>
               <select class="form-select" name="status">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
               </select>
          </div>
          <div class="modal-footer">
               <button type="submit" class="btn btn-success">Insert</button>
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
     </form>

    </div>
  </div>
</div>

<script>
     $(document).ready(function() {
     const table = $('#employee_data').DataTable({
     processing: true,
     serverSide: true,
     ajax: 'fetch_patrons.php',
     columns: [
          { data: 'Roll_no' },
          { data: 'Display_name' },
          { data: 'Email_id' },
          { data: 'Discipline' },
          { data: 'Program_name' },
          { data: 'Status' },
          {
          data: null,
          orderable: false,
          render: function(data, type, row) {
               return `
               <a href="edit11.php?edit_record=${row.Sr_no}" class="btn btn-sm btn-info">Edit</a>
               <button class="btn btn-sm btn-danger" onclick="deletePatron(${row.Sr_no})">Delete</button>
               `;
          }
          }
     ]
     });

     $('#addPatronForm').on('submit', function(e) {
     e.preventDefault();

     if (!validateForm()) return;

     $.post('insert.php', $(this).serialize(), function(response) {
          if (response.success) {
          alert(response.message);
          $('#add_data_Modal').modal('hide');
          $('#addPatronForm')[0].reset();
          table.ajax.reload();
          } else {
          alert(response.message);
          }
     }, 'json');
     });
     });

     function validateForm() {
          const email = document.querySelector("[name='email_id']").value;
          const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!re.test(email)) {
               alert("Please enter a valid email address.");
               return false;
          }
          return true;
     }

     function deletePatron(id) {
     if (confirm('Are you sure you want to delete this patron?')) {
     $.post('delete.php', { id }, function(response) {
          alert(response.message);
          $('#employee_data').DataTable().ajax.reload();
     }, 'json');
     }
     }
</script>

</body>
</html>
