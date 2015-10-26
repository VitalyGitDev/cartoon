<!--ХИДЕР-->
<?php include 'application/views/header.php'; ?>

<div class=button id=button_back> Назад </div>

<?php include 'application/views/connected_resourses.php'; ?>

<div id=back style='display:none; position: absolute; width: 100%; height:100%; z-index:777;'>
  <div style='position: relative; top: 200px; left: 500px; width:300px; height: 300px;'>
      
  </div>
</div>

<SCRIPT>
    
    $('#button_back').click(function(){
       window.location.replace('http://stat.vm.ua/cis/user/');
    });
    
    $('#button_new_resourse').click(function(){
        alert('add resourse from full list');
    });
    
</SCRIPT>