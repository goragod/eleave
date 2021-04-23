<?php
/**
 * @filesource modules/eleave/controllers/home.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Eleave\Home;

use Gcms\Login;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * Controller สำหรับการแสดงผลหน้า Home.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\KBase
{
    /**
     * ฟังก์ชั่นสร้าง card.
     *
     * @param Request               $request
     * @param \Kotchasan\Collection $card
     * @param array                 $login
     */
    public static function addCard(Request $request, $card, $login)
    {
        $leave_status = Language::get('LEAVE_STATUS');
        $datas = \Eleave\Home\Model::get($login);
        if (Login::checkPermission($login, 'can_approve_eleave')) {
            // สามารถอนุมัติได้
            $url = 'index.php?module=eleave-report&amp;status=';
        } else {
            $url = 'index.php?module=eleave&amp;status=';
        }
        \Index\Home\Controller::renderCard($card, 'icon-verfied', '{LNG_Request for leave}', number_format($datas->pending), $leave_status[0], $url.'0');
        \Index\Home\Controller::renderCard($card, 'icon-valid', '{LNG_Request for leave}', number_format($datas->approve), $leave_status[1], $url.'1');
        \Index\Home\Controller::renderCard($card, 'icon-invalid', '{LNG_Request for leave}', number_format($datas->unapprove), $leave_status[2], $url.'2');
    }
}
