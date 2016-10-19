jQuery(document).ready(function($){

});
orderShop = {
	delOneShopCart:function(product_id){
		var result = confirm("Bạn có muốn cập nhật đơn hàng không [OK]:Đồng ý [Cancel]:Bỏ qua ?");
		if(result){
			jQuery.ajax({
				type: "POST",
				url: WEB_ROOT+'/shop/deleteOneItemShopCart',
				data: {product_id : product_id},
				success: function(data){
					if(data.isIntOk == 1){
						window.location.reload();
					}else{
						alert('Chưa xóa được sản phẩm trong giỏ hàng');
					}
				}
			});
		}
	},
	changeNumberBuyShopCart:function(product_id){
		var number_buy = jQuery("#number_buy_id_"+product_id).val();
		jQuery.ajax({
			type: "POST",
			url: WEB_ROOT+'/shop/changeNumberBuyShopCart',
			data: {product_id : product_id,number_buy : number_buy},
			success: function(data){
				if(data.isIntOk == 1){
					window.location.reload();
				}else{
					alert('Chưa cập nhật được sản phẩm trong giỏ hàng');
				}
			}
		});
	},
	getInforShopCart:function(){
		var product_id = jQuery("#product_id").val();
		var customer_phone = jQuery("#customer_phone").val();
		if(product_id != '' && customer_phone != ''){
			$('#img_loading').show();
			jQuery.ajax({
				type: "POST",
				url: WEB_ROOT+'/shop/getInforShopCart',
				data: {product_id : product_id,customer_phone : customer_phone},
				success: function(data) {
					$('#img_loading').hide();
					if(data.isIntOk === 1){
						jQuery('#block_show_infor_shop_cart').html(data.infor);
					}
				}
			});
		}else{
			alert('Bạn chưa nhập đầy đủ thông tin để tạo đơn hàng shop');
		}
	},
	orderBuyShopCart:function(){
		var customer_shop_phone = jQuery("#customer_shop_phone").val();
		var customer_shop_full_name = jQuery("#customer_shop_full_name").val();
		var customer_shop_email = jQuery("#customer_shop_email").val();
		var customer_shop_address = jQuery("#customer_shop_address").val();
		var customer_shop_note = jQuery("#customer_shop_note").val();

		if(customer_shop_phone != '' && customer_shop_full_name != '' && customer_shop_email != ''){
			var result = confirm("Bạn chắc chắn muốn bán những mặt hàng trên?");
			if(result){
				$('#img_loading').show();
				jQuery.ajax({
					type: "POST",
					url: WEB_ROOT+'/shop/orderBuyShopCart',
					data: {customer_shop_phone : customer_shop_phone,customer_shop_full_name : customer_shop_full_name,customer_shop_email : customer_shop_email,customer_shop_address : customer_shop_address,customer_shop_note : customer_shop_note},
					success: function(data) {
						$('#img_loading').hide();
						if(data.isIntOk === 1){
							alert('Bán hàng thành công');
							jQuery("#product_id").val('');
							jQuery("#customer_phone").val('');
							window.location.reload();
						}else{
							alert('Chưa đủ thông tin để bán hàng');
						}
					}
				});
			}
		}else{
			alert('Bạn chưa nhập đầy đủ thông tin để đặt hàng cho khách');
		}
	}

}