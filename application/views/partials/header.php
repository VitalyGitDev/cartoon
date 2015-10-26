<div class="header">
  <div id="logo" >
      <img  src='http://stat.vm.ua/cis/images/CiS_logo.png' />
  </div>
  <div class="info_block">
    <b>Hello: <?php if (!empty($_SESSION['admin'])) { echo "ADMIN";} else { echo $_SESSION['user']; } ?>!</b>
    <br>
    
    <a style='color: #E0EEEE;' href='/user/logout/'><img src='<?php echo $data['img_config']['IMAGES_DIR']?>logout_button.png' style='width: 20px;'></a>
    <a style='color: #E0EEEE;' href='/user/settings/'><img src='<?php echo $data['img_config']['IMAGES_DIR']?>felipecaparelli_Gears_1.png' style='width: 20px;'></a>
  </div>
</div>