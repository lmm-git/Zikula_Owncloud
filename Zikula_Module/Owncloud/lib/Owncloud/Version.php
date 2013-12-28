<?php
/**
 * ownCloud connector
 *
 * @copyright  (c) Leonard Marschke
 * @license    GPLv3
 * @package    Owncloud
 * @subpackage Version
 */
class Owncloud_Version extends Zikula_AbstractVersion
{
	public function getMetaData()
	{
		$meta = array();
		$meta['displayname']    = $this->__('Owncloud');
		$meta['description']    = $this->__('Owncloud connection module for Zikula. For using this module you have to install the zikula_auth module at your owncloud instance.');
		//! module name that appears in URL
		$meta['url']            = $this->__('owncloud');
		$meta['version']        = '0.8.0';
		$meta['core_min']       = '1.3.5'; //tested only with Zikula 1.3.5
		$meta['core_max']       = '1.3.99';


		// Permissions schema
		$meta['securityschema'] = array('Owncloud::Use' => '::',
			'Owncloud::Admin' => '::');

		// Module depedencies
		$meta['dependencies'] = array();

		return $meta;
	}
}
