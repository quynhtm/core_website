<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Quynhtm
 */
class CustomerEmail extends Eloquent
{
    protected $table = 'web_customer_email';
    protected $primaryKey = 'customer_id';
    public $timestamps = false;

    //cac truong trong DB
    protected $fillable = array('customer_id','customer_alias', 'customer_password','customer_status_id','customer_master_email',
        'customer_type_id','customer_phone','customer_full_name',
        'customer_street','customer_address','customer_ward_id','customer_district_id','customer_city_id',
        'customer_is_support','customer_flg_update', 'customer_mc_id');

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = CustomerEmail::where('customer_id','>',0);
            if (isset($dataSearch['customer_alias']) && $dataSearch['customer_alias'] != '') {
                $query->where('customer_alias','LIKE', '%' . $dataSearch['customer_alias'] . '%');
            }
            if (isset($dataSearch['customer_full_name']) && $dataSearch['customer_full_name'] != '') {
                $query->where('customer_full_name','LIKE', '%' . $dataSearch['customer_full_name'] . '%');
            }
            if (isset($dataSearch['customer_master_email']) && $dataSearch['customer_master_email'] != '') {
                $query->where('customer_master_email','LIKE', '%' . $dataSearch['customer_master_email'] . '%');
            }
            $total = $query->count();
            $query->orderBy('customer_id', 'desc');

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
     * @desc: Update Data.
     * @param $id
     * @param $status
     * @return bool
     * @throws PDOException
     */
    public static function deleteData($id){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $dataSave = CustomerEmail::find($id);
            $dataSave->delete();
            DB::connection()->getPdo()->commit();
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

}