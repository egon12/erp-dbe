<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Customer_model
 *
 * used to search and get
 * used in pos controller
 */
class Customer_model extends CI_Model{

    /**
     */
    function __construct()
    {
		parent::__construct();
		$this->load->database();
        $this->table = 'customer';
    }


    /**
     * get 
     *
     */
    function get($id)
    {
		$this->db->where('id', $id);
		$query = $this->db->get($this->table, 1);

		if ($query->num_rows() > 0) {
		    return $query->row();
		} else {
		    $errormsg = "$id not exist";
		    throw new Exception($errormsg);
		}
    }

    /**
     * search
     *
     * search in name, address and phone
     *
     * @param $query nama, alamat atau no telpon yang dicari
     * @return array berisi object data pasien
     */
    function search($query) 
	{
		$query = strtolower($query);
		$this->db->like ('LOWER(name)', $query);
		//$this->db->or_like ('LOWER(address)', $query);
		//$this->db->or_like ('phone', $query);
		$query = $this->db->get('customer');
		return $query->result();
    }

    /**
     * search_and_get
     *
     * if query empty, error
     * if 0-5 digit
     *
     */
    function search_and_get($query) {
        if ($query == '') {
            throw new Exception("Query is required, cannot be empty");
        } else if ( preg_match("/^\d{0,5}$/", $query )) {
            return $this->get($query);
        } else {
            $hasil = $this->search($query);
            if ( count($hasil) > 1 ) 
                throw new Exception ( "There are more then one '$query', choose one" );
            if ( count($hasil) == 0 ) 
                throw new Exception ( "There are no '$query'" );
            return $this->get($hasil[0]->id);
        }
    }

    /**
     * last transaction
     *
     */
    function last_visit ($customer_id)
    {
        $receipt = $this->db
            ->select ('timestamp')
            ->order_by ('timestamp', 'desc') 
            ->get_where ('receipts', array ('customer_id' => $customer_id) , 1)->row();
	   if ($receipt) {
              return $receipt->timestamp;
        }
    }
}
