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

pimcore.registerNS("pimcore.plugin.socialmedia.object.shares");
pimcore.plugin.socialmedia.object.shares = Class.create({

    initialize: function(element, id, type) {
        this.element = element;
        this.id = id;
        this.type = type;
        this.grid = false;
        this.store = false;
    },

    getLayout: function() {
        this.store = Ext.create('Ext.data.JsonStore', {
            fields : [
                'id',
                'elementId',
                'elementType',
                'network',
                'clicks'
            ],
            proxy: {
                type: 'ajax',
                url: '/plugin/SocialMedia/admin_share/get-shares-for-element',
                reader: {
                    type: 'json',
                    rootProperty : 'data'
                },
                extraParams : {
                    id : this.id,
                    type : this.type
                }
            }
        });

        this.store.load();

        this.grid = Ext.create('Ext.grid.Panel', {
            frame:false,
            autoScroll:true,
            store:this.store,
            columnLines:true,
            trackMouseOver:true,
            stripeRows:true,
            columns : {
                items: [
                    {
                        header: t("socialmedia_network"),
                        dataIndex: 'network',
                        flex : 1,
                        renderer : function(value) {
                            return t('socialmedia_' + value);
                        }
                    },
                    {
                        header: t("socialmedia_clicks"),
                        dataIndex: 'clicks',
                        flex : 1
                    }
                ]
            }
        });

        if (this.layout == null) {
            this.layout = new Ext.Panel({
                title: t('socialmedia_shares_click'),
                border: false,
                scrollable: "y",
                iconCls: "socialmedia_share",
                items : [
                    this.grid
                ]
            });
        }
        return this.layout;
    }
});