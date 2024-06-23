<?php

use app\core\Application;

class m0003_add_photos_table
{
    public function up()
    {
        $db = Application::$app->db;
        $SQL = "CREATE TABLE photos(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    path VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)ENGINE=INNODB;";

        $db->pdo->exec($SQL);
    }

    public function down()
    {
        $db = Application::$app->db;

        $SQL = "drop table photos";

        $db->pdo->exec($SQL);

    }
}