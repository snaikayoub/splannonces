<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210104234026 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5D27C88A8');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D074860');
        $this->addSql('DROP TABLE ville');
        $this->addSql('DROP INDEX IDX_F65593E5D27C88A8 ON annonce');
        $this->addSql('ALTER TABLE annonce ADD ville VARCHAR(255) NOT NULL, DROP villea_id');
        $this->addSql('DROP INDEX IDX_8D93D649D074860 ON user');
        $this->addSql('ALTER TABLE user DROP villeu_id, DROP first_name, DROP last_name, DROP telephone, DROP contact_type, DROP created_at, DROP updated_at');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, villes VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE annonce ADD villea_id INT DEFAULT NULL, DROP ville');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5D27C88A8 FOREIGN KEY (villea_id) REFERENCES ville (id)');
        $this->addSql('CREATE INDEX IDX_F65593E5D27C88A8 ON annonce (villea_id)');
        $this->addSql('ALTER TABLE user ADD villeu_id INT DEFAULT NULL, ADD first_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD last_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD telephone VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD contact_type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D074860 FOREIGN KEY (villeu_id) REFERENCES ville (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649D074860 ON user (villeu_id)');
    }
}
