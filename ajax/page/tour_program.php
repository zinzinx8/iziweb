<?php

switch (Yii::$app->request->post('action')){
	
	case 'quick-submit-quick-add-menu-food':
		$titles = explode(',', post('title'));
		$html = '';
		if(!empty($titles)){
			foreach ($titles as $title){
				//
				$v1= [];
				$title = trim($title);
				//
				if(strlen($title) > 1 && (new \yii\db\Query())->from('foods')->where(['title'=>$title,'sid'=>__SID__])->count(1) == 0){
					$v1['id'] = Yii::$app->zii->insert('foods',['title'=>$title,'sid'=>__SID__]);
					$v1['title'] = $title;
					
				}elseif(strlen($title)>1){
					$v1= (new \yii\db\Query())->from('foods')->where(['title'=>$title])->one();
				}
				if(!empty($v1)){
					$html .= '<li class="ui-state-default" data-title="'.$v1['title'].'" data-food_id="'.$v1['id'].'">'.$v1['title'].'
<input value="'.$v1['id'].'" type="hidden" class="selected_value_'.$supplier_id.' selected_value_'.$supplier_id.'_'.$day_id.'_'.$time_id.'" name="selected_value[]">
<input value="'.$v1['title'].'" type="hidden" class="selected_value_'.$supplier_id.' selected_value_'.$supplier_id.'_'.$day_id.'_'.$time_id.'" name="selected_title[]">
</li>';;
				}
			}
		}
		
		$callback_function .= 'izi.closeModal(".mymodal1");jQuery("#sortable1").append($d.html);';
		
		break;
	
	case 'quick-add-menu-food':
		
		$body .= '<fieldset><legend>Nhập tên món ăn, nhập nhiều món cách nhau bởi dấu phẩy ","</legend>';
		
		$body .= '<textarea name="title" rows=3 class="form-control required" placeholder="Nhập tên món ăn" required></textarea>';
		
		$body .= '</fieldset>';
		
		$modal = Yii::$app->zii->renderModal([
		//'action' => 'quick-sent-request-change-for-operator',
		'name'=>'mymodal1',
		'body'=>$body,
		'class'=>'w60',
		'title' => 'Thêm mới món ăn',
		'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-check-circle-o"></i> Xác nhận</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Đóng</button></div>'
				]);
		$complete_function .= 'izi.openModal($d.modal,".mymodal1");';
		break;
	
	case 'quick-search-menu-food':
		$existed = post('existed',[]);
		//$callback_function .= 'log(\''.$value.'\');';
		$query = (new \yii\db\Query())->from('foods')->where([
				'like','title',$value
		]);
		
		if(!empty($existed)){
			$query->andWhere(['not in','id',$existed]);
		}
		
		$l = $query->limit(50)
		->all();
		
		$html = '';
		if(!empty($l)){
			foreach ($l as $v1){
				$html .= '<li class="ui-state-highlight " data-title="'.$v1['title'].'" data-food_id="'.$v1['id'].'">'.$v1['title'].'
<input value="'.$v1['id'].'" type="hidden" class="selected_value_'.$supplier_id.' selected_value_'.$supplier_id.'_'.$day_id.'_'.$time_id.'" name="selected_value[]">
<input value="'.$v1['title'].'" type="hidden" class="selected_value_'.$supplier_id.' selected_value_'.$supplier_id.'_'.$day_id.'_'.$time_id.'" name="selected_title[]">
</li>';
			}
		}
		
		$callback_function .= 'jQuery("#sortable2").html($d.html);';
		
		break;
	case 'quick-submit-quick-change-service-day-detail-' . TYPE_ID_REST:
		// Thay đổi menu
		
		$con = [
		'item_id'=>$id,
		'service_id'=>$service_id,
		'type_id'=>$type_id,
		'day_id'=>$day_id,
		'time_id'=>$time_id,
		'package_id'=>$package_id,			
		];
		
		if($item_id != $new_item_id){						
			
			$columns = [
					'sub_item_id'=>$new_item_id,
					'price1'=>$new_price,
					'currency'=>$new_currency
			];
			
			// Cap nhat
			Yii::$app->db->createCommand()->update('tours_programs_services_prices', $columns,$con)->execute();			
			
		}
		// Cập nhật món ăn - tours_programs_services_days
		$selected_value = post('selected_value',[]);
		$selected_title = post('selected_title');
		
		$services[$new_item_id] = [];
		if(!empty($selected_value)){
			foreach ($selected_value as $k=>$v){
				$services[$new_item_id][] = [
					'id'=>$v,
						'title'=>$selected_title[$k]
				];
			}
		}
		//Yii::$app->db->createCommand()->update($table,  $columns,$con)->execute();
		\app\modules\admin\models\Siteconfigs::updateBizrule('tours_programs_services_days', $con, [
				'services'=>$services
		]);
		$callback_function .= 'closeAllModal();';
		$callback_function .= 'reloadAutoPlayFunction(true);'; 
		
		break;
		
	case 'Tour_change_menu_food':
		$value = post('value');
		$supplier_id = post('supplier_id');
		$list_childs = \app\modules\admin\models\Menus::getMenuFoods(['menu_id'=>$value]);
		$day_id= post('day_id',0);
		$time_id= post('time_id',0);
		$ls = $ls2 = '';
		$fexisted = [];
		if(!empty($list_childs)){
			foreach ($list_childs as $v1){
				$fexisted[] = $v1['id'];
				$ls .= '<li class="ui-state-default" data-title="'.$v1['title'].'" data-food_id="'.$v1['id'].'">'.$v1['title'].'
<input value="'.$v1['id'].'" type="hidden" class="selected_value_'.$supplier_id.' selected_value_'.$supplier_id.'_'.$day_id.'_'.$time_id.'" name="selected_value[]">
<input value="'.$v1['title'].'" type="hidden" class="selected_value_'.$supplier_id.' selected_value_'.$supplier_id.'_'.$day_id.'_'.$time_id.'" name="selected_title[]">
</li>';
			}
		}
		
		$list_childs = \app\modules\admin\models\Foods::getAll(['supplier_id'=>$supplier_id,'limit'=>50,'not_in'=>$fexisted]);
		
		if(!empty($list_childs)){
			foreach ($list_childs as $v1){
				$ls2 .= '<li class="ui-state-highlight" data-title="'.$v1['title'].'" data-food_id="'.$v1['id'].'">'.$v1['title'].'</li>';
			}
		}
		$responData['ls'] = $ls;
		$responData['ls2'] = $ls2;
		$callback_function .= '
jQuery("input[name=new_item_id]").val($this.val());
jQuery("input[name=new_price]").val($this.attr("data-price"));
jQuery("input[name=new_currency]").val($this.attr("data-currency"));
jQuery("#sortable1").html($d.ls);
jQuery("#sortable2").html($d.ls2);
var $pr = $this.parent().parent();
$pr.parent().find(".active").removeClass("active");
$pr.addClass("active");
';
		
		break;
	
	case 'quick-change-service-day-detail-'. TYPE_ID_REST: 
		$item_id = post('item_id',0);
		$id = post('id',0);
		$supplier_id = post('supplier_id',0);
		$package_id = post('package_id',0);
		$service_id = post('service_id',0);
		 
		$quantity = post('quantity',0);
		$currency = post('currency',1);
		$from_date = post('from_date', date('Y-m-d'));
		$day_id= post('day_id',0);
		$time_id= post('time_id',0);
		$body = '';
		$ps = $_POST;
		$ps['item_id'] = $id;
		$_POST['new_item_id'] = $item_id;
		$_POST['new_price'] = $price;
		$_POST['new_currency'] = $currency;
		$sv = \app\modules\admin\models\ToursPrograms::getProgramServiceDayDetail($id, $day_id,$time_id,$service_id);
		$services = isset($sv['services']) && !empty($sv['services']) ? $sv['services'] : [];
		 
		//$ticket = \app\modules\admin\models\Tickets::getTrainTicketDetail($service_id);
		$r2 = randString(10);
		$body .= '<fieldset class="f12px mgb15"><legend>Chọn thực đơn & món ăn<b class="green"></b></legend>';
		$l = \app\modules\admin\models\Menus::getMenus(['supplier_id'=>$supplier_id]);
		$body .= '<table class="table table-bordered">
<caption></cation>
<thead>';
if(!empty($l)){
	foreach ($l as $menu){
		$ps['service_id'] = $menu['id'];
		
		$price = \app\modules\admin\models\ToursPrograms::getServiceDefaultPrice($ps);
		
		$body .= '<th colspan="2" class="center '.($menu['id'] == $item_id ? 'active ' : '').'"><label><input 
type="radio"
class="input-select-menu-id"
data-supplier_id="'.$supplier_id.'"
data-price="'.(isset($price['price1']) ? $price['price1'] : 0) .'"
data-currency="'.(isset($price['price1']) ? $price['currency'] : 1) .'"
data-action="Tour_change_menu_food"
onchange="call_ajax_function(this);"
'.($menu['id'] == $item_id ? 'checked' : '').'
name="f[menu_id]" value="'.$menu['id'].'"/> '.$menu['title'].'</label>
'.(isset($price['price1']) && $price['price1'] > 0 ? '<p class="pm0 italic red f11px font-normal">(' . getCurrencyText($price['price1'], $price['currency']) . ')</p>' : '').'
</th>';
	}
}

 
$body .= '</thead>
<tbody>';
		
		$body .= '<tr>
<td colspan="'.count($l).'" class="success" style="width:50%"><p class="pm0 bold center">Dánh sách món ăn (đã chọn)</p></td>
<td colspan="'.count($l).'" class="warning" style="width:50%"><p class="pm0 bold center">Chọn thêm món</p></td>
</tr>';
$r = randString(10);
		$body .= '<tr>
<td colspan="'.count($l).'"><div class="selected_services div-slim-scroll">
<ul class="'.$r.' ui-sortable connectedSortable" id="sortable1">';
		$nsx = [];
		$list_childs = isset($sv['services'][$item_id]) && !empty($sv['services'][$item_id]) ? $sv['services'][$item_id] : \app\modules\admin\models\Menus::getMenuFoods(['menu_id'=>$item_id]);
		$fexisted = [];
		$ls = '';
		if(!empty($list_childs)){
			foreach ($list_childs as $v1){
				$body .= '<li class="ui-state-default" data-title="'.$v1['title'].'" data-food_id="'.$v1['id'].'">'.$v1['title'].'
<input value="'.$v1['id'].'" type="hidden" class="selected_value_'.$supplier_id.' selected_value_'.$supplier_id.'_'.$day_id.'_'.$time_id.'" name="selected_value[]">
<input value="'.$v1['title'].'" type="hidden" class="selected_value_'.$supplier_id.' selected_value_'.$supplier_id.'_'.$day_id.'_'.$time_id.'" name="selected_title[]">
</li>';
				$fexisted[] = $v1['id'];
			}
		}
		$r2=randString(8);
$body .= '</ul></div>
</td>
<td colspan="'.count($l).'">
<div class="input-group w100">
<input class="form-control" placeholder="Tìm kiếm nhanh món ăn" 
data-supplier_id="'.$supplier_id.'"
data-item_id="'.$item_id.'" 
data-time_id="'.$time_id.'" 
data-day_id="'.$day_id.'" 
data-type_id="'.$type_id.'"
data-existed="'.implode(',', $fexisted).'"
data-action="quick-search-menu-food"
onkeyup="Quick_search_menu_food(this);" 
onkeypress="return disabledFnKey(this);"
>
 
<span class="input-group-btn" title="Không tìm thấy món ăn phù hợp ? Bấm vào đây để thêm mới.">
<button class="btn btn-success" type="button"
data-loading="fb2"
data-supplier_id="'.$supplier_id.'"
data-item_id="'.$item_id.'" 
data-time_id="'.$time_id.'" 
data-day_id="'.$day_id.'" 
data-type_id="'.$type_id.'"
data-action="quick-add-menu-food"
onclick="call_ajax_function(this);"
><i class="fa fa-plus"></i></button>
</span>
</div>
<div class="available_services div-slim-scroll">
<ul class="'.$r.' ui-sortable connectedSortable" id="sortable2">';
		$list_childs = \app\modules\admin\models\Foods::getAll(['supplier_id'=>$supplier_id,'limit'=>50,'not_in'=>$fexisted]);
		
		if(!empty($list_childs)){
			foreach ($list_childs as $v1){
				$body .= '<li class="ui-state-highlight" data-title="'.$v1['title'].'" data-food_id="'.$v1['id'].'">'.$v1['title'].'</li>';
			}
		}
$body .= '</ul></div>
</td>
</tr>';
		$body.='</tbody></table>';		
		$body .= '</fieldset>';		
		
		$modal = Yii::$app->zii->renderModal([
				//'action' => 'quick-sent-request-change-for-operator',
				'name'=>$modalName,
				'body'=>$body,
				'class'=>'w80',
				'title' => 'Thay đổi thực đơn',
				'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-send-o"></i> Xác nhận</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Đóng</button></div>'
		]);
		
		$complete_function = '';
		$complete_function .= 'izi.openModal($d.modal);jQuery(".number_format1").number(true,0);
 
//jQuery(".input-select-menu-id:checked").change();
loadScrollDiv();
jQuery("#sortable1").sortable({

connectWith: ".connectedSortable",
receive:function(event,ui){
var $type_id = ui.item.attr(\'data-type_id\');
var $food_id = ui.item.attr(\'data-food_id\');
var $title = ui.item.attr(\'data-title\');
(ui.item).removeClass(\'ui-state-highlight\').addClass(\'ui-state-default\')
.append(\'<input value="\'+$food_id+\'" type="hidden" class="selected_value_'.$supplier_id.' selected_value_'.$supplier_id.'_'.$day_id.'_'.$time_id.' " name="selected_value[]"/>\\
<input value="\'+$title+\'" type="hidden" class="selected_value_'.$supplier_id.' selected_value_'.$supplier_id.'_'.$day_id.'_'.$time_id.' " name="selected_title[]"/>\')
},

}).disableSelection();

$( "#sortable2" ).sortable({
connectWith: ".connectedSortable",
receive:function(event,ui){
(ui.item).removeClass(\'ui-state-default\').addClass(\'ui-state-highlight\').find("input").remove()
},
    }).disableSelection();



';
		
		break;
		
		
		
	case 'quick-submit-quick-change-service-day-detail-'.TYPE_ID_HOTEL:
	case 'quick-submit-quick-change-service-day-detail-'.TYPE_ID_SHIP_HOTEL:
		$f = post('f');
		$item_id = post('id',0);
		$service_id = post('service_id',0);
		$type_id = post('type_id',0);
		$day_id = post('day_id',0);
		$time_id = post('time_id',0);
		$package_id = post('package_id',0);
		
		
		$total_price = $total_quantity = $avrg_price = 0;
		$services = [];
		if(!empty($f)){
			foreach ($f as $ticket_id => $v){
				$quantity = cprice($v['quantity']);
				$quantity = $quantity > 0 ? $quantity : 0;
				$price1 = cprice($v['price1']);
				$price1 = $price1> 0 ? $price1: 0;
				$total_quantity += $quantity;
				$total_price += $quantity * $price1;
				//
				if($quantity>0){
					$services [$ticket_id] = $v;
				}
			}
		}
		if($total_quantity>0){
			$avrg_price = $total_price/$total_quantity;
		}
		
	//	$services = '';
		
		$biz = ['services'=>$services];
		
		$a = \app\modules\admin\models\Siteconfigs::updateBizrule('tours_programs_services_days', [
				'package_id'=>$package_id,
				'item_id'=>$item_id,
				'service_id'=>$service_id,
				'type_id'=>$type_id,
				'day_id'=>$day_id,
				'time_id'=>$time_id,
		], $biz);
		
		Yii::$app->db->createCommand()->update('tours_programs_services_prices', [
				'quantity'=>$total_quantity,
				'price1'=>$avrg_price
		],[
				'package_id'=>$package_id,
				'item_id'=>$item_id,
				'service_id'=>$service_id,
				'type_id'=>$type_id,
				'day_id'=>$day_id,
				'time_id'=>$time_id,
		])->execute();
		
		$callback_function .= 'closeAllModal();reloadAutoPlayFunction(true);';  
		
		break;
		
	case 'quick-submit-quick-change-service-day-detail-' . TYPE_ID_TRAIN:
		$f = post('f');
		$item_id = post('id',0);
		$service_id = post('service_id',0);
		$type_id = post('type_id',0);
		$day_id = post('day_id',0);
		$time_id = post('time_id',0);
		$package_id = post('package_id',0);
		
		
		$total_price = $total_quantity = $avrg_price = 0;
		$services = [];
		if(!empty($f)){
			foreach ($f as $ticket_id => $v){
				$quantity = cprice($v['quantity']);
				$quantity = $quantity > 0 ? $quantity : 0;
				$price1 = cprice($v['price1']);
				$price1 = $price1> 0 ? $price1: 0;
				$total_quantity += $quantity;
				$total_price += $quantity * $price1;
				//
				if($quantity>0){
					$services [$ticket_id] = $v;
				}
			}
		}
		if($total_quantity>0){
			$avrg_price = $total_price/$total_quantity;
		}
		
		//	$services = '';
		
		$biz = ['services'=>$services];
		
		$a = \app\modules\admin\models\Siteconfigs::updateBizrule('tours_programs_services_days', [
				'package_id'=>$package_id,
				'item_id'=>$item_id,
				'service_id'=>$service_id,
				'type_id'=>$type_id,
				'day_id'=>$day_id,
				'time_id'=>$time_id,
		], $biz);
		
		Yii::$app->db->createCommand()->update('tours_programs_services_prices', [
				'quantity'=>$total_quantity,
				'price1'=>$avrg_price
		],[
				'package_id'=>$package_id,
				'item_id'=>$item_id,
				'service_id'=>$service_id,
				'type_id'=>$type_id,
				'day_id'=>$day_id,
				'time_id'=>$time_id,
		])->execute();
		
		$callback_function .= 'closeAllModal();reloadAutoPlayFunction(true);';
		
		break;
		
	
	case 'quick-change-service-day-detail-'. TYPE_ID_TRAIN:
		$item_id = post('item_id',0);
		$id = post('id',0);
		$supplier_id = post('supplier_id',0);
		$package_id = post('package_id',0);
		$service_id = post('service_id',0);
		$station_from = post('station_from',0);
		$station_to = post('station_to',0);
		$quantity = post('quantity',0);
		$currency = post('currency',1);
		$from_date = post('from_date', date('Y-m-d'));
		$day_id= post('day_id',0);
		$time_id= post('time_id',0);
		$body = '';
		
		$sv = \app\modules\admin\models\ToursPrograms::getProgramServiceDayDetail($id, $day_id,$time_id,$service_id);
		$services = isset($sv['services']) && !empty($sv['services']) ? $sv['services'] : [];
		
		$ticket = \app\modules\admin\models\Tickets::getTrainTicketDetail($service_id);
		$r2 = randString(10);
		$body .= '<fieldset class="f12px mgb15"><legend>Giá vé	<b class="green">'.$ticket['supplier']['name'].'</b> tuyến <b class="green">'.$ticket['title'].'</b></legend>';
		
		$body .= '<table class="table table-bordered table-hover">
<caption></cation>
<thead>
<th class="w50p center">STT</th>
<th class="center">Phòng / Ghế</th><th class="center">-</th>
<th class="center">Đơn giá ('.Yii::$app->zii->showCurrency(post('currency',1),1).')</th>

<th class="center w100p">Số lượng</th>
</thead>
<tbody>';
		foreach (\app\modules\admin\models\RoomsCategorys::getRoomBySupplier($supplier_id) as $k=>$v){
			$price = Yii::$app->zii->getDefaultTrainTicketPrices([
					'supplier_id'=>$supplier_id,
					'controller_code'=>TYPE_ID_TRAIN,
					'from_date'=>$from_date,
					'package_id'=>$package_id,
					'service_id'=>0,
					'room_id'=>$v['id'],
					'station_from'=>$station_from,
					'station_to'=>$station_to,
			]);
			if(!empty($services) && isset($services[$price['ticket_id']]['quantity'])){
				$q = $services[$price['ticket_id']]['quantity'];
				$price['price1'] = $services[$price['ticket_id']]['price1'];
			}else{
				$q =  $item_id == $v['id'] ? $quantity : '';
			}
			$body .= '<tr>
	<th scope="row" class="center">'.($k+1) .'</th>
<td>'.$v['title'].'
<input name="f['.$price['ticket_id'].'][title]" type="hidden" value="'.($v['title']).'"/>
<input name="f['.$price['ticket_id'].'][currency]" type="hidden" value="'.($currency).'"/>
</td>
<td class="center">-</td>
<td class="center bold">
<input name="f['.$price['ticket_id'].'][price1]" 
type="text" value="'.(isset($price['price1']) ? getCurrencyText($price['price1'],$price['currency'],['show_symbol'=>false]) : 0).'" 
class="form-control aright bold red number_format1"/></td>

<td><input name="f['.$price['ticket_id'].'][quantity]" type="text" value="'.$q.'" 
class="form-control center bold red number_format1" title="Nhập số lượng phòng" placeholder="Nhập SL phòng"/></td>
</tr>';
		}

$body.='</tbody>
</table>';
		
		
		$body .= '</fieldset>';
		
		
		
		
		
		
		$modal = Yii::$app->zii->renderModal([
				//'action' => 'quick-sent-request-change-for-operator',
				'name'=>$modalName,
				'body'=>$body,
				'class'=>'w60',
				'title' => 'Thay đổi số lượng phòng / ghế',
				'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-send-o"></i> Xác nhận</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Đóng</button></div>'
		]);
		
		$complete_function = '';		
		$complete_function .= 'izi.openModal($d.modal);jQuery(".number_format1").number(true,0); ';
		
		break;
		
	case 'quick-change-service-day-detail-'. TYPE_ID_HOTEL:
	case 'quick-change-service-day-detail-'. TYPE_ID_SHIP_HOTEL:
		$item_id = post('item_id',0);
		$id = post('id',0);
		$supplier_id = post('supplier_id',0);
		$package_id = post('package_id',0);
		$type_id = post('type_id');
		$service_id = post('service_id',0);
		//$station_from = post('station_from',0);
		//$station_to = post('station_to',0);
		$quantity = post('quantity',0);
		$currency = post('currency',1);
		$from_date = post('from_date', date('Y-m-d'));
		$day_id= post('day_id',0);
		$time_id= post('time_id',0);
		$body = '';
		
		$sv = \app\modules\admin\models\ToursPrograms::getProgramServiceDayDetail($id, $day_id,$time_id,$service_id);
		$services = isset($sv['services']) && !empty($sv['services']) ? $sv['services'] : [];
		
		$supplier = \app\modules\admin\models\Customers::getItem($supplier_id);
		$r2 = randString(10);
		$body .= '<fieldset class="f12px mgb15"><legend>Bảng giá phòng <b class="green">'.$supplier['name'].'</b></legend>';
		
		$body .= '<table class="table table-bordered table-hover">
<caption></cation>
<thead>
<th class="w50p center">STT</th>
<th class="center">Phòng</th><th class="center">-</th>
<th class="center">Đơn giá ('.Yii::$app->zii->showCurrency(post('currency',1),1).')</th>
		
<th class="center w100p">Số lượng</th>
</thead>
<tbody>';
		foreach (\app\modules\admin\models\RoomsCategorys::getRoomBySupplier($supplier_id) as $k=>$v){
			//break;
			$price = \app\modules\admin\models\ToursPrograms::getServiceDefaultPrice([
					'item_id'=>$id,
					'service_id'=>$service_id,
					'supplier_id'=>$supplier_id,
					'time_id'=>$time_id,
					'day_id'=>$day_id,
					'from_date'=>$from_date,
					'room_id'=>$v['id'],
					'type_id'=>$type_id,
					'package_id'=>$package_id
			]);
			if($v['id']==79){
				$callback_function .= 'log(\''.json_encode([
						'item_id'=>$id,
						'service_id'=>$service_id,
						'supplier_id'=>$supplier_id,
						'time_id'=>$time_id,
						'day_id'=>$day_id,
						'from_date'=>$from_date,
						'room_id'=>$v['id'],
						'type_id'=>$type_id,
						'package_id'=>$package_id
				]).'\');';
				$callback_function .= 'log(\''.json_encode($price).'\');';
			}
			if(!empty($services) && isset($services[$v['id']]['quantity']) && $services[$v['id']]['quantity'] > 0 ){
				$q = $services[$v['id']]['quantity'];
				$price['price1'] = $services[$v['id']]['price1'];
			}else{
				$q =  $item_id == $v['id'] ? $quantity : '';
			}
			$body .= '<tr>
	<th scope="row" class="center">'.($k+1) .'</th>
<td>'.$v['title'].'
<input name="f['.$v['id'].'][title]" type="hidden" value="'.($v['title']).'"/>
<input name="f['.$v['id'].'][currency]" type="hidden" value="'.($currency).'"/>
</td>
<td class="center">-</td>
<td class="center bold">
<input name="f['.$v['id'].'][price1]"
type="text" value="'.(isset($price['price1']) ? getCurrencyText($price['price1'],$price['currency'],['show_symbol'=>false]) : 0).'"
class="form-control aright bold red number_format1"/></td>
		
<td><input name="f['.$v['id'].'][quantity]" type="text" value="'.$q.'"
class="form-control center bold red number_format1" title="Nhập số lượng phòng" placeholder="Nhập SL phòng"/></td>
</tr>';
		}
		
		$body.='</tbody>
</table>';
		
		
		$body .= '</fieldset>';
		
		
		
		
		
		
		$modal = Yii::$app->zii->renderModal([
				//'action' => 'quick-sent-request-change-for-operator',
				'name'=>$modalName,
				'body'=>$body,
				'class'=>'w60',
				'title' => 'Thay đổi số lượng phòng',
				'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-send-o"></i> Xác nhận</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Đóng</button></div>'
		]);
		
		$complete_function = '';
		$complete_function .= 'izi.openModal($d.modal);jQuery(".number_format1").number(true,0); ';
		
		break;
	case 'Tour_change_selected_service_day_detail':
		$type_id = post('type_id',-1);
		$supplier_id= post('supplier_id',post('value',0));
		$station_id = post('station_id',[]);
		$selected = post('selected','');
		$day_id = post('day_id');
		$time_id = post('time_id');
		
		$l = \app\modules\admin\models\Stations::getAllSupplierStation($supplier_id,[
				'in'=>$station_id,
				'not_in_ticket'=>$selected,
				'is_default'=>1,'is_loop'=>true]);
		
		if(!empty($l)){
			foreach ($l as $v){
				$room = \app\modules\admin\models\RoomsCategorys::getItem($v['item_id']);
				$html .= '<li
data-type_id="'.$type_id.'" 
data-id="'.$v['ticket_id'].'" 
data-package_id="'.$v['package_id'].'"
class="ui-state-default">'.(\app\modules\admin\models\Stations::getTicketTitle($v['station_from'],$v['station_to'])).'
'.(!empty($room) ? '&nbsp; <i class="green" title="'.$room['note'].'">['.$room['title'].']</i>' : '').'</li>';
			}
		}
		
		$callback_function .= 'log(\''.json_encode($selected).'\');';
		$callback_function .= '
jQuery("#sortable2").html($d.html);

';
		
		break;
	
	case 'Tour_open_form_change_selected_service_day':
		$type_id = post('type_id',-1);
		$day_id = post('day');
		$time_id = post('time');
		$selected = post('selected','');
		//if(!Yii::$app->user->can(ROOT_USER)){
		//	$callback_function .= 'alert("Chức năng này đang được bảo trì. Vui lòng trở lại sau.");';
		//}else{
			$suppliers = \app\modules\admin\models\Customers::getAll(['type_id'=>$type_id]);
			$stations = \app\modules\admin\models\Stations::getAll(['type_id'=>$type_id]);
			$responData['select_supplier'] = '<div class="fl w50 ex-service-select-dropdown">
<label class="fl100">Đơn vị</label>
<select data-placeholder="Chọn đơn vị khai thác" 
onchange="Tour_quick_change_selected_tour_service_day(this);" 
data-action="Tour_change_selected_service_day_detail"
data-role="load_dia_danh"
data-field="supplier_id"
data-type_id="'.$type_id.'"
class="form-control input-sm chosen-select input-quick-search-supplier">';
			if(!empty($suppliers)){
				foreach ($suppliers as $supplier){
					$responData['select_supplier'] .= '<option value="'.$supplier['id'].'">'.$supplier['name'].'</option>';
				}
			}
			$responData['select_supplier'] .= '</select></div>';
			
			$responData['select_supplier'] .= '<div class="fl w50 ex-service-select-dropdown">
<label class="fl100">Ga tàu</label>
<select data-placeholder="Chọn ga đi hoặc ga đến"
data-field="station_id"
data-type_id="'.$type_id.'"
data-allow_single_deselect="1"
data-action="Tour_change_selected_service_day_detail"
onchange="Tour_quick_change_selected_tour_service_day(this);"
class="form-control input-sm chosen-select input-quick-search-station"><option value="0"></option>';
			if(!empty($stations)){
				foreach ($stations as $supplier){
					$responData['select_supplier'] .= '<option value="'.$supplier['id'].'">'.$supplier['title'].'</option>';
				}
			}
			$responData['select_supplier'] .= '</select></div>';
			
			$callback_function .= '
jQuery(".div-quick-search-service-col-right").html($d.select_supplier);
var $data = {};
$data.action = "Tour_change_selected_service_day_detail";
$data.type_id = '.$type_id.';
$data.supplier_id = jQuery(".input-quick-search-supplier").val();
$data.station_id = jQuery(".input-quick-search-station").val();
$data.day_id = '.$day_id.';
$data.time_id = '.$time_id.';
$data.selected = "'.$selected.'";
sentAjaxData($data);
chosen_select_init2();
';
		//}
		break;
	case 'Tour_service_day_remove':
		
		$body = '<fieldset class="f12px mgb15">
<legend>Lưu ý</legend>
<p class="pm0 italic text-muted">- </p>
<p class="pm0 italic text-muted">- </p>
				
</fieldset>';
		 
		 
		
		$body .= '</fieldset>';
		
		
		
		
		
		
		$modal = Yii::$app->zii->renderModal([
				//'action' => 'quick-sent-qoutation-for-sale',
				'name'=>$modalName,
				'body'=>$body,
				'class'=>'w60 mw500',
				'title' => 'Xác nhận xóa dịch vụ',
				'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-check-square-o"></i> Xác nhận</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Đóng</button></div>'
		]);
		
		$complete_function = '';
		
		$complete_function .= 'izi.openModal($d.modal);';
		
		break;
	
	case 'quick-submit-open-form-guide-note':
		$a = ['item_id',
		'segment_id',
		'supplier_id',
		'guide_id',
		'guide_type',
		'service_id',
		'quotation_id',
		'nationality_id',
		'season_id',
		'supplier_id',
		'total_pax',
		'weekend_id',
		'time_id',
		'package_id',
		'segment_parent_id',
		'quantity','number_of_day','root_guide_type','currency'
				];
		foreach ($a as $b){
			$$b = post($b,0);
		}
		$f = post('f',[]);
		$note = $f['note'];
		\app\modules\admin\models\Siteconfigs::updateBizrule('tours_programs_guides',[
				'supplier_id' => $supplier_id,
				'item_id' => $item_id,
				'guide_id' => $service_id,
				'segment_id' => $segment_id,
				'type_id' => $guide_type,
		],[
				'note'=>$note, 
				
		]);
		
		$callback_function .= 'closeAllModal();';
		
		break;
	
	case 'open-form-guide-note':
		$a = ['item_id','segment_id','supplier_id','guide_id','guide_type','service_id',
		'quotation_id',
		'nationality_id',
		'season_id',
		'supplier_id',
		'total_pax',
		'weekend_id',
		'time_id',
		'package_id',
		'segment_parent_id',
		'quantity','number_of_day','root_guide_type','currency'
		];
		foreach ($a as $b){
			$$b = post($b,0);
		}
		$guide = \app\modules\admin\models\ToursPrograms::getProgramGuideDetail([
				'item_id'=>$item_id,
				'type_id'=>$guide_type,
				'guide_type'=>$guide_type,				
				'guide_id' =>$service_id,
				'segment_id'=>$segment_id,
				'supplier_id' =>$supplier_id,
		]);
		$prices = Yii::$app->zii->getProgramGuidesPrices([
				'controller_code'=>TYPE_ID_GUIDES,
				'quotation_id'=>$quotation_id,
				'nationality_id'=>$nationality_id,
				'season_id'=>$season_id,
				'supplier_id'=>$supplier_id,
				'total_pax'=>$total_pax,
				'weekend_id'=>$weekend_id,
				'time_id'=>$time_id,
				'package_id'=>$package_id,
				'item_id'=>$item_id,
				'service_id'=>$service_id,			
				'segment_id'=>$segment_id,
				'segment_parent_id'=>$segment_parent_id,
				'loadDefault'=>false,
				'updateDatabase'=>false,
				'quantity'=>$quantity,
				'number_of_day'=>$number_of_day,
				'guide_type'=>$guide_type,
				'root_guide_type'=>$root_guide_type
				
				
		]);
		
		if(!empty($prices) && isset($prices['price1'])){
			$price = Yii::$app->zii->getServicePrice($prices['price1'],[
					'item_id'=>$item_id,
					//'price'=>$prices['price1'],
					'from'=>$prices['currency'],
					'to'=>$currency
			]);
		}
		
		$body .= '<div class="form-group">
<label class="col-sm-12 control-label aleft">'.$guide['supplier_name'] . ' - ' .$guide['title'].'</label>
<div class="col-sm-12"></div></div>';
		
		
		$body .= '<div class="form-group">
<label class="col-sm-12 control-label aleft">Số lượng</label>
<div class="col-sm-12"><input type="text" name="f[quantity]" class="form-control  "
placeholder="Số lượng HDV" value="'.$guide['quantity'].'"></div></div>';
		
		$body .= '<div class="form-group">
<label class="col-sm-12 control-label aleft">Số ngày</label>
<div class="col-sm-12"><input type="text" name="f[number_of_day]" class="form-control  "
placeholder="Số ngày đi tour" value="'.$prices['number_of_day'].'"></div></div>';
		
		$body .= '<div class="form-group">
<label class="col-sm-12 control-label aleft">Đơn giá ('.Yii::$app->zii->showCurrency($prices['currency'],1).')</label>
<div class="col-sm-12"><input type="text" name="f[price1]" class="form-control  "
placeholder="Đơn giá" value="'.(isset($prices['price1']) ? $prices['price1'] : '-').'"></div></div>';
		
		$body .= '<div class="form-group">
<label class="col-sm-12 control-label aleft">Ghi chú</label>
<div class="col-sm-12"><textarea  name="f[note]" class="form-control  "
placeholder="Ghi chú" >'.(isset($guide['note']) ? uh($guide['note']) : '').'</textarea></div></div>';
		
		
		$modal = Yii::$app->zii->renderModal([
				 
				'name'=>$modalName,
				'body'=>$body,
				'class'=>'w60',
				'title' => 'Thay đổi thông tin hướng dẫn viên',
				'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-save"></i> Lưu lại</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Đóng</button></div>'
		]);
		
		$complete_function = '';
		
		$complete_function .= 'izi.openModal($d.modal);';
		break;
	
	case 'Tour_load_other_cost':
		$item_id = post('item_id',0);
		$html .= Tour_load_other_cost($item_id);
		break;
		
	case 'quick-add-tours-program-other-prices':
		$f = post('f',[]);
		$id = post('id',0);
		$f['bizrule'] = json_encode(post('biz',[]));
		$f['item_id'] = post('item_id',0);
		//$f['segment_id'] = post('segment_id',0);
		//$f['type_id'] = post('guide_type',2);
		//
		$f['quantity'] = cprice($f['quantity']);
		$f['price1'] = cprice($f['price1']);
		//
		if($id == 0){
			Yii::$app->db->createCommand()->insert('{{%tours_programs_other_prices}}', $f)->execute();
		}else{
			Yii::$app->db->createCommand()->update('{{%tours_programs_other_prices}}', $f,[
					'id'=>$id
			])->execute();
		}
		 
		\app\modules\admin\models\ToursPrograms::Tour_update_other_price($f['item_id']);
		
		$callback_function .= 'reloadAutoPlayFunction(true);closeAllModal();';
		
		break;
		
	case 'add-tours-program-other-prices': 
		$html = '';
		$item_id = post('item_id',0);
		 
		$id = post('id',0); 
		 
		$item = \app\modules\admin\models\ToursPrograms::getItem($item_id);
		 
		$v = \app\modules\admin\models\ToursPrograms::Tour_get_other_cost_item($id);
		
		$html .= '<div class="form-group"><div class="col-sm-12"><label >Tên chi phí <i class="red font-normal">(*)</i></label><input type="text" name="f[title]" class="form-control required" required placeholder="Nhập tên chi phí" value="'.(isset($v['title']) ? uh($v['title']) : '').'"></div></div>';
		$html .= '<div class="form-group"><div class="col-sm-12"><label >Diễn giải  </label><input type="text" name="biz[note]" class="form-control " placeholder="Diễn giải" value="'.(isset($v['note']) ? uh($v['note']) : '').'"></div></div>';
		 
		$html .= '<div class="form-group"><div class="col-sm-12"><label >Đơn vị tính  </label>
<input type="text" name="biz[unit_price]" class="form-control " placeholder="Đơn vị tính: Chiếc, Cái, Thùng, ..." value="'.(isset($v['unit_price']) ? uh($v['unit_price']) : '').'"></div></div>';
		$html .= '<div class="form-group"><div class="col-sm-12"><label >Ghi chú vào KHHD  </label>
<input type="text" name="biz[plan_note]" class="form-control " placeholder="Thêm ghi chú vào KHHD" value="'.(isset($v['plan_note']) ? uh($v['plan_note']) : '').'"></div></div>';
		
		$html .= '<div class="form-group"><div class="col-sm-12"><label >Số lượng <i class="red font-normal">(*)</i></label>
<input type="number" min="0" name="f[quantity]" class="form-control " required placeholder="Số lượng" value="'.(isset($v['quantity']) ? ($v['quantity']) : 0).'"></div></div>';
		
		$html .= '<div class="form-group"><div class="col-sm-12 edit-form-left">
		'.Ad_edit_show_dropdown_currency($v,[
				'field'=>'price1',
				'label'=>'Đơn giá',
				'class'=>'bold red aleft required',
				'placeholder'=>'Nhập đơn giá',
				'currency_name'=>'f[currency]',
				'attrs'=>[
						'data-search'=>'hidden',
						'required'=>'required',
						'placeholder'=>'Nhập đơn giá',
				],
				//'data'=>\app\modules\admin\models\AdLanguage::getList(),
				'data-selected'=>[isset($v['currency']) ? $v['currency'] : 1],
				'option-value-field'=>'id',
				'option-title-field'=>'title',
		]).'</div></div>';
		
		 
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Xác nhận</button>';
		$html .= $id > 0 ? '<button 
data-action="open-confirm-dialog" 
data-title="Xác nhận xóa chi phí !" 
data-class="modal-sm" 
data-confirm-action="quick_delete_program_other_price" 
onclick="return open_ajax_modal(this);" 
data data-id="'.$id.'" data-item_id="'.$item_id.'" 
type="button" class="btn btn-warning"><i class="fa fa-trash "></i> Xóa chi phí</button>' : '';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Hủy</button>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		//
		
		//
		$callback_function .= 'load_number_format();';
		 
		break;
	
	case 'Tour_program_update_profit':
		$item_id = post('item_id');
		$value = post('value',0);
		Yii::$app->db->createCommand()->update(\app\modules\admin\models\ToursPrograms::tableName(), [
				'profit'	=>	$value,
		],[
				'id'=>$item_id,
		])->execute();
		
		$callback_function .= 'reloadAutoPlayFunction(true);';
		
		break;
		
	case 'Tour_program_update_vat':
		$item_id = post('item_id');
		$value = post('value',0);
		Yii::$app->db->createCommand()->update(\app\modules\admin\models\ToursPrograms::tableName(), [
				'vat_tax'	=>	$value,
		],[
				'id'=>$item_id,
		])->execute();
		
		$callback_function .= 'reloadAutoPlayFunction(true);';
		
		break;	
		
	case 'TourProgram_loadTotal':
		$item_id = post('item_id');
		
		$print = post('print') == 1 ? true : false;
		
		$profit_price = $vat_price = 0;				
		
		$item = \app\modules\admin\models\ToursPrograms::getItem($item_id);
		
		$profit_price = $item['net_price'] * $item['profit'] / 100;
		$vat_price = ($item['net_price']+$profit_price) * $item['vat_tax'] / 100;
		
		$avrg_price = $item['total_price'] / $item['guest'];
		
		$html .= '<div class="clear"></div>
<p class="upper bold grid-sui-pheader aleft ">Bảng tổng hợp chi phí</p>
<table class="table table-bordered vmiddle">
<tr class="bold"><td class="aright ">Chi phí dịch vụ ngày</td>
<td class="aright " colspan="2">
'.getCurrencyText($item['total_price1'],$item['currency'],['show_symbol'=>false]).'
</td></tr>
<tr class="bold">
<td class="aright ">Chi phí vận chuyển</td>
<td class="aright" colspan="2">
'.getCurrencyText($item['total_price2'],$item['currency'],['show_symbol'=>false]).'
</td></tr>
<tr class="bold"><td class="aright ">Chi phí hướng dẫn viên</td>
<td class="aright" colspan="2">
'.getCurrencyText($item['total_price3'],$item['currency'],['show_symbol'=>false]).'
</td></tr>

<tr class="bold"><td class="aright ">Chi phí khác</td>
<td class="aright" colspan="2">
'.getCurrencyText($item['total_price4'],$item['currency'],['show_symbol'=>false]).'
</td></tr>

<tr class="bold"><td class="aright ">Tổng (Giá NET)</td>
<td class="aright green" colspan="2">'.getCurrencyText($item['net_price'],$item['currency'],['show_symbol'=>false]).'</td></tr>

<tr class="bold"><td class="aright ">Lợi nhuận (%)</td>
<td class="center w100p">'.($print ? '<b>'.$item['profit'].'</b>': '<input type="number" 
class="form-control bold aright input-sm number-format"
onblur="call_ajax_function(this);"
data-action="Tour_program_update_profit" 
data-decimal="2" 
data-item_id="'.$item_id.'"
data-old="'.$item['profit'].'"
value="'.$item['profit'].'"/>').'

</td>
<td class="aright w250p">
'.getCurrencyText($profit_price,$item['currency'],['show_symbol'=>false]).'
</td>
</tr>
<tr class="bold"><td class="aright ">VAT (%)</td>
<td class="center w100p">'.($print ? '<b>'.$item['vat_tax'].'</b>' : '<input type="number" 
class="aright bold form-control input-sm number-format" data-decimal="2" 
onblur="call_ajax_function(this);"
data-action="Tour_program_update_vat" 
data-decimal="2" 
data-item_id="'.$item_id.'"
data-old="'.$item['vat_tax'].'"
value="'.$item['vat_tax'].'"/>').'

</td>
<td class="aright">
'.getCurrencyText($vat_price,$item['currency'],['show_symbol'=>false]).'
</td></tr>
<tr class="bold"><td class="aright ">Tổng cộng</td>
<td class="aright text-danger" colspan="2">'.getCurrencyText($item['total_price'],$item['currency'],['show_symbol'=>false]).'</td></tr>
<tr class="bold"><td class="aright ">Giá TB khách</td>
<td class="aright red" colspan="2">'.getCurrencyText($avrg_price,$item['currency'],['show_symbol'=>false]).'</td></tr>
					<tbody>';
		$html .= '</tbody></table>';
		break;
}