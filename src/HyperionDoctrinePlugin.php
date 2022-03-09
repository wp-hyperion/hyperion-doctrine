<?php

namespace Hyperion\Doctrine;

use Hyperion\Doctrine\Service\DoctrineService;
use Hyperion\Loader\Collection\RegisteredModuleCollection;
use Hyperion\Loader\HyperionLoader;

class HyperionDoctrinePlugin
{
    public const REGISTER_ADDITIONAL_ENTITY_NS = 'hyperion_doctrine_add_entity_ns';

    public static function init()
    {
        add_action(HyperionLoader::REGISTER_HYPERION_MODULE, function(RegisteredModuleCollection $registeredModuleCollection) {
            $registeredModuleCollection->addModule(__NAMESPACE__);
        }, 1);

        do_action(self::REGISTER_ADDITIONAL_ENTITY_NS, [DoctrineService::class, 'addEntityNamespace']);
    }
}