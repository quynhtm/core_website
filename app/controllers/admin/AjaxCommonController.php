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

                    $url_thumb = ThumbImg::thumbBaseNormal($folder, $item_id, $file_name, 100, 100, '', true, true);
                    $tmpImg['src'] = $url_thumb;
                }
                $aryData['intIsOK'] = 1;
                $aryData['id_item'] = $item_id;
                $aryData['info'] = $tmpImg;
            }
        }
        echo json_encode($aryData);
        die();
    }

    function remove_image(){
        $id = FunctionLib::getIntParam('id', 0);
        $nameImage = FunctionLib::getParam('nameImage', '');
        $type = FunctionLib::getIntParam('type', 1);

        $aryData = array();
        $aryData['intIsOK'] = -1;
        $aryData['msg'] = "Remove Img!";
        $aryData['nameImage'] = $nameImage;
        switch( $type ){
            case 1://anh tin tuc
                $folder_image = 'uploads/'.FOLDER_NEWS;
                $table_action = TABLE_NEWS;
                if($id > 0 && $nameImage != '' && $folder_image != ''){
                    $delete_action = $this->delete_image_item($table_action, self::$primary_key_news, $id, 'news_image_other', $nameImage, $folder_image);
                    if($delete_action == 1){
                        $aryData['intIsOK'] = 1;
                        $aryData['msg'] = "Remove Img!";
                    }
                }
                break;
            case 2://anh san pham
                $folder_image = 'uploads/'.FOLDER_PRODUCT;
                $table_action = TABLE_PRODUCT;
                if($id > 0 && $nameImage != '' && $folder_image != ''){
                    $delete_action = $this->delete_image_item($table_action, self::$primary_key_product, $id, 'product_image_other', $nameImage, $folder_image);
                    if($delete_action == 1){
                        $aryData['intIsOK'] = 1;
                    }
                }
                break;
            default:
                $folder_image = '';
                break;
        }
        echo json_encode($aryData);
        exit();
    }
    function delete_image_item($table_action, $primary_key, $id, $field = '', $nameImage, $folder_image){
        $delete_action = 0;
        $aryImages  = array();
        //get img in DB and remove it
        if($field != ''){
            $listImageOther = DB::getItemById($table_action, $primary_key, array($field), $id);
            $aryImages = unserialize($listImageOther[0]->$field);
        }
        if(is_array($aryImages) && count($aryImages) > 0) {
            foreach ($aryImages as $k => $v) {
                if($v === $nameImage){
                    $this->unlinkFileAndFolder($nameImage, $id, $folder_image, true);
                    unset($aryImages[$k]);
                    if(!empty($aryImages)){
                        $aryImages = serialize($aryImages);
                    }else{
                        $aryImages = '';
                    }
                    DB::updateId($table_action, $primary_key, array($field=>$aryImages), $id);
                    $delete_action = 1;
                    break;
                }
            }
        }
        //xoa khi chua update vao db, anh moi up load
        if($delete_action == 0){
            $this->unlinkFileAndFolder($nameImage, $id, $folder_image, true);
            $delete_action = 1;
        }
        return $delete_action;
    }
    function unlinkFileAndFolder($file_name = '', $id = 0, $folder = '', $is_delDir = 0){

        if($file_name != '') {
            //Xoa anh goc
            $paths = '';
            if($folder != '' && $id >0){
                $path = DRUPAL_ROOT.'/'.$folder.'/'.$id;
            }

            if($file_name != ''){
                if($path != ''){
                    if(is_file($path.'/'.$file_name)){
                        @unlink($path.'/'.$file_name);
                    }
                }
            }
            //Xoa thu muc
            if($is_delDir) {
                if($path != ''){
                    if(is_dir($path)) {
                        @rmdir($path);
                    }
                }
            }
        }
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