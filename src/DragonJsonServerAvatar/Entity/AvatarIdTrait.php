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
 * Trait für die AvatarID mit der Beziehung zu einem Avatar
 */
trait AvatarIdTrait
{
	/**
	 * @Doctrine\ORM\Mapping\Column(type="integer")
	 **/
	protected $avatar_id;
	
	/**
	 * Setzt die AvatarID der Entity
	 * @param integer $avatar_id
	 * @return AvatarIdTrait
	 */
	public function setAvatarId($avatar_id)
	{
		$this->avatar_id = $avatar_id;
		return $this;
	}
	
	/**
	 * Gibt die AvatarID der Entity zurück
	 * @return integer
	 */
	public function getAvatarId()
	{
		return $this->avatar_id;
	}
}
