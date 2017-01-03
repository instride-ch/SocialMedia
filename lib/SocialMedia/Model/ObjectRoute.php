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

namespace SocialMedia\Model;

use Pimcore\Model\AbstractModel;
use Pimcore\Model\Object\Concrete;
use Pimcore\Placeholder;

/**
 * Class ObjectRoute
 * @package SocialMedia\Model
 */
class ObjectRoute extends AbstractModel
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $class;

    /**
     * @var string
     */
    public $url;

    /**
     * @var
     */
    public $creationDate;

    /**
     * @var
     */
    public $modificationDate;

    /**
     * Get By Id.
     *
     * @param int $id
     *
     * @return static
     */
    public static function getById($id)
    {
        $cacheKey = 'socialmedia_objectroute_'.$id;

        try {
            $objectRoute = \Zend_Registry::get($cacheKey);
            if (!$objectRoute) {
                throw new \Exception('ObjectRoute in registry is null');
            }
        } catch (\Exception $e) {
            try {
                $objectRoute = new self();
                \Zend_Registry::set($cacheKey, $objectRoute);
                $objectRoute->setId(intval($id));
                $objectRoute->getDao()->getById();
            } catch (\Exception $e) {
                \Logger::error($e);

                return null;
            }
        }

        return $objectRoute;
    }

    /**
     * Get By Class.
     *
     * @param string $class
     *
     * @return static
     */
    public static function getByClass($class)
    {
        $cacheKey = 'socialmedia_objectroute_class_'.$class;

        try {
            $objectRoute = \Zend_Registry::get($cacheKey);
            if (!$objectRoute) {
                throw new \Exception('ObjectRoute in registry is null');
            }
        } catch (\Exception $e) {
            try {
                $objectRoute = new self();
                \Zend_Registry::set($cacheKey, $objectRoute);
                $objectRoute->getDao()->getByClass($class);
            } catch (\Exception $e) {
                \Logger::error($e);

                return null;
            }
        }

        return $objectRoute;
    }

    /**
     * @param Concrete $object
     *
     * @return string
     */
    public function assemble($object) {
        $url = $this->getUrl();
        $vars = get_object_vars($object);

        foreach($object->getLocalizedfields()->getItems() as $lang => $item) {

            foreach($item as $key => $val) {
                $vars[$key . "." . $lang] = $val;
            }
        }

        foreach ($vars as $key => $value) {
            if (!empty($value) && (is_string($value) || is_numeric($value))) {
                $url = str_replace("%" . $key, urlencode($value), $url);
            }
        }

        return $url;
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
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param mixed $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return mixed
     */
    public function getModificationDate()
    {
        return $this->modificationDate;
    }

    /**
     * @param mixed $modificationDate
     */
    public function setModificationDate($modificationDate)
    {
        $this->modificationDate = $modificationDate;
    }
}
