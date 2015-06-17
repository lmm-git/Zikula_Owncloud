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
 * You should have received a copy of the GNU Lesser General Public 
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

//Hack to generate valid session before performing login (I know it is ugly...)
if(isset($_POST['user']) && $_POST['user'] != '' && $_GET['zikula_authcode'] != '' && isset($_POST['requesttoken'])) {
	\OC::$server->getUserSession()->logout();
	$requesttoken = $_POST['requesttoken'];
	\OC::$server->getSession()->set('requesttoken', $requesttoken);
}

require_once 'zikula_auth/lib/user.php';
require_once 'zikula_auth/lib/group.php';
require_once 'zikula_auth/lib/hooks.php';

OCP\Util::connectHook('OC_User', 'logout', 'OC_Zikula_Auth_Hooks', 'logout');

OC_APP::registerAdmin('zikula_auth','settings');

OC_User::useBackend(new OC_USER_ZIKULA());
OC_Group::useBackend(new OC_GROUP_ZIKULA());

OCP\App::addNavigationEntry(
	array( 'id' => 'zikula_auth_backtowebsite',
		'order' => 70,
		'href' => OCP\Util::linkToRoute('ZikulaAuth_backToWebsite'),
		'icon' => OCP\Util::imagePath( 'zikula_auth', 'website.svg' ),
		'name' => 'Back to website'
));

OCP\Backgroundjob::registerJob('\OCA\Zikula_Auth\Jobs\CleanUp');
