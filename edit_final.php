<?php
include("dbcon.php");

if (isset($_GET['edit_record'])) {
    $get_id = $_GET['edit_record'];
    $get_pro = mysqli_query($conn, "SELECT * FROM entry WHERE Sr_no='$get_id'");

    while ($row_pro = mysqli_fetch_array($get_pro)) {
        $id = $row_pro['Sr_no'];
        $req_date = $row_pro['Req_date'];
        $req_by_id = $row_pro['Req_by'];

        // Fetch Display_name for req_by id
        $req = mysqli_query($conn, "SELECT Display_name FROM patrons WHERE Sr_no='$req_by_id'");
        $row1 = mysqli_fetch_array($req);
        $req_by = $row1 ? $row1['Display_name'] : '';
        $req_cat = $row_pro['Category'];
        $current_doc_type = $row_pro['Document_type'];
        $jour = $row_pro['Journal_name'];
        $biblio_det = $row_pro['Bibliographic_details'];
        $status = $row_pro['Status'];
        $file_n = $row_pro['File_name'];
    }
}

if (isset($_POST['insti'])) {
    if (!empty($_POST['framework'])){
        $number = count($_POST["framework"]);
        if ($number > 0) {
            for ($i = 0; $i < $number; $i++) {
                $name = $_POST["framework"][$i];
                mysqli_query($conn, "INSERT INTO institute_list(institute_name, entry_id, req_date) VALUES ('$name', '$get_id', '$req_date')");
            }
        }
    }
}

