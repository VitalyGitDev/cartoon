<?php

class ModelUser extends Model
  {
    public static $dbConn = null;

    public function __construct()
    {
        self::$dbConn = parent::get_connection();
    }
    
    public function get_categories()
    {
        $q="select * from categories";
        $res=self::$dbConn->query($q)->fetchAll();
        
        return $res;
    }

    public function get_cat_products($cat)
    {
        $q="select * from goods where cid=".$cat['id'];
        $res=self::$dbConn->query($q)->fetchAll();
        
        return $res;

    }

        public function get_products()
    {
        $q="select * from goods ";
        $res=self::$dbConn->query($q)->fetchAll();
        
        return $res;

    }

    function get_max_mes_id_list()
    {
        $dbCon = new dbConnector;
        $q="select max(m1.id) as max from messages as m1 where (select count(m3.title) from messages as m3 where m3.title=m1.title)>1 group by title";
        $res=$dbCon->query($q);
        foreach ($res as $i => $v) {
            $res_max[]=$v['max'];
        }
        $q="select max(m1.id) as max from messages as m1 where (select count(m3.title) from messages as m3 where m3.title=m1.title)=1 group by title";
        $res=$dbCon->query($q);
        foreach ($res as $i => $v) {
            $res_max[]=$v['max'];
        }
        //file_put_contents("/var/www/stat.vm.ua/cis/receiver_model.log"," - in model - Get Ax Procedure <pre>".print_r($res_max,1), FILE_APPEND);
        return $res_max;
    }
    
    function get_ax_procedure($name, $mess_id)
    {
        $dbCon = new dbConnector;
        $q="select * from ax_functions where name='".$name."'";
        $q2="select ax_params from messages where id=".$mess_id;
        $q3="select ax_function_id from messages where id=".$mess_id;
        $res=$dbCon->query($q);
        $func=$res[0];
        $params=$dbCon->query($q2);
        
        $f_numb_list=$dbCon->query($q3);
        $func_numb=explode(',',$f_numb_list[0]['ax_function_id']);
        foreach ($func_numb as $k=>$v){
            if ($v==$func['id']) { $par_id=$k; }        
        }
        
        if (!empty($params)) {
            foreach($params[0] as $n=>$v) {
                 $temp=unserialize($v);
                 $func['params']=$temp[$par_id];
                 //file_put_contents("/var/www/stat.vm.ua/cis/receiver_model.log"," - in model - Get Ax Procedure <pre>".print_r($func,1));
                 }
            }
        return $func;
    }

    function get_active_messages_func($mes)
    {
        $dbCon = new dbConnector;
        $res1=$dbCon->query("select ax_function_id from messages where id = ".$mes);
        
        if (!empty($res1) && ($res1[0]['ax_function_id'])) {
          
          $q="select * from ax_functions where id in (".$res1[0]['ax_function_id'].")";
          $res=$dbCon->query($q);
          //file_put_contents("/var/www/stat.vm.ua/cis/model_query.log"," - in model - <pre>"."select * from ax_functions where id in (".$res1[0]['ax_function_id'].")"); 
        } else $res='';
        unset($dbCon);
        return $res;
    }
    
    function get_active_messages($res_id)
    {           
        $dbCon = new dbConnector;
        $res=$dbCon->query("select m.*, r.name as receiver from messages as m join receivers as r on m.receiver_id=r.id where m.res_id=".$res_id." and (m.active=1 or m.active=2) ORDER BY m.title");
        if (!empty($res)) {
         foreach($res as $i => $v) {
           $tmp=$dbCon->query_add_del("update messages set last = 0 where id=".$v['id']); 
           }
        }
        unset($dbCon);
        return $res;
    }
    
    function get_new_messages($res_id)
    {           
        $dbCon = new dbConnector;
        $res=$dbCon->query("select m.*, r.name as receiver from messages as m join receivers as r on m.receiver_id=r.id where m.res_id=".$res_id." and m.active=1 and (select count(*) from messages where title=m.title)=1 and last=1 ORDER BY title");
        if (!empty($res)) {
        foreach($res as $i => $v) {
           $tmp=$dbCon->query_add_del("update messages set last = 0 where id=".$v['id']); 
        }
        }
        unset($dbCon);
        if (!empty($res)) {
            return $res;
        }
    }
    
    function get_new_add_messages($res_id)
    {           
        $dbCon = new dbConnector;
        $res=$dbCon->query("select m.*, r.name from messages as m join receivers as r on m.receiver_id=r.id where m.res_id=".$res_id." and (m.active=1 or m.active=2) and (select count(*) from messages where title=m.title)>1 and last=1 ORDER BY title");
        if (!empty($res)) {
        foreach($res as $i => $v) {
           $tmp=$dbCon->query_add_del("update messages set last = 0 where id=".$v['id']); 
        }
        }
        unset($dbCon);
        return $res;
    }
    
    function get_block_add_messages($res_id, $title)
    {           
        $dbCon = new dbConnector;
        $res=$dbCon->query("select m.*, r.name from messages as m join receivers as r on m.receiver_id=r.id where m.res_id=".$res_id." and m.title='".$title."' ORDER BY created_at");
        if (!empty($res)) {
        foreach($res as $i => $v) {
           $tmp=$dbCon->query_add_del("update messages set last = 0 where id=".$v['id']); 
        }
        }
        //file_put_contents("/var/www/stat.vm.ua/cis/receiver.log"," - in model - <pre>".print_r($res,1));	
        //$res2=$dbCon->query("update messages set last=0 where res_id=".$res_id." and active=1 and (select count(*) from messages as m where title=m.title)=1 and last=1 ORDER BY title");
        unset($dbCon);
        if (!empty($res)) {
            return $res;
        }
    }
    
     function set_not_active($mes_title)
    {
        
        $dbCon = new dbConnector;
        $q="update messages set active=0 where title='".$mes_title."'";
        $res=$dbCon->query_add_del($q);
        return $res;
        unset($dbCon);
    }
    
    function set_readed($mes_title)
    {
        $dbCon = new dbConnector;
        $q="update messages set active=2 where title='".$mes_title."'";
        //file_put_contents("/var/www/stat.vm.ua/cis/receiver.log"," - in model - ".print_r($q,1));	
        $res=$dbCon->query_add_del($q);
        return $res;
        unset($dbCon);
    }
    
    function get_allowed_resourses($u_name)
    {           
        $dbCon = new dbConnector;
        $q="select allowed_res from users where user='".$u_name."'";
        $allow=$dbCon->query($q);
        $q="select * from resourses";
        if ($allow[0]['allowed_res'] != '')
        {
            $allow_res=explode(';',$allow[0]['allowed_res']);
            foreach ($allow_res as $key=>$val) {
                if ($key == 0) { $q.=" where id=".$val; } else { $q.=" or id=".$val; }
                $res_name=$dbCon->query("select name from resourses where id=".$val);
                $qty=$dbCon->query("select count(*) as count from messages where res_id=".$val." and active=1");
                $mes_qty[$res_name[0]['name']]=$qty[0]['count'];
            }
            $res=$dbCon->query($q);
        } 
        else $res = array();
        
        if (isset($res))
        {
            foreach ($res as $key=>$value) {
                foreach ($mes_qty as $k=>$val) {
                    if ( $k == $value['name'] ) { $res[$key]['active_qty'] = $val; }
                }
            }
            //echo "<pre> "; print_r($res);
        }
       unset($dbCon); 
       return $res;
    }
     
 }
    
?>