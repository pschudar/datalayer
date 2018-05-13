<?php

/**
 * DataLayer Interface
 * 
 * This is designed as a basic interface for use with the DataLayer class.
 * One thing most databases have in common is the need for the constants
 * outlined below. Configure as necessary.
 * 
 * The most important methods to use when working with a database are 
 * related to error reporting as well as CRUD functions. So they've 
 * been flagged for use in the interface as well.
 *
 * @category Database Access
 * @package DataLayer
 * @author  Paul Schudar <p.schudar@gmail.com>
 * @copyright Copyright (c) 2015
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version 1.3
 * @internal Last Modified: 07.14.15 
 */
interface dlConfig {

    const DBHOST = 'localhost';     # your database host name
    const DBUSER = 'user';         # your database user name
    const DBPASS = '';            # your database user's pass
    const DBNAME = 'vs_vlot';    # your database

    public function getError();

    public function setError($str);

    public function select($criteria, $table, $condition = '', $sort = '', $limit = '');

    public function insert($table, $add_array);

    public function update($table, $update_array, $condition = '');

    public function delete($table, $condition = '');
}
