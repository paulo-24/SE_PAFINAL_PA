<?php
    session_start();
    include("php/database.php");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM employees";
    $result = mysqli_query($connection, $sql);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Positions</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
        <link rel="stylesheet" href="css/positionlist.css">
        <link rel="stylesheet" href="css/topsidenavbars.css"> 
        <link rel="icon" href="images/bigbrewpic2.jpg">

        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    </head>
    <style>
        .button-btn {
    display: inline-block;
    margin-right: 5px; /* Adjust as needed */
}

.separator {
    display: inline-block;
    margin: 0 5px; /* Adjust as needed */
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
                <li><a href="attendance.php"><i class="fas fa-equals"></i>DTR</a></li>
            <!--  <li><a href="employeelist.html"><i class="fas fa-users"></i>Employee List</a></li> -->
                <li><a href="positionlist.php" class="btn-active"><i class="fas fa-user-tie"></i>Employee Management</a></li>
                <li><a href="schedule.php"><i class="fas fa-credit-card"></i>Schedule</a></li>
                <li><a href="DailyTimeRecord.php"><i class="fas fa-calendar"></i>Reports</a></li>
                <li><a href="admin_user.php"><i class="fas fa-user"></i>Admin Users</a></li> 
            </ul>
        </section>
        <main class="main">  
            
            <div class="card-body">
                <div class="logo-main">User Management</div>
                <div style="padding:10px; background:#be5619; width:25%; border-radius:10px; text-align:center; color:white;"><a href="employeeform.php" style="text-decoration:none; color:White;"><i class="fas fa-paperclip"></i>&nbsp; Add Employee</a></div>

                <div class="attendance">
                    <div class="attendance-list"> 
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Address</th>
                                    <th>Employee ID</th>
                                    <th>Mobile No.</th>
                                    <th>Sex</th>
                                    <th>Position</th> 
                                    <th>Salary</th>
                                    <th>Start Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result && mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                   <!--  <td><?php echo $row["employee_id"]; ?></td>
                                    <td><?php echo $row["lname"]; ?></td>
                                    <td><?php echo $row["fname"]; ?></td>
                                    <td><?php echo $row["middle_initial"]; ?></td> -->
                                    <td><?php echo $row["name"]; ?></td>
                                    <td><?php echo $row["address"]; ?></td>
                                    <!-- <td><?php echo $row["barangay"]; ?></td>
                                    <td><?php echo $row["street"]; ?></td>
                                    <td><?php echo $row["city"]; ?></td>
                                    <td><?php echo $row["postal_code"]; ?></td> -->
                                    <td><?php echo $row["employee_id"]; ?></td>
                                    <td><?php echo $row["mobile_number"]; ?></td>
                                    <td><?php echo $row["gender"]; ?></td>
                                    <td><?php echo $row["department"]; ?></td>
                                    <td><?php echo $row["salary"]; ?></td>
                                    <td><?php echo $row["start_date"]; ?></td>
                                    <td>
                                        <!-- Edit Button -->
                                        <a id="addButton" class='button-btn edit-btn' data-address="<?= $row['address'] ?>" data-position="<?= $row['department'] ?>" data-salary="<?= $row['salary'] ?>" data-name="<?= $row['name'] ?>" data-num="<?= $row['mobile_number'] ?>" data-id="<?php echo $row['employee_id']; ?>"><i class='fas fa-edit'></i></a>

                                        <!-- Separator -->
                                        <!-- <span class="separator">|</span> -->

                                        <!-- Delete Button -->
                                        <a class='button-btn delete-btn' data-id="<?php echo $row['employee_id']; ?>" onclick="confirmDelete(<?php echo $row['employee_id']; ?>)"><i class='fas fa-trash'></i></a>


                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='5'>No employees found</td></tr>";
                                }
                                mysqli_close($connection);
                                ?>
                            </tbody>
                            <!-- Modal -->
                            <div class="bg-modal" id="modal">
                                <div class="modal-content">
                                    <div class="close">+</div>
                                    <h2 class="title-hed">-Edit Employee-</h2>
                                        <form id="editForm" action="positionlist.php" method="POST"> 
                                            <input type="text" name="name" id="name" required>
                                            <input type="text" name="employee_id" id="editEmployeeId" placeholder="" required>
                                            <input type="text" name="number" id="mobile_number" placeholder="Mobile Number" required>
                                            <input type="text" name="gender" id="gender" placeholder="Sex" required>
                                            <input type="text" name="address" class="gender" id="address" placeholder="Address" required>
                                            <input type="text" name="salary" id="salary" placeholder="Salary" required>
                                            <input type="text" name="Position" id="Position" placeholder="Position" required>
                                            <button type="submit" id="editSubmitButton" class="button-btn">Submit</button>
                                        </form>
                                </div>
                            </div>
                            <script src="js/positionlist.js"></script>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </main>
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function() {
                                            document.getElementById("editSubmitButton").addEventListener("click", function(event) {
                                            event.preventDefault(); // Prevent form submission

                                            swal({
                                                title: "Updated Successfully!",
                                                text: "",
                                                icon: "success"
                                            }).then(function() {
                                                // After the user clicks OK on the SweetAlert, submit the form
                                                document.getElementById("editForm").submit();
                                            });
                                            });
                                        });
                                        </script>
                                        <script>
                                        function confirmDelete(employeeId) {
                                            swal({
                                                title: "Are you sure?",
                                                text: "Once deleted, you will not be able to recover this data!",
                                                icon: "warning",
                                                buttons: true,
                                                dangerMode: true,
                                            }).then((willDelete) => {
                                                if (willDelete) {
                                                    // If user confirms deletion, redirect to delete_employee.php with the employee id
                                                    window.location.href = "delete_employee.php?id=" + employeeId;
                                                }
                                            });
                                        }
                                        </script>

                                </body>
                                </html>

    <?php
    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Include database connection
        include("php/database.php");

        // Check if connection is successful
        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Get form data
        $employee_id = $_POST['employee_id'];
        $position = $_POST['Position'];
        $name = $_POST['name'];
        $number = $_POST['number'];
        $salary = $_POST['salary'];
        $address = $_POST['address'];

        // Update department in the database
        $sql = "UPDATE employees SET address='$address',name='$name',mobile_number='$number',salary='$salary',department = '$position' WHERE employee_id = '$employee_id'";

        if (mysqli_query($connection, $sql)) {
            // Redirect to a different page to avoid form resubmission
            header("Location: positionlist.php");
            exit(); // Ensure script termination after redirect
        } else {
            echo "Error updating department: " . mysqli_error($connection);
        }

        // Close connection
        mysqli_close($connection);
    }
?>