<?php

/**
 * Downloads
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Version information
 */
class Downloads_Version extends Zikula_AbstractVersion
{

    public function getMetaData()
    {
        $meta = array();
        $meta['displayname'] = $this->__('Downloads');
        $meta['url'] = $this->__(/* !used in URL - nospaces, no special chars, lcase */'downloads');
        $meta['description'] = $this->__('Zikula file download manager');
        $meta['version'] = '3.0.0';

        $meta['securityschema'] = array('Downloads::' => '::',
            'Downloads::Category' => 'CategoryID::',
            'Downloads::Item' => 'ID::');
        $meta['core_min'] = '1.3.0'; // requires minimum 1.3.0 or later

        return $meta;
    }

}