<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * Items entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity
 * @ORM\Table(name="Owncloud_DeleteUser")
 */
class Owncloud_Entity_DeleteUser extends Zikula_EntityAccess
{

	/**
	 * The following are annotations which define the id field.
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * The following are annotations which define the user name (uname) field.
	 *
	 * @ORM\Column(type="string")
	 */
	private $uname;

	/**
	 * The following are annotations which define the user properties (uprop) field.
	 *
	 * @ORM\Column(type="array")
	 */
	private $uprop;

	/**
	 * The following are annotations which define the cr_date (create date) field.
	 *
	 * @ORM\Column(type="datetime")
	 */
	private $cr_date;

	public function __construct($uid = null) {
		$props = UserUtil::getVars($uid);
		if(!is_array($props) || count($props) < 2 || $uid == null) {
			throw new InvalidArgumentException();
		}
		$this->cr_date = new DateTime('NOW');
		$this->uname = $props['uname'];
		$this->uprop = $props;
	}

	//getting section

	public function getId()
	{
		return $this->id;
	}

	public function getUname()
	{
		return $this->uname;
	}

	public function getUprop()
	{
		return $this->uprop;
	}

	public function getCr_date()
	{
		return $this->cr_date;
	}
}
