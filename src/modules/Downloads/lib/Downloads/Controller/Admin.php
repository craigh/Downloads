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
     * This method provides a generic item list overview.
     *
     * @return string|boolean Output.
     */
    public function main()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Downloads::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        // initialize sort array - used to display sort classes and urls
        $sort = array();
        $fields = array('title', 'submitter'); // possible sort fields
        foreach ($fields as $field) {
            $sort['class'][$field] = 'z-order-unsorted'; // default values
        }

        // Get parameters from whatever input we need.
        $startnum = (int)$this->request->getGet()->get('startnum', $this->request->getPost()->get('startnum', isset($args['startnum']) ? $args['startnum'] : null));
        $orderby = $this->request->getGet()->get('orderby', $this->request->getPost()->get('orderby', isset($args['orderby']) ? $args['orderby'] : 'title'));
        $original_sdir = $this->request->getGet()->get('sdir', $this->request->getPost()->get('sdir', isset($args['sdir']) ? $args['sdir'] : 0));
        $category = $this->request->getPost()->get('category', $this->request->getGet()->get('category', isset($args['category']) ? $args['category'] : 0));

        $this->view->assign('startnum', $startnum);
        $this->view->assign('orderby', $orderby);
        $this->view->assign('sdir', $original_sdir);
        $this->view->assign('rowcount', ModUtil::apiFunc('Downloads', 'user', 'countQuery', array('category' => $category)));
        $this->view->assign('catselectoptions', Downloads_Util::getCatSelectList(array('sel' => $category, 'includeall' => true)));
        $this->view->assign('cid', $category);
        $this->view->assign('filter_active', false);

        $sdir = $original_sdir ? 0 : 1; //if true change to false, if false change to true
        // change class for selected 'orderby' field to asc/desc
        if ($sdir == 0) {
            $sort['class'][$orderby] = 'z-order-desc';
            $orderdir = 'DESC';
        }
        if ($sdir == 1) {
            $sort['class'][$orderby] = 'z-order-asc';
            $orderdir = 'ASC';
        }
        // complete initialization of sort array, adding urls
        foreach ($fields as $field) {
            $sort['url'][$field] = ModUtil::url('Downloads', 'admin', 'main', array(
                        'orderby' => $field,
                        'sdir' => $sdir,
                        'category' => $category));
        }
        $this->view->assign('sort', $sort);
        $this->view->assign('filter_active', (empty($category)) ? false : true);

        $downloads = ModUtil::apiFunc('Downloads', 'user', 'getall', array(
                    'startnum' => $startnum,
                    'orderby' => $orderby,
                    'orderdir' => $orderdir,
                    'category' => $category,
                ));

        return $this->view->assign('downloads', $downloads)
                          ->fetch('admin/main.tpl');
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
        $currentModVars = $this->getVars();
        $defaults = array_merge($defaults, $currentModVars);

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
     * Create or edit record.
     *
     * @return string|boolean Output.
     */
    public function edit()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Downloads::', '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());

        $form = FormUtil::newForm('Downloads', $this);
        return $form->execute('admin/edit.tpl', new Downloads_Form_Handler_Admin_Edit());
    }

    public function categoryList()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Downloads::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $cats = Doctrine_Core::getTable('Downloads_Model_Categories')->findAll()->toArray();
        // sort array by title
        $title = array();
        foreach ($cats as $key => $cat) {
            $title[$key] = $cat['title'];
        }
        array_multisort($title, SORT_ASC, $cats);

        return $this->view->assign('cats', $cats)
                          ->fetch('admin/categories.tpl');
    }

    public function editCategory()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Downloads::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $form = FormUtil::newForm('Downloads', $this);
        return $form->execute('admin/editcategory.tpl', new Downloads_Form_Handler_Admin_EditCategory());
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