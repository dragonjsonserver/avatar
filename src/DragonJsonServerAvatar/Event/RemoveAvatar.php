<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2013 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerAvatar
 */

namespace DragonJsonServerAvatar\Event;

/**
 * Eventklasse für die Entfernung eines Avatars
 */
class RemoveAvatar extends \Zend\EventManager\Event
{
	/**
	 * @var string
	 */
	protected $name = 'removeavatar';

    /**
     * Setzt den Avatar bevor er entfernt wird
     * @param \DragonJsonServerAvatar\Entity\Avatar $avatar
     * @return RemoveAvatar
     */
    public function setAvatar(\DragonJsonServerAvatar\Entity\Avatar $avatar)
    {
        $this->setParam('avatar', $avatar);
        return $this;
    }

    /**
     * Gibt den Avatar bevor er entfernt wird zurück
     * @return \DragonJsonServerAvatar\Entity\Avatar
     */
    public function getAvatar()
    {
        return $this->getParam('avatar');
    }
}
