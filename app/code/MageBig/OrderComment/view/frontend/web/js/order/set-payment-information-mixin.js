define([
    'jquery',
    'mage/utils/wrapper',
    'MageBig_OrderComment/js/order/order-comment-assigner'
], function ($, wrapper, orderCommentAssigner) {
    'use strict';

    return function (placeOrderAction) {

        /** Override place-order-mixin for set-payment-information action as they differ only by method signature */
        return wrapper.wrap(placeOrderAction, function (originalAction, messageContainer, paymentData) {
            orderCommentAssigner(paymentData);

            return originalAction(messageContainer, paymentData);
        });
    };
});
