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
 * Eventklasse für die Erstellung eines Avatars
 */
class CreateAvatar extends \Zend\EventManager\Event
{
	/**
	 * @var string
	 */
	protected $name = 'CreateAvatar';

    /**
     * Setzt den Avatar der erstellt wurde
     * @param \DragonJsonServerAvatar\Entity\Avatar $avatar
     * @return CreateAvatar
     */
    public function setAvatar(\DragonJsonServerAvatar\Entity\Avatar $avatar)
    {
        $this->setParam('avatar', $avatar);
        return $this;
    }

    /**
     * Gibt den Avatar der erstellt wurde zurück
     * @return \DragonJsonServerAvatar\Entity\Avatar
     */
    public function getAvatar()
    {
        return $this->getParam('avatar');
    }
}
