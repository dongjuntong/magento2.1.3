/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'mage/url',
    ],
    function (url) {
        'use strict';

        var successPageUrl=window.checkoutConfig.defaultSuccessPageUrl;

        return {
            redirectUrl: successPageUrl,
            /**
             * Provide redirect to page
             */
            execute: function (data) {
                if(data.index=='alipay') {
                    this.redirectUrl=window.checkoutConfig.alipaySuccessPageUrl;
                }
                window.location.replace(url.build(this.redirectUrl));
            }
        };
    }
);
