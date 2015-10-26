<div id="product_list_wraper" >   
    <div style="display: inline-block; width: 100%; ">
      <p style='padding-left: 55px; float: left; display: inline-block;'>=Список товаров=</p>
      <div id="product_add"></div>
    </div>  
    <!--Template for product line-->
    <!--REFACTORING set it to different class with setters-->
    <div id="product_item_template" prod_id="" cat_id="">
        <div class="product_image" style="background: url('') no-repeat; background-size: 100% 100%;"></div>
        <div class="product_name"></div>
        <div class="btn btn_del_product"></div>    
        <div class="btn btn_edit_product"></div>
    </div>    
    <!--End Template-->
    <div id="product_list">
      <?php if (!empty($data['products'])) {?>
        <?php foreach($data['products'] as $product) {?>
          <div class="product_item" prod_id="<?php echo $product['id']; ?>" cat_id="<?php echo $product['cid']; ?>">
              <div class="product_image" style="background: url('<?= '/images/products/'.$product['image']?>') no-repeat; background-size: 100% 100%;"></div>
              <div class="product_name"><?= $product['name'] ?>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp(<?= $product['name_supl'] ?>)</div>
              <div class="btn btn_del_product"></div>    
              <div class="btn btn_edit_product"></div>
          </div> 
        <?php } ?>  
      <?php } else { ?>
          <div>В категории нет товаров!</div>   
      <?php } ?>
    </div>  
</div>

