<?php

/**
 * Description of Edit
 *
 * @author craig
 */
class Downloads_Form_Handler_User_Edit extends Zikula_Form_AbstractHandler
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
        // load and assign registred categories
        $registryCategories = CategoryRegistryUtil::getRegisteredModuleCategories('Downloads', 'downloads_downloads');
        $categories = array();
        foreach ($registryCategories as $property => $cid) {
            $categories[$property] = (int)$cid;
        }

        $view->assign('registries', $categories);

        $id = FormUtil::getPassedValue('id', null, 'GET', FILTER_SANITIZE_NUMBER_INT);
        if ($id) {
            // load record with id
            $file = Doctrine_Core::getTable('Downloads_Model_Download')->find($id);

            if ($file) {
                // switch to edit mode
                $this->id = $id;
                // assign current values to form fields
                $view->assign($file->toArray());
            } else {
                return LogUtil::registerError($this->__f('File with id %s not found', $id));
            }
        }

        if (!$view->getStateData('returnurl')) {
			$editurl = ModUtil::url('Downloads', 'user', 'edit');
            $returnurl = System::serverGetVar('HTTP_REFERER');
            if (strpos($returnurl, $editurl) === 0) {
                $returnurl = ModUtil::url('Downloads', 'user', 'view');
			}
            $view->setStateData('returnurl', $returnurl);
        }

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

        // check for valid form
        if (!$view->isValid()) {
            return false;
        }

        // load form values
        $data = $view->getValues();

        // validate that url or file is passed
        if (!$data['url'] && !$data['filename']['size']) {
            $plugin = $view->getPluginById('filename');
            $plugin->setError($this->__('You must upload a filename'));
            $plugin = $view->getPluginById('url');
            $plugin->setError($this->__('OR specify a download url.'));
            return false;
        }

        // format data as required for table
        // shouldn't this be automatic?
        // Mateo: if using standard fields
        $data['update'] = date("Y-m-d H:i:s");
        $data['date'] = date("Y-m-d H:i:s");

        if ($data['filename']['size'] > 0) {
            $data['filesize'] = $data['filename']['size'];
            FileUtil::uploadFile($data['filename']['tmp_name'], 'data', $data['filename']['name']);
            $name = $data['filename']['name'];
            unset($data['filename']);
            $data['filename'] = $name;
        } else {
            $data['filename'] = '';
        }

        // switch between edit and create mode
        if ($this->id) {
            $file = Doctrine_Core::getTable('Downloads_Model_Download')->find($this->id);
        } else {
            $file = new Downloads_Model_Download();
        }

        $file->merge($data);
        
        try {
            $file->save();
        } catch (Exception $e) {
            echo "<pre>"; var_dump($data);
            logUtil::registerError(__('Save failed!'));
            die;
        }

        return $view->redirect($returnurl);
    }

}
