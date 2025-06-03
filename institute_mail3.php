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

  <!-- TinyMCE -->
  <script src="https://cdn.tiny.cloud/1/qp8d4fz8gld9sydga5ch53yhhve7v7y2jpf0f4ko1zanlvk2/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
    tinymce.init({
      selector: '#tinymce-body',
      height: 500,
      menubar: true,
      plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table wordcount',
      toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
      branding: false
    });
  </script>

  <style>
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
  $body = '<p>Dear Sir/Madam, <br/><br/>Could you please arrange to send the following article(s) if available in your collection.</p>The bibliographic details of the article(s) is/are given below:<br/>';

  $trun = mysqli_query($conn, "TRUNCATE TABLE send_instentry");
  if (!empty($_POST['send']) && is_array($_POST['send'])) {
    foreach($_POST['send'] as $send_id) {
        $send_m = mysqli_query($conn, "SELECT * FROM entry WHERE Sr_no='$send_id'");
        $row = mysqli_fetch_array($send_m);
        $req_date = $row['Req_date'];
        $send_entry_no = mysqli_query($conn, "INSERT INTO send_instentry (entry_id, req_date) VALUES ('$send_id', '$req_date')");
        $biblo = $row['Bibliographic_details'];
        $body .= '<br/>' . $biblo . '<br/>';
      }
    }
    $body .= '<p>Regards,<br/>Library Services<br/>Indian Institute of Technology Gandhinagar<br/>Palaj | Gandhinagar - 382355 | Gujarat | INDIA<br/>Tel: +91-079-2395 2099<br/>Email: <a href="mailto:libraryservices@iitgn.ac.in">libraryservices@iitgn.ac.in</a><br/>Website: <a href="http://www.iitgn.ac.in">http://www.iitgn.ac.in</a></p>';
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">IITGN</a>
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <li class="nav-item active">
        <a class="nav-link" href="index.php">Home</a>
      </li>
    </ul>
    <ul class="navbar-nav ms-auto">
      <?php if (isset($_SESSION['uid'])) echo "<li class='nav-item'><a class='nav-link' href='../logout.php'><i class='fas fa-sign-out-alt'></i> Logout</a></li>"; ?>
    </ul>
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
      <input type="text" class="form-control" id="sub" name="sub" value="Request for Documents">

      <label for="Body">Body:</label>
      <textarea name="Body" id="tinymce-body" class="form-control" rows="15"><?php echo $body; ?></textarea>

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
