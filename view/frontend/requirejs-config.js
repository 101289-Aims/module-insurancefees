var config = {
	map: {
       "*": {
           'Magento_Checkout/js/view/shipping': 'Aimsinfosoft_InsuranceFees/js/view/shipping',
           'Magento_Checkout/template/shipping-address/shipping-method-list' : 'Aimsinfosoft_InsuranceFees/template/shipping-address/shipping-method-list',
           'Magento_Checkout/js/model/shipping-save-processor/default' : 'Aimsinfosoft_InsuranceFees/js/model/shipping-save-processor/default'
       },
  	},
    config: {
        mixins: {
            'Magento_Checkout/js/model/shipping-save-processor/payload-extender': {
            'Aimsinfosoft_InsuranceFees/js/model/shipping-save-processor/payload-extender-mixin': true
            }
        }
    }
};