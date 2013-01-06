<?php

/**
 * Downloads
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */
function smarty_function_getcategoryfullpath($params, Zikula_View $view)
{
    $cid = isset($params['cid']) ? $params['cid'] : 0;
    if (is_object($cid)) {
        $cid = $cid->getCid();
    }
    $dom = ZLanguage::getModuleDomain('Downloads');
    $em = ServiceUtil::getService('doctrine.entitymanager');
    $categoryPath = array();
    $i = 1000;
    while ($cid > 0) {
        $currentElement = getParentName($em, $cid);
        $categoryPath[$i] = $currentElement;
        $cid = $currentElement['pid'];
        $i--;
    }
    $categoryPath[0] = array('cid' => 0, 'title' => __('Root', $dom));
//    echo "<pre>"; var_dump($categoryPath); die;
    ksort($categoryPath);
    $fullpath = '';
    foreach ($categoryPath as $path) {
        $fullpath .= "<a href ='" . ModUtil::url('Downloads', 'user', 'view', array('category' => $path['cid'])) . "'>$path[title]</a> / ";
    }
    return $fullpath;
}

function getParentName($em, $cid) {
    $category = $em->getRepository('Downloads_Entity_Categories')->find($cid);
    return array('cid' => $cid,
        'pid' => $category->getPid(),
        'title' => $category->getTitle());    
}