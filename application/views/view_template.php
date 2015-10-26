<html>
<head>
	
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>CiS</title>
    <link rel="stylesheet" type="text/css" href="/../../css/main.css">
    <script src="/js/jquery-2.1.4.min.js"></script>
    <script src="/js/modal_window_factory.js"></script> 
    
    <!--
    <script type="text/javascript" src="http://partnertest.vm.net/js/tinymce/tinymce.min.js"></script>

    -->
</head>
<?php $img_path =  $data['img_config']['IMAGES_DIR'];?>
<body>
<div id="body_bg" style="background: url('<?php echo $img_path;?>fon_test/main_bg.jpg') no-repeat; background-size: 100% 100%; "> 
  <?php require_once $_SERVER['DOCUMENT_ROOT'].'/application/views/'.$view_content; ?>
</div> 
</body>
</html>    