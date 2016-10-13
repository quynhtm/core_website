jQuery(document).ready(function($){
	CART.add();
	CART.update();
	CART.delOne();
	CART.delAll();
	CART.sendCart();
});
CART = {
	add: function () {
		jQuery('#buttonFormBuySubmit').click(function () {
			var url = WEB_ROOT + '/them-vao-gio-hang.html';
			var pid = jQuery(this).attr('data-pid');
			var pnum = jQuery('#buy-num').val();
			jQuery('body').append('<div class="loading"></div>');
			if (pid > 0) {
				jQuery.ajax({
					type: "POST",
					url: url,
					data: "pid=" + encodeURI(pid) + "&pnum=" + encodeURI(pnum),
					success: function (data) {
						jQuery('body').find('div.loading').remove();
						if (data == 1) {
							window.location.href = WEB_ROOT + '/gio-hang.html';
						} else {
							alert(data);
							return false;
						}

					}
				});
			}
		});
	},
	update: function () {
		jQuery('#txtFormShopCart select').change(function () {
			var updateCart = WEB_ROOT + '/gio-hang.html';
			var result = confirm("Bạn có muốn cập nhật đơn hàng không [OK]:Đồng ý [Cancel]:Bỏ qua ?");
			if (result) {
				jQuery('#txtFormShopCart').submit();
			}
			return true;
		});
	},
	delOne: function () {
		jQuery('.delOneItemCart').click(function () {
			var url = WEB_ROOT + '/xoa-mot-san-pham-trong-gio-hang.html';
			var id = jQuery(this).attr('data-id');
			var result = confirm("Bạn có muốn cập nhật đơn hàng không [OK]:Đồng ý [Cancel]:Bỏ qua ?");
			if (result) {
				jQuery.ajax({
					type: "POST",
					url: url,
					data: "id=" + encodeURI(id),
					success: function (data) {
						if (data != '') {
							window.location.reload();
						}
					}
				});
			}
			return true;
		});
	},
	delAll: function () {
		jQuery('#dellAllCart').click(function (e) {
			var url = WEB_ROOT + '/xoa-gio-hang.html';
			var delAll = jQuery(this).attr('data');
			var result = confirm("Bạn có muốn cập nhật đơn hàng không [OK]:Đồng ý [Cancel]:Bỏ qua ?");
			if (result) {
				jQuery.ajax({
					type: "POST",
					url: url,
					data: "delAll=" + encodeURI(delAll),
					success: function (data) {
						if (data != '') {
							window.location.reload();
						}
					}
				});
			}


			return true;
		});
	},
	sendCart: function () {
		jQuery("#submitPaymentOrder").click(function () {
			var name = jQuery('#txtFormPaymentCart input[name="txtName"]'),
				phone = jQuery('#txtFormPaymentCart input[name="txtMobile"]'),
				address = jQuery('#txtFormPaymentCart input[name="txtAddress"]');

			if (name.val() == '') {
				name.addClass('error').focus();
				return false;
			} else {
				name.removeClass('error');
			}
			if (phone.val() == '') {
				phone.addClass('error').focus();
				return false;
			} else {
				phone.removeClass('error');
			}

			if (address.val() == '') {
				address.addClass('error').focus();
				return false;
			} else {
				address.removeClass('error');
			}
		});
	}, changeStatusOrder: function (order_id, is_shop) {
		if (is_shop == is_shop_vip) {//shop vip mới có quyền này
			$('#img_loading_' + order_id).show();
			var statusOrder = jQuery("#order_status_id_" + order_id).val();
			$.ajax({
				type: "post",
				url: WEB_ROOT + '/shop/changeStatusOrder',
				data: {order_id: order_id, is_shop: is_shop, statusOrder: statusOrder},
				dataType: 'json',
				success: function (res) {
					$('#img_loading_' + product_id).hide();
					if (res.isIntOk == 1) {
						alert('Bạn thực hiện thành công!');
					} else {
						alert('Không thể thực hiện thao tác.');
					}
				}
			});
		} else {
			alert("Xin lỗi! Shop VIP mới có chức năng này");
		}
	},
}

jQuery(".loadInfoPhone").click(function(){
	var phone = jQuery('#txtFormPaymentCart input[name="txtMobile"]');
	if(phone.val() == ''){
		phone.addClass('error').focus();
		return false;
	}else{
		var url = WEB_ROOT + '/load-info-customer-shop.html';
		jQuery.ajax({
			type: "POST",
			url: url,
			data: "phone="+encodeURI(phone.val()),
			success: function(data){
				if(data != ''){
					var dataLoad = jQuery.parseJSON(data);
					jQuery('#txtName').val(dataLoad.txtName);
					jQuery('#txtEmail').val(dataLoad.txtEmail);
					jQuery('#txtAddress').val(dataLoad.txtAddress);
				}
				return true;
			}
		});
	}
});

