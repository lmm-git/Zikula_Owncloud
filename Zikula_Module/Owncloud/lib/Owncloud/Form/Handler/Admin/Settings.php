<?php
/**
 * Owncloud settings
 *
 * @license    GPLv3 or any later version
 * @package    Owncloud/Admin/Form/Handler/Settings
 */

/**
 * @brief Register FormHandler
 */
class Owncloud_Form_Handler_Admin_Settings extends Zikula_Form_AbstractHandler
{
	/**
	 * @brief Setup form
	 *
	 * @param Zikula_Form_View $view Current Zikula_Form_View instance
	 * @return boolean
	 *
	 * @author Leonard Marschke
	 * @version 1.0
	 */
	function initialize(Zikula_Form_View $view)
	{
		$vars = Owncloud_Util::getModuleDefaults();
		foreach($vars as $key => $item) {
			$value = ModUtil::getVar($this->name, $key, $item);
			$this->view->assign($key, $value);
		}
	}

	/**
	 * @brief Handle form submission
	 * @param Zikula_Form_View $view  Current Zikula_Form_View instance
	 * @param array &$args Arguments
	 * @return bool|void
	 *
	 *
	 * @author Leonard Marschke
	 * @version 1.0
	 */
	function handleCommand(Zikula_Form_View $view, &$args)
	{
		if ($args['commandName'] == 'cancel') {
			LogUtil::RegisterStatus($view->__('Configuring of module settings cancelled!'));
			return $view->redirect(ModUtil::url($this->name, 'admin', 'main'));
		}

		// check for valid form
		if (!$view->isValid()) {
			return false;
		}

		$data = $view->getValues();

		if(substr($data['OwncloudURL'], -1) != '/') {
			$data['OwncloudURL'] .= '/';
		}

		foreach ($data as $key => $item) {
			ModUtil::setVar($this->name, $key, $item);
		}

		LogUtil::registerStatus($this->__('Module settings edited!'));

		return System::redirect(ModUtil::url($this->name, 'admin', 'main'));
	}
}
