<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260823151732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE availabilities (id INT AUTO_INCREMENT NOT NULL, start_date DATETIME NOT NULL, business_id INT DEFAULT NULL, INDEX IDX_D7FC41EFA89DB457 (business_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, purpose VARCHAR(255) NOT NULL, is_validated TINYINT NOT NULL, availabilites_id INT NOT NULL, client_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_E00CEDDEE6D1744F (availabilites_id), INDEX IDX_E00CEDDE19EB6921 (client_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE business (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, manager_id INT NOT NULL, UNIQUE INDEX UNIQ_8D36E38783E3463 (manager_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, last_name VARCHAR(50) NOT NULL, first_name VARCHAR(50) NOT NULL, email VARCHAR(50) NOT NULL, phone_number VARCHAR(10) NOT NULL, is_verified TINYINT NOT NULL, role_id INT NOT NULL, INDEX IDX_8D93D649D60322AC (role_id), UNIQUE INDEX UNIQ_IDENTIFIER_ID (id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE availabilities ADD CONSTRAINT FK_D7FC41EFA89DB457 FOREIGN KEY (business_id) REFERENCES business (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEE6D1744F FOREIGN KEY (availabilites_id) REFERENCES availabilities (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE19EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE business ADD CONSTRAINT FK_8D36E38783E3463 FOREIGN KEY (manager_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE availabilities DROP FOREIGN KEY FK_D7FC41EFA89DB457');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEE6D1744F');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE19EB6921');
        $this->addSql('ALTER TABLE business DROP FOREIGN KEY FK_8D36E38783E3463');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649D60322AC');
        $this->addSql('DROP TABLE availabilities');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE business');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE `user`');
    }
}
