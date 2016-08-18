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
    protected $fillable = array('shop_id','shop_name', 'user_shop','number_limit_product','is_shop','is_login','shop_time_login','shop_time_logout',
        'user_password', 'shop_phone', 'shop_address','shop_province','shop_category',
        'shop_category_name','shop_about','shop_transfer','time_start_vip','time_end_vip','shop_string_category_id','total_product_up',
        'shop_email', 'shop_status', 'shop_created');

    public static function getByID($id) {
        $shop = (Memcache::CACHE_ON)? Cache::get(Memcache::CACHE_USER_SHOP_ID.$id) : array();
        if (sizeof($shop) == 0) {
            $shop = UserShop::where('shop_id', $id)->first();
            if($shop && Memcache::CACHE_ON){
                Cache::put(Memcache::CACHE_USER_SHOP_ID.$id, $shop, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $shop;
    }

    public static function getShopAll() {
        $data = (Memcache::CACHE_ON)? Cache::get(Memcache::CACHE_ALL_USER_SHOP) : array();
        if (sizeof($data) == 0) {
            $shop = UserShop::where('shop_id', '>', 0)->get();
            foreach($shop as $itm) {
                $data[$itm['shop_id']] = $itm['shop_name'];
            }
            if(!empty($data) && Memcache::CACHE_ON){
                Cache::put(Memcache::CACHE_ALL_USER_SHOP, $data, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $data;
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
            $query->orderBy('shop_time_login', 'desc');

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
        }
        Cache::forget(Memcache::CACHE_ALL_USER_SHOP);
    }

}