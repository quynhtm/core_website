<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Quynhtm
 */
class EmailContent extends Eloquent
{
    protected $table = 'web_mail_send_content';
    protected $primaryKey = 'mail_send_id';
    public $timestamps = false;

    //cac truong trong DB
    protected $fillable = array('mail_send_id','mail_send_title', 'mail_send_content','mail_send_str_product_id','mail_send_link',
        'mail_send_img','mail_send_status','mail_send_time_creater',
        'mail_send_time_update');

    public static function getByID($id) {
        $emailContent = EmailContent::where('mail_send_id', $id)->first();
        return $emailContent;
    }

    public static function getEmailContentAll() {
        $data = array();
        $emailContent = EmailContent::where('mail_send_id', '>', 0)->get();
        foreach($emailContent as $itm) {
            $data[$itm['mail_send_id']] = $itm['mail_send_title'];
        }
        return $data;
    }

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = EmailContent::where('mail_send_id','>',0);
            if (isset($dataSearch['mail_send_title']) && $dataSearch['mail_send_title'] != '') {
                $query->where('mail_send_title','LIKE', '%' . $dataSearch['mail_send_title'] . '%');
            }
            if (isset($dataSearch['mail_send_status']) && $dataSearch['mail_send_status'] != -1) {
                $query->where('mail_send_status', $dataSearch['mail_send_status']);
            }
            if (isset($dataSearch['mail_send_id']) && $dataSearch['mail_send_id'] > 0) {
                $query->where('mail_send_id', $dataSearch['mail_send_id']);
            }
            $total = $query->count();
            $query->orderBy('mail_send_time_creater', 'desc');

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
            $data = new EmailContent();
            if (is_array($dataInput) && count($dataInput) > 0) {
                foreach ($dataInput as $k => $v) {
                    $data->$k = $v;
                }
            }
            if ($data->save()) {
                DB::connection()->getPdo()->commit();
                return $data->mail_send_id;
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
            $dataSave = EmailContent::find($id);
            if (!empty($dataInput)){
                $dataSave->update($dataInput);
            }
            DB::connection()->getPdo()->commit();
            return $dataSave->mail_send_id;
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
            $dataSave = EmailContent::find($id);
            $dataSave->delete();
            DB::connection()->getPdo()->commit();
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
	
    //Get All Email
    public static function getAllContentEmail($limit=0){
    	$arrEmail[0] = '---Chọn nội dung email---';
    	$query = EmailContent::where('mail_send_id','>',0);
    	$query->orderBy('mail_send_time_creater', 'desc');
    	if($limit > 0){
    		$query->take($limit);
    	}
    	$result = $query->get();
    	if(sizeof($result)){
    		foreach($result as $item){
    			$arrEmail[$item->mail_send_id] = $item->mail_send_title;
    		}
    	}
    	return $arrEmail;
    }
}