<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class AccFunds extends \yii\db\ActiveRecord
{
	public static function getBooleanFields(){
		return [
			 
		];
	}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%acc_funds}}';
        //Yii::$app->security->
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            
        ];
    }

    public static function getID(){
    	return (new Query())->select('max(id) +1')->from(self::tableName())->scalar();
    }     
     
    public static function getItem($id=0,$o=[]){    	
    	$item = static::find()
    	->where(['id'=>$id, 'sid'=>__SID__]) ;
    	$item = $item->asArray()->one();
    	 
    	return $item;
    }
    /*
     * 
     */
    public static function getList($o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.lastmodify'=>SORT_DESC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);    
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';    	
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : -1;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$not_in = isset($o['not_in']) ? $o['not_in'] : [];
    	$in = isset($o['in']) ? $o['in'] : [];
    	if(!is_array($in) && $in != "") $in = explode(',', $in);
    	if(!is_array($not_in) && $not_in != "") $not_in = explode(',', $not_in);
    	$offset = ($p-1) * $limit;
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->where(['a.sid'=>__SID__]) ;
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'a.title', $filter_text]);
    	}
    	if(is_numeric($type_id) && $type_id > -1){
    		$query->andWhere(['a.type_id'=>$type_id]);
    	}
    	if(is_numeric($parent_id) && $parent_id > -1){
    		$query->andWhere(['a.parent_id'=>$parent_id]);
    	}
    	if(is_numeric($is_active) && $is_active > -1){
    		$query->andWhere(['a.is_active'=>$is_active]);
    	}
    	if(is_array($in) && !empty($in)){
    		$query->andWhere(['a.id'=>$in]);
    	}
    	if(is_array($not_in) && !empty($not_in)){
    		$query->andWhere(['not in','a.id',$not_in]);
    	}
    	$c = 0;
    	if($count){
    		$query->select('count(1)');
    		$c = $query->scalar();
    	}
    	$query->select(['a.*'])
    	->orderBy($order_by)
    	->offset($offset)
    	->limit($limit);
    	$l = $query->asArray()->all();
    	//
    	return [
    			'listItem'=>$l,
    			'total_records'=>$c,
    			'total_pages'=>ceil($c/$limit),
    			'limit'=>$limit,
    			'p'=>$p,
    	];
    	
    }
    public static function getAll($o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 0;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : [ 'a.id'=>SORT_DESC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : -1;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$not_in = isset($o['not_in']) ? $o['not_in'] : [];
    	$in = isset($o['in']) ? $o['in'] : [];
    	if(!is_array($in) && $in != "") $in = explode(',', $in);
    	if(!is_array($not_in) && $not_in != "") $not_in = explode(',', $not_in);
    	$offset = ($p-1) * $limit;
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->where(['a.sid'=>__SID__]) ;
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'a.title', $filter_text]);
    	}
    	if(is_numeric($type_id) && $type_id > -1){
    		$query->andWhere(['a.type_id'=>$type_id]);
    	}
    	if(is_numeric($parent_id) && $parent_id > -1){
    		$query->andWhere(['a.parent_id'=>$parent_id]);
    	}
    	if(is_numeric($is_active) && $is_active > -1){
    		$query->andWhere(['a.is_active'=>$is_active]);
    	}
    	if(is_array($in) && !empty($in)){
    		$query->andWhere(['a.id'=>$in]);
    	}
    	if(is_array($not_in) && !empty($not_in)){
    		$query->andWhere(['not in','a.id',$not_in]);
    	}
    
    	$query->select(['a.*'])
    	->orderBy($order_by)
    	->offset($offset);
    	if($limit > 0){
    		$query->limit($limit);
    	}
    	$l = $query->asArray()->all();
    	//
    	return $l;
    
    }
    
    public static function getFirstFund($currency = 1, $branch_id = 0){
    	$branch_id = $branch_id > 0 ? $branch_id : Yii::$app->user->branch_id;
    	 
    	return (new Query())->from(self::tableName())->where([
    			'sid'=>__SID__,
    			'branch_id'=>$branch_id,
    			'currency'=>$currency,
    			//'is_locked'=>0
    	])->orderBy(['created_at'=>SORT_ASC])->one();
    }
    
    public static function getLastFund($currency = 1,$branch_id = 0,$to_date = -1){
    	$branch_id = $branch_id > 0 ? $branch_id : Yii::$app->user->branch_id;
    	$to_date = $to_date != -1 ? $to_date : date('Y-m-d');
    	return (new Query())->from(self::tableName())->where([
    			'sid'=>__SID__,
    			'branch_id'=>$branch_id,
    			'currency'=>$currency,
    		//	'is_locked'=>1,
    			
    	])
    	->andWhere(['<=','created_at',strtotime($to_date)])
    	->orderBy(['created_at'=>SORT_DESC])->one();
    }
    
    public static function getPreAmount($currency, $from_date,$branch_id = 0){
    	$branch_id = $branch_id > 0 ? $branch_id : Yii::$app->user->branch_id;
    	$time = ctime(['string'=>$from_date,'return_type'=>1]);
    	$mtime = mktime(23,59,59,date('m',$time),date('d',$time)-1,date('Y'));
    	$fund = self::getFirstFund($currency,$branch_id);
    	$pre_amount = 0;
    	if(!empty($fund)){
    		$pre_amount = $fund['pre_amount'];
    		$tong_thu = (new Query())->select((new \yii\db\Expression('sum(amount)')))->from(AccBills::tableName())
    		->where([
    				'branch_id'=>$branch_id,
    				'type_id'=>1,
    				'sid'=>__SID__,
    				'currency'=>$currency,
    				
    		])
    		->andWhere(['<=','created_at',$mtime])
    		->scalar();
    		$tong_chi = (new Query())->select((new \yii\db\Expression('sum(amount)')))->from(AccBills::tableName())
    		->where([
    				'branch_id'=>$branch_id,
    				'type_id'=>2,
    				'sid'=>__SID__,
    				'currency'=>$currency,
    				
    		])
    		->andWhere(['<=','created_at',$mtime])
    		->scalar();
    		
    		$tong_thu = $tong_thu > 0 ? $tong_thu : 0;
    		$tong_chi = $tong_chi > 0 ? $tong_chi : 0;
    		$pre_amount = is_numeric($pre_amount) ? $pre_amount : 0;
    		$pre_amount += ($tong_thu-$tong_chi);
    	}
    	
    	return $pre_amount;
    	
    }
}
