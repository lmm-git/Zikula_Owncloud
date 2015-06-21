<?php

/**
 * ownCloud - Zikula Authentification Backend
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Zikula_Auth;

require_once 'zikula_auth/lib/zikulaconnect.php';

/**
* @brief Class providing Zikula groups to ownCloud
*/
class Group extends \OC_Group_Backend {
	/**
	 * provides the settings of the module
	 */
	private $settings;

	/**
	 * provides zikula connect
	 */
	private $zikulaConnect;

	/**
	 * @brief instantiate the new class
	 * @param $settingsDriver settings class
	 * @return void
	 *
	 * Initialise the new class.
	 */
	public function __construct($settingsDriver) {
		$this->settings = $settingsDriver;
		$this->zikulaConnect = new ZikulaConnect($settingsDriver);
	}

	/**
	 * @brief Try to create a new group
	 * @param $gid The name of the group to create
	 * @return true/false
	 *
	 * Trys to create a new group. If the group name already exists, false will
	 * be returned.
	 */
	public static function createGroup($gid) {
		\OC_Log::write('OC_Group_Zikula', 'Use the zikula webinterface to create groups',3);
		return OC_USER_BACKEND_NOT_IMPLEMENTED;
	}

	/**
	 * @brief delete a group
	 * @param $gid gid of the group to delete
	 * @return true/false
	 *
	 * Deletes a group and removes it from the group_user-table
	 */
	public function deleteGroup($gid) {
		\OC_Log::write('OC_Group_Zikula', 'Use the zikula webinterface to delete groups',3);
		return OC_USER_BACKEND_NOT_IMPLEMENTED;
	}

	/**
	 * @brief is user in group?
	 * @param $uid uid of the user
	 * @param $gid gid of the group
	 * @return true/false
	 *
	 * Checks whether the user is member of a group or not.
	 */
	public function inGroup($uid, $gid) {
		return $this->zikulaConnect->fetch('userInGroup', array('user' => $uid, 'group' => $gid));
	}

	/**
	 * check if a group exists
	 * @param string $gid
	 * @return bool
	 *
	 * Checks whether a group exists in Zikula
	 */
	public function groupExists($gid) {
		return $this->zikulaConnect->fetch('groupExists', array('group' => $gid));
	}

	/**
	 * @brief Add a user to a group
	 * @param $uid Name of the user to add to group
	 * @param $gid Name of the group in which add the user
	 * @return true/false
	 *
	 * Adds a user to a group.
	 */
	public function addToGroup($uid, $gid) {
		\OC_Log::write('OC_Group_Zikula', 'Use the zikula webinterface to add users to groups',3);
		return OC_USER_BACKEND_NOT_IMPLEMENTED;
	}

	/**
	 * @brief Removes a user from a group
	 * @param $uid Name of the user to remove from group
	 * @param $gid Name of the group from which remove the user
	 * @return true/false
	 *
	 * removes the user from a group.
	 */
	public function removeFromGroup( $uid, $gid ) {
		\OC_Log::write('OC_Group_Zikula', 'Use the zikula webinterface to remove users from groups',3);
		return OC_USER_BACKEND_NOT_IMPLEMENTED;
	}

	/**
	 * @brief Get all groups a user belongs to
	 * @param $uid Name of the user
	 * @return array with group names
	 *
	 * This function fetches all groups a user belongs to. It does not check
	 * if the user exists at all.
	 */
	public function getUserGroups($uid) {
		$result = $this->zikulaConnect->fetch('getUserGroups', array('user' => $uid));
		if(!is_array($result)) {
			$result = array();
		}
		return $result;
	}

	/**
	 * @brief get a list of all groups
	 * @return array with group names
	 *
	 * Returns a list with all groups
	 */
	public function getGroups($search = '', $limit = -1, $offset = 0) {
		$return = $this->zikulaConnect->fetch('getGroups', array('search' => $search, 'offset' => $offset, 'limit' => $limit));
		return $return;
	}

	/**
	 * @brief get a list of all users in a group
	 * @return array with user ids
	 */
	public function usersInGroup($gid, $search = '', $limit = -1, $offset = 0) {
		return $this->zikulaConnect->fetch('getUsersInGroup', array('group' => $gid, 'search' => $search, 'limit' => $limit, 'offset' => $offset));
	}
}
