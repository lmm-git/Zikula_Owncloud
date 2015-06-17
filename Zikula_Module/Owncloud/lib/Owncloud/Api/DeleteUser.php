<?php
class Owncloud_Api_DeleteUser extends Zikula_AbstractApi
{
	/**
	 * Add a user to delete to userDelete table
	 *
	 * @return void
	 */
	public function toDelete($args)
	{
		$delUser = new Owncloud_Entity_DeleteUser($args['uid']);
		$this->entityManager->persist($delUser);
		$this->entityManager->flush();
	}

	/**
	 * remove a deleted user from database
	 *
	 * @return void
	 */
	public function deleted($args)
	{
		$delUser = $this->entityManager->getRepository('Owncloud_Entity_DeleteUser')->findBy(array('uname' => $args['uname']));
		$this->entityManager->remove($delUser);
		$this->entityManager->flush();
	}
	
}