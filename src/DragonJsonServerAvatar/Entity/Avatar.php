<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2013 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerAvatar
 */

namespace DragonJsonServerAvatar\Entity;

/**
 * Entityklasse eines Avatars
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="avatars")
 */
class Avatar
{
	use \DragonJsonServerDoctrine\Entity\ModifiedTrait;
	use \DragonJsonServerDoctrine\Entity\CreatedTrait;
	use \DragonJsonServerAccount\Entity\AccountIdTrait;
	use \DragonJsonServerGameround\Entity\GameroundIdTrait;
	
	/**
	 * @Doctrine\ORM\Mapping\Id 
	 * @Doctrine\ORM\Mapping\Column(type="integer")
	 * @Doctrine\ORM\Mapping\GeneratedValue
	 **/
	protected $avatar_id;
	
	/**
	 * @Doctrine\ORM\Mapping\Column(type="string")
	 **/
	protected $name;
	
	/**
	 * Gibt die ID des Avatars zurück
	 * @return integer
	 */
	public function getAvatarId()
	{
		return $this->avatar_id;
	}
	
	/**
	 * Setzt den Namen des Avatars
	 * @param string $name
	 * @return Avatar
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}
	
	/**
	 * Gibt den Namen des Avatars zurück
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * Gibt die Attribute des Avatars als Array zurück
	 * @return array
	 */
	public function toArray()
	{
		return [
			'avatar_id' => $this->getAvatarId(),
			'modified' => $this->getModifiedTimestamp(),
			'created' => $this->getCreatedTimestamp(),
			'account_id' => $this->getAccountId(),
			'gameround_id' => $this->getGameroundId(),
			'name' => $this->getName(),
		];
	}
}
