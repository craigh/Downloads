<?php

/**
 * Description of Edit
 *
 * @author craig
 */
class Downloads_Form_Handler_Admin_EditCategory extends Zikula_Form_AbstractHandler
{

    /**
     * download id.
     *
     * When set this handler is in edit mode.
     *
     * @var integer
     */
    private $id;

    /**
     * Setup form.
     *
     * @param Zikula_Form_View $view Current Zikula_Form_View instance.
     *
     * @return boolean
     */
    public function initialize(Zikula_Form_View $view)
    {
        $id = FormUtil::getPassedValue('id', null, 'GET', FILTER_SANITIZE_NUMBER_INT);
        if ($id) {
            // load record with id
            $cat = Doctrine_Core::getTable('Downloads_Model_Categories')->find($id);

            if ($cat) {
                // switch to edit mode
                $this->id = $id;
                // assign current values to form fields
                $view->assign($cat->toArray());
            } else {
                return LogUtil::registerError($this->__f('Category with id %s not found', $id));
            }
        }

        if (!$view->getStateData('returnurl')) {
			$editurl = ModUtil::url('Downloads', 'user', 'editCategory');
            $returnurl = System::serverGetVar('HTTP_REFERER');
            if (strpos($returnurl, $editurl) === 0) {
                $returnurl = ModUtil::url('Downloads', 'admin', 'categoryList');
			}
            $view->setStateData('returnurl', $returnurl);
        }
        $this->view->assign('categories', Downloads_Util::getCatSelectArray(array()));

        return true;
    }

    /**
     * Handle form submission.
     *
     * @param Zikula_Form_View $view  Current Zikula_Form_View instance.
     * @param array     &$args Args.
     *
     * @return boolean
     */
    public function handleCommand(Zikula_Form_View $view, &$args)
    {
        $returnurl = $view->getStateData('returnurl');

        // process the cancel action
        if ($args['commandName'] == 'cancel') {
            return $view->redirect($returnurl);
        }

        if ($args['commandName'] == 'delete') {
            $cat = Doctrine_Core::getTable('Downloads_Model_Categories')->find($this->id);
            $cat->delete();
            LogUtil::registerStatus($this->__f('Category [id# %s] deleted!', $this->id));
            return $view->redirect($returnurl);            
        }

        // check for valid form
        if (!$view->isValid()) {
            return false;
        }

        // load form values
        $data = $view->getValues();

        // switch between edit and create mode
        if ($this->id) {
            $cat = Doctrine_Core::getTable('Downloads_Model_Categories')->find($this->id);
        } else {
            $cat = new Downloads_Model_Categories();
        }

        $cat->merge($data);
        
        try {
            $cat->save();
        } catch (Zikula_Exception $e) {
            echo "<pre>";
            var_dump($e->getDebug());
            echo "</pre>";
            die;
        }

        return $view->redirect($returnurl);
    }

}