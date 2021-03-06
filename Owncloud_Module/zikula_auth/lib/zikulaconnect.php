<?php

/**
 * ownCloud - Zikula authentification backend
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

use \OC\ServerNotAvailableException;

class ZikulaConnect {
	/**
	 * provides the settings of the module
	 */
	private $settings;

	/**
	 * @brief instantiate the new class
	 * @param $settingsDriver settings class
	 * @return void
	 *
	 * Initialise the new class.
	 */
	public function __construct($settingsDriver) {
		$this->settings = $settingsDriver;
	}

	private function buildUrl($func) {
		$url = $this->settings->getZikulaUrl() . 'index.php?module=Owncloud&type=Owncloud&func=' . $func;
		return $url;
	}

	public function fetch ($func, $postparams = array()) {
		$url = self::buildUrl($func);
		$postparams['token'] = $this->settings->getSecret();

		//define chache file name
		$pathname = sys_get_temp_dir() . '/Zikula_Owncloud/' . hash('sha256', $url . serialize($postparams));
		$filename = $pathname . '/' . time();

		//check for cached content
		$output = null;
		if(is_dir($pathname)) {
			$files = scandir($pathname);
			unset($files[0]);
			unset($files[1]);
			if(count($files) > 1) {
				foreach ($files as $item) {
					unlink($pathname . '/' . $item);
				}
			} else {
				foreach ($files as $item) {
					if((time() - $item) < 120) {
						$output = file_get_contents($pathname . '/' . $item);
					} else {
						unlink($pathname . '/' . $item);
					}
				}
			}
		}

		if($output == null) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postparams);
			$output = curl_exec($ch);
			curl_close($ch);

			$return = json_decode($output, true);
			if($return === null || $output === false || !isset($return['status']) || $return['status'] != 'success') {
				if(isset($return['status']) && $return['status'] == 'error' && is_string($return['data'])) {
					\OCP\Util::writeLog('OC_User_Zikula', 'Invalid server response at function ' . $func .
						'. Error message: ' . $return['data'], \OCP\Util::ERROR);
				} else {
					\OCP\Util::writeLog('OC_User_Zikula', 'Invalid server response at function ' . $func .
						'. No error message provided by Zikula.', \OCP\Util::ERROR);
				}

				if($func != 'checkUserPassword'	) {
					throw new ServerNotAvailableException('Connection to Zikula could not be established');
				} else {
					throw new \OC\User\LoginException('Connection to Zikula could not be established. Please try again later or contact your system administrator.');
				}
				return;
			}
			//store output in cache only if it is not an auth request
			if($func != 'checkUserPassword') {
				if(!is_dir($pathname)) {
					mkdir($pathname, 0770, true);
				}
				$cache = fopen($filename, 'w');
				fwrite($cache, $output);
				fclose($cache);
			}
		} else {
			$return = json_decode($output, true);
		}

		return $return['data'];
	}
}
