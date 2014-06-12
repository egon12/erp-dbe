<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Receipt_model
 *
 * What is receipt? It is a proff that we have accept some amount of money
 * This model only handle database input of receipt. So it doesn't have any 
 * Business Logic in here
 *
 * Before this, we use this as ORM. Because it's slow, so we decide to handle
 * database with SQL instead. It's far more faster and more stable;
 *
 * todo repair this model
 *  
 * @author Egon Firman <egon.firman@gmail.com>
 */
class Receipt_model extends CI_Model
{

    /**
     * Properties delcaration
     *
     */

    /**
     * receipt.id 
     *
     * berisi no kwitansi
     */
    public $id;

    /**
     * customer id, & name
     * 
     * Seperti pada biasanya, di setiap kwitansi atau receipt
     * ada nama. customer_id apabila anda ingin melihat id dari
     * customer itu
     */
    public $customer_id; 
    public $customer_name;

    /**
     * user_id & user_name
     *
     * Ini berfungsi untuk melihat siapa yang bertanggung jawab
     * atas transaksi ini.
     */
    public $user_id;
    public $user_name;

    /**
     * lines
     *
     * Berisi array mengenai detail transaksi
     */
    public $lines;

    /**
     * subtotal, discount, total
     *
     * Ok, ada sebuah permasalah. Ini adalah discount
     * keseluruhan. Discount yang ada pada baris belum tersentuh
     * todo Ok kapan-kapan tambahin fungsi diskon pada barang
     * tapi 
     */
    public $subtotal;
    public $discount;
    public $total;

    /**
     * method adalah cara pembayaran
     *
     * Ada 4 kemungkinan, cash, debit_card, credit_card dan card
     * tapi belum terpakai oleh aplikasi ini. Jadi setiap transaksi
     * hanya digunakan tunai / cash
     *
     * payment & change
     * berfungsi untuk besarnya jumlah uang dan kembalian
     */
    public $method;
    public $payment;
    public $change;

    /**
     * Ok, ini untuk melihat apakah kwitansi atau receipt ini dianggap atau tidak.
     * Jika aktif dia dianggap dan dimasukan dalam hitungan, apabila tidak aktif
     * dia tidak dianggap
     */
    public $active;
    public $timestamp;

    /**
     * this is for table name, to make it less hardcode
     * it set in __construct();
     */
    private $table;
    private $table_line;

    /**
     *
     *
     */
    function __construct() 
    {
        parent::__construct();
        $this->load->database();
        $this->table = 'receipts';
        $this->table_line = 'receipts_line';
    }

    /**
     * set ini sama kaya new kali yah
     * dia membuat kuitansi baru
     *
     * @param int $kuitansi berhubung bermasalah dengan object,
     * jadinya gua membuat input dengan bentuk array.
     *
     */
    public function create ($kuitansi) 
    {
        /* check parameter */

        /* chek ketidak lengkapan */
        $checker = array (
            'customer_id' => 0,
            'user_id' => 1,
            'lines' => array(),
            'payment' => 0
        );

        $diff = array_diff_key($checker, $kuitansi);	
        foreach (array_keys($diff) as $index) {
            $errormsg = "Kuitansi yang dikirimkan tidak memiliki kolom $index";
            throw new Exception($errormsg);
        }

        /* chek kelebihan */
        $checker = array (
            'customer_id' => 0,
            'user_id' => 0,
            'lines' => array(),
            'method' => 0,
            'discount' => 0,
            'payment' => 0
        );
        $diff = array_diff_key($kuitansi, $checker);	
        foreach (array_keys($diff) as $index) {
            trigger_error('kuitansi not using ' . $index, E_USER_WARNING);	
            unset($kuitansi[$index]);
        }

        $lines = $kuitansi['lines'];
        unset($kuitansi['lines']);

        /* Check Parameter and drop useless data is DONE */

        /** 
         * todo Check payment is not enough..  
         * ok sebenernya ini dah dilakuin di controller tapi...
         * apkah harusnya dipindahin ke sini?  
         * atau ada 2 pengecekkan itu lebih baik?
         */

        /* Starting store in database */
        if ( $this->db->insert($this->table, $kuitansi) )  {
            $this->id = $this->db->insert_id();
            if($this->id == NULL) {
                throw new Exception ("ID kosong, ada kemungkinan data tidak masuk!");
            }


            foreach ($lines as $line) {
                $this->set_line($line['code'], $line['quantity']);	
            }

            /* Stroing in database DONE! */

            /* return */
            return $this->get();
        }
    }

    /**
     * get
     *
     * mendapatkan data kuitansi
     * todo pikirkan apakah lebih baik apabila menggunakan array?
     */
    public function get($receipt_id=NULL) 
    {
        if ($receipt_id != NULL) { 
            $this->id = $receipt_id; 
        }

        $this->db->select ('customer_id, discount, method, payment, receipts.timestamp, user_id, receipts.active, receipts.inactive_reason');
        $this->db->select ('customer.name');
        $this->db->select ('users.username');
        $this->db->where ('receipts.id', $this->id);
        $this->db->join ('customer', 'customer.id = receipts.customer_id', 'left');
        $this->db->join ('users', 'users.id = receipts.user_id', 'left');

        $query = $this->db->get($this->table);

        $row = $query->row();

        if ($row == NULL) {
            throw new Exception ("Tidak dapat mengambil kuitansi no ".$this->id);
        }

        $this->customer_id   = $row->customer_id;
        $this->customer_name = $row->name;

        $this->discount  = $row->discount;
        $this->method    = $row->method;
        $this->payment   = $row->payment;
        $this->timestamp = $row->timestamp;
        $this->user_id   = $row->user_id;
        $this->user_name = $row->username;
        $this->active    = $row->active;

        if ($row->active == FALSE) {
            $this->inactive_reason = $row->inactive_reason;
        }

        $this->lines = $this->get_lines();

        $this->subtotal = 0;

        foreach ($this->lines as $line) {
            $this->subtotal += $line->total;
        }

        // dan kayanya kode ini harus diungsikan
        $this->total  = $this->subtotal - $this->discount;
        $this->change = $this->payment - $this->total;


        return $this;
    }

