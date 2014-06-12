<?php

class Flow_model extends CI_Model 
{
    public $table = 'flow_session';
    public $table_history = 'flow_history';

    public function open($customer_id)
    {
        $this->db->insert($this->table, array(
            'customer_id' => $customer_id,
            'flow_order' => 1
        ));
    }

    public function make_order($customer_id, $order)
    {
        $this->db->insert($this->table, array(
            'customer_id' => $customer_id,
            'flow_order' => 2
        ));
    }

    public function delete_order($id)
    {

    }

    public function edit_order($id)
    {

    }

    public function lock_order($id)
    {

    }

    public function make_invoice($id)
    {

    }

    public function make_receipt($id)
    {

    }

    public function close($customer_id)
    {
        $this->db->query('INSERT INTO ' . $this->table_history .' (SELECT * FROM ' . $this->table . ')');
        $this->db->delete($this->table, array('customer_id' => $customer_id));
    }

    public function get_list($flow_order = null)
    {

        $this->db->select('customer_id, name, max(flow_order) as flow_order')
            ->join('customer', 'customer_id = customer.id')
            ->group_by('customer_id');

        if ($flow_order != null) {
            $this->db->where('flow_order', $flow_order);
        }

        $query = $this->db->get($this->table);

        return $query->result();
    }

    private function check_and_get($customer_id)
    {
        $query = $this->db->get_where($this->table, array('cutomer_id' => $customer_id));
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {

        }
    }
}
