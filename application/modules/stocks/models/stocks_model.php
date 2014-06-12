<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Stock Model
 *
 * Stock menunjukan jumlah persediaan dalam warehouse
 * untuk memilih warehouse mana yang akan dimanipulasi
 * (ditambah, dikurangi atau sebagainya) digunakan
 * warehouse_id
 *
 * untuk saat ini ada 3 warehouse
 * 1 master_stock
 * 2 display_stock
 *
 * Ok, mungkin ini bisa dibilang, uji coba
 *
 * permasalahannya ialah 
 * todo buat table baru kaya stock_outpending
 * yang isinya barang-barang yang harus dikeluarkan
 * dan kepada siapa...
 *
 *
 * ok, jadi mari kita bikin api yang lebih baik..
 *
 * Stock Out
 * $this->stock_model->user ('egon')
 *      ->select('master_stock')
 *      ->link('receipt',13)
 *      ->out(2002, 1, 'Hallo apa kabar?');
 *
 * Stock In 
 * $this->stock_model->user ('egon')
 *      ->select('master_stock')
 *      ->link('po_line',4)
 *      ->in(2002, 1, 'Hallo apa kabar?');
 *
 * Transfer
 * $this->stock_model->user ('egon')
 *      ->select('master_stock')
 *      ->transfer('display_stock',2002, 20, 'Aneh ngga?');
 */

class Stocks_model extends CI_Model
{

    private $user_id;

    private $warehouse_id = 1;

    private $link = NULL;

    private $link_id = NULL;

    public $last_id = NULL;

    /**
     *
     */
    function __construct () {
        parent::__construct();
        $this->load->database();
        $this->table = 'stocks';
        $this->table_control = 'stock_control';
        $this->table_products = 'product';
    }

    /**
     * CRUD	
     *
     * Stock Product
     */
    public function insert($array) { 
        $this->db->set($array); 
        $this->db->insert($this->table_control); 
    }

    public function delete($id) { 
        $this->db->delete($this->table_control, array('id' => $id)); 
    }

    public function update($id, $data) { 
        $this->db->update($this->table_control, $data, array('id' => $id)); 
    }

	public function get_item_by_id($id)
	{

        $this->db->select ($this->table_control.'.*')
            ->select ($this->table_products.'.name')

            ->where ('warehouse_id', $this->warehouse_id)
            ->where ($this->table_control.'.id' , $id)
            ->join ($this->table_products, $this->table_control.'.code = '.$this->table_products.'.code');
        $query = $this->db->get($this->table_control);
        return $query->row();
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
		
		//my default column for search is "code", and "name" in table "products"
		$this->db->like('name', $search);
		
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
		
		//my default column for search is "code", and "name" in table "products"
		
		$this->db->from($this->table);
		$this->db->like('name', $search);
		$this->db->or_like('address', $search);
		$this->db->or_like('phone', $search);
		$this->db->or_like('email', $search);
		
		//return the result
		return $this->db->get();
	}
    
    function get_all() {
        return $this->db->get($this->table)->result();
    }

    /**
     *
     */
    function user($user) {
        if (is_numeric($user)) 
            $this->user_id = (int) $user;
        elseif (is_string ($user)) {
            $query = $this->db->where ('username', $user)
                ->get ('users');
            if ($query->num_rows() > 0) {
                $this->user_id = $query->row()->id;
            } else {
                new Exception ("Cannot find user with name $user");
            }
        }
        return $this;
    }

    /**
     * Select Warehouse
     */
    function select ($warehouse) {
        if (is_int($warehouse)) 
            $this->warehouse_id = $warehouse;
        elseif (is_string ($warehouse)) {
            $query = $this->db->where ('name', $warehouse)
                ->get ('warehouse');
            if ($query->num_rows() > 0) {
                $this->warehouse_id = $query->row()->id;
            } else {
                new Exception ("Cannot find warehouse with name $warehouse");
            }
        }
        return $this;
    }

    /**
     * Stock In
     */
    function in($code, $quantity, $description=NULL) {

        $data = array (
            'code'         => $code,
            'in_out'       => $quantity,
            'description'  => $description,

            'warehouse_id' => $this->warehouse_id,
            'link'         => $this->link,
            'link_id'      => $this->link_id,
            'user_id'      => $this->user_id,
        );

        $this->db->insert($this->table, $data);

        /* For transfer */
        $this->in_id = $this->db->insert_id();

        /* Ada perubahan stock Check minimum */
        $this->check_minimum($code);
    }

