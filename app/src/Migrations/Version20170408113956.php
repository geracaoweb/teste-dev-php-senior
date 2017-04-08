<?php

namespace Acme\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170408113956 extends AbstractMigration
{
    /**
    * @param Schema $schema
    */
    public function up(Schema $schema){
        $this->addSql('ALTER TABLE tasks_has_tags ADD CONSTRAINT fk_tags FOREIGN KEY (id_tag) REFERENCES tags (id)');
    }
    
    /**
    * @param Schema $schema
    */
    public function down(Schema $schema)
    {
        $this->addSQl('ALTER TABLE tasks_has_tags DROP FOREIGN KEY fk_tags');
    }
}
