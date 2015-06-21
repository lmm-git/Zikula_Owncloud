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

class Settings {
	/**
	 * provides the url to call for user information
	 */
	private $zikulaUrl = null;

	/**
	 * provides the public url of Zikula normal users can see
	 * Used for redirections to Zikula
	 */
	private $zikulaPublicUrl = null;

	/**
	 * contains the secret key to authenticate requests
	 */
	private $secret = null;

	/**
	 * @brief instantiate the new class
	 * @return void
	 *
	 * Initialise the settings by checking the global ownCloud settings
	 */
	public function __construct() {
		$config = \OC_config::getValue('zikula_auth', array());
		if(isset($config['zikula_public_url'])) {
			if(filter_var($config['zikula_public_url'], FILTER_VALIDATE_URL) !== false) {
				$this->zikulaPublicUrl = $config['zikula_public_url'];
			}
		}

		if(isset($config['zikula_url'])) {
			if(filter_var($config['zikula_url'], FILTER_VALIDATE_URL) !== false) {
				$this->zikulaUrl = $config['zikula_url'];
				if(substr($this->zikulaUrl, -1) != '/') {
					$this->zikulaUrl .= '/';
				}
				if($this->zikulaPublicUrl === null) {
					$this->zikulaPublicUrl = $config['zikula_url'];
				}

				if(parse_url($this->zikulaUrl, PHP_URL_SCHEME) != 'https') {
					\OC_Log::write('zikula_auth', 'Using other transport mechanisms than https is a big security risk! Please change to https!', \OCP\Util::WARN);
				}
			}
		}

		if(isset($config['secret'])) {
			$this->secret = $config['secret'];
		}
	}

	/**
	 * @brief Checks if parsed settings are valid
	 * @return true if valid, false if not
	 *
	 * Checks the parsed settings by the constructor for validity
	 */
	public function isValid() {
		if($this->zikulaUrl === null) {
			return false;
		}
		if($this->zikulaPublicUrl === null) {
			return false;
		}
		if($this->secret === null) {
			return false;
		}
		return true;
	}

	/**
	 * @brief getter for zikulaUrl
	 * @return string zikulaUrl
	 *
	 * returns the Zikula URL to request
	 */
	public function getZikulaUrl() {
		return $this->zikulaUrl;
	}

	/**
	 * @brief getter for zikulaPublicUrl
	 * @return string zikulaPublicUrl
	 *
	 * returns the Zikula URL to redirect
	 */
	public function getZikulaPublicUrl() {
		return $this->zikulaPublicUrl;
	}

	/**
	 * @brief getter for secret
	 * @return string secret
	 *
	 * returns the secret to sign requests
	 */
	public function getSecret() {
		return $this->secret;
	}
}