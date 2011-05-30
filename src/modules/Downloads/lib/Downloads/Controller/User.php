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
        if (!SecurityUtil::checkPermission('Downloads::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError(ModUtil::url('Downloads', 'user', 'main'));
        }

        $downloads = Doctrine_Core::getTable('Downloads_Model_Download')->findAll();

        return $this->view->assign('downloads', $downloads)
                          ->fetch('user/view.tpl');
    }
    
    /**
     * Create or edit record.
     *
     * @return string|boolean Output.
     */
    public function edit()
    {
        if (!SecurityUtil::checkPermission('Downloads::', '::', ACCESS_ADD)) {
            return LogUtil::registerPermissionError(ModUtil::url('Downloads', 'user', 'main'));
        }

        $form = FormUtil::newForm('Downloads', $this);
        return $form->execute('user/edit.tpl', new Downloads_Form_Handler_User_Edit());
    }
    
} // end class def