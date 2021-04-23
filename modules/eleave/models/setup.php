<?php
/**
 * @filesource modules/eleave/models/setup.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Eleave\Setup;

use Gcms\Login;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=eleave-setup
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * Query ข้อมูลสำหรับส่งให้กับ DataTable
     *
     * @return \Kotchasan\Database\QueryBuilder
     */
    public static function toDataTable()
    {
        return static::createQuery()
            ->select('id', 'topic', 'num_days', 'published')
            ->from('leave');
    }

    /**
     * รับค่าจาก action
     *
     * @param Request $request
     */
    public function action(Request $request)
    {
        $ret = array();
        // session, referer, สามารถจัดการโมดูลได้, ไม่ใช่สมาชิกตัวอย่าง
        if ($request->initSession() && $request->isReferer() && $login = Login::isMember()) {
            if (Login::checkPermission($login, 'can_manage_eleave') && Login::notDemoMode($login)) {
                // รับค่าจากการ POST
                $action = $request->post('action')->toString();
                // id ที่ส่งมา
                if (preg_match_all('/,?([0-9]+),?/', $request->post('id')->toString(), $match)) {
                    // ตาราง
                    $table = $this->getTableName('leave');
                    if ($action === 'delete') {
                        // ลบ
                        $this->db()->delete($table, array('id', $match[1]), 0);
                        // reload
                        $ret['location'] = 'reload';
                    } elseif ($action === 'published') {
                        // สถานะการเผยแพร่
                        $search = $this->db()->first($table, (int) $match[1][0]);
                        if ($search) {
                            $published = $search->published == 1 ? 0 : 1;
                            $this->db()->update($table, $search->id, array('published' => $published));
                            // คืนค่า
                            $ret['elem'] = 'published_'.$search->id;
                            $lng = Language::get('PUBLISHEDS');
                            $ret['title'] = $lng[$published];
                            $ret['class'] = 'icon-published'.$published;
                        }
                    }
                }
            }
        }
        if (empty($ret)) {
            $ret['alert'] = Language::get('Unable to complete the transaction');
        }
        // คืนค่าเป็น JSON
        echo json_encode($ret);
    }
}
