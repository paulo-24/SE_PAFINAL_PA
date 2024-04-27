<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Account List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <link rel="stylesheet" href="css/adminuser.css">
    <link rel="stylesheet" href="css/topsidenavbars.css">
    <link rel="icon" href="images/bigbrewpic2.jpg">

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

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
            <!-- <li><a href="employeelist.html"><i class="fas fa-users"></i>Employee List</a></li> -->
            <li><a href="positionlist.php"><i class="fas fa-user-tie"></i>Employee Management</a></li>
            <li><a href="schedule.php"><i class="fas fa-credit-card"></i>Schedule</a></li>
            <li><a href="DailyTimeRecord.php"><i class="fas fa-calendar"></i>Reports</a></li>
            <li><a href="admin_user.php" class="btn-active"><i class="fas fa-user"></i>Admin Users</a></li> 
        </ul>
    </section>
    <main class="main">  
        <div class="card-body">
            <div class="logo-main">Admin Panel</div>
            <div class="container">
                <h1>ADMIN ACCOUNT LIST</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th class="action">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include("php/database.php");

                        $query = "SELECT * FROM admin";
                        $result = mysqli_query($connection, $query);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['fname'] . "</td>";
                                echo "<td>" . $row['username'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['gender'] . "</td>";
                                echo "<td>";
                                echo "<a href='edit_admin.php?id=" . $row['id'] . "' class='button-btn'><i class='fas fa-edit'></i></a>";
                                echo "| ";
                                echo "<a href='#' class='button-btn delete-btn' data-id='" . $row['id'] . "'><i class='fas fa-trash-alt'></i></a>";
                                /* echo "<a href='delete_admin.php?id=" . $row['id'] . "' class='button-btn' onclick='return confirm(\"Are you sure you want to delete this admin?\")'><i class='fas fa-trash-alt'></i></a>"; */
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No admins found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <button id="addButton" onclick="openModal()">Add Admin</button>
            </div>
        </div>    
    </main>
    <div class="bg-modal" id="modal">
        <div class="modal-content">
            <div class="close">+</div>
                <h2 class="title-hed">-Admin Registration-</h2>
                    <form action="add_admin.php" method="POST">
                        <input type="text" name="fullname" placeholder="Fullname" required>
                        <input type="text" name="username" placeholder="Username" required>
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <input type="password" name="password" placeholder="Re-enter Password" required>
                        <input type="text" name="gender" placeholder="Gender" required>
                        <button type="submit" class="button-btn">Submit</button>
            </form>
        </div>
    </div>    
    <script src="js/adminuser.js"></script>
    <script>
        function openModal() {
            document.getElementById('modal').style.display = 'flex';
        }
        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }
        
        
    </script>
    <script>
    // Function to handle delete button click
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                const adminId = this.getAttribute('data-id');

                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this admin account!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        // Redirect to delete_admin.php with admin id
                        window.location.href = "delete_admin.php?id=" + adminId;
                    } else {
                        swal("Admin account is safe!", {
                            icon: "info",
                        });
                    }
                });
            });
        });
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        swal({
            title: "Are you sure?",
            text: "Are you sure you want to add this admin account?",
            icon: "info",
            buttons: true,
            dangerMode: false,
        })
        .then((willAdd) => {
            if (willAdd) {
                // If user clicks "Yes", submit the form
                form.submit();
            } else {
                swal("Admin account not added!", {
                    icon: "info",
                });
            }
        });
    });
});
</script>

</body>
</html>
