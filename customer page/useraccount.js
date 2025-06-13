<script>
    const users = [];
    let editIndex = null;

    const nameInput = document.getElementById("name");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");

    const addBtn = document.getElementById("addBtn");
    const editBtn = document.getElementById("editBtn");
    const deleteBtn = document.getElementById("deleteBtn");
    const userList = document.getElementById("userList");

    function renderList() {
      userList.innerHTML = "";
      users.forEach((user, index) => {
        const li = document.createElement("li");
        li.textContent = `Name: ${user.name} | Email: ${user.email}`;
        li.onclick = () => {
          nameInput.value = user.name;
          emailInput.value = user.email;
          passwordInput.value = user.password;
          editIndex = index;
        };
        userList.appendChild(li);
      });
    }

    addBtn.onclick = () => {
      const name = nameInput.value.trim();
      const email = emailInput.value.trim();
      const password = passwordInput.value;

      if (name && email && password) {
        users.push({ name, email, password });
        renderList();
        nameInput.value = "";
        emailInput.value = "";
        passwordInput.value = "";
      } else {
        alert("Please fill all fields.");
      }
    };

    editBtn.onclick = () => {
      if (editIndex === null) {
        alert("Please select a user to edit.");
        return;
      }

      const name = nameInput.value.trim();
      const email = emailInput.value.trim();
      const password = passwordInput.value;

      if (name && email && password) {
        users[editIndex] = { name, email, password };
        renderList();
        nameInput.value = "";
        emailInput.value = "";
        passwordInput.value = "";
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
        nameInput.value = "";
        emailInput.value = "";
        passwordInput.value = "";
        editIndex = null;
      }
    };
  </script>