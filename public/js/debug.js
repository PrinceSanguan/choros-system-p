// Debug script to check data loading from API endpoints

document.addEventListener('DOMContentLoaded', function() {
    console.log('Debug script loaded');

    // Debug API calls by wrapping fetch
    const originalFetch = window.fetch;
    window.fetch = function(url, options) {
        console.log('Fetch request to:', url, options);

        return originalFetch(url, options)
            .then(response => {
                const clone = response.clone();

                // Log the response for debugging
                clone.json().then(data => {
                    console.log('Response from', url, ':', data);
                }).catch(err => {
                    console.error('Error parsing JSON from', url, err);
                });

                return response;
            })
            .catch(err => {
                console.error('Fetch error to', url, ':', err);
                throw err;
            });
    };

    // Debug Surrendered data loading
    window.debugSurrenderedLoading = function() {
        console.log('Manually triggering surrendered data load');
        if (window.loadSurrendereds) {
            window.loadSurrendereds();
        } else {
            console.error('loadSurrendereds function not found');
        }
    };

    // Add debug button to the page
    const debugButton = document.createElement('button');
    debugButton.textContent = 'Debug Data Loading';
    debugButton.style.position = 'fixed';
    debugButton.style.bottom = '10px';
    debugButton.style.right = '10px';
    debugButton.style.zIndex = '9999';
    debugButton.style.padding = '10px';
    debugButton.style.backgroundColor = 'red';
    debugButton.style.color = 'white';
    debugButton.style.border = 'none';
    debugButton.style.borderRadius = '4px';
    debugButton.style.cursor = 'pointer';

    debugButton.addEventListener('click', window.debugSurrenderedLoading);

    document.body.appendChild(debugButton);
});
