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

use Pimcore\Controller\Action\Admin;
use Pimcore\Model\Object;

/**
 * Controller for Social Media
 *
 * Class SocialMedia_Admin_ShareController
 */
class SocialMedia_Admin_ShareController extends Admin
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

    public function getUrlAction() {
        $type = $this->getParam("type");
        $id = $this->getParam("id");
        $network = $this->getParam("network");

        $element = \Pimcore\Model\Element\Service::getElementById($type, $id);

        if($element instanceof \Pimcore\Model\Element\ElementInterface) {
            $networkClass = 'SocialMedia\Model\Network\\' . ucfirst($network);

            if(\Pimcore\Tool::classExists($networkClass)) {
                $networkClass = new $networkClass();

                if($networkClass instanceof \SocialMedia\Model\AbstractNetwork) {
                    try {
                        $shareUrl = \Pimcore\Tool::getHostUrl() . $networkClass->getShareUrl($element, $network);

                        $this->_helper->json(array("success" => true, "url" => $shareUrl));
                    } catch(\Exception $ex) {
                        $this->_helper->json(array("succcess" => false, "message" => $ex->getMessage()));
                    }
                }
                else {
                    $this->_helper->json(array("succcess" => false, "message" => "network class needs to implement AbstractNetwork")); //TODO: Localize
                }
            }
            else {
                $this->_helper->json(array("succcess" => false, "message" => "network class not found")); //TODO: Localize
            }
        }
    }

    public function getSharesForElementAction() {
        $type = $this->getParam("type");
        $id = $this->getParam("id");

        $logList = new \SocialMedia\Model\Log\Listing();
        $logList->setCondition("elementId = ? and elementType = ?", array($id, $type));
        $logs = $logList->load();

        $this->_helper->json(array("succcess" => true, "data" => $logs));
    }
}
