<?php
/**
 * DataTables Library for CodeIgniter
 *
 * @author Egon Firman <egon.firman@gmail.com>
 *
 */

Class Datatable
{
    /**
     * variable
     */
    public $table;

    protected $fields;

    protected $settings;

    protected $searchableFields;

    protected $url;

    protected $ci;

    /**
     *
     * todo use Dependency Injection to add another database driver
     */
    public function __construct($table_name = null)
    {
        $this->table = $table_name;

        $this->ci =& get_instance();

        $this->db = $this->ci->db;
    }

    /**
     * Fields Manipulation (get)
     *
     */
    public function getFields()
    {
        if (!$this->fields) {
            $this->fields = $this->db->list_fields($this->table);
        }
        return $this->fields;
    }

    /**
     * Fields Manipulation (set)
     *
     */
    public function setFields($fields = array())
    {
        if (is_string($fields)) {
            $fields = array_map('trim', explode(',', $fields));
        }

        // error checking
        $orig_fields = $this->getFields();

        // check all fields are exist
        if (array_intersect($fields, $orig_fields) == $fields) {
            $this->fields = $fields;
        } else {
            $a = implode(',', array_intersect($orig_fields, $fields));
            throw new Exception('One or more Columns you not exist in table. System only accept '.$a);
        }
    }

    /**
     * Fields Manipulation (remove)
     *
     */
    public function removeFields($del_fields = array())
    {
        if (is_string($del_fields)) {
            $del_fields = array_map('trim', explode(',', $del_fields));
        }

        if (!$this->fields) {
            $this->getFields();
        }

        foreach ($del_fields as $del_field) {
            if (($key = array_search($del_fields, $this->fields)) !== false) {
                unset($this->fields[$key]);
            }
        }
    }

    public function searchableFields($fields)
    {
        // todo error checking
        $this->searchableFields = $fields;
    }

    /**
     * getList
     *
     * process $_GET variables that send from datatables and processit
     *
     * @return list in JSON format
     */
    public function getList ()
    {
		//limit result for pagination
        return $this->queryBuilder()
            ->limit($this->iDisplayLength(), $this->iDisplayStart())
            ->get()
            ->result();
    }

    public function countList()
    {
        return $this->queryBuilder()->count_all_results();
    }

    public function countAll()
    {
		return $this->db->count_all($this->table);
    }

    private function queryBuilder()
    {
        // list fields for sorting
        $fields = $this->getFields();

		$this->db->select(implode(',', $fields));
		$this->db->from($this->table);
		
        // if search are needed
        $search = $this->sSearch();
        if ($search) {
            // if not yet set then find all in fields
            if (!$this->searchableFields) {
                $this->searchableFields = $this->getFields();
            }

            // start to search first column
            $this->db->like($this->searchableFields[0], $search);

            // search other column
            for ($i = 1; $i < sizeof($this->searchableFields); $i++) {
                $this->db->or_like($this->searchableFields[$i], $search);
            }
        }
		
		// order/sort by column ...
		$this->db->order_by($this->iSortCol(), $this->sSortDir());

		//return the result
		return $this->db;
    }

    private function sSearch() 
    {
        $search = NULL;
        if (isset($_GET['sSearch']) && $_GET['sSearch']) {
            $search = strip_tags($_GET['sSearch']);
        }
        return $search;
    }

    private function iDisplayStart() 
    {
        $start = 0;
        if (isset($_GET['iDisplayStart'])) {
            $start = intval($_GET['iDisplayStart']);

            if ($start < 0)
                $start = 0;
        }

        return $start;
    }

    private function iDisplayLength() 
    {
        $offset = 10;
        if (isset($_GET['iDisplayLength'])) {
            $offset = intval($_GET['iDisplayLength']);
            if ($offset < 5 || $offset > 500) {
                $offset = 10;
            }
        }
        return $offset;
    }

    private function iSortCol() {
        $fields = $this->getFields();
        $col = $fields[0];

        if (isset($_GET['iSortCol_0'])) {
            $col = $fields[intval($_GET['iSortCol_0'])];

        }
        return $col;
    }

    private function sSortDir() 
    {
        $sort_dir = "asc";
        if (isset($_GET['sSortDir_0'])) {
            if ($_GET['sSortDir_0'] != "asc" ) {
                $sort_dir = "desc";
            }
        }
        return $sort_dir;
    }

    /**
     * Hmmm ok, the settings in dataTable is quite robust
     * So I still don't know how the best to put it right.
     * 
     * todo think again
     */
    public function getSettings()
    {
        if (!$this->settings) {
            $settings = array (
                'bProcessing' => true,
                'bServerSide' => true,
                'sAjaxSource' => $this->sAjaxSource,
                'aoColumns'   => array(),
            );

            // list fields
            $fields = $this->getFields();

            foreach ($fields as $field) {
                $settings['aoColumns'][] = array (
                    'mDataProp' => $field,
                    'sDefaultContent' => '',
                    'sType' => 'natural',
                );
            }

            $this->settings = $settings;
        }
        return json_encode($this->settings);
    }

    public function setSettings($settings = array())
    {
        if (!$this->settings) {
            $this->get_settings();
        }
        array_merge_recursive($this->settings, $settings);
    }


    /**
     * The Output
     *
     *
     */
    public function getHtmlTable($tableClass = 'dTable')
    {
        $fields = $this->getFields();

        $dom = new DOMDocument();

        $table = $dom->createElement('table');
        $thead = $dom->createElement('thead');
        $tbody = $dom->createElement('tbody');
        $thead_tr = $dom->createElement('tr');
        $tbody_tr = $dom->createElement('tr');

        $dom->appendChild($table);

        $table->appendChild($thead);
        $table->appendChild($tbody);

        // adding class to table
        $table->setAttribute('class', $tableClass);

        $thead->appendChild($thead_tr);
        $tbody->appendChild($tbody_tr);

        foreach ($fields as $field) {
            $th = $dom->createElement('th');
            $th->appendChild(new DOMText($field));
            $thead_tr->appendChild($th);

            $td = $dom->createElement('td');
            $tbody_tr->appendChild($td);
        }

        return $table->ownerDocument->saveXML($table);
    }

    public function getJavascript()
    {
        return '$(".dTable").dataTable('.$this->getSettings().');';
    }

    public function serverProcess()
    {
        return json_encode(array(
            'sEcho' => intval($_GET['sEcho']),
            'iTotalRecords' => $this->countAll(),
            'iTotalDisplayRecords' => $this->countList(),
            'aaData' => $this->getList(),
        ));
    }
}
