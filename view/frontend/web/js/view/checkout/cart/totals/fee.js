define([
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils',
    'Magento_Checkout/js/model/totals'

], function (ko, Component, quote, priceUtils, totals) {
    'use strict';
    var show_hide_customfee_blockConfig = window.checkoutConfig.show_hide_customfee_block;
    var fee_label = window.checkoutConfig.fee_label+' ('+window.checkoutConfig.custom_fee_amount_per+'%)';
    var custom_fee_amount = window.checkoutConfig.custom_fee_amount;
    var custom_in_fee_amount = window.checkoutConfig.custom_fee_amount_inc;

    return Component.extend({
        totals: quote.getTotals(),
        canVisibleCustomFeeBlock: show_hide_customfee_blockConfig,
        getFormattedPrice: ko.observable(priceUtils.formatPrice(custom_fee_amount, quote.getPriceFormat())),
        getFeeLabel:ko.observable(fee_label),
        getInFeeLabel:false,
        getExFeeLabel:false,
        
        isDisplayed: function () {
            return this.getValue() != 0;
        },
        isDisplayBoth: function () {
            return false;

        },
        isDisplayBoth: function () {
            return false;
        },
        displayExclTax: function () {
            return false;
        },
        displayInclTax: function () {
            return false;
        },
        isTaxEnabled: function () {
            return false;
        },
        getValue: function() {
            var price = 0;
            if (this.totals() && totals.getSegment('fee')) {
                price = totals.getSegment('fee').value;
            }
            return price;
        },
        getInFormattedPrice: function() {
            var price = 0;
            if (this.totals() && totals.getSegment('fee')) {
                price = totals.getSegment('fee').value;
            }

            return priceUtils.formatPrice(price, quote.getPriceFormat());
        }
    });
});