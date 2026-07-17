<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260715065808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id VARCHAR(64) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE recipe_tag (recipe_id VARCHAR(64) NOT NULL, tag_id INT NOT NULL, INDEX IDX_72DED3CF59D8A214 (recipe_id), INDEX IDX_72DED3CFBAD26311 (tag_id), PRIMARY KEY (recipe_id, tag_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE step (id VARCHAR(64) NOT NULL, position INT NOT NULL, instruction LONGTEXT NOT NULL, recipe_id VARCHAR(64) DEFAULT NULL, INDEX IDX_43B9FE3C59D8A214 (recipe_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE recipe_tag ADD CONSTRAINT FK_72DED3CF59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_tag ADD CONSTRAINT FK_72DED3CFBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE step ADD CONSTRAINT FK_43B9FE3C59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE tag_recipe DROP FOREIGN KEY `FK_33C9F81B59D8A214`');
        $this->addSql('ALTER TABLE tag_recipe DROP FOREIGN KEY `FK_33C9F81BBAD26311`');
        $this->addSql('DROP TABLE tag_recipe');
        $this->addSql('DROP TABLE unit');
        $this->addSql('DROP TABLE week_menu');
        $this->addSql('DROP INDEX UNIQ_6BAF78705E237E06 ON ingredient');
        $this->addSql('ALTER TABLE ingredient ADD normalized_name VARCHAR(150) NOT NULL, DROP category, DROP description, CHANGE id id VARCHAR(64) NOT NULL, CHANGE name name VARCHAR(150) NOT NULL');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY `FK_DA88B137C6B4D386`');
        $this->addSql('DROP INDEX IDX_DA88B137C6B4D386 ON recipe');
        $this->addSql('ALTER TABLE recipe ADD title VARCHAR(255) NOT NULL, ADD difficulty VARCHAR(100) DEFAULT NULL, ADD season VARCHAR(255) DEFAULT NULL, ADD source_url VARCHAR(150) DEFAULT NULL, ADD image_url LONGTEXT NOT NULL, ADD updated_at DATETIME NOT NULL, DROP recipe_category_id, DROP name, DROP instructions, DROP picture, DROP created, CHANGE id id VARCHAR(64) NOT NULL, CHANGE updated created_at DATETIME NOT NULL');
        $this->addSql('DROP INDEX FULLTEXT__NAME ON recipe_category');
        $this->addSql('DROP INDEX UNIQ_70DCBC5F5E237E06 ON recipe_category');
        $this->addSql('ALTER TABLE recipe_category MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE recipe_category ADD recipe_id VARCHAR(64) NOT NULL, ADD category_id VARCHAR(64) NOT NULL, DROP id, DROP name, DROP color, DROP PRIMARY KEY, ADD PRIMARY KEY (recipe_id, category_id)');
        $this->addSql('ALTER TABLE recipe_category ADD CONSTRAINT FK_70DCBC5F59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_category ADD CONSTRAINT FK_70DCBC5F12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_70DCBC5F59D8A214 ON recipe_category (recipe_id)');
        $this->addSql('CREATE INDEX IDX_70DCBC5F12469DE2 ON recipe_category (category_id)');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY `FK_22D1FE13F8BD700D`');
        $this->addSql('DROP INDEX IDX_22D1FE13F8BD700D ON recipe_ingredient');
        $this->addSql('DROP INDEX ingredient_unique_idx ON recipe_ingredient');
        $this->addSql('ALTER TABLE recipe_ingredient ADD name VARCHAR(255) NOT NULL, ADD unit VARCHAR(100) DEFAULT NULL, DROP unit_id, CHANGE id id VARCHAR(64) NOT NULL, CHANGE recipe_id recipe_id VARCHAR(64) DEFAULT NULL, CHANGE ingredient_id ingredient_id VARCHAR(64) DEFAULT NULL, CHANGE quantity quantity DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('DROP INDEX FULLTEXT__NAME ON tag');
        $this->addSql('DROP INDEX UNIQ_389B7835E237E06 ON tag');
        $this->addSql('ALTER TABLE tag CHANGE name name VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tag_recipe (tag_id INT NOT NULL, recipe_id INT NOT NULL, INDEX IDX_33C9F81B59D8A214 (recipe_id), INDEX IDX_33C9F81BBAD26311 (tag_id), PRIMARY KEY (recipe_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE unit (id INT AUTO_INCREMENT NOT NULL, unit_name VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, abbreviation VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE week_menu (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, menu_date DATE NOT NULL, noon TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tag_recipe ADD CONSTRAINT `FK_33C9F81B59D8A214` FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_recipe ADD CONSTRAINT `FK_33C9F81BBAD26311` FOREIGN KEY (tag_id) REFERENCES tag (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_tag DROP FOREIGN KEY FK_72DED3CF59D8A214');
        $this->addSql('ALTER TABLE recipe_tag DROP FOREIGN KEY FK_72DED3CFBAD26311');
        $this->addSql('ALTER TABLE step DROP FOREIGN KEY FK_43B9FE3C59D8A214');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE recipe_tag');
        $this->addSql('DROP TABLE step');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE ingredient ADD category VARCHAR(255) DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL, DROP normalized_name, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6BAF78705E237E06 ON ingredient (name)');
        $this->addSql('ALTER TABLE recipe ADD recipe_category_id INT DEFAULT NULL, ADD instructions LONGTEXT DEFAULT NULL, ADD picture VARCHAR(255) NOT NULL, ADD created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated DATETIME NOT NULL, DROP difficulty, DROP season, DROP source_url, DROP image_url, DROP created_at, DROP updated_at, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE title name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT `FK_DA88B137C6B4D386` FOREIGN KEY (recipe_category_id) REFERENCES recipe_category (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_DA88B137C6B4D386 ON recipe (recipe_category_id)');
        $this->addSql('ALTER TABLE recipe_category DROP FOREIGN KEY FK_70DCBC5F59D8A214');
        $this->addSql('ALTER TABLE recipe_category DROP FOREIGN KEY FK_70DCBC5F12469DE2');
        $this->addSql('DROP INDEX IDX_70DCBC5F59D8A214 ON recipe_category');
        $this->addSql('DROP INDEX IDX_70DCBC5F12469DE2 ON recipe_category');
        $this->addSql('ALTER TABLE recipe_category ADD id INT AUTO_INCREMENT NOT NULL, ADD name VARCHAR(255) NOT NULL, ADD color VARCHAR(100) NOT NULL, DROP recipe_id, DROP category_id, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE FULLTEXT INDEX FULLTEXT__NAME ON recipe_category (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_70DCBC5F5E237E06 ON recipe_category (name)');
        $this->addSql('ALTER TABLE recipe_ingredient ADD unit_id INT DEFAULT NULL, DROP name, DROP unit, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE quantity quantity DOUBLE PRECISION NOT NULL, CHANGE recipe_id recipe_id INT NOT NULL, CHANGE ingredient_id ingredient_id INT NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT `FK_22D1FE13F8BD700D` FOREIGN KEY (unit_id) REFERENCES unit (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_22D1FE13F8BD700D ON recipe_ingredient (unit_id)');
        $this->addSql('CREATE UNIQUE INDEX ingredient_unique_idx ON recipe_ingredient (recipe_id, ingredient_id, unit_id, quantity)');
        $this->addSql('ALTER TABLE tag CHANGE name name VARCHAR(50) NOT NULL');
        $this->addSql('CREATE FULLTEXT INDEX FULLTEXT__NAME ON tag (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_389B7835E237E06 ON tag (name)');
    }
}
