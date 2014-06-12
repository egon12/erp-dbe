<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * PO_model...
 *
 * todo ada unsinkron antara receipt model dengan po_model...
 * harusnya dia memiliki fungsi-fungsi yang sama...
 * tapi sayangnyanya hal ini tidak begitu...
 *
 * pada receipt model menggunakan set untuk store to database...
 * sednagkan pada purchase_order menggunakan create....
 * 
 * mungkin prosesnya ialah
 * seperti yang dibikin dani pada waktu itu....
 *
 * create, approve, sent, 
 *
 * todo bikin cancel payment pikirin aja dulu deh...
 */

class Po_model extends CI_Model
{

    public $id;
    public $created_on;
    public $approved_on;
    public $sent_on;
    public $discount;
    public $postage;
    public $tax;
    public $description;
    public $active;

    public $user_create;
    public $user_approve;

    public $vendor_id;
    public $name;
    public $address;
    public $phone;
    public $email;

    public $lines;
    public $subtotal;
    public $total;

    public $arrived_status;
    public $arrived_date;
    public $payments;
    public $payments_total;

    /**
     *
     */
    function __construct () {
        parent::__construct();
        $this->load->database();
        $this->table = 'po';
        $this->table_line = 'po_line';
        $this->table_payments = 'po_payment';
    }


    /**
     *
     */
    function add($po, $lines, $payment=0) {
        /* checking date's format */
        $created_on = strtotime ($po['created_on']);
        if (!$created_on) { throw new Exception ("Format tanggal salah mohon gunakan yyyy-mm-dd hh:mm:ss"); }
        $po['created_on'] = date ('Y-m-d H:i:s', $created_on);

        if (preg_match("/^[0-9,]+$/", $po['discount'])) $po['discount'] = str_replace(',', '', $po['discount']);
        if (preg_match("/^[0-9,]+$/", $po['tax'])) $po['tax'] = str_replace(',', '', $po['tax']);
        if (preg_match("/^[0-9,]+$/", $po['postage'])) $po['postage'] = str_replace(',', '', $po['postage']);

        /* insert the data */ 
        if ( $this->db->insert($this->table, $po) )  {
            $this->id = $this->db->insert_id();

            /* error checking */
            if($this->id == NULL) {
                throw new Exception ("ID kosong, ada kemungkinan data tidak masuk!");
            }

            /* insert the line */
            foreach ($lines as $line) {
                $this->add_line($line['code'], $line['quantity'], $line['price']);	
            }

            /* pay dp */
            $this->pay($payment, $po['user_id_create']);

            return $this->get();
        }
    }

    /**
     * Editing Nested Form is never easy.
     * Usually I del all the child and then build the new one.
     * And I will do in this also
     *
     */
    function edit ($po_id, $po, $lines, $payment=0) {
        /* id */
        $this->id = $po_id;

        /* checking date's format */
        $created_on = strtotime ($po['created_on']);
        if (! $created_on) { throw new Exception ("Format tanggal salah mohon gunakan yyyy-mm-dd hh:mm:ss"); }
        $po['created_on'] = date ('Y-m-d H:i:s', $created_on);

        if (preg_match("/^[0-9,]+$/", $po['discount'])) $po['discount'] = str_replace(',', '', $po['discount']);
        if (preg_match("/^[0-9,]+$/", $po['tax'])) $po['tax'] = str_replace(',', '', $po['tax']);
        if (preg_match("/^[0-9,]+$/", $po['postage'])) $po['postage'] = str_replace(',', '', $po['postage']);

        if ($payment != NULL) {
            /* Delete all payment have been made */
            $this->db->where ('po_id', $po_id);
            $this->db->delete($this->table_payments);

            /* Set the payment */
            $this->pay($payment, $po['user_id_create'], 'now', $po_id);
        }

        $this->db->where('id' , $po_id);
        $this->db->update($this->table, $po);

        /* Edit line */
        /* Delete all lines */
        $result = $this->get_lines();
        foreach ($result as $row) {
            $this->db->or_where ('id', $row->id);
            $this->db->delete ($this->table_line);
        }

        /* Add line */
        foreach ($lines as $line) {
            $this->add_line($line['code'], $line['quantity'], $line['price']);	
        }
    }

