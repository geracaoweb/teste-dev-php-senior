<?php

namespace Acme\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170407232308 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $tagTable = $schema->createTable('tags');
        $tagTable->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement'=>true]);
        $tagTable->addColumn('title', 'string', ['length' => 100, 'unique' => true]);
        $tagTable->addColumn('color', 'string', ['length' => 10]);
        $tagTable->setPrimaryKey(['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('tags');
    }
}
