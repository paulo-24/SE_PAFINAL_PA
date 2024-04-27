<?php
include("php/database.php");

if (
    isset($_POST['action']) &&
    isset($_POST['employeeName']) &&
    !empty($_POST['action']) &&
    !empty($_POST['employeeName'])
) {
    $action = $_POST['action'];
    $employeeName = $_POST['employeeName'];

    date_default_timezone_set('Asia/Manila');
    $currentDateTime = date('Y-m-d H:i:s');
    $response = ""; // Initialize response variable

    $sqlEmployee = "SELECT id FROM employees WHERE name = '$employeeName'";
    $resultEmployee = mysqli_query($connection, $sqlEmployee);

    if (!$resultEmployee) {
        $response = "Error: " . mysqli_error($connection);
    } else {
        if (mysqli_num_rows($resultEmployee) > 0) {
            $rowEmployee = mysqli_fetch_assoc($resultEmployee);
            $employeeId = $rowEmployee['id'];
    
            // Check if it's within the normal working hours
            $morningStart = date('Y-m-d') . " 08:00:00"; 
            $morningEnd = date('Y-m-d') . " 12:10:00";
            $afternoonStart = date('Y-m-d') . " 13:00:00";
            $afternoonEnd = date('Y-m-d') . " 24:00:00";
    
            $currentTime = strtotime($currentDateTime);
    
            // Check if employee has a morning entry
            $sqlCheckMorning = "SELECT * FROM attendance WHERE name = '$employeeName' AND DATE(created_at) = CURDATE()";
            $resultCheckMorning = mysqli_query($connection, $sqlCheckMorning);
            $row_result = mysqli_fetch_assoc($resultCheckMorning);
            if (!$resultCheckMorning) {
                $response = "Error: " . mysqli_error($connection);
            } else {
                if ($currentTime >= strtotime($morningStart) && $currentTime < strtotime($morningEnd)) {
                    // Employee is within morning shift
                    if ($action === 'Time In') {
                        if ($currentTime > strtotime(date('Y-m-d') . " 08:05:00")) {
                            $status = '<span style="color: red;">Time In (Late)</span>';
                        } else {
                            $status = '<span style="color: green;">Time In</span>';
                        }
                        $sqlInsertMorning = "INSERT INTO attendance (employee_id,name, morning_time_in, status) VALUES ('$employeeId','$employeeName', '$currentDateTime', '$status')";
    
                        if (mysqli_query($connection, $sqlInsertMorning)) {
                            $response = "TIME - IN (Morning) " . date('g:i A', strtotime($currentDateTime)) . " ✔️ ";
                        } else {
                            $response = "Error inserting morning entry: " . mysqli_error($connection);
                        }
                    } else if ($action === 'Time Out') {
                        // Find the latest morning entry for the employee
                        $sqlLatestMorning = "SELECT * FROM attendance WHERE name = '$employeeName' AND morning_time_in IS NOT NULL ORDER BY morning_time_in DESC LIMIT 1";
                        $resultLatestMorning = mysqli_query($connection, $sqlLatestMorning);
    
                        if (!$resultLatestMorning) {
                            $response = "Error: " . mysqli_error($connection);
                        } else {
                            if (mysqli_num_rows($resultLatestMorning) > 0) {
                                $rowLatestMorning = mysqli_fetch_assoc($resultLatestMorning);
                                $morningId = $rowLatestMorning['id'];
                                $sqlUpdateMorning = "UPDATE attendance SET morning_time_out = '$currentDateTime', morning_total_hours = TIMESTAMPDIFF(SECOND, morning_time_in, '$currentDateTime') / 3600, status = 'Time Out' WHERE id = $morningId AND DAY(attendance.created_at)=DAY(NOW()) AND MONTH(attendance.created_at)=MONTH(NOW())";
    
                                if (mysqli_query($connection, $sqlUpdateMorning)) {
                                    $response = "TIME - OUT (Morning) " . date('g:i A', strtotime($currentDateTime)) . " ✔️ ";
                                } else {
                                    $response = "Error updating morning entry: " . mysqli_error($connection);
                                }
                            } else {
                                $response = "Cannot Time Out. No corresponding Time In.";
                            }
                        }
                    }
                } else if ($currentTime >= strtotime($afternoonStart) ) {
                    $day = date('d');
                    $month = date('m');
                    if ($currentTime > strtotime(date('Y-m-d') . " 13:05:00")) {
                        $status = '<span style="color: red;">Time In (Late)</span>';
                    } else {
                        $status = '<span style="color: green;">Time In</span>';
                    }
                   
                    $check_user_attendance = mysqli_query($connection,"SELECT * FROM attendance WHERE morning_time_in!='' AND name='$employeeName' AND DAY(created_at)=$day AND MONTH(created_at)=$month");
                    if(mysqli_num_rows($check_user_attendance)>=1){
                        $sqlInsertAfternoon = "UPDATE attendance SET afternoon_time_in='$currentDateTime',status='$status' WHERE name='$employeeName' AND DAY(created_at)=$day AND MONTH(created_at)=$month";
                    }else{
                        $sqlInsertAfternoon = "INSERT INTO attendance (employee_id,name, afternoon_time_in, status) VALUES ('$employeeId','$employeeName', '$currentDateTime', '$status')";
                    }
                    // Employee is within afternoon shift
                    if ($action === 'Time In') {
                       
                       //$sqlInsertAfternoon = "INSERT INTO attendance (name, afternoon_time_in, status) VALUES ('$employeeName', '$currentDateTime', '$status')";
                        if (mysqli_query($connection, $sqlInsertAfternoon)) {
                            $response = "TIME - IN (Afternoon) " . date('g:i A', strtotime($currentDateTime)) . " ✔️ ";
                        } else {
                            $response = "Error inserting afternoon entry: " . mysqli_error($connection);
                        }
                    } else if ($action === 'Time Out') {
                        // Find the latest afternoon entry for the employee
                        $sqlLatestAfternoon = "SELECT * FROM attendance WHERE name='$employeeName' AND DAY(created_at)='$day' AND MONTH(created_at)='$month'";
                        //$sqlLatestAfternoon = "SELECT * FROM attendance WHERE name = '$employeeName'AND afternoon_time_in IS NOT NULL ORDER BY afternoon_time_in DESC LIMIT 1";
                        $resultLatestAfternoon = mysqli_query($connection, $sqlLatestAfternoon);

                        if (!$resultLatestAfternoon) {
                            $response = "Error: " . mysqli_error($connection);
                        } else {
                            if (mysqli_num_rows($resultLatestAfternoon) > 0) {
                                $rowLatestAfternoon = mysqli_fetch_assoc($resultLatestAfternoon);
                                $afternoonId = $rowLatestAfternoon['id'];
                                $sqlUpdateAfternoon = "UPDATE attendance SET afternoon_time_out = '$currentDateTime', afternoon_total_hours = TIMESTAMPDIFF(SECOND, afternoon_time_in, '$currentDateTime') / 3600, status = 'Time Out' WHERE id = $afternoonId AND DAY(created_at)=DAY(NOW()) AND MONTH(created_at)=MONTH(NOW())";

                                if (mysqli_query($connection, $sqlUpdateAfternoon)) {
                                    $response = "TIME - OUT (Afternoon) " . date('g:i A', strtotime($currentDateTime)) . " ✔️ ";
                                } else {
                                    $response = "Error updating afternoon entry: " . mysqli_error($connection);
                                }
                            } else {
                                $response = "Cannot Time Out. No corresponding Time In.";
                            }
                        }
                    }
                } else if ($currentTime <= strtotime($morningStart)) {
                    // Employee is within overtime shift
                    if ($action === 'Time In') {
                        if ($currentTime > strtotime(date('Y-m-d') . " 08:05:00")) {
                            $status = '<span style="color: red;">Time In (Late)</span>';
                        } else {
                            $status = '<span style="color: green;">Time In</span>';
                        }
                        $sqlInsertOvertime = "INSERT INTO attendance (employee_id,name, morning_time_in, status) VALUES ('$employeeId','$employeeName', '$currentDateTime', '$status')";

                        if (mysqli_query($connection, $sqlInsertOvertime)) {
                            $response = "TIME IN " . date('g:i A', strtotime($currentDateTime)) . " ✔️ ";
                        } else {
                            $response = "Error inserting overtime entry: " . mysqli_error($connection);
                        }
                    } else if ($action === 'Time Out') {
                        // Find the latest overtime entry for the employee
                        $sqlLatestOvertime = "SELECT * FROM attendance WHERE name = '$employeeName' AND morning_time_in IS NOT NULL ORDER BY overtime_time_in DESC LIMIT 1";
                        $resultLatestOvertime = mysqli_query($connection, $sqlLatestOvertime);

                        if (!$resultLatestOvertime) {
                            $response = "Error: " . mysqli_error($connection);
                        } else {
                            if (mysqli_num_rows($resultLatestOvertime) > 0) {
                                $rowLatestOvertime = mysqli_fetch_assoc($resultLatestOvertime);
                                $overtimeId = $rowLatestOvertime['id'];
                                $sqlUpdateOvertime = "UPDATE attendance SET overtime_time_out = '$currentDateTime', overtime_total_hours = TIMESTAMPDIFF(SECOND, overtime_time_in, '$currentDateTime') / 3600, status = 'Overtime Out' WHERE id = $overtimeId AND DAY(created_at)=DAY(NOW()) AND MONTH(created_at)=MONTH(NOW())";

                                if (mysqli_query($connection, $sqlUpdateOvertime)) {
                                    $response = "OVERTIME - OUT " . date('g:i A', strtotime($currentDateTime)) . " ✔️ ";
                                } else {
                                    $response = "Error updating overtime entry: " . mysqli_error($connection);
                                }
                            } else {
                                $response = "Cannot Time Out. No corresponding Time In.";
                            }
                        }
                    }
                }
            }
        } else {
            $response = "Employee not found.";
        }
    }

    echo $response;
} else if (isset($_POST['action']) && $_POST['action'] === 'getAttendance') {
    $sql = "SELECT *, 
            DATE_FORMAT(morning_time_in, '%h:%i %p') AS formatted_morning_time_in,
            DATE_FORMAT(morning_time_out, '%h:%i %p') AS formatted_morning_time_out,
            DATE_FORMAT(afternoon_time_in, '%h:%i %p') AS formatted_afternoon_time_in,
            DATE_FORMAT(afternoon_time_out, '%h:%i %p') AS formatted_afternoon_time_out,
            DATE_FORMAT(overtime_time_in, '%h:%i %p') AS formatted_overtime_time_in,
            DATE_FORMAT(overtime_time_out, '%h:%i %p') AS formatted_overtime_time_out,
            TIMESTAMPDIFF(SECOND, morning_time_in, morning_time_out) AS morning_total_seconds,
            TIMESTAMPDIFF(SECOND, afternoon_time_in, afternoon_time_out) AS afternoon_total_seconds,
            (TIMESTAMPDIFF(SECOND, morning_time_in, morning_time_out) + TIMESTAMPDIFF(SECOND, afternoon_time_in, afternoon_time_out)) AS overall_total_seconds,
            TIMESTAMPDIFF(SECOND, overtime_time_in, overtime_time_out) AS overtime_total_seconds
            FROM attendance LEFT JOIN images as i ON (morning_time_in=i.created_at OR morning_time_out=i.created_at OR afternoon_time_in=i.created_at OR afternoon_time_out=i.created_at) LEFT JOIN employees ON employees.id=attendance.employee_id WHERE DAY(attendance.created_at)=DAY(NOW()) AND MONTH(attendance.created_at)=MONTH(NOW()) GROUP BY attendance.employee_id";

  

    $result = mysqli_query($connection, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }

    $attendanceData = array(); // Initialize an empty array

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Convert the 24-hour format times to 12-hour format
            $formattedMorningIn = date('h:i A', strtotime($row['morning_time_in']));
            $formattedMorningOut = date('h:i A', strtotime($row['morning_time_out']));
            $formattedAfternoonIn = date('h:i A', strtotime($row['afternoon_time_in']));
            $formattedAfternoonOut = date('h:i A', strtotime($row['afternoon_time_out']));
            $formattedOTIn = date('h:i A', strtotime($row['overtime_time_in']));
            $formattedOTOut = date('h:i A', strtotime($row['overtime_time_out']));
            $image = $row['image_path'];
            // Calculate morning total hours
            $morningTotalSeconds = $row['morning_total_seconds'];
            $morningTotalHours = floor($morningTotalSeconds / 3600); // 3600 seconds in an hour
            $morningTotalMinutes = floor(($morningTotalSeconds % 3600) / 60); // Remaining seconds converted to minutes

            // Correct the total hours if minutes are 60 or more
            if ($morningTotalMinutes >= 60) {
                $morningTotalHours += 1;
                $morningTotalMinutes -= 60;
            }

            // Format the total hours and minutes
            $formattedMorningTotalHours = sprintf('%02d', $morningTotalHours);
            $formattedMorningTotalMinutes = sprintf('%02d', $morningTotalMinutes);
            $formattedMorningTotal = $formattedMorningTotalHours . ':' . $formattedMorningTotalMinutes;

            // Add the formatted morning total to the row
            $row['formatted_morning_total'] = $formattedMorningTotal;

            // Calculate AFTERNOON total hours
            $afternoonTotalSeconds = $row['afternoon_total_seconds'];
            $afternoonTotalHours = floor($afternoonTotalSeconds / 3600); // 3600 seconds in an hour
            $afternoonTotalMinutes = floor(($afternoonTotalSeconds % 3600) / 60); // Remaining seconds converted to minutes

            // Correct the total hours if minutes are 60 or more
            if ($afternoonTotalMinutes >= 60) {
                $afternoonTotalHours += 1;
                $afternoonTotalMinutes -= 60;
            }

            // Format the total hours and minutes
            $formattedAfternoonTotalHours = sprintf('%02d', $afternoonTotalHours);
            $formattedAfternoonTotalMinutes = sprintf('%02d', $afternoonTotalMinutes);
            $formattedAfternoonTotal = $formattedAfternoonTotalHours . ':' . $formattedAfternoonTotalMinutes;

            // Add the formatted afternoon total to the row
            $row['formatted_afternoon_total'] = $formattedAfternoonTotal;

            // Calculate overtime total hours
            $overtimeTotalSeconds = $row['overtime_total_seconds'];
            $overtimeTotalHours = floor($overtimeTotalSeconds / 3600); // 3600 seconds in an hour
            $overtimeTotalMinutes = floor(($overtimeTotalSeconds % 3600) / 60); // Remaining seconds converted to minutes

            // Correct the total hours if minutes are 60 or more
            if ($overtimeTotalMinutes >= 60) {
                $overtimeTotalHours += 1;
                $overtimeTotalMinutes -= 60;
            }

            // Format the total hours and minutes
            $formattedOvertimeTotalHours = sprintf('%02d', $overtimeTotalHours);
            $formattedOvertimeTotalMinutes = sprintf('%02d', $overtimeTotalMinutes);
            $formattedOvertimeTotal = $formattedOvertimeTotalHours . ':' . $formattedOvertimeTotalMinutes;

            // Add the formatted overtime total to the row
            $row['formatted_overtime_total'] = $formattedOvertimeTotal;

            // Calculate overall total hours
            $overallTotalSeconds = $row['morning_total_seconds'] + $row['afternoon_total_seconds'];
            $overallTotalHours = floor($overallTotalSeconds / 3600); // Calculate overall total hours
            $overallTotalMinutes = floor(($overallTotalSeconds % 3600) / 60); // Remaining seconds converted to minutes

            // Correct the total hours if minutes are 60 or more
            if ($overallTotalMinutes >= 60) {
                $overallTotalHours += 1;
                $overallTotalMinutes -= 60;
            }

            $total = $row['morning_total_hours'] + $row['afternoon_total_hours'];

            // Format the total hours and minutes
            $formattedOverallTotalHours = sprintf('%02d', $overallTotalHours);
            $formattedOverallTotalMinutes = sprintf('%02d', $overallTotalMinutes);
            $formattedOverallTotal = $total . ':' . $formattedOverallTotalMinutes;
            // Add the formatted overall total to the row
            $row['formatted_overall_total'] = $formattedOverallTotal;
            
            $attendanceData[] = $row;
            $salary = $row['salary'];
            $pay_per_hour = $salary / 8;
            if($total >= 8){
                $total_pay = $salary;
            }else{
                $total_pay = ($salary / 8)*$row['overall_total_hours'];
                $dedution = $salary-$total_pay;
            }
            if($_POST['employee_id']!=''){
                $overall = $_POST['total'];
                $employee_id = $_POST['employee_id'];
                    mysqli_query($connection,"UPDATE attendance SET total_paid='$overall',applied_OT='Overtime' WHERE employee_id='$employee_id' AND MONTH(created_at)=MONTH(NOW()) AND DAY(created_at)=DAY(NOW())");
            }else{
                if($total_pay < $salary){
                    mysqli_query($connection,"UPDATE attendance SET deduction='-$dedution',applied_OT='Undertime',overall_total_hours='$formattedOverallTotal',total_paid='$total_pay' WHERE MONTH(created_at)=MONTH(NOW()) AND DAY(created_at)=DAY(NOW()) AND employee_id='".$row['id']."'");
                }else{
                    mysqli_query($connection,"UPDATE attendance SET overall_total_hours='$formattedOverallTotal',total_paid='$total_pay' WHERE MONTH(created_at)=MONTH(NOW()) AND DAY(created_at)=DAY(NOW()) AND employee_id='".$row['id']."'");
                }
            }
        }
    }
    
    echo json_encode($attendanceData); // Return the JSON encoded attendance data
} else {
    $response = "Invalid request. Please provide action and employee name.";
    echo $response;
}

?>