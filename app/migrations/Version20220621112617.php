<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220621112617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE instagram_user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, username VARCHAR(255) NOT NULL, description VARCHAR(1000) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(10000) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_visual (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(1000) NOT NULL, INDEX IDX_DBEB6C7A4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visual (id INT AUTO_INCREMENT NOT NULL, instagram_user_id INT NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(1000) NOT NULL, INDEX IDX_EBC9881F2A715C9B (instagram_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_visual ADD CONSTRAINT FK_DBEB6C7A4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE visual ADD CONSTRAINT FK_EBC9881F2A715C9B FOREIGN KEY (instagram_user_id) REFERENCES instagram_user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE visual DROP FOREIGN KEY FK_EBC9881F2A715C9B');
        $this->addSql('ALTER TABLE post_visual DROP FOREIGN KEY FK_DBEB6C7A4B89032C');
        $this->addSql('DROP TABLE instagram_user');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_visual');
        $this->addSql('DROP TABLE visual');
    }
}
