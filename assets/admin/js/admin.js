$(document).ready(function() {
    $("#checkAll").click(function () {
        $(".check").prop('checked', $(this).prop('checked'));
    });
});
var Admin = {
    deleteItem: function(id,type) {
        if(confirm('Bạn có muốn xóa Item này không?')) {
            $('#img_loading_'+id).show();
            var url_ajax = '';
            if(type == 1){ //xoa tin tức
                url_ajax = 'deleteNews';
            }else if(type == 2){
                url_ajax = 'deleteUserShop';
            }else if(type == 3){
                url_ajax = 'deleteBanner';
            }else if(type == 4){
                url_ajax = 'deleteProvider';
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
    removeAllItems: function(type){
        var dataId = [];
        var i = 0;
        $("input[name*='checkItems']").each(function () {
            if ($(this).is(":checked")) {
                dataId[i] = $(this).val();
                i++;
            }
        });
        if(dataId.length == 0) {
            alert('Bạn chưa chọn items để thao tác.');
            return false;
        }
        var url_ajax = '';
        if(type == 1){ //xoa sản phẩm
            url_ajax = 'deleteMultiProduct';
        }
        if(url_ajax != ''){
            if(confirm('Bạn có muốn thực hiện thao tác này?')) {
                $('#img_loading_delete_all').show();
                $.ajax({
                    type: "post",
                    url: url_ajax,
                    data: {dataId: dataId},
                    dataType: 'json',
                    success: function (res) {
                        $('#img_loading_delete_all').hide();
                        if (res.isIntOk == 1) {
                            alert('Bạn đã thực hiện thành công');
                            window.location.reload();
                        } else {
                            alert('Không thể thực hiện được thao tác.');
                        }
                    }
                });
            }
        }
    },
    setStastusBlockProduct: function(){
        var dataId = [];
        var i = 0;
        $("input[name*='checkItems']").each(function () {
            if ($(this).is(":checked")) {
                dataId[i] = $(this).val();
                i++;
            }
        });
        if(dataId.length == 0) {
            alert('Bạn chưa chọn items để thao tác.');
            return false;
        }
        var valueInput = $('#product_status_update').val();
        if(parseInt(valueInput) == -1){
            alert('Bạn chưa chọn trạng thái để cập nhật.');
            return false;
        }
        var url_ajax = 'setStastusBlockProduct';

        if(url_ajax != '' && parseInt(valueInput) > -1){
            if(confirm('Bạn có muốn thực hiện thao tác này?')) {
                $('#img_loading_delete_all').show();
                $.ajax({
                    type: "post",
                    url: url_ajax,
                    data: {dataId: dataId, valueInput:valueInput},
                    dataType: 'json',
                    success: function (res) {
                        $('#img_loading_delete_all').hide();
                        if (res.isIntOk == 1) {
                            alert('Bạn đã thực hiện thành công');
                            window.location.reload();
                        } else {
                            alert('Không thể thực hiện được thao tác.');
                        }
                    }
                });
            }
        }
    },
    updateStatusItem: function(id,status,type) {
        if(confirm('Bạn có muốn thay đổi trạng thái Item này không?')) {
            $('#img_loading_'+id).show();
            if(type == 1){ //cap nhat danh muc
               var url_ajax = WEB_ROOT + '/admin/category/updateStatusCategory';
            }/*else if(type == 2){//user shop
                var url_ajax = WEB_ROOT + '/admin/userShop/updateStatusUserShop';
            }*/

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
    changeIsShop: function(is_shop, shop_id){
        if(is_shop > 0){
            $('#img_loading').show();
            $.ajax({
                type: "post",
                url: WEB_ROOT + '/admin/userShop/setIsShop',
                data: {shop_id : shop_id, is_shop:is_shop},
                dataType: 'json',
                success: function(res) {
                    $('#img_loading').hide();
                    if(res.isIntOk == 1){
                        alert('Bạn đã thực hiện thành công');
                    }else{
                        alert('Không thể thực hiện được thao tác.');
                    }
                }
            });
        }
    },
    changeStatusShop: function(shop_status, shop_id){
        if(shop_id > 0){
            $('#img_loading').show();
            $.ajax({
                type: "post",
                url: WEB_ROOT + '/admin/userShop/updateStatusUserShop',
                data: {shop_id : shop_id, shop_status:shop_status},
                dataType: 'json',
                success: function(res) {
                    $('#img_loading').hide();
                    if(res.isIntOk == 1){
                        alert('Bạn đã thực hiện thành công');
                    }else{
                        alert('Không thể thực hiện được thao tác.');
                    }
                }
            });
        }
    }
}