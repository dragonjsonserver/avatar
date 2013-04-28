<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2013 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerAvatar
 */

namespace DragonJsonServerAvatar\Service;

/**
 * Serviceklasse zur Verwaltung von Avataren
 */
class Avatar
{
	use \DragonJsonServer\ServiceManagerTrait;
	use \DragonJsonServer\EventManagerTrait;
	use \DragonJsonServerDoctrine\EntityManagerTrait;
	
	/**
	 * @var \DragonJsonServerAvatar\Entity\Avatar
	 */
	protected $avatar;
	
    /**
	 * Validiert den übergebenen Namen
	 * @param string $name
     * @throws \DragonJsonServer\Exception
	 */
	public function validateName($name)
	{
		$filter = new \Zend\Filter\StringTrim();
		if ($name != $filter->filter($name)) {
			throw new \DragonJsonServer\Exception('invalid name', ['name' => $name]);
		}
		$namelength = $this->getServiceManager()->get('Config')['dragonjsonserveravatar']['namelength'];
		$validator = (new \Zend\Validator\StringLength())
			->setMin($namelength['min'])
			->setMax($namelength['max']);
		if (!$validator->isValid($name)) {
			throw new \DragonJsonServer\Exception(
				'invalid name', 
				['name' => $name, 'namelength' => $namelength]
			);
		}
	}

	/**
	 * Erstellt einen Avatar auf der Spielrunde
	 * @param integer $account_id
	 * @param integer $gameround_id
	 * @param string $name
	 * @return \DragonJsonServerAvatar\Entity\Avatar
	 */
	public function createAvatar($account_id, $gameround_id, $name)
	{
		$avatar = (new \DragonJsonServerAvatar\Entity\Avatar())
			->setAccountId($account_id)
			->setGameroundId($gameround_id)
			->setName($name);
		$this->getServiceManager()->get('Doctrine')->transactional(function ($entityManager) use ($avatar) {
			$entityManager->persist($avatar);
			$entityManager->flush();
			$this->getEventManager()->trigger(
				(new \DragonJsonServerAvatar\Event\CreateAvatar())
					->setTarget($this)
					->setAvatar($avatar)
			);
		});
		return $avatar;
	}
	
	/**
	 * Entfernt den Avatar von der Spielrunde
	 * @param \DragonJsonServerAvatar\Entity\Avatar $avatar
	 * @return Avatar
	 */
	public function removeAvatar(\DragonJsonServerAvatar\Entity\Avatar $avatar)
	{
		$this->getServiceManager()->get('Doctrine')->transactional(function ($entityManager) use ($avatar) {
			$this->getEventManager()->trigger(
				(new \DragonJsonServerAvatar\Event\RemoveAvatar())
					->setTarget($this)
					->setAvatar($avatar)
			);
			$entityManager->remove($avatar);
			$entityManager->flush();
		});
		return $this;
	}
	
	/**
	 * Setzt den aktuellen Avatar
	 * @param \DragonJsonServerAvatar\Entity\Avatar $avatar
	 * @return Avatar
	 */
	public function setAvatar(\DragonJsonServerAvatar\Entity\Avatar $avatar)
	{
		$this->avatar = $avatar;
		return $this;
	}
	
	/**
	 * Gibt den aktuellen Avatar zurück
	 * @return \DragonJsonServerAvatar\Entity\Avatar|null
	 */
	public function getAvatar()
	{
		return $this->avatar;
	}
	
	/**
	 * Gibt den Avatar mit der übergebenen AvatarId zurück
	 * @param integer $avatar_id
	 * @return \DragonJsonServerAvatar\Entity\Avatar
     * @throws \DragonJsonServer\Exception
	 */
	public function getAvatarByAvatarId($avatar_id)
	{
		$entityManager = $this->getEntityManager();

		$avatar = $entityManager->find('\DragonJsonServerAvatar\Entity\Avatar', $avatar_id);
		if (null === $avatar) {
			throw new \DragonJsonServer\Exception('invalid avatar_id', ['avatar_id' => $avatar_id]);
		}
		return $avatar;
	}
	
	/**
	 * Gibt den Avatar mit der übergebenen Spielrunde und dem Namen zurück
	 * @param integer $gameround_id
	 * @param string $name
	 * @param boolean $throwException
	 * @return \DragonJsonServerAvatar\Entity\Avatar|null
     * @throws \DragonJsonServer\Exception
	 */
	public function getAvatarByGameroundIdAndName($gameround_id, $name, $throwException = true)
	{
		$entityManager = $this->getEntityManager();

		$conditions = ['gameround_id' => $gameround_id, 'name' => $name];
		$avatar = $entityManager
			->getRepository('\DragonJsonServerAvatar\Entity\Avatar')
		    ->findOneBy($conditions);
		if (null === $avatar && $throwException) {
			throw new \DragonJsonServer\Exception('invalid gameround_id or name', $conditions);
		}
		return $avatar;
	}
	
	/**
	 * Gibt die Avatare mit der übergebenen AccountID zurück
	 * @param integer $account_id
	 * @return array
	 */
	public function getAvatarsByAccountId($account_id)
	{
		$entityManager = $this->getEntityManager();

		return $entityManager
			->getRepository('\DragonJsonServerAvatar\Entity\Avatar')
		    ->findBy(['account_id' => $account_id]);
	}
}
