<div id="store_product_list_wraper" >   

<div class="window_top_gradient"></div>

    <div id="all_product_list">
      <?php if (!empty($data['products'])) {?>
        <?php foreach($data['products'] as $product) {?>
          <div  class="viewCategoryItem" prod_id="<?= $product['id'] ?>" cat_id="<?= $product['cid'] ?>">
              <div class="productHeader">
                <div class="prodImage" style="background: url('<?= '/images/products/'.$product['image']?>') no-repeat; background-size: 100% 100%;">
                <!--
                  <img src="<?= '/images/products/'.$product['image']?>">
                -->
                </div>
                <div class="prodProperties">
                  <div class="prodName"><?= $product['name'] ?></div>
                  <div class="prodPrice"><?= $product['price_sell']." грн" ?></div>
                  <div class="prodQty">
                    <span class="qty_up">&nbsp+&nbsp</span><span class="qty_current">[&nbsp1&nbsp]</span><span class="qty_down">&nbsp-&nbsp</span>
                  </div>
                </div>
              </div>
              <br><div class="buttons">
                <span class="button" id="btn_prod_buy">Купить</span><span class="button" id="btn_to_cart">В корзину</span>
              </div>
          </div> 
        <?php } ?>  
      <?php } else { ?>
          <div>нет товаров!</div>   
      <?php } ?>
    </div>  

<div class="window_bottom_gradient"></div>

</div>

