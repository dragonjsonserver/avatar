<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2013 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerAvatar
 */

namespace DragonJsonServerAvatar;

/**
 * Klasse zur Initialisierung des Moduls
 */
class Module
{
    use \DragonJsonServer\ServiceManagerTrait;
	
    /**
     * Gibt die Konfiguration des Moduls zurück
     * @return array
     */
    public function getConfig()
    {
        return require __DIR__ . '/config/module.config.php';
    }

    /**
     * Gibt die Autoloaderkonfiguration des Moduls zurück
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }
    
    /**
     * Wird bei der Initialisierung des Moduls aufgerufen
     * @param \Zend\ModuleManager\ModuleManager $moduleManager
     */
    public function init(\Zend\ModuleManager\ModuleManager $moduleManager)
    {
    	$sharedManager = $moduleManager->getEventManager()->getSharedManager();
    	$sharedManager->attach('DragonJsonServerApiannotation\Module', 'request', 
	    	function (\DragonJsonServerApiannotation\Event\Request $eventRequest) {
	    		if ($eventRequest->getTag()->getName() != 'avatar') {
	    			return;
	    		}
	    		$session = $serviceManager->get('Session')->getSession();
	    		if (null === $session) {
	    			throw new \DragonJsonServer\Exception('missing session');
	    		}
	    		$serviceAvatar = $serviceManager->get('Avatar');
	    		$avatar_id = $eventRequest->getRequest()->getParam('avatar_id');
	    		$avatar = $serviceAvatar->getAvatarByAvatarId($avatar_id);
	    		if ($session->getAccountId() != $avatar->getAccountId()) {
	    			throw new \DragonJsonServer\Exception(
	    					'account_id not match',
	    					['session' => $session->toArray(), 'avatar' => $avatar->toArray()]
	    			);
	    		}
	    		$serviceAvatar->setAvatar($avatar);
	    	}
    	);
    	$sharedManager->attach('DragonJsonServerApiannotation\Module', 'servicemap', 
	    	function (\DragonJsonServerApiannotation\Event\Servicemap $eventServicemap) {
	    		if ($eventServicemap->getTag()->getName() != 'avatar') {
	    			return;
	    		}
	    		$eventServicemap->getService()->addParams([
    				[
	                    'type' => 'integer',
	                    'name' => 'avatar_id',
	    				'optional' => false,
    				],
    			]);
	    	}
    	);
    	$sharedManager->attach('DragonJsonServerAccount\Service\Account', 'removeaccount', 
	    	function (\DragonJsonServerAccount\Event\RemoveAccount $removeAccount) {
	    		$account = $removeAccount->getAccount();
	    		$serviceAvatar = $this->getServiceManager()->get('Avatar');
	    		$avatars = $serviceAvatar->getAvatarsByAccountId($account->getAccountId());
	    		foreach ($avatars as $avatar) {
	    			$serviceAvatar->removeAvatar($avatar);
	    		}
	    	}
    	);
    }
}
