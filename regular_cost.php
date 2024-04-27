<?php
include("php/database.php"); // Include your database connection

// Fetch overtime hours and employee positions
$sql = "SELECT e.id, e.name, e.department, a.overall_total_hours
        FROM employees e
        JOIN attendance a ON e.name = a.name";
$result = mysqli_query($connection, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

$overallCostData = array();

while ($row = mysqli_fetch_assoc($result)) {
    $overallHours = $row['overall_total_hours'];
    $position = $row['department'];

    // Define the rates for each position
    $rates = [
        'Cashier' => 48,    // pesos per hour
        'Cook' => 55,
        'Barista' => 48,
    ];

    // Calculate overtime cost based on position rates
    $rate = isset($rates[$position]) ? $rates[$position] : 0;
    $overallCost = $overallHours * $rate;

    $overtimeCostData[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'overall_hours' => $overallHours,
        'position' => $position,
        'rate' => $rate, // Include the rate for reference
        'overall_cost' => $overallCost // Include the calculated cost
    ];
}

echo json_encode($overtimeCostData); // Return JSON encoded data
?>
