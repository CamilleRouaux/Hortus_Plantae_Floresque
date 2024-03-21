document.getElementById('searchInput').addEventListener('input', function () {
    var searchTerm = this.value.toLowerCase();
    filterPlants(searchTerm);
});

function filterPlants(searchTerm) {
    var resultsFound = false;

    var plantRows = document.querySelectorAll('#plantTableBody tr');
    plantRows.forEach(function (row) {
        var name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        var latinName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

        if (name.includes(searchTerm) || latinName.includes(searchTerm)) {
            row.style.display = 'table-row';
            resultsFound = true;
        } else {
            row.style.display = 'none';
        }
    });

    var noResultsMessage = document.getElementById('noResultsMessage');
    if (resultsFound) {
        noResultsMessage.style.display = 'none';
    } else {
        noResultsMessage.style.display = 'block';
    }
}