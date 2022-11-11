<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221109182137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE warehouse_item ADD node_id INT NOT NULL');
        $this->addSql('ALTER TABLE warehouse_item ADD CONSTRAINT FK_C07125CA460D9FD7 FOREIGN KEY (node_id) REFERENCES warehouse_structure_tree (id)');
        $this->addSql('CREATE INDEX IDX_C07125CA460D9FD7 ON warehouse_item (node_id)');
        $this->addSql('ALTER TABLE warehouse_leaf_settings ADD node_id INT NOT NULL');
        $this->addSql('ALTER TABLE warehouse_leaf_settings ADD CONSTRAINT FK_CEA65F9F460D9FD7 FOREIGN KEY (node_id) REFERENCES warehouse_structure_tree (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CEA65F9F460D9FD7 ON warehouse_leaf_settings (node_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE warehouse_item DROP FOREIGN KEY FK_C07125CA460D9FD7');
        $this->addSql('DROP INDEX IDX_C07125CA460D9FD7 ON warehouse_item');
        $this->addSql('ALTER TABLE warehouse_item DROP node_id');
        $this->addSql('ALTER TABLE warehouse_leaf_settings DROP FOREIGN KEY FK_CEA65F9F460D9FD7');
        $this->addSql('DROP INDEX UNIQ_CEA65F9F460D9FD7 ON warehouse_leaf_settings');
        $this->addSql('ALTER TABLE warehouse_leaf_settings DROP node_id');
    }
}
