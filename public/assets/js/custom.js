function ajaxCall(h, ajaxType, formData, submitButton = false, successCallBack = false) {
    if (!empty(submitButton)) {
        submitButton.data('lbl', submitButton.html()).html('Loading...').prop("disabled", true);
    }
    let ajaxRequest;
    if (ajaxType === 'File') {
        ajaxRequest = {
            type: "POST",
            dataType: 'json',
            url: h,
            data: formData,
            contentType: false,
            processData: false,
        };
    } else {
        ajaxRequest = {
            type: "POST",
            dataType: 'json',
            url: h,
            data: formData,
        };
    }
    return $.ajax(ajaxRequest).done(function (data) {
        if (!data.error && !empty(data.msg)) {
            alertify.logPosition("top right"), alertify.success(data.msg);
        }
        if (data.error && !empty(data.msg)) {
            alertify.logPosition("top right"), alertify.error(data.msg);
        }
        if (typeof successCallBack == "function") successCallBack(data);
        return false;
    })
        .fail(ajaxError)
        .always(function () {
            if (!empty(submitButton)) {
                submitButton.html(submitButton.data('lbl')).prop("disabled", false);
            }
        });
}
function refreshTable(idName) {
    $('#' + idName).dataTable().fnDestroy();
    createTable('#' + idName);
}
jQuery.browser = {};
(function () {
    jQuery.browser.msie = false;
    jQuery.browser.version = 0;
    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
        jQuery.browser.msie = true;
        jQuery.browser.version = RegExp.$1;
    }
})();
function empty(mixed_var) {
    var undef, key, i, len;
    var emptyValues = ['undefined', undef, null, false, 0, '', '0'];
    for (i = 0, len = emptyValues.length; i < len; i++) {
        if (mixed_var === emptyValues[i]) {
            return true;
        }
    }
    if (typeof mixed_var === 'object') {
        for (key in mixed_var) {
            return false;
        }
        return true;
    }
    return false;
}
function ajaxError(jqXHR, textStatus, errorThrown) {
    var M = '';
    if (jqXHR.status === 0) {
        M = 'Unable to connect to the server';
    } else if (jqXHR.status == 404) {
        M = 'Requested page not found. [404]';
    } else if (jqXHR.status == 500) {
        M = 'Internal Server Error [500].';
    } else if (textStatus === 'parsererror') {
        M = 'Requested JSON parse failed:<br />' + jqXHR.responseText;
    } else if (textStatus === 'timeout') {
        M = 'Request Time out.';
    } else if (textStatus === 'abort') {
        M = 'Ajax request aborted.';
    } else {
        M = 'Uncaught Error ' + jqXHR.textStatus;
    }
    alertify.logPosition("top right"), alertify.error(M);
}
function show_hide(showDiv, hideDiv) {
    $(hideDiv).hide();
    $(showDiv).show();
}