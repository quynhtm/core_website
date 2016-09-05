<?php

/**
 * Created by PhpStorm.
 * User: QuynhTM
 */
class AjaxCommonController extends BaseAdminController
{
    function uploadImage() {
        $id_hiden = Request::get('id', 0);
        $type = Request::get('type', 1);
        $dataImg = $_FILES["multipleFile"];
        $aryData = array();
        $aryData['intIsOK'] = -1;
        $aryData['msg'] = "Data not exists!";
        switch( $type ){
            case 1://img news
                $aryData = $this->uploadImageToFolder($dataImg, $id_hiden, CGlobal::FOLDER_NEWS, $type);
                break;
            case 2://img Product
                $aryData = $this->uploadImageToFolder($dataImg, $id_hiden, CGlobal::FOLDER_PRODUCT, $type);
                break;
            default:
                break;
        }
        echo json_encode($aryData);
        exit();
    }

    function uploadImageToFolder($dataImg, $id_hiden, $folder, $type){
        $aryData = array();
        $aryData['intIsOK'] = -1;
        $aryData['msg'] = "Upload Img!";
        $item_id = 0; // id doi tuong dang upload
        if (!empty($dataImg)) {
            if($id_hiden == 0){
                switch( $type ){
                    case 1://img news
                        $new_row['news_create'] = time();
                        $new_row['news_status'] = CGlobal::IMAGE_ERROR;
                        $item_id = News::addData($new_row);
                        break;
                    case 2://img Product
                        $this->user_shop = UserShop::user_login();
                        if(sizeof($this->user_shop) > 0){
                            $new_row['time_created'] = time();
                            $new_row['product_status'] = CGlobal::IMAGE_ERROR;
                            $new_row['user_shop_id'] = $this->user_shop->shop_id;
                            $new_row['user_shop_name'] = $this->user_shop->user_shop_name;
                            $new_row['is_shop'] = $this->user_shop->is_shop;
                            $new_row['shop_province'] = $this->user_shop->shop_province;
                            $item_id = Product::addData($new_row);
                        }
                        break;
                    default:
                        break;
                }
            }elseif($id_hiden > 0){
                $item_id = $id_hiden;
            }

            if($item_id > 0){
                $aryError = $tmpImg = array();
                $file_name = Upload::uploadFile('multipleFile',
                    $_file_ext = 'jpg,jpeg,png,gif',
                    $_max_file_size = 10*1024*1024,
                    $_folder = $folder.'/'.$item_id);

                if ($file_name != '' && empty($aryError)) {
                    $tmpImg['name_img'] = $file_name;
                    $tmpImg['id_key'] = rand(10000, 99999);
                    $url_thumb = ThumbImg::getImageThumb($folder, $item_id, $file_name, CGlobal::sizeImage_100);
                    $tmpImg['src'] = $url_thumb;

                    //cap nhat DB de quan ly cac file anh
                    if( $type == 2 ){
                        //img Product
                        $this->user_shop = UserShop::user_login();
                        if(sizeof($this->user_shop) > 0){
                            //get mang anh other
                            $shop_id = $this->user_shop->shop_id;
                            $inforPro = Product::getProductByShopId($shop_id,$item_id);
                            if($inforPro){
                                $arrImagOther = unserialize($inforPro->product_image_other);
                                $arrImagOther[] = $file_name;//gan anh vua upload
                                $proUpdate['product_image_other'] = serialize($arrImagOther);
                                Product::updateData($item_id,$proUpdate);
                            }
                        }
                    }
                }
                $aryData['intIsOK'] = 1;
                $aryData['id_item'] = $item_id;
                $aryData['info'] = $tmpImg;
            }
        }
        echo json_encode($aryData);
        die();
    }

    function get_image_insert_content(){
        $id_hiden = FunctionLib::getIntParam('id_hiden', 0);
        $type = FunctionLib::getIntParam('type', 1);
        $aryData = array();
        $aryData['intIsOK'] = -1;
        $aryData['msg'] = "Data not exists!";
        if($id_hiden > 0){
            switch( $type ){
                case 1://img news
                    $aryData = $this->getImgContent($id_hiden, TABLE_NEWS, FOLDER_NEWS, 'news_image_other', self::$primary_key_news);
                    break;
                case 2 ://img product
                    $aryData = $this->getImgContent($id_hiden, TABLE_PRODUCT, FOLDER_PRODUCT, 'product_image_other', self::$primary_key_product);
                    break;
                default:
                    break;
            }
        }
        echo json_encode($aryData);
        exit();
    }
    function getImgContent($id_hiden, $table_action, $folder, $field_img_other='', $primary_key){
        global $base_url;

        $listImageTempOther = DB::getItemById($table_action, $primary_key, array($field_img_other), $id_hiden);
        if(!empty($listImageTempOther)){
            $aryTempImages = ($listImageTempOther[0]->$field_img_other !='')? unserialize($listImageTempOther[0]->$field_img_other): array();

            $aryData = array();
            if(!empty($aryTempImages)){
                foreach($aryTempImages as $k => $item){
                    $aryData['item'][$k] = FunctionLib::getThumbImage($item,$id_hiden,$folder,700,700);
                }
            }
            $aryData['intIsOK'] = 1;
            $aryData['msg'] = "Data exists!";
            return $aryData;
        }
    }

}