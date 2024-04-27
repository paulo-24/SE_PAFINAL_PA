<?php
    include("php/database.php");

    if(isset($_POST['submit_OT'])){
        $overtime_amount = $_POST['overtime_amount'];
        $employee_id = $_POST['employeeID'];
        $total_pay = $_POST['totalPaid'];
        $overall_total = $total_pay+$overtime_amount;
        if($overtime_amount < 0){
            $insert = mysqli_query($connection,"UPDATE attendance SET overtime_cost='$overtime_amount',total_paid='$overall_total',applied_OT='Applied' WHERE employee_id='$employee_id' AND MONTH(created_at)=MONTH(NOW()) AND DAY(created_at)=DAY(NOW())");
        }else{
            $insert = mysqli_query($connection,"UPDATE attendance SET overtime_cost='$overtime_amount',total_paid='$overall_total',applied_OT='Applied' WHERE employee_id='$employee_id' AND MONTH(created_at)=MONTH(NOW()) AND DAY(created_at)=DAY(NOW())");
        }
        if($insert){
           header('location: attendance.php?id='.$employee_id.'&overall_total='.$overall_total);
        }
    }
if(isset($_GET['id'])){
        $employee_id = $_GET['id'];
        $get_attendance = mysqli_query($connection,"SELECT * FROM attendance INNER JOIN employees ON employees.id=attendance.employee_id WHERE attendance.employee_id=$employee_id AND MONTH(created_at)=MONTH(NOW()) AND DAY(created_at)=DAY(NOW())");
        if(mysqli_num_rows($get_attendance)>=1){
            $row_attendance = mysqli_fetch_assoc($get_attendance);
            $employee_id = $_GET['id'];
            $employee_name = $row_attendance['name'];
            $morning_total_hrs = $row_attendance['morning_total_hours'];
            $afternoon_total_hrs = $row_attendance['afternoon_total_hours'];
            $total_hours = $row_attendance['overall_total_hours'];
            $salary = $row_attendance['salary'];
            $pay_per_hour = $salary / 8;
            if($total_hours == 0){
                $total_pay = 0;
                $get_remaining_hrs = 0;
                $total_overtime = 0;
            }else{
                if($total_hours >= 8){
                    $total_pay = $salary;
                    $get_remaining_hrs = $total_hours - 8;
                    $total_overtime = $get_remaining_hrs * $pay_per_hour;
                }else{
                    $total_pay = ($salary / 8)*$total_hours;
                    $get_remaining_hrs = 0;
                }
            }
        }

}
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <link rel="stylesheet" href="css/attendance.css">
    <link rel="stylesheet" href="css/topsidenavbars.css">
    <link rel="icon" href="images/bigbrewpic2.jpg">

</head>
<style>
.btn-active {
    background-color: rgb(134, 87, 25);
    width: 260px;
}

.btn-reset {
    margin-right: 5px;
    margin-bottom: 5px;
    padding: 5px 15px;
    cursor: pointer;
    border: none;
    border-radius: 4px;
    background-color: #be5619;
    color: white;
    font-size: 14px;
}

.btn-reset:hover {
    background-color: orangered;
}

.btn-reset:active {
    background-color: #000000;
}

#btn {
    padding: 5px;
}

#btn:hover {
    background: white;
}

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f5f5;
}


.logo-main {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
}


.attendance-form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.attendance-form input {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

.attendance-form input[readonly] {
    background-color: #f9f9f9;
    cursor: not-allowed;
}

</style>

<body>
    <header class="header">
        <nav class="topnav">
            <a class="active" href="index.php">Logout</a>
        </nav>

    </header>
    <section class="sidebar">
        <div class="logo-sidebar">ADMIN</div>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-box"></i>Dashboard</a></li>
            <li><a href="attendance.php" class="btn-active"><i class="fas fa-equals"></i>DTR</a></li>
            <!--  <li><a href="employeelist.html"><i class="fas fa-users"></i>Employee List</a></li> -->
            <li><a href="positionlist.php"><i class="fas fa-user-tie"></i>Employee Management</a></li>
            <li><a href="schedule.php"><i class="fas fa-credit-card"></i>Schedule</a></li>
            <li><a href="DailyTimeRecord.php"><i class="fas fa-calendar"></i>Reports</a></li>
            <li><a href="admin_user.php"><i class="fas fa-user"></i>Admin Users</a></li>
        </ul>
        </div>
    </section>
    <main class="main">
        <div class="card-body">
            <div class="logo-main">Attendance Information</div>
            <div class="attendance-list">

                <div class="attendance-form">
                    <h2>Employee Details</h2>
                    <form action="" id="attendanceDetailsForm" method="POST">
                        <br>
                        <label for="employeeName">Employee Name:</label>
                        <input type="text" id="employeeName" name="employeeName" value="<?= $employee_name ?>" readonly>

                        <label for="employeeID">Employee ID:</label>
                        <input type="text" id="employeeID" name="employeeID" value="<?= $employee_id ?>" readonly>

                        <label for="amHours">Morning Hours:</label>
                        <input type="text" id="amHours" name="amHours" value="<?= $morning_total_hrs ?>" readonly>

                        <label for="pmHours">Afternoon Hours:</label>
                        <input type="text" id="pmHours" name="pmHours" value="<?= $afternoon_total_hrs ?>" readonly>

                        <div style="display:flex; justify-content:space-between;">
                            <div style="width:50%;">
                                <label for="totalHours">Total Hours:</label>
                                <input type="text" id="totalHours" name="totalHours" value="<?= $total_hours ?>"
                                    readonly>
                            </div>
                            &nbsp;
                            <div style="width:50%;">
                                <label for="totalHours">Overtime Hours:</label>
                                <input type="text" id="totalHours" name="totalHours" value="<?= $get_remaining_hrs ?>"
                                    readonly>
                            </div>
                        </div>
                        <div class="" style="display:flex; justify-content:space-between; left:0; width:100%;">
                            <div style="width:50%">
                                <label for="payPerHour">Pay per Hour:</label>
                                <input type="text" id="payPerHour" name="payPerHour" value="<?= $pay_per_hour ?>"
                                    readonly>
                            </div>
                            &nbsp;
                            <div style="width:50%;">
                                <label for="totalPay">Total Pay:</label>
                                <input type="text" id="totalPay" name="totalPaid" value="<?= $total_pay ?>" readonly>
                            </div>
                        </div>
                        <label for="deductions">Other Amount <i style="font-weight:500; font-size:12px;">(this must be
                                related to OT's Or Deduction *if you want do deduct just add negative before the value)</i>:</label>
                        <input type="text" id="deductions" name="overtime_amount" value="<?= $total_overtime ?>">
                        <div style="right:0; width:100%; text-align:right; margin-top:10px;">
                            <button type="submit" name="submit_OT" style="padding:5px; border-radius:10px; width:100px;">Submit</button>
                        </div>
                        <!-- Hidden field to store employee ID or any other necessary data -->
                        <!-- <input type="hidden" id="employeeId" name="employeeId" value="employeeIdValue"> -->

                        <!-- Optionally, you can add more fields here -->
                    </form>

                </div>
            </div>
        </div>

        </div>
        </div>
    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/camera.js"></script> <!-- Include camera.js -->

    <script>

    </script>
</body>

</html>