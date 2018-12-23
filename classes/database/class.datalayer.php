<?php

namespace database;

/**
 * DataLayer
 * 
 * MySQLi Wrapper for commonly used SQL functions
 * 
 * DataLayer was originally written by Matt Zandstra and used as a demonstration
 * to OOP with PHP v4.x. It has been heavily modified by me to be compatible
 * with PHP v5.x/v7.x as well as to use the mysqli extension. The original API remains
 * largely unchanged, though the class has been modified to the point where it's no
 * longer the original.
 * 
 * Throughout the documentation, I have examples of the DataLayer API. You'll see
 * <code>$dl</code> quite often. In my examples, <code>$dl = $this->mysqli;</code>
 * 
 * The documentation is still under construction. Please pardon the dust.
 *
 * @category Database Access
 * @package DataLayer
 * @author  Paul Schudar
 * @copyright Copyright (c) 2015
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version 1.3.3
 * @internal Last Modified: 03.02.16
 * @internal Derivative of Matt Zandstra's 'DataLayer' class from Sam's Teach Yourself PHP in 24 Hours
 * */
class DataLayer implements dlConfig {

    /**
     * errors
     * 
     * Maintains a stack of error messages
     * @var array
     */
    private $errors = [];

    /**
     * mysqli
     * 
     * @var object - database resource
     */
    private $mysqli = null;

    /**
     * insert_id
     * 
     * Stores last inserted ID
     * @var int
     */
    private $insert_id;

    /**
     * debug
     * 
     * Flag to allow monitoring of the class behavior
     * @var bool
     */
    public $debug = false;

    /**
     * __construct
     * 
     * Checks for a database connection, if non-existent, one is created.
     * Error checking follows to ensure a connection is established, if not
     * the error is written to the browser via the setError() method. 
     * If all is well, the connection is returned in the $mysqli property.
     */
    public function __construct() {
        if (!isset($this->mysqli)) {
            $this->mysqli = new \mysqli(dlConfig::DBHOST, dlConfig::DBUSER, dlConfig::DBPASS, dlConfig::DBNAME);
        }
        if ($this->mysqli->connect_errno) {
            exit($this->setError($this->mysqli->connect_error));
            return false;
        }
        return true;
    }

    /**
     * getLastId()
     * 
     * This method was originally protected, however, it is
     * very useful for ajax calls in other scripts.
     * 
     * Simply returns the last inserted ID
     * @return int
     */
    public function getLastId() {
        return $this->mysqli->insert_id;
    }

    /**
     * getLastError()
     * 
     * Simply returns last error
     * @return string
     */
    protected function getLastError() {
        return $this->mysqli->error;
    }

    /**
     * getError()
     * 
     * @return int
     */
    public function getError() {
        return $this->errors[count($this->errors) - 1];
    }

    /**
     * setError()
     * 
     * Allows one to set a custom error message
     * @param $str
     */
    public function setError($str) {
        array_push($this->errors, $str);
    }

    /**
     * _query()
     * 
     * Checks a few basics but it's main focus is to pass a string
     * containing an SQL query to the mysqli_query method and given that 
     * all goes well, a result resource is returned. In failing, we add
     * another error message to the stack stored by setError().
     * @param $query
     * @return $result result resource
     */
    protected function _query($query) {
        if (!$this->mysqli) {
            $this->setError('DataLayer::_query ' . $this->getLastError());
            return false;
        }
        $result = $this->mysqli->query($query);
        if (!$result) {
            $this->setError('DataLayer::_query ' . $this->getLastError());
        }
        return $result;
    }

    /**
     * setQuery()
     * 
     * Calls _query and passes it an SQL string. This method is designed
     * for SQL statements that act upon a database. INSERT, DELETE or UPDATE  
     * statements for instance, and returns an integer representing the 
     * number of affected rows
     * <code>$dl->setQuery("DELETE FROM test_table");</code>
     * @param $query
     * @return int
     */
    protected function setQuery($query) {
        if (!$result = $this->_query($query)) {
            return false;
        }
        return $this->mysqli->affected_rows;
    }

