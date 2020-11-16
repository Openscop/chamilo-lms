<?php

namespace Application\Migrations\Schema;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class Version20201116100847
 * @package Application\Migrations\Schema
 */
class Version20201116100847 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("INSERT INTO `course_category`(`name`, `code`, `parent_id`, `tree_pos`, `children_count`, `auth_course_child`, `auth_cat_child`) VALUES ('Affiché en 1ère position', 'ORDER11', NULL, 11, 0, 'FALSE', 'TRUE'),
('Affiché en 2ème position', 'ORDER12', NULL, 12, 0, 'FALSE', 'TRUE'),
('Affiché en 3ème position', 'ORDER13', NULL, 13, 0, 'FALSE', 'TRUE'),
('Affiché en 4ème position', 'ORDER14', NULL, 14, 0, 'FALSE', 'TRUE'),
('Affiché en 5ème position', 'ORDER15', NULL, 15, 0, 'FALSE', 'TRUE'),
('Affiché en 6ème position', 'ORDER16', NULL, 16, 0, 'FALSE', 'TRUE'),
('Affiché en 7ème position', 'ORDER17', NULL, 17, 0, 'FALSE', 'TRUE'),
('Affiché en 8ème position', 'ORDER18', NULL, 18, 0, 'FALSE', 'TRUE'),
('Affiché en 9ème position', 'ORDER19', NULL, 19, 0, 'FALSE', 'TRUE'),
('Affiché en 10ème position', 'ORDER20', NULL, 20, 0, 'FALSE', 'TRUE'),
('Affiché en 11ème position', 'ORDER21', NULL, 21, 0, 'FALSE', 'TRUE'),
('Affiché en 12ème position', 'ORDER22', NULL, 22, 0, 'FALSE', 'TRUE'),
('Affiché en 13ème position', 'ORDER23', NULL, 23, 0, 'FALSE', 'TRUE'),
('Affiché en 14ème position', 'ORDER24', NULL, 24, 0, 'FALSE', 'TRUE'),
('Affiché en 15ème position', 'ORDER25', NULL, 25, 0, 'FALSE', 'TRUE'),
('Affiché en 16ème position', 'ORDER26', NULL, 26, 0, 'FALSE', 'TRUE')");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
