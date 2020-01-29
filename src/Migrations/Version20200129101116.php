<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200129101116 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE animal (id INT AUTO_INCREMENT NOT NULL, booking_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, birthday DATETIME NOT NULL, description LONGTEXT NOT NULL, localisation VARCHAR(255) NOT NULL, specie VARCHAR(255) NOT NULL, race VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_6AAB231F3301C60 (booking_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE black_jack (id INT AUTO_INCREMENT NOT NULL, played_at DATETIME DEFAULT NULL, to_pay INT NOT NULL, amount INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, booking_id INT DEFAULT NULL, profil_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nickname VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D6493301C60 (booking_id), UNIQUE INDEX UNIQ_8D93D649275ED078 (profil_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, at_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roulette (id INT AUTO_INCREMENT NOT NULL, played_at DATETIME DEFAULT NULL, to_pay INT NOT NULL, amount INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, total_amount INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil_roulette (profil_id INT NOT NULL, roulette_id INT NOT NULL, INDEX IDX_C4E6E883275ED078 (profil_id), INDEX IDX_C4E6E883C247C4 (roulette_id), PRIMARY KEY(profil_id, roulette_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil_black_jack (profil_id INT NOT NULL, black_jack_id INT NOT NULL, INDEX IDX_4058F26F275ED078 (profil_id), INDEX IDX_4058F26FF2E2AE6F (black_jack_id), PRIMARY KEY(profil_id, black_jack_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, animal_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_C53D045F8E962C16 (animal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collaborator (id INT AUTO_INCREMENT NOT NULL, localisation VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, birthday DATETIME NOT NULL, description LONGTEXT NOT NULL, role VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231F3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
        $this->addSql('ALTER TABLE profil_roulette ADD CONSTRAINT FK_C4E6E883275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil_roulette ADD CONSTRAINT FK_C4E6E883C247C4 FOREIGN KEY (roulette_id) REFERENCES roulette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil_black_jack ADD CONSTRAINT FK_4058F26F275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil_black_jack ADD CONSTRAINT FK_4058F26FF2E2AE6F FOREIGN KEY (black_jack_id) REFERENCES black_jack (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F8E962C16');
        $this->addSql('ALTER TABLE profil_black_jack DROP FOREIGN KEY FK_4058F26FF2E2AE6F');
        $this->addSql('ALTER TABLE animal DROP FOREIGN KEY FK_6AAB231F3301C60');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6493301C60');
        $this->addSql('ALTER TABLE profil_roulette DROP FOREIGN KEY FK_C4E6E883C247C4');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649275ED078');
        $this->addSql('ALTER TABLE profil_roulette DROP FOREIGN KEY FK_C4E6E883275ED078');
        $this->addSql('ALTER TABLE profil_black_jack DROP FOREIGN KEY FK_4058F26F275ED078');
        $this->addSql('DROP TABLE animal');
        $this->addSql('DROP TABLE black_jack');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE roulette');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE profil_roulette');
        $this->addSql('DROP TABLE profil_black_jack');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE collaborator');
    }
}
