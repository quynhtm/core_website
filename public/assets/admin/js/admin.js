var Admin = {
    deleteItem: function(id,type) {
        if(confirm('Bạn có muốn xóa Item này không?')) {
            $('#img_loading_'+id).show();

            if(type == 1){ //xoa NCC
               var url_ajax = WEB_ROOT + '/admin/providers/deleteItem';
            }
            else if(type == 2){ //xoa san pham
                var url_ajax = WEB_ROOT + '/admin/product/deleteItem';
            }
            else if(type == 3){ //xoa danh mục
                var url_ajax = WEB_ROOT + '/admin/categories/deleteItem';
            }
            else if(type == 4){ //xoa nhan vien
                var url_ajax = WEB_ROOT + '/admin/personnel/deleteItem';
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