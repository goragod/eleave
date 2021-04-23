<?php
/**
 * @filesource modules/eleave/models/home.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Eleave\Home;

use Gcms\Login;

/**
 * โมเดลสำหรับอ่านข้อมูลแสดงในหน้า  Home.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * Dashboard
     *
     * @return object
     */
    public static function get($login)
    {
        $q1 = static::createQuery()
            ->selectCount()
            ->from('leave_items');
        $q2 = static::createQuery()
            ->selectCount()
            ->from('leave_items');
        $q3 = static::createQuery()
            ->selectCount()
            ->from('leave_items');
        if (Login::checkPermission($login, 'can_approve_eleave')) {
            // สามารถอนุมัติได้
            $q1->where(array('status', 0));
            $q2->where(array('status', 1));
            $q3->where(array('status', 2));
        } else {
            $q1->where(array(
                array('member_id', $login['id']),
                array('status', 0),
            ));
            $q2->where(array(
                array('member_id', $login['id']),
                array('status', 1),
            ));
            $q3->where(array(
                array('member_id', $login['id']),
                array('status', 2),
            ));
        }

        return static::createQuery()->first(array($q1, 'pending'), array($q2, 'approve'), array($q3, 'unapprove'));
    }
}
