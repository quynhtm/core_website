var Admin = {
    deleteItem: function(id,type) {
        if(confirm('Bạn có muốn xóa Item này không?')) {
            $('#img_loading_'+id).show();
            var url_ajax = '';
            if(type == 1){ //xoa tin tức
                url_ajax = 'deleteNews';
            }
            $.ajax({
                type: "post",
                url: url_ajax,
                data: {id : id},
                dataType: 'json',
                success: function(res) {
                    $('#img_loading_'+id).hide();
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
    updateStatusItem: function(id,status,type) {
        if(confirm('Bạn có muốn thay đổi trạng thái Item này không?')) {
            $('#img_loading_'+id).show();
            if(type == 1){ //cap nhat danh muc
               var url_ajax = WEB_ROOT + '/admin/category/updateStatusCategory';
            }

            $.ajax({
                type: "post",
                url: url_ajax,
                data: {id : id,status : status},
                dataType: 'json',
                success: function(res) {
                    $('#img_loading_'+id).hide();
                    if(res.isIntOk == 1){
                        window.location.reload();
                    }else{
                        alert('Không thể thực hiện được thao tác.');
                    }
                }
            });
        }
    },
    changeOptionPersonnel: function(){
        var personnel_check_creater = $('#personnel_check_creater').val();
        if(parseInt(personnel_check_creater) == 1){
            $('#show_personnel_user_name').show();
        }else{
            $('#show_personnel_user_name').hide();
        }
    },
    updateCategoryCustomer: function(customer_id,category_id){
        $('#img_loading_'+category_id).show();
        var category_price_discount = $('#category_price_discount_id_'+category_id).val();
        var category_price_hide_discount = $('#category_price_hide_discount_id_'+category_id).val();
        $.ajax({
            type: "post",
            url: WEB_ROOT + '/admin/discountCustomers/updateCategory',
            data: {customer_id : customer_id, category_id:category_id, category_price_discount : category_price_discount, category_price_hide_discount : category_price_hide_discount},
            dataType: 'json',
            success: function(res) {
                $('#img_loading_'+category_id).hide();
                if(res.isIntOk == 1){
                    /*alert('Bạn đã thực hiện thành công');
                    window.location.reload();*/
                }else{
                    alert('Không thể thực hiện được thao tác.');
                }
            }
        });
    },
    updateProductCustomer: function(customer_id,product_id){
        $('#img_loading_'+product_id).show();
        var product_price_discount = $('#product_price_discount_id_'+product_id).val();
        $.ajax({
            type: "post",
            url: WEB_ROOT + '/admin/discountCustomers/updateProduct',
            data: {customer_id : customer_id, product_id:product_id, product_price_discount : product_price_discount},
            dataType: 'json',
            success: function(res) {
                $('#img_loading_'+product_id).hide();
                if(res.isIntOk == 1){
                    /*alert('Bạn đã thực hiện thành công');
                    window.location.reload();*/
                }else{
                    alert('Không thể thực hiện được thao tác.');
                }
            }
        });
    },

    uploadImagesCategory: function() {
        $('#sys_PopupUploadImg').modal('show');
        $('.ajax-upload-dragdrop').remove();
        var id_hiden = $('#id_hiden').val();
        var settings = {
            url: WEB_ROOT + '/admin/categories/uploadImage',
            method: "POST",
            allowedTypes:"jpg,png,jpeg",
            fileName: "multipleFile",
            formData: {id: id_hiden},
            multiple: false,
            onSuccess:function(files,xhr,data){
                if(xhr.intIsOK === 1){
                    $('#sys_PopupUploadImg').modal('hide');
                    //thanh cong
                    $("#status").html("<font color='green'>Upload is success</font>");
                    setTimeout( "jQuery('.ajax-file-upload-statusbar').hide();",5000 );
                    setTimeout( "jQuery('#status').hide();",5000 );
                }
            },
            onError: function(files,status,errMsg){
                $("#status").html("<font color='red'>Upload is Failed</font>");
            }
        }
        $("#sys_mulitplefileuploader").uploadFile(settings);
    },
}