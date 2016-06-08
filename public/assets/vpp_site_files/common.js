$(document).ready(function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/getProducts',
		type: 'post',
		dataType: 'json',
		success: function(json) {
			console.log(json);
			$('input[name^=\'quantity3[\'').val('');
	        $('input[name^=\'quantity3[\'').css("background-color", "#055993");
	        $('input[name^=\'quantity3[\'').css('background-image', 'url(catalog/view/theme/default/image/add-cart.png)');
	        $('input[name^=\'quantity3[\'').attr('readonly', true);
			$.each(json, function(i,data) {
				$('input[name=\'quantity3['+data.product_id+']\']').val(data.quantity);
				$('input[name=\'quantity3['+data.product_id+']\']').css('background-image', 'none');
				$('input[name=\'quantity3['+data.product_id+']\']').css("background-color", "#094269");
				$('input[name=\'quantity3['+data.product_id+']\']').prop('readonly', false);
			});
		}
	});

	/* Search */
	$('.button-search').bind('click', function() {
		url = $('base').attr('href') + 'index.php?route=product/search';
				 
		var search = $('input[name=\'search\']').attr('value');
		
		if (search) {
			url += '&search=' + encodeURIComponent(search);
		}
		
		location = url;
	});
	
	$('#header input[name=\'search\']').bind('keydown', function(e) {
		if (e.keyCode == 13) {
			url = $('base').attr('href') + 'index.php?route=product/search';
			 
			var search = $('input[name=\'search\']').attr('value');
			
			if (search) {
				url += '&search=' + encodeURIComponent(search);
			}
			
			location = url;
		}
	});
	
	/* Ajax Cart */
	$('#cart > .heading a').live('mouseover', function() {
		$('#cart').addClass('active');
		
		$('#cart').load('index.php?route=module/cart #cart > *');
		
		$('#cart').live('mouseleave', function() {
			$(this).removeClass('active');
		});
	});

	$('#cart').load('index.php?route=module/cart #cart > *');

	$('#cartLi').hover(function(){
		$('#cart').load('index.php?route=module/cart #cart > *');
	});
	
	/*$('#icnCart').live('click', function() {
		$('#cart').addClass('active');
		
		$('#cart').load('index.php?route=module/cart #cart > *').hide().fadeIn('slow');

		$(document.body).click(function(){
		    var $this = $('#cart');
		    alert(this.attr('id'));
		    if(!$this.data('clicked')) {
		        //$this.removeClass('active');
		        //alert('w2w2');
		    }
		    else {
		        alert('www');
		    }
		});
		
	});*/

	/* Mega Menu */
	$('#menu ul > li > a + div').each(function(index, element) {
		// IE6 & IE7 Fixes
		if ($.browser.msie && ($.browser.version == 7 || $.browser.version == 6)) {
			var category = $(element).find('a');
			var columns = $(element).find('ul').length;
			
			$(element).css('width', (columns * 143) + 'px');
			$(element).find('ul').css('float', 'left');
		}		
		
		var menu = $('#menu').offset();
		var dropdown = $(this).parent().offset();
		
		i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());
		
		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 5) + 'px');
		}
	});

	// IE6 & IE7 Fixes
	if ($.browser.msie) {
		if ($.browser.version <= 6) {
			$('#column-left + #column-right + #content, #column-left + #content').css('margin-left', '195px');
			
			$('#column-right + #content').css('margin-right', '195px');
		
			$('.box-category ul li a.active + ul').css('display', 'block');	
		}
		
		if ($.browser.version <= 7) {
			$('#menu > ul > li').bind('mouseover', function() {
				$(this).addClass('active');
			});
				
			$('#menu > ul > li').bind('mouseout', function() {
				$(this).removeClass('active');
			});	
		}
	}
	
	$('.success img, .warning img, .attention img, .information img').live('click', function() {
		$(this).parent().fadeOut('slow', function() {
			$(this).remove();
		});
	});	
});

function getURLVar(key) {
	var value = [];
	
	var query = String(document.location).split('?');
	
	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');
			
			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}
		
		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
} 

