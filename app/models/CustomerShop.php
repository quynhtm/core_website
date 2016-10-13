<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Quynhtm
 */
class CustomerShop extends Eloquent
{
    protected $table = 'web_customer_shop';
    protected $primaryKey = 'customer_shop_id';
    public $timestamps = false;

    //cac truong trong DB
    protected $fillable = array('customer_shop_id','customer_shop_full_name', 'customer_shop_email','customer_shop_phone',
        'customer_shop_password','customer_shop_status','customer_shop_address','customer_shop_province_id',
        'customer_shop_last_action', 'customer_shop_created', 'customer_shop_updated');
    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = CustomerShop::where('customer_shop_id','>',0);
            if (isset($dataSearch['customer_shop_full_name']) && $dataSearch['customer_shop_full_name'] != '') {
                $query->where('customer_shop_full_name','LIKE', '%' . $dataSearch['customer_shop_full_name'] . '%');
            }
            if (isset($dataSearch['customer_shop_email']) && $dataSearch['customer_shop_email'] != '') {
                $query->where('customer_shop_email','LIKE', '%' . $dataSearch['customer_shop_email'] . '%');
            }
            if (isset($dataSearch['customer_shop_phone']) && $dataSearch['customer_shop_phone'] != '') {
                $query->where('customer_shop_phone','LIKE', '%' . $dataSearch['customer_shop_phone'] . '%');
            }
            if (isset($dataSearch['customer_shop_province_id']) && $dataSearch['customer_shop_province_id'] != -1) {
                $query->where('customer_shop_province_id', $dataSearch['customer_shop_province_id']);
            }
            if (isset($dataSearch['customer_shop_id']) && $dataSearch['customer_shop_id'] > 0) {
                $query->where('customer_shop_id', $dataSearch['customer_shop_id']);
            }
            $total = $query->count();
            $query->orderBy('customer_shop_last_action', 'desc');

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
            $data = new CustomerShop();
            if (is_array($dataInput) && count($dataInput) > 0) {
                foreach ($dataInput as $k => $v) {
                    $data->$k = $v;
                }
            }
            if ($data->save()) {
                DB::connection()->getPdo()->commit();
                return $data->customer_shop_id;
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
            $dataSave = CustomerShop::find($id);
            if (!empty($dataInput)){
                $dataSave->update($dataInput);
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
            $dataSave = CustomerShop::find($id);
            $dataSave->delete();
            DB::connection()->getPdo()->commit();
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

}