var WINDOW_FACTORY = new modal_window_factory(),
    popup_cart_timeout = null;
$(document).ready(function(){
    
    $('.cat_window').css({
        'top': $('.window_background').height(), 
        'left': 0, 
        'width': 0, 
        'height': 0,
    });

    $('#store_product_list_wraper .window_top_gradient, #store_product_list_wraper .window_bottom_gradient').css(
            'width', ($('#store_product_list_wraper').width()) + 'px' 
    );
    
});

$(window).resize(function(){
    $('#store_product_list_wraper .window_top_gradient, #store_product_list_wraper .window_bottom_gradient').css(
            'width', ($('#store_product_list_wraper').width()) + 'px' 
    );
    bottom = $(document).height() - ($('.header_store').height() + $('#all_product_list').height()) - 10; 
    $('.window_bottom_gradient').css('bottom', bottom);
});

$(window).on('load', function(){ 
    bottom = $(document).height() - ($('.header_store').height() + $('#all_product_list').height()) - 10; 
    $('.window_bottom_gradient').css('bottom', bottom);
});

$(document).on('click', '.menu_item_image img', function(){
	var w_height = $(document).height() - ($('.header_store').height() + $('.footer_store').height()),
        w_width = ($(document).width() / 5)*4,
        left = ($(document).width() - w_width)/2,
		y = ($(document).height() - w_height)/7,
        cat_id = $(this).parents('.category_item').attr('cid');

    $('.category_item').hide();
    $('#store_product_list_wraper').hide();
    $('.window_background').show();
    $('.cat_window').animate({
	    opacity: 0.9,
	    left: "+="+left,
	    top: "-="+(w_height+y),
	    height: "+="+w_height,
        width: "+="+w_width,
		}, 300, function() {
            $('.cat_window .container').html(WINDOW_FACTORY.getCategoryViewWindow());
            getCategoryProducts(cat_id, $(this).find('.container'));		    
	});
    
});

$(document).on('click', '.btn_close', function(){
    $('.window_background').hide();
    $('.cat_window').css({
        'top': $('.window_background').height(), 
        'left': 0, 
        'width': 0, 
        'height': 0,
    }).find('.container').html('');    
    $('.category_item').show();
    $('#store_product_list_wraper').show();
});

$(document).on('click', '#cart', function(){
    alert('go into cart');
});

$(document).on('mouseover', '#cart', function(){
    //alert('hover cart');
    popup_cart_timeout = setTimeout(show_cart_popup($(this)), 500);
});

$(document).on('mouseout', '#cart', function(){
    //alert('hover cart');
    if (popup_cart_timeout) {
        clearTimeout(popup_cart_timeout);
        if (typeof $('.cart_popup') != 'undefined') {
            $('.cart_popup').remove();
        }
    }
    
});

function show_cart_popup(elem) {
    elem.append('<div class="cart_popup"></div>');
    elem.find('.cart_popup').offset(function(i, val){
        return {top:val.top, left:val.left - 50}
    });
}

function getCategoryProducts(id, container) {
	
    $.post('/store/get_list_by_cat', {"id": id}, function(r){
        if ( p_list = $.parseJSON(r) ) {
            row_template = container.find('.viewCategoryItem').clone().removeClass('template');
            var container_style = {
                "height"    : (container.parents('.cat_window').height() - 50) + 'px', 
                "width" : (container.parents('.cat_window').width() + 50) + 'px',
                "position"  : 'relative',
                "top"   : '30px',
            };
            var list_wrapper_style = {
                "height"    : (container.parents('.cat_window').height() - 50) + 'px', 
                "width" : '100%',
                "position"  : 'relative',
            };
            container.css(list_wrapper_style);
            container.find('.viewCategory').html('').css(list_wrapper_style);
            
            if (typeof p_list.empty != 'undefined' ) {
                return;
            }
            for (var i in p_list) {
                var line = row_template.clone();
                line.attr({'prod_id': p_list[i].id, 'cat_id': p_list[i].cid, 'id': ''});
                line.addClass('product_item');
                line.find('.prodImage').attr('style', "background: url('/images/products/" + p_list[i].image + "') no-repeat; background-size: 100% 100%;");
                line.find('.prodImage').css({"width": "100px", "height": "100px", 'margin': '0 auto'});                
                line.find('.prodName').text(p_list[i].name);
                
                container.find('.viewCategory').append(line);
            }

        }
    });	
}       


