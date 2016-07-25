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

use Pimcore\Model\AbstractModel;
use Pimcore\Model\Element\ElementInterface;
use Pimcore\Model\Element\Service;

/**
 * Class Log
 * @package SocialMedia\Model
 */
class Log extends AbstractModel
{

    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $elementId;

    /**
     * @var string
     */
    public $elementType;

    /**
     * @var string
     */
    public $network;

    /**
     * @var int
     */
    public $clicks;

    /**
     * @param $id
     * @return null|Log
     */
    public static function getById($id)
    {
        try {
            $obj = new self;
            $obj->getDao()->getById($id);
            return $obj;
        } catch (\Exception $ex) {
            \Logger::warn("Log with id $id not found");
        }

        return null;
    }

    /**
     * @param ElementInterface $element
     * @param string $network
     *
     * @return static
     */
    public static function getForElement($element, $network) {
        $type = Service::getType($element);

        try {
            $obj = new self;
            $obj->getDao()->getForElement($element->getId(), $type, $network);
            return $obj;
        } catch (\Exception $ex) {
            $obj = new self();
            $obj->setElementId($element->getId());
            $obj->setElementType($type);
            $obj->setNetwork($network);
            $obj->setClicks(0);
            $obj->save();

            return $obj;
        }
    }

    /**
     * Increase clicks
     */
    public function increase() {
        $this->clicks++;
        $this->save();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getElementId()
    {
        return $this->elementId;
    }

    /**
     * @param int $elementId
     */
    public function setElementId($elementId)
    {
        $this->elementId = $elementId;
    }

    /**
     * @return string
     */
    public function getElementType()
    {
        return $this->elementType;
    }

    /**
     * @param string $elementType
     */
    public function setElementType($elementType)
    {
        $this->elementType = $elementType;
    }

    /**
     * @return int
     */
    public function getClicks()
    {
        return $this->clicks;
    }

    /**
     * @param int $clicks
     */
    public function setClicks($clicks)
    {
        $this->clicks = $clicks;
    }

    /**
     * @return string
     */
    public function getNetwork()
    {
        return $this->network;
    }

    /**
     * @param string $network
     */
    public function setNetwork($network)
    {
        $this->network = $network;
    }
}
