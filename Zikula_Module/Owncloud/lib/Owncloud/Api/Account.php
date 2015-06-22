<?php
/**
 * The Account API provides links for modules on the "user account page"; this class provides them for the Profile module.
 */
class Owncloud_Api_Account extends Zikula_AbstractApi
{

	/**
	 * Return an array of items to show in the "user account page".
	 *
	 * Parameters passed in the $args array:
	 * -------------------------------------
	 * string uname The user name of the user for whom links should be returned; optional, defaults to the current user.
	 *
	 * @param array $args All parameters passed to this function.
	 * @return   array   array of items, or false on failure
	 */
	public function getall($args)
	{
		$items = array();

		//is the user allowed to use the ownCloud?
		if(SecurityUtil::checkPermission('Owncloud::Use', '::', ACCESS_EDIT)) {
			$items[] = array('url'     => ModUtil::url($this->name, 'user', 'redirect'),
				'module'  => $this->name,
				'title'   => $this->__('Go to ownCloud'),
				'icon'    => 'admin.png');
		}

		// Return the items
		return $items;
	}
}
