<?php  
 if(isset($_POST["employee_id"]))  
 {  
     $output = '';  
     include('dbcon.php'); 
     $id=$_POST["employee_id"];
     $result = mysqli_query($conn,"SELECT * FROM entry WHERE Sr_no = '".$_POST["employee_id"]."'");  
     $getcats=mysqli_query($conn,"select institutions.institute_name as institute_name from institutions,institute_list where institute_list.entry_id=$id and institute_list.institute_name=institutions.Sr_no");
     $getrec=mysqli_query($conn,"select institutions.institute_name as institute_name from institutions,receive_list where receive_list.entry_id=$id and receive_list.institute_name=institutions.Sr_no");
      
     $output .= '  
     <div class="table-responsive">
           <table class="table table-bordered">';  
          while($row = mysqli_fetch_array($result))  
          {  
                    $req_by=$row['Req_by'];
                    $req=mysqli_query($conn,"select Display_name from patrons where Sr_no='$req_by'");
                    $row1=mysqli_fetch_array($req);
                    $req_by1=$row1['Display_name'];
               $output .= '  
                    <tr>  
                         <td width="30%"><label>Request Date</label></td>  
                         <td width="70%">'.$row["Req_date"].'</td>  
                    </tr>  
                    <tr>  
                         <td width="30%"><label>Request By</label></td>  
                         <td width="70%">'.$req_by1.'</td>  
                    </tr>  
                    <tr>  
                         <td width="30%"><label>Category</label></td>  
                         <td width="70%">'.$row["Category"].'</td>  
                    </tr>  
                    <tr>  
                         <td width="30%"><label>Bibliographic Details</label></td>  
                         <td width="70%">'.$row["Bibliographic_details"].'</td>  
                    </tr> 
                         <tr>  
                         <td width="30%"><label>Document Type</label></td>  
                         <td width="70%">'.$row["Document_type"].'</td>  
                    </tr> 				
                    <tr>  
                         <td width="30%"><label>Journal Name</label></td>  
                         <td width="70%">'.$row["Journal_name"].'</td>  
                    </tr>  
                         <tr>  
                         <td width="30%"><label>Status</label></td>  
                         <td width="70%">'.$row["Status"].'</td>  
                    </tr>
                         </table>
                         <table>
                         
                         <tr>  
                         <td width="100%"><label>Requested Institutions</label></td>	
                         </tr>
                         <tr>';
                         
                         
                              
                              while($rowcats=mysqli_fetch_array($getcats)){
                              $output.= '
                              
                              <td width="100%">'.$rowcats["institute_name"].'</td>
                              </tr>';
                              }
                              
          
          $output .= '  
                         <tr>  
                         <td width="100%"><label>Received From</label></td>	
                         </tr>
                         <tr>';
                         
                         
                              
                              while($rowcats=mysqli_fetch_array($getrec)){
                              $output.= '
                              
                              <td width="100%">'.$rowcats["institute_name"].'</td>
                              </tr>';
                              }
          }
          
          $output .= '    </table>  
     </div>  
     ';  
     echo $output;  
}  
?>
 

