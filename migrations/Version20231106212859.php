<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231106212859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture_article DROP FOREIGN KEY FK_1F1CFAB47294869C');
        $this->addSql('ALTER TABLE picture_article DROP FOREIGN KEY FK_1F1CFAB4EE45BDBF');
        $this->addSql('DROP TABLE picture_article');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE picture_article (picture_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_1F1CFAB47294869C (article_id), INDEX IDX_1F1CFAB4EE45BDBF (picture_id), PRIMARY KEY(picture_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE picture_article ADD CONSTRAINT FK_1F1CFAB47294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE picture_article ADD CONSTRAINT FK_1F1CFAB4EE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id) ON DELETE CASCADE');
    }
}
