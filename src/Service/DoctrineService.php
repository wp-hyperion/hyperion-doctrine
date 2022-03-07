<?php

namespace Hyperion\Doctrine\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use Hyperion\Model\ClassTreeMapperPathModel;
use Hyperion\Toolbox\Helper;

class DoctrineService
{
	private EntityManagerInterface $entityManager;
	/** @var ClassTreeMapperPathModel[] */
	private static array $entityNSPaths = [];

	public function __construct()
    {
        $this->buildEntityManager();
    }

    public static function addEntityNamespace(ClassTreeMapperPathModel $classTreeMapperPathModel)
    {
        $id = md5(serialize($classTreeMapperPathModel));
        if(array_key_exists($id, self::$entityNSPaths)) {
            throw new \Exception("Ce namespace d'entité (".$classTreeMapperPathModel->getNamespace().")  a déjà été ajouté");
        }

        self::$entityNSPaths[$id] = Helper::realPathForNamespace($classTreeMapperPathModel->getNamespace());
    }

    public function getEntityManager() : EntityManagerInterface
    {
        return $this->entityManager;
    }

    private function buildEntityManager() : void
    {
        $config = Setup::createAnnotationMetadataConfiguration(self::$entityNSPaths, true, null, null, false);
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
            'host' => getenv('DB_HOST'),
            'prefix' => getenv('DB_PREFIX') ?: 'wp_'
        ];
    }
}
