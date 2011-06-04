<?php

function smarty_modifier_getcategorynamefromid($id)
{
    $id = (int)$id;
    if ($id == 0) {
        return __('Root');
    }
    $category = Doctrine_Core::getTable('Downloads_Model_Categories')->find($id)->toArray();
    return $category['title'];
}