<?php
    
class ModelStore extends Model {
    public static $dbConn = null;

    public function __construct()
    {
        self::$dbConn = parent::get_connection();
    }


/*
*   URGENTLY make image present checking and validate
*   for Products and for Categories
*/
    public function add_category($params)
    {
        
        $q="INSERT INTO categories (name, description, image) VALUES('".$params['name']."', '".$params['descr']."', '".$params['image']['name']."')";

        $res=self::$dbConn->query($q)->fetch();

        if ( ! isset($res) )
        {
            $status=False;
        }
        else
        {
            $status=True;
        }
        
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/images/categories/'.$params['image']['name'], base64_decode($params['image']['body']));
        return $status;        
    }

    public function get_categories()
    {
        $q="select * from categories";
        $res=self::$dbConn->query($q)->fetchAll();
        
        return $res;
    }


    public function add_product($params)
    {
        $q="INSERT INTO goods (name, description, image, cid) VALUES('".$params['name']."', '".$params['descr']."', '".$params['image']['name']."', ".($params['cat']*1).")";
//die($q);
        $res=self::$dbConn->query($q)->fetch();

        if ( ! isset($res) )
        {
            $status=False;
        }
        else
        {
            $status=True;
        }
        
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/images/products/'.$params['image']['name'], base64_decode($params['image']['body']));

        return $status;         
    }

    public function del_product($params)
    {
        $q="DELETE FROM goods WHERE id=".$params['id']." AND cid=".$params['cat_id'];
//die($q);
        $res=self::$dbConn->query($q)->fetch();

        if ( ! isset($res) )
        {
            $status=False;
        }
        else
        {
            $status=True;
        }
        
        return $status;         
    }    

    public function del_category($params)
    {
        $q = "DELETE FROM categories WHERE id=".$params['id'];
        $res = self::$dbConn->query($q)->fetch();
        $status_delete = (isset($res)) ? True : False;

        $q = "UPDATE goods SET cid=0 where cid=".$params['id'];
        $res = self::$dbConn->query($q)->fetch();
        $status_update = (isset($res)) ? True : False;        
        
        return ($status_delete && $status_update);         
    }   

    public function get_product_info($id)
    {
        $q = "SELECT * FROM goods WHERE id=".$id;
        $res = self::$dbConn->query($q)->fetch();

        return $res;
    }  

    public function get_cat_info($id)
    {
        $q = "SELECT * FROM categories WHERE id=".$id;
        $res = self::$dbConn->query($q)->fetch();

        return $res;
    }     

    public function set_category($params)
    {
        
        $q="UPDATE categories SET name = '".$params['name']."', description = '".$params['descr']."'";

        if (isset($params['image']))
        {
            if (file_put_contents($_SERVER['DOCUMENT_ROOT'].'/images/categories/'.$params['image']['name'], base64_decode($params['image']['body'])))
            {    
                $q .= ", image = '". $params['image']['name'] ."'";                
            }    
        }
        $q .= " WHERE id = ".$params['id'];
        $res=self::$dbConn->query($q)->fetch();

        if ( ! isset($res) )
        {
            $status=False;
        }
        else
        {
            $status=True;
        }
        
        return $status;        
    }

    public function set_product($params)
    {
        // TODO make error handler to result, 
        //      and separate error handler for image changing!!!

        $q="UPDATE goods SET name = '".$params['name']."',"
                ." name_supl = '".$params['name_supl']."',"
                ." price_buy = ".$params['price_buy'].","
                ." price_sell = ".$params['price_sell'].","
                ." description = '".$params['descr']."',"
                ." cid = ".($params['cat']*1);
//die($q);
        if (isset($params['image']))
        {
            if (file_put_contents($_SERVER['DOCUMENT_ROOT'].'/images/products/'.$params['image']['name'], base64_decode($params['image']['body'])))
            {    
                $q .= ", image = '". $params['image']['name'] ."'";                
            }    
        }
        $q .= " WHERE id = ".$params['id'];
        $res=self::$dbConn->query($q)->fetch();

        if ( ! isset($res) )
        {
            $status=False;
        }
        else
        {
            $status=True;
        }
        
        

        return $status;         
    }   

    public function get_list_by_cat($id)
    {
        $q="select * from goods where cid=".$id;
        $res=self::$dbConn->query($q)->fetchAll();        

        return $res;
    } 

    public function get_all_products()
    {
        $q="select * from goods ";
        $res=self::$dbConn->query($q)->fetchAll();
        
        return $res;

    }    
}
    
?>