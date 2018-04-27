<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class Tickets extends \yii\db\ActiveRecord
{
	public static function getBooleanFields(){
		return [
				'is_active',	
		];
	}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tickets}}';
    }
    public static function tableToNationalityGroup(){
    	return '{{%nationality_groups_to_ticket}}';
    }
    public static function tableNationalityGroup(){
    	return '{{%nationality_groups}}';
    }
    public static function tableToPrices(){
    	return '{{%tickets_to_prices}}';
    }
    public static function tableToSupplier(){
    	return '{{%tickets_to_suppliers}}';
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
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);    
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';    	
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$place_id = isset($o['place_id']) ? $o['place_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : -1;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : -1;
    	$not_in = isset($o['not_in']) ? $o['not_in'] : [];
    	$in = isset($o['in']) ? $o['in'] : [];
    	if(!is_array($in) && $in != "") $in = explode(',', $in);
    	if(!is_array($not_in) && $not_in != "") $not_in = explode(',', $not_in);
    	$offset = ($p-1) * $limit;
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->leftJoin(['c'=>'places'],'c.id=a.place_id')
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
    	if($supplier_id>0){
    		$query->innerJoin(['b'=>self::tableToSupplier()],'a.id=b.ticket_id');
    		$query->andWhere(['b.supplier_id'=>$supplier_id]);
    	}
    	if($place_id>0){
    		$query->andWhere(['a.place_id'=>$place_id]);
    	}
    	$c = 0;
    	if($count){
    		$query->select('count(1)');
    		$c = $query->scalar();
    	}
    	//$query->leftJoin(['b'=>Local::tableName()],'c.local_id=b.id');
    	//$query->select(['a.*','localName'=>'b.title','localType'=>'b.type_id'])
    	$query->select(['a.*','place_name'=>'c.title','local_id'=>'c.local_id'])
    	
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
    public function getNationalityGroup($id){
    	$id = $id>0 ? $id : 0;
    	$sql = "select a.* from {$this->tableNationalityGroup()} as a inner join {$this->tableToNationalityGroup()} as b on a.id = b.group_id
    	where a.state>0 and b.ticket_id=$id order by a.title";
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public function get_prices($item_id,$group_id,$season_id=-1){
    	$sql = "select a.* from {$this->tableToPrices()} as a where a.item_id=$item_id and a.group_id=$group_id";
    	$sql .= $season_id >-1 ? " and a.season_id=$season_id" : '';
    	$l = Yii::$app->db->createCommand($sql)->queryAll();
    	$r = [];
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			$r[$v['guest_group_id']]['price1'] = $v['price1'];
    			$r[$v['guest_group_id']]['currency'] = $v['currency'];
    		}
    	}
    	return $r;
    }
    public function get_price($item_id,$group_id,$guest_group_id,$season_id=-1){
    	$sql = "select a.* from {$this->tableToPrices()} as a where a.item_id=0 and a.group_id=$group_id and a.guest_group_id=$guest_group_id";
    	$sql .= $season_id >-1 ? " and a.season_id=$season_id" : '';
    	return Yii::$app->db->createCommand($sql)->queryOne();
    }
    
    //////////////////////////////////
    public static function getPrice($o = []){    	 
    	$item_id = isset($o['item_id']) ? $o['item_id'] : 0;    	
    	$nationality = isset($o['nationality']) ? $o['nationality'] : 0;
    	
    	//
    	$query = (new Query())->select(['a.price1','a.currency'])->from(['a'=>'tickets_to_prices'])->where([ 
    			'item_id'=>$item_id,
    			'guest_group_id'=>(new Query())->select('id')->from('guest_groups')->where(['is_default'=>1,'sid'=>__SID__]),
    			'group_id'=>(new Query())->select('group_id')->from('nationality_groups_to_local')->where([
    					'local_id'=>$nationality,
    					'group_id'=>(new Query())->select('group_id')->from('nationality_groups_to_ticket')->where(['item_id'=>$item_id])
    			]),
    	]);
    	//
    	//$price = 0;
    	$r = $query->one();
    	 
    	return [
    			'price1'=>!empty($r) ? $r['price1'] : 0,
    			'currency'=>!empty($r) ? $r['currency'] : 1
    	];
    }
    
    public static function updateSupplier($ticket_id, $supplier_id){
    	if((new Query())->from(self::tableToSupplier())->where(['supplier_id'=>$supplier_id,'ticket_id'=>$ticket_id])->count(1)==0){
    		Yii::$app->db->createCommand()->insert(self::tableToSupplier(), ['supplier_id'=>$supplier_id,'ticket_id'=>$ticket_id])->execute();
    	}
    }
    
    
    public static function getSupplier($ticket_id){
    	$query = (new Query())->from(['a'=>Customers::tableName()])
    	->innerJoin(['b'=>self::tableToSupplier()],'a.id=b.supplier_id')
    	->where(['b.ticket_id'=>$ticket_id]);
    	return $query->select(['a.*','title'=>'a.name'])->one();
    }
    
    public static function updateOldPlace(){
    	$l = (new Query())->from(self::tableName())->where([
    			'is_updated'=>0
    	])
    	->groupBy('place_id')
    	->all();
    	if(!empty($l)){
    		foreach ($l as $v){
    			$p = Places::getPlaceFromDepartureID($v['place_id']);
    			//view($v['place_id'].'-'.$p['id']. '-' . $p['title']);
    			Yii::$app->db->createCommand()->update(self::tableName(), [
    					'place_id'=>$p['id'],
    					'is_updated'=>1
    			],[    					
    					'is_updated'=>0,
    					'place_id'=>$v['place_id']
    			])->execute();
    		}
    	}
    }
    
    
    public static function getTrainTicketDetail($ticket_id){
    	$ticket = (new Query())->from(['a'=>'trains_to_prices'])
    	->innerJoin(['b'=>'tickets'],'b.id=a.ticket_id')
    	->where(['a.ticket_id'=>$ticket_id])->one();
    	if(!empty($ticket)){
    		$ticket['supplier'] = Customers::getItem($ticket['supplier_id']);
    		$ticket['room']=RoomsCategorys::getItem($ticket['item_id']);
    	}
    	return $ticket;
    }
    
    public function getTrainTicketPrice($o=[]){
    	
    	$quotation_id = isset($o['quotation_id']) && $o['quotation_id'] > 0 ? $o['quotation_id'] : 0;
    	$supplier_id = isset($o['supplier_id']) && $o['supplier_id'] > 0 ? $o['supplier_id'] : 0;
    	$service_id = isset($o['service_id']) && $o['service_id'] > 0 ? $o['service_id'] : 0;
    	$nationality_id = isset($o['nationality_id']) && $o['nationality_id'] > 0 ? $o['nationality_id'] : 0;
    	$time_id = isset($o['time_id']) ? $o['time_id'] : -1;
    	$day_id = isset($o['day_id']) ? $o['day_id'] : 0;
    	return;
    	if($supplier_id == 0 && $service_id>0){
    		$supplier_id = Yii::$app->zii->getSupplierIDFromService($service_id,TYPE_ID_TRAIN);
    	}
    	
    	$from_date = isset($o['from_date']) ? $o['from_date'] : date('Y-m-d');
    	
    	if($quotation_id == 0){
	    	$quotation = \app\modules\admin\models\Suppliers::getQuotation([
	    			'supplier_id'=>$supplier_id,
	    			'date'=>$from_date
	    	]);
	    	if(!empty($quotation)){
	    		$quotation_id = $quotation['id'];
	    	}
    	}
    	//
    	$nationality_group = \app\modules\admin\models\Suppliers::getNationalityGroup([
    			'supplier_id'=>$supplier_id,
    			'nationality_id'=>$nationality_id,
    	]);
    	//
    	return;
    	$seasons = \app\modules\admin\models\Suppliers::getSeasons([
    			'supplier_id'=>$supplier_id,
    			'date'=>$from_date,
    			'time_id'=>$time_id
    	]);

    	$groups = \app\modules\admin\models\Suppliers::getGuestGroup([
    			'supplier_id'=>$supplier_id,
    			
    			'date'=>$from_date,
    			'time_id'=>$time_id
    	]);
     
    	//
    	return Yii::$app->zii->getDefaultTrainTicketPrices([
    			'supplier_id'=>$supplier_id,
    			'controller_code'=>TYPE_ID_TRAIN,
    	]);
    	
    	
    	
    	
    }
}
