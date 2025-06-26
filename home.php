<?php 
  session_start();
  include('checkUser.php');
  include('dbcon.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document Delivery Service - IIT Gandhinagar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Latest Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Latest Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- Latest jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <style>
    body {
      padding-top: 55px;
      background-color: #f8f9fa;
    }
    .hero-section {
      background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.6)), url('image/bg3.png') center/cover no-repeat;
      color: white;
      padding: 100px 20px;
      text-align: center;
    }
    .hero-section h1 {
      font-size: 3rem;
      font-weight: bold;
    }
    .info-section {
      padding: 40px 20px;
    }
    .info-section h2 {
      margin-bottom: 20px;
    }
    .footer {
      background: #343a40;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 40px;
    }
  </style>
</head>
<body>

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
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Manage
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="institutions/index.php">Institutions</a></li>
            <li><a class="dropdown-item" href="journal/index.php">Document Sources</a></li>
            <li><a class="dropdown-item" href="patrons/index.php">Patrons</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="orders.php">Requests</a></li>
        <li class="nav-item"><a class="nav-link" href="reports/index.php">Reports</a></li>
        <?php if($_SESSION['type']=='0'){ ?>
        <li class="nav-item"><a class="nav-link" href="users/index.php">Users</a></li>
        <?php } ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if(isset($_SESSION['uid'])){ ?>
        <li class="nav-item"><a class="nav-link">User <?php echo $_SESSION['uid']; ?></a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>

<div class="hero-section">
  <h1>Document Delivery Service</h1>
  <p class="lead">Access scholarly resources from partnering institutions across India</p>
</div>

<div class="container info-section">
  <h2>About DDS at IIT Gandhinagar</h2>
  <p>
    The Document Delivery Service (DDS) at IIT Gandhinagar is a collaborative platform that helps faculty, students, and researchers obtain academic materials—such as journal articles, book chapters, conference papers, and more—that are not available in our library's subscriptions.
  </p>

  <h3>How It Works</h3>
  <ul>
    <li>Users submit requests for specific documents via the DDS portal.</li>
    <li>The library team identifies partner institutions that have access to the requested documents.</li>
    <li>Requests are sent to those institutions, and the documents are delivered electronically to the requester.</li>
  </ul>

  <h3>Who Can Use It?</h3>
  <p>All registered users of IITGN Library including faculty, students, and research staff can use the DDS system.</p>

  <h3>What Can Be Requested?</h3>
  <ul>
    <li>Journal articles</li>
    <li>Book chapters</li>
    <li>Conference papers</li>
    <li>Other academic documents (as permitted by copyright)</li>
  </ul>

  <h3>Partner Institutions</h3>
  <p>
    IIT Gandhinagar actively collaborates with other academic institutions and libraries across India to fulfill document requests. The DDS system maintains records of interactions, success rates, and performance metrics for each partner.
  </p>

  <h3>Need Help?</h3>
  <p>
    If you have any queries or encounter issues with the DDS, please contact the library team at <a href="mailto:libraryservices@iitgn.ac.in">libraryservices@iitgn.ac.in</a>.
  </p>
</div>

<div class="footer">
  &copy; <?php echo date("Y"); ?> IIT Gandhinagar - Library Services
</div>

</body>
</html>
