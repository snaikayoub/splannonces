<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210109220444 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5D27C88A8');
        $this->addSql('DROP INDEX IDX_F65593E5D27C88A8 ON annonce');
        $this->addSql('ALTER TABLE annonce DROP ville, CHANGE villea_id ville_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('CREATE INDEX IDX_F65593E5A73F0036 ON annonce (ville_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5A73F0036');
        $this->addSql('DROP INDEX IDX_F65593E5A73F0036 ON annonce');
        $this->addSql('ALTER TABLE annonce ADD ville VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE ville_id villea_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5D27C88A8 FOREIGN KEY (villea_id) REFERENCES ville (id)');
        $this->addSql('CREATE INDEX IDX_F65593E5D27C88A8 ON annonce (villea_id)');
    }
}
