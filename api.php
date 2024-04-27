<?php
include("php/database.php");

// Retrieve the data sent via POST
$data = $_POST;

// Loop through each day and process the data
foreach ($data as $day => $input_sched) {
    // Sanitize input data
    $day = mysqli_real_escape_string($connection, $day);
    $datemonth = date('m'); // Assuming $datemonth is the current month
    $input_sched = mysqli_real_escape_string($connection, $input_sched);
    
    // Only insert if input value is not empty
    if (!empty($input_sched)) {
        // Construct the full date
        $cvrtDate = date('Y') . '-' . $datemonth . '-' . $day;
        
        // Check if the record already exists
        $check_date = mysqli_query($connection, "SELECT * FROM schedule WHERE day='$day' AND MONTH(date)='$datemonth'");
        
        if (mysqli_num_rows($check_date) >= 1) {
            // Update the existing record
            mysqli_query($connection, "UPDATE schedule SET date_schedule='$input_sched' WHERE day='$day' AND MONTH(date)='$datemonth'");
        } else {
            // Insert a new record
            mysqli_query($connection, "INSERT INTO schedule(day, date_schedule, date) VALUES ('$day', '$input_sched', '$cvrtDate')");
        }
    }
}
?>
