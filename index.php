<?php 
  session_start();
  include('checkUser.php');
  include('dbcon.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>DDS</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Font Awesome 5 (latest) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <!-- jQuery (needed for your datepicker and other plugins) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 5 Bundle JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap 3 Typeahead (you can keep it if still used) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet" />
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.2.7/build/pdfmake.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.2.7/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>

    <!-- Bootstrap Datepicker CSS + JS (not officially BS5, but keeping your version) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <style>
        body {
            background-image: url("image/bg3.jpg");
            background-attachment: fixed;
        }
        .tabclass {
            background-color: white;
        }
        h1 {
            font-size: 40px;
            color: white;
            padding-top: 70px;
        }
        #employee_data td:nth-child(6),
        #employee_data th:nth-child(6) {
          max-width: 350px;   /* Adjust width as needed */
          white-space: normal !important;
          word-wrap: break-word;
        }

        input.form-check-input {
          border: 2px solid #333;
          background-color: #fff;
        }

        input.form-check-input:checked {
          background-color: #0d6efd; /* Bootstrap primary */
          border-color: #0d6efd;
        }
    </style>

    <script>
        $(document).ready(function () {
            // Initialize datepickers for all inputs with name date1, date2, req_date
            $('input[name="date1"], input[name="date2"], input[name="req_date"]').datepicker({
                format: 'yyyy/mm/dd',
                todayHighlight: true,
                autoclose: true
            });
        });
    </script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">IITGN</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php if ($_SESSION['type'] == '0' || $_SESSION['type'] == '1') { ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Manage
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="institutions/index.php">Institutions</a></li>
                                <li><a class="dropdown-item" href="journal/index.php">Journals</a></li>
                                <li><a class="dropdown-item" href="patrons/index.php">Patrons</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="biblo_search1.php">Search</a></li>
                        <li class="nav-item"><a class="nav-link" href="orders.php">Orders</a></li>
                    <?php } ?>
                    <li class="nav-item"><a class="nav-link" href="reports/index.php">Reports</a></li>
                    <?php if ($_SESSION['type'] == '0') { ?>
                        <li class="nav-item"><a class="nav-link" href="users/index.php">Settings</a></li>
                    <?php } ?>
                </ul>

                <form id="dateFilterForm" class="d-flex align-items-center" role="search">
                    <input type="text" name="date1" id="date1" placeholder="YYYY/MM/DD" class="form-control me-2" required />
                    <input type="text" name="date2" id="date2" placeholder="YYYY/MM/DD" class="form-control me-2" required />
                    <button type="submit" class="btn btn-secondary me-3">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['uid'])) { ?>
                        <li class="nav-item"><a class="nav-link disabled" href="#">User <?php echo $_SESSION['uid']; ?></a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

<div class="container mt-5 pt-5">
    <h1 class="text-center mb-4">List of Articles</h1>


    <div class="tabclass table-responsive p-3 rounded shadow-sm">
        
        <div class="dropdown mb-3">
          <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            Filter
          </button>
          <div class="dropdown-menu p-3" style="min-width: 300px;">
            <!-- Status Filters -->
            <strong>Status</strong><br>
            <div class="form-check">
              <input class="form-check-input status-filter" type="checkbox" value="Pending" id="status-pending">
              <label class="form-check-label" for="status-pending">Pending</label>
            </div>
            <div class="form-check">
              <input class="form-check-input status-filter" type="checkbox" value="Approached" id="status-approached">
              <label class="form-check-label" for="status-approached">Approached</label>
            </div>
            <div class="form-check">
              <input class="form-check-input status-filter" type="checkbox" value="Received" id="status-received">
              <label class="form-check-label" for="status-approached">Received</label>
            </div>
            <div class="form-check">
              <input class="form-check-input status-filter" type="checkbox" value="Complete" id="status-complete">
              <label class="form-check-label" for="status-approached">Complete</label>
            </div>
            <div class="form-check">
              <input class="form-check-input status-filter" type="checkbox" value="Closed" id="status-closed">
              <label class="form-check-label" for="status-approached">Closed</label>
            </div>

            <hr class="my-2">

            <!-- Document Type Filters -->
            <strong>Document Type</strong><br>
            <div class="form-check">
              <input class="form-check-input doc-type-filter" type="checkbox" value="Journal Article" id="doc-journalarticle">
              <label class="form-check-label" for="doc-journalarticle">Journal Article</label>
            </div>
            <div class="form-check">
              <input class="form-check-input doc-type-filter" type="checkbox" value="Conference Paper" id="doc-conferencepaper">
              <label class="form-check-label" for="doc-conferencepaper">Conference Paper</label>
            </div>
            <div class="form-check">
              <input class="form-check-input doc-type-filter" type="checkbox" value="Book" id="doc-book">
              <label class="form-check-label" for="doc-book">Book</label>
            </div>
            <div class="form-check">
              <input class="form-check-input doc-type-filter" type="checkbox" value="Book Chapter" id="doc-bookchapter">
              <label class="form-check-label" for="doc-bookchapter">Book Chapter</label>
            </div>
            <div class="form-check">
              <input class="form-check-input doc-type-filter" type="checkbox" value="Standard" id="doc-standard">
              <label class="form-check-label" for="doc-standard">Standard</label>
            </div>
            <div class="form-check">
              <input class="form-check-input doc-type-filter" type="checkbox" value="Patent" id="doc-patent">
              <label class="form-check-label" for="doc-patent">Patent</label>
            </div>
            <div class="form-check">
              <input class="form-check-input doc-type-filter" type="checkbox" value="Thesis" id="doc-thesis">
              <label class="form-check-label" for="doc-thesis">Thesis</label>
            </div>
            <div class="form-check">
              <input class="form-check-input doc-type-filter" type="checkbox" value="Technical Paper" id="doc-technicalpaper">
              <label class="form-check-label" for="doc-technicalpaper">Technical Paper</label>
            </div>
            <div class="form-check">
              <input class="form-check-input doc-type-filter" type="checkbox" value="Technical Report" id="doc-technicalreport">
              <label class="form-check-label" for="doc-technicalreport">Technical Report</label>
            </div>
            <div class="form-check">
              <input class="form-check-input doc-type-filter" type="checkbox" value="External" id="doc-external">
              <label class="form-check-label" for="doc-external">External</label>
            </div>
            <div class="form-check">
              <input class="form-check-input doc-type-filter" type="checkbox" value="Others" id="doc-others">
              <label class="form-check-label" for="doc-others">Others</label>
            </div>

            <hr class="my-2">

            <!-- Patron Category Filters -->
            <strong>Category</strong><br>
            <div class="form-check">
              <input class="form-check-input category-filter" type="checkbox" value="Student" id="cat-student">
              <label class="form-check-label" for="cat-student">Student</label>
            </div>
            <div class="form-check">
              <input class="form-check-input category-filter" type="checkbox" value="Faculty" id="cat-faculty">
              <label class="form-check-label" for="cat-faculty">Faculty</label>
            </div>
            <div class="form-check">
              <input class="form-check-input category-filter" type="checkbox" value="Other Institution" id="cat-other">
              <label class="form-check-label" for="cat-other">Other Institution</label>
            </div>
            <div class="form-check">
              <input class="form-check-input category-filter" type="checkbox" value="Corporate Member" id="cat-corporatemember">
              <label class="form-check-label" for="cat-corporatemember">Corporate Member</label>
            </div>
            <div class="form-check">
              <input class="form-check-input category-filter" type="checkbox" value="Other Institute" id="cat-institutemember">
              <label class="form-check-label" for="cat-institutemember">Institute Member</label>
            </div>
            <div class="form-check">
              <input class="form-check-input category-filter" type="checkbox" value="Staff" id="cat-staff">
              <label class="form-check-label" for="cat-staff">Staff</label>
            </div>
            <div class="form-check">
              <input class="form-check-input category-filter" type="checkbox" value="Individual Membership" id="cat-individual">
              <label class="form-check-label" for="cat-individual">Individual Membership</label>
            </div>
            <div class="form-check">
              <input class="form-check-input category-filter" type="checkbox" value="Alumni" id="cat-alumni">
              <label class="form-check-label" for="cat-alumni">Alumni</label>
            </div>
          </div>
        </div>
        <button type="button" name="add" id="add" data-bs-toggle="modal" data-bs-target="#add_data_Modal" class="btn btn-warning mb-2">Add</button>
        <form method="POST" action="institute_mail3.php" id="entryMailForm">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <div>
              <button type="submit" name="mail" class="btn btn-primary">Add to Mail</button>
              <span id="selectedCount" class="ms-2 text-muted">(0 entries selected)</span>
            </div>
          </div>
          <table id="employee_data" class="table table-hover table-striped table-bordered" style="width:100%">
              <thead class="table-info">
                  <tr>
                      <th>Mail</th>
                      <th>ID</th>
                      <th>Date</th>
                      <th>Patron</th>
                      <th>Category</th>
                      <th>Bibliographic Details</th>
                      <th>Journal Name</th>
                      <th>Document Type</th>
                      <th>Status</th>
                      <th>Actions</th>
                      <th><i class="fa fa-download fa-lg" aria-hidden="true"></i></th>
                  </tr>
              </thead>
              <tbody></tbody> <!-- DataTables will populate this -->
          </table>
        </form>
    </div>
</div>
</body>

</html>
<!-- Data Modal -->
<div id="dataModal" class="modal fade" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dataModalLabel">Entry Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="employee_detail"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Add Data Modal -->
<div id="add_data_Modal" class="modal fade" tabindex="-1" aria-labelledby="addDataModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- Optional large modal -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addDataModalLabel">New Entry</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" id="insert_form">
          <label for="req_date">Date</label>
          <input type="text" class="form-control" id="req_date" name="req_date" placeholder="Request Date (YYYY/MM/DD)" required>

          <label for="req_by" class="mt-3">Patron</label>
          <input type="text" name="req_by" id="req_by" class="form-control" autocomplete="off" placeholder="Request By" required>

          <label for="category" class="mt-3">Category</label>
          <select name="category" id="category" class="form-control">
            <?php
              $doc = mysqli_query($conn, "SELECT * FROM patron_type");
              while ($get = mysqli_fetch_array($doc)) {
                echo '<option value="'.htmlspecialchars($get['Patron_type']).'">'.htmlspecialchars($get['Patron_type']).'</option>';
              }
            ?>
          </select>

          <div class="mt-4">
            <label>Bibliographic Details | Document Type | Journal Name</label>
            <table id="dynamic" class="table table-borderless">
              <tbody>
                <tr id="row1">
                  <td style="width:98%">
                    <select name="document_type[]" class="form-control document_type_select" required>
                      <?php
                        $doc = mysqli_query($conn, "SELECT * FROM document_type");
                        while ($get = mysqli_fetch_array($doc)) {
                          echo '<option value="'.htmlspecialchars($get['Document_type']).'">'.htmlspecialchars($get['Document_type']).'</option>';
                        }
                      ?>
                    </select>
                    <br>
                    <input type="text" name="journal_name[]" class="form-control journal_name_input" autocomplete="off" placeholder="Journal Name" />
                    <br>
                    <textarea name="name[]" class="form-control biblio_det_textarea" placeholder="Bibliographic details" required></textarea>
                    <br>
                  </td>
                  <td style="width:2%" class="align-middle">
                    <button type="button" name="add" id="add_input" class="btn btn-primary btn-sm">
                      <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <label for="status" class="mt-3">Status</label>
          <select name="status" id="status" class="form-control" required>
            <option value="Pending">Pending</option>
            <option value="Approached">Approached</option>
            <option value="Received">Received</option>
            <option value="Complete">Complete</option>
            <option value="Closed">Closed</option>
          </select>

          <input type="hidden" name="employee_id" id="employee_id" />
          <br><br>
          <input type="submit" name="insert" id="insert" value="Insert" class="btn btn-success" />
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script>
  $(document).ready(function () {
      function updateSelectedCount() {
        const count = $('.entry-checkbox:checked').length;
        $('#selectedCount').text(`(${count} entr${count === 1 ? 'y' : 'ies'} selected)`);
      }

      $(document).on('change', '.entry-checkbox', function() {
        updateSelectedCount();
        
        // If not all are checked, uncheck master checkbox
        if (!this.checked) {
          $('#selectAll').prop('checked', false);
        } else if ($('.entry-checkbox').length === $('.entry-checkbox:checked').length) {
          $('#selectAll').prop('checked', true);
        }
      });

      // Run on table redraw (DataTables pagination/search)
      $('#entry_data').on('draw.dt', function() {
        updateSelectedCount();
        $('#selectAll').prop('checked', false); // Reset master checkbox
      });
      var i = 1;
      $(document).on('click', '.view_data', function() {
        var employee_id = $(this).attr("id");
        if(employee_id != '') {
          $.ajax({
            url: "select.php",
            method: "POST",
            data: { employee_id: employee_id },
            success: function(data) {
              $('#employee_detail').html(data);
              $('#dataModal').modal('show');
            }
          });
        }
      });
    $(document).on('click', '.delete_data', function() { 
        var employee_id = $(this).attr("id");
        if (employee_id != '') {
            if(confirm("Are you sure you want to delete this record?")) {
                $.ajax({
                    url: 'delete_record.php',
                    method: 'POST',
                    data: { employee_id: employee_id },
                    success: function(response) {
                        if (response.trim() == 'success') {
                            alert('Record deleted successfully.');
                            location.reload(); // reload page or refresh table
                        } else {
                            alert('Failed to delete the record.');
                        }
                    },
                    error: function() {
                        alert('Error connecting to server.');
                    }
                });
            }
        }
      });

    let selectedStatuses = [];
    let selectedDocTypes = [];
    let selectedCategories = [];

    const table = $('#employee_data').DataTable({
      "scrollX": true,
      "processing": true,
      "serverSide": true,
      "ajax": {
            url: "fetch_data.php",
            type: "POST",
            "data": function (d) {
                // Add date filters to AJAX request
                d.date1 = $('#date1').val();
                d.date2 = $('#date2').val();
                d.statuses = selectedStatuses;
                d.docTypes = selectedDocTypes;
                d.categories = selectedCategories;
            }
      },
      "stateSave": true,
      "lengthMenu": [ [10, 25, 50, 100], [10, 25, 50, 100] ],
      dom: 'Bflrtip',
      buttons: [
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
      ],
      "columns": [
            { "data": 0 },  // Mail icon
            { "data": 1 },  // ID
            { "data": 2 },  // Date
            { "data": 3 },  // Patron
            { "data": 4 },  // Category
            { "data": 5 },  // Bibliographic Details
            { "data": 6 },  // Journal Name
            { "data": 7 },  // Document Type
            { "data": 8 },  // Status
            { "data": 9 },   // Actions
            { "data": 10 } // Download
      ]
    });

    function updateFilters() {
      selectedStatuses = $('.status-filter:checked').map(function () {
        return this.value;
      }).get();

      selectedDocTypes = $('.doc-type-filter:checked').map(function () {
        return this.value;
      }).get();

      selectedCategories = $('.category-filter:checked').map(function () {
        return this.value;
      }).get();

      table.ajax.reload(); // Reload DataTable with updated filters
    }

    $('.status-filter, .doc-type-filter, .category-filter').on('change', updateFilters);

    $('.status-checkbox').on('change', function () {
      selectedStatuses = $('.status-checkbox:checked').map(function () {
        return $(this).val();
      }).get();

      table.ajax.reload();
    });

    $('#employee_data_length select').addClass('form-select');

    // Reload DataTable when the form is submitted
    $('#dateFilterForm').on('submit', function (e) {
        e.preventDefault(); // Prevent default form submit
        $('#employee_data').DataTable().draw(); // Redraw DataTable with date filters
    });

    // Add new row
    $('#add_input').click(function () {
      i++;
      var row = `<tr id="row${i}">
        <td style="width:98%">
          <select name="document_type[]" class="form-control document_type_select" required>
            <?php
              $doc = mysqli_query($conn, "SELECT * FROM document_type");
              while ($get = mysqli_fetch_array($doc)) {
                echo '<option value="'.htmlspecialchars($get['Document_type']).'">'.htmlspecialchars($get['Document_type']).'</option>';
              }
            ?>
          </select>
          <br>
          <input type="text" name="journal_name[]" class="form-control journal_name_input" autocomplete="off" placeholder="Journal Name" />
          <br>
          <textarea name="name[]" class="form-control biblio_det_textarea" placeholder="Bibliographic details" required></textarea>
          <br>
        </td>
        <td style="width:2%" class="align-middle">
          <button type="button" name="remove" id="${i}" class="btn btn-danger btn-sm btn_remove">
            <i class="fa fa-minus-circle" aria-hidden="true"></i>
          </button>
        </td>
      </tr>`;
      $('#dynamic tbody').append(row);
    });

    // Remove row
    $(document).on('click', '.btn_remove', function () {
      var button_id = $(this).attr('id');
      $('#row' + button_id).remove();
    });

    // Form submission validation and ajax
    $('#insert_form').on("submit", function (event) {
      event.preventDefault();

      // Simple validation for required fields
      if (!$('#req_date').val()) {
        alert("Request Date is required");
        return;
      }
      if (!$('#req_by').val()) {
        alert("Request By is required");
        return;
      }
      if (!$('#category').val()) {
        alert("Category is required");
        return;
      }
      // Validate dynamic fields (at least one row)
      var valid = true;
      $('#dynamic tbody tr').each(function () {
        if (!$(this).find('.document_type_select').val() ||
          !$(this).find('.journal_name_input').val() ||
          !$(this).find('.biblio_det_textarea').val()) {
          valid = false;
          return false; // break loop
        }
      });
      if (!valid) {
        alert("Please fill all bibliographic details in each row.");
        return;
      }

      // Submit via AJAX
      $.ajax({
        url: "insert.php",
        method: "POST",
        data: $('#insert_form').serialize(),
        beforeSend: function () {
          $('#insert').val("Inserting...");
        },
        success: function (data) {
          $('#insert_form')[0].reset();
          $('#add_data_Modal').modal('hide');
          $('#employee_data').DataTable().ajax.reload(null, false); // Refresh table or data as needed
          $('#insert').val("Insert");
        }
      });
    });

    // Initialize typeahead for static fields
    function initTypeahead(selector, sourceUrl) {
      $(selector).typeahead({
        source: function (query, process) {
          return $.ajax({
            url: sourceUrl,
            type: 'POST',
            data: { query: query },
            dataType: 'json',
            success: function (result) {
              return process(result);
            }
          });
        }
      });
    }

    // For static inputs
    initTypeahead('#jour', 'fetch1.php');
    initTypeahead('#req_by', 'fetch2.php');

    // Since dynamic rows added later, delegate typeahead initialization on focus
    $(document).on('focus', '.journal_name_input', function () {
      if (!$(this).data('typeahead')) { // Prevent multiple init
        $(this).typeahead({
          source: function (query, process) {
            return $.ajax({
              url: 'fetch1.php',
              type: 'POST',
              data: { query: query },
              dataType: 'json',
              success: function (result) {
                return process(result);
              }
            });
          }
        });
      }
    });
  });
</script>
