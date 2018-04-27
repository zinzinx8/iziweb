<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class Bills extends \yii\db\ActiveRecord
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
        return '{{%pos_bills}}';
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

    public static function getID($c=8){
    	$id = randString($c,'abcdefghijklmnopqrstuvw0123456789');
    	$i = 0;
    	while ((new Query())->from(self::tableName())->where(['sid'=>__SID__,'id'=>$id])->count(1) > 0){
    		$id = randString($c,'abcdefghijklmnopqrstuvw0123456789');
    		$i++;
    		if($i > 100){
    			return self::getID(++$c);
    		}
    	}
    	return $id;
    }     
     
    public static function getItemFromOrderId($order_id,$o=[]){
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->where(['a.order_id'=>$order_id, 'a.sid'=>__SID__])
    	->andWhere(['>', 'a.state',-2]);
    	$query->select(['a.*']);
    	$user  = isset($o['user']) && $o['user'] == true ? true : false;
    	if($user){
    		$query->leftJoin(['b'=>'users'],'b.id=a.created_by');
    		$query->addSelect(['staff_name'=>'b.name']);
    	}
    	if(isset($o['branch_id']) && $o['branch_id']>0){
    		
    	}
    	if(isset($o['branch']) && $o['branch']==true){
    		$query->leftJoin(['c'=>'branches'],'c.id=a.branch_id');
    		$query->addSelect(['branch_name'=>'c.name','branch_code'=>'c.short_name']);
    	}
    	
    	
    	$item = $query->asArray()->one();
    	//view($item);
    	return $item;
    }
    
    
    public static function getItem($id,$o=[]){
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->where(['a.id'=>$id, 'a.sid'=>__SID__]) 
    	->andWhere(['>', 'a.state',-2]);
    	$query->select(['a.*']);
    	$user  = isset($o['user']) && $o['user'] == true ? true : false;
    	if($user){
    		$query->leftJoin(['b'=>'users'],'b.id=a.created_by');
    		$query->addSelect(['staff_name'=>'b.name']);
    	}
    	if(isset($o['branch_id']) && $o['branch_id']>0){
    		
    	}
    	if(isset($o['branch']) && $o['branch']==true){
    		$query->leftJoin(['c'=>'branches'],'c.id=a.branch_id');
    		$query->addSelect(['branch_name'=>'c.name','branch_code'=>'c.short_name']);
    	}
    	
    	
    	$item = $query->asArray()->one();
    	//view($item);
    	return $item;
    }
    /*
     * 
     */
    public static function getList($o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.created_at'=>SORT_DESC ];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);    
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';    	
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : [1,2];
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$not_in = isset($o['not_in']) ? $o['not_in'] : [];
    	$user  = isset($o['user']) && $o['user'] == true ? true : false;
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
    	if(!empty($type_id)){
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
    	$query->select(['a.*']);
    	if($user){
    		$query->leftJoin(['b'=>'users'],'b.id=a.created_by');
    		$query->addSelect(['staff_name'=>'b.name']);
    	}
    	if(isset($o['branch_id']) && $o['branch_id']>0){
    		
    	}
    	if(isset($o['branch']) && $o['branch']==true){
    		$query->leftJoin(['c'=>'branches'],'c.id=a.branch_id');
    		$query->addSelect(['branch_name'=>'c.name','branch_code'=>'c.short_name']);
    	}
    	
    	$query
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
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.created_at'=>SORT_ASC ];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : [1,2];
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$not_in = isset($o['not_in']) ? $o['not_in'] : [];
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
    	if(!empty($type_id)){
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
    
    public static function genCode($type_id = 1, $table = ''){
    	if($table == ''){
    		$table = self::tableName();
    	}
    	$code = false;
		switch ($type_id){
			case 1: $item['type'] = 'bill'; break;
			case 2: $item['type'] = 'bill_import'; break;
			case 3: $item['type'] = 'bill_export'; break;
		}
		$code_regex = Yii::$site['settings'][$item['type']]['code'];    
		
		$con = ['sid'=>__SID__];		
		$query = (new Query())->from($table)->where(['sid'=>__SID__]);
		if($code_regex['code_before'] != ""){
			$query->andWhere(['like','id',$code_regex['code_before'].'%',false]);
		}
		if($code_regex['code_after'] != ""){
			$query->andWhere(['like','id','%'.$code_regex['code_after'],false]);
		}
		$count = $query->count(1);
    	if(isset(Yii::$site['settings'][$item['type']]['code']) && !empty(Yii::$site['settings'][$item['type']]['code'])){
    		
    				
    		$code_rule = Yii::$site['settings'][$item['type']]['code']['code_rule'];
    		$code_length = $code_regex['code_length'] > 0 ? $code_regex['code_length'] : 3;
    		
    		$code_length = max($code_length,strlen($count));
    		
    		if($code_regex['code_length']>0){
    			$code_length = $code_regex['code_length'] - (strlen($code_regex['code_before']) + strlen($code_regex['code_after']));
    		}
    		
    		if(isset($code_regex['sort_asc']) && $code_regex['sort_asc'] == 'on'){
    			$code_identity = $count+1;
    		}else{
    			$code_identity = randString($code_length,$code_regex['code_regex']);
    		}
    		    		
    		
    		$replace_regex['{CODE_IDENTITY}'] = danhso($code_identity,$code_length);
    		$replace_regex['{CODE_RANDOM}'] = randString($code_length,$code_regex['code_regex']);
    		$replace_regex['{TOUR_START_PLACE}'] = '';
    		$replace_regex['{CODE_BEFORE}'] = $code_regex['code_before'];
    		$replace_regex['{CODE_AFTER}'] = $code_regex['code_after'];
    		 
    		
    		//
    		$code = replaceCode ($code_rule,$replace_regex); 
    		while((new Query())->from($table)->where([
    				//'and',[
    						'id'=>$code,
    						'sid'=>__SID__
    						
    				//],[
    				//		'not in','id',$id
    				//]
    		])->count(1)>0){    			
    			if(isset($code_regex['sort_asc']) && $code_regex['sort_asc'] == 'on'){
    				$code_identity++;
    			}else{
    				$code_identity = randString($code_length,$code_regex['code_regex']); 
    			}
    			$replace_regex['{CODE_IDENTITY}'] = danhso($code_identity,$code_length);
    			$code = replaceCode($code_rule,$replace_regex);
    		}
    	}
    	return $code;
    }
    
    public static function createBill($bill = [], $o = []){
    	if(is_numeric($bill) && $bill>0){
    		$bill = Yii::$app->getBill($bill);
    	}
    	if(!empty($bill)){
    		$status = isset($o['status']) ? $o['status'] : -1;
    		$order_id = isset($o['order_id']) ? $o['order_id'] : '';
    		$bill_id = self::genCode();
    		$data = [
    				'id'	=>	$bill_id,
    				'sid'	=>	__SID__,
    				'created_by'	=>	Yii::$app->user->id,
    				'customer_id'	=>	$bill['customer']['id'],
    				'created_at'	=>	__TIME__,
    				'updated_at'	=>	__TIME__,
    				'branch_id'		=>	Yii::$app->user->branch_id,
    				'order_id'		=> 	$order_id,
    				'status'		=>	$status,
    				'total_item'	=>	$bill['total_item'],
    				'sub_total'		=>	$bill['sub_total'], 
    				'grand_total'	=>	$bill['grand_total'],
    				'guest_pay'		=>	$bill['guest_pay'],
    				'owed_total'	=>	$bill['grand_total'] - $bill['guest_pay'],
    				'bizrule'		=> 	json_encode(['bill'=>$bill]),
    		];
    		Yii::$app->db->createCommand()->insert(self::tableName(), $data)->execute();
    		return $bill_id;
    	}
    }
    
    public static function importBillFromOrder1($order_code){
    	$order = Orders::getItemByCode($order_code);
    //	view($order,true);
    	if(!empty($order)){
    		$total_quantity = 0;
    		if(!empty($order['listItem'])){
    			foreach ($order['listItem'] as $item){
    				$total_quantity += $item['amount'];
    			}
    		}
    		$status = 2;
    		$order_id = $order['code'];
    		//view($bill);
    		//
    		//view($order);
    		$bill['customer'] = $order['guest'];
    		$bill['customer']['id'] = $order['mem_id'];
    		if(isset($order['guest']['full_name'])){
    			$bill['customer']['name'] = $order['guest']['full_name'];
    		}
    		$bill['listItem'] = [];
    		$bill['discount'] = ['value'=>0,'type'=>'%'];
    		
    		$bill['sub_total'] = $order['total_price'];
    		//$bill['total'] = $order['total_price'];
    		$bill['total_item'] = $total_quantity;
    		$bill['discount_value'] = 0;
    		$bill['discount_total'] = 0;
    		$bill['ship_total'] = 0;
    		
    		
    		$bill['guest_pay'] = $order['total_price'];
    		$bill['excess_cash'] = 0;
    		$bill['guest_cash'] = [
    				'cash'=>$order['total_price'],
    				'atm'=>0,
    				'visa'=>0
    		];
    		
    		$bill['currency'] = $order['currency'];
    		
    		$bill['grand_total'] = $bill['sub_total'] - $bill['discount_total'] + $bill['ship_total'];
    		
    		$bill['owed_total'] = $bill['guest_pay'] - $bill['grand_total'];
    		
    		//$bill[''] = 0;
    		
    		//if(!empty($order['seller'])){
    		//	foreach ($order['seller'] as $seller_id => $cart){
    		if(!empty($order['listItem'])){
    			foreach ($order['listItem'] as $v){
    						if(!isset($v['code'])){
    							$i = \app\modules\admin\models\Content::getItem($v['id']);
    							$v['code'] = $i['code'];
    						}else{
    							$i = \app\modules\admin\models\Content::getItem($v['id']);
    						}
    						$v['price2'] = $v['price'];
    						$v['discount'] = ['value'=>0,'type'=>'%'];
    						$bill['listItem'][$v['id']] = $v;
    						$bill['listItem'][$v['id']]['quantity'] = $v['amount'];
    						$bill['listItem'][$v['id']]['sub_total'] = $v['amount'] * $v['price'];
    						
    						$bill['listItem'][$v['id']]['icon'] = $i['icon'];
    					}
    				}
    		//	}
    			
    		//}
    		$bill_id = self::genCode();
    		//
    		$data = [
    				'id'			=>	$bill_id,
    				'sid'			=>	__SID__,
    				'created_by'	=>	Yii::$app->user->id,
    				'customer_id'	=>	$bill['customer']['id'],
    				'created_at'	=>	__TIME__,
    				'updated_at'	=>	__TIME__,
    				'branch_id'		=>	Yii::$app->user->branch_id,
    				'order_id'		=> 	$order_id,
    				'status'		=>	$status,
    				'total_item'	=>	$bill['total_item'],
    				'sub_total'		=>	$bill['sub_total'],
    				'grand_total'	=>	$bill['grand_total'],
    				'owed_total'	=>	$bill['owed_total'],
    				'guest_pay'		=>	$bill['guest_pay'],
    				'bizrule'		=> 	json_encode(['bill'=>$bill]),
    				'currency'		=>	$order['currency'],
    		];
    		
    		  
    		if(!empty(self::findBillFromOrder($order_id))){
    			unset($data['id']);
    			Yii::$app->db->createCommand()->update(self::tableName(), $data,[
    					'order_id'=>$order_id,
    					'sid'=>__SID__
    			])->execute();
    			$bill = (new Query())->from(self::tableName())->where([
    					'order_id'=>$order_id,
    					'sid'=>__SID__
    			])->one();
    			$bill_id = $bill['id'];
    		}else{
    			Yii::$app->db->createCommand()->insert(self::tableName(), $data)->execute();
    		}
    		
    		return $bill_id;
    	}
    }
    
    public static function importBillFromOrder($order_code){
    	$order = Orders::getItemByCode($order_code);
    	
    	if(!empty($order)){
    		
    		if($order['version'] == 1){
    			return self::importBillFromOrder1($order_code);
    		}
    		
    		$status = 2;
    		$order_id = $order['code'];
    		//view($bill);
    		//
    		//view($order);
    		$bill['customer'] = $order['customer'];
    		$bill['customer']['id'] = $order['mem_id'];
    		$bill['listItem'] = [];
    		$bill['discount'] = ['value'=>0,'type'=>'%'];
    		
    		$bill['sub_total'] = $order['total_price'];
    		//$bill['total'] = $order['total_price'];
    		$bill['total_item'] = $order['total_quantity'];
    		$bill['discount_value'] = 0;
    		$bill['discount_total'] = 0;
    		$bill['ship_total'] = 0;
    		
    		
    		$bill['guest_pay'] = $order['total_price'];
    		$bill['excess_cash'] = 0;
    		$bill['guest_cash'] = [
    				'cash'=>$order['total_price'],
    				'atm'=>0,
    				'visa'=>0
    		];
    		
    		$bill['currency'] = $order['currency'];
    		
    		$bill['grand_total'] = $bill['sub_total'] - $bill['discount_total'] + $bill['ship_total'];
    		
    		$bill['owed_total'] = $bill['guest_pay'] - $bill['grand_total']; 
    		
    		//$bill[''] = 0;
    		
    		if(!empty($order['seller'])){
    			foreach ($order['seller'] as $seller_id => $cart){
    				if(!empty($cart['listItem'])){
    					foreach ($cart['listItem'] as $v){
    						if(!isset($v['code'])){
    							$i = \app\modules\admin\models\Content::getItem($v['id']);
    							$v['code'] = $i['code'];
    						}
    						$v['price'] = $v['price2'];
    						$v['discount'] = ['value'=>0,'type'=>'%'];
    						$bill['listItem'][$v['id']] = $v;
    						$bill['listItem'][$v['id']]['sub_total'] = $v['amount'];
    					}
    				}
    			}
    			
    		}
    		$bill_id = self::genCode();
    		//
    		$data = [
    				'id'			=>	$bill_id,
    				'sid'			=>	__SID__,
    				'created_by'	=>	Yii::$app->user->id,
    				'customer_id'	=>	$bill['customer']['id'],
    				'created_at'	=>	__TIME__,
    				'updated_at'	=>	__TIME__,
    				'branch_id'		=>	Yii::$app->user->branch_id,
    				'order_id'		=> 	$order_id,
    				'status'		=>	$status,
    				'total_item'	=>	$bill['total_item'],
    				'sub_total'		=>	$bill['sub_total'],
    				'grand_total'	=>	$bill['grand_total'],
    				'owed_total'	=>	$bill['owed_total'],
    				'guest_pay'		=>	$bill['guest_pay'],
    				'bizrule'		=> 	json_encode(['bill'=>$bill]),
    				'currency'		=>	$order['currency'],
    		];
    		
    		if(!empty(self::findBillFromOrder($order_id))){
    			unset($data['id']);
    			Yii::$app->db->createCommand()->update(self::tableName(), $data,[
    					'order_id'=>$order_id,
    					'sid'=>__SID__
    			])->execute();
    			$bill = (new Query())->from(self::tableName())->where([
    					'order_id'=>$order_id,
    					'sid'=>__SID__
    			])->one();
    			$bill_id = $bill['id'];
    		}else{
    			Yii::$app->db->createCommand()->insert(self::tableName(), $data)->execute();
    		}
    		
    		return $bill_id;
    	}
    }
    
    public static function findBillFromOrder($code){
    	if($code != ""){
    		return self::find()->where([
    				'sid'=>__SID__,
    				'order_id'=>$code
    		])->asArray()->one();
    	}
    	return false;
    }
    
    public static function showStatus($status){
    	$class = ''; $text = '-';
    	switch ($status){
    		// Pos: 
    		case 1: $text = '<i class="yellow">[pos] Hoàn thành</i>'; $class = 'label-success';		break;
    		
    		case 2:    			 	// Lưu tạm    		
    		case 3:    			
    		case 4:    			
    			$text = 'Đang chờ'; $class = 'label-warning';
    			break;	
    		
    		
    		case 5: 
    			$text = 'Xác nhận'; $class = 'label-danger';
    			break;	// Đã xn
    		case 6:    			    		    		
    		case 7:    			
    			$text = 'Hoàn thành'; $class = 'label-success';
    			break;	// Hoàn tất đơn hàng
    		
    		case 8:    			
    			$text = 'Giao hàng'; $class = 'label-info';
    			break;	// Giao hàng
    		
    		case 15:
    			$text = '<i class="line-through">Đã hủy</i>'; $class = 'label-default';
    			break;	// Đã hủy
    		default: 
    			
    			break;
    	}
    	
    	return '<span class="label '.$class.'">'.$text.'</span>';
    }
    
    public static function updateOrderStatus($status,$order_code){
    	Yii::$app->db->createCommand()->update(Orders::tableName(), ['state'=>$status],[
    			'sid'=>__SID__,
    			'code'=>$order_code
    	])->execute();
    }
}
