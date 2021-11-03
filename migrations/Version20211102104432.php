<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211102104432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE association (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE association_user (id INT AUTO_INCREMENT NOT NULL, association_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_A2312D48EFB9C8A5 (association_id), INDEX IDX_A2312D48A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, room_id_id INT NOT NULL, association_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', options JSON NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_E00CEDDE35F83FFC (room_id_id), INDEX IDX_E00CEDDEEFB9C8A5 (association_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, cp VARCHAR(45) NOT NULL, road VARCHAR(45) NOT NULL, city VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, room_id INT DEFAULT NULL, name VARCHAR(45) NOT NULL, nb_place INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_729F519B64D218E (location_id), INDEX IDX_729F519B54177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_association (id INT AUTO_INCREMENT NOT NULL, association_id INT DEFAULT NULL, room_id INT DEFAULT NULL, INDEX IDX_E9B08FF3EFB9C8A5 (association_id), INDEX IDX_E9B08FF354177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_room (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_81E1D5254177093 (room_id), INDEX IDX_81E1D52A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE association_user ADD CONSTRAINT FK_A2312D48EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('ALTER TABLE association_user ADD CONSTRAINT FK_A2312D48A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE35F83FFC FOREIGN KEY (room_id_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEEFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B54177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE room_association ADD CONSTRAINT FK_E9B08FF3EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('ALTER TABLE room_association ADD CONSTRAINT FK_E9B08FF354177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE user_room ADD CONSTRAINT FK_81E1D5254177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE user_room ADD CONSTRAINT FK_81E1D52A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE association_user DROP FOREIGN KEY FK_A2312D48EFB9C8A5');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEEFB9C8A5');
        $this->addSql('ALTER TABLE room_association DROP FOREIGN KEY FK_E9B08FF3EFB9C8A5');
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519B64D218E');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE35F83FFC');
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519B54177093');
        $this->addSql('ALTER TABLE room_association DROP FOREIGN KEY FK_E9B08FF354177093');
        $this->addSql('ALTER TABLE user_room DROP FOREIGN KEY FK_81E1D5254177093');
        $this->addSql('ALTER TABLE association_user DROP FOREIGN KEY FK_A2312D48A76ED395');
        $this->addSql('ALTER TABLE user_room DROP FOREIGN KEY FK_81E1D52A76ED395');
        $this->addSql('DROP TABLE association');
        $this->addSql('DROP TABLE association_user');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE room_association');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_room');
    }
}
