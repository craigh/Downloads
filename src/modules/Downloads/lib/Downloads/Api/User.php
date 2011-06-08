<?php

/**
 * Downloads
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control User interface
 */
class Downloads_Api_User extends Zikula_AbstractApi
{

    public function getall($args)
    {
        // declare args
        $category = isset($args['category']) ? $args['category'] : 0;
        $startnum = isset($args['startnum']) ? $args['startnum'] : 0;
        $orderby = isset($args['orderby']) ? $args['orderby'] : 'title';
        $orderdir = isset($args['orderdir']) ? $args['orderdir'] : 'ASC';
        $limit = isset($args['limit']) ? $args['limit'] : $this->getVar('perpage');

        $tbl = Doctrine_Core::getTable('Downloads_Model_Download');
        $q = $tbl->createQuery('d')
                ->expireQueryCache()
                ->expireResultCache()
                ->orderBy("$orderby $orderdir")
                ->offset($startnum);
        if ($limit > 0) {
            $q->limit($limit);
        }
        if (!empty($category)) {
            $q->where("d.cid = ?", $category);
        }
        $downloads = $q->execute();

        foreach ($downloads as $key => $download) {
            if ((!SecurityUtil::checkPermission('Downloads::Item', $download['lid'] . '::', ACCESS_READ)) ||
                (!SecurityUtil::checkPermission('Downloads::Category', $download['cid'] . '::', ACCESS_READ))) {
                unset($downloads[$key]);
            }
        }
        return $downloads;
    }

    public function countQuery($args)
    {
        $args['limit'] = -1;
        $items = $this->getall($args);
        return count($items);
    }

}