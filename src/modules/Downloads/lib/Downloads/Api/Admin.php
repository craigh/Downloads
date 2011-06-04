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
class Downloads_Api_Admin extends Zikula_AbstractApi
{

    /**
     * Get available admin panel links
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
                'text' => $this->__('Settings'),
                'class' => 'z-icon-es-config');
        }

        if (SecurityUtil::checkPermission('Downloads::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url('Downloads', 'admin', 'main'),
                'text' => $this->__('File List'),
                'class' => 'z-icon-es-view');
        }

        $links[] = array(
            'url' => ModUtil::url('Downloads', 'admin', 'edit'),
            'text' => $this->__('New download'),
            'class' => 'z-icon-es-new');

        return $links;
    }

}