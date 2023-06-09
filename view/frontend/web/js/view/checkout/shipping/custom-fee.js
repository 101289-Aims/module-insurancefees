define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils',
    'Magento_Ui/js/modal/modal'

], function ($, ko, Component, quote, priceUtils,modal) {
    'use strict';
    var fee_label = window.checkoutConfig.fee_label;
    var custom_fee_amount = window.checkoutConfig.custom_fee_amount_per;

    return Component.extend({

        defaults: {
            template: 'Aimsinfosoft_InsuranceFees/checkout/shipping/custom-fee',
            allowGiftFee: ko.observable(true),
            canVisibleCustomfeeBlock: window.checkoutConfig.show_hide_customfee_shipblock,
            getFormattedPrice: ko.observable(custom_fee_amount+'% '),
            getFeeLabel:ko.observable(fee_label)

        }
        //, initialize: function () {
        //     this._super();
        //     var self = this;
        //     // $(document).on('change', 'input[name="gift_fee"]', function () {
        //     //     // if($(this).prop('checked') == true) {
        //     //     //     $(".gift-fee-error").hide();
        //     //     // }
        //     // });
        // }
    });
});