if (isset($_POST['update'])) {
    $eid = $_POST['eid'];
    $req_date = mysqli_real_escape_string($conn, $_POST['req_date']);

    // The input value for requested_by will be like: "Sr_no-Display_name(Email_id)"
    // We need to parse the Sr_no from the input and use it for Req_by foreign key
    $requested_by_raw = trim($_POST['requested_by']);
    // Extract Sr_no before the first dash "-"
    $pos_dash = strpos($requested_by_raw, '-');
    if ($pos_dash !== false) {
        $req_by_id = substr($requested_by_raw, 0, $pos_dash);
    } else {
        // fallback: treat whole input as name, try to lookup ID later
        $req_by_id = 0;
    }

    // If req_by_id is numeric and >0, use it directly; else try to find Sr_no by Display_name
    if (!is_numeric($req_by_id) || intval($req_by_id) <= 0) {
        $req_by_id = 0;
    } else {
        $req_by_id = intval($req_by_id);
    }

    // If Sr_no is 0, fallback lookup by Display_name only (cleaned)
    if ($req_by_id === 0) {
        $req_by_name_only = $requested_by_raw; // could be just Display_name
        $q = mysqli_query($conn, "SELECT Sr_no FROM patrons WHERE Display_name='" . mysqli_real_escape_string($conn, $req_by_name_only) . "' LIMIT 1");
        if (mysqli_num_rows($q) > 0) {
            $row = mysqli_fetch_assoc($q);
            $req_by_id = $row['Sr_no'];
        }
    }

    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $doc_type = mysqli_real_escape_string($conn, $_POST['doc_type']);
    $journal_name = mysqli_real_escape_string($conn, $_POST['journal_name']);
    $biblio_det = mysqli_real_escape_string($conn, $_POST['biblio_det']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Handle file upload if status is Complete or Received and file uploaded
    $file_name = $file_tmp = '';
    if (($status === 'Complete' || $status === 'Received') && isset($_FILES['fil']) && $_FILES['fil']['error'] === 0) {
        $file_name = basename($_FILES['fil']['name']);
        $file_tmp = $_FILES['fil']['tmp_name'];
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $target_file = $upload_dir . $file_name;
        move_uploaded_file($file_tmp, $target_file);
    }

    $update_sql = "UPDATE entry SET 
        Req_date='$req_date',
        Req_by='$req_by_id',
        Category='$category',
        Document_type='$doc_type',
        Journal_name='$journal_name',
        Bibliographic_details='$biblio_det',
        Status='$status'";

    if ($file_name) {
        $update_sql .= ", File_name='$file_name', File_path='$target_file'";
    }

    $update_sql .= " WHERE Sr_no='$eid'";

    if (mysqli_query($conn, $update_sql)) {
        echo '<div class="alert alert-success mt-3">Record updated successfully!</div>';
    } else {
        echo '<div class="alert alert-danger mt-3">Error updating record: ' . mysqli_error($conn) . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>DDS - Edit Record</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <!-- Bootstrap5 Multiselect -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap5-multiselect@1.1.1/dist/css/bootstrap-multiselect.min.css" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>  

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap5 Multiselect JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap5-multiselect@1.1.1/dist/js/bootstrap-multiselect.min.js"></script>

    <!-- Bootstrap 3 Typeahead -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>

    <style>
        input[type=text], select, textarea {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type=submit]:hover {
            background-color: #45a049;
            cursor: pointer;
        }
        .form-container {
            border-radius: 5px;
            background-color: #f2f2f2;
            padding: 20px;
            margin: 5% auto;
            max-width: 600px;
        }
        h1 {
            margin-top: 5%;
            text-align: center;
        }
        .close-btn {
            text-align: right;
            margin-bottom: 10px;
        }
    </style>

    <script>
        function enable_text(status) {
            document.getElementById("fil").disabled = !status;
        }

        $(document).ready(function() {
            $('#framework').select2({
                placeholder: 'Select institutions',
                allowClear: true,
                width: '100%'
            });

            // Setup typeahead on Requested By input field using fetch2.php
            $('#requested_by').typeahead({
                minLength: 2,
                source: function(query, process) {
                    return $.ajax({
                        url: "fetch2.php",
                        type: 'POST',
                        data: { query: query },
                        dataType: 'json',
                        success: function(data) {
                            return process(data);
                        }
                    });
                },
                updater: function(item) {
                    // When selected, just fill the input with the full string (Sr_no-Display_name(Email))
                    return item;
                }
            });
        });
    </script>

</head>
<body onload="enable_text(document.getElementById('status').value === 'Complete' || document.getElementById('status').value === 'Received');">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">IITGN</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item active"><a class="nav-link" href="index.php">Home</a></li>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['uid'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="form-container mt-5 pt-5">
    <div class="close-btn">
        <a href="index.php" class="btn btn-outline-secondary btn-sm" title="Close">
            <i class="fa fa-times"></i>
        </a>
    </div>

    <h1>Edit Record</h1>

    <label>Requested Institutions</label><br/>
    <strong>Already requested:</strong>
    <?php
    $getcats = mysqli_query($conn, "SELECT institutions.institute_name FROM institutions, institute_list WHERE institute_list.entry_id=$get_id AND institute_list.institute_name=institutions.Sr_no");
    $institutes = [];
    while ($rowcats = mysqli_fetch_array($getcats)) {
        $institutes[] = htmlspecialchars($rowcats['institute_name']);
    }
    echo implode(", ", $institutes);
    ?>

    <form method="post" action="" class="mb-3 mt-2">
        <div class="row g-2">
            <div class="col-11">
                <select id="framework" name="framework[]" multiple class="form-select" style="width: 100%;">
                    <?php
                        $getlist = mysqli_query($conn, "SELECT * FROM institutions WHERE Sr_no NOT IN (SELECT institute_name FROM institute_list WHERE entry_id=$get_id)");
                        while ($rowcats = mysqli_fetch_array($getlist)) {
                            $val = htmlspecialchars($rowcats['Sr_no']);
                            $name = htmlspecialchars($rowcats['institute_name']);
                            echo "<option value=\"$val\">$name</option>";
                        }
                    ?>
                </select>

            </div>
            <div class="col-1">
                <input type="submit" value="Add" class="btn btn-primary btn-sm" name="insti" />
            </div>
        </div>
    </form>

    <form method="post" name="f1" id="framework_form" action="" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="req_date" class="form-label">Request Date</label>
            <input type="text" id="req_date" name="req_date" value="<?php echo htmlspecialchars($req_date); ?>" class="form-control" placeholder="Request Date" />
        </div>

        <div class="mb-3">
            <label for="requested_by" class="form-label">Requested By</label>
            <input type="text" class="form-control" id="requested_by" name="requested_by" value="<?php 
                // Prefill with Sr_no-Display_name(Email) format for typeahead consistency
                if ($req_by) {
                    $p = mysqli_query($conn, "SELECT Sr_no, Display_name, Email_id FROM patrons WHERE Display_name='" . mysqli_real_escape_string($conn, $req_by) . "' LIMIT 1");
                    if ($p && mysqli_num_rows($p) > 0) {
                        $patron = mysqli_fetch_assoc($p);
                        echo htmlspecialchars($patron['Sr_no'] . '-' . $patron['Display_name'] . '(' . $patron['Email_id'] . ')');
                    } else {
                        echo htmlspecialchars($req_by);
                    }
                }
            ?>" autocomplete="off" required>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select name="category" id="category" class="form-select">
                <option value="<?php echo htmlspecialchars($req_cat); ?>"><?php echo htmlspecialchars($req_cat); ?></option>
                <?php
                $doc = mysqli_query($conn, "SELECT * FROM patron_type");
                while ($get = mysqli_fetch_array($doc)) {
                    $type = htmlspecialchars($get['Patron_type']);
                    echo "<option value=\"$type\">$type</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="doc_type" class="form-label">Document Type</label>
            <select name="doc_type" id="doc_type" class="form-select" required>
                <option value="">-- Select Document Type --</option>
                <?php
                $doc_types = mysqli_query($conn, "SELECT * FROM document_type ORDER BY Document_type ASC");
                while ($type = mysqli_fetch_assoc($doc_types)) {
                    $selected = ($type['Document_type'] == $current_doc_type) ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($type['Document_type']) . '" ' . $selected . '>' . htmlspecialchars($type['Document_type']) . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="country" class="form-label">Journal Name</label>
            <input type="text" name="journal_name" id="country" class="form-control" value="<?php echo htmlspecialchars($jour); ?>" autocomplete="off" placeholder="Journal Name" />
        </div>

        <div class="mb-3">
            <label for="biblio_det" class="form-label">Bibliographic Details</label>
            <textarea rows="3" name="biblio_det" class="form-control"><?php echo htmlspecialchars($biblio_det); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" onchange="enable_text(this.value === 'Complete' || this.value === 'Received');">
                <option value="<?php echo htmlspecialchars($status); ?>" selected><?php echo htmlspecialchars($status); ?></option>
                <option value="Approached">Approached</option>
                <option value="Closed">Closed</option>
                <option value="Pending">Pending</option>
                <option value="Complete">Complete</option>
                <option value="Received">Received</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="file" class="form-label">File (PDF/Doc)</label>
            <input type="file" id="fil" name="fil" class="form-control" disabled />
        </div>

        <input type="hidden" name="eid" value="<?php echo htmlspecialchars($id); ?>" />
        <input type="submit" class="btn btn-primary" name="update" value="Update Record" />
    </form>

</div>
</body>
</html>
