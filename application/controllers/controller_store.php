<?php
/**
* REFACTORING
* set CRUD methods to Admin cmontroller!!!
*/
class Controller_Store extends Controller
{
	public $img_config;

	function __construct()
	{
		$this->model = new ModelStore();
		require_once($_SERVER['DOCUMENT_ROOT'].'/application/config/imgs_config.php');
		$this->view = new View();
		$this->img_config = $img_all_config;
	}
	
	public function action_index()
	{
		//die("<pre>".print_r($_SERVER, 1)."</pre>");
            $data = array('img_config' => $this->img_config);
            $data['categories'] = $this->model->get_categories();
            $data['products'] = $this->model->get_all_products();
            
            $this->view->generate('view_store_index.php', 'view_template.php', $data);

    }

	public function action_add_category()
	{
		
		$post = $_POST;
		//die("<pre>".print_r($post, 1)."</pre>");
		if ( $this->model->add_category($post) )
        {
        	die('200');
        }

    }   

	public function action_add_product()
	{
		
		$post = $_POST;
		//die("<pre>".print_r($post, 1)."</pre>");
		if ( $this->model->add_product($post) )
        {
        	die('200');
        }

    }        

    public function action_del_product()
	{
		
		$post = $_POST;
		//die("<pre>".print_r($post, 1)."</pre>");
		if ( $this->model->del_product($post) )
        {
        	die('200');
        }

    } 

    public function action_del_category()
	{
		$post = $_POST;

		if ( $this->model->del_category($post) )
        {
        	die('200');
        }

    }  

    public function action_get_product_info()
    {
    	$post = $_POST;	
    	$prod_info = $this->model->get_product_info($post['id']);
    	die(json_encode($prod_info));
    }

    public function action_get_cat_info()
    {
    	$post = $_POST;	
    	$prod_info = $this->model->get_cat_info($post['id']);
    	die(json_encode($prod_info));	
    }

    public function action_edit_category()
    {
		$post = $_POST;
		//die("<pre>".print_r($post, 1)."</pre>");
		if ( $this->model->set_category($post) )
        {
        	die('200');
        }

    }

    public function action_edit_product()
    {
		$post = $_POST;
		//die("<pre>".print_r($post, 1)."</pre>");
		if ( $this->model->set_product($post) )
        {
        	die('200');
        }

    }  

    public function action_get_list_by_cat()
    {
        $post = $_POST;
        $prod_list = $this->model->get_list_by_cat($post['id']);
        if ( ! empty($prod_list) ) 
        {
            die(json_encode($prod_list));                    
        }
        else 
        {
            die(json_encode(array("empty"=> true)));
        }
    }  
}        


?>
