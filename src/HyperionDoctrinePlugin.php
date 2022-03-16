<?php

namespace Hyperion\Doctrine;

use Hyperion\Doctrine\Command\SyncEntityWithModel;
use Hyperion\Doctrine\Service\DoctrineService;
use Hyperion\Loader\Collection\AutoloadedNamespaceCollection;
use Hyperion\Loader\HyperionLoader;
use Hyperion\Loader\Service\ContainerEngine;
use WP_CLI;


class HyperionDoctrinePlugin
{
    public const REGISTER_ADDITIONAL_ENTITY_NS = 'hyperion_doctrine_add_entity_ns';

    public static function init()
    {
        add_action(HyperionLoader::REGISTER_AUTOLOADED_NAMESPACE, function(AutoloadedNamespaceCollection $autoloadedNamespaceCollection) {
            $autoloadedNamespaceCollection->addAutoloadNamespace(__NAMESPACE__."\Service");
            $autoloadedNamespaceCollection->addAutoloadNamespace(__NAMESPACE__."\Command");
        }, 1);

        add_action(HyperionLoader::HYPERION_CONTAINER_READY, [__CLASS__, 'registerCliCommands'], 1);
        do_action(self::REGISTER_ADDITIONAL_ENTITY_NS, [DoctrineService::class, 'addEntityNamespace']);
    }

    public static function registerCliCommands(ContainerEngine $containerEngine)
    {
        $syncEntityWithModelCmd = $containerEngine->getContainer()->get(SyncEntityWithModel::class);
        WP_CLI::add_command(SyncEntityWithModel::COMMAND_NAME, [$syncEntityWithModelCmd, 'run']);
    }
}