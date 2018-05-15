$(function () {

    // Form validation
    $('#setting_form').validator();

    $('#setting_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            console.log('form is not valid');
        } else {
            e.preventDefault();
            submit();
        }
    });

    function submit() {
        var data = {
            name: $('#name').val(),
            email: $('#email').val(),
            password: $('#password').val()
        }

        var url = 'settings/change_me';
        axios.post(url, data)
            .then(function (response) {
                if (response.data.status == true) {
                    location.reload();
                } else {
                    console.log('Failed to change the account info.')
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    }

    // Form validation
    $('#agile_form').validator();

    $('#agile_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            console.log('form is not valid');
        } else {
            e.preventDefault();
            submitConfig();
        }
    });

    function submitConfig() {
        var data = {
            agile_domain: $('#agile_domain').val(),
            agile_email: $('#agile_email').val(),
            api_key: $('#api_key').val()
        }

        var url = 'settings/change_config';
        axios.post(url, data)
            .then(function (response) {
                if (response.data.status == true) {
                    location.reload();
                } else {
                    console.log('Failed to change the account info.')
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    }
})
