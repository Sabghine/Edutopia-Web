<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210410115539 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE co_studying CHANGE type type INT DEFAULT NULL');
        $this->addSql('ALTER TABLE co_studying ADD CONSTRAINT FK_5B8A12508CDE5729 FOREIGN KEY (type) REFERENCES costudyingtype (id)');
        $this->addSql('CREATE INDEX IDX_5B8A12508CDE5729 ON co_studying (type)');
        $this->addSql('ALTER TABLE department CHANGE created_by created_by INT DEFAULT NULL, CHANGE ownerId ownerId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exam CHANGE id_subject id_subject INT DEFAULT NULL, CHANGE created_by created_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ligne_exam MODIFY idligne INT NOT NULL');
        $this->addSql('ALTER TABLE ligne_exam DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE ligne_exam CHANGE idligne idligne INT NOT NULL');
        $this->addSql('ALTER TABLE ligne_exam ADD PRIMARY KEY (idligne, idexam, iduser)');
        $this->addSql('ALTER TABLE question CHANGE id_Exam id_Exam INT DEFAULT NULL');
        $this->addSql('ALTER TABLE seance CHANGE id_teacher id_teacher INT DEFAULT NULL, CHANGE id_classe id_classe INT DEFAULT NULL');
        $this->addSql('ALTER TABLE specialties CHANGE idTeacher idTeacher INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subject CHANGE id_teacher id_teacher INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE co_studying DROP FOREIGN KEY FK_5B8A12508CDE5729');
        $this->addSql('DROP INDEX IDX_5B8A12508CDE5729 ON co_studying');
        $this->addSql('ALTER TABLE co_studying CHANGE type type VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`');
        $this->addSql('ALTER TABLE department CHANGE created_by created_by INT NOT NULL, CHANGE ownerId ownerId INT NOT NULL');
        $this->addSql('ALTER TABLE exam CHANGE created_by created_by INT NOT NULL, CHANGE id_subject id_subject INT NOT NULL');
        $this->addSql('ALTER TABLE ligne_exam DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE ligne_exam CHANGE idligne idligne INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE ligne_exam ADD PRIMARY KEY (idligne, iduser, idexam)');
        $this->addSql('ALTER TABLE question CHANGE id_Exam id_Exam INT NOT NULL');
        $this->addSql('ALTER TABLE seance CHANGE id_classe id_classe INT NOT NULL, CHANGE id_teacher id_teacher INT NOT NULL');
        $this->addSql('ALTER TABLE specialties CHANGE idTeacher idTeacher INT NOT NULL');
        $this->addSql('ALTER TABLE subject CHANGE id_teacher id_teacher INT NOT NULL');
    }
}
