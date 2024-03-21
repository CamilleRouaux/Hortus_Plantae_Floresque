// Type filtering functionality
document.getElementById("typeFilter").addEventListener("change", function () {
    const filterValue = this.value;
    const articleRows = document.querySelectorAll("tbody tr");

    articleRows.forEach(function (row) {
        const articleType = row.cells[1].textContent.toLowerCase();

        if (filterValue === "all" || filterValue === articleType) {
            row.style.display = "table-row";
        } else {
            row.style.display = "none";
        }
    });
});

// display asc or dsc by article title functionality
document.getElementById("titleHeader").addEventListener("click", function () {
    const articleRows = document.querySelectorAll("tbody tr");
    const sortedRows = Array.from(articleRows).sort((a, b) => {
        const titleA = a.cells[2].textContent.toLowerCase();
        const titleB = b.cells[2].textContent.toLowerCase();
        return titleA.localeCompare(titleB);
    });

    // Check if the rows are currently in ascending order
    const isAscending = articleRows[0] === sortedRows[0];

    // If in ascending order, reverse the sorted array to get descending order
    if (isAscending) {
        sortedRows.reverse();
    }

    // Append the sorted rows back to the table
    const tableBody = document.querySelector("tbody");
    tableBody.innerHTML = ''; // Clear the current table
    sortedRows.forEach(row => {
        tableBody.appendChild(row);
    });
});

// display asc or dsc by author name functionality
document.getElementById("authorHeader").addEventListener("click", function () {
    const articleRows = document.querySelectorAll("tbody tr");
    const sortedRows = Array.from(articleRows).sort((a, b) => {
        const authorA = a.cells[3].textContent.trim(); // Trim to remove leading/trailing white spaces
        const authorB = b.cells[3].textContent.trim();
        return authorA.localeCompare(authorB);
    });

    // Check if the rows are currently in ascending order
    const isAscending = articleRows[0] === sortedRows[0];

    // If in ascending order, reverse the sorted array to get descending order
    if (isAscending) {
        sortedRows.reverse();
    }

    // Append the sorted rows back to the table
    const tableBody = document.querySelector("tbody");
    tableBody.innerHTML = ''; // Clear the current table
    sortedRows.forEach(row => {
        tableBody.appendChild(row);
    });
});

// display asc or dsc by date/time functionality
document.getElementById("dateHeader").addEventListener("click", function () {
    const articleRows = document.querySelectorAll("tbody tr");
    const sortedRows = Array.from(articleRows).sort((a, b) => {
        const dateA = new Date(a.cells[4].textContent);
        const dateB = new Date(b.cells[4].textContent);
        return dateA - dateB;
    });

    // Check if the rows are currently in ascending order
    const isAscending = articleRows[0] === sortedRows[0];

    // If in ascending order, reverse the sorted array to get descending order
    if (isAscending) {
        sortedRows.reverse();
    }

    // Append the sorted rows back to the table
    const tableBody = document.querySelector("tbody");
    tableBody.innerHTML = ''; // Clear the current table
    sortedRows.forEach(row => {
        tableBody.appendChild(row);
    });
});

