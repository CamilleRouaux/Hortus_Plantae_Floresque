var currentIndex = 0; // Index to keep track of the current position
var letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
var lettersPerPage = 13;

function generateAlphabetButtons() {
    var letterButtonsContainer = document.getElementById('letterButtonsContainer');

    for (var i = 0; i < lettersPerPage; i++) {
        var letterButton = document.createElement('a');
        letterButton.className = 'btn btnl btn-outline-success';
        letterButton.style.marginRight = '10px';
        letterButton.textContent = letters[i];
        letterButton.href = getPlantListPath(letters[i]);
        letterButtonsContainer.appendChild(letterButton);
    }
}

function getPlantListPath(letter) {
    // Define the base URL dynamically based on the server environment
    const baseUrl = window.location.origin + '/projet-02-hortus/public';

    // Construct the complete path
    return `${baseUrl}/plant/list?letter=${encodeURIComponent(letter)}`;
}

function scrollLetters(direction) {
    currentIndex = (currentIndex + direction) % (letters.length - lettersPerPage + 1);
    currentIndex = currentIndex < 0 ? (letters.length - lettersPerPage) : currentIndex;

    // Display the letters based on the current index
    for (var i = 0; i < lettersPerPage; i++) {
        var letterIndex = (currentIndex + i) % letters.length;
        var letterButton = document.getElementById('letterButtonsContainer').children[i];
        letterButton.textContent = letters[letterIndex];
        letterButton.href = getPlantListPath(letters[letterIndex]);
    }
}

// Generate alphabet buttons on page load
generateAlphabetButtons();
