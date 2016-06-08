<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Quynhtm
 */
class Product extends Eloquent
{
    protected $table = 'category';
    protected $primaryKey = 'category_id';
    public $timestamps = false;

    //cac truong trong DB
    protected $fillable = array('category_id','category_name', 'category_parent_id',
        'category_content_front', 'category_content_front_order', 'category_status',
        'category_image_background', 'category_icons', 'category_order');

    public static function getByID($id) {
        $admin = Product::where('category_id', $id)->first();
        return $admin;
    }

    public static function getCategoriessAll() {
        $categories = Product::where('category_id', '>', 0)->get();
        $data = array();
        foreach($categories as $itm) {
            $data[$itm['category_id']] = $itm['category_name'];
        }
        return $data;
    }

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = Product::where('category_id','>',0);
            if (isset($dataSearch['category_name']) && $dataSearch['category_name'] != '') {
                $query->where('category_name','LIKE', '%' . $dataSearch['category_name'] . '%');
            }
            if (isset($dataSearch['category_status']) && $dataSearch['category_status'] != -1) {
                $query->where('category_status', $dataSearch['category_status']);
            }
            $total = $query->count();
            $query->orderBy('category_id', 'desc');

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
                return $data->category_id;
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
            DB::connection()->getPdo()->commit();
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

}