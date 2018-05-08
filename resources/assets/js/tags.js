$(function () {
	// submit form data
	$('#btn_submit').click(function() {
		var data = {
			sales_date: $('#sales_date').val(),
			ship_date: $('#ship_date').val(),
			account_connected_date: $('#account_connected_date').val(),
			swab_returned_date: $('#swab_returned_date').val(),
			ship_to_lab_date: $('#ship_to_lab_date').val(),
			lab_received_date: $('#lab_received_date').val(),
			sequenced_date: $('#sequenced_date').val(),
			uploaded_to_server_date: $('#uploaded_to_server_date').val(),
			bone_marrow_consent_date: $('#bone_marrow_consent_date').val(),
			bone_marrow_shared_date: $('#bone_marrow_shared_date').val()
		};
		var url = 'tags';
        axios.post(url, data)
            .then(function (response) {
                if (response.data.status == true) {
                    showResult(true);
                } else {
                    showResult(false);
                }
            })
            .catch(function (error) {
                showResult(false);
            });
	});

	// Show modal for request result.
    function showResult(status) {
        $("#result_modal").modal('show');

        if (status) {
            $("#result_modal .modal-title").text('Success');
            $("#result_modal .modal-body").text('Tags updated successfully.');
            $('#btn_result_modal').data('status', true);
        } else {
            $("#result_modal .modal-title").text('Failed');
            $("#result_modal .modal-body").text("We can't update the tags. Please try again.");
            $('#btn_result_modal').data('status', false);
        }
    }

    $('#btn_result_modal').click(function() {
        if($(this).data('status')) {
            location.reload();
        }
    });
});