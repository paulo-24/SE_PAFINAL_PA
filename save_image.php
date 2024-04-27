<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['image'])) {
    $imageData = $_POST['image'];
    $employee_id = $_POST['employee_id'];
    $month = date('m');
    $day = date('d');
    // Remove data prefix
    $filteredData = substr($imageData, strpos($imageData, ',') + 1);

    // Decode the base64 data
    $decodedData = base64_decode($filteredData);

    // Generate a unique filename
    $imageName = uniqid() . '.png';

    // Path to save the image (in the "images" folder)
    $imagePath = 'images/' . $imageName;

    // Save the image to the server
    file_put_contents($imagePath, $decodedData);

    // Save image information to the database
    include 'php/database.php'; // Include the database connection
    $check_image = mysqli_query($connection,"SELECT * FROM images WHERE (DAY(created_at)=$day AND MONTH(created_at)=$month) AND employee_id='$employee_id'");
    if(mysqli_num_rows($check_image)>=1){
       $update = mysqli_query($connection,"UPDATE images SET image_name='$imageName',image_path='$imagePath',created_at=NOW() WHERE employee_id='$employee_id'");
        if($update){
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $imageName . '"');
            readfile($imagePath);
            exit;
        }
    }else{
        $sql = "INSERT INTO images (image_name, image_path,employee_id) VALUES ('$imageName', '$imagePath','$employee_id')";
    
        if (mysqli_query($connection, $sql)) {
            // Return the image as a downloadable attachment
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $imageName . '"');
            readfile($imagePath);
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($connection);
        }
    }
    mysqli_close($connection);
} else {
    echo "Invalid request";
}
?>
