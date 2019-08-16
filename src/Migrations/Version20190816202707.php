<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190816202707 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA1D8309E3');
        $this->addSql('DROP INDEX IDX_BF5476CA1D8309E3 ON notification');
        $this->addSql('ALTER TABLE notification CHANGE like_by_id liked_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAB4622EC2 FOREIGN KEY (liked_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_BF5476CAB4622EC2 ON notification (liked_by_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAB4622EC2');
        $this->addSql('DROP INDEX IDX_BF5476CAB4622EC2 ON notification');
        $this->addSql('ALTER TABLE notification CHANGE liked_by_id like_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA1D8309E3 FOREIGN KEY (like_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA1D8309E3 ON notification (like_by_id)');
    }
}
