<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200410150216 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE token token VARCHAR(255) DEFAULT NULL, CHANGE activated_at activated_at DATETIME DEFAULT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE product CHANGE product_group_id product_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE role CHANGE login_path_id login_path_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_profile ADD user_id INT DEFAULT NULL, CHANGE last_name_prefix last_name_prefix VARCHAR(180) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB405A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D95AB405A76ED395 ON user_profile (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product CHANGE product_group_id product_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE role CHANGE login_path_id login_path_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE token token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE activated_at activated_at DATETIME DEFAULT \'NULL\', CHANGE last_login last_login DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB405A76ED395');
        $this->addSql('DROP INDEX UNIQ_D95AB405A76ED395 ON user_profile');
        $this->addSql('ALTER TABLE user_profile DROP user_id, CHANGE last_name_prefix last_name_prefix VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
