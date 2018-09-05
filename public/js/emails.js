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
/******/ 	return __webpack_require__(__webpack_require__.s = 61);
/******/ })
/************************************************************************/
/******/ ({

/***/ 61:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(62);


/***/ }),

/***/ 62:
/***/ (function(module, exports) {

$(function () {

    var arrSettings = $('#setting_values').data('settings');
    var objSettings = {};

    for (key in arrSettings) {
        objSettings[arrSettings[key].setting_key] = arrSettings[key].setting_value;
    }
    console.log(objSettings);

    for (key in objSettings) {
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
    $('#save_update_email').click(function () {
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
        };
        var url = 'update-status-email';
        axios.post(url, requestData).then(function (response) {
            if (response.data.status == true) {
                location.reload();
            } else {
                alert('Update failed!');
            }
        }).catch(function (error) {
            console.log(error);
        });
    });

    // Save reminder email
    $('#save_reminder_email').click(function () {

        var first_reminder_email = $('#first_reminder_email').val();
        var second_reminder_email = $('#second_reminder_email').val();

        var requestData = {
            first_reminder_email: objSettings.first_reminder_email == first_reminder_email ? '' : first_reminder_email,
            second_reminder_email: objSettings.second_reminder_email == second_reminder_email ? '' : second_reminder_email
        };
        var url = 'update-reminder-email';
        axios.post(url, requestData).then(function (response) {
            if (response.data.status == true) {
                location.reload();
            } else {
                alert('Update failed!');
            }
        }).catch(function (error) {
            console.log(error);
        });
    });
});

/***/ })

/******/ });