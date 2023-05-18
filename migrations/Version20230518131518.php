<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230518131518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD ban_reason_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64927236837 FOREIGN KEY (ban_reason_id) REFERENCES report_reason (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64927236837 ON user (ban_reason_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64927236837');
        $this->addSql('DROP INDEX IDX_8D93D64927236837 ON user');
        $this->addSql('ALTER TABLE user DROP ban_reason_id');
    }
}
