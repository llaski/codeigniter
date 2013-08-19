<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Controller
	 *
	 * @access public
	 * @return view
	 */
	public function index()
	{
		$this->load->view('home');
	}
}