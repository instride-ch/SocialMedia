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

pimcore.registerNS("pimcore.plugin.socialmedia");

pimcore.plugin.socialmedia = Class.create(pimcore.plugin.admin, {
    config : [],

    getClassName: function() {
        return "pimcore.plugin.socialmedia";
    },

    initialize: function() {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {

        var user = pimcore.globalmanager.get('user');

        if (user.isAllowed('plugins')) {
            Ext.Ajax.request({
                url: '/plugin/SocialMedia/admin_settings/get',
                success: function (response) {
                    pimcore.globalmanager.add("socialmedia_settings", Ext.decode(response.responseText));

                    Ext.Ajax.request({
                        url: '/plugin/SocialMedia/admin_settings/get-config',
                        method: 'GET',
                        success: function (result) {
                            var config = Ext.decode(result.responseText);

                            this.config = config;

                            var socialMedia = new Ext.Action({
                                text: t('socialmedia'),
                                iconCls: 'socialmedia_icon',
                                handler: this.openSocialMedia
                            });

                            layoutToolbar.settingsMenu.add(socialMedia);

                        }.bind(this)
                    });

                }.bind(this)
            });
        }
    },

    postOpenDocument : function(tab, type) {
        this.createShareButton(tab, 'document', type, tab.data.id);

        tab.tabbar.add(new pimcore.plugin.socialmedia.object.shares(tab, tab.data.id, 'document').getLayout());
    },

    postOpenObject : function(tab, type) {
        this.createShareButton(tab, 'object', type, tab.id);

        tab.tabbar.add(new pimcore.plugin.socialmedia.object.shares(tab, tab.id, 'object').getLayout());
    },

    createShareButton : function(tab, type, subtype, id) {
        var submenu = [];

        Ext.each(this.config.networks, function(network) {
            submenu.push({
                text: t('socialmedia_' + network),
                iconCls: "socialmedia_icon_" + network,
                handler : this.shareOnNetwork.bind(this, network, type, subtype, id, tab.data)
            });
        }.bind(this));

        tab.toolbar.insert(tab.toolbar.items.length,
            '-'
        );
        tab.toolbar.insert(tab.toolbar.items.length,
            {
                text: t('socialmedia_share'),
                scale: 'medium',
                iconCls: 'socialmedia_share',
                menu: submenu
            }
        );
    },

    shareOnNetwork : function(network, type, subtype, id, data) {
        Ext.Ajax.request({
            url: '/plugin/SocialMedia/admin_share/get-url',
            method: 'GET',
            params : {
                type : type,
                id : id,
                network : network
            },
            success: function (result) {
                var jsonData = Ext.decode(result.responseText);

                var networkCls = new pimcore.plugin.socialmedia.networks[network]();
                networkCls.share(id, type, subtype, data, jsonData.url);
            }.bind(this)
        });
    },

    openSocialMedia : function() {
        try {
            pimcore.globalmanager.get('socialmedia').activate();
        }
        catch (e) {
            pimcore.globalmanager.add('socialmedia', new pimcore.plugin.socialmedia.socialMedia());
        }
    }
});

var socialmediaPlugin = new pimcore.plugin.socialmedia();

