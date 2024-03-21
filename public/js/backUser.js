
// Role filtering functionality
document.getElementById("roleFilter").addEventListener("change", function () {
    const filterValue = this.value;
    const userRows = document.querySelectorAll("tbody tr");

    userRows.forEach(function (row) {
        const userRoles = JSON.parse(row.getAttribute("data-roles"));

        let roleToConsider = null;

        if (userRoles.length === 1) {
            // Display the user if they have only one role
            roleToConsider = userRoles[0];
        } else if (userRoles.length >= 2) {
            // Display the user and consider the second role if they have two or more roles
            roleToConsider = userRoles[1];
        }

        if (filterValue === "all" || filterValue === roleToConsider) {
            row.style.display = "table-row";
        } else {
            row.style.display = "none";
        }
    });
});



// Sorting functionality
const makeSortable = function () {
    const table = document.getElementById("userTable");
    const headers = table.querySelectorAll(".sortable");

    headers.forEach(function (header) {
        header.addEventListener("click", function () {
            const sortKey = this.getAttribute("data-sort-key");
            const currentOrder = this.getAttribute("data-sort-order");

            if (currentOrder === "asc" || currentOrder === null) {
                this.setAttribute("data-sort-order", "desc");
            } else {
                this.setAttribute("data-sort-order", "asc");
            }

            sortTable(table, sortKey, currentOrder);
        });
    });
};

const sortTable = function (table, sortKey, currentOrder) {
    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.querySelectorAll("tr"));

    rows.sort((a, b) => {
        const aValue = a.querySelector(`td:nth-child(${sortKeyIndex[sortKey]})`).textContent.trim();
        const bValue = b.querySelector(`td:nth-child(${sortKeyIndex[sortKey]})`).textContent.trim();

        const orderFactor = currentOrder === "desc" ? -1 : 1;

        return orderFactor * aValue.localeCompare(bValue);
    });

    tbody.innerHTML = "";
    rows.forEach(row => tbody.appendChild(row));
};

// Mapping of sort keys to column indexes
const sortKeyIndex = {
    "firstname": 2,
    "lastname": 3,
};

// Initialize sorting
makeSortable();


// disply users if status if false
document.addEventListener("DOMContentLoaded", function() {
    const falseUsersBtn = document.getElementById('showFalseUsersBtn');
    const allUsers = document.querySelectorAll('.user-row');
    const userListTitle = document.querySelector('.user-list-title');

    falseUsersBtn.addEventListener('click', function() {
        const showAll = falseUsersBtn.dataset.showAll === 'true';

        allUsers.forEach(user => {
            const isFalsyStatus = !user.dataset.status;  // Check for falsy status
            user.style.display = showAll || isFalsyStatus ? '' : 'none';
        });

        falseUsersBtn.innerText = showAll ? 'Utilisateurs désactivés' : 'Tout les utilisateurs';
        falseUsersBtn.dataset.showAll = showAll ? 'false' : 'true';

        userListTitle.innerText = showAll ? 'Liste des utilisateurs' : 'Liste des utilisateurs désactivés';

        // Change Bootstrap class for button color
        falseUsersBtn.classList.remove(showAll ? 'btn-success' : 'btn-danger');
        falseUsersBtn.classList.add(showAll ? 'btn-secondary' : 'btn-success');
    });
});