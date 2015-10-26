<div id=resourses_list >
    
   <p style='padding-left: 55px'>=Настройки=</p>
  <div style='margin: 20px;'>
  <? foreach ($data['connections'] as $val) { ?>
   <div style='border-bottom: 1px solid #696969;' ><? echo $val['name']; ?></div>
   <? } ?>
  </div> 
   
</div>
  <div class=button id=button_new_resourse style='width: 140px;'> Подключить новый ресурс </div>
  <div class=button id=button_disconnect_resourse> Отключить ресурс </div>

<SCRIPT>
    $(document).ready(function(){
          $('#resourses_list').css('width',($(document).width()-40) );
    });
</SCRIPT>