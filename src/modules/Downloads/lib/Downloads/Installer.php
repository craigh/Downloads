<?php

/**
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
        } catch (Exception $e) {
            return false;
        }

        // Set up config variables
        $this->setVars(Downloads_Util::getModuleDefaults());
        $this->createUploadDir();
        $cid = $this->createSampleCategory();
        $this->createSampleDownload($cid);

        HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());

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
                if (substr($newVars['upload_folder'], -1) == '/') {
                    // remove trailing slash
                    $newVars['upload_folder'] = substr($newVars['upload_folder'], 0, -1);
                }
                $this->setVars($newVars);

                $prefix = $this->serviceManager['prefix'];
                $connection = Doctrine_Manager::getInstance()->getConnection('default');
                $sqlStatements = array();
                // N.B. statements generated with PHPMyAdmin
                $sqlStatements[] = 'RENAME TABLE ' . $prefix . '_downloads_downloads' . " TO downloads_downloads";
                // note: because 'update' and 'date' are reserved SQL words, the fields are renamd to uupdate and ddate, respectively
                $sqlStatements[] = "ALTER TABLE `downloads_downloads` 
CHANGE `pn_lid` `lid` INT(11) NOT NULL AUTO_INCREMENT, 
CHANGE `pn_cid` `cid` INT(11) NOT NULL DEFAULT '0', 
CHANGE `pn_status` `status` SMALLINT(6) NOT NULL DEFAULT '0', 
CHANGE `pn_update` `uupdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00', 
CHANGE `pn_title` `title` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '', 
CHANGE `pn_url` `url` VARCHAR(254) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '', 
CHANGE `pn_filename` `filename` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
CHANGE `pn_description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, 
CHANGE `pn_date` `ddate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00', 
CHANGE `pn_email` `email` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '', 
CHANGE `pn_hits` `hits` INT(11) NOT NULL DEFAULT '0', 
CHANGE `pn_submitter` `submitter` VARCHAR(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
CHANGE `pn_filesize` `filesize` double NOT NULL DEFAULT '0', 
CHANGE `pn_version` `version` VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '', 
CHANGE `pn_homepage` `homepage` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '', 
CHANGE `pn_modid` `modid` INT(11) NOT NULL DEFAULT '0', 
CHANGE `pn_objectid` `objectid` VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0'";
                $sqlStatements[] = "ALTER TABLE `downloads_downloads` 
DROP INDEX `pn_title`, 
ADD FULLTEXT `title` (`title`, `description`)";
                $sqlStatements[] = 'RENAME TABLE ' . $prefix . '_downloads_categories' . " TO downloads_categories";
                $sqlStatements[] = "ALTER TABLE `downloads_categories` 
CHANGE `pn_cid` `cid` INT( 11 ) NOT NULL AUTO_INCREMENT ,
CHANGE `pn_pid` `pid` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `pn_title` `title` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0',
CHANGE `pn_description` `description` VARCHAR( 254 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''";
                foreach ($sqlStatements as $sql) {
                    $stmt = $connection->prepare($sql);
                    try {
                        $stmt->execute();
                    } catch (Exception $e) {
                    }   
                }
                // update url field for each row to include upload dir
                // and rename from ID to name
                $this->updateRows();

                // drop old modrequest table
                DoctrineUtil::dropTable($prefix . '_downloads_modrequest');

                HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());

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
        DoctrineUtil::dropTable('downloads_categories');

        //remove files from data folder
        $uploaddir = DataUtil::formatForOS($this->getVar('upload_folder'));
        FileUtil::deldir($uploaddir, true);

        // remove all module vars
        $this->delVars();
        
        HookUtil::unregisterSubscriberBundles($this->version->getHookSubscriberBundles());

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

    /**
     * Create a sample category
     * @return int created category id
     */
    private function createSampleCategory()
    {
        $data = array(
            "title" => $this->__("SampleCategory"),
            "description" => $this->__("This category is provided as a sample and can be safely deleted."),
            "pid" => 0
        );

        $cat = new Downloads_Model_Categories();
        $cat->merge($data);
        $cat->save();
        return $cat['cid'];
    }

    /**
     * Create a sample download
     * @param type $cid 
     */
    private function createSampleDownload($cid)
    {
        $data = array(
            "title" => $this->__("Sample download"),
            "filename" => "",
            "status" => 1,
            "url" => "modules/Downloads/docs/en/sampledownload.txt",
            "description" => $this->__("This file is provided as a sample and this entry can be safely deleted."),
            "submitter" => "admin",
            "email" => "",
            "homepage" => "",
            "version" => "1",
            "cid" => $cid,
            "update" => date("Y-m-d H:i:s"),
            "date" => date("Y-m-d H:i:s"),
        );

        $file = new Downloads_Model_Download();
        $file->merge($data);
        $file->save();
    }

    /**
     * Update all rows so url is properly formatted
     */
    private function updateRows()
    {
        $path = $this->getVar('upload_folder');
        $tbl = Doctrine_Core::getTable('Downloads_Model_Download');
        $rows = $tbl->findAll()->toArray();
        foreach ($rows as $row) {
            $parts = explode('.', $row['url']);
            if (is_numeric($parts[0]) && ((int)$parts[0] == (int)$row['lid'])) {
                // this section renames numeric filenames to strings and attempts to
                // rename the actual file it also corrects the url for local files
                $filenameParts = explode('.', $row['filename']);
                $newname = DataUtil::formatForURL($filenameParts[0]) . '.' . array_pop($filenameParts);
                $newurl = "$path/$newname";
                $oldurl = $path . '/' . $row['url'];
                if (@rename(DataUtil::formatForOS($oldurl), DataUtil::formatForOS($newurl))) {
                    // update DB
                    $tbl->createQuery()
                            ->update()
                            ->set('url', '?', $newurl)
                            ->set('filename', '?', $newname)
                            ->where('lid = ?', $row['lid'])
                            ->execute();
                } else {
                    // update DB
                    $tbl->createQuery()
                            ->update()
                            ->set('url', '?', $oldurl)
                            ->set('filename', '?', $newname)
                            ->where('lid = ?', $row['lid'])
                            ->execute();
                }
            } else {
                // this section simply renames filenames to only have one extension
                // old versions of the module somehow added double extensions
                $filenameParts = explode('.', $row['filename']);
                if (count($filenameParts) > 2) {
                    $newname = DataUtil::formatForURL($filenameParts[0]) . '.' . array_pop($filenameParts);
                    // update DB
                    $tbl->createQuery()
                            ->update()
                            ->set('filename', '?', $newname)
                            ->where('lid = ?', $row['lid'])
                            ->execute();
                }
            }
        }
    }

}

// end class def