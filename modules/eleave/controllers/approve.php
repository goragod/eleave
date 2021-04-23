<?php
/**
 * @filesource modules/eleave/controllers/approve.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Eleave\Approve;

use Gcms\Login;
use Kotchasan\Html;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=eleave-approve
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * แบบฟอร์มขอลา
     *
     * @param Request $request
     *
     * @return string
     */
    public function render(Request $request)
    {
        // สมาชิก
        $login = Login::isMember();
        // ตรวจสอบรายการที่เลือก
        $index = \Eleave\Leave\Model::get($request->request('id')->toInt(), $login);
        // ข้อความ title bar
        $this->title = Language::trans('{LNG_Edit} {LNG_Request for leave}');
        // เลือกเมนู
        $this->menu = 'report';
        // อนุมัติได้
        if ($index && Login::checkPermission($login, 'can_approve_eleave')) {
            // แสดงผล
            $section = Html::create('section', array(
                'class' => 'content_bg',
            ));
            // breadcrumbs
            $breadcrumbs = $section->add('div', array(
                'class' => 'breadcrumbs',
            ));
            $ul = $breadcrumbs->add('ul');
            $ul->appendChild('<li><span class="icon-verfied">{LNG_Request for leave}</span></li>');
            $ul->appendChild('<li><a href="{BACKURL?module=eleave-report}">{LNG_Report}</a></li>');
            $ul->appendChild('<li><span>{LNG_Edit}</span></li>');
            $section->add('header', array(
                'innerHTML' => '<h2 class="icon-write">'.$this->title.'</h2>',
            ));
            // menu
            $section->appendChild(\Index\Tabmenus\View::render($request, 'report', 'eleave'));
            // แสดงฟอร์ม
            $section->appendChild(createClass('Eleave\Approve\View')->render($index, $login));
            // คืนค่า HTML
            return $section->render();
        }
        // 404
        return \Index\Error\Controller::execute($this, $request->getUri());
    }
}
