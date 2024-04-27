<?php
include("php/database.php"); // Include your database connection
$get_overtime = mysqli_query($connection,"SELECT SUM(overtime_cost) as total_cost,SUM(deduction) as total_deduction FROM attendance");
$row = mysqli_fetch_array($get_overtime);
$overtime_cost = $row['total_cost'];
$deduction = $row['total_deduction'];
// Fetch overtime hours and employee positions
$sql = "SELECT e.id, e.name, e.department, a.overtime_total_hours
        FROM employees e
        JOIN attendance a ON e.name = a.name";
$result = mysqli_query($connection, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

$overtimeCostData = array();

while ($row = mysqli_fetch_assoc($result)) {
    $overtimeHours = $row['overtime_total_hours'];
    $position = $row['department'];

    // Define the rates for each position
    $rates = [
        'Cashier' => 52,    // pesos per hour
        'Cook' => 60,
        'Barista' => 52,
    ];

    // Calculate overtime cost based on position rates
    $rate = isset($rates[$position]) ? $rates[$position] : 0;
    $overtimeCost = $overtimeHours * $rate;

    $overtimeCostData[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'overtime_hours' => $overtimeHours,
        'position' => $position,
        'rate' => $rate, // Include the rate for reference
        'overtime_cost' => $overtime_cost, // Include the calculated cost
        'deduction' => $deduction,
    ];
}

echo json_encode($overtimeCostData); // Return JSON encoded data
?>
