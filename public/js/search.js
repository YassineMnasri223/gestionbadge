// Get a reference to the search form
const searchForm = document.getElementById('search-form');

// Add an event listener for the submit event
searchForm.addEventListener('submit', (event) => {
    // Prevent the default form submission
    event.preventDefault();

    // Get the search term from the form input
    const searchTerm = event.target.elements.q.value;

    // Make an AJAX request to the search endpoint with the search term as a parameter
    fetch('/test/search?q=' + searchTerm)
        .then(response => response.text())
        .then(html => {
            // Replace the current search results with the new search results
            const searchResults = document.getElementById('search-results');
            searchResults.innerHTML = html;
        })
        .catch(error => console.error(error));
});
