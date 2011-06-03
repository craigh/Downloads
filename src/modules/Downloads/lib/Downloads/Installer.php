<?php
/**
 * Copyright Craig Heydenburg 2010 - Downloads
 *
 * Downloads
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Installer interface
 */
class Downloads_Installer extends Zikula_AbstractInstaller
{
    /**
     * Initializes a new install
     *
     * This function will initialize a new installation.
     * It is accessed via the Zikula Admin interface and should
     * not be called directly.
     *
     * @return  boolean    true/false
     */
    public function install()
    {
        // create the table
        try {
            DoctrineUtil::createTablesFromModels('Downloads');
            // create category relationship
            $rootcat = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules/Global');
            if ($rootcat) {
                CategoryRegistryUtil::insertEntry ('Downloads', 'downloads_downloads', 'Main', $rootcat['id']);
            }
        } catch (Exception $e) {
            return false;
        }
        
        // Set up config variables
        $this->setVars(Downloads_Util::getModuleDefaults());
        $this->createUploadDir();

        return true;
    }
    
    /**
     * Upgrades an old install
     *
     * This function is used to upgrade an old version
     * of the module.  It is accessed via the Zikula
     * Admin interface and should not be called directly.
     *
     * @param   string    $oldversion Version we're upgrading
     * @return  boolean   true/false
     */
    public function upgrade($oldversion)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Downloads::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
    
        switch ($oldversion) {
            case '2.4':
            case '2.4.0':
                // upgrade from old module
                // convert modvars from yes|no to true|false && be sure all defaults are set
                $oldVars = ModUtil::getVar('downloads'); // must use lowercase name here for old mod
                $checkedVars = $this->getCheckedVars();
                $defaultVars = Downloads_Util::getModuleDefaults();
                $newVars = array();
                $this->delVars(); // delete any with current modname
                ModUtil::delVar('downloads'); // again use lowercase for old mod
                foreach ($defaultVars as $var => $val) {
                    if (isset($oldVars[$var])) {
                        if (in_array($var, $checkedVars)) {
                            // update value to boolean
                            $newVars[$var] = ($oldVars[$var] == 'yes') ? true : false;
                        } else {
                            // use old value
                            $newVars[$var] = $oldVars[$var];
                        }
                    } else {
                        // not set
                        $newVars[$var] = $val;
                    }
                }
                $this->setVars($newVars);

                // convert categories to zikula categories
                
                // drop old categories table and old modrequest table
                DoctrineUtil::dropTable('downloads_categories');
                DoctrineUtil::dropTable('downloads_modrequest');
                
            case '3.0.0':
                //future development
        }
    
        return true;
    }
    
    /**
     * removes an install
     *
     * This function removes the module from your
     * Zikula install and should be accessed via
     * the Zikula Admin interface
     *
     * @return  boolean    true/false
     */
    public function uninstall()
    {
        // drop table
        DoctrineUtil::dropTable('downloads_downloads');
        
        // Delete entries from category registry
        CategoryRegistryUtil::deleteEntry('Downloads');
        // clean up associated categories
        DBUtil::deleteWhere('categories_mapobj', "cmo_modname='Downloads'");
        
        //remove files from data folder
        $uploaddir = DataUtil::formatForOS($this->getVar('upload_folder'));
        FileUtil::deldir($uploaddir, true);
        
        // remove all module vars
        $this->delVars();

        return true;
    }
    
    /**
     * Upload directory creation
     */
    private function createUploadDir()
    {
        // upload dir creation
        $uploaddir = $this->getVar('upload_folder');

        if (mkdir($uploaddir, System::getVar('system.chmod_dir', 0777), true)) {
            LogUtil::registerStatus($this->__f('Created the file storage directory successfully at [%s]. Be sure that this directory is accessible via web and writable by the webserver.', $uploaddir));
        }

        return $uploaddir;
    }
    
    /**
     * List of ModVars that previously (<= v2.4) were stored as strings: Yes/No
     * @return array
     */
    private function getCheckedVars()
    {
        return array(
            'ratexdlsactive',
            'topxdlsactive',
            'lastxdlsactive',
            'allowupload',
            'securedownload',
            'sizelimit',
            'showscreenshot',
            'limit_extension',
            'allowscreenshotupload',
            'frontpagesubcats',
            'sessionlimit',
            'inform_user',
            'torrent', //
        );
    }
} // end class def