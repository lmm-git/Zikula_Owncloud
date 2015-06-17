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

namespace OCA\Zikula_Auth\Jobs;

use \OC\BackgroundJob\TimedJob;

require_once 'zikula_auth/lib/zikulaconnect.php';

/**
 * Class CleanUp
 *
 * a Background job to clean up deleted users by Zikula
 */
class CleanUp extends TimedJob {
	/** @var int $defaultIntervalMin default interval in minutes */
	protected $defaultIntervalMin = 1;

	public function __construct() {
		$this->setInterval(intval($this->defaultIntervalMin) * 60);
	}

	/**
	 * makes the background job do its work
	 * @param array $argument
	 */
	public function run($argument) {
		$fetch = \ZikulaConnect::fetch('getUsersToDelete');

		foreach($fetch as $key => $item) {
			\OC_Log::write('OC_User_Zikula', 'User ' . $key . ' = ' . $item, \OCP\Util::DEBUG);
		}
		\OC_Log::write('OC_User_Zikula', 'END', \OCP\Util::DEBUG);

		if(!is_array($fetch)) {
			return;
		}

		foreach($fetch as $user) {
			\OC_Log::write('OC_User_Zikula', 'Deleted user ' . $key . ' = ' . $item, \OCP\Util::DEBUG);

			//what is better?
			\OC::$server->getUserManager()->get($user)->delete();
			//\OC_User::deleteUser($user);
		}

	}
}
