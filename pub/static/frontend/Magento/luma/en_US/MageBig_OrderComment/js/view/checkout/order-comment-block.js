define(
        [
            'jquery',
            'ko',
            'uiComponent'
        ],
        function ($, ko, Component) {
            'use strict';
            return Component.extend({
                defaults: {
                    template: 'MageBig_OrderComment/checkout/order-comment-block'
                },
                isEnabled: window.checkoutConfig.enabled_comment,
                initialize: function () {
                    var self = this;
                     $(document).on("click", ".input-text.magebig_order_comment", function () {
                        var activePay = $(".payment-method._active").find("input.radio").val();
                            $(this).attr('id',activePay);
                        });
                return this._super();
                },
            });
            
        }
);
