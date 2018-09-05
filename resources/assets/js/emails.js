$(function () {

    var arrSettings = $('#setting_values').data('settings');
    var objSettings = {};

    for(key in arrSettings) {
        objSettings[arrSettings[key].setting_key] = arrSettings[key].setting_value;
    }
    console.log(objSettings);

    for(key in objSettings) {
        var id = '#' + key;
        if (key == 'first_reminder_email') {
            $('#first_reminder_email').val(objSettings[key]);
        } else if (key == 'second_reminder_email') {
            $('#second_reminder_email').val(objSettings[key]);
        } else if (objSettings[key] == '1') {
            $(id).iCheck('check');
        } else {
            $(id).iCheck('uncheck');
        }
    }

    // Save status update email
    $('#save_update_email').click(function() {
        var ship_update_email = $('#ship_update_email').iCheck('update')[0].checked;
        var sales_update_email = $('#sales_update_email').iCheck('update')[0].checked;
        var account_update_email = $('#account_update_email').iCheck('update')[0].checked;
        var swab_update_email = $('#swab_update_email').iCheck('update')[0].checked;
        var sequence_update_email = $('#sequence_update_email').iCheck('update')[0].checked;

        var requestData = {
            ship_update_email: ship_update_email,
            sales_update_email: sales_update_email,
            account_update_email: account_update_email,
            swab_update_email: swab_update_email,
            sequence_update_email: sequence_update_email
        }
        var url = 'update-status-email';
        axios.post(url, requestData)
            .then(function (response) {
                if (response.data.status == true) {
                    location.reload();
                } else {
                    alert('Update failed!');
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    });

        // Save reminder email
    $('#save_reminder_email').click(function() {

        var first_reminder_email = $('#first_reminder_email').val();
        var second_reminder_email = $('#second_reminder_email').val();

        var requestData = {
            first_reminder_email: objSettings.first_reminder_email == first_reminder_email ? '' : first_reminder_email,
            second_reminder_email: objSettings.second_reminder_email == second_reminder_email ? '' : second_reminder_email,
        }
        var url = 'update-reminder-email';
        axios.post(url, requestData)
            .then(function (response) {
                if (response.data.status == true) {
                    location.reload();
                } else {
                    alert('Update failed!');
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    });

})