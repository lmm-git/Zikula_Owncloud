<?php
class Owncloud_Util
{

	/**
	 * Provides an array containing default values for module variables (settings).
	 *
	 * @author Leonard Marschke
	 * @return array An array indexed by variable name containing the default values for those variables.
	 */
	public static function getModuleDefaults()
	{
		$vars = array ();
		$vars['Authtoken'] = '';
		$vars['OwncloudURL'] = '';
		$vars['AllowedHost'] = '127.0.0.1';
		return $vars;
	}

}
