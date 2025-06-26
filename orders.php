<?php  
// index.php
session_start();
include('dbcon.php');
include('checkUser.php');

$query = "SELECT * FROM orders
          WHERE MONTH(date) = MONTH(CURRENT_DATE) 
          AND YEAR(date) = YEAR(CURRENT_DATE)";

$result = mysqli_query($conn, $query);

// Date filter
if (isset($_POST['date_submit'])) {
    $ab = $_POST['date1'];
    $cd = $_POST['date2'];
    $stmt = $conn->prepare("SELECT * FROM orders WHERE date BETWEEN ? AND ?");
    $stmt->bind_param("ss", $ab, $cd);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
}
?>  
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>DDS - Library Orders</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Font Awesome 6 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

  <!-- jQuery 3.6.4 -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <!-- Bootstrap 5 Bundle JS (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- DataTables with Bootstrap 5 styling -->
  <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
  <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

  <style>
    body {
      background-color: lightblue;
    }
  </style>

</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="home.php">Home</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" 
      aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Entries</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="manageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Manage
          </a>
          <ul class="dropdown-menu" aria-labelledby="manageDropdown">
            <li><a class="dropdown-item" href="institutions/index.php">Institutions</a></li>
            <li><a class="dropdown-item" href="journal/index.php">Document Sources</a></li>
            <li><a class="dropdown-item" href="patrons/index.php">Patrons</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="orders.php">Requests</a></li>
        <li class="nav-item"><a class="nav-link" href="reports/index.php">Reports</a></li>
        <?php if ($_SESSION['type'] == '0'): ?>
          <li class="nav-item"><a class="nav-link" href="users/index.php">Users</a></li>
        <?php endif; ?>
      </ul>

      <form method="post" class="d-flex align-items-center" role="search" style="gap:0.5rem;">
        <input type="date" name="date1" class="form-control form-control-sm" title="date1" required />
        <input type="date" name="date2" class="form-control form-control-sm" title="date2" required />
        <button type="submit" name="date_submit" class="btn btn-outline-light btn-sm" title="Filter by date range">
          <i class="fas fa-filter"></i>
        </button>
      </form>

      <ul class="navbar-nav ms-3">
        <?php if (isset($_SESSION['uid'])): ?>
          <li class="nav-item"><a class="nav-link disabled">User <?php echo htmlspecialchars($_SESSION['uid']); ?></a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Main container -->
<div class="container" style="margin-top: 80px; max-width: 900px;">
  <h1 class="text-center mb-4">List of Requests</h1>

  <div class="table-responsive bg-white p-3 rounded shadow-sm">
    <table id="employee_data" class="table table-hover table-striped table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th>Basket Number</th>
          <th>Date</th>
          <th>Username</th>
          <th>Patron Name</th>
          <th>Status</th>
          <th>View</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <?php
          $order_id = $row['order_id'];
          $entry_q = $conn->prepare("SELECT Status, sent_date FROM entry WHERE Order_id = ?");
          $entry_q->bind_param("s", $order_id);
          $entry_q->execute();
          $entry_result = $entry_q->get_result();

          $total = 0;
          $complete = 0;
          $sent_complete = 0;

          while ($entry = $entry_result->fetch_assoc()) {
              if (in_array($entry['Status'], ['Pending', 'Approached', 'Received', 'Complete', 'Closed'])) {
                  $total++;
                  if ($entry['Status'] === 'Complete') {
                      $complete++;
                      if ($entry['sent_date'] !== '0000-00-00 00:00:00' && $entry['sent_date'] !== null && $entry['sent_date'] !== '') {
                          $sent_complete++;
                      }
                  }
              }
          }

          $entry_q->close();

          if ($complete === 0) {
              $status = "Incomplete";
          } elseif ($complete < $total) {
              $status = "Partially Complete";
          } else {
              $status = "Complete";
          }

          $fully_sent = ($status === "Complete" && $complete === $sent_complete);

          if (!$fully_sent):
        ?>
        <tr>
          <td><?= htmlspecialchars($row["order_id"]) ?></td>
          <td><?= htmlspecialchars($row["date"]) ?></td>
          <td><?= htmlspecialchars($row["username"]) ?></td>
          <td><?= htmlspecialchars($row["Display_name"]) ?></td>
          <td><?= $status ?></td>
          <td>
            <a href="show.php?order_no=<?= urlencode($row["order_id"]) ?>" class="btn btn-info btn-sm">Show</a>
          </td>
        </tr>
        <?php endif; ?>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
$(document).ready(function() {
    $('#employee_data').DataTable({
        stateSave: true,
        order: [[0, 'desc']],
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        language: {
          searchPlaceholder: "Search baskets..."
        }
    });
});
</script>

</body>
</html>
