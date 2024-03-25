define([
    'jquery',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/action/select-shipping-method',
    'Magento_Checkout/js/model/payment-service',
    'Magento_Checkout/js/checkout-data',
    'Magento_Checkout/js/action/select-payment-method',
    'mage/utils/wrapper',
    'Magento_Checkout/js/action/set-shipping-information',
    'uiRegistry'
], function (
    $,
    quote,
    selectShippingMethodAction,
    paymentService,
    checkoutData,
    selectPaymentMethodAction,
    wrapper,
    setShippingInformationAction,
    registry
) {
    'use strict';

    let checkoutConfig = window.checkoutConfig.fastCheckout;

    return function (checkoutDataResolver) {
        if (!checkoutConfig.isEnable) {
            return checkoutDataResolver;
        }

        checkoutDataResolver.resolveShippingRates = wrapper.wrapSuper(
            checkoutDataResolver.resolveShippingRates, function (ratesData) {
                let selectedShippingRate = checkoutData.getSelectedShippingRate(),
                    defaultMethod = checkoutConfig.shippingMethod,
                    availableRate = false;

                if (!selectedShippingRate && defaultMethod) {
                    availableRate = _.find(ratesData, function (rate) {
                        return rate['carrier_code'] + '_' + rate['method_code'] === defaultMethod;
                    });

                    if (availableRate) {
                        selectShippingMethodAction(availableRate);
                        registry.async('checkoutProvider')(function (checkoutProvider) {
                            let shippingAddressData = checkoutData.getShippingAddressFromData();
                            if (shippingAddressData) {
                                checkoutProvider.set(
                                    'shippingAddress',
                                    $.extend(true, {}, checkoutProvider.get('shippingAddress'), shippingAddressData)
                                );
                            }
                        });
                        setShippingInformationAction().done(function () {
                            window.checkoutConfig.isUpdatedShipping = true;
                        });
                        checkoutData.setSelectedShippingRate(availableRate);
                    }
                } else {
                    this._super(ratesData);
                }
            })

        checkoutDataResolver.resolvePaymentMethod = wrapper.wrapSuper(
            checkoutDataResolver.resolvePaymentMethod, function () {
                let availablePaymentMethods = paymentService.getAvailablePaymentMethods(),
                    selectedPaymentMethod = checkoutData.getSelectedPaymentMethod(),
                    defaultMethod = checkoutConfig.paymentMethod;

                if (!selectedPaymentMethod && defaultMethod && availablePaymentMethods.length > 0) {
                    availablePaymentMethods.some(function (payment) {
                        if (payment.method === defaultMethod) {
                            selectPaymentMethodAction(payment);
                            checkoutData.setSelectedPaymentMethod(defaultMethod);
                        }
                    });
                } else {
                    this._super();
                }
            })

        return checkoutDataResolver;
    };
});
