<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260823160000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Création du rôle ROLE_ADMIN et promotion du compte denisurgel04@gmail.com en administrateur";
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO role (name) SELECT 'ROLE_ADMIN' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM role WHERE name = 'ROLE_ADMIN')");

        // le compte existe déjà (inscrit via /register) : on le promeut, mot de passe existant conservé.
        // s'il n'existait pas, on le crée avec le mot de passe fourni par l'utilisateur.
        $this->addSql(<<<'SQL'
            INSERT INTO `user` (email, roles, password, last_name, first_name, phone_number, is_verified, role_id)
            SELECT 'denisurgel04@gmail.com', '[]', '$2y$13$1kqg2uO0Qd1Pya2sEVkaAOwwwtfqqfG922YFNalKDrpn8J/2AzkFa', 'Admin', 'Admin', '0000000000', 1,
                (SELECT id FROM role WHERE name = 'ROLE_ADMIN')
            ON DUPLICATE KEY UPDATE role_id = (SELECT id FROM role WHERE name = 'ROLE_ADMIN')
            SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql("UPDATE `user` SET role_id = (SELECT id FROM role WHERE name = 'ROLE_USER') WHERE email = 'denisurgel04@gmail.com'");
        $this->addSql("DELETE FROM role WHERE name = 'ROLE_ADMIN'");
    }
}
