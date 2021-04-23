<?php
/**
 * @filesource modules/download/controllers/index.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Download\Index;

use Kotchasan\Mime;

/**
 * แสดงผลไฟล์ดาวน์โหลด
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * ลิสต์รายการไฟล์ คืนค่าเป็น HTML สำหรับแสดงผล
     * ที่เก็บไฟล์ ROOT_PATH.DATA_FOLDER.$module.'/'.$id.'/'.
     *
     * @param int $id ID ของไฟล์
     * @param string $module ไดเร็คทอรี่เก็บไฟล์ปกติจะเป็นชื่อโมดูล
     * @param array $typies ประเภทของไฟล์ที่สามารถอัปโหลดได้
     * @param int $owner_id มากกว่า 0 ตรวจสอบกับคน Login ถ้าตรงกันถึงจะลบได้, 0 ไม่สามารถลบได้
     *
     * @return string
     */
    public static function init($id, $module, $typies, $owner_id = 0)
    {
        $files = array();
        \Kotchasan\File::listFiles(ROOT_PATH.DATA_FOLDER.$module.'/'.$id.'/', $files);
        $elem = uniqid();
        $content = '<div class="file_list clear" id='.$elem.'>';
        foreach ($files as $i => $item) {
            if (preg_match('/.*\/('.$id.')\/([a-z0-9]+)\.('.implode('|', $typies).')$/', $item, $match)) {
                // id ของไฟล์
                $uid = uniqid();
                // MIME สำหรับการดาวน์โหลด
                $mime = Mime::get($match[3]);
                $_SESSION[$uid] = array(
                    'file' => $item,
                    'mime' => $mime === null || !in_array($match[3], array('pdf', 'png', 'gif', 'jpg', 'jpeg')) ? 'application/octet-stream' : $mime,
                    'owner_id' => $owner_id,
                );
                $img = WEB_URL.'skin/ext/'.(is_file(ROOT_PATH.'skin/ext/'.$match[3].'.png') ? $match[3] : 'file').'.png';
                $content .= '<div id="item_'.$uid.'"><a href="'.WEB_URL.'modules/download/download.php?id='.$uid.'" target="download" title="{LNG_Download}">';
                $content .= '<img src="'.$img.'" alt="'.$match[3].'" alt="{LNG_Download}">';
                $content .= '<span>{LNG_Download}</span>';
                $content .= '</a>';
                if ($owner_id > 0) {
                    $content .= '<a class="icon-delete" id=download_delete_'.$uid.' title="{LNG_Delete}"></a>';
                }
                $content .= '</div>';
            }
        }
        $content .= '</div>';
        if ($owner_id > 0) {
            $content .= '<script>initDownload("'.$elem.'")</script>';
        }

        return $content;
    }
}
