<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Remove_slug_test {

	var $CI;
		
	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->helper('project_helper');
	}
	
	public function removeSlug() {
		$str = "Ginger-rules";
		return removeSlug($str);
	}
	
}