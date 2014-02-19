<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2014 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerAvatar
 */

namespace DragonJsonServerAvatar\Api;

/**
 * API Klasse zur Verwaltung von Avataren
 */
class Avatar
{
	use \DragonJsonServer\ServiceManagerTrait;

	/**
	 * Validiert die übergebene GameroundID und Namen
	 * @param integer $gameround_id
	 * @param string $name
     * @throws \DragonJsonServer\Exception
     * @DragonJsonServerAccount\Annotation\Session
	 */
	public function validateGameroundIdAndName($gameround_id, $name)
	{
		$serviceManager = $this->getServiceManager();

		$gameround = $serviceManager->get('\DragonJsonServerGameround\Service\Gameround')->getGameroundByGameroundId($gameround_id);
		$serviceAvatar = $serviceManager->get('\DragonJsonServerAvatar\Service\Avatar');
		$serviceAvatar->validateName($name);
		$avatar = $serviceAvatar->getAvatarByGameroundIdAndName($gameround_id, $name, false);
		if (null !== $avatar) {
			throw new \DragonJsonServer\Exception(
				'gameround_id and name not unique', 
				['gameround_id' => $gameround_id, 'name' => $name, 'avatar' => $avatar->toArray()]
			);
		}
	}
	
	/**
	 * Erstellt einen Avatar auf der Spielrunde
	 * @param integer $gameround_id
	 * @param string $name
	 * @DragonJsonServerAccount\Annotation\Session
	 */
	public function createAvatar($gameround_id, $name)
	{
		$this->validateGameroundIdAndName($gameround_id, $name);
		$serviceManager = $this->getServiceManager();

		$session = $serviceManager->get('\DragonJsonServerAccount\Service\Session')->getSession();
		return $serviceManager->get('\DragonJsonServerAvatar\Service\Avatar')->createAvatar($session->getAccountId(), $gameround_id, $name)->toArray();
	}
	
	/**
	 * Entfernt den Avatar von der Spielrunde
	 * @DragonJsonServerAccount\Annotation\Session
	 * @DragonJsonServerAvatar\Annotation\Avatar
	 */
	public function removeAvatar()
	{
		$serviceManager = $this->getServiceManager();

		$serviceAvatar = $serviceManager->get('\DragonJsonServerAvatar\Service\Avatar'); 
		$avatar = $serviceAvatar->getAvatar();
		$serviceAvatar->removeAvatar($avatar);
	}
	
	/**
	 * Gibt den aktuellen Avatar zurück
	 * @return array
	 * @DragonJsonServerAccount\Annotation\Session
	 * @DragonJsonServerAvatar\Annotation\Avatar
	 */
	public function getAvatar()
	{
		$serviceManager = $this->getServiceManager();

		$serviceAvatar = $serviceManager->get('\DragonJsonServerAvatar\Service\Avatar');
		return $avatar = $serviceAvatar->getAvatar()->toArray();
	}
	
	/**
	 * Gibt alle Avatare zum aktuellen Account zurück
	 * @return array
	 * @DragonJsonServerAccount\Annotation\Session
	 */
	public function getAvatars()
	{
		$serviceManager = $this->getServiceManager();

		$session = $serviceManager->get('\DragonJsonServerAccount\Service\Session')->getSession();
		$avatars = $serviceManager->get('\DragonJsonServerAvatar\Service\Avatar')->getAvatarsByAccountId($session->getAccountId());
		return $serviceManager->get('\DragonJsonServerDoctrine\Service\Doctrine')->toArray($avatars);
	}
	
	/**
	 * Gibt die Avatare passend zum übergebenen Namen zurück
	 * @param string $name
	 * @return array
	 * @DragonJsonServerAccount\Annotation\Session
	 * @DragonJsonServerAvatar\Annotation\Avatar
	 */
	public function searchAvatarsByName($name)
	{
		$serviceManager = $this->getServiceManager();

		$serviceAvatar = $serviceManager->get('\DragonJsonServerAvatar\Service\Avatar');
		$avatar = $serviceAvatar->getAvatar();
		$avatars = $serviceAvatar->searchAvatarsByGameroundIdAndName($avatar->getGameroundId(), $name);
		return $serviceManager->get('\DragonJsonServerDoctrine\Service\Doctrine')->toArray($avatars);
	}
}
