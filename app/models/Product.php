<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Quynhtm
 */
class Product extends Eloquent
{
    protected $table = 'web_product';
    protected $primaryKey = 'product_id';
    public $timestamps = false;

    //cac truong trong DB
    protected $fillable = array('product_id','product_code', 'product_name', 'category_name', 'category_id',
        'product_price_sell', 'product_price_market', 'product_price_input','product_type_price','product_selloff',
        'product_is_hot', 'product_sort_desc', 'product_content','product_image','product_image_hover','product_image_other',
        'product_order', 'quality_input','quality_out','product_status','is_block',
        'user_shop_id', 'user_shop_name', 'is_shop','shop_province','time_created', 'time_update');

    /**
     * @param $product_id
     * @return array
     */
    public static function getProductByID($product_id) {
        $product = (Memcache::CACHE_ON)? Cache::get(Memcache::CACHE_PRODUCT_ID.$product_id) : array();
        if (sizeof($product) == 0) {
            $product = Product::where('product_id', $product_id)->first();
            if($product && Memcache::CACHE_ON){
                Cache::put(Memcache::CACHE_PRODUCT_ID.$product_id, $product, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $product;
    }

    /**
     * @param $shop_id
     * @param $id
     * @return array
     */
    public static function getProductByShopId($shop_id,$product_id) {
        if($product_id > 0){
            $product = Product::getProductByID($product_id);
            if (sizeof($product) > 0) {
                if(isset($product->user_shop_id) && (int)$product->user_shop_id == $shop_id){
                    return $product;
                }
            }
        }
        return array();
    }

    public static function getProductForSite($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = Product::where('product_id','>',0);
            $query->where('product_status',CGlobal::status_show);

            if (isset($dataSearch['category_id'])) {
                if (is_array($dataSearch['category_id'])) {//tim theo m?ng id danh muc
                    $query->whereIn('category_id', join(',',$dataSearch['category_id']));
                }
                elseif ((int)$dataSearch['category_id'] > 0) {//theo id danh muc
                    $query->where('category_id', (int)$dataSearch['category_id']);
                }
            }
            if (isset($dataSearch['user_shop_id']) && $dataSearch['user_shop_id'] != -1) {
                $query->where('user_shop_id', $dataSearch['user_shop_id']);
            }

            //1: shop free, 2: shop thuong: 3 shop VIP
            if (isset($dataSearch['is_shop'])) {
                if (is_array($dataSearch['is_shop'])) {
                    $query->whereIn('is_shop', join(',',$dataSearch['is_shop']));
                }
                elseif ((int)$dataSearch['is_shop'] > 0) {
                    $query->where('is_shop', (int)$dataSearch['is_shop']);
                }
            }
            $total = $query->count();

            $query->orderBy('is_shop', 'desc')
                  ->orderBy('time_update', 'desc');

            //get field can lay du lieu
            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',',trim($dataSearch['field_get'])): array();
            if(!empty($fields)){
                $result = $query->take($limit)->skip($offset)->get($fields);
            }else{
                $result = $query->take($limit)->skip($offset)->get();
            }
            return $result;

        }catch (PDOException $e){
            throw new PDOException();
        }
    }

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = Product::where('product_id','>',0);
            if (isset($dataSearch['product_name']) && $dataSearch['product_name'] != '') {
                $query->where('product_name','LIKE', '%' . $dataSearch['product_name'] . '%');
            }
            if (isset($dataSearch['product_status']) && $dataSearch['product_status'] != -1) {
                $query->where('product_status', $dataSearch['product_status']);
            }
            if (isset($dataSearch['category_id']) && $dataSearch['category_id'] != -1) {
                $query->where('category_id', $dataSearch['category_id']);
            }
            if (isset($dataSearch['user_shop_id']) && $dataSearch['user_shop_id'] != -1) {
                $query->where('user_shop_id', $dataSearch['user_shop_id']);
            }
            $total = $query->count();
            $query->orderBy('product_id', 'desc');

            //get field can lay du lieu
            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',',trim($dataSearch['field_get'])): array();
            if(!empty($fields)){
                $result = $query->take($limit)->skip($offset)->get($fields);
            }else{
                $result = $query->take($limit)->skip($offset)->get();
            }
            return $result;

        }catch (PDOException $e){
            throw new PDOException();
        }
    }

    /**
     * @desc: Tao Data.
     * @param $data
     * @return bool
     * @throws PDOException
     */
    public static function addData($dataInput)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $data = new Product();
            if (is_array($dataInput) && count($dataInput) > 0) {
                foreach ($dataInput as $k => $v) {
                    $data->$k = $v;
                }
            }
            if ($data->save()) {
                DB::connection()->getPdo()->commit();
                if(isset($data->product_id) && $data->product_id > 0){
                    self::removeCache($data->product_id);
                }
                return $data->product_id;
            }
            DB::connection()->getPdo()->commit();
            return false;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    /**
     * @desc: Update du lieu
     * @param $id
     * @param $data
     * @return bool
     * @throws PDOException
     */
    public static  function updateData($id, $dataInput)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $dataSave = Product::find($id);
            if (!empty($dataInput)){
                $dataSave->update($dataInput);
                if(isset($dataSave->product_id) && $dataSave->product_id > 0){
                    self::removeCache($dataSave->product_id);
                }
            }
            DB::connection()->getPdo()->commit();
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    /**
     * @desc: Update Data.
     * @param $id
     * @param $status
     * @return bool
     * @throws PDOException
     */
    public static function deleteData($id){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $dataSave = Product::find($id);
            $dataSave->delete();
            if(isset($dataSave->product_id) && $dataSave->product_id > 0){
                self::removeCache($dataSave->product_id);
            }
            DB::connection()->getPdo()->commit();
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function removeCache($id = 0){
        if($id > 0){
            Cache::forget(Memcache::CACHE_PRODUCT_ID.$id);
        }
    }
	
    //get product hot
    public static function getProductHot($dataField='', $limit=10){
    	try{
    		$result = array();
    
    		if($limit>0){
    			$query = Product::where('product_is_hot', CGlobal::status_show);
    			$query->where('product_status', CGlobal::status_show);
    			$query->where('product_image', '<>', '');
    			$query->orderBy('product_id', 'desc');
    			 
    			$fields = (isset($dataField['field_get']) && trim($dataField['field_get']) != '') ? explode(',',trim($dataField['field_get'])): array();
    			if(!empty($fields)){
    				$result = $query->take($limit)->get($fields);
    			}else{
    				$result = $query->take($limit)->get();
    			}
    		}
    		return $result;
    
    	}catch (PDOException $e){
    		throw new PDOException();
    	}
    }
}