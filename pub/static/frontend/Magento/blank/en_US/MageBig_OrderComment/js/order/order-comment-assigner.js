define([
    'jquery'
], function ($) {
    'use strict';


    /** Override default place order action and add comment to request */
    return function (paymentData) {

        if (paymentData['extension_attributes'] === undefined) {
            paymentData['extension_attributes'] = {};
        }
        var id = $(".payment-method._active").find("input.radio").val();
        paymentData['extension_attributes']['comment'] = $('textarea#'+id).val();
    };
});
