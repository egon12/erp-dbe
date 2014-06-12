<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class Produk_model, rewriten...
 *
 * Ok, gua coba untu mengurangi sebanyak-banyaknya kode yang tidak dibutuhkan
 * Jadi saat ini gua menghapus kode-kode yang belum berguna
 *
 * untuk databasenya, kita masih bisa menggunakan grocery_CRUD, atau 
 * mungkin crud yang akan kita buat
 *
 * Jadi untuk saat ini hanya fokus di get and search saja
 *
 *
 */
class Product_model extends CI_Model{

    public $table;

    /*
     * exist for nothing
     */
    function __construct()
    {
        parent::__construct();
        $this->load->database();

        $this->table = 'product';
    }

    /**
     * get 
     * dapetin data produk
     * @param in $id id produk yang diinginkan
     * @return data produk dalam bentuk object
     *
     */
    function get($code)
    {
        $this->db->where('code', $code);
        $query = $this->db->get($this->table, 1);

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            throw new Exception ("Product with code '$code' not exist");
        }
    }

    /**
     *
     */
    function get_all() {
        $query = $this->db->get($this->table);
        return $query->result();
    }

    /**
     *
     */
    function get_price($code) {
        $b = $this->get($code);
        return $b->price;
    }

    /**
     * search
     *
     * mencari pasien produk dari nama atau keteranganny
     *
     * @param $query nama, atau keterangan
     * @return array berisi object data produk 
     *
     */
    function search($query) {
        $query = strtolower($query);
        $this->db->like ('LOWER(name)', $query);
        $this->db->or_like ('LOWER(description)', $query);
        $query = $this->db->get($this->table);
        return $query->result();
    }

    /**
     * search_by_category (use number)
     *
     */
    function search_by_category($query) {
        $query = (int) $query;
        if ($query > 999) {
            $range = 1;
        } else if ($query > 99) {
            $range = 10;
        } else if ($query > 9) {
            $range = 100;
        } else {
            $range = 1000;
        }

        $from = (string) ($query * $range);
        $to  = (string) ($query * $range + $range);

        $this->db->where ('code >=', $from);
        $this->db->where ('code <', $to);
        $query = $this->db->get($this->table);
        return $query->result();
    }

    /**
     * search and get
     *
     */
    function search_and_get($query) {
        if (preg_match("/^\d{4}$/",$query) ) {
            return $this->get($query);
        } else {
            $hasil = $this->search($query);
            if ( count($hasil) > 1 ) 
                throw new Exception ( "There are more then one '$query', choose one" );
            if ( count($hasil) == 0 ) 
                throw new Exception ( "There are no '$query'" );
            return $this->get($hasil[0]->code);
        }
    }
}

