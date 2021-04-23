<?php
/**
 * @filesource modules/download/models/upload.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Download\Upload;

use Kotchasan\File;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * อัปโหลดไฟล์
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\KBase
{
    /**
     * ลบไฟล์
     * ที่เก็บไฟล์ ROOT_PATH.DATA_FOLDER.$module.'/'.$id.'/'
     * คืนค่าข้อมูลไฟล์อัปโหลด
     *
     * @param array $ret ตัวแปรสำหรับรับค่ากลับ
     * @param Request $request
     * @param int $id ID ของไฟล์
     * @param string $module ไดเร็คทอรี่เก็บไฟล์ปกติจะเป็นชื่อโมดูล
     * @param array $typies ประเภทของไฟล์ที่สามารถอัปโหลดได้
     * @param int $size ขนาดของไฟล์ (byte) ที่สามารถอัปโหลดได้, 0 หมายถึงไม่ตรวจสอบ
     *
     * @return array
     */
    public static function execute(&$ret, Request $request, $id, $module, $typies, $size = 0)
    {
        $files = array();
        // ไดเร็คทอรี่เก็บไฟล์
        $dir = ROOT_PATH.DATA_FOLDER.$module.'/'.$id.'/';
        // อัปโหลดไฟล์
        foreach ($request->getUploadedFiles() as $item => $file) {
            if (preg_match('/^([a-zA-Z0-9_]+)(\[[0-9]+\])?$/', $item, $match)) {
                /* @var $file \Kotchasan\Http\UploadedFile */
                if ($file->hasUploadFile()) {
                    if (!File::makeDirectory(ROOT_PATH.DATA_FOLDER.$module.'/') || !File::makeDirectory($dir)) {
                        // ไดเรคทอรี่ไม่สามารถสร้างได้
                        $ret['ret_'.$match[1]] = sprintf(Language::get('Directory %s cannot be created or is read-only.'), $module.'/'.$id.'/');
                    } elseif (!$file->validFileExt($typies)) {
                        // ชนิดของไฟล์ไม่ถูกต้อง
                        $ret['ret_'.$match[1]] = Language::get('The type of file is invalid');
                    } elseif ($size > 0 && $size < $file->getSize()) {
                        // ขนาดของไฟล์ใหญ่เกินไป
                        $ret['ret_'.$match[1]] = Language::get('The file size larger than the limit');
                    } else {
                        // อัปโหลด ชื่อไฟล์แบบสุ่ม
                        $ext = $file->getClientFileExt();
                        $file_upload = uniqid().'.'.$ext;
                        while (file_exists($dir.$file_upload)) {
                            $file_upload = uniqid().'.'.$ext;
                        }
                        try {
                            $file->moveTo($dir.$file_upload);
                            // คืนค่ารายละเอียดของไฟล์อัปโหลด
                            $files[] = array(
                                'ext' => $ext,
                                'name' => preg_replace('/\\.'.$ext.'$/', '', $file->getClientFilename()),
                                'size' => $file->getSize(),
                                'file' => $file_upload,
                            );
                        } catch (\Exception $exc) {
                            // ไม่สามารถอัปโหลดได้
                            $ret['ret_'.$match[1]] = Language::get($exc->getMessage());
                        }
                    }
                } elseif ($file->hasError()) {
                    // ข้อผิดพลาดการอัปโหลด
                    $ret['ret_'.$match[1]] = Language::get($file->getErrorMessage());
                }
            }
        }

        return $files;
    }
}
