/**
* Ifthenpay_Payment module dependency
*
* @category    Gateway Payment
* @package     Ifthenpay_Payment
* @author      Ifthenpay
* @copyright   Ifthenpay (http://www.ifthenpay.com)
* @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
define([
    'Magento_Checkout/js/view/payment/default',
    'jquery',
    'domReady!'
], function (Component, $, documentReady) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Ifthenpay_Payment/payment/mbwayForm',
        },
        getLogoUrl: function () {
            return window.checkoutConfig.payment.mbway.logoUrl;
        },
        getShowPaymentIcon: function () {
            return window.checkoutConfig.payment.mbway.showPaymentIcon ? window.checkoutConfig.payment.mbway.showPaymentIcon : false;
        },
        getData: function () {
            return {
                'method': this.item.method,
                'additional_data': {
                    'mbwayPhoneNumber': $('#mbwayPhoneNumber').val()
                }
            };
        }
    });
});