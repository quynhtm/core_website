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
            case 2://cập nhật link ảnh trong tin tức
                $this->updateLinkInContent($action);
                break;
            default:
                break;
        }
        echo 'Bạn chưa chọn kiểu action';
    }

    public function updateLinkInContent($type = 0){
    	$total = 0;
    	switch( $type ){
        		case 1://cập nhật link ảnh trong sản phẩm
        			$dataSearch['field_get'] = 'product_id,product_content';
        			$data = Product::searchByCondition($dataSearch,500,0,$total);
        			if($data){
        				foreach($data as $k=>$product){
        					$content = stripcslashes($product->product_content);
        			
        					$url_old1 = 'http://www.shopcuatui.com.vn/image.php?type_dir=product&amp;id='.$product->product_id.'&amp;width=700&amp;height=700&amp;image=';
        					$content1 = str_replace($url_old1, '',$content);
        			
        					$url_old2 = 'http://shopcuatui.com.vn/image.php?type_dir=product&amp;id='.$product->product_id.'&amp;width=700&amp;height=700&amp;image=';
        					$content2 = str_replace($url_old2, '',$content1);
        					$dataUpdate['product_content'] = $content2;
        			
        					Product::updateData($product->product_id,$dataUpdate);
        				}
        			}
        			break;
        		case 2://cập nhật link ảnh trong tin tức
        				$dataSearch['field_get'] = 'news_id,news_content';
        				$data = News::searchByCondition($dataSearch,1000,0,$total);
        				
        				if($data){
        					foreach($data as $k=>$product){
        						$content = stripcslashes($product->news_content);
        						 
        						$url_old1 = 'http://www.shopcuatui.com.vn/image.php?type_dir=news&amp;id='.$product->news_id.'&amp;width=700&amp;height=700&amp;image=';
        						$content1 = str_replace($url_old1, '',$content);
        						 
        						$url_old2 = 'http://shopcuatui.com.vn/image.php?type_dir=news&amp;id='.$product->news_id.'&amp;width=700&amp;height=700&amp;image=';
        						$content2 = str_replace($url_old2, '',$content1);
        						$dataUpdate['news_content'] = $content2;
        						 
        						News::updateData($product->news_id,$dataUpdate);
        					}
        				}
        				break;
        		default:
        			break;
        	}
            echo 'đã cập nhật xong';
        }

}