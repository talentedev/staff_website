$(function () {

    // Datatable initialize
    $('#users_table').DataTable({
        order: [0, 'asc'],
        columnDefs: [
            { targets: [6], orderable: false}
        ],
        paging: true,
        info: true
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

    // Show/Hide access code
    $('#generate_code').prop('disabled', true);
    $('#code').prop('disabled', true);
    $('#role').change(function() {
        if($(this).val() == 'street team') {
            $('#generate_code').prop('disabled', false);
            $('#code').prop('disabled', false);
        } else {
            $('#generate_code').prop('disabled', true);
            $('#code').prop('disabled', true);
            $('#code').val('');
        }
    });

    // Form validation
    $('#user_form').validator();

    $('#user_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            console.log('form is not valid');
        } else {
            e.preventDefault();
            submit();
        }
    });

    var email = 'admin@pheramor.com';
    var apiKey = 'bv083j1o67spn0hqek0o3gal9o';
    var tag = 'Street';

    // Add new staff
    $('#add_account').click(function() {
        $('#add_account_modal').modal('show');
        $('#staffModalLabel').html('Add Account');

        $('#name').val('');
        $('#code').val('');
        $('#email').val('');
        $('#tag').val('');
        $('#role').val('admin');

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
        var role = $(this).data('role');

        $('#name').val(data.name);
        $('#code').val(data.source);
        $('#email').val(data.email);
        $('#tag').val(data.tag);
        $('#role').val(role);

        $('#btn_save_data').data('staff', 'edit');
        $('#btn_save_data').data('id', data.id);
    });

    // Save data
    function submit() {

        var data = {
            name: $('#name').val(),
            source: $('#code').val(),
            email: $('#email').val(),
            tag: $('#tag').val(),
            role: $('#role').val()
        }

        var state = $('#btn_save_data').data('staff')

        if (state == 'add') {
            var url = 'staff'
            axios.post(url, data)
                .then(function (response) {
                    if (response.data.status == true) {
                        showCreateResult(true, true);
                    } else {
                        showCreateResult(false, true);
                    }
                })
                .catch(function (error) {
                    showCreateResult(false, true);
                });
        } else if(state == 'edit') {
            var id = $('#btn_save_data').data('id');

            var url = 'staff/' + id;
            axios.put(url, data, id)
                .then(function (response) {
                    if (response.data.status == true) {
                        showCreateResult(true, false);
                    } else {
                        showCreateResult(false, false);
                    }
                })
                .catch(function (error) {
                    showCreateResult(false, false);
                });
        }
    }

    // Show modal for creation or updating result.
    function showCreateResult(status, isCreate) {
        $("#add_account_modal").modal('hide');
        $("#result_modal").modal('show');

        if (status) {
            $("#result_modal .modal-title").text('Success');

            if (isCreate) {
                $("#result_modal .modal-body").text('User created successfully.');
            } else {
                $("#result_modal .modal-body").text('User updated successfully.');
            }

            $('#btn_result_modal').data('status', true);
        } else {
            $("#result_modal .modal-title").text('Failed');

            if (isCreate) {
                $("#result_modal .modal-body").text("We can't create the user, because the same Email or Access Code already exist.");
            } else {
                $("#result_modal .modal-body").text("We can't update the user, because the same Email or Access Code already exist.");
            }

            $('#btn_result_modal').data('status', false);
        }
    }

    // Delete staff
    var modalConfirm = function(callback){

        var userData = null;

        $(".delete-user").on("click", function(){
            $("#mi-modal").modal('show');
            userData = $(this).data('user');
        });

        $("#modal-btn-yes").on("click", function(){
            callback(true, userData);
            $("#mi-modal").modal('hide');
        });
        
        $("#modal-btn-no").on("click", function(){
            callback(false);
            $("#mi-modal").modal('hide');
        });
    };

    modalConfirm(function(confirm, data){
        if(confirm){
            var url = 'staff/' + data.id;
            axios.delete(url)
                .then(function (response) {
                    if (response.data.status == true) {
                        showDeleteResult(true);
                    } else {
                        showDeleteResult(false);
                    }
                })
                .catch(function (error) {
                    showDeleteResult(false);
                });
        }else{
            console.log('The operation to delete was canceled by user!')
        }
    });

    // Show modal for deleting result.
    function showDeleteResult(status) {
        $("#mi-modal").modal('hide');
        $("#result_modal").modal('show');

        if (status) {
            $("#result_modal .modal-title").text('Success');
            $("#result_modal .modal-body").text('User deleted successfully.');
            $('#btn_result_modal').data('status', true);
        } else {
            $("#result_modal .modal-title").text('Failed');
            $("#result_modal .modal-body").text("We can't delete the user. Please try again.");
            $('#btn_result_modal').data('status', false);
        }
    }

    $('#btn_result_modal').click(function() {
        if($(this).data('status')) {
            location.reload();
        }
    });
})
