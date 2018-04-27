<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class Accounting extends \yii\db\ActiveRecord
{
	public static function tableName(){
		return '{{%acc_bills}}';
	}
	
	//
	public static function genCode($type_id = 1, $table = ''){
		if($table == ''){
			$table = self::tableName();
		}
		$code = false;
		switch ($type_id){
			case 1: $item['type'] = 'bill_in'; break;
			case 2: $item['type'] = 'bill_out'; break;
			//case 3: $item['type'] = 'bill_export'; break;
		}
		
		$count = (new Query())->from($table)->where(['sid'=>__SID__])->count(1);
		if(isset(Yii::$site['settings'][$item['type']]['code']) && !empty(Yii::$site['settings'][$item['type']]['code'])){
			
			$code_regex = Yii::$site['settings'][$item['type']]['code'];
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
	
	
	public static function getResonFromCode($code){
		return (new Query())->from(AccResons::tableName())->where(['code'=>$code])->one();
	}
	
	public static function getResonIdAuto($type_id=1){
		$code = '';
		switch ($type_id){
			case 1 : $code = 'TTBH'; break; // Thu
			case 2 : $code = 'CMH'; break; // Chi
			case 3 : $code = 'TTBH'; break;	// Xuất = Thu
			case 4 : $code = 'CMH'; break;	// Nhập = Chi
		}
		$r = self::getResonFromCode($code);
		if(!empty($r)){
			return $r['id'];
		}
		return 0;
	}
	
	public static function getResonAuto($type_id=1){
		$code = '';
		switch ($type_id){
			case 1 : $code = 'TTBH'; break; // Thu
			case 2 : $code = 'CMH'; break; // Chi
			case 3 : $code = 'TTBH'; break;	// Xuất = Thu
			case 4 : $code = 'CMH'; break;	// Nhập = Chi
		}
		$r = self::getResonFromCode($code);
		if(!empty($r)){
			return $r;
		}
		return [];
	}
	
	public static function getFund($currency = 1,$branch_id = 0){
		$branch_id = $branch_id > 0 ? $branch_id : Yii::$app->user->branch_id;
		return (new Query())->from('acc_funds')->where([
				'sid'=>__SID__,
				'branch_id'=>$branch_id,
				'currency'=>$currency,
				'is_locked'=>0
		])->one();
	}
	
	private static function createFundBook($currency = 1,$branch_id = 0){
		$branch_id = $branch_id > 0 ? $branch_id : Yii::$app->user->branch_id;
		$f = self::getFund($currency);
		if(!empty($f)){
			//
			return $f;
			//
		}else{
			$f = [
					'amount'=>0,
					'pre_amount'=>0,
					'lastmodify'=>time(),
					'updated_at'=>time(),
					'created_at'=>time(),
					'created_by'=>Yii::$app->user->id,
					'updated_by'=>Yii::$app->user->id,
					'sid'=>__SID__,
					'branch_id'=>$branch_id,
					'currency'=>$currency,
					
			];
			
			$f['check_sum'] = Yii::$app->security->generateFundBookCheckSum($f);
			Yii::$app->db->createCommand()->insert('acc_funds',$f)->execute();			
			return self::getFund($currency);
		}
	}
	
	private static function updateAccFundBook($data){
		if(!empty($data) && isset($data['type_id'])){
			$fund = self::getFund($data['currency']);
			$action = '+';
			switch ($data['type_id']){
				case 1: // Thu 
					break;
				case 2: // Chi
					$data['amount'] *= -1;
					$action = '-';
					break;
			}
			
			$last_amount = 0;
			if(!empty($fund)){
				//
				if(!Yii::$app->security->validateFundBookCheckSum($fund)){
					return false;
				}
				//
				$amount = $fund['amount'] + $data['amount'];
				$last_amount = $fund['amount'];
				$f = [
						'amount'=>$amount,
						'lastmodify'=>time(),
						'updated_at'=>time(),
						
				];
				Yii::$app->db->createCommand()->update('acc_funds',$f,[
						'id'=>$fund['id'],
						'currency'=>$data['currency'],
				])->execute();
				$fund['amount'] = $amount;
			}else{
				$amount = $data['amount'];
				$f = [
						'amount'=>$amount,
						'lastmodify'=>time(),
						'updated_at'=>time(),						
						'created_at'=>time(),
						'created_by'=>Yii::$app->user->id,
						'updated_by'=>Yii::$app->user->id,
						'sid'=>__SID__,
						'branch_id'=>Yii::$app->user->branch_id,
						'currency'=>$data['currency'],
				];
				$fund = $f;
				Yii::$app->db->createCommand()->insert('acc_funds',$f)->execute();
			}
			//			
			Yii::$app->db->createCommand()->update('acc_funds',['check_sum'=>Yii::$app->security->generateFundBookCheckSum($fund)],[
					'sid'=>__SID__,
					'currency'=>$data['currency'],
					'branch_id'=>Yii::$app->user->branch_id,
					'is_locked'=>0,
			])->execute();
			
			$biz = isset($data['bizrule']) ? json_decode($data['bizrule'],1) : []; 
			
			$logs = isset($fund['logs']) ? $fund['logs'] : [];
			$logs[time()] = $action . $data['amount'] . '. SD ' . $amount
					.$biz['reson_text']
					.'<br/>User: ' . Yii::$app->user->id;
			Siteconfigs::updateBizrule('acc_funds',['sid'=>__SID__,'currency'=>$data['currency'],'branch_id'=>Yii::$app->user->branch_id],
			[
					'logs'=>$logs
			]);
		}
		return true;
	}
	
	// Tạo phiếu thu / chi từ hóa đơn bán hàng / mua hàng
	public static function createBillFromPos($pos_bill_id){
	 	$bill = Bills::getItem($pos_bill_id);
	 	
	 	if(!empty($bill) && in_array($bill['type_id'],[1,2])){
	 		$data = [];
	 		$reson = self::getResonAuto($bill['type_id']);
	 		$bill_id = self::genCode($bill['type_id']);
	 		
	 		$fund = self::getFund($bill['currency']);
	 		if(!$fund){
	 			$fund = self::createFundBook($bill['currency']);
	 		}
	 		
	 		$data['id'] = $bill_id;
	 		$data['sid'] = __SID__;
	 		$data['created_at'] = time();
	 		$data['created_by'] = Yii::$app->user->id;
	 		$data['customer_id'] = $bill['customer_id'];
	 		$data['reson_id'] = $reson['id'];
	 		$data['branch_id'] = $bill['branch_id'];
	 		$data['amount'] = $bill['guest_pay'];
	 		$data['currency'] = $bill['currency'];	 		
	 		$data['bill_id'] = $bill['id'];
	 		$data['type_id'] = $bill['type_id'];
	 		$data['fund_id'] = $fund['fund_id'];
	 		$data['status'] = 1;
	 		$data['bizrule'] = json_encode([
	 				'customer'	=>	$bill['bill']['customer'],
	 				'book_number'	=> '01',
	 				'acc_credit'	=>	'', // Ghi có
	 				'acc_debit'		=>	'', // Ghi nợ
	 				'reson_text'	=>	$reson['title'],
	 				'attach'		=>	'01',
	 				'currency_text'	=>	readCurrency($data['amount'],$data['currency'],__LANG__),
	 				
	 		],JSON_UNESCAPED_UNICODE);
	 		
	 		Yii::$app->db->createCommand()->insert(self::tableName(), $data)->execute();
	 		
	 		Yii::$app->db->createCommand()->update(Bills::tableName(),['acc_bill_id'=>$bill_id],['id'=>$bill['id']])->execute();
	 		self::updateAccFundBook($data);
	 		return $bill_id;
	 	}
	}
	
	
	// Tạo phiếu xuất kho từ hóa đơn bán hàng
	public static function createBillExportFromPos($pos_bill_id){
		$bill = Bills::getItem($pos_bill_id);
		if(!empty($bill) && $bill['type_id'] == 1){ // Đúng là hóa đơn bán hàng mới lập phiếu
			$data = [];
			$type_id = 3;
			$bill_id = Bills::genCode($type_id);
			$data['type_id'] = $type_id;
			$data['id'] = $bill_id;
			$data['sid'] = __SID__;
			$data['created_at'] = $data['updated_at']= time();
			$data['created_by'] = Yii::$app->user->id;
			$data['customer_id'] = $bill['customer_id'];
			$data['branch_id'] = $bill['branch_id'];
			$data['total_item'] = $bill['total_item'];
			$data['currency'] = $bill['currency'];
			$data['pos_bill_id'] = $bill['id'];
			$data['order_id'] = $bill['order_id'];
			$data['grand_total'] = $bill['grand_total'];
			$data['discount_total'] = $bill['discount_total'];
			$data['sub_total'] = $bill['sub_total'];
			$data['bizrule'] = cjson(['listItem'=> $bill['bill']['listItem']]);
			$data['status'] = 1;
			Yii::$app->db->createCommand()->insert(Bills::tableName(),$data)->execute();
			return $bill_id;
		}
		return false;
	}
	
	
	
}
