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

OC_Util::checkAdminUser();

$params = array(
	'zikula_server',
	'zikula_server_token',
	'zikula_server_www_address'
);

if ($_POST) {
	foreach($params as $param) {
		if(($param == 'zikula_server' || $param =='zikula_server_www_address') && substr($_POST[$param], -1) != '/') {
			$_POST[$param] .= '/';
		}
		if(isset($_POST[$param])) {
			OC_Appconfig::setValue('zikula_auth', $param, $_POST[$param]);
		}
	}
}

// fill template
$tmpl = new OC_Template( 'zikula_auth', 'settings');
$tmpl->assign('zikula_server', OC_Appconfig::getValue('zikula_auth', 'zikula_server', 'localhost/'));
$tmpl->assign('zikula_server_token', OC_Appconfig::getValue('zikula_auth', 'zikula_server_token', ''));
$tmpl->assign('zikula_server_www_address', OC_Appconfig::getValue('zikula_auth', 'zikula_server_www_address', ''));

return $tmpl->fetchPage();
