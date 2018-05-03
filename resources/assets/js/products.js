$(function () {

    // Datatable
    var table = $('#products_table').DataTable({
        order: [1, 'asc'],
        columnDefs: [
            { targets: [0, 15, 16], orderable: false}
        ],
        paging: true,
        info: true
    }); 

    // Date Range Filter
    $('#products_table tfoot th.filter-date').each( function (key) {
        var title = $(this).text();
        $(this).html('<div class="input-prepend input-group"><span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span><input type="text" style="width: 200px" name="' + $.trim(title).replace(/ /g, '') + '"  placeholder="Search ' + $.trim(title) + '" class="form-control daterange"/></div>');
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

    $("#products_table tfoot").on("mousedown", "th", function (event) {
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
    $.each($('.filter-date', table.table().footer()), function () {
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
    $('#products_table tfoot th.filter-input').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" class="form-control text" placeholder="Search '+title+'" />' );
    } );

    // Apply the search
    table.columns().every( function () {
        var that = this;
 
        $( 'input.text', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );

        $( 'select', this.footer() ).change(function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );

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

        var data = {
            pheramor_id: $('#pheramor_id').val(),
            sales_email: $('#sales_email').val(),
            note: $('#note').val()
        }

        var url = 'products'
        axios.post(url, data)
            .then(function (response) {
                if (response.data.status == true) {
                    location.reload();
                } else {
                    console.log('creation failed.')
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    }

    // Add new customer
    $('#add_customer').click(function() {
        $('#add_account_modal').modal('show');
        $('#staffModalLabel').html('Add Customer');

        $('#pheramor_id').val('');
        $('#sales_email').val('');
        $('#note').val('');
    });

    // Update customer status date
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });

    $('.update-product').click(function() {

        $('#update_product_modal').modal('show');
        initUpdateStatusModal();

        var data = $(this).data('product');

        $('#update_modal_label').text('Pheramor ID: ' + data.pheramor_id);

        $('#sales_date').val(data.sales_date);
        $('#account_connected_date').val(data.account_connected_date);
        $('#swab_returned_date').val(data.swab_returned_date);
        $('#ship_to_lab_date').val(data.ship_to_lab_date);
        $('#lab_received_date').val(data.lab_received_date);
        $('#sequenced_date').val(data.sequenced_date);
        $('#uploaded_to_server_date').val(data.uploaded_to_server_date);
        $('#bone_marrow_consent_date').val(data.bone_marrow_consent_date);
        $('#bone_marrow_shared_date').val(data.bone_marrow_shared_date);

        $('#btn_update_status').data('id', data.id);
    });

    // Update customer status on bulk
    $('#update_status_bulk').click(function() {

        $('#update_product_modal').modal('show');
        initUpdateStatusModal();

        var selectedProducts = getSelectedProducts();
        $('#btn_update_status').data('id', selectedProducts);
        $('#update_modal_label').text(selectedProducts.length + ' customers selected.');
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
        $('#sales_date').val('');
        $('#account_connected_date').val('');
        $('#swab_returned_date').val('');
        $('#ship_to_lab_date').val('');
        $('#lab_received_date').val('');
        $('#sequenced_date').val('');
        $('#uploaded_to_server_date').val('');
        $('#bone_marrow_consent_date').val('');
        $('#bone_marrow_shared_date').val('');
    }

    // Update status confirmation
    var updateStatusConfirm = function(callback){

        $("#btn_update_status").on("click", function(){
            $("#mi-modal").modal('show');

            $('#summary_sales').text($('#sales_date').val());
            $('#summary_account_connected').text($('#account_connected_date').val());
            $('#summary_swab_returned').text($('#swab_returned_date').val());
            $('#summary_ship_to_lab').text($('#ship_to_lab_date').val());
            $('#summary_lab_received').text($('#lab_received_date').val());
            $('#summary_sequenced').text($('#sequenced_date').val());
            $('#summary_uploaded_to_server').text($('#uploaded_to_server_date').val());
            $('#summary_bone_marrow_consent').text($('#bone_marrow_consent_date').val());
            $('#summary_bone_marrow_shared').text($('#bone_marrow_shared_date').val());
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

            var data = {
                ids: $('#btn_update_status').data('id'),
                sales_date: $('#sales_date').val(),
                account_connected_date: $('#account_connected_date').val(),
                swab_returned_date: $('#swab_returned_date').val(),
                ship_to_lab_date: $('#ship_to_lab_date').val(),
                lab_received_date: $('#lab_received_date').val(),
                sequenced_date: $('#sequenced_date').val(),
                uploaded_to_server_date: $('#uploaded_to_server_date').val(),
                bone_marrow_consent_date: $('#bone_marrow_consent_date').val(),
                bone_marrow_shared_date: $('#bone_marrow_shared_date').val()
            }

            var url = 'products/update_status';
            axios.post(url, data)
                .then(function (response) {
                    if (response.data.status == true) {
                        location.reload();
                    } else {
                        console.log('Update customer failed!');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        }else{
            console.log('The operation to delete was canceled by user!')
        }
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

    // Show/Hide Advanced Filters
    $('#products_table tfoot').hide();

    $('#show_advanced_filter').on('ifChecked', function(event){
        $('#products_table tfoot').show();
    });

    $('#show_advanced_filter').on('ifUnchecked', function(event){
        $('#products_table tfoot').hide();
    });
})