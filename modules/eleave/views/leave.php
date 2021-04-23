<?php
/**
 * @filesource modules/eleave/views/leave.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Eleave\Leave;

use Kotchasan\Html;
use Kotchasan\Language;
use Kotchasan\Text;

/**
 * module=eleave-leave
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
        // สามารถแก้ไขได้
        $canEdit = $index->status == 0 ? true : false;
        // form
        $form = Html::create('form', array(
            'id' => 'setup_frm',
            'class' => 'setup_frm',
            'autocomplete' => 'off',
            'action' => 'index.php/eleave/model/leave/submit',
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
            'value' => isset($index->leave_id) ? $index->leave_id : 0,
            'disabled' => !$canEdit,
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
                'value' => isset($index->{$k}) ? $index->{$k} : 0,
            ));
        }
        // detail
        $fieldset->add('textarea', array(
            'id' => 'detail',
            'labelClass' => 'g-input icon-file',
            'itemClass' => 'item',
            'label' => '{LNG_Detail}/{LNG_Reasons for leave}',
            'rows' => 5,
            'value' => isset($index->detail) ? $index->detail : '',
            'disabled' => !$canEdit,
        ));
        $groups = $fieldset->add('groups');
        // start_date
        $groups->add('date', array(
            'id' => 'start_date',
            'labelClass' => 'g-input icon-calendar',
            'itemClass' => 'width50',
            'label' => '{LNG_Start date}',
            'value' => isset($index->start_date) ? $index->start_date : date('Y-m-d'),
            'disabled' => !$canEdit,
        ));
        $leave_period = Language::get('LEAVE_PERIOD');
        // start_period
        $groups->add('select', array(
            'id' => 'start_period',
            'labelClass' => 'g-input icon-clock',
            'itemClass' => 'width50',
            'label' => '&nbsp;',
            'options' => Language::get('LEAVE_PERIOD'),
            'value' => isset($index->start_period) ? $index->start_period : 0,
            'disabled' => !$canEdit,
        ));
        $groups = $fieldset->add('groups');
        // end_date
        $groups->add('date', array(
            'id' => 'end_date',
            'labelClass' => 'g-input icon-calendar',
            'itemClass' => 'width50',
            'label' => '{LNG_End date}',
            'value' => isset($index->end_date) ? $index->end_date : date('Y-m-d'),
            'disabled' => !$canEdit,
        ));
        unset($leave_period[2]);
        // end_period
        $groups->add('select', array(
            'id' => 'end_period',
            'labelClass' => 'g-input icon-clock',
            'itemClass' => 'width50',
            'label' => '&nbsp;',
            'options' => $leave_period,
            'value' => isset($index->end_period) ? $index->end_period : 0,
            'disabled' => !$canEdit,
        ));
        if ($canEdit) {
            // file
            $fieldset->add('file', array(
                'id' => 'file',
                'labelClass' => 'g-input icon-upload',
                'itemClass' => 'item',
                'label' => '{LNG_Attached file}',
                'comment' => '{LNG_Upload :type files no larger than :size} ({LNG_Can select multiple files})',
                'accept' => self::$cfg->eleave_file_typies,
                'dataPreview' => 'filePreview',
            ));
        }
        $fieldset->appendChild('<div class="item">'.\Download\Index\Controller::init($index->id, 'eleave', self::$cfg->eleave_file_typies, ($canEdit ? $login['id'] : 0)).'</div>');
        // communication
        $fieldset->add('textarea', array(
            'id' => 'communication',
            'labelClass' => 'g-input icon-file',
            'itemClass' => 'item',
            'label' => '{LNG_Communication}',
            'comment' => '{LNG_Contact information during leave}',
            'rows' => 3,
            'value' => isset($index->communication) ? $index->communication : '',
            'disabled' => !$canEdit,
        ));
        $fieldset = $form->add('fieldset', array(
            'class' => 'submit',
        ));
        if ($canEdit) {
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
        } else {
            // status
            $fieldset->add('select', array(
                'id' => 'status',
                'labelClass' => 'g-input icon-star0',
                'itemClass' => 'item',
                'label' => '{LNG_Status}',
                'options' => Language::get('LEAVE_STATUS'),
                'value' => $index->status,
                'disabled' => true,
            ));
            // reason
            $fieldset->add('text', array(
                'id' => 'reason',
                'labelClass' => 'g-input icon-comments',
                'itemClass' => 'item',
                'label' => '{LNG_Reason}',
                'maxlength' => 255,
                'value' => $index->reason,
                'disabled' => true,
            ));
        }
        // Javascript
        $form->script('initEleaveLeave();');
        // คืนค่า HTML
        return $form->render();
    }
}
