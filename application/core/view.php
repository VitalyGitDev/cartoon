<?php
class View {
    //public $template_def_view;
    
    function generate($view_content, $view_template, $data=NULL)
    {
                
        include 'application/views/'.$view_template;
    }
}

?>