<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Quynhtm
 */
class UserShop extends Eloquent
{
    protected $table = 'web_user_shop';
    protected $primaryKey = 'shop_id';
    public $timestamps = false;

    //cac truong trong DB
    protected $fillable = array('shop_id','shop_name', 'user_shop','is_shop','is_login','shop_time_login','shop_time_logout',
        'user_password', 'shop_phone', 'shop_address','shop_province','shop_category',
        'shop_category_name','shop_about','shop_transfer','time_start_vip','time_end_vip',
        'shop_up_product',//khi nhập sản phẩm cái này +1
        'shop_number_share',//khi share sẽ công number_limit_product + 1
        'number_limit_product',//thay đổi khi share url shop +1
        'shop_email', 'shop_status', 'shop_created', 'shop_logo');

    public static function getByID($id) {
        $shop = (Memcache::CACHE_ON)? Cache::get(Memcache::CACHE_USER_SHOP_ID.$id) : array();
        if($id > 0){
            if (sizeof($shop) == 0) {
                $shop = UserShop::where('shop_id', $id)->first();
                if($shop && Memcache::CACHE_ON){
                    Cache::put(Memcache::CACHE_USER_SHOP_ID.$id, $shop, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
        }
        return $shop;
    }

    public static function getCategoryShopById($id) {
        $categoryShop = (Memcache::CACHE_ON)? Cache::get(Memcache::CACHE_CATEGORY_SHOP_ID.$id) : array();
        if (sizeof($categoryShop) == 0) {
            $shop = UserShop::getByID($id);
            if (sizeof($shop) > 0) {
                if (isset($shop->shop_category) && $shop->shop_category != '') {
                    $arrCateId = explode(',', $shop->shop_category);
                    $categoryShop = Category::getCategoryByArrayId($arrCateId);
                    if($categoryShop && Memcache::CACHE_ON){
                        Cache::put(Memcache::CACHE_CATEGORY_SHOP_ID.$id, $categoryShop, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
                    }
                    return $categoryShop;
                }
            }
        }
        return $categoryShop;
    }

    public static function getShopAll() {
        $data = (Memcache::CACHE_ON)? Cache::get(Memcache::CACHE_ALL_USER_SHOP) : array();
        if (sizeof($data) == 0) {
            $shop = UserShop::where('shop_id', '>', 0)->where('shop_status', CGlobal::status_show)->get();
            foreach($shop as $itm) {
                $data[$itm['shop_id']] = $itm['shop_name'];
            }
            if(!empty($data) && Memcache::CACHE_ON){
                Cache::put(Memcache::CACHE_ALL_USER_SHOP, $data, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $data;
    }

    public static function getUserByName($name){
        $shop = UserShop::where('user_shop', $name)->first();
        return $shop;
    }
    public static function getUserShopByPhone($shop_phone){
        $shop = UserShop::where('shop_phone', $shop_phone)->first();
        return $shop;
    }
    public static function getUserShopByEmail($shop_email){
        $shop = UserShop::where('shop_email', $shop_email)->first();
        return $shop;
    }
    public static function isLogin(){
        $result = false;
        if (Session::has('user_shop')) {
            $result = true;
        }
        return $result;
    }
    public static function user_login(){
        $user_shop = array();
        if(Session::has('user_shop')){
            return $user_shop = Session::get('user_shop');
        }
        return $user_shop;
    }

    public static function updateLogin($shop = array()){
        if($shop){
            $shop->shop_time_login = time();
            $shop->save();
        }
    }

    //cap nhat nhung shop da het session
    public static function updateShopLogout(){
        $yesterday = time() - (2 * 60 * 60);
        $query = UserShop::where('shop_id','>',0)->where('is_login','=',1);
        $query->where('shop_time_login', '<=', $yesterday);
        $result = $query->get();
        if($result){
            foreach($result as $k =>$shop){
                $dataInput = array('is_login'=>0,'shop_time_logout'=>$shop->shop_time_login);
                $shop->update($dataInput);
            }
        }
    }
    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = UserShop::where('shop_id','>',0);
            if (isset($dataSearch['shop_name']) && $dataSearch['shop_name'] != '') {
                $query->where('shop_name','LIKE', '%' . $dataSearch['shop_name'] . '%');
            }
            if (isset($dataSearch['user_shop']) && $dataSearch['user_shop'] != '') {
                $query->where('user_shop','LIKE', '%' . $dataSearch['user_shop'] . '%');
            }
            if (isset($dataSearch['is_shop']) && $dataSearch['is_shop'] != -1) {
                $query->where('is_shop', $dataSearch['is_shop']);
            }
            if (isset($dataSearch['shop_id']) && $dataSearch['shop_id'] > 0) {
                $query->where('shop_id', $dataSearch['shop_id']);
            }
            $total = $query->count();
            $query->orderBy('shop_time_login', 'desc')->orderBy('shop_time_logout', 'desc');

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
            $data = new UserShop();
            if (is_array($dataInput) && count($dataInput) > 0) {
                foreach ($dataInput as $k => $v) {
                    $data->$k = $v;
                }
            }
            if ($data->save()) {
                DB::connection()->getPdo()->commit();
                if(isset($data->shop_id) && $data->shop_id > 0){
                    self::removeCache($data->shop_id);
                }
                return $data->shop_id;
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
            $dataSave = UserShop::find($id);
            if (!empty($dataInput)){
                $dataSave->update($dataInput);
            }
            DB::connection()->getPdo()->commit();
            if(isset($dataSave->shop_id) && $dataSave->shop_id > 0){
                self::removeCache($dataSave->shop_id);
            }
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
            $dataSave = UserShop::find($id);
            $dataSave->delete();
            DB::connection()->getPdo()->commit();
            if(isset($dataSave->shop_id) && $dataSave->shop_id > 0){
                self::removeCache($dataSave->shop_id);
            }
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    /**
     * @param int $id
     */
    public static function removeCache($id = 0){
        if($id > 0){
            Cache::forget(Memcache::CACHE_USER_SHOP_ID.$id);
            Cache::forget(Memcache::CACHE_CATEGORY_SHOP_ID.$id);
        }
        Cache::forget(Memcache::CACHE_ALL_USER_SHOP);
    }

}