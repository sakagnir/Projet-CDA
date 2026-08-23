<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260823155108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "L'identifiant de connexion passe de id à email, et création du rôle par défaut ROLE_USER";
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_ID ON `user`');
        $this->addSql('ALTER TABLE `user` CHANGE email email VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON `user` (email)');

        // rôle attribué à chaque compte créé via /register
        $this->addSql("INSERT INTO role (name) SELECT 'ROLE_USER' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM role WHERE name = 'ROLE_USER')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM role WHERE name = 'ROLE_USER'");
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON `user`');
        $this->addSql('ALTER TABLE `user` CHANGE email email VARCHAR(50) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_ID ON `user` (id)');
    }
}
