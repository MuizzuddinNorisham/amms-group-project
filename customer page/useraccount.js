const users = [];
let editIndex = null;

// Form input elements
const nameInput = document.getElementById("name"); // will hold 'username'
const emailInput = document.getElementById("email"); // will hold 'useremail'
const passwordInput = document.getElementById("password"); // will hold 'userpass'

const addBtn = document.getElementById("addBtn");
const editBtn = document.getElementById("editBtn");
const deleteBtn = document.getElementById("deleteBtn");
const userList = document.getElementById("userList");

// Render the user list on the page
function renderList() {
  userList.innerHTML = "";
  users.forEach((user, index) => {
    const li = document.createElement("li");
    li.textContent = `Username: ${user.username} | Email: ${user.useremail}`;
    li.onclick = () => {
      nameInput.value = user.username;
      emailInput.value = user.useremail;
      passwordInput.value = user.userpass;
      editIndex = index;
    };
    userList.appendChild(li);
  });
}

// Add button - just validates (form submits through useradd.php)
addBtn.onclick = (e) => {
  if (!nameInput.value || !emailInput.value || !passwordInput.value) {
    e.preventDefault(); // prevent form submission
    alert("Please fill all fields.");
  }
};

// Edit button - updates user via fetch to userupdate.php
editBtn.onclick = () => {
  if (editIndex === null) {
    alert("Please select a user to update.");
    return;
  }

  const username = nameInput.value.trim();
  const useremail = emailInput.value.trim();
  const userpass = passwordInput.value;

  if (username && useremail && userpass) {
    const originalEmail = users[editIndex].useremail;

    fetch('userupdate.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        originalEmail: originalEmail,
        username: username,
        useremail: useremail,
        userpass: userpass
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        users[editIndex] = { username, useremail, userpass };
        renderList();
        nameInput.value = "";
        emailInput.value = "";
        passwordInput.value = "";
        editIndex = null;
        alert("User updated successfully.");
      } else {
        alert("Update failed: " + data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert("An error occurred while updating the user.");
    });
  } else {
    alert("Please fill all fields.");
  }
};

// Delete button - deletes user via fetch to userdelete.php
deleteBtn.onclick = () => {
  if (editIndex === null) {
    alert("Please select a user to delete.");
    return;
  }

  if (confirm("Are you sure you want to delete this user?")) {
    const userToDelete = users[editIndex];

    fetch('userdelete.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ useremail: userToDelete.useremail })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        users.splice(editIndex, 1);
        renderList();
        nameInput.value = "";
        emailInput.value = "";
        passwordInput.value = "";
        editIndex = null;
        alert("User deleted successfully.");
      } else {
        alert("Error deleting user: " + data.message);
      }
    })
    .catch((error) => {
      console.error('Error:', error);
      alert("An error occurred while deleting the user.");
    });
  }
};
