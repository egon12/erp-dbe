<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Customer_Model extends CI_Model
{
	//define your target table
	private $table = 'customer';
	
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
		
		//my default column for search is "name", and "address" in table "customer"
		$this->db->like('name', $search);
		$this->db->or_like('address', $search);
		
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
		
		//my default column for search is "name", and "address" in table "customer"
		
		$this->db->from($this->table);
		$this->db->like('name', $search);
		$this->db->or_like('address', $search);
		
		//return the result
		return $this->db->get();
	}
}
