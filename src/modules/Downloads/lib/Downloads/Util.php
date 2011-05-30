<?php

/**
 * Copyright Craig Heydenburg 2010 - Downloads
 *
 * Downloads
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * External Util class for example
 */
class Downloads_Util
{

    public static function getModuleDefaults()
    {
        return array(
            'perpage' => 10,
            'newdownloads' => 5,
            'topdownloads' => 5,
            'ratexdlsamount' => 5,
            'topxdlsamount' => 5,
            'lastxdlsamount' => 5,
            'ratexdlsactive' => false, //'no',
            'topxdlsactive' => false, //'no',
            'lastxdlsactive' => false, //'no',
            'allowupload' => true, //'yes',
            'securedownload' => true, //'yes',
            'sizelimit' => true, //'yes',
            'limitsize' => '5-Mb',
            'showscreenshot' => true, //'yes',
            'thumbnailwidth' => 100,
            'thumbnailheight' => 100,
            'screenshotmaxsize' => '2-Mb',
            'thumbnailmaxsize' => '1-Mb',
            'limit_extension' => true, //'yes',
            'allowscreenshotupload' => true, //'yes',
            'importfrommod' => false, //0,
            'sessionlimit' => false, //'no',
            'sessiondownloadlimit' => 8,
            'captchacharacters' => 5,
            'notifymail' => ' ',
            'inform_user' => true, //'yes',
            'gd' => extension_loaded('gd'),
            'securedownload' => extension_loaded('gd') ? null : 'no',
            'fulltext' => false,
            'sortby' => 'title',
            'cclause' => 'ASC',
            'popular' => 10,
            'torrent' => false,
            'upload_folder' => '',
            'screenshot_folder' => '',
            'cache_folder' => '',
            'treeview' => false,
        );
    }

}