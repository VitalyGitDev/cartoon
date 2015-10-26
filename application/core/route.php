<?php
  class Route
  {
    static function start()
    {
        $controller_name = 'store';
        $action_name = 'index';
        $address = explode('/',$_SERVER['REQUEST_URI']);     
        
        

        if ( ! empty($address[1]) )
        {
            $controller_name = $address[1];
        }
        if ( ! empty($address[2]) )
        {
            $action_name = $address[2];
        }
        
        $model_name = 'model_'.$controller_name;
        $controller_name = 'controller_'.$controller_name;
        $action_name = 'action_'.$action_name;
        $model_file = 'application/models/'.strtolower($model_name).'.php';
        $controller_file = 'application/controllers/'.strtolower($controller_name).'.php';
        
        if ( file_exists($model_file) )
        {
            require_once($model_file);
        }
        
        if ( file_exists($controller_file) )
        {
            require_once($controller_file);
        }
        else
        {
            echo "404";
            Route::ErrorPage404();

        }

        $controller = new $controller_name;
        
        if ( method_exists($controller, $action_name) )
        {
            $controller->$action_name();
        }
        else
        {
            //Route::ErrorPage404();
            $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
            header('HTTP/1.1 404 Not Found');
    	    header("Status: 404 Not Found");
    	    header('Location:'.$host.'404');            
        }
    }
    function ErrorPage404()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
    	header("Status: 404 Not Found");
    	header('Location:'.$host.'404');
    }

}

?>