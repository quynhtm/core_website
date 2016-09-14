<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Quynhtm
 */
class ShopShare extends Eloquent
{
    protected $table = 'web_shop_share';
    protected $primaryKey = 'shop_share_id';
    public $timestamps = false;

    //cac truong trong DB
    protected $fillable = array('shop_share_id','shop_id', 'shop_name','shop_share_ip','shop_share_time');

    public static function getByID($id) {
        $provider = (Memcache::CACHE_ON)? Cache::get(Memcache::CACHE_PROVIDER_ID.$id) : array();
        if (sizeof($provider) == 0) {
            $provider = ShopShare::where('shop_share_id', $id)->first();
            if($provider && Memcache::CACHE_ON){
                Cache::put(Memcache::CACHE_PROVIDER_ID.$id, $provider, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $provider;
    }

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = ShopShare::where('shop_share_id','>',0);
            if (isset($dataSearch['shop_name']) && $dataSearch['shop_name'] != '') {
                $query->where('shop_name','LIKE', '%' . $dataSearch['shop_name'] . '%');
            }

            $total = $query->count();
            $query->orderBy('shop_share_time', 'desc');

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
            $data = new ShopShare();
            if (is_array($dataInput) && count($dataInput) > 0) {
                foreach ($dataInput as $k => $v) {
                    $data->$k = $v;
                }
            }
            if ($data->save()) {
                DB::connection()->getPdo()->commit();
                if(isset($data->shop_share_id) && $data->shop_share_id > 0){
                    self::removeCache($data->shop_share_id);
                }
                return $data->shop_share_id;
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
            $dataSave = ShopShare::find($id);
            if (!empty($dataInput)){
                $dataSave->update($dataInput);
            }
            DB::connection()->getPdo()->commit();
            if(isset($dataSave->shop_share_id) && $dataSave->shop_share_id > 0){
                self::removeCache($dataSave->shop_share_id);
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
            $dataSave = ShopShare::find($id);
            $dataSave->delete();
            DB::connection()->getPdo()->commit();
            if(isset($dataSave->shop_share_id) && $dataSave->shop_share_id > 0){
                self::removeCache($dataSave->shop_share_id);
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
            Cache::forget(Memcache::CACHE_PROVIDER_ID.$id);
        }
        Cache::forget(Memcache::CACHE_ALL_PROVIDER);
    }

}