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

namespace SocialMedia\Model\Configuration\Listing;

use Pimcore;
use SocialMedia\Model;

/**
 * Class Dao
 * @package SocailMedia\Model\Configuration\Listing
 */
class Dao extends Pimcore\Model\Dao\PhpArrayTable
{

    public function configure()
    {
        parent::configure();
        $this->setFile('socialmedia_configurations');
    }

    /**
     * Loads a list of Configurations for the specicifies parameters, returns an array of Configuration elements
     *
     * @return array
     */
    public function load()
    {
        $routesData = $this->db->fetchAll($this->model->getFilter(), $this->model->getOrder());

        $routes = array();
        foreach ($routesData as $routeData)
        {
            $routes[] = Model\Configuration::getById($routeData['id']);
        }

        $this->model->setConfigurations($routes);
        return $routes;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        $data = $this->db->fetchAll($this->model->getFilter(), $this->model->getOrder());
        $amount = count($data);

        return $amount;
    }
}
