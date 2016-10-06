<?php

/**
 * Created by PhpStorm.
 * User: QuynhTM
 */
class AjaxCommonController extends BaseSiteController
{
    private  $sizeImageShowUpload = CGlobal::sizeImage_100;
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
            case 3://img banner
                $this->sizeImageShowUpload = CGlobal::sizeImage_300;
                $aryData = $this->uploadImageToFolder($dataImg, $id_hiden, CGlobal::FOLDER_BANNER, $type);
                break;
            case 4://img logo shop
                	$this->sizeImageShowUpload = CGlobal::sizeImage_300;
                	$aryData = $this->uploadImageToFolder($dataImg, $id_hiden, CGlobal::FOLDER_LOGO_SHOP, $type);
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
                        $user_shop = UserShop::user_login();
                        if(sizeof($user_shop) > 0){
                            $new_row['time_created'] = time();
                            $new_row['product_status'] = CGlobal::status_hide;
                            $new_row['user_shop_id'] = $user_shop->shop_id;
                            $new_row['user_shop_name'] = $user_shop->shop_name;
                            $new_row['is_shop'] = $user_shop->is_shop;
                            $new_row['shop_province'] = $user_shop->shop_province;
                            $item_id = Product::addData($new_row);

                            //cap nhat lai so l??t up san ph?m cho shop
                            $inforShop = UserShop::getByID($user_shop->shop_id);//lay du lieu moi nhat, ko lay session vi ko cap nhat dung
                            if(sizeof($inforShop) > 0){
                                $userShopUpdate['shop_up_product'] = $inforShop->shop_up_product + 1;
                                UserShop::updateData($inforShop->shop_id, $userShopUpdate);
                                $userShop2 = UserShop::getByID($inforShop->shop_id);
                                if($userShop2){
                                    Session::forget('user_shop');//xóa session
                                    Session::put('user_shop', $userShop2, 60*24);
                                }
                            }
                        }
                        break;
                    case 3://img banner
                        $new_row['banner_create_time'] = time();
                        $new_row['banner_status'] = CGlobal::IMAGE_ERROR;
                        $item_id = Banner::addData($new_row);
                        $sizeImageShowUpload = CGlobal::sizeImage_300;
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
                    $url_thumb = ThumbImg::getImageThumb($folder, $item_id, $file_name, $this->sizeImageShowUpload, '', true, CGlobal::type_thumb_image_banner, false);
                    $tmpImg['src'] = $url_thumb;
					//Cap nhat DB de quan ly file anh new
                    if($type == 1 ){
                    	//img news
                   		$inforNews = News::getNewByID($item_id);
                    	if($inforNews){
                    		$arrImagOther = unserialize($inforNews->news_image_other);
                    		$arrImagOther[] = $file_name;//gan anh vua upload
                    		$proUpdate['news_image_other'] = serialize($arrImagOther);
                    		News::updateData($item_id,$proUpdate);
                    	}
                    }
                    //cap nhat DB de quan ly cac file anh product
                    if( $type == 2 ){
                        //img Product
                        $user_shop = UserShop::user_login();
                        if(sizeof($user_shop) > 0){
                            //get mang anh other
                            $shop_id = $user_shop->shop_id;
                            $inforPro = Product::getProductByShopId($shop_id,$item_id);
                            if($inforPro){
                                $arrImagOther = unserialize($inforPro->product_image_other);
                                $arrImagOther[] = $file_name;//gan anh vua upload
                                $proUpdate['product_image_other'] = serialize($arrImagOther);
                                Product::updateData($item_id,$proUpdate);
                            }
                        }
                    }
                    if($type == 3){//anh banner
                        $banner = Banner::getBannerByID($item_id);
                        if($banner){
                            if($banner->banner_image != ''){//xoa anh c?
                                //xoa anh upload
                                FunctionLib::deleteFileUpload($banner->banner_image,$item_id,CGlobal::FOLDER_BANNER);
                                //xóa anh thumb
                                $arrSizeThumb = CGlobal::$arrBannerSizeImage;
                                foreach($arrSizeThumb as $k=>$size){
                                    $sizeThumb = $size['w'].'x'.$size['h'];
                                    FunctionLib::deleteFileThumb($banner->banner_image,$item_id,CGlobal::FOLDER_BANNER,$sizeThumb);
                                }
                            }
                            Banner::updateData($item_id,array('banner_image'=>$file_name));//cap nhat anh moi
                        }
                    }
                    if($type == 4){//anh logo shop
                    	$logo = UserShop::getByID($item_id);
                    	if($logo){
                    		if($logo->shop_logo != ''){//xoa anh cũ
                    			//xoa anh upload
                    			FunctionLib::deleteFileUpload($logo->shop_logo,$item_id,CGlobal::FOLDER_LOGO_SHOP);
                    			//xoa anh thumb
                    			$arrSizeThumb = CGlobal::$arrBannerSizeImage;
                    			foreach($arrSizeThumb as $k=>$size){
                    				$sizeThumb = $size['w'].'x'.$size['h'];
                    				FunctionLib::deleteFileThumb($logo->shop_logo,$item_id,CGlobal::FOLDER_LOGO_SHOP,$sizeThumb);
                    			}
                    		}
                    		UserShop::updateData($item_id,array('shop_logo'=>$file_name));//cap nhat anh moi
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

    public function removeImageCommon(){
        $item_id = (int)Request::get('id',0);
        $type = (int)Request::get('type',1);
        $nameImage = trim(Request::get('nameImage',''));
        $key = trim(Request::get('key',''));
        $aryData = array();
        $aryData['intIsOK'] = -1;
       	
        if($item_id > 0 && $nameImage != '' && $key != ''){
            switch( $type ){
                case 1:
                	//img news
                	$inforNews = News::getNewByID($item_id);
                	if(sizeof($inforNews) >0){
                		$arrImagOther = unserialize($inforNews->news_image_other);
                		if(!empty($arrImagOther)){
                			foreach($arrImagOther as $k=>$v){
                				if($v == $nameImage){
                					unset($arrImagOther[$k]);
                				}
                			}
                		}
                		$proUpdate['news_image_other'] = serialize($arrImagOther);
                		News::updateData($item_id,$proUpdate);
                	}
                	$aryData['intIsOK'] = 1;
                	break;
                case 3 ://xoa anh banner
                    $banner = Banner::getBannerByID($item_id);
                    if($banner){
                        if($banner->banner_image != '' && strcmp(trim($banner->banner_image),$nameImage) == 0){//xoa anh c?
                            //xoa anh upload
                            FunctionLib::deleteFileUpload($banner->banner_image,$item_id,CGlobal::FOLDER_BANNER);
                            //xóa anh thumb
                            $arrSizeThumb = CGlobal::$arrBannerSizeImage;
                            foreach($arrSizeThumb as $k=>$size){
                                $sizeThumb = $size['w'].'x'.$size['h'];
                                FunctionLib::deleteFileThumb($banner->banner_image,$item_id,CGlobal::FOLDER_BANNER,$sizeThumb);
                            }
                            $aryData['intIsOK'] = 1;
                        }
                    }
                    break;
                    case 4 ://xoa logo shop
                    	$logo = UserShop::getByID($item_id);
                    	if($logo){
                    		if($logo->shop_logo != '' && strcmp(trim($logo->shop_logo),$nameImage) == 0){//xoa anh cu
                    			//xoa anh upload
                    			FunctionLib::deleteFileUpload($logo->shop_logo,$item_id,CGlobal::FOLDER_LOGO_SHOP);
                    			//xóa anh thumb
                    			$arrSizeThumb = CGlobal::$arrBannerSizeImage;
                    			foreach($arrSizeThumb as $k=>$size){
                    				$sizeThumb = $size['w'].'x'.$size['h'];
                    				FunctionLib::deleteFileThumb($logo->shop_logo,$item_id,CGlobal::FOLDER_LOGO_SHOP,$sizeThumb);
                    			}
                    			$aryData['intIsOK'] = 1;
                    		}
                    	}
                    	break;
                default:
                    break;
            }
        }
        return Response::json($aryData);
    }
	
    //getImgContent
    function getImageContentCommon(){
    	 $id_hiden = Request::get('id', 0);
         $type = Request::get('type', 1);
         $data = array('isIntOk' => 0);
         $arrImg = $arrViewImgOther = array();
    	 switch($type){
            case 1://img news
            	$inforNews = News::getNewByID($id_hiden);
            	if(sizeof($inforNews) >0){
            		$arrImg = unserialize($inforNews->news_image_other);
            		foreach($arrImg as $k=>$val){
            			$url_thumb = ThumbImg::getImageThumb(CGlobal::FOLDER_NEWS, $id_hiden, $val, CGlobal::sizeImage_100);
            			$url_thumb_content = ThumbImg::getImageThumb(CGlobal::FOLDER_NEWS, $id_hiden, $val, CGlobal::sizeImage_600);
            			$arrViewImgOther[] = array(
            					'post_title'=>$inforNews->news_title,
            					'src_img_other'=>$url_thumb,
            					'src_thumb_content'=>$url_thumb_content);
            		}
            		
            	}
            	$data['dataImage'] = $arrViewImgOther;
            	$data['isIntOk'] = 1;
            	return Response::json($data);
            	break;
            default:
                break;
        }
        return Response::json($data);
    }
    function sendEmail(){
        // Test gửi mail
        Mail::send('emails.test_email', array('firstname'=>'Trương Manh Quỳnh'), function($message){
            $message->to('nguyenduypt86@gmail.com', 'Trương Manh Quỳnh')
                ->subject('Test gửi mail!');
        });

        die();
    }
    function getProductFromOtherSite(){
    	$result = array();
   		
    	$search['product_status'] = isset($_POST['product_status']) ? (int)$_POST['product_status'] : -1;
    	$search['user_shop_id'] = isset($_POST['user_shop_id']) ? (int)$_POST['user_shop_id'] : -1;
    	$limit = isset($_POST['product_limit']) ? (int)$_POST['product_limit'] : 0;
    	$search['field_get'] = '';
    	
    	if($search['user_shop_id'] > 0 && $limit > 0){
    		$pageNo = 1;
    		$offset = $total = 0;
    		$search = $data = array();
    		$data = Product::searchByCondition($search, $limit, $offset, $total);
    		$paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';
    		
    		if(sizeof($data) > 0){
    			foreach($data as $v){
    				$item = array(
    						'product_id'=>$v->product_id,
    						'product_id'=>$v->product_id,
    						'product_name'=>$v->product_name,
    						'category_name'=>$v->category_name,
    						'category_id'=>$v->category_id,
    						'product_image'=>ThumbImg::getImageThumb(CGlobal::FOLDER_PRODUCT, $v->product_id, $v->product_image, CGlobal::sizeImage_450, '', true, CGlobal::type_thumb_image_banner, false),
    						'product_price_sell'=>$v->product_price_sell,
    						'product_price_market'=>$v->product_price_market,
    						'product_type_price'=>$v->product_type_price,
    						'product_selloff'=>$v->product_selloff,
    						'product_link'=>FunctionLib::buildLinkDetailProduct($v->product_id, $v->product_name, $v->category_name),
    				);
    				$result[] = $item;
    			}
    		}
    		
    	}
    	return json_encode($result);
    }
}