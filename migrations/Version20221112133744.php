<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221112133744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE warehouse_structure_tree DROP FOREIGN KEY FK_4C7ECC12727ACA70');
        $this->addSql('ALTER TABLE warehouse_structure_tree ADD CONSTRAINT FK_4C7ECC12727ACA70 FOREIGN KEY (parent_id) REFERENCES warehouse_structure_tree (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE warehouse_structure_tree DROP FOREIGN KEY FK_4C7ECC12727ACA70');
        $this->addSql('ALTER TABLE warehouse_structure_tree ADD CONSTRAINT FK_4C7ECC12727ACA70 FOREIGN KEY (parent_id) REFERENCES warehouse_structure_tree (id)');
    }
}
