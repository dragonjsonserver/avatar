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
 * Eventklasse für das Laden eines Avatars bei jedem Request
 */
class LoadAvatar extends \Zend\EventManager\Event
{
	/**
	 * @var string
	 */
	protected $name = 'LoadAvatar';

    /**
     * Setzt den Avatar der beim Request geladen wurde
     * @param \DragonJsonServerAvatar\Entity\Avatar $avatar
     * @return LoadAvatar
     */
    public function setAvatar(\DragonJsonServerAvatar\Entity\Avatar $avatar)
    {
        $this->setParam('avatar', $avatar);
        return $this;
    }

    /**
     * Gibt den Avatar der beim Request geladen wurde zurück
     * @return \DragonJsonServerAvatar\Entity\Avatar
     */
    public function getAvatar()
    {
        return $this->getParam('avatar');
    }
}
