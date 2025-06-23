<?php  
  session_start();
  include('dbcon.php');
  include('checkUser.php');
  $query = "SELECT * FROM institutions";
  $result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DDS - Institutions</title>
  
  <!-- Latest Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Latest Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- Latest jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!-- Latest DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

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
        <?php if($_SESSION['type']=='0' || $_SESSION['type']=='1'){ ?>
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
        <?php } ?>
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

<div class="container mt-5 pt-5">
  <h1 class="text-center">Institutions List</h1>
  <div class="text-end mb-3">
    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addModal">Add Institution</button>
  </div>
  <table id="employee_data" class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>Institution Name</th>
        <th>Email</th>
        <th>Phone no.</th>
        <th>Address</th>
        <th>Status</th>
        <th>Remarks</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = mysqli_fetch_array($result)){ ?>
      <tr>
        <td><?php echo $row["institute_name"]; ?></td>
        <td><?php echo $row["email"]; ?></td>
        <td><?php echo $row["phone_no"]; ?></td>
        <td><?php echo $row["address"]; ?></td>
        <td><?php echo $row["Status"]; ?></td>
        <td><?php echo $row["Remarks"]; ?></td>
        <td>
          <a href="edit11.php?edit_record=<?php echo $row["Sr_no"]; ?>" class="btn btn-info btn-sm">Edit</a>
          <button class="btn btn-danger btn-sm delete_data" data-id="<?php echo $row["Sr_no"]; ?>">Delete</button>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Entry</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" title="close-btn"></button>
      </div>
      <form method="POST" onsubmit="return validateForm();">
        <div class="modal-body">
          <div class="mb-3">
            <label for="institute_name" class="form-label">Institute Name</label>
            <input type="text" name="institute_name" id="institute_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="phone_no" class="form-label">Phone no.</label>
            <input type="tel" name="phone_no" id="phone_no" class="form-control">
          </div>
          <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" name="address" id="address" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" title="status">
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="remarks" class="form-label">Remarks</label>
            <input type="text" name="remarks" id="remarks" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="insert" class="btn btn-success">Insert</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function validateForm() {
    const email = document.querySelector('[name="email"]').value;
    const atpos = email.indexOf("@");
    const dotpos = email.lastIndexOf(".");
    if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= email.length) {
      alert("Not a valid e-mail address");
      return false;
    }
    return true;
  }

  $(document).ready(function() {
    $('#employee_data').DataTable({
      stateSave: true,
      order: [[0, 'asc']],
      dom: 'Bfrtip',
      buttons: ['excelHtml5', 'csvHtml5', 'pdfHtml5']
    });

    $(document).on('click', '.delete_data', function() {
      const institute_id = $(this).data('id');
      if (confirm('Are you sure you want to delete this record?')) {
        $.post('delete.php', { institute_id: institute_id }, function(data) {
          location.reload();
        });
      }
    });
  });
</script>

<?php
if (!empty($_POST)) {
  $inst = $_POST['institute_name'];
  $email = $_POST['email'];
  $phone = $_POST['phone_no'];
  $add = $_POST['address'];
  $status = $_POST['status'];
  $remarks = $_POST['remarks'];

  $check = mysqli_query($conn, "SELECT * FROM institutions WHERE institute_name = '$inst'");
  $count = mysqli_num_rows($check);
  if ($count >= 1) {
    echo "<script>alert('This entry already exists.')</script>";
  } else {
    $sql = mysqli_query($conn, "INSERT INTO institutions (institute_name,email,phone_no,address,Status,Remarks) VALUES ('$inst','$email','$phone','$add','$status','$remarks')");
    if ($sql) {
      echo "<script>alert('Successfully inserted'); window.location='index.php';</script>";
    }
  }
}
?>

</body>
</html>
