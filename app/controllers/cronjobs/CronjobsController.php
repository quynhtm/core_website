<?php

/**
 * Created by PhpStorm.
 * User: QuynhTM
 */
class CronjobsController extends BaseSiteController
{
    private  $sizeImageShowUpload = CGlobal::sizeImage_100;
    function runJobs() {
        $action = Request::get('action', 0);//kiểu chạy joib
        switch( $action ){
            case 1://cập nhật link ảnh trong sản phẩm
                $this->updateLinkInContent($action);
                break;
            default:
                break;
        }
        echo 'Bạn chưa chọn kiểu action';
    }

    public function updateLinkInContent($type = 1){
        if($type > 0){
            $dataSearch['product_id'] = 334;
            $dataSearch['field_get'] = 'product_id,product_content';
            $data = Product::searchByCondition($dataSearch,1,0,$total);
            //FunctionLib::debug($data);
            if($data){
                foreach($data as $k=>$product){
                    $url_old = 'http://shopcuatui.com.vn/image.php?type_dir=product&id='.$product->product_id.'&width=700&height=700&image=';
                    //http://shopcuatui.com.vn/uploads/product/334/03-28-34-11-05-2016-image.jpeg';
                    echo $url_old.'<br/>';
                    echo str_replace($url_old,"",$product->product_content); die;
                    $dataUpdate['product_content'] = str_replace($url_old,"",$product->product_content);
                    Product::updateData($product->product_id,$dataUpdate);
                }
            }
            echo 'đã cập nhật xong';
        }
    }

}