<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250510163440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__maintenance AS SELECT id, name, date, description, gear_id FROM maintenance
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE maintenance
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE maintenance (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, date DATE NOT NULL, description CLOB DEFAULT NULL, gear_id INTEGER NOT NULL, CONSTRAINT FK_2F84F8E977201934 FOREIGN KEY (gear_id) REFERENCES gear (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO maintenance (id, name, date, description, gear_id) SELECT id, name, date, description, gear_id FROM __temp__maintenance
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__maintenance
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2F84F8E977201934 ON maintenance (gear_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE maintenance ADD COLUMN image BLOB DEFAULT NULL
        SQL);
    }
}
