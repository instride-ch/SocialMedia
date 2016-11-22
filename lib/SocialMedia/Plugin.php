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

namespace SocialMedia;

use Pimcore\API\Plugin as PluginLib;
use Pimcore\Db;
use SocialMedia\Controller\Plugin\Analytics;

/**
 * Class Plugin
 * @package SocialMedia
 */
class Plugin extends PluginLib\AbstractPlugin implements PluginLib\PluginInterface
{
    /**
     * @var \Zend_Translate
     */
    protected static $_translate;


    public function init()
    {
        parent::init();

        define('SOCIALMEDIA_PLUGIN_CONFIG', PIMCORE_PLUGINS_PATH . '/SocialMedia/plugin.xml');

        \Pimcore::getEventManager()->attach('system.startup', function (\Zend_EventManager_Event $e) {
            $frontController = $e->getTarget();

            if ($frontController instanceof \Zend_Controller_Front) {
                $frontController->registerPlugin(new Analytics());
            }
        });
    }

    /**
     * @return bool
     */
    public static function install()
    {
        $db = Db::get();

        $db->query("CREATE TABLE `socialmedia_log` (
            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `elementId` int(11) unsigned NOT NULL DEFAULT '0',
            `elementType` enum('document','asset','object') NOT NULL DEFAULT 'document',
            `network` varchar(100) NOT NULL,
            `clicks` int(11) unsigned NOT NULL DEFAULT 0,
            UNIQUE( `elementId`, `elementType`, `network`)
        );");

        return true;
    }

    /**
     * @return bool
     */
    public static function uninstall()
    {
        return true;
    }

    /**
     * indicates wether this plugins is currently installed
     * @return boolean
     */
    public static function isInstalled() {
        $result = null;

        try
        {
            $result = Db::get()->describeTable("socialmedia_log");
        }
        catch(\Exception $e) {

        }

        return !empty($result);
    }

    /**
     * get translation directory.
     *
     * @return string
     */
    public static function getTranslationFileDirectory()
    {
        return PIMCORE_PLUGINS_PATH.'/SocialMedia/static/texts';
    }

    /**
     * get translation file.
     *
     * @param string $language
     *
     * @return string path to the translation file relative to plugin directory
     */
    public static function getTranslationFile($language)
    {
        if (is_file(self::getTranslationFileDirectory()."/$language.csv")) {
            return "/SocialMedia/static/texts/$language.csv";
        } else {
            return '/SocialMedia/static/texts/en.csv';
        }
    }

    /**
     * get translate.
     *
     * @param $lang
     *
     * @return \Zend_Translate
     */
    public static function getTranslate($lang = null)
    {
        if (self::$_translate instanceof \Zend_Translate) {
            return self::$_translate;
        }
        if (is_null($lang)) {
            try {
                $lang = \Zend_Registry::get('Zend_Locale')->getLanguage();
            } catch (\Exception $e) {
                $lang = 'en';
            }
        }

        self::$_translate = new \Zend_Translate(
            'csv',
            PIMCORE_PLUGINS_PATH.self::getTranslationFile($lang),
            $lang,
            array('delimiter' => ',')
        );

        return self::$_translate;
    }
}
