<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210517071729 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image CHANGE alt alt VARCHAR(255) DEFAULT NULL, CHANGE description_image description_image VARCHAR(255) DEFAULT NULL, CHANGE sort_order sort_order INT DEFAULT NULL');
        $this->addSql('DROP INDEX search_idx ON image_type');
        $this->addSql('ALTER TABLE image_type CHANGE width width INT DEFAULT NULL, CHANGE height height INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX search_idx ON image_type (id)');
        $this->addSql('ALTER TABLE product CHANGE product_group_id product_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE role CHANGE login_path_id login_path_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE token token VARCHAR(255) DEFAULT NULL, CHANGE activated_at activated_at DATETIME DEFAULT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user_profile CHANGE image_id image_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE first_name first_name VARCHAR(180) DEFAULT NULL, CHANGE last_name last_name VARCHAR(180) DEFAULT NULL, CHANGE last_name_prefix last_name_prefix VARCHAR(180) DEFAULT NULL, CHANGE birthday birthday DATE DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image CHANGE alt alt VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE description_image description_image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE sort_order sort_order INT DEFAULT NULL');
        $this->addSql('DROP INDEX search_idx ON image_type');
        $this->addSql('ALTER TABLE image_type CHANGE width width INT DEFAULT NULL, CHANGE height height INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX search_idx ON image_type (folder)');
        $this->addSql('ALTER TABLE product CHANGE product_group_id product_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE role CHANGE login_path_id login_path_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE token token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE activated_at activated_at DATETIME DEFAULT \'NULL\', CHANGE last_login last_login DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user_profile CHANGE image_id image_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE first_name first_name VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE last_name_prefix last_name_prefix VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE birthday birthday DATE DEFAULT \'NULL\'');
    }
}
