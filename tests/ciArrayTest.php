<?php
if ( basename(__FILE__) == basename($_SERVER['PHP_SELF']) ) { die("This file cannot be loaded directly."); }

class ciBasicTest extends CI_TestCase {
	
	
	function setUp() {
		parent::setUp();
		// you'll want to add class members here. You can access the CI instance
		// object using $this->CI. You can test models, libraries, or helpers here.
		$this->CI->load->library('unit-tests/array_test');
	}

	
	public function testMultiExplode() {
		$this->assertNotEmpty( $this->CI->array_test->doMultiExplode() );
	}
	
}