/**
 * Created by Linh on 7/31/2020.
 */
define(
    [
        'jquery',
        'Magento_Checkout/js/view/payment/default',
        'mage/url'
    ],
    function ($, Component, url){
        'use strict';

        return Component.extend({
            redirectAfterPlaceOrder: false,

            defaults: {
                template: 'PL_Psigate/payment/psigate-htmlmessenger'
            },

            initialize: function() {
                this._super();
                self = this;
            },

            getCode: function() {
                return 'psigate_htmlmessenger';
            },

            getData: function() {
                var data = {
                    'method': this.getCode()
                };
                return data;
            },

            afterPlaceOrder: function () {
                window.location.replace(url.build('psigate/htmlmessenger/redirect'));
            }

        });
    }
);