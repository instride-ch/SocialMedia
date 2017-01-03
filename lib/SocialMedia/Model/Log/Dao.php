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

namespace SocialMedia\Model\Log;

use Pimcore\Model\Dao\AbstractDao;

/**
 * Class Dao
 * @package SocialMedia\Model\Log
 */
class Dao extends AbstractDao
{
    protected $tableName = 'socialmedia_log';

    /**
     * get log by id
     *
     * @param null $id
     * @throws \Exception
     */
    public function getById($id = null)
    {
        if ($id != null) {
            $this->model->setId($id);
        }

        $data = $this->db->fetchRow('SELECT * FROM '.$this->tableName.' WHERE id = ?', $this->model->getId());

        if (!$data["id"]) {
            throw new \Exception("Object with the ID " . $this->model->getId() . " doesn't exists");
        }

        $this->assignVariablesToModel($data);
    }

    /**
     * @param $elementId
     * @param $elementType
     * @param $network
     *
     * @throws \Exception
     */
    public function getForElement($elementId, $elementType, $network) {
        $data = $this->db->fetchRow('SELECT * FROM '.$this->tableName.' WHERE elementId = ? AND elementType = ? AND network = ?', array($elementId, $elementType, $network));

        if (!$data["id"]) {
            throw new \Exception("Object for elementId $elementId/$elementType/$network doesn't exists");
        }

        $this->assignVariablesToModel($data);
    }

    /**
     * save log
     *
     * @throws \Zend_Db_Adapter_Exception
     */
    public function save()
    {
        $vars = get_object_vars($this->model);

        $buffer = [];

        $validColumns = $this->getValidTableColumns($this->tableName);

        if (count($vars)) {
            foreach ($vars as $k => $v) {
                if (!in_array($k, $validColumns)) {
                    continue;
                }

                $getter = "get" . ucfirst($k);

                if (!is_callable([$this->model, $getter])) {
                    continue;
                }

                $value = $this->model->$getter();

                if (is_bool($value)) {
                    $value = (int)$value;
                }

                $buffer[$k] = $value;
            }
        }

        if ($this->model->getId() !== null) {
            $this->db->update($this->tableName, $buffer, $this->db->quoteInto("id = ?", $this->model->getId()));
            return;
        }

        $this->db->insert($this->tableName, $buffer);
        $this->model->setId($this->db->lastInsertId());
    }

    /**
     * delete vote
     */
    public function delete()
    {
        $this->db->delete($this->tableName, $this->db->quoteInto("id = ?", $this->model->getId()));
    }
}
