$(document).ready(function($){
	SITE.change_img();
	SITE.show_tab_category_home();
	SITE.hover_img_detail_product();
	SITE.tab_select_detail_product();
	SITE.fixed_category_shop();
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
					html +='<br/><a href="javascript: void(0);" onclick="Common.removeImageItem('+dataResult.id_item.trim()+',\''+dataResult.info.name_img.trim()+'\','+type+');">Xóa ảnh</a>';
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
	insertImageContentProduct: function() {
		var product_id = document.getElementById('id_hiden').value;
		$.ajax({
			type: "post",
			url: WEB_ROOT+'/shop/getImageProductOther',
			data: {product_id : product_id},
			dataType: 'json',
			success: function(res) {
				$('#img_loading_'+product_id).hide();
				if(res.isIntOk == 1){
					jQuery('#sys_PopupImgOtherInsertContent').modal('show');
					var rs = res.dataImage;
					for( k in rs ) {
						var clickInsert = "<a href='javascript:void(0);' class='img_item' onclick='insertImgContent(\"" + rs[k].src_thumb_content + "\",\"" + rs[k].product_name + "\")'>";
						var html ='<span class="float_left image_insert_content" style="margin:5px;">';
						html += clickInsert;
						html += "<img src='" + rs[k].src_img_other + "' width='100' height='100'/>";
						html +="</a>";
						html +="</span>";
						$('#div_image_insert_content').append(html);
					}
				}else{
					alert('Không thể thực hiện thao tác.');
				}
			}
		});
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
					var delete_img = "<a href='javascript:void(0);' id='sys_delete_img_other_" + dataResult.info.id_key + "' onclick='SITE.removeImage(\""+dataResult.info.id_key+"\",\""+dataResult.id_item+"\",\""+dataResult.info.name_img+"\")' >Xóa ảnh</a>";
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
		
		jQuery('a[href*=#]:not([href=#])').click(function() {
		    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
		      var target = jQuery(this.hash);
		      target = target.length ? target : jQuery('[id=' + this.hash.slice(1) +']');
		      if (target.length) {
		        jQuery('html,body').animate({
		          scrollTop: (target.offset().top - 20)
		        }, 1000);
		        if(target.selector != ''){
		        	jQuery('.left-bottom-content-view ul.tab li').removeClass('act');
		        	jQuery(target.selector).addClass('act');
		        	var datatab = jQuery(target.selector).attr('data-tab');
		        	jQuery('.content-bottom-content-view .show-tab').removeClass('act');
		        	jQuery('.content-bottom-content-view .show-tab-'+datatab).addClass('act');
		        }
		        return false;
		      }
		    }
		  });
	},
	fixed_category_shop:function(){
		if(jQuery('.left-category-shop').find('.wrapp-category-menu')){
			var menu_site = jQuery('.wrapp-category-menu'),
				pos_menu  = menu_site.offset();
			var menu_site1 = jQuery('.shopInfo'),
				pos_menu1  = menu_site1.offset();
				shopInfo = jQuery('.shopInfo').height();
			
			var box_footer = jQuery('#footer'),
				pos_footer  = box_footer.offset();
				footer = jQuery('#footer').height();
				
			jQuery(window).scroll(function(){
				if(jQuery(this).scrollTop() >=  pos_menu1.top + shopInfo + 5){
					jQuery(".wrapp-category-menu").addClass('fixed');
				}else if(jQuery(this).scrollTop() <= pos_menu.top){
					jQuery(".wrapp-category-menu").removeClass('fixed');
				}
				if(jQuery(this).scrollTop() + footer >= pos_footer.top){
					jQuery(".wrapp-category-menu").removeClass('fixed');
				}
			});
		}
	},
}