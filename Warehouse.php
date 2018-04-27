<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class Warehouse extends \yii\db\ActiveRecord
{
	public static function getBooleanFields(){
		return [
			//	'is_active',	
		];
	}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%warehouse}}';
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

    public function getID(){
    	return (new Query())->select('max(id) +1')->from(self::tableName())->scalar();
    }     
     
    public static function getItem($id=0,$o=[]){    	
    	$item = static::find()
    	->where(['id'=>$id, 'sid'=>__SID__]);
    
    	$item = $item->asArray()->one();
    	 
    	return $item;
    }
    /*
     * 
     */
    public static function getList($o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.title'=>SORT_ASC,'a.id'=>SORT_DESC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : 1;    
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
    	
    	->leftJoin(['b'=>'branches'],'a.branch_id=b.id')
    	
    	->where(['a.sid'=>__SID__])
    	->andWhere(['>','a.state',-2]);
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
    	$query->select(['a.*','branch_name'=>'b.name'])
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
    
    
    public static function getListByUser($o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.title'=>SORT_ASC,'a.id'=>SORT_DESC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : 1;
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : -1;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$not_in = isset($o['not_in']) ? $o['not_in'] : [];
    	$item_id = isset($o['item_id']) ? $o['item_id'] : 0;
    	$in = isset($o['in']) ? $o['in'] : [];
    	if(!is_array($in) && $in != "") $in = explode(',', $in);
    	if(!is_array($not_in) && $not_in != "") $not_in = explode(',', $not_in);
    	$offset = ($p-1) * $limit;
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->where(['a.sid'=>__SID__])
    	->andWhere(['>','a.state',-2]);
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
    	 
    	$query->leftJoin(['b'=>'item_to_warehouse'],'a.id=b.warehouse_id and b.item_id='.$item_id);
    	$query->select(['a.*', 'b.quantity'
    			
    	])  
    	
    	//$query->addSelect([])
    	
    	->orderBy($order_by)
    	->offset($offset)
    	->limit($limit);
    	return $query->asArray()->all();
    	// 
    }
    
    public static function updateItemWarehouse($o = []){
    	$item_id = isset($o['item_id']) ? $o['item_id'] : 0;
    	$quantity = isset($o['quantity']) ? $o['quantity'] : 0;
    	$warehouse_id = isset($o['warehouse_id']) ? $o['warehouse_id'] : 0;
    	$sku = isset($o['sku']) ? $o['sku'] : '';
    	if(is_array($quantity) && !empty($quantity)){
    		foreach ($quantity as $warehouse_id=>$qtt){
    			$o['quantity'] = cprice($qtt);
    			$o['warehouse_id']=$warehouse_id;
    			self::updateSingleItemWarehouse($o);
    		}
    	}else{
    		$o['quantity'] = cprice($quantity);
    		self::updateSingleItemWarehouse($o);
    	}
    }
    
    public static function updateSingleItemWarehouse($o = []){
    	$item_id = isset($o['item_id']) ? $o['item_id'] : 0;
    	$quantity = isset($o['quantity']) ? $o['quantity'] : 0;
    	$warehouse_id = isset($o['warehouse_id']) ? $o['warehouse_id'] : 0;
    	$sku = isset($o['sku']) ? $o['sku'] : '';
    	
    	if(is_numeric($quantity)){
    	if((new Query())->from('item_to_warehouse')->where([
    			'item_id'=>$item_id,'warehouse_id'=>$warehouse_id,'sku'=>$sku
    	])->count(1) == 0){
    		Yii::$app->db->createCommand()->insert('item_to_warehouse', [
    				'item_id'=>$item_id,'warehouse_id'=>$warehouse_id,'sku'=>$sku,
    				'quantity'=>$quantity
    		])->execute();
    	}else{
    		Yii::$app->db->createCommand()->update('item_to_warehouse',['quantity'=>$quantity,'sku'=>$sku], [
    				'item_id'=>$item_id,'warehouse_id'=>$warehouse_id,'sku'=>$sku,
    				
    		])->execute();
    	}
    	}
    	
    	
    	    	    
    	
    	
    }
    
    
    public static function getItemQuantity($o=[]){
    	$item_id = isset($o['item_id']) ? $o['item_id'] : 0;    	
    	$warehouse_id = isset($o['warehouse_id']) ? $o['warehouse_id'] : 0;
    	$sku = isset($o['sku']) ? $o['sku'] : '';
    	return (new Query())
    	->select('quantity')
    	->from('item_to_warehouse')->where([
    			'item_id'=>$item_id,'warehouse_id'=>$warehouse_id,'sku'=>$sku,
    	])->scalar();
    }
    
    
    
    
    
    
    
    
}
