<?php
/**
 * @filesource modules/eleave/models/email.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Eleave\Email;

use Kotchasan\Language;

/**
 * ส่งอีเมลไปยังผู้ที่เกี่ยวข้อง
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * ส่งอีเมลแจ้งการทำรายการ
     *
     * @param string $mailto อีเมล
     * @param string $name   ชื่อ
     * @param array  $order ข้อมูล
     */
    public static function send($mailto, $name, $order)
    {
        $ret = array();
        // ข้อความอีเมล
        $msg = Language::trans(createClass('Eleave\View\View')->render($order, true));
        // ข้อความสำหรับผู้อนุมัติ
        $admin_msg = str_replace('%MODULE%', 'approve', $msg);
        if (self::$cfg->noreply_email != '') {
            // email subject
            $subject = '['.self::$cfg->web_title.'] '.Language::get('Request for leave').' '.Language::find('LEAVE_STATUS', '', $order['status']);
            // ส่งอีเมลไปยังผู้ทำรายการ
            $user_msg = str_replace('%MODULE%', 'leave', $msg);
            $err = \Kotchasan\Email::send($name.'<'.$mailto.'>', self::$cfg->noreply_email, $subject, $user_msg);
            if ($err->error()) {
                // คืนค่า error
                $ret[] = strip_tags($err->getErrorMessage());
            }
            // ส่งอีเมล์ไปยังผู้อนุมัติ
            $where = array(
                array('U.permission', 'LIKE', '%,can_approve_eleave,%'),
                array('U.active', 1),
            );
            if (self::$cfg->demo_mode) {
                $where[] = array('U.social', 0);
            }
            $query = static::createQuery()
                ->select('U.username', 'U.name')
                ->from('user U')
                ->where($where)
                ->cacheOn();
            foreach ($query->execute() as $item) {
                $err = \Kotchasan\Email::send($item->name.'<'.$item->username.'>', self::$cfg->noreply_email, $subject, $admin_msg);
                if ($err->error()) {
                    // คืนค่า error
                    $ret[] = strip_tags($err->getErrorMessage());
                }
            }
        }
        if (!empty(self::$cfg->line_api_key)) {
            // ส่ง Line
            \Gcms\Line::send($admin_msg, self::$cfg->line_api_key);
        }
        return empty($ret) ? Language::get('Your message was sent successfully') : implode("\n", $ret);
    }
}
