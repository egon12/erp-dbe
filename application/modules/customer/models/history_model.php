<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This is for Customer Transaction History
 * But It's should not be in here..
 * It should in POS or in Report
 * But Maybe later
 *
 */
Class History_model extends CI_Model
{
    public function get($customer_id)
    {
        $this->db->where('customer_id', $customer_id);
        $query = $this->db->get('receipts');
        return $query->result();
    }
}
