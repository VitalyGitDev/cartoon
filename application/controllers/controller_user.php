<?php

class Controller_User extends Controller {

    public $img_config;    

    function __construct ()
    {
        $this->model = new ModelUser();
        $this->view = new View();
        require_once($_SERVER['DOCUMENT_ROOT'].'/application/config/imgs_config.php');
        $this->img_config = $img_all_config;        
    }
	
    function action_index()
    {
        session_start();

        if (isset($_SESSION['access'])&&( $_SESSION['access']== "access_granted"))
        {
            /*
            $data['connections'] = $this->model->get_allowed_resourses($_SESSION['user']);;

            foreach ($data['connections'] as $value) {
                $data['messages'][$value['name']] = $this->model->get_active_messages($value['id']);
            }
            */

            $data = array('img_config' => $this->img_config);
            $data['categories'] = $this->model->get_categories();
            $data['products'] = $this->model->get_products();
            //die("<pre>".print_r($data['products'], 1)."/<pre>");
            $this->view->generate('view_user.php', 'view_template.php', $data);
        }
        else
        {
            session_destroy();
            header("Location: http://".$_SERVER['HTTP_HOST']."/", true);
          //Route::ErrorPage404();
        }
    }	
	
    function action_settings()
    {
        session_start();

        $data['connections'] = $this->model->get_allowed_resourses($_SESSION['user']);
        $data['all_connections'] = $this->model->get_all_resourses();

        $this->view->generate('view_user_settings.php', 'view_template.php', $data); 
    }
  
	
    function action_logout()
    {
        session_start();
        session_destroy();
        header('Location:http://'.$_SERVER['HTTP_HOST']);
    }
	
    function action_lock_unlock()
    {

    }
}
