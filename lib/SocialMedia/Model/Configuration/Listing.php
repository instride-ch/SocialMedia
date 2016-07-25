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

namespace SocialMedia\Model\Configuration;

use SocialMedia\Model\Configuration;
use Pimcore\Model;

/**
 * Class Listing
 * @package SocialMedia\Model\Configuration
 */
class Listing extends Model\Listing\JsonListing
{

    /**
     * Contains the results of the list. They are all an instance of Configuration
     *
     * @var array
     */
    public $configurations = null;

    /**
     * @return Configuration[]
     */
    public function getConfigurations()
    {
        if (is_null($this->configurations))
        {
            $this->load();
        }

        return $this->configurations;
    }

    /**
     * @param array $configurations
     * @return void
     */
    public function setConfigurations($configurations)
    {
        $this->configurations = $configurations;
    }
}
