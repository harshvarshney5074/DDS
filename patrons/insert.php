<?php
include('dbcon.php');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roll_no = $_POST['roll_no'];
    $display_name = $_POST['display_name'];
    $email_id = $_POST['email_id'];
    $discipline = $_POST['discipline'];
    $program_name = $_POST['program_name'];
    $status = $_POST['status'];

    $check = mysqli_query($conn, "SELECT * FROM patrons WHERE Email_id='$email_id'");
    if (mysqli_num_rows($check) > 0) {
        $response['message'] = 'This entry already exists.';
    } else {
        $sql = mysqli_query($conn, "INSERT INTO patrons (Roll_no, Display_name, Email_id, Discipline, Program_name, Status)
            VALUES ('$roll_no','$display_name','$email_id','$discipline','$program_name','$status')");
        if ($sql) {
            $response['success'] = true;
            $response['message'] = 'Successfully inserted';
        } else {
            $response['message'] = 'Database error during insert';
        }
    }
}

echo json_encode($response);
