function modal_window_factory() {
	this.getCategoryCreationWindow = function(){
            var innerHTML = '<div class="newCategory"><span class="window_header_text">Создание новой категории</span>'
                                +'<div class="fields">'
                                    +'<span class="label">Название категории : </span><input type=text id="cat_name">'
                                    +'<br><span class="label">Изображение : </span>'
                                    +'<br><div class="image"></div>'
                                    +'<br><form id="img_form"><input type=file id="cat_image"></form>'
                                    +'<br><span class="label">Описание категории : </span><br><textarea id="cat_description"></textarea>'
                                +'</div>'
                                +'<br><div class="buttons">'
                                    +'<span class="button" id="btn_cat_create">Создать</span><span class="button" id="btn_cancel">Отмена</span>'
                                +'</div>'
                            +'</div>';

            return innerHTML;
	}

    this.getCategoryEditWindow = function(){
            var innerHTML = '<div class="editCategory"><span class="window_header_text">Редактирование категории</span>'
                                +'<div class="fields">'
                                    +'<span class="label">Название категории : </span><input type=text id="cat_name">'
                                    +'<br><span class="label">Изображение : </span>'
                                    +'<span class="btn_img_change button">изменить</span>'
                                    +'<form id="img_form" style="display: none;"><input type=file id="cat_image"></form>'
                                    +'<br><div class="image"></div>'
                                    +'<br><span class="label">Описание категории : </span><br><textarea id="cat_description"></textarea>'
                                +'</div>'
                                +'<br><div class="buttons">'
                                    +'<span class="button" id="btn_cat_edit">Сохранить</span><span class="button" id="btn_cancel">Отмена</span>'
                                +'</div>'
                            +'</div>';

            return innerHTML;
    }    

    this.getCategoryViewWindow = function(){
            var innerHTML = '<div class="viewCategory">'
                                +'<div class="viewCategoryItem template">'
                                    +'<div class="productHeader">'
                                        +'<br><div class="prodImage"></div>'
                                        +'<br><span class="prodName"></span>'
                                        +'<br><span class="prodPrice"></span>'
                                    +'</div>'
                                    +'<br><div class="buttons">'
                                        +'<span class="button" id="btn_prod_buy">Купить</span><span class="button" id="btn_to_cart">В корзину</span>'
                                    +'</div>'
                                +'</div>'                                    
                            +'</div>';

            return innerHTML;
    } 

	this.getProductCreationWindow = function(){
            var innerHTML = '<div class="newProduct"><span class="window_header_text">Создание нового товара</span>'
                                +'<div class="fields">'
                                    +'<span class="label">Название товара(наше) : </span><input type=text class="text" id="prod_name">'
                                    +'<span class="label">Название товара(поставщик) : </span><input type=text class="text" id="prod_name_supl">'
                                    +'<span class="label">Цена(закуп) : </span><input type=text class="price" id="prod_price_supl">'
                                    +'<span class="label">Цена(розн.) : </span><input type=text class="price" id="prod_price_rozn">'
                                    +'<br><span class="label">Изображение : </span>'
                                    +'<br><div class="image"></div>'
                                    +'<br><form id="img_form"><input type=file id="prod_image"></form>'
                                    +'<br><span class="label">Описание товара : </span><br><textarea id="prod_description"></textarea>'
                                +'</div>'
                                +'<br><div class="buttons">'
                                    +'<span class="button" id="btn_prod_create">Создать</span><span class="button" id="btn_cancel">Отмена</span>'
                                +'</div>'
                            +'</div>';

            return innerHTML;
	}	
        
	this.getProductEditWindow = function(){
            var innerHTML = '<div class="editProduct"><span class="window_header_text">Редактирование товара</span>'
                                +'<div class="fields">'
                                        +'<span class="label">Название товара(наше) : </span><input type=text class="text" id="prod_name">'
                                        +'<span class="label">Название товара(поставщик) : </span><input type=text class="text" id="prod_name_supl">'
                                        +'<span class="label">Цена(закуп) : </span><input type=text class="price" id="prod_price_supl">'
                                        +'<span class="label">Цена(розн.) : </span><input type=text class="price" id="prod_price_rozn">'
                                        +'<br><span class="label">Изображение : </span>'
                                        +'<span class="btn_img_change button">изменить</span>'
                                        +'<form id="img_form" style="display: none;"><input type=file id="prod_image"></form>'
                                        +'<br><div class="image"></div>'

                                        +'<br><span class="label">Описание товара : </span><br><textarea id="prod_description"></textarea>'
                                +'</div>'
                                +'<br><div class="buttons">'
                                        +'<span class="button" id="btn_prod_edit">Сохранить</span><span class="button" id="btn_cancel">Отмена</span>'
                                +'</div>'
                            +'</div>';

            return innerHTML;
	}	        
} 

