<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200908205158 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) NOT NULL, name_image VARCHAR(255) NOT NULL, alt VARCHAR(255) DEFAULT NULL, description_image VARCHAR(255) DEFAULT NULL, sort_order INT DEFAULT NULL, UNIQUE INDEX search_idx (name_image), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_imagetypes (id INT NOT NULL, imageTypeId INT NOT NULL, INDEX IDX_9AF10483BF396750 (id), UNIQUE INDEX UNIQ_9AF10483125F993 (imageTypeId), PRIMARY KEY(id, imageTypeId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_type (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) NOT NULL, folder VARCHAR(255) NOT NULL, width INT DEFAULT NULL, height INT DEFAULT NULL, image_type_name VARCHAR(255) NOT NULL, is_crop INT DEFAULT 0 NOT NULL, is_original INT DEFAULT 0 NOT NULL, UNIQUE INDEX search_idx (file_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image_imagetypes ADD CONSTRAINT FK_9AF10483BF396750 FOREIGN KEY (id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_imagetypes ADD CONSTRAINT FK_9AF10483125F993 FOREIGN KEY (imageTypeId) REFERENCES image_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_profile ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB4053DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D95AB4053DA5256D ON user_profile (image_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB4053DA5256D');
        $this->addSql('ALTER TABLE image_imagetypes DROP FOREIGN KEY FK_9AF10483BF396750');
        $this->addSql('ALTER TABLE image_imagetypes DROP FOREIGN KEY FK_9AF10483125F993');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE image_imagetypes');
        $this->addSql('DROP TABLE image_type');
        $this->addSql('DROP INDEX UNIQ_D95AB4053DA5256D ON user_profile');
        $this->addSql('ALTER TABLE user_profile DROP image_id');
    }
}
