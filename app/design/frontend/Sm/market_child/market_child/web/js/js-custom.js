require([
	"jquery"
],
function($){
    $.noConflict();
    $(document).ready(function() {
        var target_overlay = document.querySelector('#checkout');
        var observerInit = new MutationObserver(function(mutations) {
            var target_shipping = document.querySelector('#co-shipping-form');
            if(target_shipping)
            {
                $('#co-shipping-form select[name="country_id"]')
                .find('option')
                .remove()
                .end()
                .append('<option value="cu">Cuba</option>')
                .val('cu');
                
            }
            
            var storeCode = window.checkoutConfig.storeCode;
            var websiteCode = window.checkoutConfig.websiteCode;
            
            if(target_shipping)
            {
                if(websiteCode == "base" ){
                $('#co-shipping-form select[name="region_id"]')
                .find('option')
                .remove()
                .end()
                .append('<option value="1122">Habana</option>')
                .val('1122');
                }
     
            }
            
        });

        if(target_overlay !== null)
        {
            observerInit.observe(target_overlay, {attributes: true, childList: true, characterData: true});
        }

        var target_form = document.querySelector('#form-validate');
        if(target_form)
        {
            var str = document.location.href;
            if(str.includes('/customer-create'))
            {
                $('select[name="country_id"]')
                .find('option')
                .remove()
                .end()
                .append('<option value="cu">Cuba</option>')
                .val('cu');
            }
        }
    });
});