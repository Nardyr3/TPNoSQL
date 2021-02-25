<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210225110558 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE request (id INT AUTO_INCREMENT NOT NULL, database_name_id INT NOT NULL, date_time DATETIME NOT NULL, result TEXT DEFAULT NULL, execution_time TIME NOT NULL, INDEX IDX_3B978F9FE46CC806 (database_name_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FE46CC806 FOREIGN KEY (database_name_id) REFERENCES `database` (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE request');
    }
}
