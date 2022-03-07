<?php

namespace Hyperion\Core\Services;

use Hyperion\Core\Helper\ToolMethodsHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\SchemaValidator;
use Doctrine\ORM\Tools\Setup;
use Hyperion\Core\Subscriber\TablePrefixSubscriber;
use Roots\WPConfig\Config;
use Roots\WPConfig\Exceptions\UndefinedConfigKeyException;

class DoctrineService
{
	private EntityManagerInterface $entityManager;
	private array $entities;
	private array $dbParams;

    /**
     * @param array $entities
     * @param string|null $additionalEntityNS
     * @throws \Doctrine\DBAL\Exception
     * @throws \Doctrine\ORM\ORMException
     */
    public function __construct(array $entities)
    {
        $autoloaded = ConfigParserService::getConfig()['autoload'];
        foreach($autoloaded as $key => $desc) {
            if(array_key_exists('tag', $desc) && $desc['tag'] === 'entity') {
                $additionalEntityNSPath = ToolMethodsHelper::realPathForNamespace($desc['namespace']);
                $paths[] = $additionalEntityNSPath;
            }
        }

        // the connection configuration
        if(class_exists(Config::class)) {
            $this->dbParams = array(
                'driver' => 'pdo_mysql',
                'user' => Config::get('DB_USER'),
                'password' => Config::get('DB_PASSWORD'),
                'dbname' => Config::get('DB_NAME'),
                'host' => Config::get('DB_HOST'),
            );
            try {
            	$this->dbParams['prefix'] = Config::get('DB_PREFIX');
            } catch (UndefinedConfigKeyException $exception) {
            	$this->dbParams['prefix'] = "wp_";
            }
            
        } else {
            $this->dbParams = array(
                'driver' => 'pdo_mysql',
                'user' => DB_USER,
                'password' => DB_PASSWORD,
                'dbname' => DB_NAME,
                'host' => DB_HOST,
	            'prefix' => defined(DB_PREFIX) ? DB_PREFIX : 'wp_'
            );
        }

        $isDevMode = true;
        
        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
        $entityManager = EntityManager::create($this->dbParams, $config);
        $entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        $config->addCustomStringFunction('STR_TO_DATE', 'DoctrineExtensions\Query\Mysql\StrToDate');

        $this->entityManager = $entityManager;
        $this->entities = $entities;
    }

    public function checkSchema()
    {
        $validator = new SchemaValidator($this->entityManager);
        $errors = $validator->validateMapping();

        if (count($errors) > 0) {
            $message = PHP_EOL;
            foreach ($errors as $class => $classErrors) {
                $message .= "- " . $class . ":" . PHP_EOL . implode(PHP_EOL, $classErrors) . "<br>";
            }
            echo $message; die;
        }
    }
    
    public function getEntityManager() : EntityManagerInterface
    {
    	return $this->entityManager;
    }

    public function createTablesFromEntities()
    {
        $tool = new SchemaTool($this->entityManager);
        $fromSchema = $tool->getSchemaFromMetadata($this->getMetadataClassFromEntityName());

        $allTables = $this->entityManager->getConnection()->getSchemaManager()->listTableNames();
        $tablesNames = array_map(function($tableName) { return substr($tableName, strpos($tableName,'.') + 1); },$fromSchema->getTableNames());
        $tableToDrop = array_filter($tablesNames, function($tableName) use ($allTables) { return in_array($tableName, $allTables, true); });

        foreach ($tableToDrop as $tableName) {
            $fromSchema->dropTable($tableName);
        }

        $sqlCommands = $fromSchema->toSql($this->entityManager->getConnection()->getDatabasePlatform());
        $this->entityManager->beginTransaction();
        foreach ($sqlCommands as $sqlCommand) {
            $this->entityManager->getConnection()->executeQuery($sqlCommand);
        }

        $this->entityManager->commit();
    }

    public function destroyTablesFromEntities()
    {
        $tool = new SchemaTool($this->entityManager);
        $dropCommands = $tool->getDropSchemaSQL($this->getMetadataClassFromEntityName());

        $this->entityManager->beginTransaction();
        foreach ($dropCommands as $dropCommand) {
            $this->entityManager->getConnection()->executeQuery($dropCommand);
        }
        $this->entityManager->commit();
    }

    private function getMetadataClassFromEntityName() : array
    {
        $classesMetadata = [];
        foreach($this->entities as $entityName) {
            $classesMetadata[]  = $this->entityManager->getMetadataFactory()->getMetadataFor(get_class($entityName));

        }

        return $classesMetadata;
    }

    public function setDatabaseFixes()
    {
        $sql = "
            ALTER TABLE ".$this->dbParams['prefix']."posts modify post_parent bigint unsigned null;
            CREATE TRIGGER triggerInsertZeroToNull BEFORE INSERT ON ".$this->dbParams['prefix']."posts FOR EACH ROW IF NEW.post_parent = 0 THEN SET NEW.post_parent = null; END IF;
            CREATE TRIGGER triggerUpdateZeroToNull BEFORE UPDATE ON ".$this->dbParams['prefix']."posts FOR EACH ROW IF NEW.post_parent = 0 THEN SET NEW.post_parent = null; END IF;
            UPDATE ".$this->dbParams['prefix']."posts SET post_parent = null WHERE post_parent=0;
        ";
        $this->entityManager->getConnection()->executeQuery($sql);
    }

    public function removeDatabaseFixes()
    {
        $sql = "
            UPDATE ".$this->dbParams['prefix']."posts SET post_parent = 0 WHERE post_parent IS NULL;
            DROP TRIGGER triggerInsertZeroToNull;
            DROP TRIGGER triggerUpdateZeroToNull;
            ALTER TABLE ".$this->dbParams['prefix']."posts modify post_parent bigint unsigned default 0 not null;
        ";
        $this->entityManager->getConnection()->executeQuery($sql);
    }
}
