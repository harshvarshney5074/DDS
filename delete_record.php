<?php
include('dbcon.php');

if (isset($_POST['employee_id'])) {
    $delete_pro = $_POST['employee_id'];

    $sel_pro = mysqli_query($conn, "DELETE FROM entry WHERE Sr_no='$delete_pro'");
    $del_inst = mysqli_query($conn, "DELETE FROM institute_list WHERE entry_id='$delete_pro'");
    $del_rec = mysqli_query($conn, "DELETE FROM receive_list WHERE entry_id='$delete_pro'");

    if ($sel_pro || $del_inst || $del_rec) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "no_id";
}
?>
