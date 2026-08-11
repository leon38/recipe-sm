<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260811142928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id VARCHAR(64) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE ingredient (id VARCHAR(64) NOT NULL, name VARCHAR(150) NOT NULL, normalized_name VARCHAR(150) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE recipe (id VARCHAR(64) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, prep_time INT DEFAULT NULL, cook_time INT DEFAULT NULL, difficulty VARCHAR(100) DEFAULT NULL, servings INT NOT NULL, season VARCHAR(255) DEFAULT NULL, source_url VARCHAR(150) DEFAULT NULL, image_url LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE recipe_tag (recipe_id VARCHAR(64) NOT NULL, tag_id INT NOT NULL, INDEX IDX_72DED3CF59D8A214 (recipe_id), INDEX IDX_72DED3CFBAD26311 (tag_id), PRIMARY KEY (recipe_id, tag_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE recipe_category (recipe_id VARCHAR(64) NOT NULL, category_id VARCHAR(64) NOT NULL, INDEX IDX_70DCBC5F59D8A214 (recipe_id), INDEX IDX_70DCBC5F12469DE2 (category_id), PRIMARY KEY (recipe_id, category_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE recipe_ingredient (id VARCHAR(64) NOT NULL, name VARCHAR(255) NOT NULL, quantity DOUBLE PRECISION DEFAULT NULL, unit VARCHAR(100) DEFAULT NULL, recipe_id VARCHAR(64) DEFAULT NULL, ingredient_id VARCHAR(64) DEFAULT NULL, INDEX IDX_22D1FE1359D8A214 (recipe_id), INDEX IDX_22D1FE13933FE08C (ingredient_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE step (id VARCHAR(64) NOT NULL, position INT NOT NULL, instruction LONGTEXT NOT NULL, recipe_id VARCHAR(64) DEFAULT NULL, INDEX IDX_43B9FE3C59D8A214 (recipe_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE recipe_tag ADD CONSTRAINT FK_72DED3CF59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_tag ADD CONSTRAINT FK_72DED3CFBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_category ADD CONSTRAINT FK_70DCBC5F59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_category ADD CONSTRAINT FK_70DCBC5F12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE1359D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE13933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE step ADD CONSTRAINT FK_43B9FE3C59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_tag DROP FOREIGN KEY FK_72DED3CF59D8A214');
        $this->addSql('ALTER TABLE recipe_tag DROP FOREIGN KEY FK_72DED3CFBAD26311');
        $this->addSql('ALTER TABLE recipe_category DROP FOREIGN KEY FK_70DCBC5F59D8A214');
        $this->addSql('ALTER TABLE recipe_category DROP FOREIGN KEY FK_70DCBC5F12469DE2');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE1359D8A214');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE13933FE08C');
        $this->addSql('ALTER TABLE step DROP FOREIGN KEY FK_43B9FE3C59D8A214');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_tag');
        $this->addSql('DROP TABLE recipe_category');
        $this->addSql('DROP TABLE recipe_ingredient');
        $this->addSql('DROP TABLE step');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
