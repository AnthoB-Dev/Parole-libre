<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240326191845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD last_updated_by_id INT DEFAULT NULL, CHANGE parole_libre parole_libre TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66E562D849 FOREIGN KEY (last_updated_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_23A0E66E562D849 ON article (last_updated_by_id)');
        $this->addSql('ALTER TABLE user ADD is_author TINYINT(1) NOT NULL, CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66E562D849');
        $this->addSql('DROP INDEX IDX_23A0E66E562D849 ON article');
        $this->addSql('ALTER TABLE article DROP last_updated_by_id, CHANGE parole_libre parole_libre INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP is_author, CHANGE roles roles JSON NOT NULL');
    }
}
