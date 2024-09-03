<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240903142351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accounts (id INT AUTO_INCREMENT NOT NULL, clients_id INT DEFAULT NULL, balance NUMERIC(10, 2) NOT NULL, opening_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_CAC89EACAB014612 (clients_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE alerts (id INT AUTO_INCREMENT NOT NULL, invoices_id INT DEFAULT NULL, alert_date DATETIME NOT NULL, alert_type VARCHAR(50) NOT NULL, status VARCHAR(20) NOT NULL, INDEX IDX_F77AC06B2454BA75 (invoices_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clients (id INT AUTO_INCREMENT NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(20) DEFAULT NULL, address LONGTEXT DEFAULT NULL, creation_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE credits (id INT AUTO_INCREMENT NOT NULL, clients_id INT DEFAULT NULL, credit_amount NUMERIC(10, 2) NOT NULL, credit_date DATETIME NOT NULL, reason LONGTEXT DEFAULT NULL, INDEX IDX_4117D17EAB014612 (clients_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoices (id INT AUTO_INCREMENT NOT NULL, clients_id INT DEFAULT NULL, total_amount NUMERIC(10, 2) NOT NULL, invoice_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', due_date DATETIME NOT NULL, interest_rate NUMERIC(5, 2) NOT NULL, interest_amount NUMERIC(10, 2) NOT NULL, status VARCHAR(20) NOT NULL, INDEX IDX_6A2F2F95AB014612 (clients_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payments (id INT AUTO_INCREMENT NOT NULL, invoices_id INT DEFAULT NULL, paid_amount NUMERIC(10, 2) NOT NULL, payment_date DATETIME NOT NULL, payment_method VARCHAR(50) NOT NULL, INDEX IDX_65D29B322454BA75 (invoices_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unpaid_invoices (id INT AUTO_INCREMENT NOT NULL, invoices_id INT DEFAULT NULL, due_date DATETIME NOT NULL, unpaid_amount NUMERIC(10, 2) NOT NULL, alert_date DATETIME DEFAULT NULL, alert_sent TINYINT(1) NOT NULL, interest_amount NUMERIC(10, 2) NOT NULL, INDEX IDX_FD3E93662454BA75 (invoices_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accounts ADD CONSTRAINT FK_CAC89EACAB014612 FOREIGN KEY (clients_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE alerts ADD CONSTRAINT FK_F77AC06B2454BA75 FOREIGN KEY (invoices_id) REFERENCES invoices (id)');
        $this->addSql('ALTER TABLE credits ADD CONSTRAINT FK_4117D17EAB014612 FOREIGN KEY (clients_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F95AB014612 FOREIGN KEY (clients_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B322454BA75 FOREIGN KEY (invoices_id) REFERENCES invoices (id)');
        $this->addSql('ALTER TABLE unpaid_invoices ADD CONSTRAINT FK_FD3E93662454BA75 FOREIGN KEY (invoices_id) REFERENCES invoices (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accounts DROP FOREIGN KEY FK_CAC89EACAB014612');
        $this->addSql('ALTER TABLE alerts DROP FOREIGN KEY FK_F77AC06B2454BA75');
        $this->addSql('ALTER TABLE credits DROP FOREIGN KEY FK_4117D17EAB014612');
        $this->addSql('ALTER TABLE invoices DROP FOREIGN KEY FK_6A2F2F95AB014612');
        $this->addSql('ALTER TABLE payments DROP FOREIGN KEY FK_65D29B322454BA75');
        $this->addSql('ALTER TABLE unpaid_invoices DROP FOREIGN KEY FK_FD3E93662454BA75');
        $this->addSql('DROP TABLE accounts');
        $this->addSql('DROP TABLE alerts');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE credits');
        $this->addSql('DROP TABLE invoices');
        $this->addSql('DROP TABLE payments');
        $this->addSql('DROP TABLE unpaid_invoices');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
