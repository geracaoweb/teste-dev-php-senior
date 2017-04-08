<?php

namespace Acme\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170407235951 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {
        $tags = $schema->createTable('tasks_has_tags');
        $tags->addColumn('id_task', 'integer', ['unsigned' => true]);
        $tags->addColumn('id_tag', 'integer', ['unsigned' => true]);
        $tags->setPrimaryKey(['id_tag', 'id_task']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        $schema->dropTable('tasks_has_tags');
    }
}
