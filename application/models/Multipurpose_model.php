<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Multipurpose Model
 *
 * @package         CodeIgniter
 * @subpackage      Model
 * @category        Model
 * @author          Álvaro Lima
 * @link   https://limagiron.com
 * @link    https://github.com/limagiron/build_database
*/

class Multipurpose_model extends CI_Model {

	public $table;
	public $data;
	public $where;
	
	public function __construct($type = '')
	{
		parent::__construct();
        $this->load->database();

        if (!empty($type))  //global
        {
            $this->db = $this->load->database($type,TRUE);
        }
        else
        {
            $this->db = $this->load->database('default',TRUE);
        }
        
	}

    public function __destruct() 
    {
        $this->db = $this->load->database('default',TRUE);
    }

    /**
    * Function to obtain a result in the database
    *
    * @param table String  table name ata base
    * @param data  Array list of attributes
    *
    * @return Array
    */

	public function insert($table = '', $data, $type =  '')
	{
		if(!empty($data))
        {
            if ($type == 'batch') 
            {
                $query = $this->db->insert_batch($table, $data);
            }
            else
            {
                $query = $this->db->insert($table, $data);
            }
        	
        	if($query)
        	{
        		return $this->db->affected_rows();
        	}
        }
	}
    /**
    * Function to obtain a result in the database
    *
    * @param table String  table name
    * @param where array /string where condition
    *
    * @return Array
    */
	function get_record($table = '', $where = null)
    {
        if(!empty($where))
        {
            $this->db->where($where);
        }
        $query = $this->db->get($table);
        if($query->num_rows() > 0)
        {
            return $query->row();
        }
        else
        {
            return 0;
        }
    }
    /**
    * Object of the entity with where and limit
    *
    * @param table String  table name
    * @param where String where condition
    * @param limit String Results limit
    *
    * @return Array
    */
    function get_records($table, $where = NULL,$limit = NULL)
    {
        if (!empty($limit)) 
        {
            $this->db->limit($limit);
        }
        if(!empty($where))
        {
            $this->db->where($where);
        }
        $query = $this->db->get($table);
        if($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return array();
        }
    }

    /**
    * Entity update
    *
    * @param table String  table name
    * @param where String where name
    * @param data Array list of attributes
    *
    * @return bolean
    */
	function update($table = '', $where = NULL, $data)
	{
		if(!empty($where))
		{
			$this->db->where($where);
		}
		$query = $this->db->update($table, $data);
		if($query)
		{
			return $this->db->affected_rows();
		}
	}

    /**
    * Consult the database
    *
    * @param table String  table name
    * @param where String where name
    * 
    * @return Interger
    */
	function delete($table, $where = NULL)
    {
        if(!empty($where))
        {
            $this->db->where($where);
        }
        if($this->db->delete($table))
        {
            return $this->db->affected_rows();
        }
    }

    /**
    * Consult the database
    *
    * @param querys String
    * @param type String  type row (1) | result list ( > 0) | num_rows (total)
    * 
    * @return Interger
    */
    function query($querys = '', $type = '')
    {
        if(!empty($querys))
        {
            $query = $this->db->query($querys);
            if($query->num_rows() > 0)
            {
                if (isset($type)) 
                {
                    if ($type == 'row') 
                    {
                        return $query->row();
                    }
                    elseif ($type == 'num_rows') 
                    {
                        return $query->num_rows();
                    }
                    else
                    {
                        return $query->result();
                    }
                }
                else
                {
                    return $query->result();
                }  
            }
            else
            {
                if ($type == 'num_rows') 
                {
                    return 0;
                }
                else
                {
                    return array();
                }
                
            }
        }
        else
        {
            return array();
        }
    }
    /**
    * Total elements in an entity
    * Creation date: 29/07/2018
    *
    * @param table String 
    * @param where String | array
    * 
    * @return Interger
    */
    public function get_total($table, $where = NULL)
    {
        if(!empty($where))
        {
            $this->db->where($where);
        }
        $query = $this->db->get($table);
        return $query->num_rows();
    }

    /**
    * select content
    * Creation date: 19/03/2019
    *
    * @param table String 
    * @param attributes String 
    * @param where String | array
    * @param default String | array
    * 
    * @return Interger
    */
    public function select($table = '', $attributes = '' ,$where = '')
    {

        if (empty($attributes)) 
        {
            return false;
        }

        if(!empty($where))
        {
            $this->db->where($where);
        }

        $query = $this->db->get($table);
        $areas[''] = 'Seleccione una opción';
        if ($query->num_rows() > 0)
        {
            $string = '';
            $counter = 0;
            foreach($query->result_array() as $key => $row)
            {
                foreach ($attributes as $keys => $value) 
                {
                    $counter = $keys+1;
                    if (!empty($attributes[$counter])) 
                    {
                        $string .=  ' '.$row[ $attributes[$counter]];
                    }
                    
                }
                
                $areas[htmlspecialchars($row[ $attributes[0] ], ENT_QUOTES)] = htmlspecialchars($string, ENT_QUOTES);

                $string = '';
                $counter = 0;
            }
                
            $query->free_result();
        }
        return $areas;
    }

    /**
    * max elements
    * Creation date: 29/01/2019
    *
    * @param table String 
    * @param where String | array
    * @param element String
    * 
    * @return Interger
    */
    public function max($table, $where = NULL, $element = '')
    {
        if (!empty($element)) 
        {
            $this->db->select_max($element,'maximum');
        }
        if(!empty($where))
        {
            $this->db->where($where);
        }
        $query = $this->db->get($table);
        if (!empty($query)) 
        {
            return $query->row()->maximum;
        }
       
    }

}

/* End of file Multipurpose_model.php */
/* Location: ./application/models/Multipurpose_model.php */
