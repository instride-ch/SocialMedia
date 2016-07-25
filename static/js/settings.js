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

pimcore.registerNS('pimcore.plugin.socialmedia.settings');
pimcore.plugin.socialmedia.settings = Class.create({

    initialize: function () {
        this.getPanel();
        this.getData();

        return this.panel;
    },

    getData: function () {
        Ext.Ajax.request({
            url: '/plugin/SocialMedia/admin_settings/get',
            success: function (response) {

                this.data = Ext.decode(response.responseText);

                this.getItems();

            }.bind(this)
        });
    },

    getPanel: function () {
        if (!this.panel) {
            this.panel = Ext.create('Ext.form.Panel', {
                title: t('socialmedia_settings'),
                iconCls: 'socialmedia_icon_settings',
                border: false,
                autoScroll: true,
                forceLayout: true,
                defaults: {
                    forceLayout: true
                },
                buttons: [
                    {
                        text: 'Save',
                        handler: this.save.bind(this),
                        iconCls: 'pimcore_icon_apply'
                    }
                ]
            });

            pimcore.layout.refresh();
        }

        return this.panel;
    },

    getItems : function() {
        var networks = Object.keys(pimcore.plugin.socialmedia.networks);

        Ext.each(networks, function(network) {
            var networkPanel = new pimcore.plugin.socialmedia.networks[network]();

            this.panel.add(networkPanel.getSettings(this.data));
        }.bind(this));
    },

    save: function () {
        var data = this.panel.getForm().getFieldValues();

        Ext.Ajax.request({
            url: '/plugin/SocialMedia/admin_settings/set',
            method: 'post',
            params: {
                data: Ext.encode(data)
            },
            success: function (response) {
                try {
                    var res = Ext.decode(response.responseText);
                    if (res.success) {
                        pimcore.helpers.showNotification(t('success'), t('settings_save_success'), 'success');

                    } else {
                        pimcore.helpers.showNotification(t('error'), t('settings_save_error'),
                            'error', t(res.message));
                    }
                } catch (e) {
                    pimcore.helpers.showNotification(t('error'), t('settings_save_error'), 'error');
                }
            }
        });
    }
});
