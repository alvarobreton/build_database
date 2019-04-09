<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Build database Model
 *
 *
 * @package         CodeIgniter
 * @subpackage      Model
 * @category        Model
 * @author          Álvaro Lima
 * @link   https://limagiron.com
 * @link    https://github.com/limagiron/build_database
 */

class Build_database_model extends CI_Model 
{

	private $tables = array();
	private $total_tables = 0;
	private $entity_name = '';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	* Function there is the entity
	* as an bolean.
	* update: 2018/10/25 :
	*
	* @access  public
	* @param  entity_name string
	* @return  boelan
	*
	*/
	public function there_is_the_entity($entity_name='')
	{
		if ($this->db->table_exists($entity_name))
		{
			return true;
		}
		return false;
	}

	/**
	* Function total of entities
	* update: 2018/10/25 :
	*
	* @access  public
	* @return  int
	*
	*/
	public function total_of_entities()
	{
		$tables = $this->db->list_tables();
		$total_tables = count($tables);
		return $total_tables;
	}

	/**
	* Function create table
	* update: 2018/11/6 :
	*
	* @access  public
	* @param entify string
	* @return  bolean
	*
	*/
	public function create_table($entity = '')
	{
		if (!empty($entity)) 
		{
			$this->db->query($entity);
			return true;
		}
		return false;
	}

	/**
	* Function build attributes
	* update: 2018/11/6 :
	*
	* @access  public
	* @param   table_name  string  name of the table
	* @param   attributes array attributes
	* @return  bolean
	*
	*/
	public function build_attributes($table_name = '', $attributes = null )
	{
		
		foreach ($attributes as $column_name => $datatype) 
		{
			$query = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME = '".$column_name."' AND TABLE_NAME = '".$table_name."' AND TABLE_SCHEMA = '".$this->db->database."' ");
		
			if ($query->num_rows() == 0) 
			{
				$query = $this->db->query("ALTER TABLE `".$table_name."` ADD `".$column_name."` ".$datatype."  NULL");
			}
		}

		return true;
	}
	public function edit_attributes($table_name = '', $attributes = null, $type = 'columns')
	{
		if ($type == 'columns') 
		{
			foreach ($attributes as $column_name => $datatype) 
			{
				$query = $this->db->query("ALTER TABLE `".$table_name."` CHANGE COLUMN `".$column_name."` `".$column_name."` ".$datatype." ;");
			}
		}
		elseif ($type == 'engine') 
		{
			// ALTER TABLE `book_movimientos` ENGINE=InnoDB;
			$query = $this->db->query("ALTER TABLE `".$table_name."` ENGINE=InnoDB ");
		}
		elseif ($type == 'unique_index') 
		{
			foreach ($attributes as $column_name => $datatype) 
			{
				$query = $this->db->query("ALTER TABLE `".$table_name."` ADD UNIQUE INDEX `".$column_name."` (`".$column_name."`) ");
			}
		}
		
		
		return true;
	}

	public function get_primary_key($table_name = '')
	{
		if(empty($table_name)) : return false; endif;
	
		$query ="ALTER TABLE `".$table_name."` ENGINE=InnoDB";
		$this->db->query($query);
		
		$query = "SELECT COLUMN_NAME, COLUMN_KEY FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".$this->db->database."' AND TABLE_NAME = '".$table_name."' and COLUMN_KEY IN('PRI', 'UNI');";

		$result =  $this->db->query($query);

		if (!empty($result->row())) 
		{
			$row = $result->row();

			if ($row->COLUMN_KEY == 'PRI'):
				return $row->COLUMN_NAME;
			endif;

		}
		return false;

	}

	public function edit_entities($table_name = '', $type = 'engine')
	{

		if ($type == 'engine') 
		{
			// ALTER TABLE `book_movimientos` ENGINE=InnoDB;
			$query = $this->db->query("ALTER TABLE `".$table_name."` ENGINE=InnoDB ");
		}
		
		return true;
	}

}

/* End of file Build_database_model.php */
/* Location: ./application/models/Build_database_model.php */
?>