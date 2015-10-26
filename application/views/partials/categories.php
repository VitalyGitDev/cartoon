<?php require_once $_SERVER['DOCUMENT_ROOT'].'/application/views/partials/modal_window.php'; ?> 
  
  <div id="categories_list">
    <div class="cat_menu">  
      <ul class="cat_links">
      <?php if (!empty($data['categories'])) {?>
        <?php foreach($data['categories'] as $category) {?>
          <li class="category" cid="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></li> 
        <?php } ?>  
      <?php } else { ?>
          <li>There no categories!</li>   
      <?php } ?>
      </ul>
    </div>

    <div id="cat_menu_add"></div>  
    <div id="cat_menu_del"></div>    
    <div id="cat_menu_edit"></div>
  </div>

