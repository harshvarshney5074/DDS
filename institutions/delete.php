<?php
if (isset($_POST["institute_id"])) {
    include('dbcon.php');
    $id = intval($_POST["institute_id"]);

    $query = "DELETE FROM institutions WHERE Sr_no = $id";

    if (mysqli_query($conn, $query)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
