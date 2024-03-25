define([
    'ko',
    'jquery',
    'uiComponent',
    'Magento_Ui/js/lib/view/utils/dom-observer',
    'Magento_Checkout/js/model/totals',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/action/select-shipping-address',
    'Magento_Checkout/js/action/set-shipping-information',
    'Magento_Checkout/js/model/checkout-data-resolver',
    'Magento_Checkout/js/checkout-data',
    'uiRegistry',
    'Magento_Checkout/js/model/address-converter',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/shipping-service',
    'MageBig_FastCheckout/js/model/agreement/agreement-validator',
    'Magento_Checkout/js/model/payment-service',
    'Magento_Customer/js/model/address-list',
    'mage/translate',
    'Magento_Checkout/js/model/shipping-address/form-popup-state',
    'Magento_Checkout/js/model/full-screen-loader'
], function (
    ko,
    $,
    Component,
    dom,
    totals,
    quote,
    selectShippingAddress,
    setShippingInformationAction,
    checkoutDataResolver,
    checkoutData,
    registry,
    addressConverter,
    customer,
    shippingService,
    agreementValidator,
    paymentService,
    addressList,
    $t,
    formPopUpState
) {
    'use strict';

    let timer,
        checkoutConfig = window.checkoutConfig;

    return Component.extend({
        defaults: {
            template: 'MageBig_FastCheckout/view/place-order-btn',
            shippingUpdateFields: ['country_id', 'region_id', 'city', 'postcode'],
            updateTimeout: 0,
            updateDelay: 100,
        },

        isLoading: totals.isLoading,

        initialize: function () {
            let self = this;
            this._super();

            let fields = ['input', 'select', 'textarea'],
                shippingForm = '#shipping-new-address-form';

            fields.forEach(function (item) {
                dom.get(item, function (elem) {
                    let $field = $(elem).parents('.field').first(),
                        $label = $field.find('label'),
                        $item = $label.find(item),
                        $shippingForm = $(elem).parents(shippingForm);

                    if ($item.length) {
                        $item.insertBefore($label);
                    }

                    if ($label.length && !$item.length) {
                        $label.insertAfter($(elem));
                    }

                    if ($shippingForm.length) {
                        $(elem).on('change', function () {
                            if (!formPopUpState.isVisible() && $(elem).attr('name') !== 'fullName' && $(elem).val()) {
                                let checkValue = self.checkShippingFieldValue($shippingForm);

                                if (checkValue) {
                                    window.checkoutConfig.isUpdatedShipping = false;
                                    self.updateAddress();
                                }
                            }
                        })

                        // Fix show/hide issue on region field
                        if ($(elem).attr('name') === 'region' && $shippingForm.parents('#co-shipping-form').length) {
                            let regionField = $(elem).parents('[name="shippingAddress.region"]'),
                                regionIdField = $shippingForm.find('[name="shippingAddress.region_id"]');

                            if (regionIdField.is(':visible')) {
                                regionField.hide();
                            } else {
                                regionField.show();
                            }
                        }
                    }

                    switch (item) {
                        case 'input':
                            self.initInputData(elem);
                            break;
                        case 'select':
                            self.initSelectData(elem, $label);
                            break;
                        default:
                            $(elem).attr('placeholder', ' ');
                    }
                });
            });

            dom.get('.payment-method', function (elem) {
                $(elem).on('click', function (event) {
                    event.stopPropagation();
                    let $input = $(this).find('input[name="payment[method]"]');

                    if (!$input.prop("checked")) {
                        $input.trigger('click');
                    }
                })
            });

            dom.get('.shipping-address-item', function (elem) {
                $(elem).on('click', function (event) {
                    event.stopPropagation();
                    let $btn = $(this).find('.action-select-shipping-item');

                    if ($(elem).hasClass('not-selected-item')) {
                        $btn.trigger('click');
                    }
                })
            });

            dom.get('.payment-icon', function (elem) {
                $(elem).parents('.payment-method').addClass('has-payment-icon');
            });

            dom.get('.same-shipping', function (elem) {
                $(elem).on('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    let sHeight = $(this).outerHeight() + 'px';
                    $(this).parents('.checkout-billing-address').find(".fieldset:not(#customer-email-fieldset)").css('min-height', sHeight);

                    setTimeout(function () {
                        $(elem).find('.action-edit-address').trigger('click');
                    }, 100)
                })
            });

            dom.get('.checkout-billing-address', function (elem) {
                dom.get('#shipping', function (co) {
                    if ($(co).is(':visible')) {
                        $(elem).insertAfter($(co)).wrap('<li id="billing-info" class="billing-same"></li>');
                    } else {
                        $(elem).insertBefore($('#payment')).wrap('<li id="billing-info" class="billing-heading"></li>');
                    }
                });

                if (quote.isVirtual() && !customer.isLoggedIn()) {
                    setTimeout(function () {
                        $('#payment .form-login').insertAfter($('#billing-info .title'));
                    }, 500)
                }
            });

            dom.get('#co-shipping-method-form', function (elem) {
                $(elem).on('click', '[data-bind*=selectShippingMethod]', function () {
                    window.checkoutConfig.isUpdatedShipping = true;
                })
            });
        },

        checkShippingFieldValue: function (form) {
            let self = this,
                result = true;

            form.find('[aria-required=true]').each(function () {
                let inputName = $(this).attr('name');

                // if (self.shippingUpdateFields.indexOf(inputName) > -1 && !$(this).val() && $(this).is(':visible')) {
                if (!$(this).val() && $(this).is(':visible')) {
                    result = false;
                    return result;
                }
            })

            return result;
        },

        initPlaceOrder: function () {
            let self = this,
                $placeWrap = $('.place-order-wrap');

            $placeWrap.on('click', 'button', function () {
                let attr = $(this).attr('data-btn'),
                    $active = $('.payment-method._active');

                let isValid = self.validateShippingInformation();

                self.hideMessage();

                if (isValid && !quote.isVirtual()) {
                    if (window.checkoutConfig.isUpdatedShipping) {
                        $active.find('button[data-btn=' + attr + ']').trigger('click');
                    } else {
                        registry.async('checkoutProvider')(function (checkoutProvider) {
                            let shippingAddressData = checkoutData.getShippingAddressFromData();
                            if (shippingAddressData) {
                                checkoutProvider.set(
                                    'shippingAddress',
                                    $.extend(true, {}, checkoutProvider.get('shippingAddress'), shippingAddressData)
                                );
                            }
                        });
                        setShippingInformationAction().done(
                            function () {
                                window.checkoutConfig.isUpdatedShipping = true;
                                $active.find('button[data-btn=' + attr + ']').trigger('click');
                            }
                        );
                    }
                } else if (isValid && quote.isVirtual()) {
                    $active.find('button[data-btn=' + attr + ']').trigger('click');
                }
            });
        },

        updateAddress: function () {
            let self = this;

            clearTimeout(self.updateTimeout);
            self.updateTimeout = setTimeout(function () {
                let addressFlat, address, billingComponent;

                addressFlat = registry.get('checkoutProvider').shippingAddress;
                address = addressConverter.formAddressDataToQuoteAddress(addressFlat);
                billingComponent = registry.get('checkout.steps.billing-step.payment.afterMethods.billing-address-form');

                if (billingComponent.isAddressSameAsShipping()) {
                    quote.billingAddress(address);
                    billingComponent.isAddressSameAsShipping(true);
                }

                selectShippingAddress(address);
            }, self.updateDelay)
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
        },

        /**
         * @return {Boolean}
         */
        validateShippingInformation: function () {
            let loginFormSelector = 'form[data-role=email-with-possible-login]',
                emailValidationResult = customer.isLoggedIn();

            if (!customer.isLoggedIn()) {
                $(loginFormSelector).validation();
                emailValidationResult = Boolean($(loginFormSelector + ' input[name=username]').valid());
            }

            if (!emailValidationResult) {
                $(loginFormSelector + ' input[name=username]').focus();

                return false;
            }

            let shippingComponent = registry.get('checkout.steps.shipping-step.shippingAddress'),
                billingComponent = registry.get('checkout.steps.billing-step.payment.afterMethods.billing-address-form'),
                isValid = true;

            if (!quote.isVirtual() && addressList().length === 0) {
                let shippingField = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset');

                isValid = this.validateFormFields(shippingField);

                if (!isValid) {
                    shippingComponent.focusInvalid();

                    return false;
                }
            }

            if (!billingComponent.isAddressSameAsShipping() && !billingComponent.isAddressDetailsVisible() && billingComponent.isAddressFormVisible()) {
                let billingField = registry.get('checkout.steps.billing-step.payment.afterMethods.billing-address-form.form-fields');

                isValid = this.validateFormFields(billingField);

                if (!isValid) {
                    billingComponent.focusInvalid();

                    return false;
                }
            }

            if (!billingComponent.isAddressSameAsShipping() && !billingComponent.isAddressDetailsVisible()) {
                let messageContainer = registry.get('checkout.errors').messageContainer;

                this.scrollToForm($('.checkout-billing-address'));
                messageContainer.addErrorMessage({
                    message: $t('Please update your billing address.')
                });

                return false;
            }

            isValid = this.validateShippingMethod();

            if (!isValid) {
                this.scrollToForm($('.checkout-shipping-method'));

                return false;
            }

            isValid = this.validatePaymentMethod();

            if (!isValid) {
                this.scrollToForm($('#co-payment-form'));

                return false;
            }

            isValid = agreementValidator.validate(false);

            if (!isValid) {
                this.scrollToForm($('.checkout-agreements'));

                return false;
            }

            if (isValid && !quote.isVirtual()) {
                isValid = shippingComponent.validateShippingInformation();
            }

            return isValid;
        },

        validateShippingMethod: function () {
            if (quote.isVirtual()) {
                return true;
            }

            let messageContainer = registry.get('checkout.errors').messageContainer;

            if (!quote.shippingMethod()) {
                this.scrollToForm($('#opc-shipping_method'));
                messageContainer.addErrorMessage({
                    message: $t('Please select a shipping method.')
                });

                return false;
            }

            return true;
        },

        validatePaymentMethod: function () {
            let messageContainer = registry.get('checkout.errors').messageContainer;

            if (!quote.paymentMethod()) {
                this.scrollToForm($('.opc-payment'));
                messageContainer.addErrorMessage({
                    message: $t('Please select a payment method.')
                });

                return false;
            }

            return true;
        },

        scrollToForm: function (elm) {
            let $win = $(window),
                windowHeight = $win.innerHeight();

            if ($win.scrollTop() > elm.offset().top) {
                $('html, body').stop().animate({
                    scrollTop: elm.offset().top - 130
                });
            }
        },

        hideMessage: function () {
            if (timer) {
                clearTimeout(timer);
            }

            timer = setTimeout(function () {
                $('div.mage-error, .field-error').fadeOut();
            }, 5000)
        },

        setFullName: function (elem) {
            let $address = $(elem).parents('.address'),
                defaultFirst = $address.find('[name=firstname]'),
                defaultLast = $address.find('[name=lastname]'),
                name;

            if (!$(elem).val() && (defaultFirst.val() || defaultLast.val())) {
                if (checkoutConfig.fullName === 2) {
                    name = defaultFirst.val() + ' ' + defaultLast.val();
                } else {
                    name = defaultLast.val() + ' ' + defaultFirst.val();
                }

                $(elem).val(name).trigger('change');
            }

            $(elem).on('focusout', function () {
                let fullName = $(this).val().trim(),
                    nameData = fullName.split(' '),
                    firstName, lastName;

                if (checkoutConfig.fullName === 2) {
                    firstName = nameData.shift();
                } else {
                    firstName = nameData.pop();
                }

                lastName = nameData.join(' ').trim();

                if (fullName) {
                    lastName = lastName ? lastName : $t('Guest');
                }

                $address.find('[name=firstname]').val(firstName).trigger('change');
                $address.find('[name=lastname]').val(lastName).trigger('change');
            });
        },

        initInputData: function (elem) {
            let self = this;

            $(elem).attr('placeholder', ' ');

            if ($(elem).attr('type') === 'radio') {
                if (!$(elem).attr('id')) {
                    let name = $(elem).attr('name') + Math.floor(Math.random() * 1000);
                    $(elem).attr('id', name);
                }

                if (!$(elem).parent('.field').length) {
                    $(elem).wrap('<div class="field"></div>');
                }

                $(elem).parent('.field').append('<label for="' + $(elem).attr('id') + '"> </label>');
            }

            if ($(elem).attr('name') === 'payment[method]') {
                $(elem).parents('.payment-method').addClass('payment-' + $(elem)[0].id);
            }

            if ($(elem).attr('name') === 'fullName') {
                self.setFullName(elem);
            }
        },

        initSelectData: function (elem, $label) {
            let self = this;

            if ($(elem).attr('name') === 'region_id') {
                $(elem).find("option[value='']").text('');
            }

            if (!$(elem).val()) {
                $(elem).addClass('no-value').find('option:selected').text();
                $label.addClass('no-value');
            } else {
                $(elem).removeClass('no-value');
                $label.removeClass('no-value');
            }

            $(elem).on('change', function () {
                if (!$(elem).val()) {
                    $(elem).addClass('no-value').find('option:selected').text();
                    $label.addClass('no-value');
                } else {
                    $(elem).removeClass('no-value');
                    $label.removeClass('no-value');
                }
            });
        }
    });
});
