<?php
    
class ModelAdmin extends Model {
   //public $dbconector;
    public static $dbConn = null;

    public function __construct()
    {
        self::$dbConn = parent::get_connection();
    }

    public function check_user_aval($usr, $pss)
    {
        $dbCon = new dbConnector;
        $res=$dbCon->query('select * from users where user = '.$usr.'and md5pass = '.$pss.'');
        if ( ! isset($res) )
        {
            $status=False;
        }
        else
        {
            $status=True;
        }
        unset($dbCon);
        return $status;         
    }

    function get_users()
    {
        $res=self::$dbConn->query('select * from users');
        return $res;
    }

    function add_users($new_usr, $new_pass)
    {
        $x=md5($new_pass);
        $dbCon = new dbConnector;
        $q="insert into users (user, md5pass) values('".$new_usr."', '".$x."')";
        $res=$dbCon->query_add_del($q);
        unset($dbCon);
        return $res;
    }

    function del_users($del_usr)
    {

        $dbCon = new dbConnector;
        $q="delete from users where user='".$del_usr."'";
        $res=$dbCon->query_add_del($q);
        unset($dbCon);
        return $res;
    }
}
    
?>