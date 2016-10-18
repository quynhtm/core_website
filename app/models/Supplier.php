<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Quynhtm
 */
class Supplier extends Eloquent
{
    protected $table = 'web_supplier';
    protected $primaryKey = 'supplier_id';
    public $timestamps = false;

    //cac truong trong DB
    protected $fillable = array('supplier_id','supplier_name', 'supplier_phone','supplier_hot_line','supplier_email',
        'supplier_website','supplier_status','supplier_created');

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = Supplier::where('supplier_id','>',0);
            if (isset($dataSearch['supplier_name']) && $dataSearch['supplier_name'] != '') {
                $query->where('supplier_name','LIKE', '%' . $dataSearch['supplier_name'] . '%');
            }
            if (isset($dataSearch['supplier_phone']) && $dataSearch['supplier_phone'] != '') {
                $query->where('supplier_phone','LIKE', '%' . $dataSearch['supplier_phone'] . '%');
            }
            if (isset($dataSearch['supplier_email']) && $dataSearch['supplier_email'] != '') {
                $query->where('supplier_email','LIKE', '%' . $dataSearch['supplier_email'] . '%');
            }

            if (isset($dataSearch['supplier_id'])) {
                if (is_array($dataSearch['supplier_id'])) {
                    $query->whereIn('supplier_id', $dataSearch['supplier_id']);
                }
                elseif ((int)$dataSearch['supplier_id'] > 0) {
                    $query->where('supplier_id','=', (int)$dataSearch['supplier_id']);
                }
            }

            $total = $query->count();
            $query->orderBy('supplier_id', 'asc');

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
        $new = Supplier::where('supplier_id', $id)->first();
        return $new;
    }

    public static function addData($dataInput){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $data = new Supplier();
            if (is_array($dataInput) && count($dataInput) > 0) {
                foreach ($dataInput as $k => $v) {
                    $data->$k = $v;
                }
            }
            if ($data->save()) {
                DB::connection()->getPdo()->commit();
                return $data->supplier_id;
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
            $dataSave = Supplier::find($id);
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
            $dataSave = Supplier::find($id);
            $dataSave->delete();
            DB::connection()->getPdo()->commit();
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

}