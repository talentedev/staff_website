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
/******/ 	return __webpack_require__(__webpack_require__.s = 57);
/******/ })
/************************************************************************/
/******/ ({

/***/ 57:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(58);


/***/ }),

/***/ 58:
/***/ (function(module, exports) {

$(function () {
    // submit form data
    $('#btn_submit').click(function () {
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
        axios.post(url, data).then(function (response) {
            if (response.data.status == true) {
                showResult(true);
            } else {
                showResult(false);
            }
        }).catch(function (error) {
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

    $('#btn_result_modal').click(function () {
        if ($(this).data('status')) {
            location.reload();
        }
    });
});

/***/ })

/******/ });