<?php

namespace Hyperion\Doctrine\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;

class DoctrineService
{
	private EntityManagerInterface $entityManager;
	private static array $entityNamespaces;

	public function __construct()
    {
        self::$entityNamespaces[] = __NAMESPACE__."\Entity";
        self::$entityNamespaces[] = __NAMESPACE__."\MetaEntity";
        $this->buildEntityManager();
    }

    public static function addEntityNamespace(string $namespace)
    {
        if(!in_array($namespace, self::$entityNamespaces, true)) {
            self::$entityNamespaces[] = $namespace;
        }
    }

    public function getEntityManager() : EntityManagerInterface
    {
        return $this->entityManager;
    }

    private function buildEntityManager() : void
    {
        $config = Setup::createAnnotationMetadataConfiguration(self::$entityNamespaces, true, null, null, false);
        $this->entityManager = EntityManager::create($this->getDatabaseConfig(), $config);
        $this->entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        $config->addCustomStringFunction('STR_TO_DATE', 'DoctrineExtensions\Query\Mysql\StrToDate');
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
