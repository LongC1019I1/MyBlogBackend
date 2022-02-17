<?php

use yii\db\Migration;

/**
 * Class m220217_023014_add_roleid_users_table
 */
class m220217_023014_add_roleid_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'role_id', $this->integer()->after('status'));
        $this->addForeignKey('fk-user-roles', 'user', 'role_id', 'roles', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-user-roles',
            'user'
        );

        $this->dropColumn('{{%user}}', 'role_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220217_023014_add_roleid_users_table cannot be reverted.\n";

        return false;
    }
    */
}
