<!DOCTYPE html>
<html>
<head>
    <title>API Key</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="modal" id="apiKeyModal" tabindex="-1" role="dialog" aria-labelledby="apiKeyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="apiKeyModalLabel">Enter API Key</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="apiKeyForm">
                    <div class="form-group">
                        <label for="apiKeyInput">API Key:</label>
                        <input type="text" class="form-control" id="apiKeyInput">
                    </div>
                </form>
                <div id="errorDiv" class="mt-3 text-danger"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveApiKeyBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(window).on('load', function() {
        $('#apiKeyModal').modal('show');
    });
</script>
<script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
