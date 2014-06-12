<?php

class HealthRecord_Model extends CI_Model {

    protected $table_prefix = 'healthrecord_';

    public function insert($table, $data = array())
    {
        if ($data['customer_id'] == null) {
            throw new Exception('Customer must be set first.');
        }

        $data['user_id'] = $this->session->userdata('user_id');

        $this->db->insert($this->table_prefix.$table, $data);
        // todo data validation
    }

    public function get($table, $customer_id = null)
    {
        // todo error checking
        return $this->db->get_where($this->table_prefix.$table, array('customer_id' => $customer_id))->result();
    }

    public function process_image_file()
    {
        // todo make this

    }
}
