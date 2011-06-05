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
 * Class to control User interface
 */
class Downloads_Controller_User extends Zikula_AbstractController
{

    /**
     * main
     *
     * main view function for end user
     * @access public
     */
    public function main()
    {
        $this->redirect(ModUtil::url('Downloads', 'user', 'view'));
    }

    /**
     * This method provides a generic item list overview.
     *
     * @return string|boolean Output.
     */
    public function view()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Downloads::', '::', ACCESS_READ), LogUtil::getErrorMsgPermission());


        // initialize sort array - used to display sort classes and urls
        $sort = array();
        $fields = array('title', 'submitter', 'hits'); // possible sort fields
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
            $sort['url'][$field] = ModUtil::url('Downloads', 'user', 'view', array(
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
                ->fetch('user/view.tpl');
    }

    public function prepHandOut($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Downloads::', '::', ACCESS_READ), LogUtil::getErrorMsgPermission());

        $lid = (int)$this->request->getGet()->get('lid', null);

        // if admin limits session downloads, enforce
        if ($this->getVar('sessionlimit')) {
            $dlcount = SessionUtil::getVar('dlcount', 0);
            if (empty($dlcount)) {
                $dlcount = 1;
                SessionUtil::setVar('dlcount', $dlcount);
            } else {
                $dlcount = ++$dlcount;
                SessionUtil::setVar('dlcount', $dlcount);
            }

            // if limit not reached, download, else redirect
            if ($dlcount < $this->getVar('sessiondownloadlimit')) {
                $this->handoutFile(array('lid' => $lid));
            } else {
                $this->redirect(ModUtil::url('Downloads', 'user', 'main'));
            }
        } else {
            $this->handoutFile(array('lid' => $lid));
        }

        return true;
    }

    /**
     * Output file to browser
     * most of method taken from original Downloads module (with refactoring)
     * @param type $args
     * @return type 
     */
    public function handoutFile($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Downloads::', '::', ACCESS_READ), LogUtil::getErrorMsgPermission());

        if (!isset($args['lid']) || !is_numeric($args['lid'])) {
            return LogUtil::registerArgsError(ModUtil::url('Downloads', 'user', 'main'));
        }

        $tbl = Doctrine_Core::getTable('Downloads_Model_Download');
        $myfile = $tbl->find($args['lid']);

        if (stristr($myfile['url'], 'http:') || stristr($myfile['url'], 'ftp:') || stristr($myfile['url'], 'https:')) {
            // increment hit count
            $tbl->createQuery()
                ->update()
                ->set('hits', 'hits + 1')
                ->where('lid = ?', $args['lid'])
                ->execute();

            // redirect to external link 
            $this->redirect($myfile['url']);
        } else {
            // file is local
            $fileinfo = pathinfo($myfile['url']);
            $filename = $fileinfo['basename'];

            // check for existance						
            $filepointer = is_file($myfile['url']);

            // last file type check
            if ($filepointer && !preg_match('.php[[:space:]]*$', $filename)) {

                // increment hit count
                $tbl->createQuery()
                    ->update()
                    ->set('hits', 'hits + 1')
                    ->where('lid = ?', $args['lid'])
                    ->execute();

                // get file size
                $fsize = filesize($myfile['url']);

                if ($fsize == false) {
                    throw new Zikula_Exception_Fatal($this->__f('Error! Could not determine filesize for %s.', $filename));
                }

                // get the right content type
                $contenttype = Downloads_Util::getContentType(array('extension' => $fileinfo['extension']));

                // remove bad characters from title in a cross-platform manner by replacing 
                // the union of characters not allowed by *nix, Mac and Windows (which is the most restrictive)
                // with an underscore.
                // use DataUtil::formatForURL() here?
                $myfile['filename'] = preg_replace('![\x00-\x1f\x7f*:\\\\/<>|"?]!', '_', $myfile['filename']);

                $UseCompression = System::getVar('UseCompression');

                if ($UseCompression == 1) {
                    ob_end_clean();
                }

                // write header
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Cache-Control: private", false);
                header('Content-Description: File Transfer');
                header("Content-Type:$contenttype");
                // Properly quote the filename parameter per RFC2616 section 19.5.1, which allows spaces and other international characters
                header('Content-Disposition: attachment; filename="' . $myfile['filename'] . '"');
                header('Content-Transfer-Encoding: binary');
                header("Content-Length:" . $fsize);

                // wonder if readfile wouldn't be a better solution here
                // http://de.php.net/manual/en/function.readfile.php
                // 
                // prepare file and send it
                @set_time_limit(0);
                $fp = @fopen($myfile['url'], "rb");
                ini_set('magic_quotes_runtime', 0);
                $chunksize = 15 * (512 * 1024);
                while ($fp && !feof($fp)) {
                    $buffer = fread($fp, $chunksize);
                    print $buffer;
                    flush();
                    sleep(5); // is this necessary?
                }
                ini_set('magic_quotes_runtime', get_magic_quotes_gpc());
                fclose($fp);

                exit();
            } else {
                throw new Zikula_Exception_Fatal($this->__f('Error! Could not read file %s.', $filename));
            }
        }
    }

}