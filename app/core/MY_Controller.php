<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * MY_Controller - extends CI_Controller of CodeIgniter Framework
 *
 * @package     CodeIgniter
 * @subpackage  Controllers
 * @category    Core Files
 * @author      Larry Laski [llaski@resolute.com]
 * @copyright   2013 Resolute Digital [http://resolute.com]
 */
class MY_Controller extends CI_Controller
{
    /**
     * Constructor
     *
     * @access  public
     * @return  void
     */
    function __construct()
    {
        parent::__construct();
    }
}

/**
 * Terminal Controller
 *
 * @author    Larry Laski [llaski@resolute.com]
 * @copyright 2013 Resolute Digital [http://resolute.com]
 * @version   2.0
 */
class Terminal_Controller extends MY_Controller
{
    /**
     * Constructor
     *
     * @access  public
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();

        //Non-accessible in browser
        if (array_key_exists('HTTP_HOST', $_SERVER))
            show_404();

        ini_set('memory_limit', '2048M'); //2GB
        set_time_limit(0);
    }

    /**
     * Run Cron w/ output buffer
     *
     * @access  protected
     * @return  void
     */
    protected function run()
    {
        ob_start();
    }

    /**
     * Clear Buffer
     *
     * @access  protected
     * @return  void
     */
    protected function _buffer()
    {
        if (ob_get_contents() != false) {
            ob_flush();
        }
        flush();
    }

    /**
     * Connection Check for DB
     *
     * @access  protected
     * @return  void
     */
    protected function reconnect()
    {
        if (mysqli_ping($this->db->conn_id) === FALSE)
            $this->db->reconnect();
    }

    /**
     * Helper Function to execute array of sql statements
     *
     * @access  protected
     * @param   array $sql
     * @return  void
     */
    protected function _execute_sql($sql)
    {
        foreach ($sql as $statement) {
            $this->_announce('Executing: ' . $statement);
            $this->_buffer();
            $this->db->query($statement);
        }
    }
}