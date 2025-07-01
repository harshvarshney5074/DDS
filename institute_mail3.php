<?php 
  session_start();
  include('checkUser.php');
  include('dbcon.php');
?>
<!DOCTYPE html>
<html>
<head>
  <title>DDS</title>

  <!-- Bootstrap 5.3.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Choices.js for multiselect -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

  <!-- CKEditor 5 Classic (Production Build) -->
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
    body{
      background-color: lightblue;
    }
    input[type=text], select, textarea {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    .form {
        border-radius: 5px;
        background-color: #f2f2f2;
        padding: 20px;
        margin: 10% auto;
        max-width: 700px;
    }
    h1 { margin-top: 5%; text-align: center; }
    .close { margin-left: 90%; }
  </style>
</head>
<body>
<?php 
include("dbcon.php");
$body = '';
if (isset($_POST['mail'])) {
$body = <<<HTML
<p>Dear Sir/Madam,</p>
<p>Greetings from Library, IIT Gandhinagar.</p>
<p>One of our researchers urgently requires access to the following research paper for immediate reference in connection with ongoing work:</p>
<p><strong>Details of the requested resource(s):</strong></p>
HTML;


  $trun = mysqli_query($conn, "TRUNCATE TABLE send_instentry");
  if (!empty($_POST['send']) && is_array($_POST['send'])) {
    foreach($_POST['send'] as $send_id) {
        $send_m = mysqli_query($conn, "SELECT * FROM entry WHERE Sr_no='$send_id'");
        $row = mysqli_fetch_array($send_m);
        $req_date = $row['Req_date'];
        $send_entry_no = mysqli_query($conn, "INSERT INTO send_instentry (entry_id, req_date) VALUES ('$send_id', '$req_date')");
        $biblo = $row['Bibliographic_details'];
        $body .= '<p> ' . $biblo . '</p>';
      }
    }
    $body .= <<<HTML
<p>As this document is not available in our library collection, we would be most grateful if you could kindly arrange to share a copy with us at your earliest convenience.</p>
<p><strong>Please be assured that the paper will be provided solely to the requesting researcher and used strictly for academic and non-commercial purposes.</strong></p>
<p>Your kind support in this matter will be deeply appreciated.<br/>
Looking forward to your kind response.</p>

<p>Regards,<br/>
Library Services<br/>
Indian Institute of Technology Gandhinagar<br/>
Palaj | Gandhinagar - 382355 | Gujarat | INDIA<br/>
Tel: +91-079-2395 2099<br/>
Email: <a href="mailto:libraryservices@iitgn.ac.in">libraryservices@iitgn.ac.in</a><br/>
Website: <a href="http://www.iitgn.ac.in">http://www.iitgn.ac.in</a></p>
HTML;

}
?>

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
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Manage</a>
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

<div class="container form">
  <h1>Institute Mail</h1>
  <div class="close">
    <a href="index.php"><i class="fa fa-times" style="width:20px;" aria-hidden="true"></i></a>
  </div>
  <form method="post" name="f1" id="framework_form" action="send_mail1.php" enctype="multipart/form-data">
    <div class="form-group">
      <label>Request Institutions</label><br/>
      <select id="framework1" name="framework1[]" multiple>
        <?php 
        $query3 = "SELECT * FROM institutions";
        $getlist = mysqli_query($conn, $query3);
        while ($rowcats = mysqli_fetch_array($getlist)) {
        ?>
        <option value="<?php echo $rowcats['Sr_no']; ?>"><?php echo $rowcats['institute_name']; ?></option>
        <?php } ?>
      </select>

      <div class="row">
        <div class="col-md-6">
          <label for="Cc">Cc:</label>
          <input type="text" class="form-control" id="Cc" name="Cc">
        </div>
        <div class="col-md-6">
          <label for="Bcc">Bcc:</label>
          <input type="text" class="form-control" id="Bcc" name="Bcc">
        </div>
      </div>

      <label for="sub">Subject:</label>
      <input type="text" class="form-control" id="sub" name="sub" value="Request for Access to Subscribed Resources">

      <label for="Body">Body:</label>
      <textarea name="Body" id="body" class="form-control" rows="15"><?php echo $body; ?></textarea>

      <br/>
      <div class="text-center">
        <input type="submit" class="btn btn-primary" name="send_inst" value="Send">
        <a href="index.php" class="btn btn-secondary" role="button">Close</a>
      </div>
    </div>
  </form>
</div>

<!-- jQuery 3.7.1 -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 5.3.3 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Choices.js for multiselect -->
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const element = document.getElementById('framework1');
    if (element) {
      new Choices(element, {
        removeItemButton: true,
        searchEnabled: true,
        placeholderValue: 'Select Institute(s)',
        maxItemCount: 100
      });
    } 
    // For Cc
    new Choices('#Cc', {
      delimiter: ',',
      editItems: true,
      removeItemButton: true,
      placeholderValue: 'Add Cc recipient(s)',
      duplicateItemsAllowed: false
    });

    // For Bcc
    new Choices('#Bcc', {
      delimiter: ',',
      editItems: true,
      removeItemButton: true,
      placeholderValue: 'Add Bcc recipient(s)',
      duplicateItemsAllowed: false
    });
  });
</script>
</body>
</html>
