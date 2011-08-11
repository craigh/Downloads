<?php
/**
 * Downloads
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

function smarty_modifier_getcategorynamefromid($id)
{
    $dom    = ZLanguage::getModuleDomain('Downloads');
    $id = (int)$id;
    if ($id == 0) {
        return __('Root', $dom);
    }
    $category = Doctrine_Core::getTable('Downloads_Model_Categories')->find($id)->toArray();
    return $category['title'];
}