/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'uiRegistry'
], function ($, wrapper, quote, registry) {
    'use strict';

    return function (placeOrderModel) {
        return wrapper.wrap(placeOrderModel, function (originalModel, serviceUrl, payload, messageContainer) {
            let billingComponent = registry.get('checkout.steps.billing-step.payment.afterMethods.billing-address-form');

            if (billingComponent.canUseShippingAddress() && billingComponent.isAddressSameAsShipping()) {
                payload['billingAddress'] = null;
            }

            return originalModel(serviceUrl, payload, messageContainer);
        });
    };
});
