<?php
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<?php  
include('dbcon.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!empty($_POST)) {  
    $output = '';  
    $message = '';  

    $req_date = test_input($_POST["req_date"]);  
    $req_by = test_input($_POST["req_by"]);
    $req_by1 = explode("-", $req_by);
    $req_by2 = $req_by1[0];

    $category = test_input($_POST["category"]);  
    $biblio_det = $_POST["name"]; 
    $status = test_input($_POST["status"]); 	  
    $document_type = $_POST["document_type"]; 
    $journal_name = $_POST["journal_name"];

    if ($_POST["employee_id"] != '') {  
        $query = "  
        UPDATE entry   
        SET Req_date='$req_date',   
            Req_by='$req_by',   
            Category='$category',   
            Bibliographic_details='$biblio_det',   
            Journal_name='$journal_name',
            Status='$status'
        WHERE id='".$_POST["employee_id"]."'";  

        $message = 'Data Updated';  
    } else {  
        $number = count($_POST["name"]);

        $ab = mysqli_query($conn, "SELECT * FROM patrons WHERE Sr_no='$req_by2'");
        $get = mysqli_fetch_array($ab);
        $re = $get['Email_id'];
        $name = $get['Display_name'];

        if (function_exists('date_default_timezone_set')) {
            date_default_timezone_set("Asia/Kolkata");
        }

        $todaydate = date('Y-m-d'); 
        $get_prev = mysqli_query($conn, "
            SELECT o.order_id 
            FROM orders o 
            WHERE o.username = '$re' 
              AND o.date = '$todaydate' 
              AND o.sent = 0
            ORDER BY o.order_id DESC
            LIMIT 1
        ");

        if (mysqli_num_rows($get_prev) > 0) {
            // Reuse the unsent order
            $get_order = mysqli_fetch_array($get_prev);
            $order_id = $get_order['order_id'];
        } else {
            // Create a new order
            $order = mysqli_query($conn, "INSERT INTO orders (username, Display_name, date, sent) VALUES ('$re', '$name', '$req_date', 0)");
            $order1 = mysqli_query($conn, "SELECT MAX(order_id) as order_id FROM orders");
            $row = mysqli_fetch_array($order1);
            $order_id = $row['order_id'];
        }


        if ($number > 0) {
            for ($i = 0; $i < $number; $i++) {
                $name = $_POST["name"][$i];
                $document_type = $_POST["document_type"][$i];
                $journal_name = $_POST["journal_name"][$i];

                $query = mysqli_query($conn, "  
                    INSERT INTO entry(Req_date, Req_by, Category, Bibliographic_details, Journal_name, Document_type, Status, order_id, entry_date)  
                    VALUES('$req_date', '$req_by2', '$category', '$name', '$journal_name', '$document_type', '$status', '$order_id', now())"
                );  

                $message = 'Data Inserted';  
            } 
        }	  
    }

    if ($query) {  
        $output .= '<label class="text-success">' . $message . '</label>'; 
        echo "<script>window.open('index.php','_self');</script>";
    }  
}
?>
