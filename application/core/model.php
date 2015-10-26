<?php
class Model {

    protected static function __conctruct() 
    {
        //self::$dbConn = dbConnector::get_instance();
        //echo "<pre>db_con - ".print_r(self::$dbConn, 1 )."</pre>";
    }

    protected function get_connection()
    {
        return dbConnector::get_instance();
    }
    
}

?>