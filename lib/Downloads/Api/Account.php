<?php
/**
 * Downloads
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to display Dowloads-link at the users dashboard
 */
class Downloads_Api_Account extends Zikula_AbstractApi
{
	/**
	 * @brief get all user page items
	 * @return $items
	 *
	 * @author Leonard Marschke
	 * @version 1.0
	 */
	public function getAll($args)
	{
		$items = array();

		if(SecurityUtil::checkPermission('Downloads::', '::', ACCESS_ADD))
		{
			$items[] = array(
				'url'   => ModUtil::url($this->name, 'user', 'view'),
				'module'=> $this->name,
				'title' => $this->__('Downloads'),
				'icon'  => 'folder.png'
			);
			
		}

		return $items;
	}
}
