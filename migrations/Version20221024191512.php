<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221024191512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE warehouse_dimension (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(12) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warehouse_item (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, identifier VARCHAR(255) NOT NULL, status VARCHAR(64) NOT NULL, INDEX IDX_C07125CA4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warehouse_item_history (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, user_id INT NOT NULL, identifier VARCHAR(255) NOT NULL, status VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C7AEA4D4584665A (product_id), INDEX IDX_C7AEA4DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warehouse_leaf_settings (id INT AUTO_INCREMENT NOT NULL, dimension_id INT DEFAULT NULL, capacity INT DEFAULT NULL, INDEX IDX_CEA65F9F277428AD (dimension_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warehouse_structure_tree (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(8) NOT NULL, is_leaf TINYINT(1) NOT NULL, INDEX IDX_4C7ECC12727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE warehouse_item ADD CONSTRAINT FK_C07125CA4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE warehouse_item_history ADD CONSTRAINT FK_C7AEA4D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE warehouse_item_history ADD CONSTRAINT FK_C7AEA4DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE warehouse_leaf_settings ADD CONSTRAINT FK_CEA65F9F277428AD FOREIGN KEY (dimension_id) REFERENCES warehouse_dimension (id)');
        $this->addSql('ALTER TABLE warehouse_structure_tree ADD CONSTRAINT FK_4C7ECC12727ACA70 FOREIGN KEY (parent_id) REFERENCES warehouse_structure_tree (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE warehouse_leaf_settings DROP FOREIGN KEY FK_CEA65F9F277428AD');
        $this->addSql('ALTER TABLE warehouse_structure_tree DROP FOREIGN KEY FK_4C7ECC12727ACA70');
        $this->addSql('DROP TABLE warehouse_dimension');
        $this->addSql('DROP TABLE warehouse_item');
        $this->addSql('DROP TABLE warehouse_item_history');
        $this->addSql('DROP TABLE warehouse_leaf_settings');
        $this->addSql('DROP TABLE warehouse_structure_tree');
    }
}
