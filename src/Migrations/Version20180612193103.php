<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180612193103 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE servico CHANGE status status CHAR(1) NOT NULL COMMENT \'Usar P para publicado, A para em Análise, I para inativo, E para excluído e R para Rejeitado\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE servico CHANGE status status VARCHAR(1) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'Usar P para publicado, A para em Análise, I para inativo, E para excluído e R para Rejeitado\'');
    }
}
