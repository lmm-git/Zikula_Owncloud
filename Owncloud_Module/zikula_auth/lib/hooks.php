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

/**
 * This class contains all hooks.
 */
class OC_Zikula_Auth_Hooks{
        /**
         * @brief Logging out user at Zikula
         * @param paramters parameters from logout-Hook
         * @return boolean
         */
        public static function logout($parameters) {
             if($_GET['backtoZikulaWebsite'] === true) {
                 header('Location: ' . OC_Appconfig::getValue( 'zikula_auth', 'zikula_server_www_address', null));
             } else {
                 header('Location: ' . OC_Appconfig::getValue( 'zikula_auth', 'zikula_server_www_address', null) . 'index.php?module=owncloud&type=user&func=logout');
             }
             
             session_unset();
             session_destroy();
             OC_User::unsetMagicInCookie();
             exit();
        }
}

