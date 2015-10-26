<script src="/js/main_face_page.js"></script> 

    <?php require_once $_SERVER['DOCUMENT_ROOT'].'/application/views/partials/modal_window.php'; ?>
    <?php require_once $_SERVER['DOCUMENT_ROOT'].'/application/views/partials/header_store.php'; ?>
    <?php /*echo '<pre>'.print_r($data,1).'</pre>';*/?>
    <div class='body' style="">

        <div class='left_panel' style="position: relative; width: 100px; display: inline-block; float:left;">
        <?php if (! empty($data['categories']) ) { ?>
            <?php foreach($data['categories'] as $category) {?>
                <div class="category_item" cid="<?= $category['id'] ?>">
                	<div class="menu_item_image">
                        <img src="<?php echo $data['img_config']['IMAGES_DIR']?>categories/<?= $category['image'] ?>">
                	</div>
                	<div class="menu_item_name" title="<?= $category['name'] ?>">
                		<?= $category['name'] ?>
                	</div>	
                </div>
            <?php } ?>
        <?php } ?>
        </div>

        <div class='right_panel' style="position: relative; width: 100px; display: inline-block; float:right;">
        <?php if (! empty($data['categories']) ) { ?>
            <?php foreach($data['categories'] as $category) {?>
                <div class="category_item" cid="<?= $category['id'] ?>">
                    <div class="menu_item_image">
                        <img src="<?php echo $data['img_config']['IMAGES_DIR']?>categories/<?= $category['image'] ?>">
                    </div>
                    <div class="menu_item_name" title="<?= $category['name'] ?>">
                        <?= $category['name'] ?>
                    </div>  
                </div>
            <?php } ?>
        <?php } ?>
        </div>  

        <?php require_once $_SERVER['DOCUMENT_ROOT'].'/application/views/partials/all_products_list.php'; ?>        

    </div>

    <?/*php require_once $_SERVER['DOCUMENT_ROOT'].'/application/views/partials/footer_store.php'; */?>


