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
class Downloads_Api_User extends Zikula_AbstractApi
{
    /**
     * Get available user links
     *
     * @return array array of admin links
     */
    public function getlinks()
    {
        // Define an empty array to hold the list of admin links
        $links = array();
        
        if (SecurityUtil::checkPermission('Downloads::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url('Downloads', 'admin', 'modifyconfig'),
                'text' => $this->__('Admin'),
                'class' => 'z-icon-es-config');
        }
        
        $links[] = array(
            'url' => ModUtil::url('Downloads', 'user', 'view'),
            'text' => $this->__('Download List'),
            'class' => 'z-icon-es-list');

        $links[] = array(
            'url' => ModUtil::url('Downloads', 'user', 'edit'),
            'text' => $this->__('New download'),
            'class' => 'z-icon-es-new');
                            
        return $links;
    }
} // end class def