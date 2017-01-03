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

namespace SocialMedia\Model\ObjectRoute;

use Pimcore\Model;

/**
 * Class Dao
 * @package SocialMedia\Model\ObjectRoute
 */
class Dao extends Model\Dao\PhpArrayTable
{
    /**
     * Configure Configuration File.
     */
    public function configure()
    {
        parent::configure();
        $this->setFile('socialmedia_objectroute');
    }

    /**
     * Get Configuration By Id.
     *
     * @param null $id
     *
     * @throws \Exception
     */
    public function getById($id = null)
    {
        if ($id != null) {
            $this->model->setId($id);
        }

        $data = $this->db->getById($this->model->getId());

        if (isset($data['id'])) {
            $this->assignVariablesToModel($data);
        } else {
            throw new \Exception('Definition with id: '.$this->model->getId().' does not exist');
        }
    }

    /**
     * Get ObjectRoute by Class.
     *
     * @param null $class
     *
     * @throws \Exception
     */
    public function getByClass($class = null)
    {
        $data = $this->db->fetchAll(function ($row) use ($class) {
            if ($row['class'] == $class) {
                return true;
            }

            return false;
        });

        if (count($data) && $data[0]['id']) {
            $this->assignVariablesToModel($data[0]);
        } else {
            throw new \Exception('ObjectRoute for class: '. $class.' does not exist');
        }
    }

    /**
     * @param array $data
     * @return void
     */
    protected function assignVariablesToModel($data)
    {
        parent::assignVariablesToModel($data);
    }

    /**
     * save configuration.
     *
     * @throws \Exception
     */
    public function save()
    {
        $ts = time();
        if (!$this->model->getCreationDate()) {
            $this->model->setCreationDate($ts);
        }
        $this->model->setModificationDate($ts);

        try {
            $dataRaw = get_object_vars($this->model);
            $data = [];

            foreach ($dataRaw as $key => $value) {
                $data[$key] = $value;
            }
            $this->db->insertOrUpdate($data, $this->model->getId());
        } catch (\Exception $e) {
            throw $e;
        }

        if (!$this->model->getId()) {
            $this->model->setId($this->db->getLastInsertId());
        }
    }

    /**
     * Deletes object from database.
     */
    public function delete()
    {
        $this->db->delete($this->model->getId());
    }
}
