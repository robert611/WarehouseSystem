<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221005180607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_parameter ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_parameter ADD CONSTRAINT FK_4437279D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_4437279D4584665A ON product_parameter (product_id)');
        $this->addSql('ALTER TABLE product_picture ADD type_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_picture ADD CONSTRAINT FK_C7025439C54C8C93 FOREIGN KEY (type_id) REFERENCES product_picture_type (id)');
        $this->addSql('CREATE INDEX IDX_C7025439C54C8C93 ON product_picture (type_id)');
        $this->addSql('ALTER TABLE product_picture_type ADD value VARCHAR(86) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_parameter DROP FOREIGN KEY FK_4437279D4584665A');
        $this->addSql('DROP INDEX IDX_4437279D4584665A ON product_parameter');
        $this->addSql('ALTER TABLE product_parameter DROP product_id');
        $this->addSql('ALTER TABLE product_picture DROP FOREIGN KEY FK_C7025439C54C8C93');
        $this->addSql('DROP INDEX IDX_C7025439C54C8C93 ON product_picture');
        $this->addSql('ALTER TABLE product_picture DROP type_id');
        $this->addSql('ALTER TABLE product_picture_type DROP value');
    }
}
