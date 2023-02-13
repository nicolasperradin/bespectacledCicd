<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212131329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticketing DROP CONSTRAINT fk_86ae198d6bf700bd');
        $this->addSql('DROP SEQUENCE refresh_tokens_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE artist_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE spectator_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE status_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "payment_transaction_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE schedule_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE event (id INT NOT NULL, room_id INT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3BAE0AA754177093 ON event (room_id)');
        $this->addSql('CREATE TABLE event_artist (event_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(event_id, user_id))');
        $this->addSql('CREATE INDEX IDX_33C0E1D571F7E88B ON event_artist (event_id)');
        $this->addSql('CREATE INDEX IDX_33C0E1D5A76ED395 ON event_artist (user_id)');
        $this->addSql('CREATE TABLE "payment_transaction" (id INT NOT NULL, buyer_id INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_84BBD50B6C755722 ON "payment_transaction" (buyer_id)');
        $this->addSql('CREATE TABLE schedule (id INT NOT NULL, event_id INT DEFAULT NULL, date DATE NOT NULL, times TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5A3811FB71F7E88B ON schedule (event_id)');
        $this->addSql('COMMENT ON COLUMN schedule.times IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA754177093 FOREIGN KEY (room_id) REFERENCES "room" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_artist ADD CONSTRAINT FK_33C0E1D571F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_artist ADD CONSTRAINT FK_33C0E1D5A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "payment_transaction" ADD CONSTRAINT FK_84BBD50B6C755722 FOREIGN KEY (buyer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE artist');
        $this->addSql('DROP TABLE spectator');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('ALTER TABLE room RENAME COLUMN seats TO nb_seats');
        $this->addSql('ALTER TABLE room_reservation ADD client_id INT NOT NULL');
        $this->addSql('ALTER TABLE room_reservation ADD payment_transaction_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE room_reservation ADD status INT NOT NULL');
        $this->addSql('ALTER TABLE room_reservation ADD CONSTRAINT FK_56FDE76A19EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room_reservation ADD CONSTRAINT FK_56FDE76ACAE8710B FOREIGN KEY (payment_transaction_id) REFERENCES "payment_transaction" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_56FDE76A19EB6921 ON room_reservation (client_id)');
        $this->addSql('CREATE INDEX IDX_56FDE76ACAE8710B ON room_reservation (payment_transaction_id)');
        $this->addSql('DROP INDEX idx_86ae198d6bf700bd');
        $this->addSql('ALTER TABLE ticketing ADD event_id INT NOT NULL');
        $this->addSql('ALTER TABLE ticketing ADD payment_transaction_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ticketing ADD status INT NOT NULL');
        $this->addSql('ALTER TABLE ticketing RENAME COLUMN status_id TO buyer_id');
        $this->addSql('ALTER TABLE ticketing ADD CONSTRAINT FK_86AE198D6C755722 FOREIGN KEY (buyer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticketing ADD CONSTRAINT FK_86AE198D71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticketing ADD CONSTRAINT FK_86AE198DCAE8710B FOREIGN KEY (payment_transaction_id) REFERENCES "payment_transaction" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_86AE198D6C755722 ON ticketing (buyer_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_86AE198D71F7E88B ON ticketing (event_id)');
        $this->addSql('CREATE INDEX IDX_86AE198DCAE8710B ON ticketing (payment_transaction_id)');
        $this->addSql('ALTER TABLE "user" ADD validated_account BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ticketing DROP CONSTRAINT FK_86AE198D71F7E88B');
        $this->addSql('ALTER TABLE room_reservation DROP CONSTRAINT FK_56FDE76ACAE8710B');
        $this->addSql('ALTER TABLE ticketing DROP CONSTRAINT FK_86AE198DCAE8710B');
        $this->addSql('DROP SEQUENCE event_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "payment_transaction_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE schedule_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE refresh_tokens_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE artist_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE spectator_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE artist (id INT NOT NULL, lastname VARCHAR(55) NOT NULL, firstname VARCHAR(55) NOT NULL, stage_name VARCHAR(55) DEFAULT NULL, password VARCHAR(16) NOT NULL, email VARCHAR(255) NOT NULL, confirm_email BOOLEAN NOT NULL, validated_account BOOLEAN NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, number VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE spectator (id INT NOT NULL, firstname VARCHAR(55) NOT NULL, lastname VARCHAR(55) NOT NULL, email VARCHAR(255) NOT NULL, number VARCHAR(10) DEFAULT NULL, password VARCHAR(16) NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, confirm_email BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE status (id INT NOT NULL, worded VARCHAR(9) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE refresh_tokens (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_9bace7e1c74f2195 ON refresh_tokens (refresh_token)');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA754177093');
        $this->addSql('ALTER TABLE event_artist DROP CONSTRAINT FK_33C0E1D571F7E88B');
        $this->addSql('ALTER TABLE event_artist DROP CONSTRAINT FK_33C0E1D5A76ED395');
        $this->addSql('ALTER TABLE "payment_transaction" DROP CONSTRAINT FK_84BBD50B6C755722');
        $this->addSql('ALTER TABLE schedule DROP CONSTRAINT FK_5A3811FB71F7E88B');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_artist');
        $this->addSql('DROP TABLE "payment_transaction"');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('ALTER TABLE ticketing DROP CONSTRAINT FK_86AE198D6C755722');
        $this->addSql('DROP INDEX IDX_86AE198D6C755722');
        $this->addSql('DROP INDEX UNIQ_86AE198D71F7E88B');
        $this->addSql('DROP INDEX IDX_86AE198DCAE8710B');
        $this->addSql('ALTER TABLE ticketing ADD status_id INT NOT NULL');
        $this->addSql('ALTER TABLE ticketing DROP buyer_id');
        $this->addSql('ALTER TABLE ticketing DROP event_id');
        $this->addSql('ALTER TABLE ticketing DROP payment_transaction_id');
        $this->addSql('ALTER TABLE ticketing DROP status');
        $this->addSql('ALTER TABLE ticketing ADD CONSTRAINT fk_86ae198d6bf700bd FOREIGN KEY (status_id) REFERENCES status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_86ae198d6bf700bd ON ticketing (status_id)');
        $this->addSql('ALTER TABLE room_reservation DROP CONSTRAINT FK_56FDE76A19EB6921');
        $this->addSql('DROP INDEX IDX_56FDE76A19EB6921');
        $this->addSql('DROP INDEX IDX_56FDE76ACAE8710B');
        $this->addSql('ALTER TABLE room_reservation DROP client_id');
        $this->addSql('ALTER TABLE room_reservation DROP payment_transaction_id');
        $this->addSql('ALTER TABLE room_reservation DROP status');
        $this->addSql('ALTER TABLE "user" DROP validated_account');
        $this->addSql('ALTER TABLE "user" DROP deleted_at');
        $this->addSql('ALTER TABLE "room" RENAME COLUMN nb_seats TO seats');
    }
}
