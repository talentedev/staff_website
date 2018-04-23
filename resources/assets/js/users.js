$(function () {

    // Datatable initialize
    $('#users_table').DataTable({
        order: [0, 'asc'],
        columnDefs: [
            { targets: [6], orderable: false}
        ],
        paging: false,
        info: false
    });

    // Select all rows.
    $('thead input').on('ifChecked', function(event){
        $('tbody input').each(function () {
            $(this).iCheck('check');
        });
    });

    // Deselect all rows.
    $('thead input').on('ifUnchecked', function(event){
        $('tbody input').each(function () {
            $(this).iCheck('uncheck');
        });
    });

    var email = 'admin@pheramor.com';
    var apiKey = 'bv083j1o67spn0hqek0o3gal9o';
    var tag = 'Street';

    // Add new staff
    $('#add_account').click(function() {
        $('#add_account_modal').modal('show');
        $('#staffModalLabel').html('Add Staff');

        $('#name').val('');
        $('#code').val('');
        $('#email').val(email);
        $('#key').val(apiKey);
        $('#tag').val(tag);

        $('#btn_save_data').data('staff', 'add');

    });

    // Generate Access Code
    $('#generate_code').click(function() {
        var min = 100000;
        var max = 999999;
        var x = Math.floor(Math.random()*(max-min+1)+min);
        $('#code').val('staff' + x);
    });

    // Edit staff
    $('.edit-user').click(function() {

        $('#add_account_modal').modal('show');
        $('#staffModalLabel').html('Edit Staff');

        var data = $(this).data('user');

        $('#name').val(data.name);
        $('#code').val(data.source);
        $('#email').val(data.email);
        $('#key').val(data.api_key);
        $('#tag').val(data.tag);

        $('#btn_save_data').data('staff', 'edit');
        $('#btn_save_data').data('id', data.id);
    });

    // Save data
    $('#btn_save_data').click(function() {

        var data = {
            name: $('#name').val(),
            source: $('#code').val(),
            email: $('#email').val(),
            api_key: $('#key').val(),
            tag: $('#tag').val()
        }

        var state = $(this).data('staff')

        if (state == 'add') {
            var url = 'users'
            axios.post(url, data)
                .then(function (response) {
                    if (response.data.message == 'User successfully added') {
                        location.reload();
                    } else {
                        alert('The Access Code is exist already. Please try again with other Access Code.');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        } else if(state == 'edit') {
            var id = $(this).data('id');

            var url = 'users/' + id;
            axios.put(url, data, id)
                .then(function (response) {
                    if (response.data.message == 'User successfully updated') {
                        location.reload();
                    } else {
                        alert('The Access Code is exist already. Please try again with other Access Code.');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    
    });
})