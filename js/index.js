$(document).ready(function() {
  var startTime, endTime;

  $('#time-in-btn').click(function() {
    var employeeName = $('#employeeSelect').val();
    if (employeeName) {
      openModal(employeeName + ' - Time In');
      startTime = new Date();
      $('#modal-title').text(employeeName + ' - Time In (' + getCurrentTime() + ')');
      $('#modal-action-btn').data('action', 'Time In');
    } else {
      alert('Please select an employee first.');
    }
  });

  $('#time-out-btn').click(function() {
    var employeeName = $('#employeeSelect').val();
    if (employeeName) {
      openModal(employeeName + ' - Time Out');
      endTime = new Date();
      calculateTotalHours(startTime, endTime);
      $('#modal-title').text(employeeName + ' - Time Out (' + getCurrentTime() + ')');
      $('#modal-action-btn').data('action', 'Time Out');
    } else {
      alert('Please select an employee first.');
    }
  });

  $('#overtime-time-in-btn').click(function(){
    var employeeName = $('#employeeSelect').val();
    if (employeeName) {
      openModal(employeeName + '- Overtime Time In');
      startTime = new Date();
      $('#modal-title').text(employeeName + '- Overtime Time In (' + getCurrentTime() + ')');
      $('#modal-action-btn').data('action', 'Overtime Time In');
    } else {
      alert('Please select an employee first.');
    }
  });

  $('#overtime-time-out-btn').click(function() {
    var employeeName = $('#employeeSelect').val();
    if (employeeName) {
      openModal(employeeName + ' - Overtime Time Out');
      endTime = new Date();
      calculateTotalHours(startTime, endTime);
      $('#modal-title').text(employeeName + ' - Overtime Time Out (' + getCurrentTime() + ')');
      $('#modal-action-btn').data('action', 'Overtime Time Out');
    } else {
      alert('Please select an employee first');
    }
  });

  $('#modal-action-btn').click(function() {
    var action = $(this).data('action');
    var employeeName = $('#employeeSelect').val();
    var currentTime = $('#clock').text();
    var imgData = captureImage();

    $.ajax({
      url: 'attendance_employee.php',
      type: 'POST',
      data: {
        action: action,
        employeeName: employeeName,
        time: currentTime,
        image: imgData
      },
      success: function(response) {
        console.log("Response:", response);
        alert(response);
        closeModal();
      },
      error: function(xhr, status, error) {
        console.error(status, error);
      }
    });
  });

  function openModal(title) {
    $('#modal-title').text(title);
    $('.bg-modal').fadeIn(500);
    startCamera();
  }

  function calculateTotalHours(start, end) {
    var diff = end - start;
    var totalSeconds = Math.floor(diff / 1000);
    var hours = Math.floor(totalSeconds / 3600);
    totalSeconds %= 3600;
    var minutes = Math.floor(totalSeconds / 60);
    var seconds = totalSeconds % 60;

    var totalTime = hours + ':' + minutes + ':' + seconds;
    $('#modal-title').text($('#modal-title').text() + ' (' + totalTime + ')');
  }

  $('.close, .close-modal').click(function() {
    closeModal();
  });

  function closeModal() {
    $('.bg-modal').fadeOut(500);
  }

  function getCurrentTime() {
    var now = new Date();
    var hours = now.getHours();
    var meridiem = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12;
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();

    return hours + ':' + minutes + ':' + seconds + ' ' + meridiem;
  }

  function startCamera() {
    const video = document.getElementById('video');
    const cameraButton = document.getElementById('startCamera');

    if (video) {
      if (!video.srcObject) {
        navigator.mediaDevices.getUserMedia({ video: true })
          .then(function(stream) {
            video.srcObject = stream;
          })
          .catch(function(error) {
            console.error('Error accessing the camera: ', error);
          });
      }

      if (cameraButton) {
        cameraButton.style.display = 'none';
      }
    } else {
      console.error('Video element not found.');
    }
  }
});
