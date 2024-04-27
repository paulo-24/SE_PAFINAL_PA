<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/topsidenavbars.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+OutLined">
    <link rel="icon" href="images/bigbrewpic2.jpg">
    <!-- Inserted script for fetching admin count -->
    <script>
        // Function to fetch total admin count
        function fetchAdminCount() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = xhr.responseText;
                        document.querySelector('.total-admin').textContent = response;
                    } else {
                        console.error('Error fetching admin count: ' + xhr.status);
                    }
                }
            };
            xhr.open('GET', 'admin_user_count.php', true);
            xhr.send();
        }


        /// Function to fetch total employee count
        var empXhr = new XMLHttpRequest();
        empXhr.onreadystatechange = function() {
            if (empXhr.readyState === XMLHttpRequest.DONE) {
                if (empXhr.status === 200) {
                    var empResponse = empXhr.responseText;
                    document.querySelector('.total-emp').textContent = empResponse;
                } else {
                    console.error('Error fetching employee count: ' + empXhr.status);
                }
            }
        };
        empXhr.open('GET', 'employee_count.php', true);
        empXhr.send();

        function fetchOvertimeCost() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        displayOvertimeCost(response); // Call the display function
                    } else {
                        console.error('Error fetching overtime cost: ' + xhr.status);
                    }
                }
            };
            xhr.open('GET', 'overtime_cost.php', true);
            xhr.send();
        }
        
       // Inside the displayOvertimeCost function
function displayOvertimeCost(data) {
    var overtimeCostContainer = document.querySelector('.total-overtime-cost');
    
    // Clear previous data
    overtimeCostContainer.innerHTML = '';
    
    // Initialize total cost variable
    var totalOvertimeCostCents = 0;
    
   
    
    // Convert total back to currency format
    var totalOvertimeCost = (totalOvertimeCostCents / 100).toFixed(2);
    
    // Display the total cost after rounding
    var totalOvertimeCostElement = document.createElement('span');
    totalOvertimeCostElement.textContent = data[0].overtime_cost;
document.getElementById('total-deduction').textContent = ''+data[0].deduction+'';

    overtimeCostContainer.appendChild(totalOvertimeCostElement);
}
        

        // Call the fetch function when the page loads
        fetchOvertimeCost();
         // Call the function when the page loads
    fetchAdminCount();  
</script>
</head>
<body>    
    <header class="header">
        <nav class="topnav">
            <a class="active" href="index.php">Logout</a>
            
           <!--<a class="logout-btn" href="index.php">Home</a>--> 
        </nav>     
    </header>
    <section class="sidebar">
              <div class="logo-sidebar">ADMIN</div>
                <ul>
                    <li><a href="dashboard.php" class="btn-active"><i class="fas fa-box"></i>Dashboard</a></li>
                    <li><a href="attendance.php"><i class="fas fa-equals"></i>DTR</a></li>
                    <!-- <li><a href="employeelist.html"><i class="fas fa-users"></i>Employee List</a></li> -->
                    <li><a href="positionlist.php"><i class="fas fa-user-tie"></i>Employee Management</a></li>
                    <li><a href="schedule.php"><i class="fas fa-credit-card"></i>Schedule</a></li>
                    <li><a href="DailyTimeRecord.php"><i class="fas fa-calendar"></i>Reports</a></li>
                    <li><a href="admin_user.php"><i class="fas fa-user"></i>Admin Users</a></li> 
                </ul>
        </div>
    </section>
    <main class="main">  
        <div class="card-body">
            <div class="logo-main">Dashboard</div>
            <div class="card-container">
                <div class="card-wrapper">
                    <div class="payment-card">
                        <div class="card-header">
                            <div class="amount"></div>
                            <span class="title">Admin</span> <i class="fas fa-user icon"></i><br><br>
                            <span class="title-total">Total Admin Account: </span>
                            <strong><span class="total-admin">0</span></strong></span>
                        </div>
                    </div>
                </div>
                <div class="card-wrapper">
                    <div class="payment-card">
                        <div class="card-header">
                            <div class="amount"></div>
                            <span class="title">Employers</span> <i class="fas fa-users icon"></i><br><br>
                            <span class="title-total">Total Employee: </span>
                            <strong><span class="total-emp">0</span></strong></span>
                        </div>
                    </div>
                </div>
                <div class="card-wrapper">
                    <div class="payment-card">
                        <div class="card-header">
                            <div class="amount"></div>
                            <span class="title">Overtime Cost</span> <i class="fas fa-clock icon"></i><br><br>
                            <span class="title-total">Total Cost:</span>
                            <strong><span class="total-overtime-cost">0</span></strong></span>
                        </div>
                    </div>
                </div>
                <div class="card-wrapper">
                    <div class="payment-card">
                        <div class="card-header">
                            <div class="amount"></div>
                            <span class="title">Average Salary</span> <i class="fas fa-dollar-sign icon"></i><br><br>
                            <span class="title-total">Total: </span>
                            <span class="total-emp">0</span>
                        </div>
                    </div>
                </div>
                <div class="card-wrapper">
                    <div class="payment-card">
                        <div class="card-header">
                            <div class="amount"></div>
                            <span class="title">Deduction Cost</span> <i class="fas fa-minus     icon"></i><br><br>
                            <span class="title-total">Total: </span>
                            <span id="total-deduction" style="font-weight:bold;">0</span>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </main>
</body>
</html>