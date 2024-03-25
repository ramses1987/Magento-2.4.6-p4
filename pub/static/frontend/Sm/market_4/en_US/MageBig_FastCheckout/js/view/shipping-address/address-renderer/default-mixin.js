define([
    'ko',
    'Magento_Checkout/js/model/checkout-data-resolver'
], function (
    ko,
    checkoutDataResolver
) {
    'use strict';

    var mixin = {
        defaults: {
            template: 'MageBig_FastCheckout/shipping-address/address-renderer/default'
        },

        selectAddress: function () {
            this._super();
            window.checkoutConfig.isUpdatedShipping = false;
            checkoutDataResolver.resolveBillingAddress();
        }
    };

    return function (target) {
        if (!window.checkoutConfig.fastCheckout.isEnable) {
            return target;
        }

        return target.extend(mixin);
    };
});
