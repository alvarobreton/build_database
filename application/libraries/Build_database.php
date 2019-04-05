<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Build database
 *
 *  Creation : 2018/10/25
 *
 * @package         CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author          Álvaro Lima
 * @link	https://limagiron.com
 * @link	https://github.com/limagiron/build_database
 */

class Build_database
{
	// variables
	protected $ci;
	private $i;
	private $entity_name;
	private $attributes;
	private $entity;
	private $total_attributes;
	private $primary_key;

	public function __construct()
	{
		

        $this->ci =& get_instance();
        $this->ci->load->model('build_database_model');
	}

	/**
	* Function generator of entities and returns the results.
	* as an bolean.
	* update: 2018/10/25 :
	*
	* @access  public
	* @param   entity_name  string  name of the entity to be integrated
	* @param   attributes  array  attributes to integrate
	* @param   primary_key  string  attributes to integrate
	* @param   foreign_key  array  attributes to integrate
	* @return  bolean
	*
	*/
	public function build_entities($entity_name = '', $attributes = array(), $primary_key = '', $foreign_key = array() )
	{
		// initialization of variables
		$entity = '';
		$i 		= 0;

		if (empty($entity_name)) 
		{
			return false;
		}

		if (empty($attributes)) 
		{
			return false;
		}

		// there is the entity ?
		if ($this->there_is_the_entity($entity_name)) 
		{
			# true
			return false;
		}

		$total_attributes = count($attributes);

		$entity .= "CREATE TABLE IF NOT EXISTS `".$entity_name."` (";

		foreach ($attributes as $key => $value) 
		{
			$i++;

			if ($i == $total_attributes):

				$entity .= " `".$key."` ".$value;

				if($primary_key != ''):
					$entity .= ", PRIMARY KEY (`".$primary_key."`) ";
				endif;
				if(!empty($foreign_key)):
					foreach ($foreign_key as $key => $value):
						if($this->there_is_the_entity($value)):
							if(!empty($this->get_primary_key($value))):
								$entity .= ", FOREIGN KEY (`".$key."`) REFERENCES ".$value."(`".$this->get_primary_key($value)."`)";
							endif;
						endif;
					endforeach;
				endif;
			else:
				$entity .= " `".$key."` ".$value." ,";
			endif;
		}
		$entity .= ") ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;";

		return $this->ci->build_database_model->create_table($entity);
		

	}

	/**
	* Function to verify if there are entities.
	* as an bolean.
	* update: 2018/10/25 :
	*
	* @access  public
	* @param   entity_name  string  name of the entity to be integrated
	* @return  bolean
	*
	*/
	public function there_is_the_entity($entity_name='')
	{
		return $this->ci->build_database_model->there_is_the_entity($entity_name);
	}

	/**
	* Function generator attributes.
	* as an bolean.
	* update: 2019/02/15 :
	*
	* @access  public
	* @param   table_name  string  name of the table
	* @param   attributes  array  attributes array
	* @return  bolean
	*
	*/
	public function build_attributes($table_name = '', $attributes = null )
	{
		if (empty($table_name)) 
		{
			return false;
		}
		if (empty($attributes)) 
		{
			return false;
		}
		// there is the entity ?
		if (!$this->there_is_the_entity($table_name)) 
		{
			# true
			return false;
		}

		return $this->ci->build_database_model->build_attributes($table_name, $attributes);
	}
	/**
	* Function edit attributes.
	* as an bolean.
	* update: 2019/02/15:
	*
	* @access  public
	* @param   table_name  string  name of the table
	* @param   attributes  array  attributes array
	* @return  bolean
	*
	*/
	public function edit_attributes($table_name = '', $attributes = null, $type = 'columns' )
	{
		if (empty($table_name)) 
		{
			return false;
		}
		if (empty($attributes)) 
		{
			return false;
		}
		// there is the entity ?
		if (!$this->there_is_the_entity($table_name)) 
		{
			# true
			return false;
		}

		return $this->ci->build_database_model->edit_attributes($table_name, $attributes, $type);
	}
	/**
	* Function get primary key.
	* as an bolean.
	* update: 2019/02/15:
	*
	* @access  public
	* @param   table_name  string  name of the table
	* @return  string
	*
	*/
	public function get_primary_key($table_name = '')
	{
		if(empty($table_name)) : return false; endif;
		// there is the entity ?
		if (!$this->there_is_the_entity($table_name)) 
		{
			# true
			return false;
		}
		return $this->ci->build_database_model->get_primary_key($table_name);
	}
	/**
	* Function edit entities.
	* as an bolean.
	* update: 2019/02/27:
	*
	* @access  public
	* @param   table_name  string  name of the table
	* @param   type  String 
	* @return  bolean
	*
	*/
	public function edit_entities($table_name = '', $type = 'engine' )
	{
		if (empty($table_name)) 
		{
			return false;
		}


		// there is the entity ?
		if (!$this->there_is_the_entity($table_name)) 
		{
			# true
			return false;
		}

		return $this->ci->build_database_model->edit_entities($table_name, $type);
	}
}

/* End of file Build_database.php */
/* Location: ./application/libraries/Build_database.php */
