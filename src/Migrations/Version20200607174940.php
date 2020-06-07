<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200607174940 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE taxonomia_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE producto_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE taxonomia (id INT NOT NULL, nombre VARCHAR(80) NOT NULL, descripcion TEXT DEFAULT NULL, imagen VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE producto (id INT NOT NULL, taxonomia_id INT DEFAULT NULL, nombre VARCHAR(80) NOT NULL, descripcion TEXT DEFAULT NULL, precio DOUBLE PRECISION NOT NULL, imagen VARCHAR(255) DEFAULT NULL, precio_iva DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A7BB061529CAB57C ON producto (taxonomia_id)');
        $this->addSql('ALTER TABLE producto ADD CONSTRAINT FK_A7BB061529CAB57C FOREIGN KEY (taxonomia_id) REFERENCES taxonomia (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE producto DROP CONSTRAINT FK_A7BB061529CAB57C');
        $this->addSql('DROP SEQUENCE taxonomia_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE producto_id_seq CASCADE');
        $this->addSql('DROP TABLE taxonomia');
        $this->addSql('DROP TABLE producto');
    }
}
