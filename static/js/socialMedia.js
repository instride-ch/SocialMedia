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
pimcore.registerNS('pimcore.plugin.socialmedia.objectroute.panel');

pimcore.plugin.socialmedia.socialMedia = Class.create({

    initialize:function () {
        this.getTabPanel();
    },


    activate:function () {
        var tabPanel = Ext.getCmp("pimcore_panel_tabs");
        tabPanel.setActiveItem("socialmedia");
    },

    getTabPanel:function () {

        if (!this.panel) {
            this.panel = new Ext.tab.Panel({
                id:"socialmedia",
                title: t('socialmedia'),
                iconCls: "socialmedia_icon",
                border:false,
                layout:"fit",
                closable:true,
                items:this.getPanels()
            });

            var tabPanel = Ext.getCmp("pimcore_panel_tabs");
            tabPanel.add(this.panel);
            tabPanel.setActiveItem("socialmedia");

            this.panel.on("destroy", function () {
                pimcore.globalmanager.remove("socialmedia");
            }.bind(this));

            pimcore.layout.refresh();
        }

        return this.panel;
    },

    getPanels : function() {
        var items = [
            new pimcore.plugin.socialmedia.settings().panel,
            new pimcore.plugin.socialmedia.objectroute.panel().panel
        ];

        return items;
    }
});
