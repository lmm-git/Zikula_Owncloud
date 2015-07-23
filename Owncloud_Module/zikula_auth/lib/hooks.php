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

/**
 * This class contains all hooks.
 */
class Hooks {
    /**
     * @brief Logging out user at Zikula
     * @param paramters parameters from logout-Hook
     * @return boolean
     */
    public static function logout($parameters) {
        $settings = new Settings();
        if($_GET['backtoZikulaWebsite'] === true) {
            header('Location: ' . $settings->getZikulaPublicUrl());
        } else {
            header('Location: ' . $settings->getZikulaPublicUrl() . 'index.php?module=owncloud&type=user&func=logout');
        }

        session_unset();
        session_destroy();
        \OC_User::unsetMagicInCookie();
        exit();
    }
}
