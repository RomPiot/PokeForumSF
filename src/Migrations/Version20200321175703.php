<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200321175703 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE badge (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, topic_id INTEGER NOT NULL, created_at DATETIME NOT NULL, content CLOB NOT NULL)');
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
        $this->addSql('CREATE INDEX IDX_9474526C1F55203D ON comment (topic_id)');
        $this->addSql('CREATE TABLE sub_category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_BCE3F79812469DE2 ON sub_category (category_id)');
        $this->addSql('CREATE TABLE topic (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, category_id INTEGER NOT NULL, subcategory_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, content CLOB NOT NULL, created_at DATETIME NOT NULL, featured BOOLEAN DEFAULT NULL, is_active BOOLEAN NOT NULL)');
        $this->addSql('CREATE INDEX IDX_9D40DE1BF675F31B ON topic (author_id)');
        $this->addSql('CREATE INDEX IDX_9D40DE1B12469DE2 ON topic (category_id)');
        $this->addSql('CREATE INDEX IDX_9D40DE1B5DC6FE57 ON topic (subcategory_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, birthday DATE DEFAULT NULL, created_at DATETIME NOT NULL, avatar VARCHAR(255) DEFAULT NULL, is_active BOOLEAN NOT NULL, is_blocked BOOLEAN NOT NULL, gender VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE TABLE user_badge (user_id INTEGER NOT NULL, badge_id INTEGER NOT NULL, PRIMARY KEY(user_id, badge_id))');
        $this->addSql('CREATE INDEX IDX_1C32B345A76ED395 ON user_badge (user_id)');
        $this->addSql('CREATE INDEX IDX_1C32B345F7A2C2FC ON user_badge (badge_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE badge');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE sub_category');
        $this->addSql('DROP TABLE topic');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_badge');
    }
}
