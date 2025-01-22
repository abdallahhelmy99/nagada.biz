<?php

/**
 * Class Migration_Calendar_files
 * This is the default code for generating new tables.
 * Both up and down must be filled out accordingly to the data needed
 */


class Migration_Create_sessions_table extends CI_Migration
{

  public function up()
  {
    $this->dbforge->add_field(array(
      'session_id' => array(
        'type' => 'VARCHAR',
        'constraint' => 40,
        'default' => '0'
      ),
      'ip_address' => array(
        'type' => 'VARCHAR',
        'constraint' => 45,
        'default' => '0'
      ),
      'user_agent' => array(
        'type' => 'VARCHAR',
        'constraint' => 120
      ),
      'last_activity' => array(
        'type' => 'INT',
        'constraint' => 10,
        'unsigned' => TRUE,
        'default' => 0
      ),
      'user_data' => array(
        'type' => 'TEXT'
      )
    ));

    $this->dbforge->add_key('session_id', TRUE);
    $this->dbforge->add_key('last_activity');

    $this->dbforge->create_table('ci_sessions', TRUE);
  }

  public function down()
  {
    $this->dbforge->drop_table('ci_sessions');
  }
}
