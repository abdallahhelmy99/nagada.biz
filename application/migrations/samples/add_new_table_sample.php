<?php

/**
 * Class Migration_Calendar_files
 * This is the default code for generating new tables.
 * Both up and down must be filled out accordingly to the data needed
 */



class Migration_Calendar_files extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_field(
			array(
				'calender_fid' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'auto_increment' => true
				),
				'calender_id' => array(
					'type' => 'INT',
					'constraint' => '11',
					'null' => true,
				),
				'file_name' => array(
					'type' => 'VARCHAR',
					'constraint' => '200',
					'null' => true,
				),
			)
		);

		$this->dbforge->add_key('calender_fid', TRUE);
		$this->dbforge->create_table('tbl_calender_files');
	}


	public function down()
	{
		$this->dbforge->drop_table('tbl_calender_files');
	}
}
