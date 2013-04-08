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
		$configNamelength = $this->getServiceManager()->get('Config')['avatar']['namelength'];
		$validator = (new \Zend\Validator\StringLength())
			->setMin($configNamelength['min'])
			->setMax($configNamelength['max']);
		if (!$validator->isValid($name)) {
			throw new \DragonJsonServer\Exception(
				'invalid name', 
				['name' => $name, 'length' => $configNamelength]
			);
		}
	}

	/**
	 * Erstellt einen Avatar auf der Spielrunde
	 * @param \DragonJsonServerAccount\Entity\Account $account
	 * @param integer $gameround_id
	 * @param string $name
	 */
	public function createAvatar(\DragonJsonServerAccount\Entity\Account $account, $gameround_id, $name)
	{
		$entityManager = $this->getEntityManager();

		$avatar = (new \DragonJsonServerAvatar\Entity\Avatar())
			->setAccountId($account->getAccountId())
			->setGameroundId($gameround_id)
			->setName($name);
		$entityManager->persist($avatar);
		$entityManager->flush();
		$this->getEventManager()->trigger(
			(new \DragonJsonServerAvatar\Event\CreateAvatar())
				->setTarget($this)
				->setAvatar($avatar)
		);
	}
	
	/**
	 * Entfernt den Avatar von der Spielrunde
	 * @param \DragonJsonServerAvatar\Entity\Avatar $avatar
	 * @return Avatar
	 */
	public function removeAvatar(\DragonJsonServerAvatar\Entity\Avatar $avatar)
	{
		$entityManager = $this->getEntityManager();

		$this->getEventManager()->trigger(
			(new \DragonJsonServerAvatar\Event\RemoveAvatar())
				->setTarget($this)
				->setAvatar($avatar)
		);
		$entityManager->remove($avatar);
		$entityManager->flush();
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

		$conditions = ['avatar_id' => $avatar_id];
		$avatar = $entityManager
			->getRepository('\DragonJsonServerAvatar\Entity\Avatar')
		    ->findOneBy($conditions);
		if (null === $avatar) {
			throw new \DragonJsonServer\Exception('invalid avatar_id', $conditions);
		}
		return $avatar;
	}
	
	/**
	 * Gibt den Avatar mit der übergebenen Spielrunde und dem Namen zurück
	 * @param integer $gameround_id
	 * @param string $name
	 * @param boolean $throwException
	 * @return \DragonJsonServerAvatar\Entity\Avatar
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