function addToCart(product_id, quantity) {
	quantity = typeof(quantity) != 'undefined' ? quantity : 1;

	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: 'product_id=' + product_id + '&quantity=' + quantity,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information, .error').remove();
			
			if (json['redirect']) {
				location = json['redirect'];
			}
			
			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
				
				$('.success').fadeIn('slow');
				
				$('#cart-total').html(json['total']);
				
				$('html, body').animate({ scrollTop: 0 }, 'slow'); 

				$.ajax({
					url: 'index.php?route=checkout/cart/delivery',
					type: 'post',
					dataType: 'json',
					success: function(json) {
						$('#delivery-status').html(json['message']);

						$( "#progressbar" ).progressbar({
					    	value: json['percentage']
					    });

					}
				});

				$.ajax({
					url: 'index.php?route=checkout/cart/getProducts',
					type: 'post',
					dataType: 'json',
					success: function(json) {
						$('input[name^=\'quantity3[\'').val('');
				        $('input[name^=\'quantity3[\'').css("background-color", "#055993");
				        $('input[name^=\'quantity3[\'').css('background-image', 'url(catalog/view/theme/default/image/add-cart.png)');
				        $('input[name^=\'quantity3[\'').attr('readonly', true);
						$.each(json, function(i,data) {
							$('input[name=\'quantity3['+data.product_id+']\']').val(data.quantity);
							$('input[name=\'quantity3['+data.product_id+']\']').css('background-image', 'none');
							$('input[name=\'quantity3['+data.product_id+']\']').css("background-color", "#094269");
							$('input[name=\'quantity3['+data.product_id+']\']').prop('readonly', false);
						});
					}
				});
			}	
		}
	});
}
function editableField(name) {
	$("input[name='" + name + "']").css('background-image', 'none');
	$("input[name='" + name + "']").css("background-color", "#094269");
	$("input[name='" + name + "']").prop('readonly', false);
	$("input[name='" + name + "']").focus();
}
function changeQuantity(product_id, quantity) {
	quantity = typeof(quantity) != 'undefined' ? quantity : 1;

	$.ajax({
		url: 'index.php?route=checkout/cart/changeQuantity',
		type: 'post',
		data: 'product_id=' + product_id + '&quantity=' + quantity,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information, .error').remove();
			
			if (json['redirect']) {
				$.fancybox({
                    'width': 540,
                    'autoScale': true,
                    'transitionIn': 'fade',
                    'transitionOut': 'fade',
                    'type': 'iframe',
					'scrolling': 'no',
                    'href': json['redirect']
                });
				//location = json['redirect'];
			}
			
			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
				
				$('.success').fadeIn('slow');
				
				$('#cart-total').html(json['total']);
				
				// $('html, body').animate({ scrollTop: 0 }, 'slow'); 

				$.ajax({
					url: 'index.php?route=checkout/cart/delivery',
					type: 'post',
					dataType: 'json',
					success: function(json) {
						$('#delivery-status').html(json['message']);

						$( "#progressbar" ).progressbar({
					    	value: json['percentage']
					    });
					}
				});

				$.ajax({
					url: 'index.php?route=checkout/cart/getProducts',
					type: 'post',
					dataType: 'json',
					success: function(json) {
						$('input[name^=\'quantity3[\'').val('');
				        $('input[name^=\'quantity3[\'').css("background-color", "#055993");
				        $('input[name^=\'quantity3[\'').css('background-image', 'url(catalog/view/theme/default/image/add-cart.png)');
				        $('input[name^=\'quantity3[\'').attr('readonly', true);
						$.each(json, function(i,data) {
							$('input[name=\'quantity3['+data.product_id+']\']').val(data.quantity);
							$('input[name=\'quantity3['+data.product_id+']\']').css('background-image', 'none');
							$('input[name=\'quantity3['+data.product_id+']\']').css("background-color", "#094269");
							$('input[name=\'quantity3['+data.product_id+']\']').attr('readonly', false);
						});
					}
				});
			}	
		}
	});
}
function addToWishList(product_id) {
	$.ajax({
		url: 'index.php?route=account/wishlist/add',
		type: 'post',
		data: 'product_id=' + product_id,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information').remove();
						
			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
				
				$('.success').fadeIn('slow');
				
				$('#wishlist-total').html(json['total']);
				
				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}	
		}
	});
}

function addToCompare(product_id) { 
	$.ajax({
		url: 'index.php?route=product/compare/add',
		type: 'post',
		data: 'product_id=' + product_id,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information').remove();
						
			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
				
				$('.success').fadeIn('slow');
				
				$('#compare-total').html(json['total']);
				
				$('html, body').animate({ scrollTop: 0 }, 'slow'); 
			}	
		}
	});
}