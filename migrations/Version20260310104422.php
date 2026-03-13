<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260310104422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY `FK_FDCA8C9C7ECF78B0`');
        $this->addSql('DROP INDEX IDX_FDCA8C9C7ECF78B0 ON cours');
        $this->addSql('ALTER TABLE cours DROP cours_id, CHANGE jour jour VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD cours_id INT NOT NULL, CHANGE statut statut VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849557ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('CREATE INDEX IDX_42C849557ECF78B0 ON reservation (cours_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours ADD cours_id INT NOT NULL, CHANGE jour jour VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT `FK_FDCA8C9C7ECF78B0` FOREIGN KEY (cours_id) REFERENCES cours (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_FDCA8C9C7ECF78B0 ON cours (cours_id)');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849557ECF78B0');
        $this->addSql('DROP INDEX IDX_42C849557ECF78B0 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP cours_id, CHANGE statut statut VARCHAR(255) NOT NULL');
    }
}
