<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200929203858 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, user_name VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, archived TINYINT(1) NOT NULL, token VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, activated_at DATETIME DEFAULT NULL, last_login DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D64924A232CF (user_name), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_roles (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_51498A8EA76ED395 (user_id), INDEX IDX_51498A8ED60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_profile (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, user_id INT DEFAULT NULL, first_name VARCHAR(180) DEFAULT NULL, last_name VARCHAR(180) DEFAULT NULL, last_name_prefix VARCHAR(180) DEFAULT NULL, birthday DATE DEFAULT NULL, UNIQUE INDEX UNIQ_D95AB4053DA5256D (image_id), UNIQUE INDEX UNIQ_D95AB405A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unit (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, `order` INT NOT NULL, archived TINYINT(1) NOT NULL, UNIQUE INDEX search_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, unit_id INT NOT NULL, product_group_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, archived TINYINT(1) NOT NULL, carbohydrates INT NOT NULL, INDEX IDX_D34A04ADF8BD700D (unit_id), INDEX IDX_D34A04AD35E4B3D0 (product_group_id), UNIQUE INDEX search_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, archived TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, login_path_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, role_id VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, archived TINYINT(1) NOT NULL, INDEX IDX_57698A6AC9414816 (login_path_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE route (id INT AUTO_INCREMENT NOT NULL, route VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2C420792C42079 (route), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, image_file VARCHAR(255) NOT NULL, name_image VARCHAR(255) NOT NULL, alt VARCHAR(255) DEFAULT NULL, description_image VARCHAR(255) DEFAULT NULL, sort_order INT DEFAULT NULL, UNIQUE INDEX search_idx (name_image), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_imagetypes (id INT NOT NULL, imageTypeId INT NOT NULL, INDEX IDX_9AF10483BF396750 (id), UNIQUE INDEX UNIQ_9AF10483125F993 (imageTypeId), PRIMARY KEY(id, imageTypeId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_type (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) NOT NULL, folder VARCHAR(255) NOT NULL, width INT DEFAULT NULL, height INT DEFAULT NULL, image_type_name VARCHAR(255) NOT NULL, is_crop INT DEFAULT 0 NOT NULL, is_original INT DEFAULT 0 NOT NULL, UNIQUE INDEX search_idx (file_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_roles ADD CONSTRAINT FK_51498A8EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_roles ADD CONSTRAINT FK_51498A8ED60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB4053DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB405A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADF8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD35E4B3D0 FOREIGN KEY (product_group_id) REFERENCES product_group (id)');
        $this->addSql('ALTER TABLE role ADD CONSTRAINT FK_57698A6AC9414816 FOREIGN KEY (login_path_id) REFERENCES route (id)');
        $this->addSql('ALTER TABLE image_imagetypes ADD CONSTRAINT FK_9AF10483BF396750 FOREIGN KEY (id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_imagetypes ADD CONSTRAINT FK_9AF10483125F993 FOREIGN KEY (imageTypeId) REFERENCES image_type (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users_roles DROP FOREIGN KEY FK_51498A8EA76ED395');
        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB405A76ED395');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADF8BD700D');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD35E4B3D0');
        $this->addSql('ALTER TABLE users_roles DROP FOREIGN KEY FK_51498A8ED60322AC');
        $this->addSql('ALTER TABLE role DROP FOREIGN KEY FK_57698A6AC9414816');
        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB4053DA5256D');
        $this->addSql('ALTER TABLE image_imagetypes DROP FOREIGN KEY FK_9AF10483BF396750');
        $this->addSql('ALTER TABLE image_imagetypes DROP FOREIGN KEY FK_9AF10483125F993');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE users_roles');
        $this->addSql('DROP TABLE user_profile');
        $this->addSql('DROP TABLE unit');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_group');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE route');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE image_imagetypes');
        $this->addSql('DROP TABLE image_type');
    }
}
