define([
    'jquery',
    'mage/utils/wrapper',
    'MageBig_OrderComment/js/order/order-comment-assigner'
], function ($, wrapper, orderCommentAssigner) {
    'use strict';

    return function (placeOrderAction) {

        /** Override default place order action and add comment to request */
        return wrapper.wrap(placeOrderAction, function (originalAction, paymentData, messageContainer) {
            orderCommentAssigner(paymentData);

            return originalAction(paymentData, messageContainer);
        });
    };
});
