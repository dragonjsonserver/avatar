<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2013 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerAvatar
 */

/**
 * @return array
 */
return [
	'dragonjsonserveravatar' => [
		'namelength' => [
			'min' => '3',
			'max' => '255',
		],
	],
	'dragonjsonserverapiannotation' => [
		'annotations' => ['avatar'],
	],
	'dragonjsonserver' => [
	    'apiclasses' => [
	        '\DragonJsonServerAvatar\Api\Avatar' => 'Avatar',
	    ],
	],
	'service_manager' => [
		'invokables' => [
            'Avatar' => '\DragonJsonServerAvatar\Service\Avatar',
		],
	],
	'doctrine' => [
		'driver' => [
			'DragonJsonServerAvatar_driver' => [
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => [
					__DIR__ . '/../src/DragonJsonServerAvatar/Entity'
				],
			],
			'orm_default' => [
				'drivers' => [
					'DragonJsonServerAvatar\Entity' => 'DragonJsonServerAvatar_driver'
				],
			],
		],
	],
];
