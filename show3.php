<?php
session_start();
include("dbcon.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DDS - Send Mail</title>
  
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

  <!-- TinyMCE -->
  <script src="https://cdn.tiny.cloud/1/qp8d4fz8gld9sydga5ch53yhhve7v7y2jpf0f4ko1zanlvk2/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
    tinymce.init({ selector: 'textarea' });
  </script>

  <style>
    body {
      padding-top: 70px;
    }
    .file-list li {
      word-break: break-word;
    }
  </style>
</head>
<body>

<!-- ✅ Consistent Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">IITGN</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if ($_SESSION['type'] === '0' || $_SESSION['type'] === '1'): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Manage</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="institutions/index.php">Institutions</a></li>
              <li><a class="dropdown-item" href="journal/index.php">Journals</a></li>
              <li><a class="dropdown-item" href="patrons/index.php">Patrons</a></li>
            </ul>
          </li>
          <li class="nav-item"><a class="nav-link" href="biblo_search1.php">Search</a></li>
          <li class="nav-item"><a class="nav-link" href="orders.php">Orders</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="reports/index.php">Reports</a></li>
        <?php if ($_SESSION['type'] === '0'): ?>
          <li class="nav-item"><a class="nav-link" href="users/index.php">Settings</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['uid'])): ?>
          <li class="nav-item"><a class="nav-link">User <?= $_SESSION['uid'] ?></a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Main Content -->
<div class="container mt-4">
  <h2 class="mb-4 text-center">Send Mail to Patron</h2>

  <?php
  if (isset($_POST['submit'])) {
    $del_attach = mysqli_query($conn, "TRUNCATE TABLE attachments");

    $order_no = $_POST['order_no'];
    $useremail = $_POST['useremail'];
    $username = $_POST['username'];

    $body = 'Dear ' . $username . ',<p>Please find attached the requested article(s).</p>';

    echo '<div class="mb-3"><strong>Attached Files:</strong><ul class="file-list">';

    foreach ($_POST['send'] as $send_id) {
      $send_m = mysqli_query($conn, "SELECT * FROM entry WHERE Sr_no='$send_id'");
      $row = mysqli_fetch_array($send_m);

      $file_path = $row['File_path'];
      $file_name = $row['File_name'];

      mysqli_query($conn, "INSERT INTO attachments (Send_id, File_name, File_path) VALUES ('$send_id', '$file_name', '$file_path')");

      $biblo = $row['Bibliographic_details'];
      $body .= '<ul><li>' . $biblo . '</li></ul>';

      echo "<li>$file_name</li>";
    }

    echo '</ul></div>';

    $body .= '<br/>';

    $check = mysqli_query($conn, "SELECT * FROM entry WHERE order_id='$order_no' AND (Status='Pending' OR Status='Approached')");
    $check_count = mysqli_num_rows($check);
    if ($check_count > 0) {
      $body .= '<p>The following remaining document(s) will be arranged soon:</p><ul>';
      while ($row1 = mysqli_fetch_array($check)) {
        $body .= '<li>' . $row1["Bibliographic_details"] . '</li>';
      }
      $body .= '</ul>';
    }

    $body .= '<br/>Regards,<br/>Library Services<br/>Indian Institute of Technology Gandhinagar<br/>Palaj | Gandhinagar - 382355 | Gujarat | INDIA<br/>Tel: +91-079-2395 2099<br/>Email: <a href="mailto:libraryservices@iitgn.ac.in">libraryservices@iitgn.ac.in</a><br/>Website: <a href="http://www.iitgn.ac.in">http://www.iitgn.ac.in</a><br/>';

    $body .= '<hr><p><strong>Copyright Guidelines:</strong></p>
    <p style="color: red;">Kindly note that the attached documents are strictly for academic and research purposes only. They must not be shared electronically or hosted publicly.</p>';
  }
  ?>

  <form method="post" action="show4.php">
    <div class="mb-3">
      <label for="to" class="form-label">To:</label>
      <input type="email" class="form-control" id="to" name="To" value="<?= htmlspecialchars($useremail) ?>" required>
    </div>

    <div class="row mb-3">
      <div class="col">
        <label for="cc" class="form-label">Cc:</label>
        <input type="text" class="form-control" id="cc" name="Cc">
      </div>
      <div class="col">
        <label for="bcc" class="form-label">Bcc:</label>
        <input type="text" class="form-control" id="bcc" name="Bcc">
      </div>
    </div>

    <div class="mb-3">
      <label for="sub" class="form-label">Subject:</label>
      <input type="text" class="form-control" id="sub" name="sub" value="Requested Papers" required>
    </div>

    <div class="mb-3">
      <label for="body" class="form-label">Body:</label>
      <textarea name="Body" class="form-control" rows="15"><?= htmlspecialchars($body) ?></textarea>
    </div>

    <input type="hidden" name="order_no" value="<?= htmlspecialchars($order_no) ?>">

    <div class="d-grid">
      <button type="submit" name="send1" class="btn btn-primary">Send Mail</button>
    </div>
  </form>
</div>

</body>
</html>
