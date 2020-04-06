<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ingredient}}`.
 */
class m190722_201505_create_measure_unit_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%measure_unit}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'abbr' => $this->string()->notNull()->unique(),
        ], app\utilities\MigrationHelper::getTableOptions($this->db->driverName));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%measure_unit}}');
    }

}
