<?php

/**
 * Class Migration_update_user_groups
 * This is the default code for altertions in existing tables.
 * Both up and down must be filled out accordingly to the data needed
 * Make sure, that type and constraint are filled out, else it will not work.
 */

class Migration_update_user_groups extends CI_Migration
{
    public function up()
    {

        $this->db->query("UPDATE `users_groups` 
                          SET `group_id` = 4
                          WHERE `group_id` = 3;");
    }


    public function down()
    {

        $this->db->query("UPDATE `user_groups`  
                          SET `group_id` = 3
                          WHERE `group_id` = 4;");
    }
}
