<?php

namespace Acme\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
* Auto-generated Migration: Please modify to your needs!
*/
class Version20170408113322 extends AbstractMigration
{
    /**
    * @param Schema $schema
    */
    public function up(Schema $schema){
        $this->addSql('ALTER TABLE tasks_has_tags ADD CONSTRAINT fk_tasks FOREIGN KEY (id_task) REFERENCES tasks (id)');
    }
    
    /**
    * @param Schema $schema
    */
    public function down(Schema $schema)
    {
        $this->addSQl('ALTER TABLE tasks_has_tags DROP FOREIGN KEY fk_tasks');
    }
}