<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * MySQLI Driver Extension for Codeigniter
 *
 * @package     CodeIgniter
 * @subpackage  Models
 * @category    Models
 * @author      Larry Laski [llaski@resolute.com]
 * @copyright   2013 Resolute Digital [http://resolute.com]
 * @version     2.0
 *
 * Credit to Simon Emms <simon@simonemms.com> for explanation of how to extend db drivers
 */

class MY_DB_mysqli_driver extends CI_DB_mysqli_driver {

    /**
     * Constructor
     *
     * @access  public
     * @return  void
     */
    final public function __construct($params)
    {
        parent::__construct($params);
    }

    /**
     * Set Prepared Query
     *
     * @access  public
     * @param   string $sql - sql query
     * @return  object
     */
    public function set_prepared_statement($sql)
    {
    	$this->prepared_statement = mysqli_prepare($this->conn_id, $sql);
        return $this->prepared_statement;
    }

    /**
     * Run Prepared Query
     *
     * @access  public
     * @param   string $param_types - concatenated strings of param types to be passed in
     *                              - i = integer
     *                              - d = double
     *                              - s = string
     *                              - b = blob to be sent in packets
     *
     * @param   string $params - array of values corresponding to params to be passed into the prepared statement
     * @param   obj $prepared_statement - in case of multiple prepared statements can pass in the one you need to reference
     * @return  void
     */
    public function run_prepared_query($param_types, $params = array(), $prepared_statement = NULL)
    {
        if ($prepared_statement) $this->prepared_statement = $prepared_statement;

    	if ($this->prepared_statement)
    	{
    		$parameters = array($this->prepared_statement);
    		$results = array();

    		call_user_func_array('mysqli_stmt_bind_param', array_merge($parameters, $this->referenced_values(array_merge(array($param_types), $params))));
	    	mysqli_stmt_execute($this->prepared_statement);

	    	//Dynamic Results
	    	$meta = mysqli_stmt_result_metadata($this->prepared_statement);

	    	if ($meta)
	    	{
			   	while ( $field = mysqli_fetch_field($meta) )
			   	{
			     	$parameters[] = &$row[$field->name]; //Needs to be by reference for the way bind result is called
			   	}

			   	call_user_func_array('mysqli_stmt_bind_result', $parameters);

			   	//Get Results
		    	while ( mysqli_stmt_fetch($this->prepared_statement) )
		    	{
			      	$temp = array();
				    foreach( $row as $key => $val ) {
				        $temp[$key] = $val;
				    }
			      	$results[] = $temp;
			   	}
			}

		   	return $results;
    	}
    	else
    		return FALSE;
    }

    /**
     * Returns an array of referenced values (necessary for call_user_func_array)
     *
     * @access  public
     * @param   array $array
     * @return  void
     */
    private function referenced_values($array)
    {
        $refs = array();
        foreach($array as $key => $value)
        $refs[$key] = &$array[$key];
        return $refs;
    }

    /**
     * Creates Statement for MySQL - INSERT ... ON DUPLICATE KEY UPDATE ...
     *
     * @access  public
     * @param   string $table
     * @param   array $fields
     * @param   array $set
     * @return  void
     */
    public function on_duplicate_update($table = '', $fields = NULL, $set = NULL)
    {
        if ( ! is_null($set))
        {
            $this->set($set);
        }

        if (count($this->ar_set) == 0)
        {
            if ($this->db_debug)
            {
                return $this->display_error('db_must_use_set');
            }
            return FALSE;
        }

        if ($table == '')
        {
            if ( ! isset($this->ar_from[0]))
            {
                if ($this->db_debug)
                {
                    return $this->display_error('db_must_set_table');
                }
                return FALSE;
            }

            $table = $this->ar_from[0];
        }

        $sql = $this->_insert_on_duplicate_update($this->_protect_identifiers($table, TRUE, NULL, FALSE), $fields, $this->ar_set);
        $this->_reset_write();
        return $this->query($sql);
    }

    /**
     * Creates Statement for MySQL - INSERT ... ON DUPLICATE KEY UPDATE ...
     *
     * @access  public
     * @param   string $table
     * @param   array $array
     * @param   array $values
     * @return  void
     */
    private function _insert_on_duplicate_update($table, $update, $values)
    {
        foreach($values as $key=>$value)
        {
            $insert_fields[] = $key.'='.$value;
        }

        foreach($update as $key=>$name)
        {
            $update_fields[] = $name.'='.$values['`'.$name.'`'];
        }

        return "INSERT INTO ".$table." SET ".implode(',', $insert_fields)." ON DUPLICATE KEY UPDATE ".implode(',', $update_fields);
    }

    /**
     * Insert Batch
     * - Compiles batch insert strings and runs the queries - replaces insert with insert ignore so it will ignore rows with duplicate keys
     *
     * @param   string $table
     * @param   array $set
     * @return  boolean
     */
    public function insert_ignore_batch($table = '', $set = NULL)
    {
        if ( ! is_null($set))
        {
            $this->set_insert_batch($set);
        }

        if (count($this->ar_set) == 0)
        {
            if ($this->db_debug)
            {
                //No valid data array.  Folds in cases where keys and values did not match up
                return $this->display_error('db_must_use_set');
            }
            return FALSE;
        }

        if ($table == '')
        {
            if ( ! isset($this->ar_from[0]))
            {
                if ($this->db_debug)
                {
                    return $this->display_error('db_must_set_table');
                }
                return FALSE;
            }

            $table = $this->ar_from[0];
        }

        // Batch this baby
        for ($i = 0, $total = count($this->ar_set); $i < $total; $i = $i + 100)
        {
            $sql = $this->_insert_ignore_batch($this->_protect_identifiers($table, TRUE, NULL, FALSE), $this->ar_keys, array_slice($this->ar_set, $i, 100));

            $this->query($sql);
        }

        $this->_reset_write();


        return TRUE;
    }

    /**
     * Insert Batch
     * - Compiles batch insert strings and runs the queries - replaces insert with insert ignore so it will ignore rows with duplicate keys
     *
     * @param   string $table
     * @param   array $keys
     * @param   array $values
     * @return  string
     */
    private function _insert_ignore_batch($table, $keys, $values)
    {
        return "INSERT IGNORE INTO ".$table." (".implode(', ', $keys).") VALUES ".implode(', ', $values);
    }

}