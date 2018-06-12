<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180612190124 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE categoria (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, data_cadastro DATETIME NOT NULL, data_alteracao DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categoria_servico (categoria_id INT NOT NULL, servico_id INT NOT NULL, INDEX IDX_3278C623397707A (categoria_id), INDEX IDX_3278C6282E14982 (servico_id), PRIMARY KEY(categoria_id, servico_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE servico (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, titulo VARCHAR(60) NOT NULL, slug VARCHAR(60) NOT NULL, valor NUMERIC(10, 2) NOT NULL, descricao LONGTEXT NOT NULL, informacoes_adicionais LONGTEXT DEFAULT NULL, prazo_entrega INT NOT NULL, status VARCHAR(1) NOT NULL COMMENT \'Usar P para publicado, A para em Análise, I para inativo, E para excluído e R para Rejeitado\', data_cadastro DATETIME NOT NULL, data_alteracao DATETIME DEFAULT NULL, imagem VARCHAR(255) DEFAULT NULL, INDEX IDX_14873CCDB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categoria_servico ADD CONSTRAINT FK_3278C623397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categoria_servico ADD CONSTRAINT FK_3278C6282E14982 FOREIGN KEY (servico_id) REFERENCES servico (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE servico ADD CONSTRAINT FK_14873CCDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categoria_servico DROP FOREIGN KEY FK_3278C623397707A');
        $this->addSql('ALTER TABLE categoria_servico DROP FOREIGN KEY FK_3278C6282E14982');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE categoria_servico');
        $this->addSql('DROP TABLE servico');
    }
}
