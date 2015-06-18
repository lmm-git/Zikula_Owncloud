<?php
class Owncloud_Controller_Owncloud extends Zikula_AbstractController
{
	/**
	 * check for valid server-credentials
	 *
	 * @version 1.0
	 * @author Leonard Marschke
	 * @return true on valid server-credentials
	 */
	private function authenticate() {
		//we do not need a session, so we don't need to have unnused db-entries in session db
		session_destroy();

		$token = FormUtil::getPassedValue('token', null, 'GETPOST');
		$addr = $_SERVER['REMOTE_ADDR'];

		if($token == $this->getVar('Authtoken') && $this->getVar('Authtoken') != null && $addr == $this->getVar('AllowedHost')) {
			return true;
		} else {
			echo 'You are a hacker, right?';
			exit();
		}
	}

	/**
	 * return message to requesting server
	 *
	 * @version 1.0
	 * @author Leonard Marschke
	 * @return System::shutdown()
	 */
	private function ret($data, $status = 'success') {
		echo json_encode(array(
			'status' => $status,
			'data' => $data)
		);
		return System::shutdown();
	}

	/**
	 * return error message to requesting server
	 *
	 * @version 1.0
	 * @author Leonard Marschke
	 * @return return()
	 */
	private function retError($string) {
		return self::ret($error, 'error');
	}

	/**
	 * get valid users allowed to use owncloud
	 *
	 * @version 1.0
	 * @author Leonard Marschke
	 * @return array with users
	 */
	private function getRawUsers($where = null) {
		if($where == null) {
			$where = 'activated = 1';
		} else {
			$where = '( ' . mysql_escape_string($where) . ' ) AND activated = 1';
		}
		$users = UserUtil::getUsers($where, 'uname', FormUtil::getPassedValue('offset', -1), FormUtil::getPassedValue('limit', -1));
		//check for right permissions
		foreach($users as $key => $item) {
			if(SecurityUtil::checkPermission('Owncloud::Use', '::', ACCESS_EDIT, $item['uid']) && $item['uid'] != 1) {
				if(SecurityUtil::checkPermission('Owncloud::Admin', '::', ACCESS_MODERATE, $item['uid'])) {
					$users[$key]['owncloudadmin'] = true;
				} else {
					$users[$key]['owncloudadmin'] = false;
				}
				//delete some never used variables
				unset($users[$key]['pass']);
				unset($users[$key]['passreminder']);
				unset($users[$key]['activated']);
				unset($users[$key]['approved_date']);
				unset($users[$key]['approved_by']);
				unset($users[$key]['user_regdate']);
				unset($users[$key]['theme']);
				unset($users[$key]['ublockon']);
				unset($users[$key]['ublock']);
			} else {
				unset($users[$key]);
			}
		}
		return $users;
	}

	/**
	 * getUsers function
	 *
	 * @version 1.0
	 * @author Leonard Marschke
	 * @return JSON-Array
	 */
	public function getUsers()
	{
		self::authenticate();
		$search = FormUtil::getPassedValue('search', null, 'GETPOST');
		if($search != '') {
			$where = 'uname LIKE \'' . $search . '%\'';
		} else {
			$where = null;
		}
		$users = self::getRawUsers($where);
		//build return array
		$return = array();
		foreach($users as $item) {
			$return[] = $item['uname'];
		}

		return self::ret($return);
	}


	/**
	 * userExists - check if the given user exists in database and is allowed to use ownCloud
	 *
	 * @version 1.0
	 * @author Leonard Marschke
	 * @return JSON-Array
	 */
	public function userExists()
	{
		self::authenticate();

		$uname = FormUtil::getPassedValue('user', null);

		if($uname == null) {
			return self::retError('ERROR: No user name passed!');
		}

		$users = self::getRawUsers('uname = \'' . $uname . '\'');

		if(count($users) == 1) {
			foreach($users as $user) {
				if($user['uname'] == $uname) {
					$return = true;
				}
			}
		} else {
			$return = false;
		}
		return self::ret($return);
	}

