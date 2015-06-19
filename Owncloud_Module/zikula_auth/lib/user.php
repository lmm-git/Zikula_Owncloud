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
* @brief Class providing zikula users to ownCloud
*/
class User extends \OC_User_Backend implements \OCP\UserInterface {
	/**
	* provides the possible actions of this user backend
	*/
	protected $possibleActions = array(
		self::CHECK_PASSWORD => 'checkPassword',
		self::GET_HOME => 'getHome',
		self::GET_DISPLAYNAME => 'getDisplayName',
		self::COUNT_USERS => 'countUsers',
	);

	/**
	* @brief Create a new user
	* @param $uid The username of the user to create
	* @param $password The password of the new user
	* @returns true/false
	*
	* Creates a new user. Basic checking of username is done in OC_User
	* itself, not in its subclasses.
	*/
	public function createUser($uid, $password) {
		\OC_Log::write('OC_User_Zikula', 'Use the zikula webinterface to create users',3);
		return OC_USER_BACKEND_NOT_IMPLEMENTED;
	}

	/**
	* @brief delete a user
	* @param $uid The username of the user to delete
	* @returns true/false
	*
	* Deletes a user
	*/
	public function deleteUser($uid) {
		\OC_Log::write('OC_User_Zikula', 'Use the zikula webinterface to delete users',3);
		return OC_USER_BACKEND_NOT_IMPLEMENTED;
	}

	/**
	* @brief Set password
	* @param $uid The username
	* @param $password The new password
	* @returns true/false
	*
	* Change the password of a user
	*/
	public function setPassword($uid, $password) {
		\OC_Log::write('OC_User_Zikula', 'Use the zikula webinterface to change passwords',3);
		return OC_USER_BACKEND_NOT_IMPLEMENTED;
	}

	/**
	* @brief Check if the password is correct
	* @param $uid The username
	* @param $password The password
	* @returns true/false
	*
	* Check if the password is correct without logging in the user
	*/
	public function checkPassword($uid, $password) {
		$postparams = array('user' => $uid, 'up' => $password);
		if($_POST['user'] != '' && isset($_GET['zikula_authcode']) && $_GET['zikula_authcode'] != '') {
			$postparams['viaauthcode'] = true;
		}

		if(ZikulaConnect::fetch('checkUserPassword', $postparams)) {
			return $uid;
		} else {
			return false;
		}
	}

	/**
	 * Determines if the backend can enlist users
	 *
	 * @return bool
	 */
	public function hasUserListings() {
		return true;
	}

	/**
	* @brief Get a list of all users
	* @returns array with all active usernames
	*
	* Get a list of all users.
	*/
	public function getUsers($search = '', $limit = -1, $offset = 0) {
		if(!is_numeric($limit)) {
			$limit = -1;
		}
		if(!is_numeric($offset)) {
			$offset = -1;
		}
		return ZikulaConnect::fetch('getUsers', array('search' => $search, 'offset' => $offset, 'limit' => $limit));
	}

	/**
	* @brief check if a user exists
	* @param string $uid the username
	* @return boolean
	*/
	public function userExists($uid) {
		//sometimes we get an empty uid?!?
		if($uid == '') {
			return false;
		}
		return ZikulaConnect::fetch('userExists', array('user' => $uid));
	}

	/**
	* @brief get count of users
	* @return integer
	*/
	public function countUsers() {
		return count(self::getUsers());
	}

	/**
	 * Backend name to be shown in user management
	 * @return string the name of the backend to be shown
	 */
	public function getBackendName(){
		return 'Zikula';
	}
}
