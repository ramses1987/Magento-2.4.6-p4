/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/url-builder',
    'mage/storage',
    'Magento_Checkout/js/model/error-processor',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/action/get-totals',
    'Magento_Checkout/js/model/totals',
    'underscore',
    'Magento_Checkout/js/model/payment/place-order-hooks'
], function (quote, urlBuilder, storage, errorProcessor, customer, getTotalsAction, totals, _, hooks) {
    'use strict';

    /**
     * Filter template data.
     *
     * @param {Object|Array} data
     */
    var filterTemplateData = function (data) {
        return _.each(data, function (value, key, list) {
            if (_.isArray(value) || _.isObject(value)) {
                list[key] = filterTemplateData(value);
            }

            if (key === '__disableTmpl' || key === 'title') {
                delete list[key];
            }
        });
    };

    return function (messageContainer, paymentData, skipBilling) {
        var serviceUrl,
            payload,
            headers = {};

        paymentData = filterTemplateData(paymentData);
        skipBilling = skipBilling || false;
        payload = {
            cartId: quote.getQuoteId(),
            paymentMethod: paymentData
        };

        /**
         * Checkout for guest and registered customer.
         */
        if (!customer.isLoggedIn()) {
            serviceUrl = urlBuilder.createUrl('/guest-carts/:cartId/set-payment-information', {
                cartId: quote.getQuoteId()
            });
            payload.email = quote.guestEmail;
            if (quote.guestEmail === null) {
                payload.email = '';
            }
        } else {
            serviceUrl = urlBuilder.createUrl('/carts/mine/set-payment-information', {});
        }

        if (skipBilling === false) {
            payload.billingAddress = quote.billingAddress();
        }

        totals.isLoading(true);

        _.each(hooks.requestModifiers, function (modifier) {
            modifier(headers, payload);
        });

        return storage.post(
            serviceUrl, JSON.stringify(payload), true, 'application/json', headers
        ).fail(function (response) {
                totals.isLoading(false);
                errorProcessor.process(response, messageContainer);
            }
        ).always(
            function () {
                _.each(hooks.afterRequestListeners, function (listener) {
                    listener();
                });
            }
        );
    };
});
