jQuery(document).ready(function($){

});
orderShop = {
	delOne:function(){
		jQuery('.delOneItemCart').click(function(){
			var url = WEB_ROOT+'/shop/deleteProvider';

			var id = jQuery(this).attr('data-id');
			var result = confirm("Bạn có muốn cập nhật đơn hàng không [OK]:Đồng ý [Cancel]:Bỏ qua ?");
			if(result){
				jQuery.ajax({
					type: "POST",
					url: url,
					data: "id="+encodeURI(id),
					success: function(data){
						if(data != ''){
							window.location.reload();
						}
					}
				});	
			}
			return true;	
		});	
	},
	getInforCustomerBuyProduct:function(){
		var customer_phone = jQuery("#customer_phone").val();
		if(customer_phone != ''){
			$('#img_loading').show();
			jQuery.ajax({
				type: "POST",
				url: WEB_ROOT+'/shop/getInforCustomerBuyProduct',
				data: {customer_phone : customer_phone},
				success: function(data) {
					$('#img_loading').hide();
					if(data.intIsOK === 1){
						alert('xxxx');
					}else{
						jQuery('#sys_msg_return').html(data.msg);
					}
				}
			});
		}
	},
	getInforProductBuy:function(){
		var product_id = jQuery("#product_id").val();
		if(product_id != ''){
			$('#img_loading').show();
			jQuery.ajax({
				type: "POST",
				url: WEB_ROOT+'/shop/getInforProductBuy',
				data: {product_id : product_id},
				success: function(data) {
					$('#img_loading').hide();
					if(data.intIsOK === 1){
						alert('yyyy');
					}else{
						jQuery('#sys_msg_return').html(data.msg);
					}
				}
			});
		}
	}

}