<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2014 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerAvatar
 */

namespace DragonJsonServerAvatar\Entity;

/**
 * Trait für den Avatar mit der Beziehung zu einem Avatar
 */
trait AvatarTrait
{
	/**
	 * @Doctrine\ORM\Mapping\OneToOne(targetEntity="\DragonJsonServerAvatar\Entity\Avatar")
	 * @Doctrine\ORM\Mapping\JoinColumn(name="avatar_id", referencedColumnName="avatar_id")
	 **/
	protected $avatar;
	
	/**
	 * Setzt den Avatar der Entity
	 * @param \DragonJsonServerAvatar\Entity\Avatar $avatar
	 * @return AvatarTrait
	 */
	public function setAvatar(\DragonJsonServerAvatar\Entity\Avatar $avatar)
	{
		$this->avatar = $avatar;
		return $this;
	}
	
	/**
	 * Gibt den Avatar der Entity zurück
	 * @return \DragonJsonServerAvatar\Entity\Avatar
	 */
	public function getAvatar()
	{
		return $this->avatar;
	}
}
