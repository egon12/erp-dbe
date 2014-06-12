<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Users_Model extends CI_Model
{
	private $table = 'users';
	
	function listing($start = 0, $offset = 10, $search = '', $sort, $sort_dir = 'asc')
	{
		$fields = $this->db->list_fields($this->table);
		$sort = (!$sort) ? $fields[0] : $sort;
		
		// select db columns which related for the result
		$this->db->select('users.id, users.username, users.first_name, users.last_name, users.email, users.active, users_groups.group_id, groups.name');
		$this->db->from('users');
		
		// join "users", "users_groups", and "groups" tables to get groups name for every user
		$this->db->join('users_groups', 'users.id=users_groups.user_id', 'left' );
		$this->db->join('groups', 'users_groups.group_id=groups.id', 'inner' );
		
		// order your column
		$this->db->order_by($sort, $sort_dir);
		
		// my default column for search is "username" and "first_name" in table "users"
		$this->db->like('username', $search);
		$this->db->or_like('first_name', $search);
		
		// limit result for pagination
		$this->db->limit($offset, $start);
		
		// return the result
		return $this->db->get();
	}
	
	function count_all()
	{
		return $this->db->count_all($this->table);
	}
	
	function search($search = '')
	{
		$fields = $this->db->list_fields($this->table);
		
		// my default column for search is "username" and "first_name" in table "users"
		
		$this->db->like('username', $search);
		$this->db->or_like('first_name', $search);
		
		// return the result
		return $this->db->get($this->table);
	}
}

?>
