define(['ko', 'uiRegistry'], function (ko, registry) {
    'use strict';

    var mixin = {
        defaults: {
            detailsTemplate: 'MageBig_FastCheckout/billing-address/details'
        },

        initialize: function () {
            this._super();

            // this.isAddressSameAsShipping(true);
            // this.isAddressDetailsVisible(true);
        },

        updateAddress: function () {
            let billingComponent = registry.get('checkout.steps.billing-step.payment.afterMethods.billing-address-form'),
                isValid = true;

            if (billingComponent.isAddressFormVisible()) {
                let billingField = registry.get('checkout.steps.billing-step.payment.afterMethods.billing-address-form.form-fields');

                isValid = this.validateFormFields(billingField);

                if (!isValid) {
                    billingComponent.focusInvalid();
                } else {
                    this._super();
                }
            } else {
                this._super();
            }
        },

        validateFormFields: function (fields) {
            let i = 0, j = 0, isValid = true;

            _.each(fields._elems, function (data) {
                if (data && typeof data === 'object') {
                    if (data.validation !== undefined) {
                        if (!data.validate(false).valid) {
                            if (i === 0) {
                                isValid = false;
                            }
                            i++;
                        }
                    } else if (data._elems.length) {
                        _.each(data._elems, function (item) {
                            if (item.validation !== undefined && !item.validate(false).valid) {
                                if (j === 0) {
                                    isValid = false;
                                }
                                j++;
                            }
                        })
                    }
                }
            })

            return isValid;
        }
    };

    return function (target) {
        if (!window.checkoutConfig.fastCheckout.isEnable) {
            return target;
        }

        return target.extend(mixin);
    };
});