    /**
     *
     * 
     * todo bikin error code untuk database
     */
    public function cancel($receipt_id=NULL, $user_id=NULL, $inactive_reason='NULL') {
        if ($receipt_id != NULL) { 
            $this->id = $receipt_id; 
        }

        $this->db->where ('id', $this->id);
        $this->db->set ('active', 0);
        $this->db->set ('user_id', $user_id);
        $this->db->set ('inactive_reason', $inactive_reason);
        $this->db->update ($this->table);

        return $this->get();
    }

    /**
     * set_line
     *
     * set satu baris
     */
    function set_line($code, $quantity, $receipt_id=NULL) 
    {
        if ($receipt_id != NULL) {
            $this->id = $receipt_id;
        }

        /* get  nama, harga, dari tabel produk */
        $this->load->model('product_model');

        $product = $this->product_model->get($code);

        $data = array (
            'receipt_id' => $this->id,
            'code'       => $code,
            'name'       => $product->name,
            'price'      => $product->price,
            'quantity'   => $quantity
        );

        $this->db->insert($this->table_line, $data);
    }

    /**
     * get_lines harusnya ini bisa lebih dipake.
     *
     */
    function get_lines($receipt_id=NULL) {
        if ($receipt_id != NULL) {
            $this->id = $receipt_id;
        }

        $this->db->where("receipt_id", $this->id);
        $query = $this->db->get($this->table_line);
        $return = $query->result();
        foreach ($return as $row) {
            $row->total = $row->quantity * $row->price;
        }

        return $return;
    }


    /**
     *
     * header
     * todo ubah dari hardcode jadi option
     * todo apakah lebih baik apabila dipindahin ke report?
     *
     "    PT. ENFYSOLUTION UTAMA    "
     "           & ALLSBON          "
     "Jl Surya Sarana Blok 2C No 15 "
     "Telp. 021-82561084            "
     "NPWP :                        "
     "------------------------------"
     "Tanggal : 03/03/2013 10:22:30 "
     "Kasir   : Dessy      No : 1234"
     "Pelangg : Tn Siapa            "
     "------------------------------"
     "Alat Potensial Pro~ 52,800,000"
     " 4001  1@ 52,800,000          "
     "Botol Harier            70,000"
     " 2001  2@     35,000          "
     "Subtotal           ==========="
     "                    52,870,000"
     "Diskon               5,287,000"
     "Total               47,563,000"
     "Tunai               47,600,000"
     "                              "
     "Kembalian               37,000"
     "        Terima Kasih          "
     "------------------------------"
     */
    function struk($kuitansi_id='') 
    {
        if ($kuitansi_id = '') {
            $kuitansi = $this;
        }
        $kuitansi = $this->get($kuitansi_id);

        $tanggal10 = date('d/m/Y', strtotime($kuitansi->timestamp));
        $waktu08 = date('H:i:s', strtotime($kuitansi->timestamp));
        $usname_10 = $this->align_left($kuitansi->user_name, 10);
        $noku = $this->align_right( (string) $kuitansi->id, 4);
        $patientname______20 = $this->align_left($kuitansi->customer_name, 20);

        $struk = "";
        $struk .= "Tanggal : $tanggal10 $waktu08 \n";
        $struk .= "Kasir   : $usname_10 No :$noku\n";
        $struk .= "Pelangg : $patientname______20\n";
        $struk .= "------------------------------\n";
        foreach ($kuitansi->lines as $line) {
            $nama_produk_____19 = $this->align_left($line->name,19);
            $hargat_10 = $this->align_right(number_format($line->total),10);
            $ko4 = $line->code;
            $j = $this->align_right(number_format($line->quantity),2);
            $hargap_10 = $this->align_right(number_format($line->price),10);

            $struk .= "$nama_produk_____19 $hargat_10\n";
            $struk .= " $ko4 $j@ $hargap_10          \n";
        }

        $subtotal0 = $this->align_right(number_format($kuitansi->subtotal),10);
        $diskon_10 = $this->align_right(number_format($kuitansi->discount),10);
        $total__10 = $this->align_right(number_format($kuitansi->total),10);
        $pembayara = $this->align_right(number_format($kuitansi->payment),10);
        $kembalian = $this->align_right(number_format($kuitansi->change),10);
        $jenis_pembayaran19 = $this->align_left($kuitansi->method,19);

        $struk .= "Subtotal           ===========\n";
        $struk .= "                    $subtotal0\n";
        $struk .= "Diskon              $diskon_10\n";
        $struk .= "Total               $total__10\n";
        $struk .= "$jenis_pembayaran19 $pembayara\n";
        $struk .= "                              \n";
        $struk .= "Kembalian           $kembalian\n";

        return $struk;
    }


    /**
     *
     */
    private function align_right($text, $maxlength) {
        $return = "";
        $e = $maxlength - strlen ($text);
        for ($i=0; $i<$e; $i++) { $return .= " "; }
        $return .= $text;
        return $return;
    }

    /**
     *
     */
    private function align_left($text, $maxlength) {
        $return = $text; 
        $e = $maxlength - strlen ($text);
        if ($e > 0) { for ($i=0; $i<$e; $i++) { $return .= " "; }
        } else { $return = substr($text,0,$maxlength-1) . "~"; }
        return $return;
    }
}
