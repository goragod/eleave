<?php
/**
 * @filesource eleave/models/leavetype.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Eleave\Leavetype;

/**
 * คลาสสำหรับอ่านข้อมูลหมวดหมู่
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model
{
    /**
     * @var array
     */
    private $datas = array();

    /**
     * อ่านรายชื่อการลา
     *
     * @return static
     */
    public static function init()
    {
        $obj = new static();
        // Query
        $query = \Kotchasan\Model::createQuery()
            ->select('id', 'topic')
            ->from('leave')
            ->where(array('published', 1))
            ->order('topic')
            ->cacheOn();
        foreach ($query->execute() as $item) {
            $obj->datas[$item->id] = $item->topic;
        }

        return $obj;
    }

    /**
     * ลิสต์รายชื่อการลา
     * สำหรับใส่ลงใน select
     *
     * @return array
     */
    public function toSelect()
    {
        return empty($this->datas) ? array() : $this->datas;
    }

    /**
     * อ่านรายชื่อการลาจาก $id
     * ไม่พบ คืนค่าว่าง
     *
     * @param int $id
     *
     * @return string
     */
    public function get($id)
    {
        return empty($this->datas[$id]) ? '' : $this->datas[$id];
    }
}
