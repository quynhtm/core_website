$(document).ready(function($){
	//$('.province_id, .category_id').fancySelect(); đang bị lỗi chỗ này
	SITE.change_img();
});
 var is_shop_vip = 3;

SITE = {
	change_img:function(){
		jQuery(".item").hover(function(){
			var img = jQuery(this).find('.post-thumb img').attr('src');
			var img_hover = jQuery(this).find('.post-thumb img').attr('data-other-src');
			jQuery(this).find('.post-thumb img').attr('src', img_hover);
			jQuery(this).find('.post-thumb img').attr('data-other-src', img);
		});
	},
	setOnTopProduct: function(product_id,is_shop) {
		if(is_shop == is_shop_vip){//shop vip mới có quyền này
			$('#img_loading_'+product_id).show();
			$.ajax({
				type: "post",
				url: 'shop/setOntop',
				data: {product_id : product_id,is_shop : is_shop},
				dataType: 'json',
				success: function(res) {
					$('#img_loading_'+product_id).hide();
					if(res.isIntOk == 1){
						alert('Bạn đã thực hiện thành công');
						//window.location.reload();
					}else{
						alert('Không thể thực hiện được thao tác.');
					}
				}
			});
		}else{
			alert("Xin lỗi! Shop VIP mới có chức năng này");
		}
	},
	deleteProduct: function(product_id) {
		if(confirm('Bạn có muốn xóa sản phẩm này không?')) {
			$('#img_loading_'+product_id).show();
			$.ajax({
				type: "post",
				url: 'shop/deleteProduct',
				data: {product_id : product_id},
				dataType: 'json',
				success: function(res) {
					$('#img_loading_'+product_id).hide();
					if(res.isIntOk == 1){
						alert('Bạn đã thực hiện thành công');
						window.location.reload();
					}else{
						alert('Không thể thực hiện được thao tác.');
					}
				}
			});
		}
	},
}