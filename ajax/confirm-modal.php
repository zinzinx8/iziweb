<?php 
use yii\db\Query; 
$html = $modal = $callback_function = $complete_function = $body = $event = '';
$modalName = 'mymodal';
$modalID = '#' . $modalName;
if(!defined('__IS_ROOT__')){
	define('__IS_ROOT__', Yii::$app->user->can(ROOT_USER));
}
$callback = $complete = true ;

$responData = [];
if(Yii::$app->request->method == 'POST'){
	$responData += $_POST;
}
switch (post('confirm-action')){
	
	case 'intra-confirm-request':
		//$callback = false; $callback_function = ''; $event = 'hide-modal'; $modal = '.mymodal,.mymodal1';
		$id = post('id',0);
		//$callback_function = '';
		$user = \app\modules\admin\models\SaleSentRequest::getUserConfirmed($id);
		 
		if(!empty($user)){
			$callback_function .= 'showModal(\'Thông báo\',\'Yêu cầu này đã được xác nhận trước đó. Bạn không thể xác nhận lại.\');';
		}else{
			
			$callback_function .= 'showModal(\'Thông báo\',\'Bạn đã xác nhận thành công\');';
			$callback_function .= 'jQuery(".btn-confirm-action").html("<i class=\"fa fa-check-square-o\"></i> Bạn đã xác nhận thành công").attr("disabled","");';
			
			\app\modules\admin\models\SaleSentRequest::confirmRequest($id);
			
		}
		
		break; 
	
	case 'quick-remove-member-class':
		$modal = '.mymodal,.mymodal1';
		$class_id = post('class_id',0); 
		$customer_id = post('customer_id',0);
		Yii::$app->db->createCommand()->delete('customers_to_class',[
				'class_id'=>$class_id,
				'customer_id'=>$customer_id
		])->execute();
		$callback = true;
		$callback_function = 'reloadAutoPlayFunction(true);';
		
		break; 
	case 'quick-delete-article-filter':
		$item_id = post('item_id',0); $filter_id = post('filter_id',0);
		Yii::$app->db->createCommand()->delete('articles_to_filters',[
				'item_id'=>$item_id,
				'filter_id'=>$filter_id
		])->execute();
		$callback = true;
		$event = 'hide-modal';
		$modal = '.mymodal';
		$callback_function = 'console.log(data);reloadAutoPlayFunction(true);';
		
		break;
	case 'quick-delete-article-filter-date-time':
		$item_id = post('item_id',0); $filter_id = post('filter_id',0);
		$price_code = post('price_code');
		Yii::$app->db->createCommand()->delete('articles_to_filters',[
				'item_id'=>$item_id,
				'filter_id'=>$filter_id
		])->execute();
		
		Yii::$app->db->createCommand()->delete('articles_prices_list',[
				'code'=>[$price_code,'old_'.$price_code],
				'sid'=>__SID__
		])->execute();
		
		$callback = true;
		$event = 'hide-modal';
		$callback_function = 'reloadAutoPlayFunction(true);';
		
		break;	
		
	case 'unSuspendUser':
		$id = post('id',0); $type_id = post('type_id',1);
		\common\models\Suspended::unSuspended($id,$type_id);
		$callback = true;
		$callback_function = 'window.location=window.location;';
		break;
	case 'addSuspendUser':
		$id = post('id',0); $type_id = post('type_id',1);
		\common\models\Suspended::addSuspended($id,$type_id);
		$callback = true;
		$callback_function = 'window.location=window.location;';
		break;
	case 'quick_delete_program_segment':
		$id = post('id',0);
		$item_id = post('item_id',0);
		$segment = \app\modules\admin\models\ProgramSegments::getItem($id);
		
		
		Yii::$app->db->createCommand()->delete(\app\modules\admin\models\ProgramSegments::tableName(),
				['or',['id'=>$id],['parent_id'=>$id]])->execute();
				
				$callback = true;
				$callback_function = 'reloadAutoPlayFunction(true);closeAllModal();';
				//loadTourProgramGuides($item_id,[
				//		'loadDefault'=>true,
				//		'updateDatabase'=>true,
				//]);
				break;
	case 'quick_delete_program_other_price':
		$id = post('id',0);
		$item_id = post('item_id',0);
		
		Yii::$app->db->createCommand()->delete('tours_programs_other_prices',
				[
						'id'=>$id, 'item_id'=>$item_id
						
				])->execute();
		\app\modules\admin\models\ToursPrograms::Tour_update_other_price($item_id);
		$callback = true;
		$callback_function = 'reloadAutoPlayFunction(true);closeAllModal();';
			
		break;			
				
	case 'quick_delete_program_segment_extend_price':
		$id = post('id',0);
		$item_id = post('item_id',0);
		
		Yii::$app->db->createCommand()->delete('tours_programs_segments_extend_prices',
				[
						'id'=>$id, 'item_id'=>$item_id
						
				])->execute();
				
				$callback = true;
				$callback_function = 'reloadAutoPlayFunction(true);';
				
				break;
				
				
	case 'quick-remove-supplier-seasons':
		$callback = true;
		$supplier_id = post('supplier_id');
		$season_id = post('season_id');
		$callback_function = 'reloadAutoPlayFunction(true);';
		Yii::$app->db->createCommand()->delete('seasons_categorys',['id'=>post('season_id'),'owner'=>post('supplier_id')])->execute();
		Yii::$app->db->createCommand()->delete('seasons_categorys_to_suppliers',['season_id'=>post('season_id'),'supplier_id'=>post('supplier_id')])->execute();
		
		
		$c = \app\modules\admin\models\Customers::getItem($supplier_id);
		switch ($c['type_id']){
			case TYPE_ID_VECL: case TYPE_ID_HOTEL:
				Yii::$app->db->createCommand()->delete(Yii::$app->zii->getTablePrice($c['type_id']),[
				'season_id'=>$season_id,
				'supplier_id'=>$supplier_id
				])->execute();
				Yii::$app->db->createCommand()->delete(Yii::$app->zii->getTablePrice($c['type_id']),[
						'weekend_id'=>$season_id,
						'supplier_id'=>$supplier_id
				])->execute();
				
				if($c['type_id'] == TYPE_ID_VECL){
					Yii::$app->db->createCommand()->delete(Yii::$app->zii->getTablePrice($c['type_id'],2),[
							'season_id'=>$season_id,
							'supplier_id'=>$supplier_id
					])->execute();
					Yii::$app->db->createCommand()->delete(Yii::$app->zii->getTablePrice($c['type_id'],2),[
							'weekend_id'=>$season_id,
							'supplier_id'=>$supplier_id
					])->execute();
				}
				break;
		}
		
		$callback_function .= 'closeAllModal();';
		
		break;
	case 'delete-supplier-vehicle':
		$callback = true;
		$callback_function = 'reloadAutoPlayFunction(true);';
		Yii::$app->db->createCommand()->delete('vehicles_to_cars',['parent_id'=>post('supplier_id'),'vehicle_id'=>post('vehicle_id')])->execute();
		break;
	case 'quick_delete_package_supplier':
		$supplier_id = post('supplier_id',0);
		$package_id = post('package_id',0);
		//
		Yii::$app->db->createCommand()->delete('package_to_supplier',['supplier_id'=>$supplier_id,'package_id'=>$package_id])->execute();
		if(post('trash') == 0){
			Yii::$app->db->createCommand()->delete('package_prices',['supplier_id'=>$supplier_id,'id'=>$package_id])->execute();
		}
		//
		switch (post('controller_code')){
			case TYPE_ID_REST:
				Yii::$app->db->createCommand()->delete('menus_to_prices',['supplier_id'=>$supplier_id,'package_id'=>$package_id])->execute();
				break;
		}
		//
		$event = 'hide-modal';
		$modal = '.mymodal1';
		$callback = true;
		$callback_function = 'jQuery(\'tr.tr-item-odr-'.$supplier_id.'-'.$package_id.'\').remove();';
		break;
	case 'quick_delete_quotation_supplier':
		$supplier_id = post('supplier_id',0);
		$quotation_id = post('quotation_id',0);
		//
		Yii::$app->db->createCommand()->delete('supplier_quotations_to_supplier',['supplier_id'=>$supplier_id,'quotation_id'=>$quotation_id])->execute();
		if(post('trash') == 0){
			Yii::$app->db->createCommand()->delete('supplier_quotations',['supplier_id'=>$supplier_id,'id'=>$quotation_id])->execute();
		}
		//
		
		$event = 'hide-modal';
		$modal = '.mymodal1';
		$callback = true;
		$callback_function = 'jQuery(\'tr.tr-item-odr-'.$supplier_id.'-'.$quotation_id.'\').remove();';
		break;
	case 'quick_delete_position_user':
		if(Yii::$app->user->can([ROOT_USER])){
			$position_id = post('position_id',0);
			$type = post('type',0);
			//
			Yii::$app->db->createCommand()->delete('positions_to_users',['position_id'=>$position_id,
					'user_id'=>__SID__,'type_id'=>$type])->execute();
			
			
			$event = 'hide-modal';
			$modal = '.mymodal1';
			$callback = true;
			$callback_function = 'jQuery(\'tr.tr-item-odr-'.$position_id.'\').remove();';
		}
		break;
	case 'quick_change_station_price_remove':
		$supplier_id = post('supplier_id',0);
		$quotation_id = post('quotation_id',0);
		$package_id = post('package_id',0);
		$station_from = post('station_from',0);
		$station_to = post('station_to',0);
		$ticket_id = post('ticket_id',0);
		// 1. Remove ticket
		Yii::$app->db->createCommand()->delete('tickets',[
				'id'=>(new Query())->from('trains_to_prices')->where([
						'supplier_id'=>$supplier_id,
						'quotation_id'=>$quotation_id,
						'package_id'=>$package_id,
						'station_from'=>$station_from,
						'station_to'=>$station_to
				])->select('ticket_id')
		])->execute();
		Yii::$app->db->createCommand()->delete('trains_to_prices',[
				
				'supplier_id'=>$supplier_id,
				'quotation_id'=>$quotation_id,
				'package_id'=>$package_id,
				'station_from'=>$station_from,
				'station_to'=>$station_to
				
		])->execute();
		
		$class_remove = '.tr-item-'.$supplier_id.'-'.$quotation_id.'-'.$package_id.'-'.$station_from.'-'.$station_to;
		$callback = true;
		$callback_function = 'closeAllModal(); jQuery(\''.$class_remove.'\').remove();';
		break;
	case 'quick_change_menu_price_remove':
		$event = 'hide-modal';
		$modal = '.mymodal';
		$callback = true;
		//
		$supplier_id = post('supplier_id');
		$supplier = \app\modules\admin\models\Customers::getItem($supplier_id);
		//
		$table = Yii::$app->zii->getTablePrice($supplier['type_id'],post('price_type',1));
		$con = [
				'supplier_id'=>$supplier_id,
				'item_id'=>post('item_id'),
		];
		if(post('parent_group_id',0)>0){
			$con['parent_group_id'] = post('parent_group_id',0);
		}
		if($table !== false){
			//
			switch ($supplier['type_id']){
				case TYPE_ID_HOTEL: case TYPE_ID_SHIP_HOTEL:
					Yii::$app->db->createCommand()->delete('rooms_to_hotel',[
					'parent_id'=>$supplier_id,
					'room_id'=>post('item_id'),
					])->execute();
					
					break;
				case TYPE_ID_REST:
					Yii::$app->db->createCommand()->delete('menus_to_suppliers',[
					'supplier_id'=>$supplier_id,
					'menu_id'=>post('item_id'),
					])->execute();
					break;
				case TYPE_ID_VECL: case TYPE_ID_SHIP:
					if(post('price_type',1) == 2){
						Yii::$app->db->createCommand()->delete('distances_to_suppliers',[
								'supplier_id'=>$supplier_id,
								'item_id'=>post('item_id'),
						])->execute();
					}
					break;
					
			}
			
			
			Yii::$app->db->createCommand()->delete($table,$con)->execute();
		}
		$pieces = [
				post('supplier_id'),
				post('quotation_id'),
				post('package_id'),
				post('nationality_id'),
				post('item_id'),
		];
		
		$callback_function = 'reloadAutoPlayFunction(true);';
		break;
		
}
echo json_encode([
		//'html'=>$_POST,
		'callback'=>$callback,
		'callback_function'=>$callback_function,
		'event'=>$event,
		'modal_target'=>$modal,
]); exit;