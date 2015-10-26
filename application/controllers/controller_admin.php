<?php

class Controller_Admin extends Controller
{

    public $img_config; 	

	function __construct ()
	{
		$this->model = new ModelAdmin();
		$this->view = new View();
		require_once($_SERVER['DOCUMENT_ROOT'].'/application/config/imgs_config.php');
        $this->img_config = $img_all_config;        		
	}
	
	function action_index()
	{
		session_start();
		
		if (isset($_POST['del_usr']) && ($_POST['del_usr']<>''))
		{
			
			$this->model->del_users($_POST['del_usr']);
						
		}
		
		if (isset($_POST['new_usr']) && ($_POST['new_usr']<>''))
		{
			
			$this->model->add_users($_POST['new_usr'], $_POST['new_pass']);
			
		}		
		
		if ( $_SESSION['admin'] == "access_granted" )
		{
      		$data = array();
			$data['user_list'] = $this->model->get_users();
			$data['img_config'] = $this->img_config;
			//die("<pre>".print_r($data2, 1)."</pre>");
			$this->view->generate('view_admin.php', 'view_template.php',$data);
			
		}
		else
		{
			
			session_destroy();
			Route::ErrorPage404();
		}

	}
	
	
	function action_logout()
	{
		session_start();
		session_destroy();
		header('Location:http://'.$_SERVER['HTTP_HOST']);
	}

}
