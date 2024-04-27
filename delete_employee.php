<?php
session_start();
include("php/database.php");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $employee_id = $_GET['id'];

    $sql = "DELETE FROM employees WHERE employee_id = $employee_id";

    if (mysqli_query($connection, $sql)) {
        // Redirect back to the positionlist.php page after successful deletion
        header("Location: positionlist.php");
        exit;
    } else {
        // Show an error message if deletion fails
        echo "<script>
                swal('Error', 'Error deleting record: " . mysqli_error($connection) . "', 'error');
            </script>";
    }
}

mysqli_close($connection);
?>
