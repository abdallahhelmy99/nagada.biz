<?php

/**
 * Class Migration_Calendar_files
 * This is the default code for altertions in existing tables.
 * Both up and down must be filled out accordingly to the data needed
 * Make sure, that type and constraint are filled out, else it will not work.
 */

class Migration_add_username_order_template extends CI_Migration
{
	public function up() {

        $this->db->query("ALTER TABLE `order_template` 
                          ADD COLUMN `username` VARCHAR(100) NULL;");
    
        $this->db->query("UPDATE `order_template` 
                        SET `username` = 'helix';");    

    }
        
    
      public function down() {
    
        $this->db->query("ALTER TABLE `order_template` 
                          DROP COLUMN `username`;");
      
      }
}