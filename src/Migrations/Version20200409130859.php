<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200409130859 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE last_name_prefix last_name_prefix VARCHAR(180) DEFAULT NULL, CHANGE token token VARCHAR(255) DEFAULT NULL, CHANGE activated_at activated_at DATETIME DEFAULT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE product CHANGE product_group_id product_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE role ADD login_path_id INT DEFAULT NULL, DROP login_path');
        $this->addSql('ALTER TABLE role ADD CONSTRAINT FK_57698A6AC9414816 FOREIGN KEY (login_path_id) REFERENCES route (id)');
        $this->addSql('CREATE INDEX IDX_57698A6AC9414816 ON role (login_path_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product CHANGE product_group_id product_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE role DROP FOREIGN KEY FK_57698A6AC9414816');
        $this->addSql('DROP INDEX IDX_57698A6AC9414816 ON role');
        $this->addSql('ALTER TABLE role ADD login_path LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP login_path_id');
        $this->addSql('ALTER TABLE user CHANGE last_name_prefix last_name_prefix VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE token token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE activated_at activated_at DATETIME DEFAULT \'NULL\', CHANGE last_login last_login DATETIME DEFAULT \'NULL\'');
    }
}
