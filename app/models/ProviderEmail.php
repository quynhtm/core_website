<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Quynhtm
 */
class ProviderEmail extends Eloquent
{
    protected $table = 'web_provider_email';
    protected $primaryKey = 'provider_id';
    public $timestamps = false;

    //cac truong trong DB
    protected $fillable = array('provider_id, provider_name', 'provider_phone', 'provider_email',
        'provider_address', 'provider_fax', 'provider_type',
        'provider_code', 'provider_usual_repon', 'provider_usual_stand',
        'provider_description', 'provider_bank_name', 'provider_bank_account',
        'provider_bank_office', 'provider_bank_account_holder', 'provider_create_id',
        'provider_create_time', 'provider_update_id', 'provider_update_time',
        'provider_service_address', 'provider_service_name', 'provider_service_stand',
        'provider_service_email', 'provider_service_phone', 'provider_sell_website',
        'provider_limit_money_product', 'province_id', 'district_id', 'provider_nemo_id');

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = ProviderEmail::where('provider_id','>',0);
            if (isset($dataSearch['provider_name']) && $dataSearch['provider_name'] != '') {
                $query->where('provider_name','LIKE', '%' . $dataSearch['provider_name'] . '%');
            }
            if (isset($dataSearch['provider_phone']) && $dataSearch['provider_phone'] != '') {
                $query->where('provider_phone','LIKE', '%' . $dataSearch['provider_phone'] . '%');
            }
            if (isset($dataSearch['provider_email']) && $dataSearch['provider_email'] != '') {
                $query->where('provider_email','LIKE', '%' . $dataSearch['provider_email'] . '%');
            }
			
            if (isset($dataSearch['provider_id'])) {
            	if (is_array($dataSearch['provider_id'])) {
            		$query->whereIn('provider_id', $dataSearch['provider_id']);
            	}
            	elseif ((int)$dataSearch['provider_id'] > 0) {
            		$query->where('provider_id','=', (int)$dataSearch['provider_id']);
            	}
            }
            
            $total = $query->count();
            $query->orderBy('provider_id', 'asc');
			
            if($limit > 0){
            	$query->take($limit);
            }
            
            //get field can lay du lieu
            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',',trim($dataSearch['field_get'])): array();
            if(!empty($fields)){
                $result = $query->skip($offset)->get($fields);
            }else{
                $result = $query->skip($offset)->get();
            }
            return $result;

        }catch (PDOException $e){
            throw new PDOException();
        }
    }
	
    public static function getByID($id) {
    	$new = ProviderEmail::where('provider_id', $id)->first();
    	return $new;
    }
    
    public static function addData($dataInput){
    	try {
    		DB::connection()->getPdo()->beginTransaction();
    		$data = new ProviderEmail();
    		if (is_array($dataInput) && count($dataInput) > 0) {
    			foreach ($dataInput as $k => $v) {
    				$data->$k = $v;
    			}
    		}
    		if ($data->save()) {
    			DB::connection()->getPdo()->commit();
    			return $data->provider_id;
    		}
    		DB::connection()->getPdo()->commit();
    		return false;
    	} catch (PDOException $e) {
    		DB::connection()->getPdo()->rollBack();
    		throw new PDOException();
    	}
    }
    
    public static function updateData($id, $dataInput){
    	try {
    		DB::connection()->getPdo()->beginTransaction();
    		$dataSave = ProviderEmail::find($id);
    		if(!empty($dataInput)){
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
            $dataSave = ProviderEmail::find($id);
            $dataSave->delete();
            DB::connection()->getPdo()->commit();
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

}