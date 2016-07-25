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

use Pimcore\Controller\Action\Admin;
use Pimcore\Model\Object;

/**
 * Controller for Social Media
 *
 * Class SocialMedia_Admin_ShareController
 */
class SocialMedia_Admin_SettingsController extends Admin
{
    public function init()
    {
        parent::init();

        // check permissions
        //TODO: Permissions?
        /*$notRestrictedActions = array('list');
        if (!in_array($this->getParam('action'), $notRestrictedActions)) {
            $this->checkPermission('');
        }*/
    }

    public function getConfigAction()
    {
        $this->_helper->json(array(
            'success' => true,
            'networks' => \SocialMedia\Model\AbstractNetwork::$availableNetworks
        ));
    }

    public function objectRouteAction()
    {
        if ($this->getParam("data")) {
            $this->checkPermission("website_settings");

            $data = \Zend_Json::decode($this->getParam("data"));

            if (is_array($data)) {
                foreach ($data as &$value) {
                    $value = trim($value);
                }
            }

            if ($this->getParam("xaction") == "destroy") {
                $id = $data;

                $route = \SocialMedia\Model\ObjectRoute::getById($id);
                $route->delete();

                $this->_helper->json(["success" => true, "data" => []]);
            } elseif ($this->getParam("xaction") == "create" || $this->getParam("xaction") == "update") {

                unset($data['id']);

                // save route
                $route = SocialMedia\Model\ObjectRoute::getByClass($data['class']);

                if(!$route instanceof \SocialMedia\Model\ObjectRoute) {
                    $route = new SocialMedia\Model\ObjectRoute();
                }

                $route->setValues($data);
                $route->save();

                $this->_helper->json(["data" => $route, "success" => true]);
            }
        } else {
            $classes = new Object\ClassDefinition\Listing();
            $result = [];
            $classes = $classes->load();

            foreach ($classes as $class) {
                if ($class instanceof Object\ClassDefinition) {
                    $r = [
                        'class' => $class->getName(),
                        'staticRoute' => null
                    ];
                    $objectRoute = \SocialMedia\Model\ObjectRoute::getByClass($class->getName());

                    if ($objectRoute) {
                        $r['id'] = $objectRoute->getId();
                        $r['url'] = $objectRoute->getUrl();
                    }

                    $result[] = $r;
                }
            }

            $this->_helper->json(array("success" => true, "data" => $result));
        }

        $this->_helper->json(false);
    }

    public function saveAction()
    {
        $route = $this->getParam('staticRoute');
        $class = $this->getParam('class');
        $objectRoute = \SocialMedia\Model\ObjectRoute::getByClass($class);

        if(!$objectRoute) {
            $objectRoute = new \SocialMedia\Model\ObjectRoute();
        }

        $objectRoute->setClass($class);
        $objectRoute->setStaticRoute($route);
        $objectRoute->save();

        $this->_helper->json(array('success' => true, 'data' => $objectRoute));
    }

    public function getAction()
    {
        $configValues = [];

        $config = new \SocialMedia\Model\Configuration\Listing();

        foreach ($config->getConfigurations() as $c) {
            $configValues[$c->getKey()] = $c->getData();
        }

        $response = array(
            'values' => $configValues
        );

        $this->_helper->json($response);
    }

    public function setAction()
    {
        $data = \Zend_Json::decode($this->getParam('data'));
        $data = array_htmlspecialchars($data);

        foreach($data as $key => $value) {
            \SocialMedia\Model\Configuration::set($key, $value);
        }

        $this->_helper->json(array('success' => true));
    }
}
