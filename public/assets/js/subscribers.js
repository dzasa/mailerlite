var table = null;

$(document).ready(function() {
    table = $('#subscribersTable').DataTable({
        "fnInfoCallback": function( settings, iStart, iEnd, iMax, iTotal, sPre ) {
            var response = settings.json;
            if(!response.meta) {
                return;
            }

            var nextCursor = response.meta.next_cursor;
            var prevCursor = response.meta.prev_cursor;

            return (prevCursor ? '<button class="cursor btn" data-cursor="' + prevCursor + '">Previous</button>' : '') +
                (nextCursor ? '<button class="cursor btn" data-cursor="' + nextCursor + '">Next</button>' : '')
        },
        "sDom": "lfrti",
        processing: true,
        serverSide: true,
        ajax: {
            url: '/subscribers',
            type: 'GET',
            data: {
                cursor: '',
            }
        },
        error: function(data) {
            $('#errorDiv').text(data.responseJSON.error);
        },
        columns: [
            { data: 'email',
                render: function(data) {
                    return '<a href="#" class="editBtn" data-id="' + data + '">'+data+'</a>';
                }
            },
            { data: 'fields.name' },
            { data: 'fields.country' },
            { data: 'subscribed_at',
                render: function(subscribed_at) {
                    return moment(subscribed_at).format("d/m/Y");
                }
            },
            { data: 'subscribed_at',
                data: 'subscribed_at',
                render: function(subscribed_at) {
                    return moment(subscribed_at).format("hh:mm:ss");
                }
            },
            {
                data: 'id',
                render: function(data) {
                    return '<button class="deleteBtn btn" data-id="' + data + '">Delete</button>';
                }
            },
        ],
    });

    $.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) {
        var response = settings.jqXHR.responseJSON;
        $('#errorDiv').text(response.error);
    };

    $(document).on('click', '.cursor', function() {
        table.context[0].ajax.data.cursor = $(this).data('cursor');
        console.log($(this).data('cursor'));
        table.ajax.reload();
    });

    $('#subscribeForm').on('submit', function(e) {
        e.preventDefault();

        // Clear error and success messages
        $('#errorDiv').empty();
        $('#successDiv').empty();

        // Get form data
        var email = $('#email').val();
        var name = $('#name').val();
        var country = $('#country').val();

        // Call MailerLite API to create a subscriber
        $.ajax({
            url: '/subscribers',
            method: 'POST',
            data: {email: email, name: name, country: country},
            success: function(response) {
                table.ajax.reload();
                $('#successDiv').text('Subscriber saved successfully!');
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log(xhr.responseJSON);
                if(xhr.responseJSON.errors){
                    var errors = '';
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        errors += value[0]+' ';
                    });

                    $('#errorDiv').text(errors);
                } else {
                    $('#errorDiv').text(xhr.responseJSON.error);
                }

            }
        });
    });

    $('#subscribersTable tbody').on('click', '.deleteBtn', function() {
        var subscriberId = $(this).data('id');
        $.ajax({
            url: '/subscribers/' + subscriberId,
            type: 'DELETE',
            success: function(result) {
                table.ajax.reload();
            },
            error: function(xhr, textStatus, errorThrown) {
                $('#errorDiv').text(xhr.responseJSON.error);
            }
        });
    });

    $('#subscribersTable tbody').on('click', '.editBtn', function(e) {
        e.preventDefault();

        var subscriberId = $(this).data('id');
        $.ajax({
            url: '/subscribers/' + subscriberId,
            type: 'GET',
            success: function(result) {
                console.log(result);
                $('#email').val(result.data.email);
                $('#name').val(result.data.fields.name);
                $('#country').val(result.data.fields.country);
            },
            error: function(xhr, textStatus, errorThrown) {
                $('#errorDiv').text(xhr.responseJSON.error);
            }
        });
    });
});
