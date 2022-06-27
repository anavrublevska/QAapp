<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220627000120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ztp_answers (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', content LONGTEXT DEFAULT NULL, best TINYINT(1) NOT NULL, email VARCHAR(255) NOT NULL, nickname VARCHAR(255) NOT NULL, INDEX IDX_39B1A5BE1E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ztp_categories (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ztp_questions (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', title VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, email VARCHAR(255) NOT NULL, nickname VARCHAR(255) NOT NULL, INDEX IDX_727B29E612469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ztp_users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX email_idx (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ztp_answers ADD CONSTRAINT FK_39B1A5BE1E27F6BF FOREIGN KEY (question_id) REFERENCES ztp_questions (id)');
        $this->addSql('ALTER TABLE ztp_questions ADD CONSTRAINT FK_727B29E612469DE2 FOREIGN KEY (category_id) REFERENCES ztp_categories (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ztp_questions DROP FOREIGN KEY FK_727B29E612469DE2');
        $this->addSql('ALTER TABLE ztp_answers DROP FOREIGN KEY FK_39B1A5BE1E27F6BF');
        $this->addSql('DROP TABLE ztp_answers');
        $this->addSql('DROP TABLE ztp_categories');
        $this->addSql('DROP TABLE ztp_questions');
        $this->addSql('DROP TABLE ztp_users');
    }
}
