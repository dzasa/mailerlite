$(document).ready(function() {
    // Save API key on form submit
    $('#saveApiKeyBtn').on('click', function() {
        var apiKey = $('#apiKeyInput').val();
        // Make AJAX POST request
        $.ajax({
            url: '/api-key', // Replace with your server-side API endpoint
            type: 'POST',
            data: { api_key: apiKey },
            success: function(response) {
                if(response.result!=='ok'){
                    $('#apiKeyModal .error-message').html(response.error.message);
                } else {
                    $('#apiKeyModal').modal('hide');
                }

                window.location.href = '/';
            },
            error: function(xhr, textStatus, errorThrown) {
                $('#errorDiv').text(xhr.responseJSON.error);
            }
        });
    });
});
