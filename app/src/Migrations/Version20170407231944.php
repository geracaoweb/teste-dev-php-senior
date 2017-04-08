<?php

namespace Acme\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
* Auto-generated Migration: Please modify to your needs!
*/
class Version20170407231944 extends AbstractMigration
{
    /**
    * @param Schema $schema
    */
    public function up(Schema $schema) {
        $taskTable = $schema->createTable('tasks');
        $taskTable->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement'=>true]);
        $taskTable->addColumn('description', 'string', ['length' => 100]);
        $taskTable->addColumn('message', 'string', ['length' => 100]);
        $taskTable->setPrimaryKey(['id']);
        
    }
    
    /**
    * @param Schema $schema
    */
    public function down(Schema $schema) {
        $schema->dropTable('tasks');
    }
}