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
    public function getall($args)
    {
        // declare args
        $category = isset($args['category']) ? $args['category'] : 0;
        $startnum = isset($args['startnum']) ? $args['startnum'] : 0;
        $orderby = isset($args['orderby']) ? $args['orderby'] : 'title';
        $orderdir = isset($args['orderdir']) ? $args['orderdir'] : 'ASC';
        $limit = $this->getVar('perpage');
        
        $tbl = Doctrine_Core::getTable('Downloads_Model_Download');
        if (!empty($category)) {
            $downloads = $tbl->createQuery('d')
                ->expireQueryCache()
                ->expireResultCache()
                ->orderBy("$orderby $orderdir")
                ->offset($startnum)
                ->limit($limit)
                ->where("d.cid=$category")
                ->execute();
        } else {
            $downloads = $tbl->createQuery('d')
                ->expireQueryCache()
                ->expireResultCache()
                ->orderBy("$orderby $orderdir")
                ->offset($startnum)
                ->limit($limit)
                ->execute();
        }
        return $downloads;
    }
    
    public function countQuery($args)
    {
        $category = isset($args['category']) ? $args['category'] : 0;
        if (!empty($category)) {
            $count = Doctrine_Core::getTable('Downloads_Model_Download')
                    ->createQuery()
                    ->expireQueryCache()
                    ->expireResultCache()
                    ->where("cid=$category")
                    ->count();
        } else {
            $count = Doctrine_Core::getTable('Downloads_Model_Download')
                    ->createQuery()
                    ->expireQueryCache()
                    ->expireResultCache()
                    ->count();
        }
        return $count;
    }
} // end class def