    /**
     * set_line
     *
     * set satu baris
     */
    private function add_line($code, $quantity, $price, $po_id=NULL) {
        if ($po_id != NULL) {$this->id = $po_id;}

        //remove commas from numeric strings
        if (preg_match("/^[0-9,]+$/", $price)) $price = str_replace(',', '', $price);
        
        $data = array (
            'code' => $code,
            'price' => $price,
            'quantity' => $quantity,
            'po_id' => $this->id,
        );

        $this->db->insert($this->table_line, $data);
        //todo error checking is it neccesarry in here?
    }

    /**
     * fungsi approve oleh owner
     *
     */
    function approve($user_id, $po_id=NULL) {
        if ($po_id != NULL) 
            $this->id = $po_id;
        $data = array (
            'user_id_approve' => $user_id,
            'approved_on' => date('Y-m-d h:i:s'),
        );
        $this->db->where('id', $po_id);
        $this->db->update($this->table, $data);
    }

    /**
     * gua masih belum tahu sih...
     * tapi kayanya sent akan terset otomatis
     * apabila orang tersebut mengeklik print
     * atau email
     * sent hanya bisa ditandai apabila approved_on 
     * sudah ada...
     *
     */
    function sent($po_id=NULL) {
        if ($po_id != NULL) 
            $this->id = $po_id;

        $po = $this->get($po_id);

        if ($po->approved_on != NULL ) {
            $data = array (
                'sent_on' => date ('Y-m-d H:i:s'),
            );
            $this->db->where('id', $po_id);
            $this->db->update($this->table, $data);
        }
    }

    /**
     * ok ini dipake ketika barang sudah datang
     * @param $date waktu dalam bentuk string...
     *
     * arrive hanya bisa dilakukan sekali.
     * null apabila tidak ada
     *
     */
    function arrive ($po_line_id, $date) {
        $data = array(
            'arrived_at' => $date,
        );
        $this->db->where('id', $po_line_id);
        $this->db->where('arrived_at IS NULL', null);
        $this->db->update($this->table_line, $data);

        $this->db->where('id', $po_line_id);
        $query = $this->db->get($this->table_line);
        return $query->row();
    }

    /**
     * payment bayar..
     *
     */
    function pay ($amount, $user_id, $date='now', $po_id=NULL) {
        if ($po_id != NULL)  {
            $this->id = $po_id;
        }
        if (preg_match("/^[0-9,]+$/", $amount)) $amount = str_replace(',', '', $amount);

        $data = array(
            'amount' => $amount,
            'user_id' => $user_id,
            'date' => date ('Y-m-d H:i:s', strtotime($date)),
            'po_id' => $this->id,
        );

        $this->db->insert($this->table_payments, $data);
        if ($this->db->insert_id() == NULL) {
            throw new Exception ("Ada kemungkinan pembayaran tidak masuk dalam database");
        }

        /* perlu non aktif setelah bayar? */
        /* well it dont
        $this->get();
        if ($this->total == $this->payments_total) {
            $this->db->where('id', $po_id);
            $this->db->update($this->table, array ('active' => FALSE) );
        }
         */
    }

    function delete_pay ($payment_id) {
        /* delete from table */
        $this->db->where('id', $payment_id);
        $this->db->delete($this->table_payments);
    }

    /**
     *
     */
    function delete($user_id, $po_id=NULL) {
        if ($po_id != NULL)  {
            $this->id = $po_id;
        }
        $this->db->where('id', $po_id);
        $this->db->delete($this->table);
    }


