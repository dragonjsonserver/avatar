<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2013 DragonProjects (http://dragonprojects.de/)
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
     * @session
	 */
	public function validateGameroundIdAndName($gameround_id, $name)
	{
		$serviceManager = $this->getServiceManager();

		$gameround = $serviceManager->get('Gameround')->getGameroundByGameroundId($gameround_id);
		$serviceAvatar = $serviceManager->get('Avatar');
		$serviceAvatar->validateName($name);
		if (null !== $serviceAvatar->getAvatarByGameroundIdAndName($gameround_id, $name, false)) {
			throw new \DragonJsonServer\Exception(
				'gameround_id and name not unique', 
				['gameround_id' => $gameround_id, 'name' => $name]
			);
		}
	}
	
	/**
	 * Erstellt einen Avatar auf der Spielrunde
	 * @param integer $gameround_id
	 * @param string $name
	 * @session
	 */
	public function createAvatar($gameround_id, $name)
	{
		$this->validateGameroundIdAndName($gameround_id, $name);
		$serviceManager = $this->getServiceManager();

		$session = $serviceManager->get('Session')->getSession();
		return $serviceManager->get('Avatar')->createAvatar($session->getAccountId(), $gameround_id, $name)->toArray();
	}
	
	/**
	 * Entfernt den Avatar von der Spielrunde
	 * @session
	 * @avatar
	 */
	public function removeAvatar()
	{
		$serviceManager = $this->getServiceManager();

		$serviceAvatar = $serviceManager->get('Avatar'); 
		$avatar = $serviceAvatar->getAvatar();
		$serviceAvatar->removeAvatar($avatar);
	}
	
	/**
	 * Gibt alle Avatare zum aktuellen Account zurück
	 * @return array
	 * @session
	 */
	public function getAvatars()
	{
		$serviceManager = $this->getServiceManager();

		$session = $serviceManager->get('Session')->getSession();
		$avatars = $serviceManager->get('Avatar')->getAvatarsByAccountId($session->getAccountId());
		return $serviceManager->get('Doctrine')->toArray($avatars);
	}
	
	/**
	 * Gibt den Avatar auf der Spielrunde mit dem Namen zurück
	 * @param string $name
	 * @return array
	 * @session
	 * @avatar
	 */
	public function getAvatarByName($name)
	{
		$serviceManager = $this->getServiceManager();

		$serviceAvatar = $serviceManager->get('Avatar');
		return $serviceAvatar->getAvatarByGameroundIdAndName($serviceAvatar->getAvatar()->getGameroundId(), $name)->toArray();
	}
}
