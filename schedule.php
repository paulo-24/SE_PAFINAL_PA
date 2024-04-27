<?php
include("php/database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
     <link rel="stylesheet" href="css/schedule.css">
    <link rel="stylesheet" href="css/topsidenavbars.css">  
    <link rel="icon" href="images/bigbrewpic2.jpg">

    <style>
        textarea {
    border: none; /* Remove the border */
    outline: none; /* Remove the outline */
}

        /* Style for the day number */
.day-number {
    font-size: 12px;
    color: #333;
    margin-right: 5px;
    margin-top:-40px;
    left:0;
    font-weight:600;
    font-size:18px;
}
input[type="text"]{
    padding:20px;
    text-align:center;
    font-size:15px;
    border:none;
    outline:none;
}

/* Style for the container of day number and textbox */
.day-wrapper {
    display: flex;
    align-items: center;
}

    </style>
</head>
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
                    <li><a href="attendance.php"><i class="fas fa-equals"></i>DTR</a></li>
                   <!--  <li><a href="employeelist.html"><i class="fas fa-users"></i>Employee List</a></li> -->
                    <li><a href="positionlist.php"><i class="fas fa-user-tie"></i>Employee Management</a></li>
                    <li><a href="schedule.php" class="btn-active"><i class="fas fa-credit-card"></i>Schedule</a></li>
                    <li><a href="DailyTimeRecord.php"><i class="fas fa-calendar"></i>Reports</a></li>
                    <li><a href="admin_user.php"><i class="fas fa-user"></i>Admin Users</a></li> 
                </ul>
        </div>
    </section>
    <main class="main">  
        <div class="card-body">
            <div class="logo-main">Schedule list</div>

            <div class="container">
    <section class="ftco-section"> 
        <div class="container"> 
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-center mb-4" id="editableMonthTime"></h4>
                    <div class="table-wrap">
                        <table class="table table-bordered text-center" id="editableEmployeeTable">
                            <thead>
                                <tr>
                                    <th>Mon</th>
                                    <th>Tue</th>
                                    <th>Wed</th>
                                    <th>Thu</th>
                                    <th>Fri</th>
                                    <th>Sat</th>
                                    <th>Sun</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                            <?php
    // Retrieve existing data from your database based on the current month
    // Assuming $connection is your database connection
    $currentMonth = date('m');
    $query = "SELECT day, date_schedule FROM schedule WHERE MONTH(date) = $currentMonth";
    $result = mysqli_query($connection, $query);

    // Create an associative array to store existing data with day as key
    $existingData = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $existingData[$row['day']] = $row['date_schedule'];
    }

    /* // Loop through each day of the month to generate the HTML for the table
    $days_in_month = date('t');
    $start_day = date('N', strtotime(date('Y-m-01'))); // Get the day of the week for the first day of the month (1 = Monday, 7 = Sunday)
    for ($day = 1; $day <= $days_in_month; $day++) {
        if ($day == 1 || ($day == $days_in_month && $start_day != 7)) {
            echo "<tr>";
            for ($i = 1; $i < $start_day; $i++) {
                echo "<td></td>"; // Fill in empty cells for days before the first day of the month
            }
        }
        echo "<td>";
        echo "<div class='day-wrapper'>";
        echo "<span class='day-number'>$day</span>"; // Display only the day number
        echo "<textarea id='date_sched_$day' class='form-control date_sched' placeholder='Enter data here'>" . ($existingData[$day] ?? '') . "</textarea>";
        /* echo "<textarea ='text' id='date_sched_$day' class='form-control date_sched' placeholder='Enter data here'> '" . ($existingData[$day] ?? '') . "'</textarea>"; */
        /* echo "</div>";
        echo "</td>";
        if (($day + $start_day - 1) % 7 == 0) {
            echo "</tr>"; // End the row after Sunday
        } */ 
    // Loop through each day of the month to generate the HTML for the table
$days_in_month = date('t');
$start_day = date('N', strtotime(date('Y-m-01'))); // Get the day of the week for the first day of the month (1 = Monday, 7 = Sunday)
$current_day = 1; // Initialize the current day of the month

echo "<tr>"; // Start the first row
for ($day_of_week = 1; $day_of_week <= 7; $day_of_week++) {
    if ($day_of_week < $start_day) {
        echo "<td></td>"; // Fill in empty cells before the first day of the month
    } else {
        echo "<td>";
        echo "<div class='day-wrapper'>";
        echo "<span class='day-number'>$current_day</span>"; // Display the day number
        echo "<textarea id='date_sched_$current_day' class='form-control date_sched' placeholder='Enter data here'>" . ($existingData[$current_day] ?? '') . "</textarea>";
        echo "</div>";
        echo "</td>";
        $current_day++;
    }
}
echo "</tr>"; // End the first row

// Continue filling in the rest of the table
while ($current_day <= $days_in_month) {
    echo "<tr>"; // Start a new row for each week
    for ($day_of_week = 1; $day_of_week <= 7; $day_of_week++) {
        if ($current_day <= $days_in_month) {
            echo "<td>";
            echo "<div class='day-wrapper'>";
            echo "<span class='day-number'>$current_day</span>"; // Display the day number
            echo "<textarea id='date_sched_$current_day' class='form-control date_sched' placeholder='Enter data here'>" . ($existingData[$current_day] ?? '') . "</textarea>";
            echo "</div>";
            echo "</td>";
            $current_day++;
        } else {
            echo "<td></td>"; // Fill in empty cells for remaining days of the last week
        }
    }
    echo "</tr>"; // End the row
}

?>
                            </tbody>
                        </table>
                    </div>
                    <button class="Save-btn" onclick="saveData()" id="saveButton">Save</button> <!-- Save button -->
                </div>
            </div>
        </div>
    </section>
</div>



            
        </div>   
    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/schedule.js"></script>
</body>
</html>