<?php
include("../dbcon.php");

if (isset($_POST['id'])) {
  $id = intval($_POST['id']);
  $query = "DELETE FROM patrons WHERE Sr_no = $id";
  $result = mysqli_query($conn, $query);

  echo json_encode([
    "status" => $result ? "success" : "error",
    "message" => $result ? "Patron deleted." : "Deletion failed."
  ]);
}
?>