    /**
     * todo table vendor dan table users hardcode gila....
     *
     *
     */
    function get($po_id = NULL) {
        if ($po_id != NULL) {$this->id = $po_id;}
        if ($this->id == NULL) {throw Exception ('Error Purchase Order ID tidak boleh NULL');}
            $this->db->select ($this->table.'.id')
            ->select ($this->table.'.created_on')
            ->select ($this->table.'.approved_on')
            ->select ($this->table.'.sent_on')
            ->select ($this->table.'.discount')
            ->select ($this->table.'.postage')
            ->select ($this->table.'.tax')
            ->select ($this->table.'.description')
            ->select ($this->table.'.active')

            ->select ($this->table.'.vendor_id')
            ->select ('vendors.name')
            ->select ('vendors.address')
            ->select ('vendors.phone')
            ->select ('vendors.email')

            ->select ('CONCAT (u1.first_name, " ", u1.last_name) AS user_create', FALSE)
            ->select ('CONCAT (u2.first_name, " ", u2.last_name) AS user_approve', FALSE);

        $this->db->where ($this->table.'.id', $this->id);
        $this->db->join ('vendors', 'vendor_id = vendors.id');
        $this->db->join ('users AS u1', 'user_id_create = u1.id');
        $this->db->join ('users AS u2', 'user_id_approve = u2.id', 'left');
        
        $query = $this->db->get($this->table );

        $row = $query->row();

        /* Setting satu-satu */
        $this->created_on = $row->created_on;
        $this->approved_on = $row->approved_on;
        $this->sent_on = $row->sent_on;
        $this->discount = $row->discount;
        $this->postage = $row->postage;
        $this->tax = $row->tax;
        $this->description = $row->description;
        $this->active = $row->active;

        $this->user_create = $row->user_create;
        $this->user_approve = $row->user_approve;

        $this->vendor_id = $row->vendor_id;
        $this->name = $row->name;
        $this->address = $row->address;
        $this->phone = $row->phone;
        $this->email = $row->email;


        $this->lines = $this->get_lines();
        $this->subtotal = $this->get_lines_total();
        $this->total = $this->subtotal - $this->discount + $this->tax + $this->postage;

        $this->arrived_status = $this->arrived_status();
        $this->arrived_date = $this->arrived_date();
        $this->payments = $this->get_payments(); 
        $this->payments_total = $this->get_payments_total(); 

        return $this;
    }


    /**
     * ok dibutuhin di stock
     * buat lihat jumlah secara keseluruhan
     *
     *
     */
    function get_line($po_line_id) {
        $this->db->where('id', $po_line_id);
        $query = $this->db->get($this->table_line);
        return $query->row();
    }


    /**
     *
     * todo mungkin harus memakai product model daripada langsung ke database...
     * Tapi what the hell... Gua lebih biasa pake MySQL dibandingin sama PHP
     * todo benerin javascript po printer (pake back ngga gitu bagus..)
     *
     */
    function get_lines($po_id=NULL) {
        if ($po_id != NULL) {$this->id = $po_id;}

            $this->db->select($this->table_line.'.id')
            ->select($this->table_line.'.code')
            ->select('product.name') 

            ->select($this->table_line.'.quantity') 

            ->select($this->table_line.'.price') 
            ->select($this->table_line.'.quantity * '.$this->table_line.'.price as total')

            ->select($this->table_line.'.arrived_at');
        $this->db->join('product', $this->table_line.'.code = product.code');
        $this->db->where ('po_id', $this->id);
        $query = $this->db->get ($this->table_line);
        return $query->result();
    }

    /**
     *
     */
    function get_lines_total ($po_id=NULL) {
        if ($po_id != NULL) {$this->id = $po_id;}

            $this->db->select( 'SUM(quantity * price) as total', FALSE);
        $this->db->where ('po_id', $this->id);
        $query = $this->db->get ($this->table_line);
        return $query->row()->total;
    }

    /**
     *  
     *
     */
    function arrived_status($po_id=NULL) {

        $this->db->where ('po_id', $this->id);
        $total = $this->db->count_all_results($this->table_line);

        $this->db->where ('po_id', $this->id);
        $this->db->where ('arrived_at is not null', null);
        $instock = $this->db->count_all_results($this->table_line);
        $percentage = $instock/$total;


        return $percentage;
    }

    function arrived_date($po_id=NULL) {
        if ($po_id != NULL) 
            $this->id = $po_id;
        $this->db->where('po_id', $this->id);
        $this->db->select_max('arrived_at');
        $this->db->where ('arrived_at is not null', null);
        $query = $this->db->get($this->table_line);
        $row = $query->row();

        if ($row->arrived_at != NULL )  
            return date('d M Y', strtotime($row->arrived_at));
    }

    /**
     * ok get payments
     *
     *
     */
    function get_payments($po_id=NULL) {
        if ($po_id != NULL) {
            $this->id = $po_id;
        }
        $this->db->where('po_id', $this->id);
        $query = $this->db->get($this->table_payments);
        return $query->result();
    }

