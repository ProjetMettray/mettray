<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211026100455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE association (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE association_user (association_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A2312D48EFB9C8A5 (association_id), INDEX IDX_A2312D48A76ED395 (user_id), PRIMARY KEY(association_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, user_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE association_user ADD CONSTRAINT FK_A2312D48EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE association_user ADD CONSTRAINT FK_A2312D48A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE association_user DROP FOREIGN KEY FK_A2312D48EFB9C8A5');
        $this->addSql('ALTER TABLE association_user DROP FOREIGN KEY FK_A2312D48A76ED395');
        $this->addSql('DROP TABLE association');
        $this->addSql('DROP TABLE association_user');
        $this->addSql('DROP TABLE user');
    }
}
