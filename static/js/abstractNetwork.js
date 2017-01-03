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
pimcore.registerNS('pimcore.plugin.socialmedia.networks.abstractNetwork');

pimcore.plugin.socialmedia.abstractNetwork = Class.create({
    share : function(id, type, subtype, data, url) {
        this.id = id;
        this.type = type;
        this.subtype = subtype;
        this.data = data;
        this.url = url;
    },

    getSettings : function(data) {
        this.config = data;
    },

    getValue: function (key) {
        var current = null;

        if (this.config.values.hasOwnProperty(key)) {
            current = this.config.values[key];
        }

        if (typeof current != 'object' && typeof current != 'array' && typeof current != 'function') {
            return current;
        }

        return '';
    }
});