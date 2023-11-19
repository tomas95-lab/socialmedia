<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231119030152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mensajes (id INT AUTO_INCREMENT NOT NULL, nombre_id INT NOT NULL, contenido LONGTEXT NOT NULL, fecha DATETIME NOT NULL, INDEX IDX_6C929C80C2D4D747 (nombre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mensajes ADD CONSTRAINT FK_6C929C80C2D4D747 FOREIGN KEY (nombre_id) REFERENCES usuarios (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mensajes DROP FOREIGN KEY FK_6C929C80C2D4D747');
        $this->addSql('DROP TABLE mensajes');
    }
}
