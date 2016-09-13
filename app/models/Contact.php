<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Quynhtm
 */
class Contact extends Eloquent
{
    protected $table = 'web_contact';
    protected $primaryKey = 'contact_id';
    public $timestamps = false;

    //cac truong trong DB
    protected $fillable = array('contact_id','contact_title', 'contact_content','contact_content_reply','contact_user_id_send',
        'contact_user_name_send','contact_phone_send','contact_email_send',
        'contact_status','contact_time_creater','contact_user_id_update','contact_user_name_update',
        'contact_type', 'contact_reason', 'contact_time_update');

    public static function getByID($id) {
        $provider = (Memcache::CACHE_ON)? Cache::get(Memcache::CACHE_PROVIDER_ID.$id) : array();
        if (sizeof($provider) == 0) {
            $provider = Contact::where('contact_id', $id)->first();
            if($provider && Memcache::CACHE_ON){
                Cache::put(Memcache::CACHE_PROVIDER_ID.$id, $provider, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $provider;
    }

    public static function getProviderShopByID($id,$shop_id) {
        $provider = Contact::getByID($id);
        if (sizeof($provider) > 0) {
            if($provider->provider_shop_id == $shop_id){
                return $provider;
            }
        }
        return array();
    }

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = Contact::where('contact_id','>',0);
            if (isset($dataSearch['provider_name']) && $dataSearch['provider_name'] != '') {
                $query->where('provider_name','LIKE', '%' . $dataSearch['provider_name'] . '%');
            }
            if (isset($dataSearch['provider_phone']) && $dataSearch['provider_phone'] != '') {
                $query->where('provider_phone','LIKE', '%' . $dataSearch['provider_phone'] . '%');
            }
            if (isset($dataSearch['provider_email']) && $dataSearch['provider_email'] != '') {
                $query->where('provider_email','LIKE', '%' . $dataSearch['provider_email'] . '%');
            }
            if (isset($dataSearch['provider_status']) && $dataSearch['provider_status'] != -1) {
                $query->where('provider_status', $dataSearch['provider_status']);
            }
            if (isset($dataSearch['contact_id']) && $dataSearch['contact_id'] > 0) {
                $query->where('contact_id', $dataSearch['contact_id']);
            }
            if (isset($dataSearch['provider_shop_id']) && $dataSearch['provider_shop_id'] > 0) {
                $query->where('provider_shop_id', $dataSearch['provider_shop_id']);
            }
            $total = $query->count();
            $query->orderBy('provider_time_creater', 'desc');

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
            $data = new Contact();
            if (is_array($dataInput) && count($dataInput) > 0) {
                foreach ($dataInput as $k => $v) {
                    $data->$k = $v;
                }
            }
            if ($data->save()) {
                DB::connection()->getPdo()->commit();
                if(isset($data->contact_id) && $data->contact_id > 0){
                    self::removeCache($data->contact_id);
                }
                return $data->contact_id;
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
            $dataSave = Contact::find($id);
            if (!empty($dataInput)){
                $dataSave->update($dataInput);
            }
            DB::connection()->getPdo()->commit();
            if(isset($dataSave->contact_id) && $dataSave->contact_id > 0){
                self::removeCache($dataSave->contact_id);
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
            $dataSave = Contact::find($id);
            $dataSave->delete();
            DB::connection()->getPdo()->commit();
            if(isset($dataSave->contact_id) && $dataSave->contact_id > 0){
                self::removeCache($dataSave->contact_id);
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