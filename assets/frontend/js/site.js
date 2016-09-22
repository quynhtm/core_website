$(document).ready(function($){
	SITE.change_img();
	SITE.show_tab_category_home();
	SITE.hover_img_detail_product();
	SITE.tab_select_detail_product();
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
	uploadOneImages: function(type) {
		jQuery('#sys_PopupUploadImg').modal('show');
		jQuery('.ajax-upload-dragdrop').remove();
		var id_hiden = document.getElementById('id_hiden').value;

		var settings = {
			url: WEB_ROOT + '/ajax/uploadImage',
			method: "POST",
			allowedTypes:"jpg,png,jpeg",
			fileName: "multipleFile",
			formData: {id: id_hiden,type: type},
			multiple: false,//up 1 anh
			onSubmit:function(){
				jQuery( "#sys_show_button_upload").hide();
				jQuery("#status").html("<font color='green'>Đang upload...</font>");
			},
			onSuccess:function(files,xhr,data){
				dataResult = JSON.parse(xhr);
				if(dataResult.intIsOK === 1){
					//gan lai id item cho id hiden: dung cho them moi, sua item
					jQuery('#id_hiden').val(dataResult.id_item);
					jQuery('#image_primary').val(dataResult.info.name_img);
					jQuery( "#sys_show_button_upload").show();

					var html= "";
					html += "<img src='" + dataResult.info.src + "'/>";
					jQuery('#block_img_upload').html(html);

					//thanh cong
					jQuery("#status").html("<font color='green'>Upload is success</font>");
					setTimeout( "jQuery('.ajax-file-upload-statusbar').hide();",1000 );
					setTimeout( "jQuery('#status').hide();",1000 );
					setTimeout( "jQuery('#sys_PopupUploadImg').modal('hide');",1000 );
				}
			},
			onError: function(files,status,errMsg){
				jQuery("#status").html("<font color='red'>Upload is Failed</font>");
			}
		}
		jQuery("#sys_mulitplefileuploader").uploadFile(settings);
	},
	/*******************************************************************************************
	 *Liên quan tới sản phẩm
	 *****************************************************************************************
	 */
	insertImageContent: function(type) {
		jQuery('#sys_PopupImgOtherInsertContent').modal('show');
		jQuery('.ajax-upload-dragdrop').remove();

		var urlAjaxUpload = '/ajax?act=upload_image&code=upload_image_insert_content';
		var id_hiden = document.getElementById('id_hiden').value;
		var settings = {
			url: urlAjaxUpload,
			method: "POST",
			allowedTypes:"jpg,png,jpeg",
			fileName: "multipleFile",
			formData: {id: id_hiden,type: type},
			multiple: (id_hiden==0)? false: true,
			onSuccess:function(files,xhr,data){
				dataResult = JSON.parse(xhr);
				if(dataResult.intIsOK === 1){
					var imagePopup = "<span class='float_left image_insert_content'>";
					var insert_img = "<a class='img_item' href='javascript:void(0);' onclick='insertImgContent(\""+dataResult.info.src_700+"\")' >";
					imagePopup += insert_img;
					imagePopup += "<img width='80' height=80 src='" + dataResult.info.src + "'/> </a>";
					jQuery('#div_image').append(imagePopup);

					//jQuery('#sys_PopupImgOtherInsertContent').modal('hide');
					//thanh cong
					jQuery("#status").html("<font color='green'>Upload is success</font>");
					setTimeout( "jQuery('.ajax-file-upload-statusbar').hide();",5000 );
					setTimeout( "jQuery('#status').hide();",5000 );
				}
			},
			onError: function(files,status,errMsg){
				jQuery("#status").html("<font color='red'>Upload is Failed</font>");
			}
		}
		jQuery("#sys_mulitplefileuploader_insertContent").uploadFile(settings);
	},
	uploadImagesProduct: function(type) {
		jQuery('#sys_PopupUploadImg').modal('show');
		jQuery('.ajax-upload-dragdrop').remove();
		var id_hiden = document.getElementById('id_hiden').value;

		var settings = {
			url: WEB_ROOT + '/ajax/uploadImage',
			method: "POST",
			allowedTypes:"jpg,png,jpeg",
			fileName: "multipleFile",
			formData: {id: id_hiden,type: type},
			multiple: (id_hiden==0)? false: true,
			onSubmit:function(){
				jQuery( "#sys_show_button_upload").hide();
				jQuery("#status").html("<font color='green'>Đang upload...</font>");
			},
			onSuccess:function(files,xhr,data){
				dataResult = JSON.parse(xhr);
				if(dataResult.intIsOK === 1){
					//gan lai id item cho id hiden: dung cho them moi, sua item
					jQuery('#id_hiden').val(dataResult.id_item);
					jQuery( "#sys_show_button_upload").show();

					//add vao list sản sản phẩm khác
					var checked_img_pro = "<div class='clear'></div><input type='radio' id='checked_image_"+dataResult.info.id_key+"' name='checked_image_' value='"+dataResult.info.id_key+"' onclick='SITE.checkedImage(\""+dataResult.info.name_img+"\",\"" + dataResult.info.id_key + "\")'><label for='checked_image_"+dataResult.info.id_key+"' style='font-weight:normal'>Ảnh đại diện</label><br/>";
					if( type == 2){
						var checked_img_pro = checked_img_pro + "<input type='radio' id='checked_image_hover"+dataResult.info.id_key+"' name='checked_image_hover' value='"+dataResult.info.id_key+"' onclick='SITE.checkedImageHover(\""+dataResult.info.name_img+"\",\"" + dataResult.info.id_key + "\")'><label for='checked_image_hover"+dataResult.info.id_key+"' style='font-weight:normal'>Ảnh hover</label><br/>";
					}
					var delete_img = "<a href='javascript:void(0);' id='sys_delete_img_other_" + dataResult.info.id_key + "' onclick='SITE.removeImage(\""+dataResult.info.id_key+"\",\""+dataResult.id_item+"\",\""+dataResult.info.name_img+"\")' >XÃ³a áº£nh</a>";
					var html= "<li id='sys_div_img_other_" + dataResult.info.id_key + "'>";
					html += "<div class='block_img_upload' >";
					html += "<img height='100' width='100' src='" + dataResult.info.src + "'/>";
					html += "<input type='hidden' id='img_other_" + dataResult.info.id_key + "' class='sys_img_other' name='img_other[]' value='" + dataResult.info.name_img + "'/>";
					html += checked_img_pro;
					html += delete_img;
					html +="</div></li>";
					jQuery('#sys_drag_sort').append(html);

					//thanh cong
					jQuery("#status").html("<font color='green'>Upload is success</font>");
					setTimeout( "jQuery('.ajax-file-upload-statusbar').hide();",1000 );
					setTimeout( "jQuery('#status').hide();",1000 );
					setTimeout( "jQuery('#sys_PopupUploadImg').modal('hide');",1000 );
				}
			},
			onError: function(files,status,errMsg){
				jQuery("#status").html("<font color='red'>Upload is Failed</font>");
			}
		}
		jQuery("#sys_mulitplefileuploader").uploadFile(settings);
	},
	checkedImage: function(nameImage,key){
		if (confirm('Bạn có muốn chọn ảnh này làm ảnh đại diện?')) {
			jQuery('#image_primary').val(nameImage);
		}
	},
	checkedImageHover: function(nameImage,key){
		jQuery('#image_primary_hover').val(nameImage);
	},
	setOnTopProduct: function(product_id,is_shop) {
		if(is_shop == is_shop_vip){//shop vip mới có quyền này
			$('#img_loading_'+product_id).show();
			$.ajax({
				type: "post",
				url: WEB_ROOT+'/shop/setOntop',
				data: {product_id : product_id,is_shop : is_shop},
				dataType: 'json',
				success: function(res) {
					$('#img_loading_'+product_id).hide();
					if(res.isIntOk == 1){
						alert('Bạn thực hiện thành công!');
						//window.location.reload();
					}else{
						alert('Không thể thực hiện thao tác.');
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
				url: WEB_ROOT+'/shop/deleteProduct',
				data: {product_id : product_id},
				dataType: 'json',
				success: function(res) {
					$('#img_loading_'+product_id).hide();
					if(res.isIntOk == 1){
						alert('Bạn đã thực hiện thành công');
						window.location.reload();
					}else{
						alert('Không thể thực hiện thao tác.');
					}
				}
			});
		}
	},
	deleteBanner: function(banner_id) {
		if(confirm('Bạn có muốn xóa banner này không?')) {
			$('#img_loading_'+banner_id).show();
			$.ajax({
				type: "post",
				url: WEB_ROOT+'/shop/deleteBanner',
				data: {banner_id : banner_id},
				dataType: 'json',
				success: function(res) {
					$('#img_loading_'+banner_id).hide();
					if(res.isIntOk == 1){
						alert('Bạn đã thực hiện thành công');
						window.location.reload();
					}else{
						alert('Không thể thực hiện thao tác.');
					}
				}
			});
		}
	},
	deleteProvider: function(provider_id) {
		if(confirm('Bạn có muốn xóa nhà cung cấp này không?')) {
			$('#img_loading_'+provider_id).show();
			$.ajax({
				type: "post",
				url: WEB_ROOT+'/shop/deleteProvider',
				data: {provider_id : provider_id},
				dataType: 'json',
				success: function(res) {
					$('#img_loading_'+provider_id).hide();
					if(res.isIntOk == 1){
						alert('Bạn đã thực hiện thành công');
						window.location.reload();
					}else{
						alert('Không thể thực hiện thao tác.');
					}
				}
			});
		}
	},
	removeImage: function(key,id,nameImage){
		//product
		if(jQuery("#image_primary_hover").length ){
			var img_hover = jQuery("#image_primary_hover").val();
			if(img_hover == nameImage){
				jQuery("#image_primary_hover").val('');
			}
		}
		if(jQuery("#image_primary").length ){
			var image_primary = jQuery("#image_primary").val();
			if(image_primary == nameImage){
				jQuery("#image_primary").val('');
			}
		}
		if (confirm('Bạn có chắc chắn xóa ảnh này?')) {
			jQuery.ajax({
				type: "POST",
				url: WEB_ROOT+'/shop/removeImage',
				data: {id : id, nameImage : nameImage},
				responseType: 'json',
				success: function(data) {
					if(data.intIsOK === 1){
						jQuery('#sys_div_img_other_'+key).hide();
						jQuery('#checked_image_'+key).hide();//anh chinh
						jQuery('#checked_image_hover_'+key).val('');//anh hover
						jQuery('#img_other_'+key).val('');//anh khac
					}else{
						jQuery('#sys_msg_return').html(data.msg);
					}
				}
			});
		}
		jQuery('#sys_PopupImgOtherInsertContent #div_image').html('');
	},
	//Duy them cac tab trang chu
	show_tab_category_home:function(){
		jQuery('.parent-cate a').click(function(){
			var dataCatId = jQuery(this).attr('datacatid');
			var dataType = jQuery(this).attr('datatype');
			var parent = jQuery(this).parents('.line-box-cat');
			var tabShow = parent.find('ul.data-tab.tab-'+ dataCatId).length;
			
			jQuery('.parent-cate').removeClass('act');
			var pr_a = jQuery(this).parents('.parent-cate');
			pr_a.addClass('act');
			
			if(parent.hasClass('vip')){
				jQuery('.line-box-cat.normal .parent-cate:first-child').addClass('act');
			}else{
				jQuery('.line-box-cat.vip .parent-cate:first-child').addClass('act');
			}
			
			if(dataCatId > 0){
				parent.find('ul.data-tab').hide();
				jQuery('body').append('<div class="loading"></div>');
				if(tabShow == 0){
					//ajax
					jQuery.ajax({
						type: "POST",
						url: WEB_ROOT + '/load-product-with-category.html',
						data: "dataCatId=" + encodeURI(dataCatId) + "&dataType=" + encodeURI(dataType),
						success: function(data){
							if(data != ''){
								parent.find('ul.data-tab').hide();
								parent.find('.content-list-item').append(data);
								jQuery('body').find('div.loading').remove();
							}else{
								return false;
							}
							tabShow = 1;
						}
					});
				}else{
					//show
					parent.find('ul.data-tab').hide();
					parent.find('ul.data-tab.tab-'+ dataCatId).show();
					jQuery('body').find('div.loading').remove();
				}
			}else{
				parent.find('ul.data-tab').hide();
				parent.find('ul.tab-0').show();
			}
		});
	},
	hover_img_detail_product:function(){
		jQuery('.list-thumb-img .slick-slide').hover(function(){
			var path_hover = jQuery(this).attr('data');
			jQuery('.list-thumb-img .item-slick').removeClass('act');
			jQuery(this).addClass('act');
			jQuery('.max-thumb-img img').attr('src', path_hover);
			//jQuery('.max-thumb-img a').attr('href', path_hover);
		});
	},
	tab_select_detail_product:function(){
		jQuery(".left-bottom-content-view .tab li").click(function(){
			jQuery(".left-bottom-content-view .tab li").removeClass("act");
			jQuery(this).addClass('act');
			var datatab = jQuery(this).attr('data-tab');
			jQuery('.left-bottom-content-view .show-tab').removeClass('act');
			jQuery('.left-bottom-content-view .show-tab-'+datatab).addClass('act');
		});
	}
}