    /**
     * Stock Out 
     */
    function out($code, $quantity, $description=NULL) {

        if ($this->remaining($code) < $quantity) {
            $description .= " Barang yang tersedia tidak cukup";
        }

        $data = array (
            'code'         => $code,
            'in_out'       => $quantity * -1,
            'description'  => $description,

            'warehouse_id' => $this->warehouse_id,
            'link'         => $this->link,
            'link_id'      => $this->link_id,
            'user_id'      => $this->user_id,
        );

        $this->db->insert($this->table, $data);
        $return = $this->db->last_query();

        /* For transfer */
        $this->out_id = $this->db->insert_id();

        /* setiap ada barang keluar cek minimum */
        $this->check_minimum ($code);
        return $return;
    }

    /**
     * Stock Transfer
     */
    function transfer ($to, $code, $quantity, $description=NULL) {
        $stock_from = $this->get_warehouse_name($this->warehouse_id);

        $this->select($to)
            ->in($code, $quantity, "Dari $stock_from. ".$description);

        $stock_to = $this->get_warehouse_name($this->warehouse_id);

        $this->select($stock_from)
            ->link_with('stock', $this->in_id)
            ->out($code, $quantity, "Ke  $stock_to. ".$descrption);

        $this->db->set ('link', 'stock')
            ->set ('link_id', $this->out_id)
            ->where ('id', $this->in_id)
            ->update ($this->table);
    } 

    /**
     * Create link for cancel 
     */
    function link_with($database,$link_id) {
        $this->link = $database;
        $this->link_id = $link_id;
        return $this;
    }

    /**
     * Remaning stock at time
     */
    function remaining ($code, $date='now') {

        $date = date ('Y-m-d H:i:s', strtotime($date));
        $this->db->select_sum('in_out')
            ->where('code', $code) 
            ->where('timestamp <=', $date)
            ->where('warehouse_id', $this->warehouse_id);
        $query = $this->db->get ($this->table);

        return $query->row()->in_out;
    }

    /**
     * untuk membatalkan barang masuk atau keluar
     *
     */
    function cancel($id) {
        $this->db->where ('id', $id);
        $query = $this->db->get ($this->table, 1);
        $row = $query->row();
        if ($row->link == "stock") {
            if ($row->link_id != NULL) {
                $this->db->where ('id' , $row->link_id);
                $this->db->or_where ('id' , $id);
                $this->db->delete ($this->table);
            } else {
                $this->db->where ('link', 'stock');
                $this->db->where ('link_id', $id);
                $this->db->delete ($this->table);

                $this->db->where ('id' , $id);
                $this->db->delete ($this->table);
            }
        } else if ($row->link == "po_line") {
            /* Delete Arrive at */
            $this->db->where ('id', $row->link_id);
            $this->db->set('arrived_at', NULL);
            $this->db->update('po_line');
            /* Delete the stock */
            $this->db->or_where ('id' , $id);
            $this->db->delete ($this->table);
        } else if ($row->link == "receipt") {
            throw new Exception ("Transaksi tidak bisa dihapus, silahkan hapus dari Cancelation");
        } else {
            $this->db->or_where ('id' , $id);
            $this->db->delete ($this->table);
        }
    }
    /**
     * check minimum
     *
     * todo kotor banget codingnya..
     * rapihihn
     *
     */
    function check_minimum ($code) {

        $remaining  = $this->remaining($code);

        /* get minimum value */
        $this->db->where('warehouse_id', $this->warehouse_id)
                 ->where('code', $code);
        $row = $this->db->get($this->table_control)->row();

        /*compare remaining with minimum */
        if ($row != NULL && $remaining < $row->low) {

            $data = array(
                'code' => $row->code,
                'remaining' => $remaining,
                'low'       => $row->low,
            );

            $this->deficit($data);
            
        } elseif ( $row != NULL && $remaining > $row->low ){
            /* if remaining still above low */
            $this->surplus($code);
        }
    }

    /**
     * todo change to notify?
     */
    private function deficit($product) {

        if ($this->warehouse_id == 2) {
            $data = array (
                'code' => $product['code'],
                'quantity' => $product['max_amount'] - $product['remaining'],
                'detected_at' => date('Y-m-d H:i:s'),
                'link' => 'display_stock',
            );

            $this->db->insert('stock_out_pending', $data);
        } elseif ($this->warehouse_id == 1) {
            $this->load->model('purchasing/po_model');
            $this->po_model->set_proposal($product);
        }
        /*
            $this->load->library('email');

        $this->email->from('machine@enfysolution.co.id', 'Automatic Email');
        $this->email->to('egon.firman@gmail.com');
        $this->email->cc('another@another-example.com');

        $this->email->subject('Limit Stock Detected');
        $this->email->message('Stok dengan kode '.$row->code.' tinggal berjumlah '.$remaining);

        $this->email->send();
             */
    }

    /**
     *
     */
    private function surplus($code) {
        if ($this->warehouse_id == 2) {
        $this->db->where('code', $code)
            ->where('link', 'display_stock')
            ->delete('stock_out_pending');
        } elseif ($this->warehouse_id == 1) {
            $this->load->model('purchasing/po_model');
            $this->po_model->cancel_proposal($code);
        }
    }



