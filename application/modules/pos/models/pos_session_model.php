<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * POS Session
 *
 * Class ini berfungsi untuk validasi dari pengguna. Dia.
 * agak mirip dengan ci_session. Walaupun yah agak beda.
 * Ada kemungkinan gua akan menggunakan memory untuk database ini,
 * karena data yang disimpan tidak akan digunakan terlalu lama.
 *
 * Apakah ada kekurangannya jika disimpan sebagai memory?
 *
 * table structure
 *      id
 *      session_id
 *      activity
 *      parameter
 *      timestamp
 *
 * sequence
 *      set_customer
 *      loop
 *          set_product
 *          set_quantity
 *      set_discount
 *      set_method
 *      set_payment
 *
 *  todo is it better user is set in get_receipt();
 *  todo munkgin akan lebih baik apabila kodenya dibagi dua.
 *  todo bikin akses ke databasenya sekali aja untuk check, total and last
 *  1 kode session
 *  2 kode pos_session
 *      
 * @author Egon Firman <egon.firman@gmail.com>
 */
class POS_session_model extends CI_Model
{

    private $table;

    /**
     * exist for nothing
     */
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('product_model');
        $this->table = 'pos_session';
    }

    /**
     * start the session
     */
    function start($parameter) 
    {
        $id = uniqid();

        $data = array (
            'session_id' => $id,
            'activity'   => 'session_start',
            'parameter'  => $parameter
        );
        
        $this->db->insert($this->table, $data);

        $cookie = array (
            'name' => 'session_id',
            'value' => $id,
            'expire' => 300 // 300 seconds to finish the transaction 
        );

        $this->input->set_cookie($cookie);

        return $id;
    }

    /**
     * check session is exist in database and exist in client cookie;
     */
    function check() 
    {
        // get from the cookie if null
        $id = $this->input->cookie('session_id');

        $data = $this->_get_data_for_check($id);

        if (sizeof($data) > 0) {
            return $id;
        } else {
            //throw new Exception ('session_id not valid');
            throw new Exception('session_not_valid');
        }
    }

    /**
     * add activity
     *
     * known activity
     * session_start
     * session_end
     *
     * set_customer
     * set_product
     * set_quantity
     * set_discount
     * set_method
     * set_payment
     *
     */
    function add($activity, $parameter, $id = Null) 
    {
        $id = $this->check();

        $data = array (
          'session_id' => $id,
          'activity'   => $activity,
          'parameter'  => $parameter
        );

        $this->db->insert($this->table, $data);
    }

    /**
     * see last activity
     * todo
     */
    function last()
    {
        $id = $this->check();
        $data = $this->_get_data_for_check($id);
        return $data[sizeof($data) -1];
    }

    /**
     * getall activity and destroy it
     * todo error handling?
     */
    function end($parameter)
    {
        $id = $this->check();

        $data = array (
            'session_id' => $id,
            'activity' => 'session_end',
            'parameter' => $parameter
        );

        $this->db->insert($this->table, $data);

        // get the data
        $this->db->where('session_id', $id);
        $this->db->order_by('id');

        $result = $this->db->get($this->table)->result();

        $this->db->delete($this->table, array('session_id' => $id) );

        // delete the cookie
        $this->input->set_cookie('session_id');

        return $result;
    }

    /**
     * sequence 1
     */
    function set_customer($customer_id)
    {
        $this->start($customer_id);
    }

    /**
     * sequence 2 loop1
     */
    function set_product($product_code)
    {
        $this->add('set_product', $product_code);
    }

    /**
     * sequence 2 loop2
     */
    function set_quantity($quantity)
    {
        $this->add('set_quantity', $quantity);
    }

    /**
     * sequence 3 part 1
     */
    function set_discount($discount)
    {
        $this->add('set_discount', $discount);
    }

    /**
     * sequence 3 part 2
     */
    function set_method($method)
    {
        $this->add('set_method', $method);
    }

    /**
     * sequence 3 part 3
     */
    function set_payment($payment)
    {
        $this->add('set_payment', $payment);
    }

    /**
     * sequence 3 part 4
     */
    function get_receipt($user_id)
    {
        $activities = $this->end($user_id);
        return $this->to_receipt($activities);      
    }

    /**
     * check wether tulis_produk in session
     */
    function check_product_exist() 
    {
        $id = $this->check();
        $data = $this->_get_data_for_check($id);
        foreach ($data as $s) {
            if ($s->activity == 'set_product') {
                return true;
            }
        }
        return false;
    }

    /**
     * convert session data to receipt
     */
    function to_receipt($result) 
    {
        $receipt = array();
        $receipt['lines'] = array();

        $i = 0;

        while ($result[$i] != NULL) {
            switch ($result[$i]->activity) {
            case 'session_start':
                $receipt['customer_id'] = $result[$i]->parameter;
                break;
            case 'set_product':
                $b = array();
                $b['code'] = $result[$i]->parameter;
                break;
            case 'set_quantity':
                $b['quantity'] = $result[$i]->parameter;
                array_push ($receipt['lines'], $b);
                break;
            case 'set_discount':
                $receipt['discount'] =  $result[$i]->parameter;
                break;
            case 'set_method':
                $receipt['method'] =  $result[$i]->parameter;
                break;
            case 'set_payment':
                $receipt['payment'] =  $result[$i]->parameter;
                break;
            case 'session_end':
                $receipt['user_id'] = $result[$i]->parameter;
                return $receipt;
            }
            $i +=1;
        }
        throw New Exception ('transaction not done yet');
    }

    /**
     * get total of active transaction
     * todo
     */
    function total () 
    {
        $session_id = $this->check();

        $result =  $this->_get_data_for_check($session_id);

        $subtotal = 0;
        $discount = 0;

        // imposeible the first to be tulis_jumlah (set_quantity)
        while ( current($result) ) {
            if (current($result)->activity == 'set_quantity') {
                $subtotal +=  
                    $this->product_model->get_price(  prev($result)->parameter  )  *
                    next($result)->parameter ;
            } else if (current($result)->activity == 'set_discount') {
                $discount = current($result)->parameter;
            }
            next($result);
        }
        return $subtotal - $discount;
    }

    private function _get_data_for_check($id)
    {
        if (!isset($this->_data_for_check)) {
            $this->db->where('session_id', $id);
            $this->db->order_by('id');

            $query = $this->db->get($this->table);
            $this->_data_for_check = $query->result();
        }
        return $this->_data_for_check;
    }
}

