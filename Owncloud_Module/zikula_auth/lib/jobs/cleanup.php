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
use \OCA\Zikula_Auth\ZikulaConnect;
use \OCA\Zikula_Auth\Settings;

require_once 'zikula_auth/lib/zikulaconnect.php';

/**
 * Class CleanUp
 *
 * a Background job to clean up deleted users by Zikula
 */
class CleanUp extends TimedJob {
	/** @var int $defaultIntervalMin default interval in minutes */
	protected $defaultIntervalMin = 31;

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
	public function __construct() {
		$this->settings = new Settings();
		$this->setInterval(intval($this->defaultIntervalMin) * 60);
		$this->zikulaConnect = new ZikulaConnect($this->settings);
	}

	/**
	 * makes the background job do its work
	 * @param array $argument
	 */
	public function run($argument) {
		$fetch = $this->zikulaConnect->fetch('getUsersToDelete');

		if(!is_array($fetch)) {
			return;
		}

		foreach($fetch as $user) {
			\OCP\Util::writeLog('OC_User_Zikula', 'Deleted user ' . $key . ' = ' . $item, \OCP\Util::DEBUG);

			//what is better?
			$successfull = \OC::$server->getUserManager()->get($user)->delete();
			//\OC_User::deleteUser($user);

			if($successfull) {
				$this->zikulaConnect->fetch('userDeleted', array('user' => $user));
			}
		}

	}
}
