/*
	TODO: refactoring for product item lines, make it by object
	TODO: put methods for 'click' events in separete file
*/
var b64encoded = {"body": '', "name": '', "type": '', "size": ''};
var WINDOW_FACTORY = new modal_window_factory();

$(document).ready(function(){
    var w_width = ($(document).width() / 5)*3,
        w_height = ($(document).height() / 10)*8;
        x = ($(document).width() - w_width)/2,
        y = ($(document).height() - w_height)/2;
    $('.cat_window').css({
        'top': y, 
        'left': x, 
        'width': w_width, 
        'height': w_height
    }).addClass('userview');
    
    $('#product_list_wraper').css('width',($(document).width()-20) )
                          .css('max-width',($(document).width()-20) )
                          .css('max-height',($(document).height()-10) );

    $('.cat_links li:first').click();
});

$(document).on('click', '#cat_menu_add', function(){
    $('.window_background').show();
	$('.cat_window .container').html('');
	$('.cat_window .container').append(WINDOW_FACTORY.getCategoryCreationWindow());
    
});

$(document).on('click', '#cat_menu_edit', function(){
    $('.window_background').show();
    $('.cat_window .container').html('');
    $('.cat_window .container').append(WINDOW_FACTORY.getCategoryEditWindow());
    fillCategoryEditWindow($('#categories_list .cat_links .active').attr('cid'), $('.cat_window .container .editCategory'));
});

$(document).on('click', '#product_add', function(){
    $('.window_background').show();
	$('.cat_window .container').html('');
	$('.cat_window .container').append(WINDOW_FACTORY.getProductCreationWindow());
});

$(document).on('click', '.btn_edit_product', function(){
    $('.window_background').show();
	$('.cat_window .container').html('');
	$('.cat_window .container').append(WINDOW_FACTORY.getProductEditWindow());
    fillProductEditWindow($(this).parent('.product_item').attr('prod_id'), $('.cat_window .container .editProduct'));
});

$(document).on('click', '.btn_close', function(){
    $('.window_background').hide();     
});     

$(document).on('click', '#btn_cancel', function(){
    $('.window_background').hide();
});  

/* 
*   Need refactoring for window factory methods
*/
$(document).on('click', '.fields .btn_img_change', function(){
    
    if ($(this).parent('.fields').find('#img_form').css('display') == 'none') {
        $(this).parent('.fields').find('#img_form').show().css({
            'display': 'inline-block', 
            'margin-bottom':'0px',
            'width': '180px',
            /*'overflow': 'hidden',*/
        });
        $(this).text('Отменить');
        
    } else {
        $(this).parent('.fields').find('#img_form').hide();
        $(this).text('Изменить');
        $(this).parent('.fields').parent('div').removeClass('newImage');
    }
});

$(document).on('click', '.newCategory #btn_cat_create', function(){
    var cat_name = '',
    	cat_descr = '';
    cat_name = $('.newCategory #cat_name').val();
    cat_descr = $('.newCategory #cat_description').val();

    var data = {
    		"action" : 'newCategory',
    		"name"	 : cat_name,
    		"descr"	 : cat_descr,
    	};

    $.post('/store/add_category', data, function(responce){
    	//console.log(responce);
    	if ( responce == '200' ) {
    		$('.newCategory #btn_cancel').click();
    		location.reload(true);
    	}
    });
});  

$(document).on('click', '.editCategory #btn_cat_edit', function(){
    var cat_name = '',
        cat_descr = '';
    cat_name = $('.editCategory #cat_name').val();
    cat_descr = $('.editCategory #cat_description').val();
    cat_id = $('#categories_list .active').attr('cid')*1;

    var data = {
            "action" : 'editCategory',
            "name"   : cat_name,
            "descr"  : cat_descr,
            "id"     : cat_id,
        };
    if ($('.editCategory').hasClass('newImage')) {
        data.image = b64encoded;        
    }
    $.post('/store/edit_category', data, function(responce){
        //console.log(responce);
        if ( responce == '200' ) {
            $('#btn_cancel').click();
            location.reload(true);
        }
    });
});

$(document).on('click', '.newProduct #btn_prod_create', function(){
    var cat_name = '',
    	cat_descr = '';
    prod_name = $('.newProduct #prod_name').val();
    prod_descr = $('.newProduct #prod_description').val();
    prod_cat = $('#categories_list .active').attr('cid')*1;
    
    var data = {
    		"action" : 'newProduct',
    		"name"	 : prod_name,
    		"descr"	 : prod_descr,
    		"cat"	 : prod_cat,
    		"image"	 : b64encoded,
    	};

    $.post('/store/add_product', data, function(responce){
    	//console.log(responce);
    	if ( responce == '200' ) {
    		$('#btn_cancel').click();
    		location.reload(true);
    	}
    });
});

