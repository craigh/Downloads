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
    /**
     * Download Item status
     */

    const STATUS_ALL = -1;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * get downloads filtered as requested
     * @param type $args
     * @return Doctrine_Collection object
     */
    public function getall($args)
    {
        // declare args
        $category = isset($args['category']) ? $args['category'] : 0;
        $startnum = isset($args['startnum']) ? $args['startnum'] : 0;
        $orderby = isset($args['orderby']) ? $args['orderby'] : 'title';
        $orderdir = isset($args['orderdir']) ? $args['orderdir'] : 'ASC';
        $limit = isset($args['limit']) ? $args['limit'] : $this->getVar('perpage');
        $status = isset($args['status']) ? $args['status'] : self::STATUS_ACTIVE;

        $tbl = Doctrine_Core::getTable('Downloads_Model_Download');
        $q = $tbl->createQuery('d')
                ->expireQueryCache()
                ->expireResultCache()
                ->orderBy("$orderby $orderdir")
                ->offset($startnum);
        if (!empty($category)) {
            $q->where("d.cid = ?", $category);
        }
        if (($status == self::STATUS_ACTIVE) || ($status == self::STATUS_INACTIVE)) {
            $q->andWhere("d.status = ?", $status);
        }
        if ($limit > 0) {
            $q->limit($limit);
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

    /**
     * cound the number of results in the query
     * @param array $args
     * @return integer
     */
    public function countQuery($args)
    {
        $args['limit'] = -1;
        $items = $this->getall($args);
        return count($items);
    }

    public function getSubCategories($args)
    {
        $category = isset($args['category']) ? $args['category'] : 0;

        $tbl = Doctrine_Core::getTable('Downloads_Model_Categories');
        $subcategories = $tbl->findBy('pid', $category);

        foreach ($subcategories as $key => $subcategory) {
            if (!SecurityUtil::checkPermission('Downloads::Category', $subcategory['cid'] . '::', ACCESS_READ)) {
                unset($subcategories[$key]);
            }
        }

        return $subcategories;
    }

    /**
     * Clear cache for given item. Can be called from other modules to clear an item cache.
     *
     * @param $item - the item: array with data or id of the item
     */
    public function clearItemCache($item)
    {
        if ($item && !is_array($item)) {
            $item = Doctrine_Core::getTable('Downloads_Model_Download')->find($item);
            if ($item) {
                $item = $item->toArray();
            }
        }
        if ($item) {
            // Clear View_cache
            $cache_ids = array();
            $cache_ids[] = 'display|lid_' . $item['lid'];
            $cache_ids[] = 'view|cid_' . $item['cid'];
            $view = Zikula_View::getInstance('Downloads');
            foreach ($cache_ids as $cache_id) {
                $view->clear_cache(null, $cache_id);
            }

            // clear Theme_cache
            $cache_ids = array();
            $cache_ids[] = 'Downloads|user|display|lid_' . $item['lid'];
            $cache_ids[] = 'Downloads|user|view|category_' . $item['cid']; // view function (item list by category)
            $cache_ids[] = 'homepage'; // for homepage (it can be adjustment in module settings)
            $theme = Zikula_View_Theme::getInstance();
            //if (Zikula_Core::VERSION_NUM > '1.3.2') {
            if (method_exists($theme, 'clear_cacheid_allthemes')) {
                $theme->clear_cacheid_allthemes($cache_ids);
            } else {
                // clear cache for current theme only
                foreach ($cache_ids as $cache_id) {
                    $theme->clear_cache(null, $cache_id);
                }
            }
        }
    }

}