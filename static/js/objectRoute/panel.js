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

pimcore.plugin.socialmedia.objectroute.panel = Class.create({

    initialize:function () {
        return this.getTabPanel();
    },

    getTabPanel:function () {

        if (!this.panel) {
            this.panel = new Ext.Panel({
                title: t('socialmedia_objectroutes'),
                iconCls: "socialmedia_icon_objectRoutes",
                border:false,
                layout:"fit",
                closable:true,
                items:[this.getRowEditor()]
            });
        }

        return this.panel;
    },

    getRowEditor:function () {


        var itemsPerPage = pimcore.helpers.grid.getDefaultPageSize();
        var url = '/plugin/SocialMedia/admin_settings/object-route?';

        this.store = pimcore.helpers.grid.buildDefaultStore(
            url,
            [
                "id",
                "class",
                "url"
            ],
            itemsPerPage
        );

        this.store.addListener('exception', function (proxy, response, operation) {
                Ext.MessageBox.show({
                    title: 'REMOTE EXCEPTION',
                    msg: operation.getError(),
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.Msg.OK
                });
            }
        );
        this.store.setAutoSync(true);

        this.pagingtoolbar = pimcore.helpers.grid.buildDefaultPagingToolbar(this.store);

        var typesColumns = [
            {
                header: t("class"),
                dataIndex: 'class',
                editable: false,
                flex : 1
            },
            {
                header: t("url"),
                dataIndex: 'url',
                flex : 1,
                editable : true,
                editor: {
                    type : 'text'
                }
            }
        ];

        this.cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
            clicksToEdit: 1
        });

        this.grid = Ext.create('Ext.grid.Panel', {
            frame:false,
            autoScroll:true,
            store:this.store,
            columnLines:true,
            trackMouseOver:true,
            bodyCls: "pimcore_editable_grid",
            stripeRows:true,
            columns : {
                items: typesColumns
            },
            sm:  Ext.create('Ext.selection.RowModel', {}),
            bbar:this.pagingtoolbar,
            plugins: [
                this.cellEditing
            ],
            viewConfig: {
                forceFit:true,
                xtype: 'patchedgridview'
            }
        });

        this.grid.on("afterrender", function() {
            this.setAutoScroll(true);
        });

        this.store.load();

        return this.grid;
    }
});
