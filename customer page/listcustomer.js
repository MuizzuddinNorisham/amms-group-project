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
                    <td contenteditable="true" data-field="userphone">${user.userphone}</td> <!-- Added phone number -->
                    <td contenteditable="true" data-field="useraddress">${user.useraddress}</td> <!-- Added address -->
                    <td>
                        <button onclick="updateUser (${user.userid}, this)">Update</button>
                        <button onclick="deleteUser (${user.userid})">Delete</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        });
});

function updateUser (userid, btn) {
    const row = btn.closest("tr");
    const username = row.querySelector('[data-field="username"]').textContent.trim();
    const useremail = row.querySelector('[data-field="useremail"]').textContent.trim();
    const userpass = row.querySelector('[data-field="userpass"]').textContent.trim();
    const userphone = row.querySelector('[data-field="userphone"]').textContent.trim(); // Get phone number
    const useraddress = row.querySelector('[data-field="useraddress"]').textContent.trim(); // Get address

    fetch("userupdate.php", {
        method: "POST",
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ userid, username, useremail, userpass, userphone, useraddress }) // Include phone and address
    })
    .then(res => res.text())
    .then(alert);
}

function deleteUser (userid) {
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
