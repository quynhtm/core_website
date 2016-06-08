$(document).ready(function() {
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
	
	/* Ajax Cart */
	$('#cart > .heading a').live('mouseover', function() {
		$('#cart').addClass('active');
		
		$('#cart').load('index.php?route=module/cart #cart > *');
		
		$('#cart').live('mouseleave', function() {
			$(this).removeClass('active');
		});
	});
});

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