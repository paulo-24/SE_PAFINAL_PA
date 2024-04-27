<?php
include("php/database.php");
$employee_id = '';
if (isset($_POST['employeeSelect']) && isset($_POST['action'])) {
    $employeeName = $_POST['employeeSelect'];
    $action = $_POST['action']; // "Time In" or "Time Out"

    date_default_timezone_set('Asia/Manila');
    $currentTime = date("H:i:s");
    
    // Check if it's morning or afternoon, excluding lunch break
    $isMorning = strtotime($currentTime) >= strtotime('08:00:00') && strtotime($currentTime) < strtotime('12:00:00');
    $isAfternoon = strtotime($currentTime) >= strtotime('13:00:00') && strtotime($currentTime) <= strtotime('17:00:00');
    $isOvertime = strtotime($currentTime) >= strtotime('17:00:00') && strtotime($currentTime) <= strtotime('22:00:00');

    if ($isMorning) {
        $shiftType = "morning";
    } elseif ($isAfternoon) {
        $shiftType = "afternoon";
    } elseif ($isOvertime) {
        $shiftType = "overtime";
    } else {
        echo "You can only Time In/Out during morning (8am-12pm) or afternoon (1pm-5pm)";
        exit();
    }

    // Check for lunch break and adjust the allowed time
    $isLunchBreak = strtotime($currentTime) >= strtotime('12:00:00') && strtotime($currentTime) < strtotime('13:00:00');
    if ($isLunchBreak) {
        echo "You can't Time In/Out during Lunch Break (12pm-1pm)";
        exit();
    }

    // Save the timestamp to the appropriate column based on shift type
    $columnToSet = $shiftType . "_time_" . strtolower($action);
    $sql = "UPDATE attendance SET $columnToSet = NOW() WHERE employee_name = '$employeeName'";
    
    if (mysqli_query($connection, $sql)) {
        echo "Record added successfully for $shiftType $action.";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($connection);
    }
}

