<?php

namespace Hyperion\Doctrine\Command;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\SchemaValidator;
use Hyperion\Doctrine\Service\DoctrineService;

class SyncEntityWithModel
{
    private EntityManagerInterface $em;
    private SchemaValidator $validator;
    private SchemaTool $schemaTool;

    public const COMMAND_NAME = "doctrine/sync_bdd_with_model";

    public function __construct(DoctrineService $doctrineService)
    {
        $this->em = $doctrineService->getEntityManager();
        $this->validator = new SchemaValidator($this->em);
        $this->schemaTool = new SchemaTool($this->em);
    }

    public function run()
    {
        if($this->checkSchema() === false) {
            WP_CLI::error("Please fix errors described before.");
        }

        if($this->validator->schemaInSyncWithMetadata()) {
            WP_CLI::success("Schema in sync with your database");
        }

        $metadatas = $this->em->getMetadataFactory()->getAllMetadata();
        $this->schemaTool->updateSchema($metadatas, false);

        WP_CLI::success("Schema updated ! Base in sync with your schemas");
    }

    private function checkSchema() : bool
    {
        $errors = $this->validator->validateMapping();
        if(count($errors) === 0) {
            return true;
        }

        $message = PHP_EOL;
        foreach ($errors as $class => $classErrors) {
            $message .= "- $class :". PHP_EOL . implode(PHP_EOL, $classErrors);
        }
        echo $message;

        return false;
    }
}