<?php  
if (isset($_POST["employee_id"])) {  
    $output = '';  
    include('dbcon.php'); 
    $id = intval($_POST["employee_id"]);

    $result = mysqli_query($conn, "SELECT * FROM entry WHERE Sr_no = '$id'");

    // Requested Institutions with mail date
    $getcats = mysqli_query($conn, "
          SELECT i.institute_name AS institute_name, l.req_date AS mail_date
          FROM institute_list l
          JOIN institutions i ON l.institute_name = i.Sr_no
          WHERE l.entry_id = $id
     ");


    // Received Institutions
    $getrec = mysqli_query($conn, "
        SELECT i.institute_name AS institute_name
        FROM receive_list r
        JOIN institutions i ON r.institute_name = i.Sr_no
        WHERE r.entry_id = $id
    ");

    $output .= '<div class="table-responsive">
        <table class="table table-bordered table-sm">';

    while ($row = mysqli_fetch_array($result)) {  
        // Fetch requestor display name
        $req_by = $row['Req_by'];
        $req = mysqli_query($conn, "SELECT Display_name FROM patrons WHERE Sr_no = '$req_by'");
        $row1 = mysqli_fetch_array($req);
        $req_by1 = $row1['Display_name'];

        $output .= '  
            <tr><td width="30%"><label>Request Date</label></td><td width="70%">' . htmlspecialchars($row["Req_date"]) . '</td></tr>
            <tr><td><label>Request By</label></td><td>' . htmlspecialchars($req_by1) . '</td></tr>
            <tr><td><label>Category</label></td><td>' . htmlspecialchars($row["Category"]) . '</td></tr>
            <tr><td><label>Bibliographic Details</label></td><td>' . nl2br(htmlspecialchars($row["Bibliographic_details"])) . '</td></tr>
            <tr><td><label>Document Type</label></td><td>' . htmlspecialchars($row["Document_type"]) . '</td></tr>
            <tr><td><label>Journal Name</label></td><td>' . htmlspecialchars($row["Journal_name"]) . '</td></tr>
            <tr><td><label>Status</label></td><td>' . htmlspecialchars($row["Status"]) . '</td></tr>
        ';
    }

    $output .= '</table>';

    // Requested Institutions
    $output .= '
        <h5 class="bg-primary text-white p-2 mt-3">Requested Institutions</h5>
        <table class="table table-bordered table-sm">
            <thead class="table-light"><tr><th>Institution Name</th><th>Mail Sent On</th></tr></thead>
            <tbody>';
    while ($rowcats = mysqli_fetch_array($getcats)) {
        $institute = htmlspecialchars($rowcats["institute_name"]);
        $date = htmlspecialchars($rowcats["mail_date"] ?? '—');
        $output .= "<tr><td>$institute</td><td>$date</td></tr>";
    }
    $output .= '</tbody></table>';

    // Received From
    $output .= '
        <h5 class="bg-success text-white p-2 mt-3">Received From</h5>
        <table class="table table-bordered table-sm">
            <thead class="table-light"><tr><th>Institution Name</th></tr></thead>
            <tbody>';
    while ($rowrec = mysqli_fetch_array($getrec)) {
        $institute = htmlspecialchars($rowrec["institute_name"]);
        $output .= "<tr><td>$institute</td></tr>";
    }
    $output .= '</tbody></table>';

    $output .= '</div>';  

    echo $output;  
}
?>
