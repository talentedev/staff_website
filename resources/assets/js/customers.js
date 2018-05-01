$(function () {

    // Datatable initialize
    $('#customers_table').DataTable({
        order: [0, 'asc'],
        columnDefs: [
            { targets: [4], orderable: false}
        ],
        paging: true,
        info: true
    });

    // Form validation
    $('#customer_form').validator();

    $('#customer_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            console.log('form is not valid');
        } else {
            e.preventDefault();
            submit();
        }
    });

    // Add new customer
    $('#add_customer').click(function() {
        $('#add_account_modal').modal('show');
        $('#staffModalLabel').html('Add Customer');

        $('#name').val('');
        $('#email').val('');
        $('#note').val('');

        $('#btn_save_data').data('staff', 'add');

    });

    // Edit customer
    $('.edit-user').click(function() {

        $('#add_account_modal').modal('show');
        $('#staffModalLabel').html('Edit Customer');

        var data = $(this).data('user');

        $('#name').val(data.name);
        $('#email').val(data.sales_email);
        $('#note').val(data.note);

        $('#btn_save_data').data('staff', 'edit');
        $('#btn_save_data').data('id', data.id);
    });

    // Save data
    function submit() {

        var data = {
            name: $('#name').val(),
            email: $('#email').val(),
            note: $('#note').val()
        }

        var state = $('#btn_save_data').data('staff')

        if (state == 'add') {
            var url = 'customers'
            axios.post(url, data)
                .then(function (response) {
                    if (response.data.message == 'Pheramor account successfully added') {
                        location.reload();
                    } else {
                        console.log('creation failed.')
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        } else if(state == 'edit') {
            var id = $('#btn_save_data').data('id');
            var url = 'customers/' + id;
            axios.put(url, data, id)
                .then(function (response) {
                    if (response.data.message == 'Customer successfully updated') {
                        location.reload();
                    } else {
                        console.log('updating failed.')
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
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
            var url = 'customers/' + data.id;
            axios.delete(url)
                .then(function (response) {
                    if (response.data.message == 'Customer is deleted successfully') {
                        location.reload();
                    } else {
                        alert('Delete user failed!');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        }else{
            console.log('The operation to delete was canceled by user!')
        }
    });
})
