$(function () {

    // Datatable
    var table = $('#products_table').DataTable({
        order: [1, 'asc'],
        columnDefs: [
            { targets: [0, 18, 19], orderable: false}
        ],
        paging: true,
        info: true,
        bSortCellsTop: true
    }); 

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
            format: 'DD-MMM-YYYY'
        }
    });

    var startDate;
    var endDate;
    var dataIdx;  //current data column to work with

    $("#products_table thead tr.filters").on("mousedown", "th", function (event) {
        var visIdx = $(this).parent().children().index($(this));
        dataIdx = table.column.index('fromVisible', visIdx);
    });

    // Function for converting a dd/mmm/yyyy date value into a numeric string for comparison (example 01-Dec-2010 becomes 20101201
    function parseDateValue(rawDate) {

        var d = moment(rawDate, "DD-MMM-YYYY").format('DD-MM-YYYY');
        var dateArray = d.split("-");
        var parsedDate = dateArray[2] + dateArray[1] + dateArray[0];
        return parsedDate;
    }

    //filter on daterange
    $(".daterange").on('apply.daterangepicker', function (ev, picker) {

        ev.preventDefault();

        //if blank date option was selected
        if ((picker.startDate.format('DD-MMM-YYYY') == "01-Jan-0001") && (picker.endDate.format('DD-MMM-YYYY')) == "01-Jan-0001") {
            $(this).val('Blank');

            val = "^$";

            table.column(dataIdx)
               .search(val, true, false, true)
               .draw();
        }
        else {
            //set field value
            $(this).val(picker.startDate.format('DD-MMM-YYYY') + ' to ' + picker.endDate.format('DD-MMM-YYYY'));

            //run date filter
            startDate = picker.startDate.format('DD-MMM-YYYY');
            endDate = picker.endDate.format('DD-MMM-YYYY');

            var dateStart = parseDateValue(startDate);
            var dateEnd = parseDateValue(endDate);

            var filteredData = table
                    .column(dataIdx)
                    .data()
                    .filter(function (value, index) {

                        var evalDate = value === "" ? 0 : parseDateValue(value);
                        if ((isNaN(dateStart) && isNaN(dateEnd)) || (evalDate >= dateStart && evalDate <= dateEnd)) {

                            return true;
                        }
                        return false;
                    });

            var val = "";
            for (var count = 0; count < filteredData.length; count++) {

                val += filteredData[count] + "|";
            }

            val = val.slice(0, -1);

            table.column(dataIdx)
                  .search(val ? "^" + val + "$" : "^" + "-" + "$", true, false, true)
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
       
    // Apply the search
    $.each($('.filter-date', table.table().header()), function () {
        var column = table.column($(this).index());
        // console.log(column);
        $('input', this).on('keyup change', function () {
            console.log(this.value);
            if (column.search() !== this.value) {
                column
                    .search(this.value)
                    .draw();
            }
        });
    });

    // Setup - add a input to each footer cell
    $('#products_table thead tr.filters th.filter-input').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" class="form-control text" placeholder="Search '+title+'" />' );
    } );

    $.each($('.filter-input', table.table().header()), function () {
        var column = table.column($(this).index());
        // console.log(column);
        $('input', this).on('keyup change', function () {
            console.log(this.value);
            if (column.search() !== this.value) {
                column
                    .search(this.value)
                    .draw();
            }
        });
    });

    $( '#filter_source' ).change(function () {
        if ( table.search() !== this.value ) {
            table
                .search( this.value )
                .draw();
        }
    } );

    // Clear All Filters
    $('#btn_clear_filter').click(function() {
        console.log('clear filter');
        $('#products_table thead input').val('').change();
        $( '#filter_source' ).val('').change();
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
            location.reload();
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

    $('.update-product').click(function() {

        $('#update_product_modal').modal('show');
        initUpdateStatusModal();

        var data = $(this).data('product');

        $('#update_modal_label').text('Pheramor ID: ' + data.pheramor_id);

        $('#sales_date').val(data.sales_date);
        $('#ship_date').val(data.ship_date);
        $('#account_connected_date').val(data.account_connected_date);
        $('#swab_returned_date').val(data.swab_returned_date);
        $('#ship_to_lab_date').val(data.ship_to_lab_date);
        $('#lab_received_date').val(data.lab_received_date);
        $('#sequenced_date').val(data.sequenced_date);
        $('#uploaded_to_server_date').val(data.uploaded_to_server_date);
        $('#bone_marrow_consent_date').val(data.bone_marrow_consent_date);
        $('#bone_marrow_shared_date').val(data.bone_marrow_shared_date);

        $('#first_name').prop('disabled', false);
        $('#last_name').prop('disabled', false);
        $('#sales_email').prop('disabled', false);
        $('#account_email').prop('disabled', false);
        $('#phone').prop('disabled', false);

        $('#first_name').val(data.first_name);
        $('#last_name').val(data.last_name);
        $('#sales_email').val(data.sales_email);
        $('#account_email').val(data.account_email);
        $('#phone').val(data.phone);

        $('#btn_update_status').data('id', data.id);
        $('#btn_update_status').data('product', data);
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

    // Update Note for a product
    $('.update-note').click(function() {

        $('#note_modal').modal('show');

        var data = $(this).data('product');

        $('#note_modal textarea').val(data.note);
        $('#btn_update_note').data('id', data.id);
    });

    $('#btn_update_note').click(function() {

        waitingDialog.show('Updating note...');

        var postData = {
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
            phone: $('#phone').val(),
            note: $('#note_modal textarea').val()
        }

        var id = $(this).data('id');
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

        var userData = null;

        $(".delete-product").on("click", function(){
            $("#delete-mi-modal").modal('show');
            userData = $(this).data('product');
        });

        $("#delete-modal-btn-yes").on("click", function(){
            callback(true, userData);
            $("#delete-mi-modal").modal('hide');
            waitingDialog.show('Deleting customer...');
        });
        
        $("#delete-modal-btn-no").on("click", function(){
            callback(false);
            $("#delete-mi-modal").modal('hide');
        });
    };

    modalDeleteConfirm(function(confirm, data){
        if(confirm){
            var url = 'customers/' + data.id;
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

    // Show/Hide Advanced Filters
    $('#products_table thead tr.filters').hide();

    $('#show_advanced_filter').on('ifChecked', function(event){
        $('#products_table thead tr.filters').show();
    });

    $('#show_advanced_filter').on('ifUnchecked', function(event){
        $('#products_table thead tr.filters').hide();
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
            if (existData[key].pheramor_id == data[0]) {
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