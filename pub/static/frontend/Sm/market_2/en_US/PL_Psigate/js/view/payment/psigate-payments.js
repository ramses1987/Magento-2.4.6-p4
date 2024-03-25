define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(

            {
                type: 'psigate_htmlmessenger',
                component: 'PL_Psigate/js/view/payment/method-renderer/psigate-htmlmessenger'
            },
            {
                type: 'psigate_xmlmessenger',
                component: 'PL_Psigate/js/view/payment/method-renderer/psigate-xmlmessenger'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);