<?php
    include("php/database.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/schedule_index.css">
    <style>
         textarea {
    border: none; /* Remove the border */
    outline: none; /* Remove the outline */
}
    .day-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .day-number {
        font-weight:bold;
        margin-bottom: 20px; /* Add some space between the day number and the input */
    }

    .form-control.date_sched {
        width: 90%; /* Adjust the width of the input to fit within the cell */
        margin: auto; /* Center the input horizontally */
    }
    </style>
</head>

<body>
    <div class="container-fluid" style="padding:100px;">
        <main class="main">
            <div class="row">
                <div class="col-md">
                    <h3 class="text-center mb-4" id="editableMonthTime"></h4>
                    <div class="table">
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
    echo "<table>"; // Start the table
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

        /* echo "<input type='text' class='form-control date_sched' style='border:none; background:transparent; outline:none' disabled id='date_sched_$day' placeholder='' value='" . ($existingData[$day] ?? '') . "'>"; */
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
                </div>
            </div>
    <button class="Save-btn" id="saveButton" onclick="window.location.href = 'index.php';">Back</button>

    </div>

    </section>
    </div>
    </div>

    </main>
    </div>





    <script>
    // Function to update month, date, and time in real-time
    function updateDateTime() {
        const now = new Date();
        const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
            'October', 'November', 'December'
        ];
        const month = months[now.getMonth()];
        const date = now.getDate();
        const year = now.getFullYear();
        const hours = now.getHours();
        const minutes = now.getMinutes();
        const seconds = now.getSeconds();

        const formattedDateTime = `${month} ${date}, ${year} ${hours}:${minutes}:${seconds}`;

        document.getElementById('nonEditableMonthTime').textContent = formattedDateTime;
    }

    // Update time every second
    setInterval(updateDateTime, 1000);

    // Initial call to update the time when the page loads
    updateDateTime();
    </script>

    <script src="js/schedule.js"></script>
</body>

</html>