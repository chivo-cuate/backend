<?php

use yii\db\Migration;

/**
 * Class m200629_154619_add_proc_logout_inactive_cooks
 */
class m200629_154619_add_proc_logout_inactive_cooks extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            DROP PROCEDURE IF EXISTS LOGOUT_INACTIVE_COOKS;
            
            CREATE PROCEDURE LOGOUT_INACTIVE_COOKS()
            BEGIN
                DECLARE curCooksDone INT DEFAULT FALSE;
                DECLARE processNotifyDone BOOLEAN DEFAULT FALSE;
                DECLARE menuId INTEGER DEFAULT 0;
                DECLARE inactiveCookId INTEGER DEFAULT 0;
                DECLARE cookIdToNotify INTEGER DEFAULT 0;
                DECLARE sessionId varchar(32) DEFAULT \"\";
                DECLARE inactiveCookName varchar(128) DEFAULT \"\";
            
                DEClARE curCooks CURSOR FOR SELECT * from v_inactive_cooks;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET curCooksDone = TRUE;
                
                OPEN curCooks;
                
                processCooks: LOOP
                    FETCH curCooks INTO menuId, inactiveCookId, sessionId, inactiveCookName;
                    
                    IF curCooksDone = TRUE THEN 
                        LEAVE processCooks;
                    END IF;
                    
                    
                    processNotify: BEGIN
                        DECLARE curNotify CURSOR FOR
                            SELECT cook_id FROM menu_cook where session_id = sessionId and cook_id != inactiveCookId;
                        DECLARE CONTINUE HANDLER FOR NOT FOUND SET processNotifyDone = TRUE;
                        OPEN curNotify; 
                        cur_attribute_loop: LOOP
                        FETCH FROM curNotify INTO cookIdToNotify;
                        IF processNotifyDone THEN 
                            LEAVE processNotify;
                        END IF;
                        
                        INSERT INTO notification (user_id, title, subtitle, headline, created_at) values (
                            cookIdToNotify,
                            'Usuario desconectado',
                            concat('El elaborador ', inactiveCookName, ' ha sido desconectado por inactividad.'),
                            CURRENT_TIMESTAMP,
                            unix_timestamp()
                        );
                        END LOOP cur_attribute_loop;
                        CLOSE curNotify; 
                    END processNotify;
                    
                    UPDATE menu_cook SET session_id = null WHERE menu_id = menuId AND cook_id = inactiveCookId;
                END LOOP processCooks;
                
                CLOSE curCooks;
            END
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200629_154619_add_proc_logout_inactive_cooks cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200629_154619_add_proc_logout_inactive_cooks cannot be reverted.\n";

        return false;
    }
    */
}
