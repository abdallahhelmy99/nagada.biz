<?php

/**
 * Class Migration_Calendar_files
 * This is the default code for altertions in existing tables.
 * Both up and down must be filled out accordingly to the data needed
 * Make sure, that type and constraint are filled out, else it will not work.
 */

class Migration_Calendar_files_modify extends CI_Migration
{
	public function up()
	{

		$fields = array(
			'file_name' => array(
				'name' => 'file_name_new',
				'type' => 'VARCHAR',
				'constraint' => '200',
				'null' => true,
			),
		);
		$this->dbforge->modify_column('tbl_calender_files', $fields);

	}


	public function down()
	{
		$fields = array(
			'file_name_new' => array(
				'name' => 'file_name',
				'type' => 'VARCHAR',
				'constraint' => '200',
				'null' => true,
			),
		);
		$this->dbforge->modify_column('tbl_calender_files', $fields);
	}
}
