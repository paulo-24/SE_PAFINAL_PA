<?php
    include("php/database.php");
if(isset($_GET['overall_total'])){
    $total = $_GET['overall_total'];
    $id = $_GET['id'];
}else{
    $total = "";
    $id = "";
}
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Attendance</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
        <link rel="stylesheet" href="css/attendance.css">
        <link rel="stylesheet" href="css/topsidenavbars.css">   
        <link rel="icon" href="images/bigbrewpic2.jpg">

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </head>
    <style>
            .btn-active{
                background-color: rgb(134, 87, 25);
                width: 260px;
                }
                .btn-reset{
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
                .btn-reset:hover{
                    background-color: orangered;
                }
                .btn-reset:active{
                    background-color: #000000;
                }
                
                #btn{
                    padding:5px;
                }
                #btn:hover{
                    background:white;
                }
        </style>
    <body>
        <header class="header">
            <nav class="topnav">
                <a class="active" href="index.php">Logout</a>  
            </nav>  

        </header>
        <section class="sidebar">
            <div class="logo-sidebar">ADMIN </div>
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
                <div class="logo-main">Daily Time Record</div>
                    <div class="attendance-list">  
                        <table id="attendanceTable" class="table">
                                <div class="reset-button-container">
                   
                            <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Morning&nbsp;In & Out</th>
                                <!-- <th>Morning Out</th> -->
                                <th>Afternoon In & Out</th>
                                <!-- <th>Afternoon Out</th> -->
                                <th>AM Total Hours</th>
                                <th>PM Total Hours</th>
                                <th>Total AM and PM</th> 
                                <th>Status</th>
                                <th>Action</th>
                            </tr>

                            </thead>
                            <tbody>
                                <!-- Dito ilalagay ng script ang attendance data -->
                            </tbody>
                        </table>
                        <!-- <button id="resetAttendance" class="btn-reset">Reset Table</button> -->
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="employee_id" value="<?= $id ?>">
        <input type="hidden" id="total" value="<?= $total ?>">
        </main>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="js/camera.js"></script> <!-- Include camera.js -->
        
        <script>
            // Attach click event to reset button
