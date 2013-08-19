<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Array_test {

	var $CI;
		
	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->helper('project_helper');
	}
	
	public function doMultiExplode() {
		$str = "My, dog Brinkley : is cool";
		return multiexplode(array(',' , ':'), $str);
	}
	
}