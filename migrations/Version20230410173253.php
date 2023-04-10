<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230410173253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE allegro_account (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, client_id VARCHAR(255) NOT NULL, client_secret VARCHAR(255) NOT NULL, active SMALLINT NOT NULL, device_code VARCHAR(255) DEFAULT NULL, refresh_token VARCHAR(2048) DEFAULT NULL, access_token VARCHAR(2048) DEFAULT NULL, code_expires_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', token_expires_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_sandbox SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE config_integer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(86) NOT NULL, description VARCHAR(1024) NOT NULL, auction_price DOUBLE PRECISION DEFAULT NULL, buy_now_price DOUBLE PRECISION DEFAULT NULL, sale_type SMALLINT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D34A04ADA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_parameter (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, name VARCHAR(86) NOT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_4437279D4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_picture (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, type_id INT NOT NULL, path VARCHAR(255) NOT NULL, INDEX IDX_C70254394584665A (product_id), INDEX IDX_C7025439C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_picture_type (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(86) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warehouse_dimension (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(12) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warehouse_item (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, node_id INT NOT NULL, identifier VARCHAR(255) NOT NULL, status VARCHAR(64) NOT NULL, position SMALLINT NOT NULL, INDEX IDX_C07125CA4584665A (product_id), INDEX IDX_C07125CA460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warehouse_item_history (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, user_id INT NOT NULL, identifier VARCHAR(255) NOT NULL, status VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C7AEA4D4584665A (product_id), INDEX IDX_C7AEA4DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warehouse_leaf_settings (id INT AUTO_INCREMENT NOT NULL, dimension_id INT DEFAULT NULL, node_id INT NOT NULL, capacity INT DEFAULT NULL, INDEX IDX_CEA65F9F277428AD (dimension_id), UNIQUE INDEX UNIQ_CEA65F9F460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warehouse_structure_tree (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(8) NOT NULL, is_leaf TINYINT(1) NOT NULL, tree_path VARCHAR(255) NOT NULL, INDEX IDX_4C7ECC12727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product_parameter ADD CONSTRAINT FK_4437279D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_picture ADD CONSTRAINT FK_C70254394584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_picture ADD CONSTRAINT FK_C7025439C54C8C93 FOREIGN KEY (type_id) REFERENCES product_picture_type (id)');
        $this->addSql('ALTER TABLE warehouse_item ADD CONSTRAINT FK_C07125CA4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE warehouse_item ADD CONSTRAINT FK_C07125CA460D9FD7 FOREIGN KEY (node_id) REFERENCES warehouse_structure_tree (id)');
        $this->addSql('ALTER TABLE warehouse_item_history ADD CONSTRAINT FK_C7AEA4D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE warehouse_item_history ADD CONSTRAINT FK_C7AEA4DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE warehouse_leaf_settings ADD CONSTRAINT FK_CEA65F9F277428AD FOREIGN KEY (dimension_id) REFERENCES warehouse_dimension (id)');
        $this->addSql('ALTER TABLE warehouse_leaf_settings ADD CONSTRAINT FK_CEA65F9F460D9FD7 FOREIGN KEY (node_id) REFERENCES warehouse_structure_tree (id)');
        $this->addSql('ALTER TABLE warehouse_structure_tree ADD CONSTRAINT FK_4C7ECC12727ACA70 FOREIGN KEY (parent_id) REFERENCES warehouse_structure_tree (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_parameter DROP FOREIGN KEY FK_4437279D4584665A');
        $this->addSql('ALTER TABLE product_picture DROP FOREIGN KEY FK_C70254394584665A');
        $this->addSql('ALTER TABLE warehouse_item DROP FOREIGN KEY FK_C07125CA4584665A');
        $this->addSql('ALTER TABLE warehouse_item_history DROP FOREIGN KEY FK_C7AEA4D4584665A');
        $this->addSql('ALTER TABLE product_picture DROP FOREIGN KEY FK_C7025439C54C8C93');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA76ED395');
        $this->addSql('ALTER TABLE warehouse_item_history DROP FOREIGN KEY FK_C7AEA4DA76ED395');
        $this->addSql('ALTER TABLE warehouse_leaf_settings DROP FOREIGN KEY FK_CEA65F9F277428AD');
        $this->addSql('ALTER TABLE warehouse_item DROP FOREIGN KEY FK_C07125CA460D9FD7');
        $this->addSql('ALTER TABLE warehouse_leaf_settings DROP FOREIGN KEY FK_CEA65F9F460D9FD7');
        $this->addSql('ALTER TABLE warehouse_structure_tree DROP FOREIGN KEY FK_4C7ECC12727ACA70');
        $this->addSql('DROP TABLE allegro_account');
        $this->addSql('DROP TABLE config_integer');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_parameter');
        $this->addSql('DROP TABLE product_picture');
        $this->addSql('DROP TABLE product_picture_type');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE warehouse_dimension');
        $this->addSql('DROP TABLE warehouse_item');
        $this->addSql('DROP TABLE warehouse_item_history');
        $this->addSql('DROP TABLE warehouse_leaf_settings');
        $this->addSql('DROP TABLE warehouse_structure_tree');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
