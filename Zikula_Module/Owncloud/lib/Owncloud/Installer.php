<?php
class Owncloud_Installer extends Zikula_AbstractInstaller
{
	/**
	 * Initialise the Inventory module.
	 *
	 * @author Leonard Marschke
	 * @return boolean: true on success / false on failure.
	 */
	public function install()
	{
		$this->setVars(Owncloud_Util::getModuleDefaults());

		EventUtil::registerPersistentModuleHandler('Owncloud', 'module.users.ui.validate_delete', array('Owncloud_Listeners', 'deleteOwncloudUser'));

		try {
			DoctrineHelper::createSchema($this->entityManager, array(
				'Owncloud_Entity_DeleteUser'
			));
		} catch (Exception $e) {
			echo $e;
			System::shutdown();
			return false;
		}

		// Initialisation successful
		return true;
	}


	/**
	 * Upgrading the module
	 *
	 * @author Leonard Marschke
	 * @return boolean: true on success / false on error
	 * @param $oldversion
	 */
	public function upgrade($oldversion)
	{
		switch($oldversion) {
			case '0.0.1':
				//further upgrades
			case '0.8.0':
				EventUtil::registerPersistentModuleHandler('Owncloud', 'module.users.ui.validate_delete', array('Owncloud_Listeners', 'deleteOwncloudUser'));
				try {
					DoctrineHelper::createSchema($this->entityManager, array(
						'Owncloud_Entity_DeleteUser'
					));
				} catch (Exception $e) {
					echo $e;
					System::shutdown();
					return false;
				}
			case '0.9.0':

		}
		return true;
	}

	/**
	 * Uninstall the module
	 *
	 * @author Leonard Marschke
	 * @return boolean: true on success / false on error
	 */
	public function uninstall()
	{
		//Remove all ModVars
		$this->delVars();

		EventUtil::unregisterPersistentModuleHandlers('Owncloud');

		DoctrineHelper::dropSchema($this->entityManager, array(
			'Owncloud_Entity_DeleteUser'
		));

		return true;
	}
}
