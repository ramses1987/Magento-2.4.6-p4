define([
    'jquery',
    'underscore',
    'Magento_Ui/js/form/form',
    'ko',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/action/select-shipping-address',
    'Magento_Checkout/js/action/set-shipping-information',
    'Magento_Checkout/js/model/checkout-data-resolver',
    'Magento_Checkout/js/checkout-data',
    'uiRegistry',
    'Magento_Customer/js/model/address-list',
    'Magento_Checkout/js/model/address-converter'
], function (
    $,
    _,
    Component,
    ko,
    customer,
    quote,
    selectShippingAddress,
    setShippingInformationAction,
    checkoutDataResolver,
    checkoutData,
    registry,
    addressList,
    addressConverter
) {
    'use strict';

    let mixin = {
        isSelected: ko.computed(function () {
            let value = quote.shippingMethod() ?
                quote.shippingMethod()['carrier_code'] + '_' + quote.shippingMethod()['method_code'] :
                null;

            if (value && window.checkoutConfig.fastCheckout.isEnable) {
                let timerId = setInterval(function () {
                    let input = 'input[value="' + value + '"]';

                    if ($(input).length) {
                        $('.table-checkout-shipping-method tr').removeClass('active');
                        $(input).parents('tr').addClass('active');

                        clearInterval(timerId);
                    }
                }, 100);
            }

            return value;
        }),

        selectShippingMethod: function (shippingMethod) {
            this._super(shippingMethod);

            registry.async('checkoutProvider')(function (checkoutProvider) {
                let shippingAddressData = checkoutData.getShippingAddressFromData();
                if (shippingAddressData) {
                    checkoutProvider.set(
                        'shippingAddress',
                        $.extend(true, {}, checkoutProvider.get('shippingAddress'), shippingAddressData)
                    );
                }
            });
            setShippingInformationAction();
        },

        saveNewAddress: function () {
            this._super();

            if (!this.source.get('params.invalid')) {
                window.checkoutConfig.isUpdatedShipping = false;
                checkoutDataResolver.resolveBillingAddress();
            }
        }
    };

    return function (target) {
        if (!window.checkoutConfig.fastCheckout.isEnable) {
            return target;
        }

        return target.extend(mixin);
    };
});
