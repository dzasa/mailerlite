<!DOCTYPE html>
<html>
<head>
    <title>Subscriber List</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h1>Subscribers</h1>
            <form id="subscribeForm">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="country">Country:</label>
                    <input type="text" class="form-control" id="country" name="country" required>
                </div>
                <button type="submit" class="btn btn-primary">Subscribe</button>
            </form>
            <div id="errorDiv" class="mt-3 text-danger"></div>
            <div id="successDiv" class="mt-3 text-success"></div></div>
        </div>
    <div class="row">
        <table id="subscribersTable" class="display" style="width:100%">
            <thead>
            <tr>
                <th>Email</th>
                <th>Name</th>
                <th>Country</th>
                <th>Subscribe date</th>
                <th>Subscribe time</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="http://cdn.datatables.net/plug-ins/1.10.25/pagination/scrolling.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="{{ asset('assets/js/subscribers.js') }}"></script>
</body>
</html>
