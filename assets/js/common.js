jQuery(document).ready(function($) {
    CHECKALL_ITEM.init();
    //hover view anh
    jQuery(".imge_hover").mouseover(function() {
        var id = jQuery(this).attr('id');
        jQuery("#div_hover_" + id).show();
    });
    jQuery(".imge_hover").mouseout(function() {
        var id = jQuery(this).attr('id');
        jQuery("#div_hover_" + id).hide();
    });


});

var Common = {
    uploadMultipleImages: function(type) {
        jQuery('#sys_PopupUploadImg').modal('show');
        jQuery('.ajax-upload-dragdrop').remove();
        var urlAjaxUpload = WEB_ROOT+'';
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
                    var checked_img_pro = "<div class='clear'></div><input type='radio' id='chẹcked_image_"+dataResult.info.id_key+"' name='chẹcked_image' value='"+dataResult.info.id_key+"' onclick='Common.checkedImage(\""+dataResult.info.name_img+"\",\"" + dataResult.info.id_key + "\")'><label for='chẹcked_image_"+dataResult.info.id_key+"' style='font-weight:normal'>Ảnh đại diện</label><br/>";
                    if( type == 2){
                        var checked_img_pro = checked_img_pro + "<input type='radio' id='chẹcked_image_hover"+dataResult.info.id_key+"' name='chẹcked_image_hover' value='"+dataResult.info.id_key+"' onclick='Common.checkedImageHover(\""+dataResult.info.name_img+"\",\"" + dataResult.info.id_key + "\")'><label for='chẹcked_image_hover"+dataResult.info.id_key+"' style='font-weight:normal'>Ảnh hover</label><br/>";
                    }
                    var delete_img = "<a href='javascript:void(0);' id='sys_delete_img_other_" + dataResult.info.id_key + "' onclick='Common.removeImage(\""+dataResult.info.id_key+"\",\""+dataResult.id_item+"\",\""+dataResult.info.name_img+"\",\""+type+"\")' >Xóa ảnh</a>";
                    var html= "<li id='sys_div_img_other_" + dataResult.info.id_key + "'>";
                    html += "<div class='block_img_upload' >";
                    html += "<img height='100' width='100' src='" + dataResult.info.src + "'/>";
                    html += "<input type='hidden' id='img_other_" + dataResult.info.id_key + "' class='sys_img_other' name='img_other[]' value='" + dataResult.info.name_img + "'/>";
                    html += checked_img_pro;
                    html += delete_img;
                    html +="</div></li>";
                    jQuery('#sys_drag_sort').append(html);
                    //jQuery('#sys_PopupImgOtherInsertContent #div_image').html('');
                    Common.getInsertImageContent(type);

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

    /**
     * Upload banner quảng cáo
     */
    uploadBannerAdvanced: function(type) {
        jQuery('#sys_PopupUploadImgOtherPro').modal('show');
        jQuery('.ajax-upload-dragdrop').remove();
        var urlAjaxUpload = '';
        var id_hiden = document.getElementById('id_hiden').value;

        var settings = {
            url: urlAjaxUpload,
            method: "POST",
            allowedTypes:"jpg,png,jpeg",
            fileName: "multipleFile",
            formData: {id: id_hiden,type: type},
            multiple: false,
            onSubmit:function(){
                jQuery( "#sys_show_button_upload").hide();
                jQuery("#status").html("<font color='green'>Đang upload...</font>");
            },
            onSuccess:function(files,xhr,data){
                dataResult = JSON.parse(xhr);
                alert(dataResult.intIsOK);
                //dataResult = xhr;
                if(dataResult.intIsOK === 1){
                    //gan lai id item cho id hiden: dung cho them moi, sua item
                    jQuery('#id_hiden').val(dataResult.id_item);
                    jQuery( "#sys_show_button_upload").show();

                    //show ảnh
                    var html = "<img height='300' width='400' src='" + dataResult.info.src + "'/>";
                    jQuery('#banner_image').val(dataResult.info.name_img);
                    jQuery('#sys_show_image_banner').html(html);

                    var img_new = dataResult.info.name_img;
                    if(img_new != ''){
                        jQuery("#img").attr('value', img_new);
                    }
                    //thanh cong
                    jQuery("#status").html("<font color='green'>Upload is success</font>");
                    setTimeout( "jQuery('.ajax-file-upload-statusbar').hide();",2000 );
                    setTimeout( "jQuery('#status').hide();",2000 );
                    setTimeout( "jQuery('#sys_PopupUploadImgOtherPro').modal('hide');",2500 );
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
            jQuery('#sys_delete_img_other_'+key).hide();

            //luu lại key anh chính
            var key_pri = document.getElementById('sys_key_image_primary').value;
            jQuery('#sys_delete_img_other_'+key_pri).show();
            jQuery('#sys_key_image_primary').val(key);

        }
    },

    checkedImageHover: function(nameImage,key){
        jQuery('#image_primary_hover').val(nameImage);
    },

    removeImage: function(key,id,nameImage,type){
        //product
        if(type == 2){
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
        }
        if (confirm('Bạn có chắc xóa ảnh này?')) {
            if(type == 2){//xóa ảnh sản phẩm
                var urlAjaxUpload = 'shop/removeImage';
            }
            jQuery.ajax({
                type: "POST",
                url: urlAjaxUpload,
                data: {id : id, nameImage : nameImage, type: type},
                responseType: 'json',
                success: function(data) {
                    dataResult = JSON.parse(data);
                    if(dataResult.intIsOK === 1){
                        jQuery('#sys_div_img_other_'+key).hide();
                        jQuery('#chẹcked_image_'+key).hide();//anh chinh
                        jQuery('#chẹcked_image_hover_'+key).val('');//anh hover
                        jQuery('#img_other_'+key).val('');//anh khac
                    }else{
                        jQuery('#sys_msg_return').html(data.msg);
                    }
                }
            });
        }
        jQuery('#sys_PopupImgOtherInsertContent #div_image').html('');
    },
    insertImageContent: function(type) {
        jQuery('#sys_PopupImgOtherInsertContent').modal('show');
        jQuery('.ajax-upload-dragdrop').remove();

        var urlAjaxUpload = '';
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
    actionImportProduct: function(key,id,nameImage,type){
        if (confirm('Bạn có chắc xóa ảnh này?')) {
            var urlAjaxUpload = '';
            jQuery.ajax({
                type: "POST",
                url: urlAjaxUpload,
                data: {id : id, nameImage : nameImage, type: type},
                responseType: 'json',
                success: function(data) {
                    dataResult = JSON.parse(data);
                    if(dataResult.intIsOK === 1){
                        jQuery('#sys_div_img_other_'+key).hide();
                        jQuery('#sys_img_other_'+key).val('');
                        jQuery('#sys_new_img_'+key).hide();
                    }else{
                        jQuery('#sys_msg_return').html(data.msg);
                    }
                }
            });
        }
    },
    getInsertImageContent: function(type) {
        var urlAjaxUpload = '';
        var id_hiden = document.getElementById('id_hiden').value;
        
        jQuery.ajax({
            type: "POST",
            url: urlAjaxUpload,
            data: "id_hiden=" + encodeURI(id_hiden) + "&type=" + encodeURI(type),
            success: function(data){
                dataResult = JSON.parse(data);
                if(dataResult.intIsOK === 1){
                    var imagePopup = '';
                    for(var i = 0; i < dataResult['item'].length; i++) {
                        imagePopup += "<span class='float_left image_insert_content'>";
                        var insert_img = "<a class='img_item' href='javascript:void(0);' onclick='insertImgContent(\""+dataResult['item'][i]+"\")' >";
                        imagePopup += insert_img;
                        imagePopup += "<img width='80' height=80 src='" + dataResult['item'][i] + "'/> </a>";
                    }
                    jQuery('#sys_PopupImgOtherInsertContent #div_image').append(imagePopup);
                }
            }
        });
    },
    showImagesProduct: function(product_id) {
        jQuery('#sys_PopupShowImagesProductId_'+product_id).modal('show');
    }
};//class

CHECKALL_ITEM = {
    init:function(){
        jQuery("input#checkAll").click(function(){
            var checkedStatus = this.checked;
            jQuery("input.checkItem").each(function(){
                this.checked = checkedStatus;
            });
        });
    },
    check_all:function(strs){
        if(strs != ''){
            jQuery("input.all_" + strs).click(function(){
                var checkedStatus = this.checked;
                jQuery("input.item_" + strs).each(function(){
                    this.checked = checkedStatus;
                });
            });
        }
    },
}