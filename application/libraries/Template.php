<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template {
	private $_data = array();
	private $_ci;
	
	function __construct($config = array()) {
		$this->_ci =& get_instance();
	}
	// --------------------------------------------------------------------
	
	/**
	 * Magic Get function to get data
	 *
	 * @access	public
	 * @param	  string
	 * @return	mixed
	 */
	public function __get($name) {
		return isset($this->_data[$name]) ? $this->_data[$name] : NULL;
	}

	// --------------------------------------------------------------------

	/**
	 * Magic Set function to set data
	 *
	 * @access	public
	 * @param	  string
	 * @return	mixed
	 */
	public function __set($name, $value) {
		$this->_data[$name] = $value;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Set data using a chainable metod. Provide two strings or an array of data.
	 *
	 * @access	public
	 * @param	  string
	 * @return	mixed
	 */
	public function set($name, $value = NULL) {
        $this->_ci->load->vars($name, $value);
        
		return $this;
	}
	
	// --------------------------------------------------------------------

	public function render_page($template = '', $view = '' , $return = FALSE) {
		
		if($view) {
			$this->_data['content_for_layout'] = $this->_ci->load->view($view, $this->_data, TRUE);
		} else {
			$this->_data['content_for_layout'] = '';
		}
		
		$this->_ci->load->view($template, $this->_data, $return);
		
		return $this;
	}
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */
/* source: http://jeromejaglale.com/doc/php/codeigniter_template */
