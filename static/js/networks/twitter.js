/**
 * Social Media.
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2016-2017 W-Vision (http://www.w-vision.ch)
 * @license    https://github.com/w-vision/SocialMedia/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */
pimcore.registerNS('pimcore.plugin.socialmedia.networks.twitter');

pimcore.plugin.socialmedia.networks.twitter = Class.create(pimcore.plugin.socialmedia.abstractNetwork, {
    share : function($super, id, type, subtype, data, url) {
        $super(id, type, subtype, data, url);

        this.twInit();
    },

    twInit : function() {
        var urlTW = "https://twitter.com/intent/tweet?text=&url=" + urlencode(this.url);
        var winTW = window.open(urlTW,'','toolbar=0, status=0, width=650, height=360');
    },

    getSettings : function($super, data) {
        $super(data);
    }
});