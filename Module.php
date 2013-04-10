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
    	$sharedManager->attach('DragonJsonServer\Service\Server', 'request', 
	    	function (\DragonJsonServer\Event\Request $eventRequest) {
	    		$serviceManager = $this->getServiceManager();
	    		$request = $eventRequest->getRequest();
	    		list ($classname, $methodname) = $serviceManager->get('Server')->parseMethod($request->getMethod());
	    		$classreflection = new \Zend\Code\Reflection\ClassReflection($classname);
	    		if (!$classreflection->getMethod($methodname)->getDocBlock()->hasTag('avatar')) {
	    			return;
	    		}
	    		$session = $serviceManager->get('Session')->getSession();
	    		if (null === $session) {
					throw new \DragonJsonServer\Exception('missing session');
	    		}
	    		$serviceAvatar = $serviceManager->get('Avatar');
	    		$avatar = $serviceAvatar->getAvatarByAvatarId($request->getParam('avatar_id'));
	    		if ($session->getAccountId() != $avatar->getAccountId()) {
					throw new \DragonJsonServer\Exception(
						'account_id not match', 
						['session' => $session->toArray(), 'avatar' => $avatar->toArray()] 
					);
	    		}
	    		$serviceAvatar->setAvatar($avatar);
	    	}
    	);
    	$sharedManager->attach('DragonJsonServer\Service\Server', 'servicemap', 
    		function (\DragonJsonServer\Event\Servicemap $eventServicemap) {
	    		$serviceManager = $this->getServiceManager();
	    		$serviceServer = $serviceManager->get('Server');
		        foreach ($eventServicemap->getServicemap()->getServices() as $method => $service) {
	    			list ($classname, $methodname) = $serviceServer->parseMethod($method);
		            $classreflection = new \Zend\Code\Reflection\ClassReflection($classname);
		            if (!$classreflection->getMethod($methodname)->getDocBlock()->hasTag('avatar')) {
		                continue;
		            }
		            $service->addParams([
		                [
		                    'type' => 'integer',
		                    'name' => 'avatar_id',
		                    'optional' => false,
		                ],
		            ]);
		        }
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