    /**
     * Tampilan utama warehouse kali yah ini..
     * pikirin apakah memang lebih baik seperti ini?
     *
     */
    function remaining_product_table($start, $end) {

        $result = $this->get_items();

        $start = date('Y-m-d H:i:s', $start);
        $end = date('Y-m-d H:i:s', $end);

        foreach ($result as $product) {
            $product->stock_start = $this->remaining($product->code, $start);
            $product->stock_in = $this->get_in($product->code, $start, $end);
            $product->stock_out = $this->get_out($product->code, $start, $end);
            $product->stock_end = $this->remaining($product->code, $end);
        }
        return $result;
    }

    private function get_in($code, $start, $end) {
        
        $this->db->select_sum('in_out');
        $this->db->where('code', $code) 
            ->where('timestamp >', $start)
            ->where('timestamp <', $end)
            ->where('warehouse_id', $this->warehouse_id)
            ->where('in_out >',0)
            ->from($this->table);

        $query = $this->db->get();
        return  $query->row()->in_out;
    }

    private function get_out($code, $start, $end) {

        $this->db->select_sum('in_out');
        $this->db->where('code', $code) 
            ->where('timestamp >', $start)
            ->where('timestamp <', $end)
            ->where('warehouse_id', $this->warehouse_id)
            ->where('in_out <',0)
            ->from($this->table);

        $query = $this->db->get();
        return  $query->row()->in_out;
    }

    /**
     * function ini dinamakan card sesuai dengan
     * nama dari sebenarnya stock_card.
     * todo bikin penamaan yang lebih bener
     * di controller namanya tuh apa gitu
     * di models namanya aneh juga.
     *
     *
     */
    function get_card($code, $start, $end) {

        $start = date ('Y-m-d H:i:s', $start);
        $end = date ('Y-m-d H:i:s', $end);

        $this->db->select ($this->table.'.*')
            ->select ('users.username');

        $this->db->where ('timestamp <', $end)
            ->where ('timestamp >', $start)
            ->where ('warehouse_id', $this->warehouse_id)
            ->where ('code', $code)
            ->join('users', $this->table.'.user_id = users.id', 'left');
        $query = $this->db->get ($this->table);
        $return =  $query->result();

        $remaining = $this->remaining($code, $start, $this->warehouse_id);

        foreach ($return as $row) {
            $remaining += $row->in_out;
            $row->stock = $remaining;
        }

        return $return;
    }

    /**
     * For Display
     */
    function get_warehouse_name($id) {
        return $this->db->get_where ('warehouse', array ('id' => $id))->row()->name;
    }

    /**
     *
     */
    function get_items() {
        $this->db->select ($this->table_control.'.*')
            ->select ($this->table_products.'.name')

            ->where ('warehouse_id', $this->warehouse_id)
            ->join ($this->table_products, $this->table_control.'.code = '.$this->table_products.'.code');
        $query = $this->db->get($this->table_control);
        return $query->result();
    }

    /**
     *
     */
    function get_name($code) {
        return $this->db->get_where($this->table_products, array ('code' => $code))->row()->name;
    }

    /**
     * all items but code only
     */
    private function get_all_code($warehouse_id=NULL) {
        $this->db->select ('code');
        if ($warehouse_id != NULL )
            $this->db->where('warehouse_id', $warehouse_id);

        $query = $this->db->get($this->table);

        $return = array();
        foreach ($query->result() as $row) {
            array_push($return, $row->code);
        }
        return $return;
    }

    /**
     * link with Point of Sales
     */
    function scan_receipt($receipt) {
        foreach ($receipt->lines as $line) {
            if (in_array($line->code, $this->get_all_code() ) ) {
                $description = 'Receipt no '.$receipt->id.'. (Dibeli oleh '.$receipt->customer_name.')';
                $this->select('master_stock')
                    ->user($receipt->user_id)
                    ->link_with ('receipt',$receipt->id)
                    ->out($line->code, $line->quantity, $description);
            }
        }
    }

    /**
     * link with Point of Sales
     */
    function unscan_receipt($receipt) {
        $this->db->where('link', 'receipt');
        $this->db->where('link_id', $receipt->id);
        $this->db->delete($this->table);
    }

    /**
     * Stock Out Order
     */
    function get_stockout_pending() {
        $this->db->select ('stock_out_pending.*')
            ->select ('products.name')
            ->join ('products', 'stock_out_pending.code = products.code');
        $query = $this->db->get('stock_out_pending');
        return $query->result();
    }

    /**
     *
     */
    function get_stockout_pending_number() {
        return $this->db->count_all_results('stock_out_pending');
    }

    /**
     *
     */
    function del_stockout_pending($id) {
        $this->db->where('id', $id)
            ->delete('stock_out_pending');
    }

}
