<?php
/**
 * @filesource modules/eleave/views/approve.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Eleave\Approve;

use Gcms\Login;
use Kotchasan\Html;
use Kotchasan\Language;
use Kotchasan\Text;

/**
 * module=eleave-approve
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    /**
     * แบบฟอร์มขอลา
     *
     * @param object $index
     * @param array $login
     *
     * @return string
     */
    public function render($index, $login)
    {
        // แอดมินสามารถแก้ไขได้
        $not_edit = Login::isAdmin() ? false : true;
        // form
        $form = Html::create('form', array(
            'id' => 'setup_frm',
            'class' => 'setup_frm',
            'autocomplete' => 'off',
            'action' => 'index.php/eleave/model/approve/submit',
            'onsubmit' => 'doFormSubmit',
            'ajax' => true,
            'token' => true,
        ));
        $fieldset = $form->add('fieldset', array(
            'title' => '{LNG_Details of} {LNG_Request for leave}',
        ));
        // leave_id
        $fieldset->add('select', array(
            'id' => 'leave_id',
            'labelClass' => 'g-input icon-verfied',
            'itemClass' => 'item',
            'label' => '{LNG_Leave type}',
            'options' => \Eleave\Leavetype\Model::init()->toSelect(),
            'value' => $index->leave_id,
            'disabled' => $not_edit,
        ));
        $fieldset->add('div', array(
            'id' => 'leave_detail',
            'class' => 'subitem message',
        ));
        $category = \Eleave\Category\Model::init();
        foreach ($category->items() as $k => $label) {
            $fieldset->add('select', array(
                'id' => $k,
                'labelClass' => 'g-input icon-valid',
                'itemClass' => 'item',
                'label' => $label,
                'options' => $category->toSelect($k),
                'value' => $index->{$k},
                'disabled' => $not_edit,
            ));
        }
        // detail
        $fieldset->add('textarea', array(
            'id' => 'detail',
            'labelClass' => 'g-input icon-file',
            'itemClass' => 'item',
            'label' => '{LNG_Detail}/{LNG_Reasons for leave}',
            'rows' => 5,
            'value' => $index->detail,
            'disabled' => $not_edit,
        ));
        $groups = $fieldset->add('groups');
        // start_date
        $groups->add('date', array(
            'id' => 'start_date',
            'labelClass' => 'g-input icon-calendar',
            'itemClass' => 'width50',
            'label' => '{LNG_Start date}',
            'value' => $index->start_date,
            'disabled' => $not_edit,
        ));
        $leave_period = Language::get('LEAVE_PERIOD');
        // start_period
        $groups->add('select', array(
            'id' => 'start_period',
            'labelClass' => 'g-input icon-clock',
            'itemClass' => 'width50',
            'label' => '&nbsp;',
            'options' => Language::get('LEAVE_PERIOD'),
            'value' => $index->start_period,
            'disabled' => $not_edit,
        ));
        $groups = $fieldset->add('groups');
        // end_date
        $groups->add('date', array(
            'id' => 'end_date',
            'labelClass' => 'g-input icon-calendar',
            'itemClass' => 'width50',
            'label' => '{LNG_End date}',
            'value' => $index->end_date,
            'disabled' => $not_edit,
        ));
        unset($leave_period[2]);
        // end_period
        $groups->add('select', array(
            'id' => 'end_period',
            'labelClass' => 'g-input icon-clock',
            'itemClass' => 'width50',
            'label' => '&nbsp;',
            'options' => $leave_period,
            'value' => $index->end_period,
            'disabled' => $not_edit,
        ));
        // file
        $fieldset->add('file', array(
            'id' => 'file',
            'labelClass' => 'g-input icon-upload',
            'itemClass' => 'item',
            'label' => '{LNG_Attached file}',
            'comment' => '{LNG_Upload :type files no larger than :size} ({LNG_Can select multiple files})',
            'accept' => self::$cfg->eleave_file_typies,
            'dataPreview' => 'filePreview',
            'disabled' => $not_edit,
        ));
        $fieldset->appendChild('<div class="item">'.\Download\Index\Controller::init($index->id, 'eleave', self::$cfg->eleave_file_typies, $login['id']).'</div>');
        // communication
        $fieldset->add('textarea', array(
            'id' => 'communication',
            'labelClass' => 'g-input icon-file',
            'itemClass' => 'item',
            'label' => '{LNG_Communication}',
            'comment' => '{LNG_Contact information during leave}',
            'rows' => 3,
            'value' => $index->communication,
            'disabled' => $not_edit,
        ));
        // status
        $fieldset->add('select', array(
            'id' => 'status',
            'labelClass' => 'g-input icon-star0',
            'itemClass' => 'item',
            'label' => '{LNG_Status}',
            'options' => Language::get('LEAVE_STATUS'),
            'value' => $index->status,
        ));
        // reason
        $fieldset->add('text', array(
            'id' => 'reason',
            'labelClass' => 'g-input icon-comments',
            'itemClass' => 'item',
            'label' => '{LNG_Reason}',
            'maxlength' => 255,
            'value' => $index->reason,
        ));
        $fieldset = $form->add('fieldset', array(
            'class' => 'submit',
        ));
        // submit
        $fieldset->add('submit', array(
            'class' => 'button ok large icon-save',
            'value' => '{LNG_Save}',
        ));
        // id
        $fieldset->add('hidden', array(
            'id' => 'id',
            'value' => $index->id,
        ));
        \Gcms\Controller::$view->setContentsAfter(array(
            '/:type/' => implode(', ', self::$cfg->eleave_file_typies),
            '/:size/' => Text::formatFileSize(self::$cfg->eleave_upload_size),
        ));
        // Javascript
        $form->script('initEleaveLeave();');
        // คืนค่า HTML
        return $form->render();
    }
}
