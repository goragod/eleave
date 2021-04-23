<?php
/**
 * @filesource modules/eleave/controllers/statistics.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Eleave\Statistics;

use Gcms\Login;
use Kotchasan\Html;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=eleave-statistics
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * แสดงรายการเอกสาร
     *
     * @param Request $request
     *
     * @return string
     */
    public function render(Request $request)
    {
        // สมาชิก
        $login = Login::isMember();
        $from = date(self::$cfg->eleave_fiscal_year);
        if ($from > date('Y-m-d')) {
            $from = date('Y-m-d', strtotime('-1 year '.$from));
        }
        // ค่าที่ส่งมา
        $params = array(
            'from' => $request->request('from', $from)->date(),
            'to' => $request->request('to', date('Y-m-d', strtotime('+12 months -1 day '.$from)))->date(),
            'module' => 'eleave-statistics',
        );
        // สามารถอนุมัติได้
        $can_approve_eleave = $request->request('id')->exists() && Login::checkPermission($login, 'can_approve_eleave');
        if ($can_approve_eleave) {
            $params['member_id'] = $request->request('id')->toInt();
        } else {
            $params['member_id'] = $login['id'];
        }
        $user = \Index\Editprofile\Model::get($params['member_id']);
        // ข้อความ title bar
        $this->title = Language::get('Statistics for leave');
        // เลือกเมนู
        $this->menu = 'eleave';
        // สมาชิก
        if ($user && $login) {
            // ข้อความ title bar
            $this->title .= ' '.$user['name'];
            // แสดงผล
            $section = Html::create('section', array(
                'class' => 'content_bg',
            ));
            // breadcrumbs
            $breadcrumbs = $section->add('div', array(
                'class' => 'breadcrumbs',
            ));
            $ul = $breadcrumbs->add('ul');
            $ul->appendChild('<li><span class="icon-verfied">{LNG_E-Leave}</span></li>');
            $ul->appendChild('<li><span>'.$user['name'].'</span></li>');
            $ul->appendChild('<li><span>{LNG_Statistics for leave}</span></li>');
            $section->add('header', array(
                'innerHTML' => '<h2 class="icon-stats">'.$this->title.'</h2>',
            ));
            if ($can_approve_eleave) {
                // menu
                $section->appendChild(\Index\Tabmenus\View::render($request, 'report', 'eleave'));
            }
            // ตาราง
            $section->appendChild(createClass('Eleave\Statistics\View')->render($request, $params));
            // คืนค่า HTML
            return $section->render();
        }
        // 404
        return \Index\Error\Controller::execute($this, $request->getUri());
    }
}
