<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231106212024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA1255CD1D');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAF624B39D');
        $this->addSql('DROP INDEX uniq_bf5476ca1255cd1d ON notification');
        $this->addSql('CREATE INDEX IDX_BF5476CA1255CD1D ON notification (ban_id)');
        $this->addSql('DROP INDEX uniq_bf5476caf624b39d ON notification');
        $this->addSql('CREATE INDEX IDX_BF5476CAF624B39D ON notification (sender_id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA1255CD1D FOREIGN KEY (ban_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA1255CD1D');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAF624B39D');
        $this->addSql('DROP INDEX idx_bf5476ca1255cd1d ON notification');
        $this->addSql('CREATE INDEX UNIQ_BF5476CA1255CD1D ON notification (ban_id)');
        $this->addSql('DROP INDEX idx_bf5476caf624b39d ON notification');
        $this->addSql('CREATE INDEX UNIQ_BF5476CAF624B39D ON notification (sender_id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA1255CD1D FOREIGN KEY (ban_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin` COMMENT \'(DC2Type:json)\'');
        $this->addSql('CREATE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }
}
