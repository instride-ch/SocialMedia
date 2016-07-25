/**
 * Social Media.
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2016 W-Vision (http://www.w-vision.ch)
 * @license    https://github.com/w-vision/SocialMedia/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */
pimcore.registerNS('pimcore.plugin.socialmedia.networks.facebook');

pimcore.plugin.socialmedia.networks.facebook = Class.create(pimcore.plugin.socialmedia.abstractNetwork, {
    share : function($super, id, type, subtype, data, url) {
        $super(id, type, subtype, data, url);

        var settings = pimcore.globalmanager.get("socialmedia_settings");

        window.fbAsyncInit = function() {
            FB.init({
                appId      : settings.values['facebookApplication'],
                xfbml      : true,
                version    : 'v2.6'
            });

            this.fbInit();
        }.bind(this);

        if(typeof FB === "undefined") {
            (function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {
                    return;
                }
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        }
        else {
            this.fbInit();
        }
    },

    fbInit : function() {
        FB.ui({
            method: 'share',
            href: this.url
        }, function(response) {
            if (response && !response.error_message) {
                pimcore.helpers.showNotification(t('success'), t('socialmedia_share_success'), 'success');
            } else {
                pimcore.helpers.showNotification(t('error'), response.error_message, 'error');
            }
        });
    },

    getSettings : function($super, data) {
        $super(data);

        return {
            xtype: 'fieldset',
            title: t('socialmedia_facebook'),
            collapsible: true,
            autoHeight: true,
            labelWidth: 250,
            defaultType: 'textfield',
            defaults: {width: 600},
            items: [
                {
                    fieldLabel: t('socialmedia_facebook_application'),
                    xtype: 'textfield',
                    name: 'facebookApplication',
                    value: this.getValue('facebookApplication')
                }
            ]
        };
    }
});