	/**
	 * checkUserPassword - check if the given user and password is valid and the user is allowed to use owncloud
	 *
	 * @version 1.0
	 * @author Leonard Marschke
	 * @return JSON-Array
	 */
	public function checkUserPassword()
	{
		self::authenticate();

		$uname = FormUtil::getPassedValue('user', null);
		$pass = FormUtil::getPassedValue('up', null);

		if($uname == null) {
			return self::retError('ERROR: No user name passed!');
		}
		if($pass == null) {
			return self::retError('ERROR: No up passed!');
		}

		$users = self::getRawUsers('uname = \'' . $uname . '\'');

		if(count($users) == 1) {
			foreach($users as $user) {
				if($user['uname'] == $uname) {
					if(FormUtil::getPassedValue('viaauthcode', null, 'POST') != null) {
						$authcode = unserialize(UserUtil::getVar('owncloud_authcode', $user['uid']));
						if($authcode['usebefore'] >= new DateTime('NOW') &&
							$authcode['authcode'] == $pass) {
								$return = true;
						} else {
							$return = false;
						}
					} else {
						$authenticationMethod = array(
							'modname' => 'Users' ///TODO
						);
						if (ModUtil::getVar(Users_Constant::MODNAME, Users_Constant::MODVAR_LOGIN_METHOD, Users_Constant::DEFAULT_LOGIN_METHOD) == Users_Constant::LOGIN_METHOD_EMAIL) {
							$authenticationMethod['method'] = 'email';
						} else {
							$authenticationMethod['method'] = 'uname';
						}
						$authenticationInfo = array(
							'login_id' => $uname,
							'pass' => $pass
						);
						//try to login (also for the right output)
						if(UserUtil::loginUsing($authenticationMethod, $authenticationInfo, false, null, true) == true) {
							$return = true;
						} else {
							$return = false;
						}
					}
				}
			}
		} else {
			$return = false;
		}
		return self::ret($return);
	}

	/**
	 * getGroups - get all groups of zikula
	 *
	 * @version 1.0
	 * @author Leonard Marschke
	 * @return JSON-Array
	 */
	public function getGroups()
	{
		self::authenticate();

		$search = FormUtil::getPassedValue('search', null, 'GETPOST');
		if($search == 'admin') {
			$return = array('admin');
		} else {
			$return = array();
		}

		if($search != '') {
			$where = 'name LIKE \'' . $search . '%\'';
		} else {
			$where = '';
		}
		$offset = (integer)FormUtil::getPassedValue('offset', -1);
		if($offset == null) {
			$offset = -1;
		}
		$limit = (integer)FormUtil::getPassedValue('limit', -1);
		if($limit == null) {
			$limit = -1;
		}

		$groups = UserUtil::getGroups($where, 'name', $offset, $limit);

		$return = array();
		foreach($groups as $item) {
			$return[] = $item['name'];
		}

		return self::ret($return);
	}

	/**
	 * groupExists - check if a given group exists
	 *
	 * @version 1.0
	 * @author Leonard Marschke
	 * @return JSON-Array
	 */
	public function groupExists()
	{
		self::authenticate();

		$search = FormUtil::getPassedValue('group', null, 'GETPOST');
		$return = false;
		if($search == 'admin') {
			$return = true;
		} else {
			if($search != '') {
				$where = 'name = \'' . $search . '\'';
			} else {
				$where = '';
			}
			$offset = (integer)FormUtil::getPassedValue('offset', -1);
			if($offset == null) {
				$offset = -1;
			}
			$limit = (integer)FormUtil::getPassedValue('limit', -1);
			if($limit == null) {
				$limit = -1;
			}

			$groups = UserUtil::getGroups($where, 'name', $offset, $limit);

			if(count($groups) >= 1) {
				$return = true;
			}
		}

		return self::ret($return);
	}

