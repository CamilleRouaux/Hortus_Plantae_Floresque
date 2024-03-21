<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231027135153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tag_plant (tag_id INT NOT NULL, plant_id INT NOT NULL, INDEX IDX_89EF1474BAD26311 (tag_id), INDEX IDX_89EF14741D935652 (plant_id), PRIMARY KEY(tag_id, plant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tag_plant ADD CONSTRAINT FK_89EF1474BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_plant ADD CONSTRAINT FK_89EF14741D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite ADD user_id INT DEFAULT NULL, ADD plant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED91D935652 FOREIGN KEY (plant_id) REFERENCES plant (id)');
        $this->addSql('CREATE INDEX IDX_68C58ED9A76ED395 ON favorite (user_id)');
        $this->addSql('CREATE INDEX IDX_68C58ED91D935652 ON favorite (plant_id)');
        $this->addSql('ALTER TABLE picture ADD plant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F891D935652 FOREIGN KEY (plant_id) REFERENCES plant (id)');
        $this->addSql('CREATE INDEX IDX_16DB4F891D935652 ON picture (plant_id)');
        $this->addSql('ALTER TABLE plant ADD location_id INT DEFAULT NULL, ADD exposure_id INT DEFAULT NULL, ADD soil_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D7264D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D72C677C140 FOREIGN KEY (exposure_id) REFERENCES exposure (id)');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D72C59CE9E2 FOREIGN KEY (soil_id) REFERENCES soil (id)');
        $this->addSql('CREATE INDEX IDX_AB030D7264D218E ON plant (location_id)');
        $this->addSql('CREATE INDEX IDX_AB030D72C677C140 ON plant (exposure_id)');
        $this->addSql('CREATE INDEX IDX_AB030D72C59CE9E2 ON plant (soil_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag_plant DROP FOREIGN KEY FK_89EF1474BAD26311');
        $this->addSql('ALTER TABLE tag_plant DROP FOREIGN KEY FK_89EF14741D935652');
        $this->addSql('DROP TABLE tag_plant');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F891D935652');
        $this->addSql('DROP INDEX IDX_16DB4F891D935652 ON picture');
        $this->addSql('ALTER TABLE picture DROP plant_id');
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D7264D218E');
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D72C677C140');
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D72C59CE9E2');
        $this->addSql('DROP INDEX IDX_AB030D7264D218E ON plant');
        $this->addSql('DROP INDEX IDX_AB030D72C677C140 ON plant');
        $this->addSql('DROP INDEX IDX_AB030D72C59CE9E2 ON plant');
        $this->addSql('ALTER TABLE plant DROP location_id, DROP exposure_id, DROP soil_id');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9A76ED395');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED91D935652');
        $this->addSql('DROP INDEX IDX_68C58ED9A76ED395 ON favorite');
        $this->addSql('DROP INDEX IDX_68C58ED91D935652 ON favorite');
        $this->addSql('ALTER TABLE favorite DROP user_id, DROP plant_id');
    }
}
