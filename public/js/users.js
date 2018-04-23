/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 51);
/******/ })
/************************************************************************/
/******/ ({

/***/ 51:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(52);


/***/ }),

/***/ 52:
/***/ (function(module, exports) {

$(function () {

    // Datatable initialize
    $('#users_table').DataTable({
        order: [0, 'asc'],
        columnDefs: [{ targets: [6], orderable: false }],
        paging: false,
        info: false
    });

    // Select all rows.
    $('thead input').on('ifChecked', function (event) {
        $('tbody input').each(function () {
            $(this).iCheck('check');
        });
    });

    // Deselect all rows.
    $('thead input').on('ifUnchecked', function (event) {
        $('tbody input').each(function () {
            $(this).iCheck('uncheck');
        });
    });

    var email = 'admin@pheramor.com';
    var apiKey = 'bv083j1o67spn0hqek0o3gal9o';
    var tag = 'Street';

    // Add new staff
    $('#add_account').click(function () {
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
    $('#generate_code').click(function () {
        var min = 100000;
        var max = 999999;
        var x = Math.floor(Math.random() * (max - min + 1) + min);
        $('#code').val('staff' + x);
    });

    // Edit staff
    $('.edit-user').click(function () {

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
    $('#btn_save_data').click(function () {

        var data = {
            name: $('#name').val(),
            source: $('#code').val(),
            email: $('#email').val(),
            api_key: $('#key').val(),
            tag: $('#tag').val()
        };

        var state = $(this).data('staff');

        if (state == 'add') {
            var url = 'users';
            axios.post(url, data).then(function (response) {
                if (response.data.message == 'User successfully added') {
                    location.reload();
                } else {
                    alert('The Access Code is exist already. Please try again with other Access Code.');
                }
            }).catch(function (error) {
                console.log(error);
            });
        } else if (state == 'edit') {
            var id = $(this).data('id');

            var url = 'users/' + id;
            axios.put(url, data, id).then(function (response) {
                if (response.data.message == 'User successfully updated') {
                    location.reload();
                } else {
                    alert('The Access Code is exist already. Please try again with other Access Code.');
                }
            }).catch(function (error) {
                console.log(error);
            });
        }
    });
});

/***/ })

/******/ });