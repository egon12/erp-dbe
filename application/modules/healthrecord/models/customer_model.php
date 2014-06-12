<?php

class Customer_model extends CI_Model
{
    private $table = 'customer';

    public function get()
    {
        return $this->db->limit(100)
            ->order_by('id', 'desc')
            ->get($this->table)
            ->result();
    }

    public function get_new()
    {
        return $this->db->limit(100)
            ->order_by('id', 'desc')
            ->where('date(timestamp)', date('Y-m-d'))
            ->get($this->table)
            ->result();
    }

    public function get_name($id)
    {
        return $this->db->where(array(
            'id' => $id
        ))->get($this->table)
        ->row()
        ->name;
    }

    public function search($name)
    {
        return $this->db->like('name', $name)
            ->or_like('id', $name)
            ->limit(100)
            ->get($this->table)
            ->result();
    }

}
