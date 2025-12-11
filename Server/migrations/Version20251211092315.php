<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251211092315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE challenges (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, details LONGTEXT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, start_date DATETIME NOT NULL, finish_date DATETIME NOT NULL, category_id INT NOT NULL, owner_id INT NOT NULL, INDEX IDX_7B5A7E012469DE2 (category_id), INDEX IDX_7B5A7E07E3C61F9 (owner_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE chat_messages (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, user_id INT NOT NULL, INDEX IDX_EF20C9A6A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE participations (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) NOT NULL, challenge_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_FDC6C6E898A21AC6 (challenge_id), INDEX IDX_FDC6C6E8A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE promos (id INT AUTO_INCREMENT NOT NULL, city VARCHAR(100) NOT NULL, year INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_B63E2EC72B36786B (title), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, username VARCHAR(50) NOT NULL, validated TINYINT NOT NULL, verified TINYINT NOT NULL, promo_id INT NOT NULL, role_id INT NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), INDEX IDX_1483A5E9D0C07AFF (promo_id), INDEX IDX_1483A5E9D60322AC (role_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE challenges ADD CONSTRAINT FK_7B5A7E012469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE challenges ADD CONSTRAINT FK_7B5A7E07E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_messages ADD CONSTRAINT FK_EF20C9A6A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participations ADD CONSTRAINT FK_FDC6C6E898A21AC6 FOREIGN KEY (challenge_id) REFERENCES challenges (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participations ADD CONSTRAINT FK_FDC6C6E8A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9D0C07AFF FOREIGN KEY (promo_id) REFERENCES promos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9D60322AC FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participations ADD CONSTRAINT UNIQ_PARTICIPATION_USER_CHALLENGE UNIQUE (user_id, challenge_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE challenges DROP FOREIGN KEY FK_7B5A7E012469DE2');
        $this->addSql('ALTER TABLE challenges DROP FOREIGN KEY FK_7B5A7E07E3C61F9');
        $this->addSql('ALTER TABLE chat_messages DROP FOREIGN KEY FK_EF20C9A6A76ED395');
        $this->addSql('ALTER TABLE participations DROP FOREIGN KEY FK_FDC6C6E898A21AC6');
        $this->addSql('ALTER TABLE participations DROP FOREIGN KEY FK_FDC6C6E8A76ED395');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9D0C07AFF');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9D60322AC');
        $this->addSql('ALTER TABLE participations DROP INDEX UNIQ_PARTICIPATION_USER_CHALLENGE');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE challenges');
        $this->addSql('DROP TABLE chat_messages');
        $this->addSql('DROP TABLE participations');
        $this->addSql('DROP TABLE promos');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE users');
    }
}
