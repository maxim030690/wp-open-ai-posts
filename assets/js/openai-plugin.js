document.addEventListener('DOMContentLoaded', () => {
    const generateButton = document.getElementById('generatePostButton');
    const promptField = document.getElementById('promptField');
    const loadingSpinner = document.getElementById('loadingSpinner');

    // Handle the 'Generate Post' button click event
    generateButton.addEventListener('click', (e) => {
        // Check if the API Key is set
        if (!openai_plugin_vars.api_key || openai_plugin_vars.api_key.trim() === '') {
            e.preventDefault(); // Prevent the action
            alert('Please enter your OpenAI API Key before generating posts.');
            return;
        }

        // Show loading spinner
        loadingSpinner.style.display = 'block';

        // Initialize the prompt variable
        let prompt = '';

        // Get prompt value from input field (if exists)
        if (promptField) {
            prompt = promptField.value.trim();
        }

        // If prompt is empty, provide a default prompt
        if (!prompt.length) {
          alert('Fill the Prompt field please!'); // Default prompt if user doesn't input one
        }

        const data = new FormData();
        data.append('action', 'generate_post');
        data.append('nonce', openai_plugin_vars.nonce);
        data.append('prompt', prompt);

        fetch(openai_plugin_vars.ajax_url, {
            method: 'POST',
            body: data,
        })
        .then(response => response.json())
        .then(responseData => {
          // Hide loading spinner
            loadingSpinner.style.display = 'none';

            if (responseData.success) {
                alert(responseData.data.message); // Success message
            } else {
                alert('Error: ' + responseData.data.message); // Error message
            }
        })
        .catch(error => {
            loadingSpinner.style.display = 'none';
            alert('An error occurred while generating the post.');
            console.error(error);
        });
    });
});
