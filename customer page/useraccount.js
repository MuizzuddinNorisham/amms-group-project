const users = [];
let editIndex = null;

const usernameInput = document.getElementById("username");
const useremailInput = document.getElementById("useremail");
const userpassInput = document.getElementById("userpass");

const addBtn = document.getElementById("addBtn");
const editBtn = document.getElementById("editBtn");
const deleteBtn = document.getElementById("deleteBtn");
const userList = document.getElementById("userList");

function renderList() {
  userList.innerHTML = "";
  users.forEach((user, index) => {
    const li = document.createElement("li");
    li.textContent = `Username: ${user.username} | Email: ${user.useremail}`;
    li.onclick = () => {
      usernameInput.value = user.username;
      useremailInput.value = user.useremail;
      userpassInput.value = user.userpass;
      editIndex = index;
    };
    userList.appendChild(li);
  });
}

addBtn.onclick = () => {
  const username = usernameInput.value.trim();
  const useremail = useremailInput.value.trim();
  const userpass = userpassInput.value;

  if (username && useremail && userpass) {
    fetch("useradd.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `username=${encodeURIComponent(username)}&useremail=${encodeURIComponent(
        useremail
      )}&userpass=${encodeURIComponent(userpass)}`,
    })
      .then((res) => res.text())
      .then((result) => {
        alert(result);
        users.push({ username, useremail, userpass });
        renderList();
        usernameInput.value = "";
        useremailInput.value = "";
        userpassInput.value = "";
      })
      .catch((err) => {
        console.error("Error:", err);
        alert("Failed to add user.");
      });
  } else {
    alert("Please fill all fields.");
  }
};

editBtn.onclick = () => {
  if (editIndex === null) {
    alert("Please select a user to edit.");
    return;
  }

  const username = usernameInput.value.trim();
  const useremail = useremailInput.value.trim();
  const userpass = userpassInput.value;

  if (username && useremail && userpass) {
    users[editIndex] = { username, useremail, userpass };
    renderList();
    usernameInput.value = "";
    useremailInput.value = "";
    userpassInput.value = "";
    editIndex = null;
  } else {
    alert("Please fill all fields.");
  }
};

deleteBtn.onclick = () => {
  if (editIndex === null) {
    alert("Please select a user to delete.");
    return;
  }

  if (confirm("Are you sure you want to delete this user?")) {
    users.splice(editIndex, 1);
    renderList();
    usernameInput.value = "";
    useremailInput.value = "";
    userpassInput.value = "";
    editIndex = null;
  }
};
