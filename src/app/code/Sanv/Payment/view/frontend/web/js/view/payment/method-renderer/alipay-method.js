/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/model/payment/additional-validators',
        'Sanv_Payment/js/action/redirect-on-success'
    ],
    function (Component,additionalValidators,redirectUrl) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Sanv_Payment/payment/alipay'
            },

            /** Returns send check to info */
            getMailingAddress: function() {
                return window.checkoutConfig.payment.checkmo.mailingAddress;
            },

            placeOrder: function (data, event) {
                var self = this;
                if (event) {
                    event.preventDefault();
                }
                if (this.validate() && additionalValidators.validate()) {
                    this.isPlaceOrderActionAllowed(false);

                    this.getPlaceOrderDeferredObject()
                        .fail(
                            function () {
                                self.isPlaceOrderActionAllowed(true);
                            }
                        ).done(
                        function () {
                            self.afterPlaceOrder();
                            if (self.redirectAfterPlaceOrder) {
                                redirectUrl.execute(data);
                            }
                        }
                    );

                    return true;
                }

                return false;
            }
           
        });
    }
);
