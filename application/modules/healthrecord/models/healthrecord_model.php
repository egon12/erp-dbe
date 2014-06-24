<?php

class HealthRecord_Model extends CI_Model {

    protected $table_prefix = 'healthrecord_';

    public function insert($table, $data = array())
    {
        if ($data['customer_id'] == null) {
            throw new Exception('Customer must be set first.');
        }

        $data['user_id'] = $this->session->userdata('user_id');

        foreach ($data as $key => $value) {
            if ($value == "") {
                unset ($data[$key]);
            }
        }

        $this->db->insert($this->table_prefix.$table, $data);
        // todo data validation
    }

    public function get($table, $customer_id = null, $date_timestamp = null)
    {
        $this->db->select($this->table_prefix.$table . '.* , users.username');
        $this->db->join('users', 'user_id = users.id');
        $this->db->order_by('id', 'desc');
        // todo error checking
        if ($date_timestamp) {
            $this->db->where(array('date(timestamp)' => $date_timestamp));
        }
        return $this->db->get_where($this->table_prefix.$table, array('customer_id' => $customer_id))->result();
    }

    public function get_all($table, $customer_id)
    {
        $this->db->select($this->table_prefix.$table . '.* , users.username');
        $this->db->join('users', 'user_id = users.id');
        $this->db->order_by('id', 'desc');
        return $this->db->get_where($this->table_prefix.$table, array('customer_id' => $customer_id))->result();
    }


    public function process_image_file()
    {
        // todo make this
    }

    public function get_perdate($table, $customer_id = null, $date_timestamp = null)
    {
        $data = $this->get($table, $customer_id, $date_timestamp);
        $result = array();
        foreach ($data as $row) {
            $date = date('M, d Y', strtotime($row->timestamp));
            $result[$date] = $row;
        }
        return $result;
    }

    public function get_all_perdate($table, $customer_id = null)
    {
        $data = $this->get_all($table, $customer_id);
        $result = array();
        foreach ($data as $row) {
            $date = date('M, d Y', strtotime($row->date));
            $result[$row->id] = $row;
        }
        return $result;
    }
}
