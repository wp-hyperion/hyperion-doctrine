<?php

namespace Hyperion\Doctrine\Service;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use Hyperion\Doctrine\Event\TablePrefixSubscriber;
use Hyperion\Doctrine\Type\EmptyStringNullable;
use Hyperion\Loader\HyperionLoader;

class DoctrineService
{
    private EntityManagerInterface $entityManager;
    private static array $entityNamespaces;

    public function __construct()
    {
        self::$entityNamespaces = ["Hyperion\Doctrine\Entity","Hyperion\Doctrine\MetaEntity"];
        $this->buildEntityManager();
    }

    public static function addEntityNamespace(string $namespace)
    {
        if (!in_array($namespace, self::$entityNamespaces, true)) {
            self::$entityNamespaces[] = $namespace;
        }
    }

    public function getEntityManager() : EntityManagerInterface
    {
        return $this->entityManager;
    }

    private function buildEntityManager() : void
    {
        /** Récupération des répertoires */
        $loader = HyperionLoader::getLoader();
        $folders = [];
        if (false === sort(self::$entityNamespaces, SORT_STRING)) {
            throw new \Exception("Trie des namespaces impossible ?!");
        }

        foreach (self::$entityNamespaces as $namespace) {
            $parentNamespace = substr($namespace, 0, strlen($namespace) - strpos(strrev($namespace), '\\'));
            foreach ($loader->getPrefixesPsr4() as $rootNamespace => $directories) {
                if ($parentNamespace === $rootNamespace) {
                    foreach ($directories as $dir) {
                        $folders[] = $dir . "/" . str_replace('\\', '/', substr($namespace, strlen($rootNamespace)));
                    }
                }
            }
        }

        $config = Setup::createAnnotationMetadataConfiguration($folders, true, null, null, false);
        $this->entityManager = EntityManager::create($this->getDatabaseConfig(), $config, $this->addEvents());
        Type::addType('strnullable', EmptyStringNullable::class);
        $this->entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        $this->entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('strnullable', 'strnullable');
        $config->addCustomStringFunction('STR_TO_DATE', 'DoctrineExtensions\Query\Mysql\StrToDate');
    }

    private function addEvents() : EventManager
    {
        $evm = new EventManager();
        $evm->addEventSubscriber(new TablePrefixSubscriber());

        return $evm;
    }

    private function getDatabaseConfig() : array
    {
        return [
            'driver' => 'pdo_mysql',
            'user' =>getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'dbname' => getenv('DB_NAME'),
            'host' => getenv('DB_HOST') ?: 'localhost',
            'prefix' => getenv('DB_PREFIX') ?: 'wp_'
        ];
    }
}
