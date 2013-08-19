<?php
if ( basename(__FILE__) == basename($_SERVER['PHP_SELF']) ) { die("This file cannot be loaded directly."); }

class ciRemoveSlugTest extends CI_TestCase {
	
	
	function setUp() {
		parent::setUp();
		// you'll want to add class members here. You can access the CI instance
		// object using $this->CI. You can test models, libraries, or helpers here.
		$this->CI->load->library('unit-tests/remove_slug_test');
	}

	
	public function testRemoveSlug() {
		$this->assertEquals('Ginger rules Chris', $this->CI->remove_slug_test->removeSlug() );
	}
	
	/**
     * @depends testRemoveSlug
     */
    public function testRemoveSlug2()
    {
        $this->assertEquals('Ginger rules', $this->CI->remove_slug_test->removeSlug() );
    }
	
}