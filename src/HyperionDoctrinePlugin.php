<?php

namespace Hyperion\Doctrine;

use Hyperion\Core\ClassTreeMapper;
use Hyperion\Core\MainEngine;
use Hyperion\Doctrine\Service\DoctrineService;
use Hyperion\Enum\PartType;
use Hyperion\Hyperion;
use Hyperion\Interfaces\PluginInterface;
use Hyperion\Model\AutoloadedComponent;
use Hyperion\Model\ClassTreeMapperPathModel;
use Hyperion\Model\Module;
use Hyperion\Model\Part;

class HyperionDoctrinePlugin implements PluginInterface
{
    public const ENTITY_TAG = 'entity';

    public static function ignition()
    {
        add_action( Hyperion::ADD_COMPONENT_EVENT, [__CLASS__, 'addDefaultEntityNamespaces']);
        add_action(Hyperion::ADD_MODULE_EVENT, [__CLASS__, 'modulePlug']);
    }

    /**
     * Appelé lors du hook ADD_MODULE_EVENT
     *
     * Enregistre le service DoctrineService avec ses dépendances.
     * Ici , ce sont l'ensemble des classes taggés avec self::ENTITY_TAG
     *
     * @param \Hyperion\Core\MainEngine $engine
     * @throws \Exception
     */
    public static function modulePlug(MainEngine $engine)
    {
        $module = new Module(
            'doctrine',
            DoctrineService::class
        );

        $module->addPart(new Part(self::ENTITY_TAG, PartType::Tag));
        $engine->addModule($module);
    }

    /**
     * Appelé lors du hook ADD_MODULE_EVENT
     *
     * @param \Hyperion\Core\MainEngine $engine
     * @throws \Exception
     */
    public static function addDefaultEntityNamespaces(MainEngine $engine)
    {
        // Rajoute son namespace dans le classTreeMapper pour qu'il puisse gérer
        ClassTreeMapper::addClassNamespace(new ClassTreeMapperPathModel(__NAMESPACE__."\\Entity", __dir__."/Entity"));
        $autoLoadedComponent = new AutoloadedComponent(
            'hyperion_default_entities',
            __NAMESPACE__."\\Entity",
            self::ENTITY_TAG
        );

        $engine->addAutoloadedComponent($autoLoadedComponent);

        ClassTreeMapper::addClassNamespace(new ClassTreeMapperPathModel(__NAMESPACE__."\\MetaEntity", __dir__."/MetaEntity"));
        $autoLoadedComponent = new AutoloadedComponent(
            'hyperion_default_metaentities',
            __NAMESPACE__."\\MetaEntity",
            self::ENTITY_TAG
        );

        $engine->addAutoloadedComponent($autoLoadedComponent);

        //DoctrineService::addEntityNamespace(new ClassTreeMapperPathModel(__NAMESPACE__."\\Entity", __dir__."/Entity"));
        //DoctrineService::addEntityNamespace(new ClassTreeMapperPathModel(__NAMESPACE__."\\MetaEntity", __dir__."/MetaEntity"));
    }

}