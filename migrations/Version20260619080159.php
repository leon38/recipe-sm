<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260619080159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE step DROP CONSTRAINT FK_43B9FE3C59D8A214');
        $this->addSql('ALTER TABLE recipe CHANGE id id VARCHAR(64) NOT NULL');
        $this->addSql('ALTER TABLE ingredient CHANGE recipe_id recipe_id VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE ingredient CHANGE id id VARCHAR(64) NOT NULL');
        $this->addSql('ALTER TABLE step CHANGE recipe_id recipe_id VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE step CHANGE id id VARCHAR(64) NOT NULL');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF787059D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE step ADD CONSTRAINT FK_43B9FE3C59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient CHANGE id id BINARY(16) NOT NULL, CHANGE recipe_id recipe_id BINARY(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE recipe CHANGE id id BINARY(16) NOT NULL');
        $this->addSql('ALTER TABLE step CHANGE id id BINARY(16) NOT NULL, CHANGE recipe_id recipe_id BINARY(16) DEFAULT NULL');
    }
}
