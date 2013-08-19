<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Project Helper Functions
 *
 * @package     CodeIgniter
 * @subpackage  Helpers
 * @category    Dashboard Helper Functions
 * @author      Larry Laski [llaski@resolute.com], Rusty Cage [rcage@resolute.com],
 * @copyright   2013 Resolute Digital [http://resolute.com]
 */

/**
 * Print's out last query
 *
 * @access  public
 * @param   boolean $die
 * @return  void
 */
function query($die = true)
{
    $CI = & get_instance();
    echo '<pre>';
    print_r($CI->db->last_query());
    echo '</pre>';
    if($die) die('End of Query');
}

/**
 * Print Out Function
 *
 * @access  public
 * @param   string $data
 * @param   boolean $die
 * @return  void
 */
function pp($data, $die = FALSE)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    if($die) die('End.');
}

/**
 * Print Out Function
 *
 * @access  public
 * @param   string $data
 * @param   boolean $die
 * @return  void
 */
function announce($msg)
{
    print_r($msg);
    echo '
';
}

/**
 * Clear Directory
 *
 * @access  public
 * @param   string $category
 * @return  string
 */
function clearDirectories($dirs = array())
{
    foreach ($dirs as $dir)
    {
        $prev_files = glob($dir);
        foreach ($prev_files as $file)
            if (is_file($file)) unlink($file);
    }
}

/**
 * Check if server contains an ajax request
 *
 * @access  public
 * @return  boolean
 */
function ajaxRequestCheck()
{
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        return TRUE;
    else
        return FALSE;
}