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
 * @copyright  Copyright (c) 2016-2017 W-Vision (http://www.w-vision.ch)
 * @license    https://github.com/w-vision/SocialMedia/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace SocialMedia\Model\ObjectRoute\Listing;

use Pimcore;
use SocialMedia\Model;

/**
 * Class Dao
 * @package SocialMedia\Model\Definition\Listing
 */
class Dao extends Pimcore\Model\Dao\PhpArrayTable
{
    /**
     * configure.
     */
    public function configure()
    {
        parent::configure();
        $this->setFile('socialmedia_objectroute');
    }

    /**
     * Loads a list of Definitions for the specicifies parameters, returns an array of Definitions elements.
     *
     * @return array
     */
    public function load()
    {
        $routesData = $this->db->fetchAll($this->model->getFilter(), $this->model->getOrder());

        $routes = array();
        foreach ($routesData as $routeData) {
            $routes[] = Model\ObjectRoute::getById($routeData['id']);
        }

        $this->model->setObjectRoutes($routes);

        return $routes;
    }

    /**
     * get total count.
     *
     * @return int
     */
    public function getTotalCount()
    {
        $data = $this->db->fetchAll($this->model->getFilter(), $this->model->getOrder());
        $amount = count($data);

        return $amount;
    }
}
