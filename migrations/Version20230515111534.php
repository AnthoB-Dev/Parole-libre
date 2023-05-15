<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230515111534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, article_id INT NOT NULL, article_comment_id INT DEFAULT NULL, report_date DATETIME NOT NULL, INDEX IDX_C42F7784A76ED395 (user_id), INDEX IDX_C42F77847294869C (article_id), INDEX IDX_C42F7784C4B0AC92 (article_comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report_report_reason (report_id INT NOT NULL, report_reason_id INT NOT NULL, INDEX IDX_9B961CE34BD2A4C0 (report_id), INDEX IDX_9B961CE3770F788A (report_reason_id), PRIMARY KEY(report_id, report_reason_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77847294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784C4B0AC92 FOREIGN KEY (article_comment_id) REFERENCES article_comment (id)');
        $this->addSql('ALTER TABLE report_report_reason ADD CONSTRAINT FK_9B961CE34BD2A4C0 FOREIGN KEY (report_id) REFERENCES report (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE report_report_reason ADD CONSTRAINT FK_9B961CE3770F788A FOREIGN KEY (report_reason_id) REFERENCES report_reason (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784A76ED395');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F77847294869C');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784C4B0AC92');
        $this->addSql('ALTER TABLE report_report_reason DROP FOREIGN KEY FK_9B961CE34BD2A4C0');
        $this->addSql('ALTER TABLE report_report_reason DROP FOREIGN KEY FK_9B961CE3770F788A');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE report_report_reason');
    }
}
