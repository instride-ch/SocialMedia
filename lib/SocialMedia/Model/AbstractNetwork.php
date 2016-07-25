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

namespace SocialMedia\Model;

use Pimcore\Model\Document;
use Pimcore\Model\Element\ElementInterface;
use Pimcore\Model\Object\AbstractObject;
use Pimcore\Model\Object\Concrete;
use Pimcore\Model\Staticroute;

/**
 * Class Network
 * @package SocialMedia\Model
 */
abstract class AbstractNetwork
{
    /**
     * available networks.
     *
     * @var array
     */
    public static $availableNetworks = array('facebook', 'twitter');

    /**
     * Add Interpreter.
     *
     * @param $network
     */
    public static function addNetwork($network)
    {
        if (!in_array($network, self::$availableNetworks)) {
            self::$availableNetworks[] = $network;
        }
    }

    /**
     * @param ElementInterface $element
     * @param string $network
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getShareUrl($element, $network) {
        $elementUrl = $this->getElementUrl($element);

        $log = Log::getForElement($element, $network);

        $elementUrl .= (parse_url($elementUrl, PHP_URL_QUERY) ? '&' : '?') . '_sm=' . $log->getId();

        return $elementUrl;
    }

    /**
     * @param ElementInterface $element
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getElementUrl($element) {
        if($element instanceof AbstractObject) {
            if($element instanceof Concrete) {
                $className = $element->getClassName();
                $classRoute = ObjectRoute::getByClass($className);

                if(!$classRoute) {
                    throw new \Exception("No ObjectRoute for Class $className found");
                }

                return $classRoute->assemble($element);
            }
        }
        else if($element instanceof Document) {
            return $element->getFullPath();
        }

        throw new \Exception("Element type is not supported");
    }
}