    /**
     * getQuery()
     *  
     * Also calls _query and passes it an SQL string. This method is designed
     * primarily for SELECT statements and builds an array of the result sets and 
     * returns it to the calling code.
     * <code>$dl->getQuery("SELECT * from test_table");</code>
     * @param $query
     * @return array
     */
    protected function getQuery($query) {
        if (!$result = $this->_query($query)) {
            return false;
        }
        $ret = [];
        while ($row = $result->fetch_assoc()) {
            $ret[] = $row;
        }
        return $ret;
    }

    /**
     * select()
     * 
     * Bare minimum, the select method requires a $table name and $criteria to 
     * fetch from the database. $critera should be table fields. Optionally, it will
     * accept $condition and $sort parameters as well as a limit argument. The sort 
     * argument should be a string, for example: <code>"age DESC, name"</code>
     * 
     * The condition parameter can be a string or an associative array. A condition
     * passed as as associative array will be used to construct a WHERE clause with
     * the keys representing field names.
     * 
     * Where more complex conditions are required such as WHERE name=Paul AND age<20
     * the condition should be passed as a string containing the valid SQL fragment.
     * <code>$dl->select('*', 'users', "name=$name AND age > $ageVar);</code>
     * 
     * See the _makeWhereList method for more details on this functionality
     * 
     * Example: Great for dev / testing / proof of concept purposes
     * <code>$dl->select('*', 'users', ['user_login' => $user_login, 'user_status' => 1], '', '1');</code>
     * Resolves to: ( I chose not to add a sort parameter here, therefore the empty parameter '' )
     * <code>SELECT * FROM users WHERE user_login=$user_login AND user_status=1 LIMIT 1</code>
     * Example 2: Better than example 1 for production
     * <code>$dl->select('user_pass', 'users', ['user_login' => $user_login, 'user_status' => 1], '', '1');</code>
     * <code>SELECT user_pass FROM users WHERE user_login=$user_login AND user_status=1 LIMIT 1</code>
     * 
     * I would highly recommend against using '*' as criteria. Use table field names instead.
     * <code>$dl->select('item1, item2, item3', 'items', ['item1' => $item1var, 'item2' => $item2var], 'price ASC', '1');</code>
     * 
     * @param $criteria
     * @param $table
     * @param $condition
     * @param $sort
     * @param $limit
     * @return array
     */
    public function select($criteria, $table, $condition = '', $sort = '', $limit = '') {
        $query = "SELECT $criteria FROM $table";
        $query .= $this->_makeWhereList($condition);
        if ($sort != '') {
            $query .= " ORDER BY $sort";
        }
        if ($limit != '') {
            $query .= " LIMIT $limit";
        }
        $this->debug($query);
        return $this->getQuery($query, $this->setError('DataLayer::select ' . $this->getLastError()));
    }

    /**
     * insert()
     * 
     * Inserts given data within $add_array into $table. The API for insert
     * is very similar to select(), update() and delete(). 
     * insert() requires a  MySQL table name as well as an associative array of fields
     * to add to the row. The array keys should be the field name(s) to be altered
     * and the value(s) should be the new content for the field
     * It's my preference to define the array before using the insert statement.
     * Example:
     * Associative Array
     * <code>$new_record = ['item1' => $item1Var, 'item2' => $item2Var];</code>
     * Insert Method
     * <code>$dl->insert('items_table', $new_record) or die($dl->getError());</code>
     * Resolves to
     * <code>INSERT INTO items_table (item1, item2) VALUES ($item1Var, $item2Var);</code>
     * In my opinion, it keeps things easier to read. See the documentation on setQuery 
     * for details on the return type INT.
     * 
     * @param $table
     * @param $add_array
     * @return int
     */
    public function insert($table, $add_array) {
        $add_array = $this->_quote_vals($add_array);
        $keys = '(' . implode(array_keys($add_array), ', ') . ')';
        $values = 'values (' . implode(array_values($add_array), ', ') . ')';
        $query = "INSERT INTO $table $keys $values";
        $this->debug($query);
        return $this->setQuery($query);
    }

