const users = [];
let editIndex = null;

// Form input elements
const nameInput = document.getElementById("name");
const phoneInput = document.getElementById("phone");
const addressInput = document.getElementById("address");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");

const addBtn = document.getElementById("addBtn");
const editBtn = document.getElementById("editBtn");
const deleteBtn = document.getElementById("deleteBtn");
const userList = document.getElementById("userList");

// Render the user list on the page
function renderList() {
  userList.innerHTML = "";
  users.forEach((user, index) => {
    const li = document.createElement("li");
    li.textContent = `Name: ${user.username} | Email: ${user.useremail} | Phone: ${user.phone} | Address: ${user.address}`;
    li.onclick = () => {
      nameInput.value = user.username;
      phoneInput.value = user.phone;
      addressInput.value = user.address;
      emailInput.value = user.useremail;
      passwordInput.value = user.userpass;
      editIndex = index;
    };
    userList.appendChild(li);
  });
}

// Add button - send data via fetch to user_action.php
addBtn.onclick = () => {
  const username = nameInput.value.trim();
  const useremail = emailInput.value.trim();
  const userpass = passwordInput.value.trim();
  const userphone = phoneInput.value.trim();
  const useraddress = addressInput.value.trim();

  if (!username || !useremail || !userpass || !userphone || !useraddress) {
    alert("Please fill all fields.");
    return;
  }

  fetch('useraccount.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: new URLSearchParams({
      action: 'add',
      username,
      useremail,
      userpass,
      userphone,
      useraddress
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      users.push({ username, useremail, userpass, phone: userphone, address: useraddress });
      renderList();
      clearForm();
      alert("User added successfully.");
    } else {
      alert("Error adding user: " + data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert("An error occurred while adding the user.");
  });
};

// Edit button - updates user via fetch to user_action.php
editBtn.onclick = () => {
  if (editIndex === null) {
    alert("Please select a user to update.");
    return;
  }

  const username = nameInput.value.trim();
  const useremail = emailInput.value.trim();
  const userpass = passwordInput.value.trim();
  const userphone = phoneInput.value.trim();
  const useraddress = addressInput.value.trim();
  const originalEmail = users[editIndex].useremail;

  if (!username || !useremail || !userpass || !userphone || !useraddress) {
    alert("Please fill all fields.");
    return;
  }

  fetch('useraccount.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      action: 'edit',
      originalEmail,
      username,
      useremail,
      userpass,
      userphone,
      useraddress
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      users[editIndex] = { username, useremail, userpass, phone: userphone, address: useraddress };
      renderList();
      clearForm();
      alert("User updated successfully.");
    } else {
      alert("Error updating user: " + data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert("An error occurred while updating the user.");
  });
};

// Delete button - deletes user via fetch to user_action.php
deleteBtn.onclick = () => {
  if (editIndex === null) {
    alert("Please select a user to delete.");
    return;
  }

  const userToDelete = users[editIndex];

  if (!confirm("Are you sure you want to delete this user?")) {
    return;
  }

  fetch('useraccount.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      action: 'delete',
      useremail: userToDelete.useremail
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      users.splice(editIndex, 1);
      renderList();
      clearForm();
      alert("User deleted successfully.");
    } else {
      alert("Error deleting user: " + data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert("An error occurred while deleting the user.");
  });
};

// Clear form inputs and reset edit index
function clearForm() {
  nameInput.value = "";
  phoneInput.value = "";
  addressInput.value = "";
  emailInput.value = "";
  passwordInput.value = "";
  editIndex = null;
}