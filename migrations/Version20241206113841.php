<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241206113841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_items ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_items RENAME INDEX idx_62809db0eab5deb TO IDX_62809DB08D9F6D38');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_items DROP product_id');
        $this->addSql('ALTER TABLE order_items RENAME INDEX idx_62809db08d9f6d38 TO IDX_62809DB0EAB5DEB');
    }
}
