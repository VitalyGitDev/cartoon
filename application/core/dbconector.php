<?php
/**
* Designed with Singletone pattern.
*/
class dbConnector {

    private static $db_inst = null;
    private static $dbh = null;

    protected function __construct()
    {
       
        require_once(__DIR__ . '/../config/dbconfig.php');
        self::$dbh = new PDO('mysql:host='.$db_config["host"].';dbname='.$db_config["database"], $db_config["user"], $db_config["pswd"]);
    }
    
    protected function __clone()
    {
    
    }

    public static function get_instance()
    {
        if ( ! self::$db_inst)
        {
            self::$db_inst = new dbConnector();
            return self::$db_inst;
        }
        else 
        {
            return self::$db_inst;
        }
    }

    public function query_add_del($q_add)
    {
        $result = self::$dbh->query($q_add);
        if ( isset($result) )
            return True;
    }
    
    public function query($q_string)
    {
        $result = self::$dbh->query($q_string) or die("Invalid query: " . mysql_error());
        if ( (isset($result))&&($result!='') )
        {
            /*
            $resMass = array();
            while ( $row = mysql_fetch_assoc($result) )
            {
                $resMass[]=$row;
            }
            */
            return $result;
        }
    }
    
}
?>