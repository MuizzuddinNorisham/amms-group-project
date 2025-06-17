document.addEventListener("DOMContentLoaded", () => {
    fetch("getuser.php")
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector("#customerTable tbody");
            tbody.innerHTML = "";
            data.forEach(user => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${user.userid}</td>
                    <td contenteditable="true" data-field="username">${user.username}</td>
                    <td contenteditable="true" data-field="useremail">${user.useremail}</td>
                    <td contenteditable="true" data-field="userpass">${user.userpass}</td>
                    <td>
                        <button onclick="updateUser(${user.userid}, this)">Update</button>
                        <button onclick="deleteUser(${user.userid})">Delete</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        });
});

function updateUser(userid, btn) {
    const row = btn.closest("tr");
    const username = row.querySelector('[data-field="username"]').textContent.trim();
    const useremail = row.querySelector('[data-field="useremail"]').textContent.trim();
    const userpass = row.querySelector('[data-field="userpass"]').textContent.trim();

    fetch("userupdate.php", {
        method: "POST",
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ userid, username, useremail, userpass })
    })
    .then(res => res.text())
    .then(alert);
}

function deleteUser(userid) {
    if (confirm("Are you sure to delete this user?")) {
        fetch("userdelete.php", {
            method: "POST",
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ userid })
        })
        .then(res => res.text())
        .then(alert)
        .then(() => location.reload());
    }
}
