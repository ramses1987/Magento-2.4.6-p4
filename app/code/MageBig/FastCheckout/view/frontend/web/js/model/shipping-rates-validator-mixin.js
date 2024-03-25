define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/shipping-rates-validation-rules',
    'Magento_Checkout/js/model/shipping-address/form-popup-state',
    'Magento_Checkout/js/model/shipping-service',
], function (
    $,
    wrapper,
    shippingRatesValidationRules,
    formPopUpState,
    shippingService
) {
    'use strict';

    let warningTimer;
    var observedElements = [],
        postcodeElements = [],
        postcodeElementName = 'postcode';

    return function (shippingRatesValidator) {
        if (!window.checkoutConfig.fastCheckout.isEnable) {
            return shippingRatesValidator;
        }

        shippingRatesValidator.postcodeValidation = wrapper.wrapSuper(shippingRatesValidator.postcodeValidation, function (postcodeElement) {
            this._super(postcodeElement);

            if (warningTimer) {
                clearTimeout(warningTimer);
            }

            warningTimer = setTimeout(function () {
                $('.message.warning').remove();
            }, 8000);

            $('.message.warning').on('click', function () {
                $(this).remove();
            });
        })

        // shippingRatesValidator.validateFields = wrapper.wrapSuper(shippingRatesValidator.validateFields, function () {
        //
        // })

        shippingRatesValidator.bindHandler = wrapper.wrapSuper(shippingRatesValidator.bindHandler, function (element, delay) {
            var self = this;

            delay = typeof delay === 'undefined' ? self.validateDelay : delay;

            if (element.component.indexOf('/group') !== -1) {
                $.each(element.elems(), function (index, elem) {
                    self.bindHandler(elem);
                });
            } else {
                element.on('value', function () {
                    clearTimeout(self.validateZipCodeTimeout);
                    self.validateZipCodeTimeout = setTimeout(function () {
                        if (element.index === postcodeElementName) {
                            self.postcodeValidation(element);
                        } else {
                            $.each(postcodeElements, function (index, elem) {
                                self.postcodeValidation(elem);
                            });
                        }
                    }, delay);

                    // if (!formPopUpState.isVisible()) {
                    //     // Prevent shipping methods showing none available whilst we resolve
                    //     if (element.value()) {
                    //         shippingService.isLoading(true);
                    //     }
                    //     clearTimeout(self.validateAddressTimeout);
                    //     self.validateAddressTimeout = setTimeout(function () {
                    //         self.validateFields();
                    //     }, delay);
                    // }
                });
                observedElements.push(element);
            }
        })

        shippingRatesValidator.doElementBinding = wrapper.wrapSuper(shippingRatesValidator.doElementBinding, function (element, force, delay) {
            var observableFields = shippingRatesValidationRules.getObservableFields();

            if (element && (observableFields.indexOf(element.index) !== -1 || force)) {
                if (element.index !== postcodeElementName) {
                    this.bindHandler(element, delay);
                }
            }

            if (element.index === postcodeElementName) {
                this.bindHandler(element, delay);
                postcodeElements.push(element);
            }
        })

        shippingRatesValidator.collectObservedData = wrapper.wrapSuper(shippingRatesValidator.collectObservedData, function () {
            var observedValues = {};

            $.each(observedElements, function (index, field) {
                observedValues[field.dataScope] = field.value();
            });

            return observedValues;
        })

        return shippingRatesValidator;
    };
});
