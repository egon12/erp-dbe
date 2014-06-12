<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Product_Model extends CI_Model
{
	//define your target table
	private $table = 'product';
	
	public function insert($array)
	{
		$this->db->set($array);
		$this->db->insert($this->table);
	}
	
	public function delete($id)
	{
		$this->db->delete($this->table, array('id' => $id));
	}
	
	public function update($id, $data)
	{
		$this->db->update($this->table, $data, array('id' => $id));
	}
	
	public function get_item_by_id($id)
	{
		return $this->db->get_where($this->table, array('id' => $id))->row();
	}
	
	public function get_item_by_code($code)
	{
		return $this->db->get_where($this->table, array('code' => $code))->row();
	}
	
	// for datatables
	function listing($start = 0, $offset = 10, $search = '', $sort, $sort_dir = 'asc')
	{
		$fields = $this->db->list_fields($this->table);
		$sort = (!$sort) ? $fields[0] : $sort;
		
		//select db columns which related for the result
		$this->db->select('*');
		$this->db->from($this->table);
		
		//order your column
		$this->db->order_by($sort, $sort_dir);
		
		//my default column for search is "code", and "name" in table "product"
		$this->db->like('code', $search);
		$this->db->or_like('name', $search);
		
		//limit result for pagination
		$this->db->limit($offset, $start);
		
		//return the result
		return $this->db->get();
	}
	
	function count_all()
	{
		return $this->db->count_all($this->table);
	}
	
	function search($search = '')
	{
		$fields = $this->db->list_fields($this->table);
		
		//my default column for search is "code", and "name" in table "product"
		
		$this->db->from($this->table);
		$this->db->like('code', $search);
		$this->db->or_like('name', $search);
		
		//return the result
		return $this->db->get();
	}

    //for another models
    function get_all () {
        return $this->db->get($this->table)->result();
    }
}
