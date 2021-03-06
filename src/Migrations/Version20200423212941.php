<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200423212941 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('ALTER TABLE user ADD COLUMN points INTEGER DEFAULT 0 NOT NULL');
        $this->addSql('DROP INDEX IDX_1C32B345A76ED395');
        $this->addSql('DROP INDEX IDX_1C32B345F7A2C2FC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_badge AS SELECT user_id, badge_id FROM user_badge');
        $this->addSql('DROP TABLE user_badge');
        $this->addSql('CREATE TABLE user_badge (user_id INTEGER NOT NULL, badge_id INTEGER NOT NULL, PRIMARY KEY(user_id, badge_id), CONSTRAINT FK_1C32B345A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1C32B345F7A2C2FC FOREIGN KEY (badge_id) REFERENCES badge (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user_badge (user_id, badge_id) SELECT user_id, badge_id FROM __temp__user_badge');
        $this->addSql('DROP TABLE __temp__user_badge');
        $this->addSql('CREATE INDEX IDX_1C32B345A76ED395 ON user_badge (user_id)');
        $this->addSql('CREATE INDEX IDX_1C32B345F7A2C2FC ON user_badge (badge_id)');
        $this->addSql('DROP INDEX IDX_64C19C1796A8F92');
        $this->addSql('CREATE TEMPORARY TABLE __temp__category AS SELECT id, parent_category_id, name, icon FROM category');
        $this->addSql('DROP TABLE category');
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_category_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, icon VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_64C19C1796A8F92 FOREIGN KEY (parent_category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO category (id, parent_category_id, name, icon) SELECT id, parent_category_id, name, icon FROM __temp__category');
        $this->addSql('DROP TABLE __temp__category');
        $this->addSql('CREATE INDEX IDX_64C19C1796A8F92 ON category (parent_category_id)');
        $this->addSql('DROP INDEX IDX_9D40DE1BF675F31B');
        $this->addSql('DROP INDEX IDX_9D40DE1B12469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__topic AS SELECT id, author_id, category_id, title, content, created_at, featured, is_active FROM topic');
        $this->addSql('DROP TABLE topic');
        $this->addSql('CREATE TABLE topic (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, category_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, content CLOB NOT NULL COLLATE BINARY, created_at DATETIME NOT NULL, featured BOOLEAN DEFAULT \'0\', is_active BOOLEAN DEFAULT \'1\' NOT NULL, CONSTRAINT FK_9D40DE1BF675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_9D40DE1B12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO topic (id, author_id, category_id, title, content, created_at, featured, is_active) SELECT id, author_id, category_id, title, content, created_at, featured, is_active FROM __temp__topic');
        $this->addSql('DROP TABLE __temp__topic');
        $this->addSql('CREATE INDEX IDX_9D40DE1BF675F31B ON topic (author_id)');
        $this->addSql('CREATE INDEX IDX_9D40DE1B12469DE2 ON topic (category_id)');
        $this->addSql('DROP INDEX IDX_9474526CF675F31B');
        $this->addSql('DROP INDEX IDX_9474526C1F55203D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comment AS SELECT id, author_id, topic_id, created_at, content FROM comment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, topic_id INTEGER NOT NULL, created_at DATETIME NOT NULL, content CLOB NOT NULL COLLATE BINARY, CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_9474526C1F55203D FOREIGN KEY (topic_id) REFERENCES topic (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO comment (id, author_id, topic_id, created_at, content) SELECT id, author_id, topic_id, created_at, content FROM __temp__comment');
        $this->addSql('DROP TABLE __temp__comment');
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
        $this->addSql('CREATE INDEX IDX_9474526C1F55203D ON comment (topic_id)');
        $this->addSql('DROP INDEX IDX_6336F6A72FE71C3E');
        $this->addSql('DROP INDEX IDX_6336F6A7A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__pokedex AS SELECT id, user_id, pokemon_id, quantity FROM pokedex');
        $this->addSql('DROP TABLE pokedex');
        $this->addSql('CREATE TABLE pokedex (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, pokemon_id INTEGER NOT NULL, quantity INTEGER DEFAULT 1 NOT NULL, CONSTRAINT FK_6336F6A7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6336F6A72FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO pokedex (id, user_id, pokemon_id, quantity) SELECT id, user_id, pokemon_id, quantity FROM __temp__pokedex');
        $this->addSql('DROP TABLE __temp__pokedex');
        $this->addSql('CREATE INDEX IDX_6336F6A72FE71C3E ON pokedex (pokemon_id)');
        $this->addSql('CREATE INDEX IDX_6336F6A7A76ED395 ON pokedex (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_64C19C1796A8F92');
        $this->addSql('CREATE TEMPORARY TABLE __temp__category AS SELECT id, parent_category_id, name, icon FROM category');
        $this->addSql('DROP TABLE category');
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_category_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, icon VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO category (id, parent_category_id, name, icon) SELECT id, parent_category_id, name, icon FROM __temp__category');
        $this->addSql('DROP TABLE __temp__category');
        $this->addSql('CREATE INDEX IDX_64C19C1796A8F92 ON category (parent_category_id)');
        $this->addSql('DROP INDEX IDX_9474526CF675F31B');
        $this->addSql('DROP INDEX IDX_9474526C1F55203D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comment AS SELECT id, author_id, topic_id, created_at, content FROM comment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, topic_id INTEGER NOT NULL, created_at DATETIME NOT NULL, content CLOB NOT NULL)');
        $this->addSql('INSERT INTO comment (id, author_id, topic_id, created_at, content) SELECT id, author_id, topic_id, created_at, content FROM __temp__comment');
        $this->addSql('DROP TABLE __temp__comment');
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
        $this->addSql('CREATE INDEX IDX_9474526C1F55203D ON comment (topic_id)');
        $this->addSql('DROP INDEX IDX_6336F6A7A76ED395');
        $this->addSql('DROP INDEX IDX_6336F6A72FE71C3E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__pokedex AS SELECT id, user_id, pokemon_id, quantity FROM pokedex');
        $this->addSql('DROP TABLE pokedex');
        $this->addSql('CREATE TABLE pokedex (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, pokemon_id INTEGER NOT NULL, quantity INTEGER DEFAULT 1 NOT NULL)');
        $this->addSql('INSERT INTO pokedex (id, user_id, pokemon_id, quantity) SELECT id, user_id, pokemon_id, quantity FROM __temp__pokedex');
        $this->addSql('DROP TABLE __temp__pokedex');
        $this->addSql('CREATE INDEX IDX_6336F6A7A76ED395 ON pokedex (user_id)');
        $this->addSql('CREATE INDEX IDX_6336F6A72FE71C3E ON pokedex (pokemon_id)');
        $this->addSql('DROP INDEX IDX_9D40DE1BF675F31B');
        $this->addSql('DROP INDEX IDX_9D40DE1B12469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__topic AS SELECT id, author_id, category_id, title, content, created_at, featured, is_active FROM topic');
        $this->addSql('DROP TABLE topic');
        $this->addSql('CREATE TABLE topic (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, category_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, content CLOB NOT NULL, created_at DATETIME NOT NULL, featured BOOLEAN DEFAULT \'0\', is_active BOOLEAN DEFAULT \'1\' NOT NULL)');
        $this->addSql('INSERT INTO topic (id, author_id, category_id, title, content, created_at, featured, is_active) SELECT id, author_id, category_id, title, content, created_at, featured, is_active FROM __temp__topic');
        $this->addSql('DROP TABLE __temp__topic');
        $this->addSql('CREATE INDEX IDX_9D40DE1BF675F31B ON topic (author_id)');
        $this->addSql('CREATE INDEX IDX_9D40DE1B12469DE2 ON topic (category_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, roles, password, name, lastname, email, birthday, created_at, avatar, is_active, is_blocked, gender, description, pokeball FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB DEFAULT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, birthday DATE DEFAULT NULL, created_at DATETIME NOT NULL, avatar VARCHAR(255) DEFAULT NULL, is_active BOOLEAN DEFAULT \'1\' NOT NULL, is_blocked BOOLEAN DEFAULT \'0\' NOT NULL, gender VARCHAR(255) DEFAULT NULL, description CLOB DEFAULT NULL, pokeball INTEGER DEFAULT 6)');
        $this->addSql('INSERT INTO user (id, username, roles, password, name, lastname, email, birthday, created_at, avatar, is_active, is_blocked, gender, description, pokeball) SELECT id, username, roles, password, name, lastname, email, birthday, created_at, avatar, is_active, is_blocked, gender, description, pokeball FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('DROP INDEX IDX_1C32B345A76ED395');
        $this->addSql('DROP INDEX IDX_1C32B345F7A2C2FC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_badge AS SELECT user_id, badge_id FROM user_badge');
        $this->addSql('DROP TABLE user_badge');
        $this->addSql('CREATE TABLE user_badge (user_id INTEGER NOT NULL, badge_id INTEGER NOT NULL, PRIMARY KEY(user_id, badge_id))');
        $this->addSql('INSERT INTO user_badge (user_id, badge_id) SELECT user_id, badge_id FROM __temp__user_badge');
        $this->addSql('DROP TABLE __temp__user_badge');
        $this->addSql('CREATE INDEX IDX_1C32B345A76ED395 ON user_badge (user_id)');
        $this->addSql('CREATE INDEX IDX_1C32B345F7A2C2FC ON user_badge (badge_id)');
    }
}
