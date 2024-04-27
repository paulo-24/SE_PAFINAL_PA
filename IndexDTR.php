<?php
    include("php/database.php");
    $name='';
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $filter="WHERE id='".$id."'";
        $get = mysqli_query($connection,"SELECT * FROM employees WHERE id='$id'");
        $row_get = mysqli_fetch_array($get);
        $name = $row_get['name'];
    }else{
        $id="";
        $filter="";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DTR</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <link rel="stylesheet" href="css/IndexDTR.css"> 
    <link rel="icon" href="images/bigbrewpic2.jpg">

    <style>
            #print-container::-webkit-scrollbar {
                width: 10px; /* Width of the scrollbar */
                background-color: #f1f1f1; /* Background color of the scrollbar track */
            }

            #print-container::-webkit-scrollbar-thumb {
                background-color: #888; /* Color of the scrollbar thumb */
            }
    </style>
</head>

<body>
 
    <main class="main">  
        <div class="card-body"> 
            <div class="container" id="print-container">
                <h2>--Daily Time Record--</h2>
                <div class="name">
                    <p>Name: <select id="employee_id" onchange="filter_employee()">
                        <option selected disabled><?php if(isset($_GET['id'])){ echo $name; }else{ echo 'Select Employee';} ?></option>
                        <?php
                         
                            $get_employee = mysqli_query($connection,"SELECT * FROM employees");
                            if(mysqli_num_rows($get_employee)>=1){
                                while($row_employee = mysqli_fetch_assoc($get_employee)){
                                    ?>
                                    <option value="<?= $row_employee['id'] ?>"><?= $row_employee['name'] ?></option>
                                    <?php
                                }
                            }
                        ?>
                    </select></p>
                </div>
                <br>
                <table id="dtr-table">
                    <thead>
                        <tr>
                            <th rowspan="2">Day</th>
                            <th colspan="2">MORNING</th>
                            <th colspan="2">AFTERNOON</th>
                            <th rowspan="2">Overtime/Undertime</th>
                            <th rowspan="2">Working Hours</th>
                            <th rowspan="2">Deduction</th>
                            <th rowspan="2">Amount Of Day Dutied</th>
                            <th rowspan="2">Status</th>
                        </tr>
                        <tr>
                            <th>In</th>
                            <th>Out</th>
                            <th>In</th>
                            <th>Out</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php
 $end_of_month = false;
 if(date('d') > 15){
         $end_of_month = true;
 }
$get_data = mysqli_query($connection,"SELECT * FROM attendance WHERE employee_id='$id' AND MONTH(created_at)=MONTH(NOW())");
if(mysqli_num_rows($get_data) >= 1){
    for($count = 1; $count <= 31; $count++) {
        $found = false;
        mysqli_data_seek($get_data, 0); // Reset pointer to the beginning of the result set
        while($row_data = mysqli_fetch_array($get_data)){
            $date = date('d',strtotime($row_data['created_at']));
            if($count == $date){ 
                $found = true;
                ?>
                <tr>     
                    <td><?= $count ?></td>
                    <td><?= date('h:i A',strtotime($row_data['morning_time_in'])) ?></td>
                    <td><?php if($row_data['morning_time_out']==""){echo '';}else{ echo date('h:i A',strtotime($row_data['morning_time_out']));} ?></td>
                    <td><?php if($row_data['afternoon_time_in']==""){echo '';}else{ echo date('h:i A',strtotime($row_data['afternoon_time_in']));} ?></td>
                    <td><?php if($row_data['afternoon_time_out']==""){echo '';}else{ echo date('h:i A',strtotime($row_data['afternoon_time_out']));} ?></td>
                    <td><?php if($row_data['overall_total_hours']=="0"){echo '';}else{ if($row_data['overall_total_hours'] > 8){echo $row_data['overall_total_hours']-8;}else{echo $row_data['overall_total_hours']-8;}} ?></td>
                    <td><?php if($row_data['overall_total_hours']=="0"){echo '';}else{ echo $row_data['overall_total_hours'].'&nbsp;Hours';} ?></td>
                    <td><?php if($row_data['deduction']=="0"){echo '';}else{ echo number_format($row_data['deduction'],2);} ?></td>
                    <td><?php if($row_data['total_paid']=="0"){echo '';}else{ echo number_format($row_data['total_paid'],2);} ?></td>
                    <td><?= $row_data['applied_OT'] ?></td>
                </tr> 
                <?php
                break; // Exit the loop once data for the current day is found
            }
        }
        if(!$found) {
            ?>
            <tr>     
                <td><?= $count ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr> 
            <?php
        }
    }
     // Calculate totals after processing all days
     $get_data1 = mysqli_query($connection, "SELECT *,SUM(total_paid) as total_half FROM attendance WHERE employee_id='$id' AND MONTH(created_at)=MONTH(NOW()) AND DAY(created_at) BETWEEN 1 AND 15");

     mysqli_data_seek($get_data1, 0);
     $row_income = mysqli_fetch_array($get_data1);
     $half_of_month = $row_income['total_half'];
       
     
     $get_data2 = mysqli_query($connection, "SELECT *,SUM(total_paid) as total_month FROM attendance WHERE employee_id='$id' AND MONTH(created_at)=MONTH(NOW()) AND DAY(created_at) BETWEEN 16 AND 31");
     mysqli_data_seek($get_data2, 0);
     $row_income = mysqli_fetch_array($get_data2);
     $monthly_income = $row_income['total_month'];
}
?>

                       
                    </tbody>                    
                </table>
                
                <div class="signature">
                <p>Insurance: <input type="text" style="border:none; border-bottom:1px solid black; outline:none;" value="<?php if($end_of_month == true) {echo $monthly_income * 0.08;}?>"></p>
                <p>Total Amount: <input type="text" style="border:none; border-bottom:1px solid black; outline:none;" value="<?php if($end_of_month == true) {echo $monthly_income;}?>"></p>
                    <!-- <p>Phil Health: <input type="text" style="border:none; border-bottom:1px solid black; outline:none;"></p>
                    <p>Pag-Ibig:  <input type="text" style="border:none; border-bottom:1px solid black; outline:none;"> </p>
                    <p>Employee's Signature:  <input type="text" style="border:none; border-bottom:1px solid black; outline:none;"></p> -->
                     <P>Summary: <input type="text" style="border:none; border-bottom:1px solid black; outline:none;" value="<?php if(isset($_GET['id']) && $end_of_month == true){ echo '&#8369;&nbsp;'.$monthly_income-($monthly_income*0.08); }else if(isset($_GET['id']) && $end_of_month == false){ echo '&#8369;&nbsp;'.$half_of_month; } ?>"></P>
                </div>

<!-- 
                <div class="export-btn">
                    <button onclick="openPrintWindow()">Print Now</button>
                </div> -->

            </div>
        </div>      
        <button class="Save-btn" id="saveButton" onclick="window.location.href = 'index.php';">Back</button>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.3/jspdf.umd.min.js"></script>
    <script src="js/DTR.js"></script>
    <script>
        function filter_employee(){
            var employee_id = document.getElementById('employee_id').value;
            window.location.href="indexDTR.php?id="+employee_id
        }
    </script>
</body>
</html>