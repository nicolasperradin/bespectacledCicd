<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230118130218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artist (id INT AUTO_INCREMENT NOT NULL, lastname VARCHAR(55) NOT NULL, firstname VARCHAR(55) NOT NULL, stage_name VARCHAR(55) DEFAULT NULL, password VARCHAR(16) NOT NULL, email VARCHAR(255) NOT NULL, confirm_email TINYINT(1) NOT NULL, validated_account TINYINT(1) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted DATETIME DEFAULT NULL, number VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(55) NOT NULL, seats INT NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_reservation (id INT AUTO_INCREMENT NOT NULL, room_id_id INT NOT NULL, created DATETIME NOT NULL, INDEX IDX_56FDE76A35F83FFC (room_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spectator (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(55) NOT NULL, lastname VARCHAR(55) NOT NULL, email VARCHAR(255) NOT NULL, number VARCHAR(10) DEFAULT NULL, password VARCHAR(16) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted DATETIME DEFAULT NULL, confirm_email TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, worded VARCHAR(9) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticketing (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, INDEX IDX_86AE198D6BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE room_reservation ADD CONSTRAINT FK_56FDE76A35F83FFC FOREIGN KEY (room_id_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE ticketing ADD CONSTRAINT FK_86AE198D6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room_reservation DROP FOREIGN KEY FK_56FDE76A35F83FFC');
        $this->addSql('ALTER TABLE ticketing DROP FOREIGN KEY FK_86AE198D6BF700BD');
        $this->addSql('DROP TABLE artist');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE room_reservation');
        $this->addSql('DROP TABLE spectator');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE ticketing');
    }
}