    /**
     * update()
     * 
     * update accepts 3 parameters. $table is the MySQL table we're updating.
     * $update_array should be an associative array with the key being the 
     * field name and the value being the updated value to write to the database.
     * $condition is optional, could be a string or associative array of keys/values
     * <code>$update_array = ['item_price' => $updatedPrice];</code>
     * <code>$dl->update('item_table', $update_array, ['id' => $idVar]) or die($dl->getError());</code>
     * 
     * @param $table
     * @param $update_array
     * @param $condition
     * @return int
     */
    public function update($table, $update_array, $condition = '') {
        $update_pairs = [];
        foreach ($update_array as $field => $val) {
            array_push($update_pairs, "$field=" . $this->_quote_val($val));
        }
        $query = "UPDATE $table SET ";
        $query .= implode(', ', $update_pairs);
        $query .= $this->_makeWhereList($condition);
        $this->debug($query);
        return $this->setQuery($query);
    }

    /**
     *  delete()
     * 
     * Requires a $table to remove data from and optionally accepts a condition. If no
     * condition is listed, all data will be deleted from the specified table.
     * DELETE statement
     * <code>$dl->delete('item_table');</code>
     * Resolves to:
     * <code>DELETE FROM item_table;</code>
     * 
     * @param $table
     * @param $condition
     * @return int || string
     */
    public function delete($table, $condition = '') {
        $query = "DELETE FROM $table";
        $query .= $this->_makeWhereList($condition);
        $this->debug($query);
        return $this->setQuery($query, $this->setError('DataLayer::delete ' . $this->getLastError()));
    }

    /**
     * debug()
     * 
     * Simply prints the $query for use in debugging the SQL fed into the above methods.
     * This is likely to break your site layout though it is indispensable while debugging
     * your code. Of course, swapping the true flag out for false disables this feature.
     * @param string $msg
     */
    protected function debug($msg) {
        if ($this->debug) {
            echo "<div id=\"debug\" class=\"sql\">$msg </div>";
        }
    }

    /**
     * _makeWhereList()
     * 
     * If no condition is required, an empty string is returned. If the condition is a string
     * it is simply tacked onto the string WHERE and returned. If the condition is an array 
     * however, the field name/value pairs are first constructed and stored in an array called 
     * $cond_pairs. The implode() function is then used to join the new array into a single string, 
     * the field name/value pairs are separated by the string âANDâ?.
     * @param $condition
     * @return string
     */
    protected function _makeWhereList($condition) {
        if (empty($condition)) {
            return "";
        }
        $retstr = ' WHERE ';
        if (is_array($condition)) {
            $cond_pairs = [];
            foreach ($condition as $field => $val) {
                array_push($cond_pairs, "$field=" . $this->_quote_val($val));
            }
            $retstr .= implode(' AND ', $cond_pairs);
        } elseif (is_string($condition) && !empty($condition)) {
            $retstr .= $condition;
        }
        return $retstr;
    }

    /**
     * _quote_val()
     * 
     * A utility method 
     * 
     * This is used to add backslashes to special characters (such as single quotes) 
     * within values. It also surrounds strings in quotes, though it leaves numbers alone.
     * It accepts a string and returns another with special characters backslashed. 
     * This is useful for surrounding strings sent to MySQL with single quotes.
     * 
     * Versions of DataLayer <= 1.0 used PHP's addslashes function.
     * Versions > 1.0 now use mysqli_real_escape_string instead.
     */
    protected function _quote_val($val) {
        if (is_null($val)) {
            return;
        } elseif (is_numeric($val)) {
            return $val;
        } else {
            return "'" . $this->mysqli->real_escape_string($val) . "'";
        }
    }

    protected function _quote_vals($array) {
        foreach ($array as $key => $val) {
            $ret[$key] = $this->_quote_val($val);
        }
        return $ret;
    }

    /**
     * __destruct()
     * 
     * Closes the DataLayer connection
     */
    public function __destruct() {
        try {
            $this->mysqli->close();
        } catch (Error $e) {
            $eText = "Query String: " . $_SERVER['QUERY_STRING'] . " || $e\r\n";
            $logFile = 'errorlog.txt';
            file_put_contents($logFile, $eText, FILE_APPEND | LOCK_EX);
        }
    }

}
