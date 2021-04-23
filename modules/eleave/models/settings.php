<?php
/**
 * @filesource modules/eleave/models/settings.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Eleave\Settings;

use Gcms\Login;
use Kotchasan\Config;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=eleave-settings
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\KBase
{
    /**
     * รับค่าจาก settings.php.
     *
     * @param Request $request
     */
    public function submit(Request $request)
    {
        $ret = array();
        // session, token, can_config, ไม่ใช่สมาชิกตัวอย่าง
        if ($request->initSession() && $request->isSafe() && $login = Login::isMember()) {
            if (Login::notDemoMode($login) && Login::checkPermission($login, 'can_config')) {
                try {
                    // รับค่าจากการ POST
                    $typies = array();
                    foreach (explode(',', strtolower($request->post('eleave_file_typies')->filter('a-zA-Z0-9,'))) as $typ) {
                        if ($typ != '') {
                            $typies[$typ] = $typ;
                        }
                    }
                    // โหลด config
                    $config = Config::load(ROOT_PATH.'settings/config.php');
                    $config->eleave_fiscal_year = $request->post('eleave_fiscal_year')->toString();
                    $config->eleave_file_typies = array_keys($typies);
                    $config->eleave_upload_size = $request->post('eleave_upload_size')->toInt();
                    if (!preg_match('/^[Y0-9]+\-[m0-9]+\-[d0-9]+$/', $config->eleave_fiscal_year)) {
                        // ข้อมูลไม่ถูกต้อง
                        $ret['ret_eleave_fiscal_year'] = 'Invalid data';
                    }
                    if (empty($config->eleave_file_typies)) {
                        // คืนค่า input ที่ error
                        $ret['ret_eleave_file_typies'] = 'this';
                    }
                    if (empty($ret)) {
                        // save config
                        if (Config::save($config, ROOT_PATH.'settings/config.php')) {
                            // คืนค่า
                            $ret['alert'] = Language::get('Saved successfully');
                            $ret['location'] = 'reload';
                            // เคลียร์
                            $request->removeToken();
                        } else {
                            // ไม่สามารถบันทึก config ได้
                            $ret['alert'] = sprintf(Language::get('File %s cannot be created or is read-only.'), 'settings/config.php');
                        }
                    }
                } catch (\Kotchasan\InputItemException $e) {
                    $ret['alert'] = $e->getMessage();
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
