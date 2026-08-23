<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260823161000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création du rôle ROLE_BUSINESS_OWNER, attribué aux utilisateurs qui créent un commerce';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO role (name) SELECT 'ROLE_BUSINESS_OWNER' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM role WHERE name = 'ROLE_BUSINESS_OWNER')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM role WHERE name = 'ROLE_BUSINESS_OWNER'");
    }
}
