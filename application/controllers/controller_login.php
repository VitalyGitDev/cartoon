<?php

class Controller_Login extends Controller
{
	public $img_config;

	function __construct()
	{
		$this->model = new ModelLogin();
		$this->view = new View();
		require_once($_SERVER['DOCUMENT_ROOT'].'/application/config/imgs_config.php');
		$this->img_config = $img_all_config;
	}
	
	function action_index()
	{
		if( isset($_POST['login']) && isset($_POST['password']) 
                        && $this->model->check_user_aval($_POST['login'],$_POST['password']) )
		{
                    $login = $_POST['login'];
                    $password =$_POST['password'];

                    if($login=="admin")
                    {
                        $data["login_status"] = "access_granted";
                        session_start();
                        $_SESSION['admin'] = "access_granted";
                        header('Location:/admin/');
                    }
                    else
                    {
                        session_start();
                        $_SESSION['user'] = $login;
                        $_SESSION['access'] = "access_granted";
                        $data["login_status"] = "access_granted";
                        header('Location:/user/');
                    }
		}
		else
		{
			$data["login_status"] = "access_denied";
		}
		$data['img_config'] = $this->img_config;
		$this->view->generate('view_login.php', 'view_template.php', $data);
	}
	
}
?>