$(document).on('click', '.editProduct #btn_prod_edit', function(){
    var cat_name = '',
        cat_descr = '';
    prod_name = $('.editProduct #prod_name').val();
    prod_name_supl = $('.editProduct #prod_name_supl').val();
    prod_price_buy = $('.editProduct #prod_price_supl').val();
    prod_price_sell = $('.editProduct #prod_price_rozn').val();
    prod_descr = $('.editProduct #prod_description').val();
    prod_cat = $('#categories_list .active').attr('cid')*1;
    prod_id = $('.cat_window .container .editProduct').attr('prod_id');
    
    var data = {
            "action" : 'editProduct',
            "name"   : prod_name,
            "name_supl"  : prod_name_supl,
            "price_buy"  : prod_price_buy,
            "price_sell" : prod_price_sell,
            "descr"  : prod_descr,
            "cat"    : prod_cat,
            "id"     : prod_id,
            /*"image"  : b64encoded,*/
        };
    if ($('.editProduct').hasClass('newImage')) {
        data.image = b64encoded;        
    }

    $.post('/store/edit_product', data, function(responce){
        //console.log(responce);
        if ( responce == '200' ) {
            $('#btn_cancel').click();
            location.reload(true);
        }
    });
});

$(document).on('click', '.btn_del_product', function(){
    var line = $(this).parent('.product_item');
    
    prod_id = line.attr('prod_id');
    prod_cat = line.attr('cat_id');

    var data = {
    		"action" : 'delProduct',
    		"id"	 : prod_id,
    		"cat_id"	 : prod_cat,
    	};

    $.post('/store/del_product', data, function(responce){
    	location.reload(true);
	});	

});

$(document).on('click', '.category', function(){
	var that = $(this);
    if (that.hasClass('active')) {
		return;
	}

    $.post('/store/get_list_by_cat', {"id": $(this).attr('cid')}, function(r){
        if ( p_list = $.parseJSON(r) ) {
            $('.category').removeClass('active');
            that.addClass('active');
            $('#product_list').html('');
            
            if (typeof p_list.empty != 'undefined' ) {
                return;
            }
            for (var i in p_list) {
                var line = $('#product_item_template').clone();
                line.attr({'prod_id': p_list[i].id, 'cat_id': p_list[i].cid, 'id': ''});
                line.addClass('product_item');
                line.find('.product_image').attr('style', "background: url('/images/products/" + p_list[i].image + "') no-repeat; background-size: 100% 100%;");
                line.find('.product_name').text(p_list[i].name + '   |   (' + p_list[i].name_supl + ')');
                $('#product_list').append(line);
            }
        }
    });
	

});

$(document).on('click', '#cat_menu_del', function(){
	if (confirm('Вы точно хотите удалить категорию ?')) {
		var data = {
			"id"	: $('#categories_list .active').attr('cid'),
		};
		$.post('/store/del_category', data, function(responce){
	    	location.reload(true);
		});		
	}
});

$(document).on('change', '#prod_image', function(){
	
	var fileReader = window.FileReader ? new FileReader() : null;
	var img_file = $(this).parent('#img_form')[0][0].files[0];

	if (fileReader){
		fileReader.addEventListener("loadend", function(e){
            b64encoded = {"body": '', "name": '', "type": '', "size": ''};
			b64encoded['body'] = window.btoa(e.target.result);
			b64encoded['name'] = img_file.name;
			b64encoded['type'] = img_file.type;
			b64encoded['size'] = img_file.size;
            $('.editProduct').addClass('newImage');
		});
		fileReader.readAsBinaryString(img_file);
	}
	
}); 

// Need refactoring for universal usage by any window
$(document).on('change', '#cat_image', function(){
    
    var fileReader = window.FileReader ? new FileReader() : null;
    var img_file = $(this).parent('#img_form')[0][0].files[0];

    if (fileReader){
        fileReader.addEventListener("loadend", function(e){
            b64encoded = {"body": '', "name": '', "type": '', "size": ''};
            b64encoded['body'] = window.btoa(e.target.result);
            b64encoded['name'] = img_file.name;
            b64encoded['type'] = img_file.type;
            b64encoded['size'] = img_file.size;
            $('.editCategory').addClass('newImage');
        });
        fileReader.readAsBinaryString(img_file);
    }
    
});

function fillProductEditWindow(prod_id, edit_window) {
    
    $.post('/store/get_product_info', {"id": prod_id}, function(resp){
        if ( product = $.parseJSON(resp) ) {
            edit_window.find('#prod_name').val(product.name).css('width','300px');
            edit_window.find('#prod_name_supl').val(product.name_supl).css('width','300px');
            edit_window.find('.fields .image').css({
                "background-image":$('.product_item[prod_id='+prod_id+']').find('.product_image').css('background-image'), 
                "width": ((edit_window.height()/4)*3 )+'px', 
                "height": ((edit_window.height()/4)*3 )+'px', 
                "display": 'inlide-block', 
                "background-size": '100% 100%',
            });
            edit_window.find('#prod_price_supl').val(product.price_buy);
            edit_window.find('#prod_price_rozn').val(product.price_sell);
            edit_window.find('#prod_description').val(product.description).css({'width':'90%', 'height':'20%'});
            edit_window.attr('prod_id', prod_id);

        }
    });
}

function fillCategoryEditWindow(cat_id, edit_window) {
    
    $.post('/store/get_cat_info', {"id": cat_id}, function(resp){
        if ( category = $.parseJSON(resp) ) {
            console.log(category);
            edit_window.find('#cat_name').val(category.name);
            edit_window.find('.fields .image').css({
                "background-image":"url('/images/categories/"+category.image+"')", 
                "width": ((edit_window.height()/4)*3 )+'px', 
                "height": ((edit_window.height()/4)*3 )+'px', 
                "display": 'inlide-block', 
                "background-size": '100% 100%',
            });            
            //edit_window.find('#cat_image').val(category.image);
            edit_window.find('#cat_description').val(category.description);
            
        }
    });
}