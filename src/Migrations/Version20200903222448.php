<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200903222448 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image_imagetypes DROP FOREIGN KEY FK_9AF10483BF396750');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE image_imagetypes');
        $this->addSql('ALTER TABLE user CHANGE token token VARCHAR(255) DEFAULT NULL, CHANGE activated_at activated_at DATETIME DEFAULT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user_profile CHANGE user_id user_id INT DEFAULT NULL, CHANGE first_name first_name VARCHAR(180) DEFAULT NULL, CHANGE last_name last_name VARCHAR(180) DEFAULT NULL, CHANGE last_name_prefix last_name_prefix VARCHAR(180) DEFAULT NULL, CHANGE birthday birthday DATE DEFAULT NULL, CHANGE avater avater VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE product CHANGE product_group_id product_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE role CHANGE login_path_id login_path_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image_type CHANGE width width INT DEFAULT NULL, CHANGE height height INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, name_image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, alt VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, description_image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, sort_order INT DEFAULT NULL, UNIQUE INDEX search_idx (name_image), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE image_imagetypes (id INT NOT NULL, imageTypeId INT NOT NULL, INDEX IDX_9AF10483BF396750 (id), UNIQUE INDEX UNIQ_9AF10483125F993 (imageTypeId), PRIMARY KEY(id, imageTypeId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE image_imagetypes ADD CONSTRAINT FK_9AF10483125F993 FOREIGN KEY (imageTypeId) REFERENCES image_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_imagetypes ADD CONSTRAINT FK_9AF10483BF396750 FOREIGN KEY (id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_type CHANGE width width INT DEFAULT NULL, CHANGE height height INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product CHANGE product_group_id product_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE role CHANGE login_path_id login_path_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE token token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE activated_at activated_at DATETIME DEFAULT \'NULL\', CHANGE last_login last_login DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user_profile CHANGE user_id user_id INT DEFAULT NULL, CHANGE first_name first_name VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE last_name_prefix last_name_prefix VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE birthday birthday DATE DEFAULT \'NULL\', CHANGE avater avater VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}