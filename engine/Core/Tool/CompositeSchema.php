<?php
namespace Core\Tool;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

/**
 * Class CompositeSchema
 * @package Core\Tool
 */
class CompositeSchema
{

    /**
     * Related pairs entity manager - scheme tool
     * @var array
     */
    protected $relatedPairs = [];

    /**
     * @param array $entityManagers
     */
    public function __construct(array $entityManagers)
    {
        foreach ($entityManagers as $entityManager) {
            if (!$entityManager instanceof EntityManager) {
                continue;
            }

            $this->relatedPairs[] = [
                'entityManager' => $entityManager,
                'schemaTool'    => new SchemaTool($entityManager)
            ];
        }
    }

    /**
     * @param bool $withDrop - skip drop
     * @return array
     */
    public function generateSchemaDiff($withDrop = false, $withKey = false)
    {
        $sqlList = [];

        foreach ($this->relatedPairs as $pair) {
            $conn       = $pair['entityManager']->getConnection();
            $platform   = $conn->getDatabasePlatform();
            $metadata   = $pair['entityManager']->getMetadataFactory()->getAllMetadata();
            $fromSchema = $conn->getSchemaManager()->createSchema();
            $toSchema   = $pair['schemaTool']->getSchemaFromMetadata($metadata);
            foreach ($fromSchema->getMigrateToSql($toSchema, $platform) as $sqlCommand) {
                if (!$withDrop && strstr($sqlCommand, "DROP")) {
                    continue;
                }

                if (!$withKey && strstr($sqlCommand, "FOREIGN KEY")) {
                    continue;
                }

                $sqlList[] = $sqlCommand;
            }
        }

        return $sqlList;
    }
}