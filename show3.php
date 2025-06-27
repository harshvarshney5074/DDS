<?php
session_start();
include("dbcon.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>DDS - Send Mail</title>
  
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

  <!-- CKEditor 5 Classic -->
  <script src="js/ckeditor.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      ClassicEditor
        .create(document.querySelector('#body'))
        .then(editor => {
          console.log('Editor ready', editor);
        })
        .catch(error => {
          console.error('Editor init error:', error);
        });
    });
  </script>


  <style>
    body {
      padding-top: 70px;
      background-color: lightblue;
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
    <a class="navbar-brand" href="home.php">Home</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" title="navbar-toggler">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Entries</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Manage</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="institutions/index.php">Institutions</a></li>
            <li><a class="dropdown-item" href="journal/index.php">Document Sources</a></li>
            <li><a class="dropdown-item" href="patrons/index.php">Patrons</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="orders.php">Requests</a></li>
        <li class="nav-item"><a class="nav-link" href="reports/index.php">Reports</a></li>
        <?php if ($_SESSION['type'] === '0'): ?>
        <li class="nav-item"><a class="nav-link" href="users/index.php">Users</a></li>
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

    $body = "<p>Dear $username,</p>";

    $body .= "<p>Please find attached some of the research paper(s) you had requested.</p>";
    $body .= "<p><strong>Details of the requested resource(s):</strong></p>";

    echo '<div class="mb-3"><strong>Attached Files:</strong><ul class="file-list">';

    $sent_entry_ids = [];

    foreach ($_POST['send'] as $send_id) {
      $sent_entry_ids[] = $send_id;
      $send_m = mysqli_query($conn, "SELECT * FROM entry WHERE Sr_no='$send_id'");
      $row = mysqli_fetch_array($send_m);

      $file_path = $row['File_path'];
      $file_name = $row['File_name'];

      mysqli_query($conn, "INSERT INTO attachments (Send_id, File_name, File_path) VALUES ('$send_id', '$file_name', '$file_path')");

      $biblo = $row['Bibliographic_details'];
      $body .= "<p> $biblo</p>";

      echo "<li>$file_name</li>";
    }

    $entry_ids_str = implode(',', $sent_entry_ids);
    echo '</ul></div>';

    $body .= '<br/>';

    $check = mysqli_query($conn, "SELECT * FROM entry WHERE order_id='$order_no' AND (Status='Pending' OR Status='Approached')");
    $check_count = mysqli_num_rows($check);
    if ($check_count > 0) {
      $body .= "<p>In the meantime, we are trying to acquire the other requested resources and we will deliver them to you soon.</p>";
      $body .= "<p><strong>Details of the requested resource(s) pending:</strong></p><ul>";

      while ($row1 = mysqli_fetch_array($check)) {
          $body .= "<li> " . $row1["Bibliographic_details"] . "</li>";
      }
      $body .= "</ul>";
    } else {
      $body .= "<p>We hope these will be helpful for your current work.</p>";
    }

    $body .= <<<HTML
<p>If you require any further assistance in accessing additional materials, please feel free to reach out to us at <a href="mailto:libraryservices@iitgn.ac.in">libraryservices@iitgn.ac.in</a>.</p>

<hr>
<p><strong>Copyright Guidelines:</strong></p>
<p style="color: red;">
Kindly note that the attached document(s) is/are being sent to you for your academic and research purposes only and must be given to the requester only. Under no circumstances should this/these paper(s) be circulated electronically or hosted on a public system. All these documents are copyrighted, hence we request your compliance.
</p>
HTML;

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
      <input type="text" class="form-control" id="sub" name="sub" value="Your Requested Research Document(s)" required>
    </div>

    <div class="mb-3">
      <label for="body" class="form-label">Body:</label>
      <textarea name="Body" class="form-control" id="body" rows="15"><?= $body ?></textarea>
    </div>

    <input type="hidden" name="order_no" value="<?= htmlspecialchars($order_no) ?>">
    <input type="hidden" name="entry_ids" value="<?= htmlspecialchars($entry_ids_str) ?>">

    <div class="d-grid">
      <button type="submit" name="send1" class="btn btn-primary">Send Mail</button>
    </div>
  </form>
</div>

</body>
</html>
