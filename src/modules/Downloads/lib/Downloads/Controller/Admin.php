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
 * Class to control Admin interface
 */
class Downloads_Controller_Admin extends Zikula_AbstractController
{

    /**
     * the main administration function
     * This function is the default function, and is called whenever the
     * module is initiated without defining arguments.
     */
    public function main()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Downloads::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        return $this->view->fetch('admin/main.tpl');
    }

    /**
     * @desc present administrator options to change module configuration
     * @return      config template
     */
    public function modifyconfig()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Downloads::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        return $this->view->fetch('admin/modifyconfig.tpl');
    }

    /**
     * @desc sets module variables as requested by admin
     * @return      status/error ->back to modify config page
     */
    public function updateconfig()
    {
        $this->checkCsrfToken();

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Downloads::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $defaults = Downloads_Util::getModuleDefaults();
        $modvars = array(
            'perpage' => $this->request->getPost()->get('perpage', $defaults['perpage']),
            'newdownloads' => $this->request->getPost()->get('newdownloads', $defaults['newdownloads']),
            'topdownloads' => $this->request->getPost()->get('topdownloads', $defaults['topdownloads']),
            'ratexdlsamount' => $this->request->getPost()->get('ratexdlsamount', $defaults['ratexdlsamount']),
            'topxdlsamount' => $this->request->getPost()->get('topxdlsamount', $defaults['topxdlsamount']),
            'lastxdlsamount' => $this->request->getPost()->get('lastxdlsamount', $defaults['lastxdlsamount']),
            'ratexdlsactive' => $this->request->getPost()->get('ratexdlsactive', $defaults['ratexdlsactive']),
            'topxdlsactive' => $this->request->getPost()->get('topxdlsactive', $defaults['topxdlsactive']),
            'lastxdlsactive' => $this->request->getPost()->get('lastxdlsactive', $defaults['lastxdlsactive']),
            'allowupload' => $this->request->getPost()->get('allowupload', $defaults['allowupload']),
            'securedownload' => $this->request->getPost()->get('securedownload', $defaults['securedownload']),
            'sizelimit' => $this->request->getPost()->get('sizelimit', $defaults['sizelimit']),
            'limitsize' => $this->request->getPost()->get('limitsize', $defaults['limitsize']),
            'showscreenshot' => $this->request->getPost()->get('showscreenshot', $defaults['showscreenshot']),
            'thumbnailwidth' => $this->request->getPost()->get('thumbnailwidth', $defaults['thumbnailwidth']),
            'thumbnailheight' => $this->request->getPost()->get('thumbnailheight', $defaults['thumbnailheight']),
            'screenshotmaxsize' => $this->request->getPost()->get('screenshotmaxsize', $defaults['screenshotmaxsize']),
            'thumbnailmaxsize' => $this->request->getPost()->get('thumbnailmaxsize', $defaults['thumbnailmaxsize']),
            'limit_extension' => $this->request->getPost()->get('limit_extension', $defaults['limit_extension']),
            'allowscreenshotupload' => $this->request->getPost()->get('allowscreenshotupload', $defaults['allowscreenshotupload']),
            'importfrommod' => $this->request->getPost()->get('importfrommod', $defaults['importfrommod']),
            'sessionlimit' => $this->request->getPost()->get('sessionlimit', $defaults['sessionlimit']),
            'sessiondownloadlimit' => $this->request->getPost()->get('sessiondownloadlimit', $defaults['sessiondownloadlimit']),
            'captchacharacters' => $this->request->getPost()->get('captchacharacters', $defaults['captchacharacters']),
            'notifymail' => $this->request->getPost()->get('notifymail', $defaults['notifymail']),
            'inform_user' => $this->request->getPost()->get('inform_user', $defaults['inform_user']),
            'fulltext' => $this->request->getPost()->get('fulltext', $defaults['fulltext']),
            'sortby' => $this->request->getPost()->get('sortby', $defaults['sortby']),
            'cclause' => $this->request->getPost()->get('cclause', $defaults['cclause']),
            'popular' => $this->request->getPost()->get('popular', $defaults['popular']),
            'torrent' => $this->request->getPost()->get('torrent', $defaults['torrent']),
            'upload_folder' => $this->request->getPost()->get('upload_folder', $defaults['upload_folder']),
            'screenshot_folder' => $this->request->getPost()->get('screenshot_folder', $defaults['screenshot_folder']),
            'cache_folder' => $this->request->getPost()->get('cache_folder', $defaults['cache_folder']),
            'treeview' => $this->request->getPost()->get('treeview', $defaults['treeview']),
        );
                    
                    
        // set the new variables
        $this->setVars($modvars);

        // clear the cache
        $this->view->clear_cache();

        LogUtil::registerStatus($this->__('Done! Updated the Downloads configuration.'));
        return $this->modifyconfig();
    }

    /**
     * @desc set caching to false for all admin functions
     * @return      null
     */
    public function postInitialize()
    {
        $this->view->setCaching(false);
    }

}