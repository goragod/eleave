<?php
/**
 * @filesource modules/eleave/controllers/report.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Eleave\Report;

use Gcms\Login;
use Kotchasan\Html;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=eleave-report
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * แสดงรายการขอลา
     *
     * @param Request $request
     *
     * @return string
     */
    public function render(Request $request)
    {
        $index = (object) array(
            'status' => $request->request('status')->toInt(),
            'leave_status' => Language::get('LEAVE_STATUS'),
        );
        $index->status = isset($index->leave_status[$index->status]) ? $index->status : 1;
        // ข้อความ title bar
        $this->title = Language::trans('{LNG_Report} {LNG_Request for leave}');
        $title = $index->leave_status[$index->status];
        $this->title .= ' '.$title;
        // เลือกเมนู
        $this->menu = 'report';
        // สมาชิก
        $login = Login::isMember();
        // สามารถอนุมัติได้
        if (Login::checkPermission($login, 'can_approve_eleave')) {
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
            $ul->appendChild('<li><span>{LNG_Report}</span></li>');
            $ul->appendChild('<li><span>'.$title.'</span></li>');
            $section->add('header', array(
                'innerHTML' => '<h2 class="icon-report">'.$this->title.'</h2>',
            ));
            // menu
            $section->appendChild(\Index\Tabmenus\View::render($request, 'report', 'eleave'.$index->status));
            // ตาราง
            $section->appendChild(createClass('Eleave\Report\View')->render($request, $index, $login));
            // คืนค่า HTML
            return $section->render();
        }
        // 404
        return \Index\Error\Controller::execute($this, $request->getUri());
    }
}
