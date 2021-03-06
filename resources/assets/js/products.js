$(function () {

    // Show/Hide Advanced Filters
    $('#products_table thead tr.filters').hide();

    var customer;
    var deleteUserId;

    // Datatable
    var table = $('#products_table').DataTable({
        "order": [1, 'asc'],
        "columnDefs": [
            { "targets": [0, 19, 20], "orderable": false}
        ],
        "paging": true,
        "info": true,
        "bSortCellsTop": true,
        "processing": true,
        "serverSide": true,
        "ajax":{
                 "url": "getCustomersByAjax",
                 "dataType": "json",
                 "type": "POST",
                 "data":{ _token: $('input[name="_token"').val()}
               },
        "columns": [
            { "data": "id" },
            { "data": "pheramor_id" },
            { "data": "sales_email" },
            { "data": "account_email" },
            { "data": "first_name" },
            { "data": "last_name" },
            { "data": "phone" },
            { "data": "source" },
            { "data": "created_at" },
            { "data": "sales_date" },
            { "data": "ship_date" },
            { "data": "account_connected_date" },
            { "data": "swab_returned_date" },
            { "data": "ship_to_lab_date" },
            { "data": "lab_received_date" },
            { "data": "sequenced_date" },
            { "data": "uploaded_to_server_date" },
            { "data": "bone_marrow_consent_date" },
            { "data": "bone_marrow_shared_date" },
            { "data": "note" },
            { "data": "actions" }
        ],
        "initComplete": function(settings, json) {
            var api = this.api();

            // Apply the search by column
            api.columns().every(function() {
              var that = this;
              // Text
              $('#products_table thead').on('keyup', ".text-search", function(e) {
                if (that.search() !== this.value && e.keyCode == 13) {
                  that
                    .column( $(this).parent().index() )
                    .search(this.value)
                    .draw();
                }
              });
            });

            // Search totally
            $('#products_table input[type="search"]').on('keyup', function(e) {
                var that = this;
                if (that.search() !== this.value && e.keyCode == 13) {
                  that
                    .search(this.value)
                    .draw();
                }
              });
        },
        "drawCallback": function() {
            init();
        }
    });

    // Initialize
    function init() {
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass   : 'iradio_minimal-blue'
        });

        // Show/Hide Advanced Filters
        $('#show_advanced_filter').on('ifChecked', function(event){
            $('#products_table thead tr.filters').show();
        });

        $('#show_advanced_filter').on('ifUnchecked', function(event){
            $('#products_table thead tr.filters').hide();
        });

        // Update customer infomations
        $('.update-product').click(function() {

            $('#update_product_modal').modal('show');
            initUpdateStatusModal();

            var customerId = $(this).data('id');

            var url = 'customers/' + customerId;
            axios.get(url)
                .then(function (response) {
                    if (response.data.status == true) {
                        customer = response.data.data;

                        $('#update_modal_label').text('Pheramor ID: ' + customer.pheramor_id);

                        $('#sales_date').val(customer.sales_date);
                        $('#ship_date').val(customer.ship_date);
                        $('#account_connected_date').val(customer.account_connected_date);
                        $('#swab_returned_date').val(customer.swab_returned_date);
                        $('#ship_to_lab_date').val(customer.ship_to_lab_date);
                        $('#lab_received_date').val(customer.lab_received_date);
                        $('#sequenced_date').val(customer.sequenced_date);
                        $('#uploaded_to_server_date').val(customer.uploaded_to_server_date);
                        $('#bone_marrow_consent_date').val(customer.bone_marrow_consent_date);
                        $('#bone_marrow_shared_date').val(customer.bone_marrow_shared_date);

                        $('#first_name').prop('disabled', false);
                        $('#last_name').prop('disabled', false);
                        $('#sales_email').prop('disabled', false);
                        $('#account_email').prop('disabled', false);
                        $('#phone').prop('disabled', false);

                        $('#first_name').val(customer.first_name);
                        $('#last_name').val(customer.last_name);
                        $('#sales_email').val(customer.sales_email);
                        $('#account_email').val(customer.account_email);
                        $('#phone').val(customer.phone);

                        $('#btn_update_status').data('id', customer.id);
                        $('#btn_update_status').data('product', customer);
                    } else {
                        console.log('Failed to get the customer.');
                    }
                })
                .catch(function (error) {
                    console.log('Failed to get the customer.');
                });
        });

        // Update note for a customer
        $('.update-note').click(function() {

            $('#note_modal').modal('show');

            var customerId = $(this).data('id');

            var url = 'customers/' + customerId;
            axios.get(url)
                .then(function (response) {
                    if (response.data.status == true) {
                        customer = response.data.data;
                        $('#note_modal textarea').val(customer.note);
                        $('#btn_update_note').data('id', customer.id);

                    } else {
                        console.log('Failed to get the customer.');
                    }
                })
                .catch(function (error) {
                    console.log('Failed to get the customer.');
                });
        });

        // Delete customer
        $(".delete-product").on("click", function(){
            $("#delete-mi-modal").modal('show');
            deleteUserId = $(this).data('id');
        });
    }

    // Date Range Filter
    $('#products_table thead tr.filters th.filter-date').each( function (key) {
        var title = $(this).text();
        $(this).html('<div class="input-prepend input-group"><label class="add-on input-group-addon" for="' + $.trim(title).replace(/ /g, '') + '"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></label><input type="text" style="width: 200px" name="' + $.trim(title).replace(/ /g, '') + '"  placeholder="Search ' + $.trim(title) + '" id="' + $.trim(title).replace(/ /g, '') + '" class="form-control daterange"/></div>');
    } );

    //instantiate datepicker and choose your format of the dates
    $('.daterange').daterangepicker({
        ranges: {
            "Today": [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '7 last days': [moment().subtract(6, 'days'), moment()],
            '30 last days': [moment().subtract(29, 'days'), moment()],
            'This month': [moment().startOf('month'), moment().endOf('month')],
            'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            // 'Blank date': [moment("0001-01-01"), moment("0001-01-01")]
        },
        autoUpdateInput: false,
        opens: "left",
        locale: {
            cancelLabel: 'Clear',
            format: 'YYYY-MM-DD'
        }
    });

    var startDate;
    var endDate;
    var dataIdx;  //current data column to work with

    $("#products_table thead tr.filters").on("mousedown", "th", function (event) {
        var visIdx = $(this).parent().children().index($(this));
        dataIdx = table.column.index('fromVisible', visIdx);
    });

    //filter on daterange
    $(".daterange").on('apply.daterangepicker', function (ev, picker) {

        ev.preventDefault();

        //if blank date option was selected
        if ((picker.startDate.format('YYYY-MM-DD') == "01-Jan-0001") && (picker.endDate.format('YYYY-MM-DD')) == "01-Jan-0001") {
            $(this).val('Blank');

            val = "^$";

            table.column(dataIdx)
               .search(val, true, false, true)
               .draw();
        }
        else {
            //set field value
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));

            //run date filter
            startDate = picker.startDate.format('YYYY-MM-DD');
            endDate = picker.endDate.format('YYYY-MM-DD');

            console.log(startDate);
            console.log(endDate);
            table.column(dataIdx)
                  .search(startDate + '|' + endDate, true, false, true)
                  .draw();
        }
    });

    $(".daterange").on('cancel.daterangepicker', function (ev, picker) {
        ev.preventDefault();
        $(this).val('');
        table.column(dataIdx)
              .search("")
              .draw();
    });

    // Setup - add a input to each footer cell
    $('#products_table thead tr.filters th.filter-input').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" class="form-control text-search" placeholder="Search '+title+'" />' );
    } );

    // Clear All Filters
    $('#btn_clear_filter').click(function() {
        console.log('clear filter');
        location.reload();
    });

    // Add Customer Form validation
    $('#customer_form').validator();

    $('#customer_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            console.log('form is not valid');
        } else {
            e.preventDefault();
            submit();
        }
    });

    // Save data
    function submit() {

        $('#add_account_modal').modal('hide');
        waitingDialog.show('Adding new customer...');

        var data = {
            pheramor_id: $('#pheramor_id').val(),
            first_name: $('#create_first_name').val(),
            last_name: $('#create_last_name').val(),
            sales_email: $('#create_sales_email').val(),
            note: $('#note').val()
        }

        var url = 'customers'
        axios.post(url, data)
            .then(function (response) {
                console.log(response.data);
                if (response.data.status == true) {
                    callbackCreateProduct(true);
                } else {
                    waitingDialog.hide();
                    console.log('creation failed.')
                }
            })
            .catch(function (error) {
                callbackCreateProduct(false);
            });
    }

    function callbackCreateProduct(status) {
        waitingDialog.hide();
        $('#product_create_callback_modal').modal('show');
        if (status) {
            $('#product_create_callback_modal .modal-title').text('Success');
            $('#product_create_callback_modal .modal-body').text('New customer created successfully.');
            $('#btn_callback_confirm').data('status', true);
        } else {
            $('#product_create_callback_modal .modal-title').text('Failed');
            $('#product_create_callback_modal .modal-body').text("New customer didn't created successfully, because the same Pheramor ID or Sales Email already exist.");
            $('#btn_callback_confirm').data('status', false);
        }
    }

    $('#btn_callback_confirm').click(function() {
        $('#product_create_callback_modal').modal('hide');
        if($(this).data('status')) {
            table.ajax.reload();
        }
    });

    // Add new customer
    $('#add_customer').click(function() {
        $('#add_account_modal').modal('show');
        $('#staffModalLabel').html('Add Customer');

        $('#pheramor_id').val('');
        $('#create_sales_email').val('');
        $('#create_first_name').val('');
        $('#create_last_name').val('');
        $('#note').val('');
    });

    // Update customer status date
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        endDate: new Date(),
        maxDate: new Date()
    });

    // Hide datepicker after selecting date.
    $('.datepicker').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });

    // Update customer status on bulk
    $('#update_status_bulk').click(function() {

        $('#update_product_modal').modal('show');
        initUpdateStatusModal();

        var selectedProducts = getSelectedProducts();
        $('#btn_update_status').data('id', selectedProducts);
        $('#update_modal_label').text(selectedProducts.length + ' customers selected.');

        $('#first_name').prop('disabled', true);
        $('#last_name').prop('disabled', true);
        $('#sales_email').prop('disabled', true);
        $('#account_email').prop('disabled', true);
        $('#phone').prop('disabled', true);
    });

    // Get selected rows
    function getSelectedProducts() {
        var selectedRows = [];
        $('#products_table tbody input').each(function() {
            if($(this).is(':checked')) {
                selectedRows.push($(this).data('id'));
            }
        });
        return selectedRows;
    }

    // initialize update product modal
    function initUpdateStatusModal() {
        $('#first_name').val('');
        $('#last_name').val('');
        $('#sales_date').val('');
        $('#ship_date').val('');
        $('#account_connected_date').val('');
        $('#swab_returned_date').val('');
        $('#ship_to_lab_date').val('');
        $('#lab_received_date').val('');
        $('#sequenced_date').val('');
        $('#uploaded_to_server_date').val('');
        $('#bone_marrow_consent_date').val('');
        $('#bone_marrow_shared_date').val('');
        $('#sales_email').val('');
        $('#account_email').val('');
        $('#phone').val('');
    }

    // Update status confirmation
    var updateStatusConfirm = function(callback){

        $("#btn_update_status").on("click", function(){
            $("#mi-modal").modal('show');

            $('#summary_sales_date').text($('#sales_date').val());
            $('#summary_ship_date').text($('#ship_date').val());
            $('#summary_account_connected_date').text($('#account_connected_date').val());
            $('#summary_swab_returned_date').text($('#swab_returned_date').val());
            $('#summary_ship_to_lab_date').text($('#ship_to_lab_date').val());
            $('#summary_lab_received_date').text($('#lab_received_date').val());
            $('#summary_sequenced_date').text($('#sequenced_date').val());
            $('#summary_uploaded_to_server_date').text($('#uploaded_to_server_date').val());
            $('#summary_bone_marrow_consent_date').text($('#bone_marrow_consent_date').val());
            $('#summary_bone_marrow_shared_date').text($('#bone_marrow_shared_date').val());
            $('#summary_first_name').text($('#first_name').val());
            $('#summary_last_name').text($('#last_name').val());
            $('#summary_sales_email').text($('#sales_email').val());
            $('#summary_account_email').text($('#account_email').val());
            $('#summary_phone').text($('#phone').val());

            $('#mi-modal .modal-body div').each(function() {
                $(this).removeClass('bg-green');
                var id = $(this).children().last().attr('id').replace('summary_','');

                var data = $('#btn_update_status').data('product');

                var isBulk = $.isArray($('#btn_update_status').data('id'));

                if (isBulk) {
                    if ($(this).children().last().text() != '') {
                        $(this).addClass('bg-green');
                    }
                } else {
                    if (data[id] == null && $(this).children().last().text() != '') {
                        $(this).addClass('bg-green');
                    }
                    if (data[id] != null &&  data[id] != $(this).children().last().text()) {
                        $(this).addClass('bg-green');
                    }
                }
            });
        });

        $("#modal-btn-yes").on("click", function(){
            callback(true);
            $("#mi-modal").modal('hide');
        });
        
        $("#modal-btn-no").on("click", function(){
            callback(false);
            $("#mi-modal").modal('hide');
        });
    };

    updateStatusConfirm(function(confirm){
        if(confirm){

            $("#mi-modal").modal('hide');
            $('#update_product_modal').modal('hide');
            waitingDialog.show('Updating status dates...');

            var data = {
                ids: $('#btn_update_status').data('id'),
                sales_date: $('#sales_date').val(),
                ship_date: $('#ship_date').val(),
                account_connected_date: $('#account_connected_date').val(),
                swab_returned_date: $('#swab_returned_date').val(),
                ship_to_lab_date: $('#ship_to_lab_date').val(),
                lab_received_date: $('#lab_received_date').val(),
                sequenced_date: $('#sequenced_date').val(),
                uploaded_to_server_date: $('#uploaded_to_server_date').val(),
                bone_marrow_consent_date: $('#bone_marrow_consent_date').val(),
                bone_marrow_shared_date: $('#bone_marrow_shared_date').val(),
                first_name: $('#first_name').val(),
                last_name: $('#last_name').val(),
                sales_email: $('#sales_email').val(),
                account_email: $('#account_email').val(),
                phone: $('#phone').val()
            }

            var url = 'customers/update_status';
            axios.post(url, data)
                .then(function (response) {
                    if (response.data.status == true) {
                        callbackUpdateStatus(true);
                    } else {
                        console.log('Update customer failed!');
                    }
                })
                .catch(function (error) {
                    callbackUpdateStatus(false);
                });
        }else{
            console.log('The operation to delete was canceled by user!')
        }
    });

    function callbackUpdateStatus(status) {
        waitingDialog.hide();
        $('#product_create_callback_modal').modal('show');
        if (status) {
            $('#product_create_callback_modal .modal-title').text('Success');
            $('#product_create_callback_modal .modal-body').text('Customer information updated successfully.');
            $('#btn_callback_confirm').data('status', true);
        } else {
            $('#product_create_callback_modal .modal-title').text('Failed');
            $('#product_create_callback_modal .modal-body').text("Customer information didn't updated.");
        }
    }

    // Update note for a customer
    $('#btn_update_note').click(function() {

        waitingDialog.show('Updating note...');

        var postData = {
            sales_date: customer.sales_date,
            ship_date: customer.ship_date,
            account_connected_date: customer.account_connected_date,
            swab_returned_date: customer.swab_returned_date,
            ship_to_lab_date: customer.ship_to_lab_date,
            lab_received_date: customer.lab_received_date,
            sequenced_date: customer.sequenced_date,
            uploaded_to_server_date: customer.uploaded_to_server_date,
            bone_marrow_consent_date: customer.bone_marrow_consent_date,
            bone_marrow_shared_date: customer.bone_marrow_shared_date,
            first_name: customer.first_name,
            last_name: customer.last_name,
            sales_email: customer.sales_email,
            account_email: customer.account_email,
            phone: customer.phone,
            note: $('#note_modal textarea').val()
        }

        var id = customer.id;
        var url = 'customers/' + id;
        axios.put(url, postData, id)
            .then(function (response) {
                console.log(response.data);
                if (response.data.status == true) {
                    location.reload();
                } else {
                    console.log('Note upating failed.');
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    });

    // Delete product
    var modalDeleteConfirm = function(callback){

        $("#delete-modal-btn-yes").on("click", function(){
            callback(true, deleteUserId);
            $("#delete-mi-modal").modal('hide');
            waitingDialog.show('Deleting customer...');
        });
        
        $("#delete-modal-btn-no").on("click", function(){
            callback(false);
            $("#delete-mi-modal").modal('hide');
        });
    };

    modalDeleteConfirm(function(confirm, id){
        if(confirm){
            var url = 'customers/' + id;
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
        $("#delete-mi-modal").modal('hide');
        waitingDialog.hide();
        $("#product_create_callback_modal").modal('show');

        if (status) {
            $('#product_create_callback_modal .modal-title').text('Success');
            $('#product_create_callback_modal .modal-body').text('Customer deleted successfully.');
            $('#btn_callback_confirm').data('status', true);
        } else {
            $('#product_create_callback_modal .modal-title').text('Failed');
            $('#product_create_callback_modal .modal-body').text("Customer didn't deleted.");
        }
    }

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

    ////////////////////////////// Read CSV File ////////////////////////////////
    $('#get_csv_file').click(function() {
        $('#upload_csv').click();
    });

    $('#csv_ignore_first_row').iCheck('check');

    $('#upload_csv').change(function (e) {
        var reader = new FileReader();
        reader.onload = function () {
            processCSVData($.csv.toArrays(reader.result));
        };
        // start reading the file. When it is done, calls the onload event defined above.
        reader.readAsBinaryString(document.getElementById("upload_csv").files[0]);
        document.getElementById("upload_csv").value = "";
        $('#csv_sales_date').val('');
        $('#csv_ship_date').val('');
        $('#csv_account_connected_date').val('');
        $('#csv_swab_returned_date').val('');
        $('#csv_ship_to_lab_date').val('');
        $('#csv_lab_received_date').val('');
        $('#csv_sequenced_date').val('');
        $('#csv_uploaded_to_server_date').val('');
        $('#csv_bone_marrow_consent_date').val('');
        $('#csv_bone_marrow_shared_date').val('');
    });

    function processCSVData(data) {
        var requestData = [];
        var createData = [];
        var updateData = [];

        var startOffset = 0;
        if($('#csv_ignore_first_row').is(':checked')) {
            startOffset = 1;
        } else {
            startOffset = 0;
        }

        for(var i = startOffset; i < data.length; i++) {
            // Check if currect csv file is input.
            if (Object.keys(data[i]).length > 2) {
                alert('CSV parse error! Invalid data structure!');
                throw new Error("CSV parse error!");
            }
            if (data[i][0] == '') {
                alert("CSV parse error! The values on first column can't be empty!");
                throw new Error("CSV parse error! Empty value. The values on first column can't be empty!");
            }

            if (compareCSVwithExistData(data[i]).result) {
                requestData.push(makeRequestData(data[i], false, compareCSVwithExistData(data[i]).id));
                updateData.push(data[i]);
            } else {
                requestData.push(makeRequestData(data[i], true));
                createData.push(data[i]);
            }
        }

        // save request date
        $("#csv-mi-modal").data('csv-data', requestData);

        $('#csv-mi-modal').modal('show');

        // Display the csv data on the table
        var createTableBody = $('#csv_create_confirm_table tbody');
        createTableBody.empty();
        var createTableBodyContent = '';
        for(key in createData) {
            createTableBodyContent += '<tr>';
            for(var i = 0; i < createData[key].length; i++){
                if(createData[key][i] === null){
                     createData[key][i]= '';
                }
                createTableBodyContent += '<td>'+ createData[key][i].replace(/\s/g, '') +'</td>';
             }
            createTableBodyContent += '</tr>' ;
        }
        createTableBody.append(createTableBodyContent);

        var updateTableBody = $('#csv_update_confirm_table tbody');
        updateTableBody.empty();
        var updateTableBodyContent = '';
        for(key in updateData) {
            updateTableBodyContent += '<tr>';
            for(var i = 0; i < updateData[key].length; i++){
                if(updateData[key][i] === null){
                     updateData[key][i]= '';
                }
                updateTableBodyContent += '<td>'+ updateData[key][i].replace(/\s/g, '') +'</td>';
             }
            updateTableBodyContent += '</tr>' ;
        }
        updateTableBody.append(updateTableBodyContent);
    }

    function compareCSVwithExistData(data) {
        var existData = $('#data_products').data('products');
        for(key in existData) {
            if (existData[key].pheramor_id == data[0].replace(/\s/g, '')) {
                return {
                    result: true,
                    id: existData[key].id
                };
            }
        }
        return {
            result: false
        };
    }

    function makeRequestData(data, isCreate, id = '') {
        var requestData = {
            pheramor_id: data[0].replace(/\s/g, ''),
            sales_email: (data[1] || '').replace(/\s/g, ''),
            is_create: isCreate,
            id: id
        }
        return requestData;
    }

    // Update status confirmation
    var updateCSVConfirm = function(callback){

        $("#csv-modal-btn-yes").on("click", function(){
            callback(true);
            $("#csv-mi-modal").modal('hide');
        });
        
        $("#csv-modal-btn-no").on("click", function(){
            callback(false);
            $("#csv-mi-modal").modal('hide');
        });
    };

    updateCSVConfirm(function(confirm){
        if(confirm){

            $("#csv-mi-modal").modal('hide');
            waitingDialog.show('Uploading CSV...');

            var customers = $("#csv-mi-modal").data('csv-data');
            var updatedDates = {
                sales_date: $('#csv_sales_date').val(),
                ship_date: $('#csv_ship_date').val(),
                account_connected_date: $('#csv_account_connected_date').val(),
                swab_returned_date: $('#csv_swab_returned_date').val(),
                ship_to_lab_date: $('#csv_ship_to_lab_date').val(),
                lab_received_date: $('#csv_lab_received_date').val(),
                sequenced_date: $('#csv_sequenced_date').val(),
                uploaded_to_server_date: $('#csv_uploaded_to_server_date').val(),
                bone_marrow_consent_date: $('#csv_bone_marrow_consent_date').val(),
                bone_marrow_shared_date: $('#csv_bone_marrow_shared_date').val()
            }
            var requestData = {
                customers: customers,
                dates: updatedDates
            }
            var url = 'customers/update_csv';
            axios.post(url, requestData)
                .then(function (response) {
                    console.log(response.data);
                    if (response.data.status == true) {
                        callbackUpdateCSV(true);
                    } else {
                        callbackUpdateCSV(false);
                    }
                })
                .catch(function (error) {
                    callbackUpdateCSV(false);
                });
        }else{
            console.log('The operation to delete was canceled by user!')
        }
    });

    function callbackUpdateCSV(status) {
        waitingDialog.hide();
        $('#product_create_callback_modal').modal('show');
        if (status) {
            $('#product_create_callback_modal .modal-title').text('Success');
            $('#product_create_callback_modal .modal-body').text('CSV File uploaded successfully.');
            $('#btn_callback_confirm').data('status', true);
        } else {
            $('#product_create_callback_modal .modal-title').text('Failed');
            $('#product_create_callback_modal .modal-body').text("CSV File didn't uploaded.");
        }
    }
})