/*             $('#resetAttendance').on('click', function() {
                // Display SweetAlert confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, reset it!'
                }).then((result) => {
                    // If user confirms the reset action
                    if (result.isConfirmed) {
                        // Perform the reset action
                        resetAttendance();
                    }
                });
            });

            // Function to reset the attendance table
            function resetAttendance() {
                $.ajax({
                    url: 'delete_attendance.php',
                    type: 'POST',
                    data: {
                        action: 'resetAttendance'
                    },
                    success: function(response) {
                        console.log(response);
                        // Clear the table after successful reset
                        $('#attendanceTable tbody').empty();
                        // Display success message using SweetAlert
                        Swal.fire(
                            'Reset!',
                            'Attendance table has been reset.',
                            'success'
                        );
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        // Display error message using SweetAlert
                        Swal.fire(
                            'Error!',
                            'Failed to reset attendance table.',
                            'error'
                        );
                    }
                });
            } */


    $(document).ready(function() {

        // Function to fetch attendance data periodically
        function fetchAttendancePeriodically() {
            fetchAttendanceData(); // Initial fetch
            setInterval(fetchAttendanceData, 2000); // Fetch every 2 seconds (adjust as needed)
        }

        // Call the function to start fetching attendance data periodically
        fetchAttendancePeriodically();
        
        // Function to format total hours and minutes
        function formatHours(totalHours) {
        var hours = Math.floor(totalHours);
        var minutes = Math.floor((totalHours - hours) * 60);
        
        // Formatting hours and minutes
        var formattedHours = hours < 10 ? '0' + hours : hours;
        var formattedMinutes = minutes < 10 ? '0' + minutes : minutes;

        return formattedHours + ':' + formattedMinutes;
    }


        function fetchAttendanceData() {
            var employee_id = document.getElementById('employee_id').value
            var total = document.getElementById('total').value
            console.log("Fetching Attendance data...")
            $.ajax({
                url: 'attendance_employee.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'getAttendance',
                    employee_id:employee_id,
                    total:total
                },
                success: function(data) {
                    console.log("Received data:", data);
                    populateAttendanceTable(data);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
      
        function populateAttendanceTable(data) {
            console.log("Populating table with data:", data);
            var table = $('#attendanceTable tbody');
            table.empty();
            
            $.each(data, function(index, record) {
                // Example value, replace it with the actual value
                var button_html ="";
                // Check if applied_OT is empty (or null)
               
                // Format time values to 12-hour format AND TO BE DELETED
                var morningIn = record.formatted_morning_time_in|| '';
                var morningOut = record.formatted_morning_time_out|| '';
                var afternoonIn = record.formatted_afternoon_time_in|| '';
                var afternoonOut = record.formatted_afternoon_time_out|| '';
                var otIn = record.formatted_overtime_time_in|| '';
                var otOut = record.formatted_overtime_time_out|| '';

                var morningTotal = parseFloat(record.morning_total_hours);
                var afternoonTotal = parseFloat(record.afternoon_total_hours);
                var overtimeTotal = parseFloat(record.overtime_total_hours);

                // Calculate overall total hours
                var overallTotalHours = morningTotal + afternoonTotal;

                // Calculate morning total hours -FIXED
                var morningHours = Math.floor(morningTotal);    
                var morningMinutes = Math.floor((morningTotal - morningHours) * 60);
                var formattedMorningTotal = formatHours(morningTotal);

                // Calculate afternoon total hours 
                var afternoonHours = Math.floor(afternoonTotal);
                var afternoonMinutes = Math.floor((afternoonTotal - afternoonHours) * 60);
                var formattedAfternoonTotal = formatHours(afternoonTotal);
                var total = parseInt(record.morning_total_hours)+parseInt(record.afternoon_total_hours);
              

                var formattedMorningTotal = isNaN(morningTotal) ? '' : formatHours(morningTotal);
                var formattedAfternoonTotal = isNaN(afternoonTotal) ? '' : formatHours(afternoonTotal); 
                var formattedOverallTotal = isNaN(overallTotalHours) ? '' : formatHours(overallTotalHours);
                var formattedOvertimeTotalHours = isNaN(overtimeTotal) ? '' : formatHours(overtimeTotal)
                var image_path = record.image_path

                if (record.applied_OT == "" && total > 8 ) {
                    // If applied_OT is empty, create the button with visibility
                    button_html = '<button onclick="window.location.href = \'employee_info.php?id=' + record.id + '\';" id="btn" style="background:rgb(209, 147, 74); color:white; border:1px solid white;">Edit</button>';
                } else {
                    // If applied_OT is not empty, hide the button
                    button_html = ''; // Empty string, no button will be created
                }
                var row = '<tr>' +
                    '<td><div style="width:100%; text-align:center; float:center; display:flex; justify-content:center;"><div style="width:100px; height:100px; border-radius:100px; overflow:hidden; float:center;"><img src="'+image_path+'" style="width:100%; min-height:100px; object-fit:cover;" alt="Image"></div></div></td>' +
                    '<td>' + record.name + '</td>' +
                    '<td>' + morningIn + '-' + morningOut + '</td>' +
                    /* '<td>' + morningOut + '</td>' + */
                    '<td>' + afternoonIn + '-' + afternoonOut +'</td>' +
                    /* '<td>' + afternoonOut + '</td>' + */
                    '<td>' + record.morning_total_hours + '</td>' +
                    '<td>' + formattedAfternoonTotal + '</td>' +    
                    '<td>' + record.overall_total_hours + '</td>' + // Display the overall total hours
                    '<td>' + record.status + '</td>' +
                    '<td>'+button_html+'</td>'
                    '</tr>';
                
                table.append(row);
            });
            // Attach click event to delete buttons
            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                var recordId = $(this).data('id');
                deleteAttendanceRecord(recordId);
            });

            // Attach click event to reset button
            $('#resetAttendance').on('click', function() {
                console.log("Resetting Attendance Table...");
                resetAttendance();
            });
            
            // Function to reset the attendance table
            function resetAttendance() {
                $.ajax({
                    url: 'delete_attendance.php',
                    type: 'POST',
                    data: {
                        action: 'resetAttendance'
                    },
                    success: function(response) {
                        console.log(response);
                        // Clear the table after successful reset
                        $('#attendanceTable tbody').empty();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }

            // Attach click event to download links
            $('.download-link').on('click', function(e) {
                e.preventDefault();
                var base64Image = $(this).data('image');
                downloadImage(base64Image);
            });
        }

        // Function to convert base64 string to a Blob object and download it
        function downloadImage(base64Image) {
            // Your download image function here
        }

        // Function to format time to 12-hour format AND TO BE DELETED
        function formatTime(timeString) {
        if (!timeString) return ''; // Handle empty time values

        // Check if timeString is in 'Y-m-d H:i:s' format
        if (/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/.test(timeString)) {
            var time = new Date(timeString);
            var formattedTime = time.toLocaleString('en-US', {
                hour: 'numeric',
                minute: 'numeric',
                hour12: true
            });

            return formattedTime;
        } else {
            return timeString; // Return as is if not in expected format
        }
    }
        fetchAttendanceData();
    });
        // Function to perform time-in or time-out action
        function performAction(action) {
            var employeeName = document.getElementById('employeeSelect').value;
            $.ajax({
                url: 'attendance_employee.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    action: action,
                    employeeName: employeeName
                },
                success: function(data) {
                    // Update the attendance table with the returned data
                    populateAttendanceTable(data);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

            // Event listener for time-in button
            document.getElementById('time-in-btn').addEventListener('click', function() {
                performAction('Time In');   
            });

            // Event listener for time-out button
            document.getElementById('time-out-btn').addEventListener('click', function() {
                performAction('Time Out');
            });

            // Event listener for time-out button
            document.getElementById('overtime-time-in-btn').addEventListener('click', function() {
                performAction('Overtime Time In');
            });

            // Event listener for time-out button
            document.getElementById('overtime-time-out-btn').addEventListener('click', function() {
                performAction('Overtime Time Out');
            });
        </script>
    </body>
    </html>