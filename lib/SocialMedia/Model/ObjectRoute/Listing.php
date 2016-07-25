<?php
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

namespace SocialMedia\Model\ObjectRoute;

use Pimcore\Model;
use SocialMedia\Model\ObjectRoute;

/**
 * Class Listing
 * @package SocialMedia\Model\Definition
 */
class Listing extends Model\Listing\JsonListing
{
    /**
     * Contains the results of the list. They are all an instance of ObjectRoute.
     *
     * @var array
     */
    public $objectRoutes = null;

    /**
     * Get Routes.
     *
     * @return ObjectRoute[]
     */
    public function getObjectRoutes()
    {
        if (is_null($this->objectRoutes)) {
            $this->load();
        }

        return $this->objectRoutes;
    }

    /**
     * Set Routes.
     *
     * @param array $objectRoutes
     */
    public function setObjectRoutes($objectRoutes)
    {
        $this->objectRoutes = $objectRoutes;
    }
}