	/**
	 * getUserGroups - get all groups of one user
	 *
	 * @version 1.0
	 * @author Leonard Marschke
	 * @return JSON-Array
	 */
	public function getUserGroups()
	{
		self::authenticate();

		$uname = FormUtil::getPassedValue('user', null);
		if($uname == null) {
			return self::retError('ERROR: No user name passed!');
		}

		$uid = UserUtil::getIdFromName($uname);
		if($uid == false) {
			return self::ret(false);
		}

		$groups = UserUtil::getGroupsForUser($uid);
		$return = array();
		foreach($groups as $item) {
			$group = UserUtil::getGroup($item);
			$return[] = $group['name'];
		}
		if(SecurityUtil::checkPermission('Owncloud::Admin', '::', ACCESS_MODERATE, $uid)) {
			$return[] = 'admin';
		}
		return self::ret($return);
	}

	/**
	 * userInGroup - check if user is in group
	 *
	 * @version 1.0
	 * @author Leonard Marschke
	 * @return JSON-Array
	 */
	public function userInGroup()
	{
		self::authenticate();

		$uname = FormUtil::getPassedValue('user', null);
		$group = FormUtil::getPassedValue('group', null);

		if($uname == null) {
			return self::retError('ERROR: No user name passed!');
		}
		if($group == null) {
			return self::retError('ERROR: No group passed!');
		}

		$uid = UserUtil::getIdFromName($uname);
		if($uid == false) {
			return self::ret(false);
		}

		if($group == 'admin') {
			if(SecurityUtil::checkPermission('Owncloud::Admin', '::', ACCESS_MODERATE, $uid)) {
				$return = true;
			} else {
				$return = false;
			}
		} else {
			$groups = UserUtil::getGroupsForUser($uid);
			$return = false;
			foreach($groups as $item) {
				$itemgroup = UserUtil::getGroup($item);
				if($itemgroup['name'] == $group) {
					$return = true;
				}
			}
		}

		return self::ret($return);
	}

	/**
	 * getUsersInGroup - get all users of one group
	 *
	 * @version 1.0
	 * @author Leonard Marschke
	 * @return JSON-Array
	 */
	public function getUsersInGroup()
	{
		self::authenticate();

		$group = FormUtil::getPassedValue('group', null);

		if($group == null) {
			return self::retError('ERROR: No group passed!');
		}

		$group = UserUtil::getGroups('name = \'' . $group . '\'');
		$return = array();
		if(count($group) == 1) {
			foreach($group as $item) {
				$users = UserUtil::getUsersForGroup($item['gid']);
				foreach($users as $uid) {
					$return[] = UserUtil::getVar('uname', $uid);
				}
			}
		} else {
			$return = false;
		}

		return self::ret($return);
	}

	/**
	 * getUsersToDelete - get deleted user which should be deleted at owncloud too
	 *
	 * @version 1.0
	 * @author Leonard Marschke
	 * @return JSON-Array
	 */
	public function getUsersToDelete()
	{
		self::authenticate();

		$db = $this->entityManager->getRepository('Owncloud_Entity_DeleteUser')->findBy(array());
		$result = array();
		foreach($db as $item) {
			$result[] = $item->getUname();
		}

		return self::ret($result);
	}

	/**
	 * userDeleted - delete a user to delete (because the user was deleted)
	 *
	 * @version 1.0
	 * @author Leonard Marschke
	 * @return JSON-Array
	 */
	public function userDeleted()
	{
		self::authenticate();

		$userId = FormUtil::getPassedValue('user', null, 'GETPOST');
		if(!is_string($userId)) {
			return self::ret(false);
		}

		$user = $this->entityManager->getRepository('Owncloud_Entity_DeleteUser')->findOneBy(array('uname' => $userId));
		if(!($user instanceof Owncloud_Entity_DeleteUser)) {
			return self::ret(false);
		}
		$this->entityManager->remove($user);
		$this->entityManager->flush();

		return self::ret(true);
	}
}
