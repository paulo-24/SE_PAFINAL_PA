document.getElementById('addButton').addEventListener('click',
function(){
    document.querySelector('.bg-modal').style.display = 'flex'
});
document.querySelector('.close').addEventListener('click',function(){
    document.querySelector('.bg-modal').style.display = 'none';
});

  // JavaScript to capture the employee ID and populate the form
  const editButtons = document.querySelectorAll('.edit-btn');
  const editForm = document.getElementById('editForm');
  const editEmployeeIdInput = document.getElementById('editEmployeeId');
  const fullname = document.getElementById('name');
  const mobile_number = document.getElementById('mobile_number');
  const salary = document.getElementById('salary');
  const position = document.getElementById('Position');
  const address = document.getElementById('address');

  editButtons.forEach(button => {
      button.addEventListener('click', function() {
          const employeeId = this.dataset.id;
          fullname.value = this.dataset.name
          fullname.value = this.dataset.name
          editEmployeeIdInput.value = employeeId;
          mobile_number.value = this.dataset.num
          salary.value = this.dataset.salary
          position.value = this.dataset.position
          address.value = this.dataset.address
          // Show the modal
          document.getElementById('modal').style.display = 'block';
      });
  });