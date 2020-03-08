<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class VersionBook extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user
        (id INT AUTO_INCREMENT NOT NULL, 
         email VARCHAR(180) NOT NULL, 
         roles JSON NOT NULL, 
         password VARCHAR(255) NOT NULL,
         api_token VARCHAR(255) DEFAULT NULL,
        UNIQUE INDEX UNIQ_USER_EMAIL (email), 
        UNIQUE INDEX UNIQ_USER_API_TOKEN (api_token),
        PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE book 
        (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', 
        isbn VARCHAR(30) NOT NULL, 
        title VARCHAR(100) NOT NULL, 
        description LONGTEXT NOT NULL,
        created_at DATETIME DEFAULT NULL,
        updated_at DATETIME DEFAULT NULL,
        deleted_at DATETIME DEFAULT NULL,
        publishing_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
        created_by INT DEFAULT NULL,
        UNIQUE INDEX UNIQ_ISBN (isbn), 
        PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_BOOK_USER_ID FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_BOOK_CREATED_BY ON book (created_by)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE user');
    }
}