mysqli_close($connection);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="icon" href="images/bigbrewpic2.jpg">
    <script>
    function captureImage() {
        const canvas = document.createElement('canvas');
        const video = document.getElementById('video');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

        const image = canvas.toDataURL('image/png');

        // Set the image data as value of hidden input fieldindex.html
        document.getElementById('imageData').value = image;

        // Submit the form
        document.getElementById('imageForm').submit();
    }

    // Function to download the image
    function downloadImage(imageUrl) {
        const a = document.createElement('a');
        a.href = imageUrl;
        a.download = 'captured_image.png';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
    </script>

</head>

<body>
    <header class="header">
        <img src="images/bigbrewpic2.jpg" class="logo">
        <a class="text">
            <div class="br-name">BigBrew Baliwasan</div>
        </a>
        <div class="selection">
            <a href="IndexDTR.php">DTR</a>
            <a href="Schedule_Index.php">SCHEDULE</a>
            <a href="login_page.php" target="_self">SIGN IN</a>
        </div>
    </header>
    <div class="carousel-container">
    <div class="carousel">
        <img src="images/bb.slider/5. TARO.jpg" class="img">
        <img src="images/bb.slider/6. KIWI.jpg" class="img">
        <img src="images/bb.slider/7. KAPE VANILLA.png" class="img">
        <img src="images/bb.slider/8. MATCHA.png" class="img">
        <img src="images/bb.slider/8. MATCHA (1).png" class="img">
        <img src="images/bb.slider/8. MATCHA (2).png" class="img">
        <img src="images/bb.slider/10. RED VELVET (1).png" class="img">
        <img src="images/bb.slider/10. RED VELVET.png" class="img">
        <img src="images/bb.slider/11. GREEN APPLE (1).png" class="img">
        <img src="images/bb.slider/11. GREEN APPLE.png" class="img">
        <img src="images/bb.slider/12. WATER-BASED FRUIT TEA.jpg" class="img">
        <img src="images/bb.slider/BERRY 3.png" class="img">
        <img src="images/bb.slider/CC 3 (1).png" class="img">
        <img src="images/bb.slider/CC 3.png" class="img">
        <img src="images/bb.slider/CHOCO 2.png" class="img">
        <img src="images/bb.slider/MATCHA.png" class="img">
        <img src="images/bb.slider/PF 1.png" class="img">
        <img src="images/bb.slider/PF 2.png" class="img">
        <img src="images/bb.slider/PRAF 1.png" class="img">
        <img src="images/bb.slider/STRAWBERRY FT.png" class="img">
        <img src="images/bb.slider/STRAWBERRY MT.png" class="img">
        <img src="images/bb.slider/TARO 2.png" class="img">
        <img src="images/bb.slider/WM 2.png" class="img">
    </div>
  </div>


    <div class="container">
        <h2>Employee's Login</h2>
        <form id="loginForm" action="#" method="post">
            <div class="form-group">
                <label for="employeeSelect">Select Employee:</label>
                <select id="employeeSelect" name="employeeSelect" onchange="selected_employee(this)" required>
                    <option value="">--Select Employee--</option>
                    <?php
            include("php/database.php");

            if (!$connection) {
              die("Connection failed: " . mysqli_connect_error());
            }
    
            $sql = "SELECT * FROM employees";
            $result = mysqli_query($connection, $sql);
    
            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                echo '<option data-id="'.$row['id'].'" value="' . $row["name"] . '">' . $row["name"] . '</option>';
              }
            } else {
              echo "<option value=''>No employees found</option>";
            }

            mysqli_close($connection);
            ?>
                </select>
            </div>
            <div class="form-group">
                <button id="time-in-btn" type="button">Time In</button>
                <button id="time-out-btn" type="button">Time Out</button>
                <!-- <button id="overtime-time-in-btn" type="button">Overtime Time In</button>
          <button id="overtime-time-out-btn" type="button">Overtime Time Out</button> -->
            </div>
        </form>
    </div>

    <!-- MODAL -->
    <div class="bg-modal">
        <div class="modal-content">
            <div class="close">+</div>
            <h2 class="title-hed" id="modal-title">-Staff Attendance-</h2>

            <div class="camera-effect">
                <!-- Camera -->
                <form id="imageForm" action="save_image.php" method="post">
                    <input type="hidden" id="employee_id" name="employee_id" value="">
                    <video id="video" class="video" width="640" height="480" autoplay></video><br>
                    <input type="hidden" id="imageData" name="image" value="">
                    <ion-icon class="icon" name="camera-outline" onclick="captureImage()"></ion-icon>
                </form>
            </div>

            <canvas id="canvas" class="canvas" width="500" height="400"></canvas>

            <div class="clock" id="clock">00:00:00</div>

            <div class="modal-btns">
                <button id="modal-action-btn" onclick="captureImage()">Confirm</button>
                <button class="close-modal">Cancel</button>
            </div>
        </div>
    </div>





    <script>
    navigator.mediaDevices.getUserMedia({
            video: true
        })
        .then(function(stream) {
            const videoElement = document.getElementById('video');
            videoElement.srcObject = stream;
        })
        .catch(function(err) {
            console.error('Error accessing the camera: ', err);
        });

    // Listen for form submission response
    document.getElementById('imageForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form from submitting normally

        // Submit form data using Fetch API
        fetch('save_image.php', {
                method: 'POST',
                body: new FormData(event.target)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    downloadImage(data.imageUrl); // Call download function if success
                } else {
                    alert('Failed to save image.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });

    })

    function selected_employee(selectElement) {
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var dataId = selectedOption.getAttribute('data-id');
        document.getElementById('employee_id').value = dataId
    }
    </script>

    <!--<script src="js/camera.js"></script>-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/index.js"></script>
    <script src="js/realtimeclock.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script>
    // Function to handle Time In button click
    document.getElementById('overtime-time-in-btn').addEventListener('click', function() {
        document.getElementById('employeeSelect').value = "<?php echo $employeeName; ?>";
        document.getElementById('overtime-time-in-btn').click();
    });

    // Function to handle Time Out button click
    document.getElementById('overtime-time-out-btn').addEventListener('click', function() {
        document.getElementById('employeeSelect').value = "<?php echo $employeeName; ?>";
        document.getElementById('overtime-time-out-btn').click();
    });
        // JavaScript is optional if you want to pause the animation on hover
    /* const carousel = document.querySelector('.carousel');

    carousel.addEventListener('mouseenter', () => {
      carousel.style.animationPlayState = 'paused';
    });

    carousel.addEventListener('mouseleave', () => {
      carousel.style.animationPlayState = 'running';
    }); */

    </script>
</body>

</html>