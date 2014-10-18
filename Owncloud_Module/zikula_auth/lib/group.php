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

require_once 'zikula_auth/lib/zikulaconnect.php';

/**
* @brief Class providing Zikula groups to ownCloud
*/
class OC_GROUP_ZIKULA extends OC_Group_Backend {

	/**
	* @brief Try to create a new group
	* @param $gid The name of the group to create
	* @returns true/false
	*
	* Trys to create a new group. If the group name already exists, false will
	* be returned.
	*/
	public static function createGroup($gid) {
		OC_Log::write('OC_Group_Zikula', 'Use the zikula webinterface to create groups',3);
		return OC_USER_BACKEND_NOT_IMPLEMENTED;
	}

	/**
	* @brief delete a group
	* @param $gid gid of the group to delete
	* @returns true/false
	*
	* Deletes a group and removes it from the group_user-table
	*/
	public function deleteGroup($gid) {
		OC_Log::write('OC_Group_Zikula', 'Use the zikula webinterface to delete groups',3);
		return OC_USER_BACKEND_NOT_IMPLEMENTED;
	}

	/**
	* @brief is user in group?
	* @param $uid uid of the user
	* @param $gid gid of the group
	* @returns true/false
	*
	* Checks whether the user is member of a group or not.
	*/
	public function inGroup($uid, $gid) {
		return ZikulaConnect::fetch('userInGroup', array('user' => $uid, 'group' => $gid));
	}

	/**
	* @brief Add a user to a group
	* @param $uid Name of the user to add to group
	* @param $gid Name of the group in which add the user
	* @returns true/false
	*
	* Adds a user to a group.
	*/
	public function addToGroup($uid, $gid) {
		OC_Log::write('OC_Group_Zikula', 'Use the zikula webinterface to add users to groups',3);
		return OC_USER_BACKEND_NOT_IMPLEMENTED;
	}

	/**
	* @brief Removes a user from a group
	* @param $uid Name of the user to remove from group
	* @param $gid Name of the group from which remove the user
	* @returns true/false
	*
	* removes the user from a group.
	*/
	public function removeFromGroup( $uid, $gid ) {
		OC_Log::write('OC_Group_Zikula', 'Use the zikula webinterface to remove users from groups',3);
		return OC_USER_BACKEND_NOT_IMPLEMENTED;
	}

	/**
	* @brief Get all groups a user belongs to
	* @param $uid Name of the user
	* @returns array with group names
	*
	* This function fetches all groups a user belongs to. It does not check
	* if the user exists at all.
	*/
	public function getUserGroups($uid) {
		$result = ZikulaConnect::fetch('getUserGroups', array('user' => $uid));
		if(!is_array($result)) {
			$result = array();
		}
		return $result;
	}

	/**
	* @brief get a list of all groups
	* @returns array with group names
	*
	* Returns a list with all groups
	*/
	public function getGroups($search = '', $limit = -1, $offset = 0) {
		$return = ZikulaConnect::fetch('getGroups'/*, array('search' => $search, 'offset' => $offset, 'limit' => $limit)*/); ///TODO
		return $return;
	}

	/**
	* @brief get a list of all users in a group
	* @returns array with user ids
	*/
	public function usersInGroup($gid, $search = '', $limit = -1, $offset = 0) {
		return ZikulaConnect::fetch('getUsersInGroup', array('user' => $gid, 'search' => $search, 'limit' => $limit, 'offset' => $offset));
	}
}