    /**
     * ok this is for payment status
     *
     */
    function get_payments_total($po_id=NULL) {
        if ($po_id != NULL) {
            $this->id = $po_id;
        }

        $this->db->where('po_id', $this->id);
        $this->db->select_sum('amount');
        $query = $this->db->get($this->table_payments);
        return $query->row()->amount;
    }

    function get_all() {
        $query = $this->db->get($this->table);
        return $query->result();
    }

    /**
     * Ok, get active ini untuk si Admin...
     *
     *
     */
    function get_active() {
        $this->db->select('id');
        $this->db->where('active', TRUE);
        $this->db->order_by('id','desc' );

        $query = $this->db->get($this->table);
        $result = $query->result();
        $return = array();
        foreach ($result as $row) {
            array_push($return, clone $this->get($row->id));
        }
        return $return;
    }

    /**
     * get non active
     */
    function get_inactive() {
        $this->db->select('id');
        $this->db->where('active', FALSE);

        $query = $this->db->get($this->table);
        $result = $query->result();
        $return = array();
        foreach ($result as $row) {
            array_push($return, clone $this->get($row->id));
        }
        return $return;
    }


    /**
     * ok, ini tampaknya cukup berhasil sih tapi masih
     * sedikit bermasalah...
     *
     */
    function notyet_approved () {
        $this->db->select ('id');
        $this->db->where ('approved_on is null', null);
        $this->db->where ('active', TRUE);
        $query = $this->db->get($this->table);
        $result = $query->result();
        $return = array();
        foreach ($result as $row) {
            array_push($return,clone $this->get($row->id));
        }
        return $return;

    }

    function notyet_approved_number() {
        $this->db->where ('approved_on is null', null);
        $this->db->where ('active', TRUE);
        return $this->db->count_all_results($this->table);
    }

    /**
     * todo kenapa gua ngga bisa mikir di sini
     * yang jelas mesti pikirin yang udah di sent, tapi belum arrived
     * todo bisa pake table_line aja 
     *
     *
     */
    function notyet_arrived() {
        $this->db->select ('id');
        $this->db->where ('approved_on is not null', null);
        $this->db->where ('active', TRUE);
        $query = $this->db->get($this->table);
        $result = $query->result();
        $return = array();
        foreach ($result as $row) {
            $po = $this->get($row->id);
            if ($po->arrived_status < 1)
                array_push($return,clone $po);
        }
        return $return;
    }

    /**
     *
     */
    function notyet_arrived_number() {
        $this->db->where('arrived_at is NULL', NULL);
        $this->db->where('approved_on is NOT NULL', NULL);
        $this->db->where('active', TRUE);
        $this->db->join($this->table, $this->table.'.id = po_id');
        return $this->db->count_all_results($this->table_line);
    }

    /**
     *
     * todo tampilin nama barangnya juga
     */
    function get_proposal() {
        $this->db->select ($this->table_line.'.*')
            ->select ('product.name');

        $this->db->where ('detected_at is not null', null)
            ->where ('po_id is null', null);

        $this->db->join('product', $this->table_line.'.code = product.code');

        $query = $this->db->get ($this->table_line);

        return $query->result();
    }

    function get_proposal_number() {
        $this->db->where ('detected_at is not null', null)
            ->where ('po_id is null', null);
        return $this->db->count_all_results ($this->table_line);
    }

    /**
     *
     */
    function set_proposal($data) {
        $data1 = array (
            'code' => $data['code'],
            'quantity' => $data['low'] - $data['remaining'],
            'detected_at' => date('Y-m-d H:i:s'),
        );

        $this->db->insert($this->table_line, $data1);
    }

    /**
     *
     */
    function del_proposal($poline_id) {
        $this->db->where('id', $poline_id);
        $this->db->where('po_id is NULL', NULL);
        $this->db->where('detected_at is NOT NULL', NULL);
        $this->db->delete ($this->table_line);
    }

    /**
     *
     */
    function cancel_proposal($code) {
        $this->db->where('code', $code);
        $this->db->where('po_id is NULL', NULL);
        $this->db->where('detected_at is NOT NULL', NULL);
        $this->db->delete ($this->table_line);
    }

    /**
     *
     */
    function set_last_price($price, $code) {

    }

    /**
     *
     */
    function po_line_get_po_id($poline_id) {
        $this->db->where ('id', $poline_id);
        $query = $this->db->get($this->table_line,1);
        return $query->row()->po_id;
    }
}
