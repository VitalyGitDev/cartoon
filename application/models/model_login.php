<?php
    
class ModelLogin extends Model {
    public static $dbConn = null;

    public function __construct()
    {
        self::$dbConn = parent::get_connection();
    }

    public function check_user_aval($usr, $pss)
    {
        
        $q="select * from users where name = '".$usr."' and hash = '".md5($pss)."'";

        $res=self::$dbConn->query($q);

        if ( ! isset($res) )
        {
            $status=False;
        }
        else
        {
            $status=True;
        }
        
        unset($dbConn); 
        return $status;        
    }
}
    
?>