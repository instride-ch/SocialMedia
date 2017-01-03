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

namespace SocialMedia\Controller\Plugin;

use Pimcore\Tool;
use SocialMedia\Model\Log;

/**
 * Class Analytics
 * @package SocialMedia\Controller\Plugin
 */
class Analytics extends \Zend_Controller_Plugin_Abstract
{
    /**
     * shutdown.
     */
    public function dispatchLoopShutdown()
    {
        if (!Tool::isHtmlResponse($this->getResponse())) {
            return;
        }

        if (!Tool::useFrontendOutputFilters($this->getRequest()) && !$this->getRequest()->getParam('pimcore_preview')) {
            return;
        }

        if($this->getRequest()->getParam("_sm")) {
            $log = Log::getById($this->getRequest()->getParam("_sm"));

            if($log instanceof Log) {
                $log->increase();
            }
        }
    }
}
