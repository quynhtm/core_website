<?php
/*
* @Created by: HSS
* @Author    : nguyenduypt86@gmail.com
* @Date      : 06/2016
* @Version   : 1.0
*/
class Order extends Eloquent
{
    protected $table = 'web_order';
    protected $primaryKey = 'order_id';
    public $timestamps = false;

    //cac truong trong DB
    protected $fillable = array('order_id','order_product_id', 'order_product_name',
        'order_product_price_sell', 'order_product_image', 'order_category_id',
        'order_category_name', 'order_product_type_price', 'order_product_province',
        'order_customer_name', 'order_customer_phone', 'order_customer_email', 'order_customer_address', 'order_customer_note',
        'order_quality_buy', 'order_user_shop_id', 'order_user_shop_name', 'order_status', 'order_time');

    public static function getByID($id) {
        $admin = Order::where('order_id', $id)->first();
        return $admin;
    }
    public static function getOrderByShopId($shop_id,$order_id) {
        if($order_id > 0){
            $order = Order::getByID($order_id);
            if (sizeof($order) > 0) {
                if(isset($order->order_user_shop_id) && (int)$order->order_user_shop_id == $shop_id){
                    return $order;
                }
            }
        }
        return array();
    }

    public static function countOrderOfShopId($shop_id) {
        if($shop_id > 0){
            return $total_order = Order::where('order_user_shop_id', $shop_id)
                ->where('order_status', CGlobal::ORDER_STATUS_NEW)->count();
        }
        return 0;
    }

    public static function getOrderAll() {
        $categories = Order::where('order_id', '>', 0)->get();
        $data = array();
        foreach($categories as $itm) {
            $data[$itm['order_id']] = $itm['order_product_name'];
        }
        return $data;
    }

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){

        try{
            $query = Order::where('order_id','>',0);
            if (isset($dataSearch['order_product_name']) && $dataSearch['order_product_name'] != '') {
                $query->where('order_product_name','LIKE', '%' . $dataSearch['order_product_name'] . '%');
            }
            if (isset($dataSearch['order_customer_name']) && $dataSearch['order_customer_name'] != '') {
                $query->where('order_customer_name','LIKE', '%' . $dataSearch['order_customer_name'] . '%');
            }
            if (isset($dataSearch['order_customer_phone']) && $dataSearch['order_customer_phone'] != '') {
                $query->where('order_customer_phone','LIKE', '%' . $dataSearch['order_customer_phone'] . '%');
            }
            if (isset($dataSearch['order_customer_email']) && $dataSearch['order_customer_email'] != '') {
                $query->where('order_customer_email','LIKE', '%' . $dataSearch['order_customer_email'] . '%');
            }
            if (isset($dataSearch['time_start_time']) && $dataSearch['time_start_time'] != '') {
                $query->where('order_time','>=' . strtotime($dataSearch['time_start_time']));
            }
            if (isset($dataSearch['time_end_time']) && $dataSearch['time_end_time'] != '') {
                $query->where('order_time','<=' . strtotime($dataSearch['time_end_time']));
            }
            if (isset($dataSearch['order_status']) && $dataSearch['order_status'] != -1) {
                $query->where('order_status', $dataSearch['order_status']);
            }
            if (isset($dataSearch['order_user_shop_id']) && $dataSearch['order_user_shop_id'] != -1) {
                $query->where('order_user_shop_id', $dataSearch['order_user_shop_id']);
            }
            $total = $query->count();
            $query->orderBy('order_id', 'desc');

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
            $data = new Order();
            if (is_array($dataInput) && count($dataInput) > 0) {
                foreach ($dataInput as $k => $v) {
                    $data->$k = $v;
                }
            }
            if ($data->save()) {
                DB::connection()->getPdo()->commit();
                return $data->order_id;
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
            $dataSave = Order::find($id);
            if (!empty($dataInput)){
                $dataSave->update($dataInput);
            }
            DB::connection()->getPdo()->commit();
            return $dataSave->order_id;
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
            $dataSave = Order::find($id);
            $dataSave->delete();
            DB::connection()->getPdo()->commit();
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

}