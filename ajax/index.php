<?php
use yii\db\Query;
use app\modules\admin\models\Siteconfigs;
use app\modules\admin\models\Content;
use app\modules\admin\models\Menu;
use app\modules\admin\models\AdminMenu;

//
$html = $modal = $callback_function = $complete_function = $body = '';
$modalName = post('modal_name',getParam('modal_name','mymodal')) ;
$modalID = '#' . $modalName;

$modal_target = '.' .(post('modal_target',getParam('modal_target','mymodal')));

if(!defined('__IS_ROOT__')){
	define('__IS_ROOT__', Yii::$app->user->can(ROOT_USER));
}
$callback = $complete = true ;

$responData = [];
if(Yii::$app->request->method == 'POST'){
	foreach ($_POST as $k=>$v){
		$$k = $v;
	}
	$responData += $_POST;
}
//////////////////////////////////////////////////////////////////////
include_once __DIR__ . '/_get_action.php';
include_once __DIR__ . '/preloadAction.php';
$dirs = scandir(__DIR__ .DIRECTORY_SEPARATOR . 'page');
if(!empty($dirs)){
	foreach ($dirs as $dir){
		if(!in_array($dir, ['.','..'])){
			include_once __DIR__ . '/page/' . $dir;
		}
	}
}

///////////////////////////////////////
//////////////////////////////////////////////////////////////////////
switch (Yii::$app->request->post('action')){
	
	case 'set_developer_domain_templete':
		$domain = post('domain');
		$temp_id = post('temp_id');
		$value = post('value');
		if($value == 'on'){
			Yii::$app->db->createCommand()->update('domain_pointer', ['state'=>3],[
					'domain'=>$domain,
					'sid'=>__SID__,
			])->execute();
			//
			//Yii::$app->db->createCommand()->update('temp_to_shop', ['state'=>2],[
			//		'sid'=>__SID__,
			//		//'temp_id'=>$temp_id,
			//		'state'=>3
			//])->execute();
			//
			Yii::$app->db->createCommand()->update('temp_to_shop', ['state'=>3],[
					'sid'=>__SID__,'temp_id'=>$temp_id
			])->execute();
			
		}else{
			Yii::$app->db->createCommand()->update('domain_pointer', ['state'=>1],[
					'domain'=>$domain,
					'sid'=>__SID__,
			])->execute();
			//
			Yii::$app->db->createCommand()->update('temp_to_shop', ['state'=>2],[
					'sid'=>__SID__,'temp_id'=>$temp_id
			])->execute();			
			
		}
		//$callback_function .= 'log(\''.$value.'\');';
		
		break;
	case 'Product_change_preorder_status':
		$value = post('value',0);
		$item_id = post('item_id',0);
		$item = \app\modules\admin\models\Content::getItem($item_id);
		
		$html = '';
		$r = randString(8);
		//if(!empty($item)){
			if($value == 0){
				$html = '<p class="pre_order_rsx_0 text-muted italic">Đơn hàng sẽ được giao ngay sau khi xác nhận thành công.</p>
<input type="hidden" class="w50p center" name="biz[pre_order_number]" value="0"/>
';
			}else{
				$pre_order_number = isset($item['pre_order_number']) && $item['pre_order_number'] > 0 ? $item['pre_order_number'] : 7;
				$html = '<div class="pre_order_rsx_1 text-muted italic">Đơn hàng sẽ được giao sau 
				<input type="number" class="w50p center '.$r.'" name="biz[pre_order_number]" value="'.$pre_order_number.'"/> ngày làm việc
				</div>';
			}
			$callback_function .= 'jQuery(".pre_order_rsx").html($d.html);jQuery(".'.$r.'").focus();'; 
		//} 
		
		break;
	case 'Product_detail_reload_tbl_price':
		$item_id = post('item_id',0);
		$type_id = post('type_id');
		$cpk = false;
		$v = \app\modules\admin\models\Content::getItem($item_id);
		$c2 = \app\modules\admin\models\TextAttrs::getVisibledCategory([
				'in'=>isset($v['classification']) ? $v['classification'] : []
		]);
		if(!empty($c2)){
			foreach ($c2 as $k2=>$v2 ){
				//if(isset(Yii::$site['settings'][getParam('type')]['fields']['attr'][$v2['id']]) && Yii::$site['settings'][getParam('type')]['fields']['attr'][$v2['id']]== 'on'){
					$type_id = $v2['id'];
					$vrs = 'lx'.$k2;
					$$vrs= \app\modules\admin\models\Content::getItemTextAttrs($item_id, $type_id);
					if(!$cpk && !empty($$vrs)){
						$cpk = true;
					}
					
				//}
			}
			//
			$stores = \app\modules\admin\models\Warehouse::getListByUser();
			$xc = [];
			if(isset($lx0) && !empty($lx0)){
				foreach ($lx0 as $x){
					$title = $x['title'];
					if(isset($lx1) && !empty($lx1)){
						
						foreach ($lx1 as $x1){
							if(isset($lx2) && !empty($lx2)){
								foreach ($lx2 as $x2){
									$price_code = ''.$x['id'].'-'.$x1['id'] . '-' . $x2['id'];
									$prices = (\app\modules\admin\models\Content::getItemPrices($id,[$price_code]));
									$sku = (isset($prices[$price_code]['sku']) ? $prices[$price_code]['sku'] : '');
									$xc[$price_code] = '<tr class="tr-user-pras-'.$price_code.'">
<td>'.$x['title'].' - '.$x1['title'].' - '.$x2['title'].'
<input type="hidden" name="" value="'.$price_code.'" />
</td>
<td class="center">
<input type="text" name="item_price2['.$price_code.'][sku]" class="form-control input-sm required" value="'.(isset($prices[$price_code]['sku']) ? $prices[$price_code]['sku'] : '').'" placeholder="Nhập mã SKU phân loại sản phẩm"/>	 		
</td>
<th class="center "><input name="item_price2['.$price_code.'][price2]" type="text" class="form-control input-sm aright number-format bold input-currency-price-00 required" required value="'.(isset($prices[$price_code]['price']) ? $prices[$price_code]['price'] : '').'" placeholder="Giá sản phẩm"/></th>
        		
';
							if(!empty($stores)){
								foreach ($stores as $store){
									$xc[$price_code] .= '<th class="center "><input name="item_price2['.$price_code.'][quantity]['.$store['id'].']" type="text" class="form-control input-sm center number-format" value="'.(\app\modules\admin\models\Warehouse::getItemQuantity([
											'item_id'=>$item_id,
											'warehouse_id'=>$store['id'],
											'sku'=>$sku,
									])).'" placeholder="Kho hàng"/></th>';
								}
							}
							$xc[$price_code] .= '</tr>';
								}
							}else{
								$price_code = ''.$x['id'].'-'.$x1['id'];
								$prices = (\app\modules\admin\models\Content::getItemPrices($item_id,[$price_code]));
								$sku = (isset($prices[$price_code]['sku']) ? $prices[$price_code]['sku'] : '');
								$xc[$price_code] = '<tr class="tr-user-pras-'.$price_code.'">
<td>'.$x['title'].' - '.$x1['title'].'
<input type="hidden" name="" value="'.$price_code.'" />
</td>
<td class="center">
<input type="text" name="item_price2['.$price_code.'][sku]" class="form-control input-sm required" value="'.(isset($prices[$price_code]['sku']) ? $prices[$price_code]['sku'] : '').'" placeholder="Nhập mã SKU phân loại sản phẩm"/>	 		
</td>
<th class="center "><input name="item_price2['.$price_code.'][price2]" type="text" class="form-control input-sm aright number-format bold input-currency-price-00 required" required value="'.(isset($prices[$price_code]['price']) ? $prices[$price_code]['price'] : '').'" placeholder="Giá sản phẩm"/></th>
        		
';
							if(!empty($stores)){
								foreach ($stores as $store){
									$xc[$price_code] .= '<th class="center "><input name="item_price2['.$price_code.'][quantity]['.$store['id'].']" type="text" class="form-control input-sm center number-format" value="'.(\app\modules\admin\models\Warehouse::getItemQuantity([
											'item_id'=>$item_id,
											'warehouse_id'=>$store['id'],
											'sku'=>$sku,
									])).'" placeholder="Kho hàng"/></th>';
								}
							}
							$xc[$price_code] .= '</tr>';
							}
						}
					}else{
						$price_code = ''.$x['id'];
						$prices = (\app\modules\admin\models\Content::getItemPrices($item_id,[$price_code]));
						$sku = (isset($prices[$price_code]['sku']) ? $prices[$price_code]['sku'] : '');
						$xc[$price_code] = '<tr class="tr-user-pras-'.$price_code.'">
<td>'.$x['title'].' 
<input type="hidden" name="" value="'.$price_code.'" />
</td>
<td class="center">
<input type="text" name="item_price2['.$price_code.'][sku]" class="form-control input-sm required" value="'.(isset($prices[$price_code]['sku']) ? $prices[$price_code]['sku'] : '').'" placeholder="Nhập mã SKU phân loại sản phẩm"/>	 		
</td>
<th class="center "><input name="item_price2['.$price_code.'][price2]" type="text" class="form-control input-sm aright number-format bold input-currency-price-00 required" required value="'.(isset($prices[$price_code]['price']) ? $prices[$price_code]['price'] : '').'" placeholder="Giá sản phẩm"/></th>
        		
';
							if(!empty($stores)){
								foreach ($stores as $store){
									$xc[$price_code] .= '<th class="center "><input name="item_price2['.$price_code.'][quantity]['.$store['id'].']" type="text" class="form-control input-sm center number-format" value="'.(\app\modules\admin\models\Warehouse::getItemQuantity([
											'item_id'=>$item_id,
											'warehouse_id'=>$store['id'],
											'sku'=>$sku,
									])).'" placeholder="Kho hàng"/></th>';
								}
							}
							$xc[$price_code] .= '</tr>';
					}
				}
			}
			$responData['xc'] = $xc;
		}
		$price_type = $cpk ? 2 : 1;
		if($price_type != $v['price_type']){
			Yii::$app->db->createCommand()->update('articles', ['price_type'=>$price_type],['id'=>$item_id])->execute();
		}
		
		$callback_function .= '
jQuery(".hsdajfhj").html("");
 jQuery.each($d.xc,function(i,e){ 
if(jQuery(".tr-user-pras-"+i).length ==0){
	jQuery(".hsdajfhj").append(e);
}
 
});
'; 
		break;
	case 'Product_detail_delete_text_attr':
		$type_id = post('type_id');
		$item_id = post('item_id',0);
		$text_id = post('text_id',0);
		if($item_id>0){
			$callback_function .= 'removeTrItem($this); ';
			Yii::$app->db->createCommand()->delete('item_to_text_attrs',[
					'item_id'=>$item_id,
					'type_id'=>$type_id,
					'text_id'	=> $text_id
			])->execute();
			
			$callback_function .= 'if(jQuery(".intra_request_list_operator_receive_existed").length>0){
	jQuery(".tb-price-list-type-1").hide();
	jQuery(".tb-price-list-type-2").show();
}else{
	jQuery(".tb-price-list-type-2").hide();
	jQuery(".tb-price-list-type-1").show();
}
var $dt = {};
$dt.item_id = '.$item_id.';
$dt.text_id = "'.$text_id.'";
$dt.type_id = "'.$type_id.'";
$dt.action="Product_detail_reload_tbl_price";
sentAjaxData($dt);
';
		}
		
		break;
	case 'quick-open-modal-select-color':
		$f = post('f',[]); $type_id = post('type_id');
		$item_id = post('item_id',0);
		if(!empty($f)){
		$colors = \app\modules\admin\models\TextAttrs::getAll(['type_id'=>$type_id,'in'=>$f]);
		if(!empty($colors)){
			foreach ($colors as $c){
				$html .= '<tr>
<td> '.$c['title'].'</td>
<td class="center">

</td>
<th class="center "></th>

<td class="center">
<input class="intra_request_list_operator_receive_existed" name="text_attr['.$type_id.']['.$c['id'].'][id]" type="hidden" value="'.$c['id'].'">

<i data-item_id="'.$item_id.'"
data-text_id="'.$c['id'].'"
data-type_id="'.$type_id.'"
data-action="Product_detail_delete_text_attr"
onclick="call_ajax_function(this);" class="hover-red pointer fa fa-trash" title="Xóa"></i>
 
</td></tr>';
				// Cập nhật csdl
				if($item_id>0){
					Yii::$app->db->createCommand()->insert('item_to_text_attrs',[
							'item_id'=>$item_id,
							'type_id'=>$type_id,
							'text_id'	=> $c['id']
					])->execute();
				}
				
			}
		}} 
		$callback_function .= 'jQuery(".hmkijs'.$type_id.'").append($d.html);';
		$callback_function .= 'if(jQuery(".intra_request_list_operator_receive_existed").length>0){
	jQuery(".tb-price-list-type-1").hide();
	jQuery(".tb-price-list-type-2").show();
}else{
	jQuery(".tb-price-list-type-2").hide();
	jQuery(".tb-price-list-type-1").show();
}
var $dt = {};
$dt.item_id = '.$item_id.';
 
$dt.type_id = "'.$type_id.'";
$dt.action="Product_detail_reload_tbl_price";
sentAjaxData($dt);
';
		$callback_function .= 'closeAllModal();';
		break;
	case 'quick-submit-open-modal-delete-classification':
		$target = post('target');
		$callback_function .= 'closeAllModal();jQuery("'.$target.'").remove();';
		break;
		
	case 'quick-submit-remove_supplier_room':
		$room_id = post('room_id');
		$item_id = post('item_id');
		Yii::$app->db->createCommand()->delete('rooms_to_hotel',[
				'parent_id'=>$item_id, 
				'room_id'=>$room_id 
		])->execute();
		
		$callback_function .= 'closeAllModal();reloadAutoPlayFunction(true);';
		break;	
		
	case 'remove_supplier_room':
		$target = post('target');
		$r2 = randString(10);
		$body .= '<fieldset class="f12px mgb15"><legend>Xác nhận thông tin</legend>';
		
		$body .= '<p>	Bạn có thực sự muốn xóa bản ghi này ?
</p>';
		
		
		$body .= '</fieldset>';
		
		
		
		
		
		
		$modal = Yii::$app->zii->renderModal([
				//'action' => 'quick-sent-request-change-for-operator',
				'name'=>$modalName,
				'body'=>$body,
				'class'=>'w30',
				'title' => 'Xóa phòng / ghế',
				'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-check-square-o"></i> Xác nhận</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Hủy</button></div>'
		]);
		
		$complete_function = '';
		
		$complete_function .= 'izi.openModal($d.modal); ';
		break;
	
	case 'open-modal-delete-classification':
		$target = post('target');
		$r2 = randString(10);
		$body .= '<fieldset class="f12px mgb15"><legend>Lưu ý</legend>';
		
		$body .= '<p>
Khi <b class="green">Thêm mới</b> hoặc <b class="red">Xóa</b> phân loại sản phẩm, bạn sẽ phải điền lại giá sản phẩm (nếu sp đó tính giá theo phân loại sản phẩm)
</p>';
		
		
		$body .= '</fieldset>';
		
		
		
		
		
		
		$modal = Yii::$app->zii->renderModal([
				//'action' => 'quick-sent-request-change-for-operator',
				'name'=>$modalName,
				'body'=>$body,
				'class'=>'w50',
				'title' => 'Xóa phân loại sản phẩm',
				'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-check-square-o"></i> Xác nhận</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Hủy</button></div>'
		]);
		
		$complete_function = '';
		
		$complete_function .= 'izi.openModal($d.modal); ';
		break;
		
		
	case 'quick-open-modal-classification':
		$f = post('f',[]); $target = post('target');
		$item_id = post('item_id',0);
		if(!empty($f)){
			$colors = \app\modules\admin\models\TextAttrs::getAllVisibledCategory(['in'=>$f]);
			if(!empty($colors)){
				foreach ($colors as $k2=>$v2){
					$type_id = $v2['id']; 
					$html .= '

<div class="form-group group-classification-'.$type_id.'">
<input type="hidden" value="'.$v2['id'].'" name="biz[classification][]" class="existed-classification"/>
<div class="col-sm-12">
<table class="table table-bordered vmiddle">
<caption class="f14px pr">'.$v2['title'].'

<button type="button"
onclick="call_ajax_function(this);"
data-item_id="'.$item_id.'"
data-action="open-modal-delete-classification"
data-type_id="'.$type_id.'" 
data-target=".group-classification-'.$type_id.'"
class="btn btn-danger btn-sm fr" title="Xóa phân loại '.$v2['title'].'"><i class="fa fa-trash"></i></button>

<button type="button"
onclick="open_ajax_modal(this);"
data-item_id="'.$item_id.'"
data-action="open-modal-select-color"
data-type_id="'.$type_id.'" 
class="btn btn-success btn-sm fr mgr3" title="Thêm '.$v2['title'].' cho sản phẩm"><i class="fa fa-plus"></i> Thêm '.$v2['title'].'</button>

</caption>
<thead>
<tr>
<th>Tiêu đề</th>
<th class="center">Mã màu</th>
<th class="center">Hình ảnh</th>
  
		
<th></th>
</tr>
 </thead>
<tbody class="hmkijs'. $type_id.'">';
					
					
					$html .= '</tbody></table></div></div>';
				}
			}}
			$callback_function .= 'jQuery("'.$target.'").append($d.html);';
			$callback_function .= 'closeAllModal();';
			break;
	case 'open-modal-classification':
		$type_id = post('type_id');
		
		$html.= '<fieldset class="f12px"><legend>Lưu ý</legend>';
		$html.= '<p class="italic text-muted">Khi sử dụng chức năng phân loại sản phẩm, giá sản phẩm sẽ được tính lại theo phân loại sản phẩm.</p>';
		$html.= '</fieldset>';
		
		$html .='<div class="pdl15 pdr15">';
		$target = post('target');
		$r = randString(8); 
		
		
		
		$html .= '<div class="form-group group-classification"> 
    <table class="table table-bordered vmiddle">
<caption class="f14px pr"></caption>
 <thead>
<tr>
<th class="center w50p">
<p class="mgb0">
<input type="checkbox" onchange="checkAllChild(this);" data-role="'.$r.'">
</p>
</th>
<th class="center w50p">STT</th><th>Tiêu đề</th>
		
		
</tr>
</thead>
 <tbody class="list-child-item">';
		$colors = \app\modules\admin\models\TextAttrs::getAllVisibledCategory(); 
		if(!empty($colors)){
			foreach ($colors as $k=>$c){
				$html .= '<tr onclick="selectCurrentCheckbox(this);" class="pointer tr-user-classification-'.$c['id'].'">
<td class="center pr">
 <input type="checkbox" onclick="return false;" value="'.$c['id'].'" name="f[]" data-role="'.$r.'">
<span class="ps w100 h100 l0 t0"></span>
</td>
<th class="center" scope="row">'.($k+1).'</th>
		
<td class="">
'.($c['title']).'
</td>
		
		
</tr>';
			}
		}
		
		$html .= '</tbody></table></div>';
		
		
		$html .= '<div class="clear"></div><div class="row"><div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="fa fa-check-square-o"></i> Xác nhận</button>';
		
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Hủy</button>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		//
		$html .= '</div></div></div>';
		
		$callback_function .= '
jQuery("'.$target.' .existed-classification").each(function(i,e){
jQuery(\'.tr-user-classification-\'+(jQuery(e).val())).find("input").attr("disabled","");
});';
		
		break;
	case 'open-modal-select-color': 
		$type_id = post('type_id');
		$html ='<div class="pdl15 pdr15">';
		
		$r = randString(8);
		$html .= '<div class="form-group">
    <table class="table table-bordered vmiddle">
<caption class="f14px pr"></caption>
 <thead>
<tr>
<th class="center w50p">
<p class="mgb0">
<input type="checkbox" onchange="checkAllChild(this);" data-role="'.$r.'">
</p> 
</th>
<th class="center w50p">STT</th><th>Tiêu đề</th>


</tr>
</thead>
 <tbody class="list-child-item">';
		$colors = \app\modules\admin\models\TextAttrs::getAll(['type_id'=>$type_id]);
		if(!empty($colors)){
			foreach ($colors as $k=>$c){
				$html .= '<tr onclick="selectCurrentCheckbox(this);" class="tr-user-'.$c['id'].'">
<td class="center pr">
 <input type="checkbox" value="'.$c['id'].'" name="f[]" data-role="'.$r.'"> 
<span class="ps w100 h100 l0 t0"></span>
</td>
<th class="center" scope="row">'.($k+1).'</th>

<td class="">
'.($c['title']).'
</td>
 

</tr>';
			}
		} 

$html .= '</tbody></table></div>';
		 
		
		$html .= '<div class="clear"></div><div class="row"><div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu lại</button>';
		
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Hủy</button>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		//
		$html .= '</div></div></div>';
		
		$callback_function .= '
jQuery(".hmkijs'.$type_id.' .intra_request_list_operator_receive_existed").each(function(i,e){
jQuery(\'.tr-user-\'+(jQuery(e).val())).find("input").attr("disabled","");
});';
		
		break;
	case 'address_street_change':
		$target = post('target');
		$value = post('value');
		$local_id =	post('local_id',0);
		
		$responData['address'] = Yii::$app->zii->showFullLocal($local_id, $value);
		
		$callback_function .= 'jQuery("'.$target.'").val($d.address);';
		
		break;
	case 'v2-load-local-country':
		$value = post('value');
		if(!is_numeric($value)){
			$value = -1;
		}
		$selected = post('target-selected',0);
		$target = post('target');
		$target2 = post('target2');
		//$callback_function .= 'console.log(\''.$value.'\');'; 
		$l = \app\modules\admin\models\Local::getAll([
				'parent_id'=>$value
		]); 
		$html = '<option></option>';
		if(!empty($l)){
			foreach ($l as $v){
				$html .= '<option '.($v['id'] == $selected ? 'selected' : '').' value="'.$v['id'].'">'.showLocalName($v['title'],$v['type_id']) .'</option>'; 
			}
		}
		$callback_function .= 'jQuery("'.$target.'")
.html($d.html)
.trigger(\'chosen:updated\')

.change();';
		if($value>0){
			$callback_function .= 'jQuery("'.$target2.'").val('.$value.');';
		}
		break;
	case 'v2-show-local-html':
		//$callback_function .= 'console.log(data);'; 
		$respon = post('respon');
		$parent_id = post('parent_id',0);
		$selected = post('selected',0);
		$selected = $selected > 0 ? $selected : 234;
		
		$l = \app\modules\admin\models\Local::getAll([
				'parent_id'=>$parent_id
		]);
		
		foreach ($l as $v){
			$html .= '<option '.($v['id'] == $selected ? 'selected' : '').' value="'.$v['id'].'">'.$v['title'].'</option>';
		}
		
		$callback_function .= 'jQuery("'.$respon.'")
.html($d.html)
.trigger(\'chosen:updated\')
.change()
.attr("data-loaded-data","1");';  
		break;
	case 'set_default_branch':
		$id = post('id',0);
		Yii::$app->db->createCommand()->update(\app\modules\admin\models\Branches::tableName(), ['is_default'=>0],['sid'=>__SID__])->execute();
		Yii::$app->db->createCommand()->update(\app\modules\admin\models\Branches::tableName(), ['is_default'=>1],['sid'=>__SID__,'id'=>$id])->execute();
		break;
	case 'sale-request-change-day':
		$index = post('index');
		$tour_category = post('tour_category');
		$val = post('value');
		$ref = post('ref');
		$ref_val = post('ref-value');
		
		$day = max($val, $ref_val);
		
		$tr = '';
		for($i=$index;$i<$day;$i++){
			$tr .= '<tr class="tr-index-x tr-index-'.$i.'"><th class="center" scope="row">'.($i+1).'</th>
<td class="center">
<a class="tour-category-referent tour-category-referent-day-'.$i.'" data-day="'.$i.'" href="#" data-tour_category="'.$tour_category.'" onclick="call_ajax_function(this); return false;" data-action="open-form-chossee-places">
<span>Chọn địa danh</span> <i class="fa fa-pencil"></i>
<small></small>
</a>
</td>
<td class="">
<textarea name="biz[day]['.$i.'][text]" rows="3" class="form-control"></textarea>
</td>

</tr>';
		}
		$callback_function .= 'var $bd= jQuery(".maksxe tr:last-child");';
		if($day>$index){
			$callback_function .= '$bd.before($d.tr);';
		}else{
			for($i=$day;$i<$index;$i++){
				$callback_function .= 'jQuery(".tr-index-'.$i.'").remove();';
			}
		}
		$responData['tr'] = $tr;
		//$callback_function .= 'var $day = parseInt($this.val());'; 
		$callback_function .= 'jQuery("'.$ref.'").attr("data-ref-value",'.$val.');';
		//$callback_function .= 'var $dx = Math.max($day,$night);
		//	var $index='.$index.'; call_ajax_function(this);';
		$callback_function .= 'jQuery(".input-ref-night,.input-ref-day").attr(\'data-index\','.$day.');';
		if(post('field') == 'day'){
			$callback_function .= 'jQuery("'.$ref.'").val('.($val-1).');';
		}
		
		break;
	case 'sale-request-chossee-place':
		$day = post('day',0);
		$place_id = post('place_id');
		$item = \app\modules\admin\models\Places::getItem($place_id);
		if(!empty($item)){
			$callback_function .= 'jQuery(".tour-category-referent-day-'.$day.' > span").html(\''.$item['title'].'\');';
			$callback_function .= 'jQuery(".tour-category-referent-day-'.$day.' > small").html(\'<input name="biz[day]['.$day.'][place_id]" data-title="'.$item['title'].'" type="hidden" value="'.$place_id.'"/><input name="biz[day]['.$day.'][place_name]" data-title="'.$item['title'].'" type="hidden" value="'.$item['title'].'"/>\');';
		}		
		
		$callback_function .= 'closeAllModal();';
		break;
	case 'quick-add-customer':	
		$f = splitName(post('f',[]));
		$f['sid'] = __SID__;
		$f['status'] = \common\models\Member::STATUS_ACTIVE;
		$f['created_by'] = Yii::$app->user->id;
		$f['created_at'] = $f['updated_at']  = time();
		$f['type_id'] = post('type_id',TYPE_ID_CUSTOMER);
		$code = isset($f['code']) && $f['code'] != "" ? $f['code'] : '';
		if($code == "") {
		$f['code'] = genCustomerCode(isset(Yii::$site['settings']['customers'][$f['type_id']]['code']) ? Yii::$site['settings']['customers'][$f['type_id']]['code'] : []);
		if($f['code'] === false){
			unset($f['code']);
		}
		}
		$target = post('target');
		$id = 0;
		$id = Yii::$app->zii->insert(\app\modules\admin\models\Customers::tableName(),$f);
		
		switch (post('target2')){
			case 'bill-quick-add-customer':
				$callback_function .= '
jQuery(\'.bill-input-customer[data-field=id]\').val(\''.$id.'\');
jQuery(\'.bill-input-customer[data-field=name]\').val(\''.$f['name'].'\');
 
jQuery(\'.bill-input-customer[data-field=phone]\').val(\''.$f['phone'].'\');
jQuery(\'.bill-input-customer[data-field=email]\').val(\''.$f['email'].'\');
jQuery(\'.bill-input-customer[data-field=address]\').val(\''.$f['address'].'\');
 
';
				break;
				
			case 'bill-quick-add-ship':
				$callback_function .= '
jQuery(\'.bill-input-ship[data-field=id]\').val(\''.$id.'\');
jQuery(\'.bill-input-ship[data-field=name]\').val(\''.$f['name'].'\');
 
jQuery(\'.bill-input-ship[data-field=phone]\').val(\''.$f['phone'].'\');
jQuery(\'.bill-input-ship[data-field=email]\').val(\''.$f['email'].'\');
jQuery(\'.bill-input-ship[data-field=address]\').val(\''.$f['address'].'\');
 
';
				break;	
				
			default:
				
				$callback_function .= 'jQuery("'.$target.'").html(\'<option value="'.$id.'">'.$f['name'].'</option>\').trigger(\'chosen:updated\');';
				
				
				break;
		}
		
		$callback_function .= 'closeAllModal();';
		
		break;
	case 'open-form-quick-add-customer':
		$type_id = post('type_id',0);
		$body = '';
		 
		$setting = isset(Yii::$site['settings']['customers'][$type_id]['code']) ? Yii::$site['settings']['customers'][$type_id]['code'] : [];
		$auto_code =  isset($v['auto_code']) ? $v['auto_code'] : (!isset($v['code']) && isset($setting['auto_code']) ? $setting['auto_code'] : '');
		
		$body .= '<div class="form-group">
<label class="col-sm-12 control-label aleft">Mã khách hàng</label>
 <div class="col-sm-12">
<div class="input-group input-group-sm group-sm30">
<input onchange="checkCustomerCode(this);" data-field="code" type="text" name="f[code]" 
'.($auto_code == 'on' ? 'disabled' : '').'
class="form-control 3RL1OlEm finput-code '.($auto_code == 'on' ? '' : 'required').'" placeholder="Nhập mã số, mã này là duy nhất cho 1 đối tượng" value="">
<span data-toggle="tooltip" title="Mã sinh tự động theo quy tắc được cài đặt trong vùng thiết lập ban đầu" class="input-group-addon ">
      <label class="mgb0"> <input name="biz[auto_code]" '.($auto_code== 'on' ? 'checked' : '').'  
data-function="reverse" 
onchange="enabledInput(this);" data-target=".finput-code" type="checkbox" aria-label="Tự động sinh mã">
        Tự động sinh mã</label>
      </span>
</div>
</div></div>';
		
		$body .= '<div class="form-group">
<label class="col-sm-12 control-label aleft">Tên công ty / Tổ chức / Cá nhân</label>
<div class="col-sm-12"><input type="text" name="f[name]" class="form-control required MYSQApzU" 
placeholder="Tên khách hàng | Công ty" value=""></div></div>';
		
		
		$body .= '<div class="form-group">
<label class="col-sm-12 control-label aleft">Email</label>
<div class="col-sm-12"><input type="email" name="f[email]" class="form-control  "
placeholder="Email" value=""></div></div>';
		
		$body .= '<div class="form-group">
<label class="col-sm-12 control-label aleft">Số điện thoại</label>
<div class="col-sm-12"><input type="text" name="f[phone]" class="form-control  "
placeholder="Số điện thoại" value=""></div></div>';
		
		$body .= '<div class="form-group">
<label class="col-sm-12 control-label aleft">Địa chỉ</label>
<div class="col-sm-12"><input type="text" name="f[address]" class="form-control  "
placeholder="Địa chỉ" value=""></div></div>';
		
		$modal = Yii::$app->zii->renderModal([
		'action' => 'quick-add-customer',
		'name'=>$modalName,
		'body'=>'<div class="col-sm-12"><div class="mgb5 pr member-avatar mgt10">
'.$body.'
</div></div>',
'class'=>'w60',
'title' => 'Thêm nhanh khách hàng / Đối tác',
'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-save"></i> Lưu lại</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Đóng</button></div>'
		]);
		
		$complete_function = '';
		
		$complete_function .= 'izi.openModal($d.modal,\''.$modal_target.'\');';
		
		break;	
		
	case 'open-form-chossee-places':
		$body = '';
		$tour_category = post('tour_category',0);
		$day = post('day',0);
		
		$body .= '<select name="place_id" class="form-control chosen-select required tour-place-end" required="required">';
		 
		foreach (\app\modules\admin\models\Places::getPlaces([
				'filter_id'=>$tour_category,
				'rf_value' => 1,
				'limit'=>0
		]) as $v1){
			$body .= '<option value="'.$v1['id'].'">'.$v1['title'].'</option>';
		}
	
		$body .= ' </select>';
		
		$modal = Yii::$app->zii->renderModal([
		'action' => 'sale-request-chossee-place',
		'name'=>$modalName,
		'body'=>'<div class="col-sm-12"><div class="mgb5 pr member-avatar mgt10">
'.$body.'
</div></div>',
'class'=>'w60',
'title' => 'Chọn địa danh',
'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-save"></i> Chọn</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Đóng</button></div>'
		]);
		
		$complete_function = '';
		
		$complete_function .= 'izi.openModal($d.modal);chosen_select_init();';
		
		break;
	case 'sale_request_change_tourcategory':
		
//		$callback_function .= 'console.log(data);';
		$id = post('value',0);
		$item = \app\modules\admin\models\Filters::getItem($id);
		$s1 = $s2 = '';
		if(!empty($item)){
			foreach (\app\modules\admin\models\Places::getPlaces([
					'filter_id'=>$id,
					'rf_value' => 2
			]) as $v1){
				$s1 .= '<option value="'.$v1['id'].'">'.$v1['title'].'</option>';
			}
			
			foreach (\app\modules\admin\models\Places::getPlaces([
					'filter_id'=>$id,
					'rf_value' => 1
			]) as $v1){
				$s2 .= '<option value="'.$v1['id'].'">'.$v1['title'].'</option>';
			}
			$responData['s1'] = $s1;
			$responData['s2'] = $s2;
			
			$callback_function .= 'jQuery(".tour-place-start").html($d.s1).trigger("chosen:updated");';
			$callback_function .= 'jQuery(".tour-place-end").html($d.s2).trigger("chosen:updated");';
			$callback_function .= 'jQuery(".tour-category-referent").attr("data-tour_category",'.$id.');';
			
		}
		break;
	
	case 'warehouse_set_default_item':
		$branch_id = post('branch_id');
		$id = post('id');
		
		Yii::$app->db->createCommand()->update('warehouse', ['is_default'=>0],['branch_id'=>$branch_id])->execute();
		Yii::$app->db->createCommand()->update('warehouse', ['is_default'=>1],['branch_id'=>$branch_id,'id'=>$id])->execute();
		
		
		break;
	
	case 'unit_exchange_load_item':
		$item_id = post('value');
		$item = \app\modules\admin\models\Content::getItem($item_id);
		if(!empty($item)){
			 $target = post('target');
			 
			 $callback_function .= 'jQuery(\''.$target.'\').val("'.($item['unit'] != "" ? $item['unit'] : 'unknown').'")';
		}
		
		
		break;
	
	case 'add_ad_language':
		$user = (Yii::$app->user->getCurrentUser());
		$field = post('field');
		$modalName = 'mymodal';
		$modalID = '#' . $modalName;
		$body = '<input type="text" name="f['.$field.']" class="form-control " value="'.(isset($user[$field]) ? uh($user[$field]) : '').'"/>';
		switch (post('field')){
			case 'name':
				$body = '<div class="row"><div class="col-sm-6"><input placeholder="Họ và tên đệm" type="text" name="f[lname]" class="form-control " value="'.(isset($user['lname']) ? uh($user['lname']) : '').'"/></div>';
				$body .= '<div class="col-sm-6"><input placeholder="Tên" type="text" name="f[fname]" class="form-control " value="'.(isset($user['fname']) ? uh($user['fname']) : '').'"/></div></div>';
				break;
			default:
				
				break;
		}
		
		$modal = Yii::$app->zii->renderModal([
				'action' => 'changeUserInfoFormSubmit',
				'name'=>$modalName,
				'body'=>'<div class="col-sm-12"><div class="mgb5 pr member-avatar mgt10">
'.$body.'
</div></div>',
				'class'=>'w60',
				'title' => 'Thêm ngôn ngũ',
				'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-save"></i> Lưu lại</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Đóng</button></div>'
		]);
		
		$complete_function = '';
		
		$complete_function .= 'izi.openModal($d.modal);';
		echo json_encode([
				'callback'=>true,
				'complete'=>true,
				'complete_function' => $complete_function,
				'modal' => $modal,
		]);exit;
		break;
	
	case 'Ars_alt_tab':
		$callback_function = $html = ''; $post = $_POST;
		$tab = $id = randString(8);
		$role = $kc = post('role',0);
		$input_name = '';
		$li = '<li class="pr" role="presentation">
<a href="#" class="delTab" onclick="delTab(\'#'.$tab.'\');">x</a>
<a href="#'.$tab.'"  role="tab" data-toggle="tab">Tab '.($role + 1).'</a>
<input type="hidden" name="tab_position[]" value="'.$role.'"/></li>';
		$callback_function .= '$this.parent().before($d.li);
$this.attr(\'data-role\','.($role+1).');';
		
		$html .= '<div role="tabpanel" class="tab-pane " id="'.$tab.'">';
		
		$html .= '<div class="mgt15 col-sm-12">';
		$html .= '<div class="form-group">
<div class="col-md-8 col-sm-8 col-xs-12">
<label>Tiêu đề</label>
<input name="tab['.$kc.'][title]" value="" type="text" class="form-control" placeholder="Tiêu đề">
</div>
<div class="col-md-4 col-sm-4 col-xs-12">
<label>Tùy chọn tab</label>
 ';
		$html .='<select class="form-control input-sm"  name="tab['.$kc.'][type]">';
		foreach (\app\modules\admin\models\Content::getTabDetailType('tours') as $vcx){
			$html .='<option value="'.$vcx['type'].'" '.('text' == $vcx['type']? 'selected' : '').'>'.$vcx['title'].'</option>';
		}
		$html .='</select>
				
    		</div>
				
  </div>
				';
		
		if(!(isset($vc['fields']) && !empty($vc['fields']))){
			$vc['fields'][0] = [
					'title' => '',
					'type' =>'text',
					'is_active' => 'on',
					
			];
		}
		$tab_index = count($vc['fields']);
		$html .= '<div class="tab-append-text">';
		
		foreach ($vc['fields'] as $tab_index2 => $tab2){
			
			
			$id = randString(8);
			$html .= '<div class="block-sm pr "><div class="input-group input-title1">
					
					
<input title="Tiêu đề" style="width:50%" name="tab_biz['.($kc).']['.$tab_index2.'][title]" value="'.(isset($tab2['title']) ? uh($tab2['title']) : '').'" type="text" class="form-control" placeholder="Tiêu đề">
		
<input title="Ghi chú" style="width:50%;margin-left:-1px;margin-right:-1px" name="tab_biz['.($kc).']['.$tab_index2.'][note]" value="'.(isset($tab2['note']) ? uh($tab2['note']) : '').'" type="text" class="form-control" placeholder="Ghi chú">
<span class="input-group-addon transparent">
<input name="tab_biz['.($kc).']['.$tab_index2.'][hide_title]" type="checkbox" '.(isset($tab2['hide_title']) && $tab2['hide_title'] == 'on' ? 'checked' : '').' aria-label="Ẩn tiêu đề" title="Ẩn tiêu đề">
		
Ẩn tiêu đề
</span>
<span class="input-group-addon transparent">
<input name="tab_biz['.($kc).']['.$tab_index2.'][is_active]" type="checkbox" '.(isset($tab2['is_active']) && $tab2['is_active'] == 'on' ? 'checked' : '').' aria-label="Kích hoạt" title="Kích hoạt"> Kích hoạt
</span>
<div class="input-group-btn">
<button onclick="call_ajax_function(this);" data-action="remove-panel-text-field" type="button" class="btn btn-default btn-tour-category mgl-1"><i class="fa fa-trash"></i></button>
</div>
<div class="input-group-btn">
<button data-category_id="77" title="Thiết lập nâng cao" data-item_id="2525" data-id="2525" data-action="tour_quick_add_detail_filter" data-class="w60" data-title="" onclick="showModal(\'Thông báo!\',\'Chức năng đang xây dựng\')" type="button" class="btn btn-default btn-tour-category"><i class="fa fa-cog"></i></button></div></div>
		
<div class="div-block-border col-sm-12 col-md-12 pdt10"><div class="mg5">
<div class="form-group">
<label>Nội dung</label>
<textarea data-expand="false" id="'.$id.'" data-height="150" name="tab_biz['.($kc).']['.$tab_index2.'][text]" class="form-control ckeditor_full2" rows="5">'.(isset($tab2['text']) ? uh($tab2['text']) : '').'</textarea>
		
		
		
</div></div></div></div>';
		}
		
		
		$html .='</div>';
		$html .='<hr/><p class="aright "><button data-page="content" data-action="ac3-add-field-text" onclick="call_ajax_function(this);"
data-tab-index="'.$kc.'" data-tab-index2="'.$tab_index.'" type="button" class="btn btn-default "><i class="fa fa-plus"></i> Thêm trường dữ liệu</button></p>';
		
		$html .= '</div>';
		$html .= '</div>';
		$callback_function .= 'jQuery(\'#append-etabs\').append($d.html);
 	        jQuery(\'a[href="#'.$tab.'"]\').tab(\'show\');loadCkeditorFull();';
		echo json_encode([
				'html' => $html,
				'li' => $li,
				'callback' => true,
				'callback_function' => $callback_function
		]+$_POST); exit;
		break;
	
	case 'menu-hover-load-child2':
		$callback_function = $html = '';
		$item_id = post('item_id',0);
		$item = \app\modules\admin\models\AdminMenu::getItem($item_id);
		switch ($item['type']){
			case 1: case 2:
				$childs2 = \app\modules\admin\models\AdminMenu::getUserForms();
				break;
			default:
				$childs2 = \app\modules\admin\models\AdminMenu::getChilds($item_id);
				break;
		}
		if(!empty($childs2)){
			$html .= '<div class="l2s-child-mnv2">';
			if($item['type'] == 1){
				$link =  cu([$item['url'].DS,'type'=>'all']);
				$html .= '<div class="liv"><a href="'.$link.'">Tất cả danh mục</a></div>';
			}
			foreach ($childs2 as $v){
				switch ($item['type']){
					case 1: case 2:
						//$link =  cu([$v['url'].DS]);
						$link =  cu([$item['url'].DS,'type'=>($v['code'] == "" ? 'other' : $v['code'])]);
						if(($v['is_sub']== 1 && $item['type']==1) || ($v['is_content'] == 1) ){
							$html .= '<div class="liv"><a href="'.$link.'">'.uh($v['title']).'</a></div>';
						}
						break;
					default:
						$link =  cu([$v['url'].DS]);
						$html .= '<div class="liv"><a href="'.$link.'">'.uh($v['title']).'</a></div>';
						break;
				}
				
			}
			$html .= '</div>';
		}
		echo json_encode([
				'html'=>$html,
				'data' => $childs2,
				//'li'=>'<li class="reload-append"><a href="#">'.uh($item['title']).'</a></li>',
				'callback'=>true,
				'callback_function'=>$callback_function
		]+$_POST); exit;
		break;
		
	case 'load-items-notes':
		$html = ''; $callback_function = '';
		$app_id = post('app_id',0);
		
		$html .= '<table class="table table-bordered table-hover f14px">
<caption>Danh sách các thuộc tính</caption>
<thead> <tr>
<th class="w50p center"></th>
<th>Tiêu đề</th>
<th class="w100p"></th>
 </tr> </thead>
				
<tbody> ';
		foreach (Yii::$app->itemsNote->getAll() as $k=>$v){
			$html .= '<tr>
<th class="center" scope="row">'.($k+1).'</th>
<td>'.uh($v['title']).'</td>
<td class="center">
<i data-id="'.$v['id'].'" data-title="Thêm thuộc tính" onclick="open_ajax_modal(this);" data-action="quick-add-item-note" data-app_id="'.$app_id.'" class="fa fa-edit hover-red pointer" title="Chỉnh sửa"></i>
<i class="fa fa-trash hover-red pointer" title="Xóa"></i>
</td>
 </tr>
	';
		}
		
		
		$html .= '<tr>
				
<td colspan="3">
<p class="aright">
				
<button data-title="Thêm thuộc tính" onclick="open_ajax_modal(this);" data-action="quick-add-item-note" data-app_id="'.$app_id.'" type="button" class="btn btn-success"><i class="fa fa-plus"></i> Thêm thuộc tính</button>
<button onclick="goBack();" type="button" class="btn btn-danger	"><i class="fa fa-mail-reply"></i> Quay lại</button>
</p>
		
</td>
		
 </tr>
</tbody>
		
</table>';
		$item = \app\modules\admin\models\Appstore::getItem($app_id);
		$callback_function .= 'if(jQuery(".Breadcrumb.ul_tree_bar .reload-append").length==0) jQuery(".Breadcrumb.ul_tree_bar").append($d.li);';
		echo json_encode([
				'html'=>$html,
				'li'=>'<li class="reload-append"><a href="#">'.uh($item['title']).'</a></li>',
				'callback'=>true,
				'callback_function'=>$callback_function
		]+$_POST); exit;
		break;
		
	case 'quick-quick-add-item-note':
		$html = ''; $callback_function = 'reloadAutoPlayFunction(true);';
		$id = post('id',0);
		$f = post('f',[]);
		if($id>0){
			Yii::$app->itemsNote->updateNote($f,$id);
		}else{
			Yii::$app->itemsNote->insertNote($f);
		}
		
		echo json_encode([
				///'html'=>$_POST,
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>$callback_function
		]); exit;
		break;
		
	case 'quick-add-item-note':
		$html = $callback_function = '';
		$id = post('id',0);
		
		$item = Yii::$app->itemsNote->getItem($id);
		
		$html .='<div class="pdl15 pdr15">';
		
		$html .= '<div class="form-group">
    <label  >Tiêu đề</label>
    <input name="f[title]" type="text" value="'.(isset($item['title']) ? $item['title'] : '').'" class="form-control required" placeholder="Nhập tiêu đề">
  </div>';
		
		
		$html .= '<div class="clear"></div><div class="row"><div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu lại</button>';
		
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Hủy</button>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		//
		$html .= '</div></div></div>';
		
		
		
		echo json_encode([
				'html'=>$html,
				
				'callback'=>true,
				'callback_function'=>$callback_function
		]); exit;
		break;
		
	case 'active_app_for_shop':
		$html = $callback_function = '';
		$app_id = post('app_id',0);
		
		$app = \app\modules\admin\models\Appstore::getUserApp($app_id);
		//
		if(!empty($app)){ // Đã cài đặt
			\app\modules\admin\models\Appstore::installUserApp($app_id);
			
		}else{
			//
			\app\modules\admin\models\Appstore::installUserApp($app_id);
		}
		
		echo json_encode([
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>$callback_function
		]); exit;
		break;
	case 'add-other-detail-info':
		$html = $callback_function = '';
		$post = $_POST;
		$v1 = \app\modules\admin\models\ExamplesData::getTourInfoCategoryDetail($post['code']);
		
		$rand = randString(8);
		
		$html .= '<li class="t2-panel mgt15 mgb15 t2-panel-'.$v1['code'].'">
    <div class="green pr mgb10"><i class="'.$v1['icon'].' "></i> <b class="upper">'.$v1['title'].'</b>
    		
<div class="ps r0 t0">
<label class="font-normal">
<input type="checkbox" name="biz[tour_other_detail]['.$v1['code'].'][is_active]" checked />&nbsp;
Kích hoạt
</label>&nbsp;&nbsp; - &nbsp;&nbsp;
<label>
 <i onclick="if(confirm(\'Xác nhận ?\')){return removeTrItem(this,4);};" class="fa fa-trash pointer lower hover-red"> Xóa thuộc tính này</i>
</label>
</div>
		
</div>
    <textarea data-height="150" name="biz[tour_other_detail]['.$v1['code'].'][text]" class="form-control ckeditor_basic4" rows=5 id="'.$rand.'">'.(isset($v1['templete']) ? $v1['templete'] : '').'</textarea>
   </li>';
		
		$callback_function .= 'if(jQuery(".t2-panel-'.$v1['code'].'").length==0){
jQuery(".panel-other-detail .panel-body ul.ui2-sortable").append($d.html);loadCkeditorBasic4();
				
	var $target = jQuery(\'html,body\'); 
$target.animate({scrollTop: $target.height()}, 100);			
}
';
		
		echo json_encode([
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>$callback_function
		]); exit;
		break;
	case 'add_external_link':
		$callback_function = $html =  '';
		$i = $index = post('index');
		$code = randString(6);
		$html .= '<div class="form-group tr-external-'.$code.'">
         <div class="col-sm-6 col-xs-12"><div class="row">
         <label class="col-sm-12 control-label">Anchor Text</label>
         <div class="col-sm-12">
<input name="external_links['.$i.'][title]" class="form-control required" required placeholder="VD: Du lịch Myanmar - Những trải nghiệm khác lạ" value=""/>
		
		
          </div>
         </div></div>
		
         <div class="col-sm-6 col-xs-12"><div class="row">
         <label class="col-sm-12 control-label">Link</label>
         <div class="col-sm-12">
		
            <div class="input-group">
		
      <input name="external_links['.$i.'][url_link]" class="form-control required" required placeholder="VD: https://dalaco.travel/du-lich-myanmar-nhung-trai-nghiem-khac-la" value=""/>
<span class="input-group-btn">
        <button onclick="remove_item_class(this);" data-target=".tr-external-'.$code.'" class="btn btn-default" type="button" title="Xóa link này"><i class="fa fa-trash hover-red"></i></button>
      </span>
 </div><!-- /input-group -->
        		
          </div>
         </div></div></div>';
		$callback_function .= '$this.attr(\'data-index\','.($index+1).');$this.before ($d.html);';
		echo json_encode([
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>$callback_function
		]); exit;
		break;
		
	case 'updateItemCode':
		$callback_function = '';
		$id = post('id');
		\app\modules\admin\models\Content::updateNewCode($id);
		echo json_encode([
				'callback'=>true,
				'callback_function'=>$callback_function
		]); exit;
		break;
	case 'quick_update_config':
		$f = post('f'); $code = post('code');
		
		
		\app\modules\admin\models\Siteconfigs::updateBizrule('site_configs',
				['code'=>$code, 'sid'=> __SID__],
				$f
				);
		
		$callback_function = '$this.find("button").attr("disabled","");';
		echo json_encode([
				'p'=>$_POST,
				'callback'=>true,
				'callback_function'=>$callback_function
		]); exit;
		break;
	case 'CTemplete_check_code_existed':
		$callback_function = $html = '';
		$id = post('id',0); $value = post('value');
		
		$v = (new Query())->from('ctemplete')->where(['name'=>$value])
		->andWhere(['not in','id',$id])
		->one();
		
		if(!empty($v)){
			$callback_function .= 'var $input_error = \'<div class="error_field"><p class="red">Giá trị <b>'.$value.'</b> đã được sử dụng</p></div>\';';
			$callback_function .= '$this.parent().find(".error_field").remove();$this.parent().append($input_error);';
		}else{
			$callback_function .= '$this.parent().find(".error_field").remove();';
		}
		
		$callback_function .= 'console.log(data);';
		echo json_encode([
				'p'=>$v,
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>$callback_function
		]); exit;
		break;
		
	case 'set_item_filter_max_booking':
		$item_id = post('item_id',0);
		$filter_id = post('filter_id',0);
		$value = trim_all(post('value'));
		
		\app\modules\admin\models\Siteconfigs::updateBizrule('articles_to_filters',['item_id'=>$item_id, 'filter_id'=> $filter_id],[
				'max_booking'=>$value
		]);
		
		break;
	case 'set_item_filter_before_booking':
		$item_id = post('item_id',0);
		$filter_id = post('filter_id',0);
		$value = trim_all(post('value'));
		
		\app\modules\admin\models\Siteconfigs::updateBizrule('articles_to_filters',['item_id'=>$item_id, 'filter_id'=> $filter_id],[
				'before_booking'=>$value
		]);
		
		break;
	case 'set_item_filter_to_date_igrone':
		$item_id = post('item_id',0);
		$filter_id = post('filter_id',0);
		$value = trim_all(post('value'));
		
		\app\modules\admin\models\Siteconfigs::updateBizrule('articles_to_filters',['item_id'=>$item_id, 'filter_id'=> $filter_id],[
				'igrone_date'=>$value
		]);
		
		break;
	case 'set_item_filter_from_date':
		$item_id = post('item_id',0);
		$filter_id = post('filter_id',0);
		$value = ctime(['string'=> post('value')]);
		Yii::$app->db->createCommand()->update('articles_to_filters', [
				'from_date'=>$value
		],[
				'item_id'=>$item_id, 'filter_id'=> $filter_id
		])->execute();
		
		exit;
		break;
		
	case 'set_item_filter_to_date':
		$item_id = post('item_id',0); $filter_id = post('filter_id',0);
		$value = ctime(['string'=> post('value')]);
		Yii::$app->db->createCommand()->update('articles_to_filters', [
				'to_date'=>$value
		],[
				'item_id'=>$item_id, 'filter_id'=> $filter_id
		])->execute();
		
		exit;
		break;
	case 'quick-modify-domain-whois':
		$f = post('f',[]);
		$biz = post('biz',[]);
		
		$f['sid'] = __SID__;
		$f['type_code'] = DOMAIN_CHECKED;
		$f['item_id'] = time();
		$f['state'] = -1;
		$f['bizrule'] = json_encode($biz);
		$f['time'] = ctime(['string'=>$f['time']]);
		
		Yii::$app->db->createCommand()->insert('cronjobs',$f)->execute();
		
		echo json_encode([
				'event'=>'hide-modal',
				
				//'html'=>$html,
				'callback'=>true,
				'callback_function'=>'reloadAutoPlayFunction(true);'
		]); exit;
		break;
	case 'modify-domain-whois':
		$html ='<div class="pdl15 pdr15">';
		
		$html .= '<div class="form-group">
    <label  >Tên miền cần check</label>
    <input name="biz[domain]" type="text" class="form-control required" placeholder="Tên miền cần check">
  </div>';
		$html .= '<div class="form-group">
    <label  >Ngày hết hạn</label>
    <input name="f[time]" type="text" class="form-control datepicker required" data-format="d/m/YYYYY" placeholder="Tên miền cần check">
  </div>';
		
		$html .= '<div class="clear"></div><div class="row"><div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu lại</button>';
		
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Hủy</button>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		//
		$html .= '</div></div></div>';
		//
		echo json_encode([
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>'load_datetimepicker();reloadAutoPlayFunction(true);'
		]); exit;
		break;
		
	case 'refresh_item':
		$id = post('id',0);
		$callback_function = '$this.parent().html(\'Vừa xong <i class="fa fa-refresh pointer" title="Làm mới tin này" onclick="call_ajax_function(this);" data-id="'.$id.'" data-action="refresh_item"></i>\');';
		
		Yii::$app->db->createCommand()->update('articles', [
				'updated_at'=>date('Y-m-d H:i:s')
		],[
				'id'=>$id,
				'sid'=>__SID__
		])->execute();
		
		echo json_encode([
				//'event'=>'hide-modal',
				
				//		'html'=>$_POST,
				'callback'=>true,
				'callback_function'=>$callback_function
		]); exit;
		break;
	case 'change_item_tour_category':
		$item_id = post('item_id');
		$filter_id = post('value');
		$filter = \app\modules\admin\models\Filters::getItem($filter_id);
		
		Yii::$app->db->createCommand()->delete('articles_to_filters',[
				'and',[
						'item_id'=>$item_id,
						'filter_id'=>(new Query())->select('id')->from('filters')->where([
								'code'=>$filter['code'],'sid'=>__SID__
						])
				],[
						'not in','filter_id',$filter_id
				]
		])->execute();
		
		if((new Query())->from('articles_to_filters')->where(['item_id'=>$item_id,'filter_id'=>$filter_id])->count(1) == 0){
			Yii::$app->db->createCommand()->insert('articles_to_filters',['item_id'=>$item_id,'filter_id'=>$filter_id])->execute();
		}
		
		echo json_encode([
				//'event'=>'hide-modal',
				
				//		'html'=>$_POST,
				//	'callback'=>true,
				//'callback_function'=>'console.log(data);'
		]); exit;
		break;
	case 'quick-my-menu-templete':
		$f = post('f',[]);
		$new = post('new',[]);
		$key = post('key');
		$data = \app\modules\admin\models\Siteconfigs::getItem($key);
		
		if(!empty($f)){
			foreach ($f as $k=>$v){
				$del = false;
				if(isset($v['delete'])){
					if($v['delete'] == 'on'){
						unset($data[$k]);
						$del = true;
					}}
					if(!$del){
						$data[$k]['code'] = $v['code'];
						$data[$k]['title'] = $v['title'];
					}
			}
		}
		
		if(!empty($new)){
			foreach ($new as $v){
				if($v['code'] != "" && $v['title'] != ""){
					$data[] = $v;
				}
			}
		}
		
		\app\modules\admin\models\Siteconfigs::updateData($key, $data);
		
		echo json_encode([
				'event'=>'hide-modal',
				
				//'html'=>$html,
				'callback'=>true,
				'callback_function'=>'reloadAutoPlayFunction(true);'
		]); exit;
		break;
	case 'my-menu-templete':
		$key = post('key');
		$data = \app\modules\admin\models\Siteconfigs::getItem($key);
		$html = '';
		
		$html .= '<div class="form-group"><div class="col-sm-12">
		<table class="table table-bordered table-hover vmiddle table-responsive">
				<caption>Danh sách đã tạo</caption>
				<thead><tr><th class="center">Mã</th><th class="center">Tiêu đề</th><th class="center">Xóa</th></tr></thead>
				<tbody>';
		if(!empty($data)){
			foreach ($data as $k1=>$v1){
				$html .= '<tr><td class=""><input class="form-control required" name="f['.$k1.'][code]" value="'.$v1['code'].'" placeholder="Nhập mã ko có dấu cách và ký tự đặc biệt"/></td>
				<td class=""><input class="form-control required" name="f['.$k1.'][title]" value="'.$v1['title'].'" placeholder="Tiêu đề"/></td>
				<td class="center"><input class="" name="f['.$k1.'][delete]" type="checkbox"/></td>
				</tr>';
			}}
			$html .= '</tbody></table>
		</div></div>';
			
			$html .= '<div class="form-group"><div class="col-sm-12">
		<table class="table table-bordered table-hover vmiddle table-responsive">
				<caption>Thêm mới</caption>
				<thead><tr><th class="center">Mã</th><th class="center">Tiêu đề</th></tr></thead>
				<tbody>
				<tr><td class=""><input class="form-control " name="new[0][code]" placeholder="Nhập mã ko có dấu cách và ký tự đặc biệt"/></td>
				<td class=""><input class="form-control " name="new[0][title]" placeholder="Tiêu đề"/></td>
				</tr>
				<tr><td class=""><input class="form-control " name="new[1][code]" placeholder="Nhập mã ko có dấu cách và ký tự đặc biệt"/></td>
				<td class=""><input class="form-control " name="new[1][title]" placeholder="Tiêu đề"/></td>
				</tr>
				<tr><td class=""><input class="form-control " name="new[2][code]" placeholder="Nhập mã ko có dấu cách và ký tự đặc biệt"/></td>
				<td class=""><input class="form-control " name="new[2][title]" placeholder="Tiêu đề"/></td>
				</tr>
				</tbody></table>
		</div></div>';
			
			
			$html .= '<div class="clear"></div><div class="row"><div class="modal-footer">';
			$html .= '<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu lại</button>';
			
			$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Hủy</button>';
			$_POST['action'] = 'quick-' . $_POST['action'];
			foreach ($_POST as $k=>$v){
				$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
			}
			//
			$html .= '</div></div>';
			//
			echo json_encode([
					'html'=>$html,
					'callback'=>true,
					'callback_function'=>'reloadAutoPlayFunction(true);'
			]); exit;
			break;
	case 'loadTourDetailFilterTourPriceList':
		$html = '';
		$item_id = post('item_id');
		$dir = post('dir');
		
		
		
		
		$html .= '</div></div>';
		
		
		foreach (\app\modules\admin\models\Filters::getFilters([
				'code'=>'tour_type','parent_id'=>0
		]) as $v1){
			foreach (\app\modules\admin\models\Filters::getFilters([
					'parent_id'=>$v1['id'],
					'item_id'=>$item_id,
					//'select'=>['a.*','b.from_date','b.to_date']
			]) as $v2){
				$html .= '<div class="form-group scheduler-group"><div class=" group-frame col-sm-12 pdb10">';
				$html .= '<div class="block-sm pr w100	">';
				$html .= '<div class="row control-label bgeee"><div class="col-sm-12"><label for="inputLink" class="bold aleft ">'.uh($v2['title']).'</label>
	<label class="bold aleft " style="margin-left:40px;"><input
			data-update-item-attr="1"
			data-item-filed="default_price"
			data-item-biz="1"
			onchange="call_ajax_function(this)"
			data-item_id="'.$item_id.'"
			data-filter_id="'.$v2['id'].'"
			data-action="set_default_item_filter_value" type="radio"
			name="biz[default_price]"
			value="'.$v2['id'].'" '.(\app\modules\admin\models\Content::getItemFilterState($item_id, $v2['id']) == 1 ? 'checked' : '').'/>&nbsp;Đặt giá mặc định</label></div>';
				$html .= '</div></div>';
				$filter_id= $v2['id'];
				
				$from_date = $v2['from_date'];
				$to_date = $v2['to_date'];
				
				switch ($v2['value']){
					case 1: // Ghép theo lịch
						include_once $dir . '/_tour_type_1.php';
						break;
					case 2: //Ghép hàng ngày
						include_once $dir . '/_tour_type_2.php';
						break;
					case 5: // Ghép hàng tuần
						include_once $dir . '/_tour_type_5.php';
						break;
					case 3:
						include_once $dir . '/_tour_type_3.php';
						break;
					default:
						
						break;
						
				}
				
				$html .= '</div></div>';
			}
		}
		echo json_encode([
				'html'=>$html,
				//'event'=>'hide-modal' ,
				//'checked'=>$i,
				//'post'=>$_POST,
				'callback'=>true,
				'callback_function'=>'reloadAutoPlayFunction(true);load_datetimepicker();loadTagsInput();'
		] + $_POST); exit;
		break;
	case 'change-toggle-item-filter':
		$item_id = post('item_id');
		$filter_id = post('filter_id');
		$checked = post('checked',false);
		if($checked ){
			$i = 1;
			if((new Query())->from('articles_to_filters')->where([
					'item_id'=>$item_id,
					'filter_id'=>$filter_id,
			])->count(1) == 0){
				Yii::$app->db->createCommand()->insert('articles_to_filters',[
						'item_id'=>$item_id,
						'filter_id'=>$filter_id,
				])->execute();
			}
		}else{
			$i = 0;
			Yii::$app->db->createCommand()->delete('articles_to_filters',[
					'item_id'=>$item_id,
					'filter_id'=>$filter_id,
			])->execute();
		}
		echo json_encode([
				//'html'=>$html,
				//'event'=>'hide-modal',
				'checked'=>$i,
				'post'=>$_POST,
				'callback'=>true,
				'callback_function'=>''
		] ); exit;
		break;
	case 'set_default_item_filter_value':
		$item_id = post('item_id');
		$filter_id = post('filter_id');
		$filter = \app\modules\admin\models\Filters::getItem($filter_id);
		
		Yii::$app->db->createCommand()->update('articles_to_filters', ['state'=>0],['item_id'=>$item_id,'filter_id'=>(new Query())->from(['filters'])->where(['code'=>$filter['code'],'sid'=>__SID__])->select('id')])->execute();
		Yii::$app->db->createCommand()->update('articles_to_filters', ['state'=>1],['item_id'=>$item_id,'filter_id'=>$filter_id])->execute();
		if(post('update-item-attr') == 1){
			$field = post('item-filed');
			if(post('item-biz') == 1){
				\app\modules\admin\models\Siteconfigs::updateBizrule('articles',['id'=>$item_id],[$field=>$filter_id]);
			}else{
				Yii::$app->db->createCommand()->update('articles',[$field=>$filter_id],['id'=>$item_id])->execute();
			}
		}
		
		echo json_encode([
				//'html'=>$html,
				//'event'=>'hide-modal',
				//'callback'=>true,
				//'callback_function'=>'reloadAutoPlayFunction(true);'
		] ); exit;
		break;
	case 'quick-loadTourDetailFilterTourType':
		$f = post('f',[]);
		if(!empty($f)){
			foreach ($f as $id=>$v){
				Yii::$app->db->createCommand()->update('filters', $v,['id'=>$id])->execute();
			}
		}
		echo json_encode([
				//'html'=>$html,
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>'reloadAutoPlayFunction(true);'
		]+$_POST ); exit;
		break;
		break;
	case 'loadClassMembers':
		$class_id = post('class_id',0);
		$html = '';
		$html .= '<table class="table table-bordered  table-hover">';
		
		$html .= afShowThread(array(
				
				array(
						'name'=>'Họ tên',
						'class'=>'',
				),
				array(
						'name'=>'Email',
						'class'=>'',
				),
				array(
						'name'=>getTextTranslate(138,ADMIN_LANG),
						'class'=>'',
				),
				
				
				
		),[
				'CHECK'=>false
		]);
		
		
		$html .=  '<tbody>';
		$users = \app\modules\admin\models\Customers::getAll([
				'type_id'=>TYPE_ID_MEMBERS,
				'class_id'=>$class_id,
				'limit'=>10000
		]);
		
		if(!empty($users)){
			$role = [
					'type'=>'single',
					'table'=>'{{%user_to_group}}',
					'controller'=>Yii::$app->controller->id,
					'class_id'=>$class_id
			];
			foreach($users as $k1=>$v1){
				$role['user_id']=$v1['id'];
				$role['action'] = 'Ad_quick_change_item_user_group';
				$html .= '<tr class="tr_item_'.$v1['id'].'">
						
      <td class="center">'.($k1+1).'</td>
      		
    <td class="nowrap">'.uh($v1['name']).'</td>
 	<td class="nowrap">'.uh($v1['email']).'</td>
	<td class="nowrap">'.uh($v1['phone']).'</td>  ';
				
				
				$role['action'] = 'Ad_quick_delete_item_member_class';
				$html .= '<td class="center">
<a disabled="" href="#" class="btn btn-link edit_item icon">Sửa</a>
<a href="#" class="btn btn-link delete_item icon" data-type="single" data-class_id="'.$class_id.'" data-customer_id="'.$v1['id'].'" data-confirm-action="quick-remove-member-class"
onclick="return open_ajax_modal(this)" data-action="open-confirm-dialog" data-class="modal-sm" data-title="Xóa bản ghi này ?" data-placement="left" data-btnokclass="btn-primary">Xóa</a></td>
      </tr>';
				
			}
		}
		
		
		
		$html .= '</tbody></table>';
		
		echo json_encode([
				'html'=>$html
		]+$_POST ); exit;
		break;
	case 'quick-add_member_to_class':
		$class_id = post('class_id');
		$member_id = post('member_id',[]);
		
		if(!empty($member_id)){
			foreach ($member_id as $customer_id){
				if((new Query())->from('customers_to_class')->where(['class_id' => $class_id,
						'customer_id' => $customer_id,
						'state' => \app\modules\admin\models\ClassManage::$CLASS_STATUS_ACTIVE,])->count(1) == 0){
						Yii::$app->db->createCommand()->insert('customers_to_class', [
								'class_id' => $class_id,
								'customer_id' => $customer_id,
								'state' => \app\modules\admin\models\ClassManage::$CLASS_STATUS_ACTIVE,
						])->execute();
				}
			}
		}
		echo json_encode([
				//'html'=>$html,
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>'reloadAutoPlayFunction(true);'
		]); exit;
		break;
		
	case 'quick-confirm-class-reserve':
		$id = post('id',0);
		$f = post('f');
		$item = \app\modules\admin\models\ClassReserve::getItem($id);
		if(!empty($item) && $item['state'] == '-1'){
			$s = false;
			if(isset($f['is_confirm']) && $f['is_confirm'] == 'on'){
				Yii::$app->db->createCommand()->update(\app\modules\admin\models\ClassReserve::tableName(), ['state'=>1],['id'=>$item['id']])->execute();
				Yii::$app->db->createCommand()->update('customers_to_class', [
						'state'=>\app\modules\admin\models\ClassManage::$CLASS_CUSTOMER_RESERVE
				],[
						'class_id'=>$item['class_id'],
						'customer_id'=>$item['customer_id'],
				])->execute();
				$s = true;
			}
			if($f['feedback'] != ""){
				\app\modules\admin\models\Siteconfigs::updateBizrule(\app\modules\admin\models\ClassReserve::tableName(),['id'=>$item['id']],[
						'feedback'=>$f['feedback'],
				]);
				$s = true;
			}
			
			if($s){
				$form2 = '<p>Xin chào: <b>'.$item['customer_name'].'</b></p>';
				
				$form2 .= '<p><b>'.DOMAIN.'</b> phản hồi nội dung bảo lưu kết quả học tập lớp <b>'.$item['class_name'].'</b> của bạn như sau:</p>';
				
				$form2 .= '<p>Trạng thái: <b>'.(isset($f['is_confirm']) && $f['is_confirm'] == 'on' ? 'Đã xác nhận' : 'Chưa xác nhận').'</b></p>';
				if($f['feedback'] != ""){
					$form2 .= '<p>Nội dung phản hồi:</p>';
					
					$form2 .= '<blockquote>' . uh($f['feedback']) . '</blockquote>';
				}
				$fx = Yii::$app->zii->getConfigs('CONTACTS');
				$fx['sender'] = $fx['email'];
				$fx['short_name']  = $fx['short_name'] != "" ? $fx['short_name'] : $fx['name'];
				//$customer = \app\modules\admin\models\Customers::getItem($item['customer_id']);
				//$class = \app\modules\admin\models\Customers::getItem($item['class_id']);
				Yii::$app->zii->sendEmail(array(
						'subject'=>DOMAIN . ' phản hồi yêu cầu bảo lưu.' ,
						'body'=>$form2,
						'from'=>$fx['sender'],
						'fromName'=>$fx['short_name'] != "" ? $fx['short_name'] : $fx['name'],
						'replyTo'=>$fx['email'],
						'replyToName'=>$fx['short_name'],
						'to'=>$item['customer_email'],'toName'=>$item['customer_name']
				));
			}
		}
		echo json_encode([
				//'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>'showModal(\'Thông báo\',\'Thiết lập thành công\');'
		]); exit;
		break;
	case 'confirm-class-reserve':
		$html = '';
		
		$html .= '<div class="form-group">
				<label class="col-sm-12">Phản hồi (không bắt buộc)</label>
				
				<div class="col-sm-12">
<textarea rows="3" class="form-control" name="f[feedback]" placeholder="Nội dung phản hồi"></textarea>
				
</div></div>
				
				<div class="checkbox">
    <label>
      <input type="checkbox" name="f[is_confirm]"> Xác nhận bảo lưu
    </label>
  </div>
				';
		
		$html .= '<div class="clear"></div><div class="row"><div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu lại</button>';
		
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Hủy</button>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		//
		$html .= '</div></div>';
		//
		echo json_encode([
				'html'=>$html,
				//'callback'=>true,
				//'callback_function'=>'reloadAutoPlayFunction(true);'
		]); exit;
		break;
	case 'add_member_to_class':
		
		$html = '';
		
		$html .= '<div class="form-group"><div class="col-sm-12">
<select data-placeholder="Tìm kiếm thành viên" name="member_id[]" class="form-control input-sm js-select-data-ajax select2-hidden-accessiblxe srequired" data-role="load_member" style="width: 100%" multiple="multiple">
		<option></option></select>
</div></div>';
		
		$html .= '<div class="clear"></div><div class="row"><div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu lại</button>';
		
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Hủy</button>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		//
		$html .= '</div></div>';
		//
		echo json_encode([
				'html'=>$html,
				//'callback'=>true,
				//'callback_function'=>'reloadAutoPlayFunction(true);'
		]); exit;
		break;
	case 'quick-add_member_to_class_excel':
		$f = post('f',[]);
		$class_id = post('class_id',0);
		if($class_id>0 && !empty($f)){
			foreach ($f as $v){
				$customer_id = (new \app\models\Members())->registerMember($v,[
						'send_password'=>(post('send_password') == 'on' ? true : false),
						'code'=>genCustomerCode(
								isset(Yii::$site['settings']['customers'][TYPE_ID_MEMBER]['code']) ?
								Yii::$site['settings']['customers'][TYPE_ID_MEMBER]['code'] : []),
				]);
				
				
				if($customer_id>0){
					if((new Query())->from('customers_to_class')->where(['class_id' => $class_id,
							'customer_id' => $customer_id,
							'state' => \app\modules\admin\models\ClassManage::$CLASS_STATUS_ACTIVE,])->count(1) == 0){
							Yii::$app->db->createCommand()->insert('customers_to_class', [
									'class_id' => $class_id,
									'customer_id' => $customer_id,
									'state' => \app\modules\admin\models\ClassManage::$CLASS_STATUS_ACTIVE,
							])->execute();
					}
				}
				
			}
		}
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
				'f'=>$f,
				'callback_function'=>' reloadAutoPlayFunction(true);'
		]);exit;
		break;
	case 'add_member_to_class_excel':
		
		$html = '';
		
		$html .= '<div class="form-group"><div class="col-sm-12">';
		
		$html .= '<div class="btn-upload-image">
 <input data-name="f" title=" Chọn tệp tin tải lên"
				data-folder_save="/tmp"
				data-upload-group="-xls-import-class"
				data-include_site_name="0"
				data-group="-xls-import-class"
				data-filename-placement="inside"
				onchange="sajax_upload_image_files(this)"
				type="file" class="bootstrap-file-inputs btn-boostrap-file-input fa fa-file-image-o f12e btn-default btn-sm"
				name="myfile" id="myfile-xls-import-class" accept=".xls,.xlsx" />
</div>
<div id="progress-group-xls-import-class" class="mgt15"></div>
<div id="respon_image_uploaded-xls-import-class" class="mgt15"></div>
';
		
		$html .= '</div></div>';
		
		$html .= '<div class="checkbox"><label>
      	<input type="checkbox" name="send_password" checked> Gửi thông tin tài khoản tới email đăng ký mới
    	</label></div>';
		
		$html .= '<div class="clear"></div><div class="row"><div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu lại</button>';
		
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Hủy</button>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		//
		$html .= '</div></div>';
		//
		echo json_encode([
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>'jQuery(\'.bootstrap-file-inputs\').bootstrapFileInput();'
		]); exit;
		break;
		
	case 'quick-qadd-more-filter-tour-guest-group':
		$item_id = post('item_id',0);
		$f = post('f',[]);
		$new = post('new',[]);
		if(!empty($new)){
			foreach ($new as $v){
				if(trim($v['title']) != "" && trim($v['value']) != "" && trim($v['value1']) != ""){
					if((new Query())->from('filters')->where([
							'code'=>$v['code'],
							'value'=>isset($v['value']) ? $v['value'] : '',
							'value1'=>isset($v['value1']) ? $v['value1'] : '',
							'sid'=>__SID__,
							'lang'=>__LANG__
					])->count(1) == 0){
						$new_id = Yii::$app->zii->insert('filters',[
								'code'=>$v['code'],
								'title'=>$v['title'],
								'value'=>isset($v['value']) ? $v['value'] : '',
								'value1'=>isset($v['value1']) ? $v['value1'] : '',
								'sid'=>__SID__,
								'lang'=>__LANG__
						]);
					}else{
						$new_id = (new Query())->select('id')->from('filters')->where([
								'code'=>$v['code'],
								'value'=>isset($v['value']) ? $v['value'] : '',
								'value1'=>isset($v['value1']) ? $v['value1'] : '',
								'sid'=>__SID__,
								'lang'=>__LANG__
						])->scalar();
					}
					
					$f[] = $new_id;
				}
			}
			
		}
		
		if(!empty($f)){
			foreach ($f as $filter_id){
				if((new Query())->from('articles_to_filters')->where([
						'item_id'=>$item_id,
						'filter_id'=>$filter_id,
				])->count(1) == 0){
					Yii::$app->db->createCommand()->insert('articles_to_filters', [
							'item_id'=>$item_id,
							'filter_id'=>$filter_id,
					])->execute();
				}
			}
		}
		
		echo json_encode([
				//		'html'=>$html,
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>'reloadAutoPlayFunction(true);'
		]); exit;
		break;
	case 'quick-qadd-more-filter-tour-date-time':
		$f = post('f',[]);
		$filter_id = post('filter_id',0);
		$item_id = post('item_id');
		$data = [];
		$callback_function = ''; $event ='hide-modal';
		foreach ($f as $v){
			$date = ctime(['string'=>$v['date'],'format'=>'Y-m-d']);
			$code = 'tour_date_time';
			$price_code = $code . '_' . $date;
			$old_price_code = 'old_' . $code . '_' . $date;
			
			if($filter_id==0){ // Thêm mới
				if((new Query()) -> from('filters')->where([
						'sid'=>__SID__,
						'code'=>$code,
						'date'=>$date
				])->count(1) == 0){
					
					$fid = Yii::$app->zii->insert('filters', [
							'sid'=>__SID__,
							'code'=>$code,
							'date'=>$date,
							'title'=>'Khởi hành '.$v['date'],
					]);
				}else{
					$fid = (new Query())->select('id') -> from('filters')->where([
							'sid'=>__SID__,
							'code'=>$code,
							'date'=>$date
					])->scalar();
				}
				
				if((new Query()) -> from('articles_to_filters')->where([
						'item_id'=>$item_id,
						'filter_id'=>$fid
				])->count(1) == 0){
					if((new Query())->from('articles_prices_list')->where(['sid'=>__SID__,'code'=>$price_code])->count(1) == 0){
						Yii::$app->db->createCommand()->insert('articles_prices_list', [
								
								'code'=>$price_code,
								'title'=>'Giá ngày '. $v['date'],
								'sid'=>__SID__,
						])->execute();
					}
					if(cprice($v['price1']) > 0){
						if((new Query())->from('articles_prices_list')->where(['sid'=>__SID__,'code'=>$old_price_code])->count(1) == 0){
							Yii::$app->db->createCommand()->insert('articles_prices_list', [
									
									'code'=>$old_price_code,
									'title'=>'Giá cũ ngày '. $v['date'],
									'sid'=>__SID__,
							])->execute();
						}
					}
					if($fid>0 ){
						//
						
						if((new Query())->from('articles_to_filters')->where(['item_id'=>$item_id,'filter_id'=>$fid])->count(1) == 0){
							Yii::$app->db->createCommand()->insert('articles_to_filters',['item_id'=>$item_id,'filter_id'=>$fid])->execute();
						}
						//
						Yii::$app->db->createCommand()->insert('articles_prices', [
								'item_id'=>$item_id,
								'code'=>$price_code,
								'price'=>cprice($v['price2']),
								'currency'=>$v['currency'],
						])->execute();
						
						if(cprice($v['price1']) > 0){
							Yii::$app->db->createCommand()->insert('articles_prices', [
									'item_id'=>$item_id,
									'code'=>$old_price_code,
									'price'=>cprice($v['price1']),
									'currency'=>$v['currency'],
							])->execute();
						}
					}
					
				}else{
					$event = false;
					$callback_function .= 'showModal(\'Thông báo\',\'Ngày khởi hành đã tồn tại, vui lòng chọn ngày khác.\');';
				}
			}
		}
		
		$callback_function .= 'reloadAutoPlayFunction(true);';
		echo json_encode([
				'modal_target'=>'.mymodal1',
				
				'event'=>$event,
				'callback'=>true,
				'callback_function'=>$callback_function
		]); exit;
		
		break;
	case 'qadd-more-filter-tour-date-time':
		$html = '';
		$code = post('code');
		$rand = 'rh_' . randString(12);
		$item_id = post('item_id');
		$currency = 1; $price_code = '';
		$html .= '
				
				<div class="form-group"><div class="col-sm-12">
		<label class="fl100">Ngày tháng</label>
				
		<input name="f[0][date]" data-format="d/m/Y" type="text" required class="form-control required datepicker" placeholder="Ngày tháng d/m/Y"/>
		</div></div>';
		
		$html .= '<div class="form-group"><div class="col-sm-5">
		<label class="fl100">Đơn giá</label>
				
		<input name="f[0][price2]" type="text" required class="red bold center required form-control input-sm number-format input-price-'.$rand.'" placeholder="Giá bán ra"/>
		</div>
		<div class="col-sm-5">
		<label class="fl100">Giá niêm yết</label>
				
		<input name="f[0][price1]" type="text" class="red bold center form-control input-sm number-format input-price-'.$rand.'" placeholder="Giá so sánh"/>
		</div>
				
				<div class="col-sm-2">
		<label class="fl100">Tiền tệ</label>';
		
		$html .= '<select
							data-decimal="'.Yii::$app->zii->showCurrency($currency,3).'"
							data-target-input=".input-price-'.$rand.'"
							onchange="get_decimal_number(this);"
						 	data-action="change_item_price_currency"
						 	data-price_code="'.$price_code.'"
						 	data-item_id="'.$item_id.'"
						 			
							class="ajax-select2-no-search sl-cost-price-currency form-control ajax-select2 input-sm"
							data-search="hidden" name="f[0][currency]">';
		
		foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v3){
			$html .= '<option value="'.$v3['id'].'" '.($currency == $v3['id'] ? 'selected' : '').'>'.$v3['code'].'</option>';
		}
		
		$html .= '</select>';
		$html .= '</div>
				</div>';
		
		$html .= '<div class="form-group hide"><div class="col-sm-12">
		<label class="fl100">Ghi chú</label>
				
		<input name="f[0][note]" type="text" class="form-control" placeholder="Ghi chú"/>
		</div></div>';
		
		$html .= '<div class="clear"></div><div class="row"><div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu lại</button>';
		
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Hủy</button>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		//
		$html .= '</div></div>';
		//
		echo json_encode([
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>'load_datetimepicker();load_number_format()'
		]); exit;
		break;
		
	case 'qadd-more-filter-tour-guest-group':
		$html = '';
		$code = post('code');
		
		$html .= '<div class="form-group"><div class="col-sm-12">
		<label class="fl100">Các nhóm có thể chọn</label>
		<select multiple="multiple" class="chosen-select" name="f[]">';
		foreach (\app\modules\admin\models\Filters::getFilters([
				//'parent_id'=>post('id',0),
				'code'=>$code
		]) as $v2){
			$html .= '<option value="'.$v2['id'].'">'.uh($v2['title']).' ('.$v2['value'].' - '.$v2['value1'].')'.'</option>';
		}
		$html .= '</select>
		</div></div><hr>';
		
		$html .= '
				<p class="help-block bold">Nếu chưa có trong danh sách thì thêm mới qua form dưới</p>
				<div class="form-group"><div class="col-sm-12">
		<label class="fl100">Tên nhóm</label>
		<input name="new[0][code]" type="hidden" class="required" value="'.$code.'" />
		<input name="new[0][title]" type="text" class="form-control " placeholder="Tiêu đề"/>
		</div></div>';
		
		$html .= '<div class="form-group"><div class="col-sm-12">
		<label class="fl100">Giá trị bắt đầu</label>
				
		<input name="new[0][value]" type="text" class="form-control " placeholder="Từ x người"/>
		</div></div>';
		
		$html .= '<div class="form-group"><div class="col-sm-12">
		<label class="fl100">Giá trị kết thúc</label>
				
		<input name="new[0][value1]" type="text" class="form-control" placeholder="Đến x người"/>
		</div></div>';
		
		$html .= '<div class="clear"></div><div class="row"><div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu lại</button>';
		
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Hủy</button>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		//
		$html .= '</div></div>';
		//
		echo json_encode([
				'html'=>$html,
				//'callback'=>true,
				//'callback_function'=>'reloadAutoPlayFunction(true);'
		]); exit;
		break;
	case 'loadTourDetailFilterTourType1':
		$currency = 1;
		$html = '';
		$html .= '<div class="mg5 mgt0">';
		$html .= '<table class="table table-bordered vmiddle table-hovere table-striped table_depart_scheduler_3 sx">';
		
		$html .= '<thead> <tr>
				<th width="60" class="center">
<input type="checkbox" onchange="toggle_tr_hidden(this);" title="Hiển thị (ẩn) các ngày quá hạn"/>
</th>
				<th width="150" class="center">Ngày khởi hành</th>
<th width="150" class="center">Hạn đặt tour</th>
				<th class="center">Đơn giá</th>
				<th class="center">Giá so sánh (giá niêm yết)</th>
				<th class="center">Giá đối tác</th>
				<th class="center">Tiền tệ</th>
				<th class="center">Tình trạng</th>
					';
		
		$html .= '<th class="center" width="100">Thao tác</th></tr> </thead>';
		$html .= '<tbody class="">';
		$item_id = post('item_id',0);
		$filter_id = post('id',0);
		//$html .= '<table class="table table-bordered vmiddle list-sm1">';
		$item = \app\modules\admin\models\Filters::getItem(post('id'));
		$istate = false;
		$l = \app\modules\admin\models\Filters::getFilters([
				//'parent_id'=>post('id',0),
				'item_id'=>$item_id,
				'code'=>'tour_date_time',
				'orderBy'=>['a.date'=>SORT_ASC,'a.position'=>SORT_ASC,'a.title'=>SORT_ASC]
		]);
		if(!empty($l)){
			foreach ($l as $k2=>$v2){
				$price_code = $v2['code'] . '_' . $v2['date'];
				$price = \common\models\Articles::getItemPrice($price_code,$item_id);
				
				if(!$istate &&  strtotime($v2['date']) > time()){
					$price['state'] = 1;
					$istate = true;
				}
				
				
				$filter = \app\models\Articles::getItemFilter($item_id,$v2['id']);
				$price_code_old = 'old_price_' .$price_code;
				$price_code_partner = 'partner_price_' .$price_code;
				$price_old = \common\models\Articles::getItemPrice($price_code_old,$item_id);
				$price_partner = \common\models\Articles::getItemPrice($price_code_partner,$item_id);
				$code = $v2['code'] . '_' . $v2['value'];
				
				$currency = isset($price['currency']) ? $price['currency'] : $currency;
				
				$cclass = strtotime($v2['date']) > time() ? '' : 'hide tr-hidden';
				
				$html .= '<tr class="'.$cclass.'"><td class="center">'.($k2+1).'</td>
						
					<td class="center">
			<label class="ip-label font-normal">
						'.date('d/m/Y',strtotime($v2['date'])).'
						</label>
			</td>';
				$html .= '<td class="center pr"><input type="text"
							class="center form-control input-sm datepicker"
							data-price_code="'.$price_code.'"
							data-price_name="'. uh($v2['title']). ' "
							data-item_id="'.$item_id.'"
							data-filter_id="'.$v2['id'].'"
							onblur="call_ajax_function(this)"
							data-action="change_item_price_dateline"
							data-old="'.(isset($filter['to_date']) && check_date_string($filter['to_date'],2014) ? date('d/m/Y',strtotime($filter['to_date'])) : '').'"
							value="'.(isset($filter['to_date']) && check_date_string($filter['to_date'],2014) ? date('d/m/Y',strtotime($filter['to_date'])): '').'"
							data-format="d/m/Y"
							/></td>';
				$html .= ' <td class="center">
						
						<input type="text"
						
							class="red bold center form-control input-sm number-format input-price-'.$price_code.'"
							data-price_code="'.$price_code.'"
							data-price_name="'. uh($v2['title']). '"
							data-item_id="'.$item_id.'"
							onblur="call_ajax_function(this)"
							data-action="change_item_price"
							data-state="'.(isset($price['state']) ? $price['state'] : 2).'"
							data-old="'.(isset($price['price']) ? $price['price'] : '').'" value="'.(isset($price['price']) ? $price['price'] : '').'"
							data-currency="'.((isset($price['currency']) ? $price['currency'] : 1)).'"
							data-decimal="'.Yii::$app->zii->showCurrency($currency,3).'" />
									
									</td>';
				$html .= '<td class="center"><input type="text"
							class="red bold center form-control input-sm number-format input-price-'.$price_code.'"
							data-price_code="'.$price_code_old.'"
							data-price_name="'. uh($v2['title']). ' "
							data-item_id="'.$item_id.'"
							onblur="call_ajax_function(this)"
							data-action="change_item_price"
							data-old="'.(isset($price_old['price']) ? $price_old['price'] : '').'" value="'.(isset($price_old['price']) ? $price_old['price'] : '').'"
							data-currency="'.((isset($price_old['currency']) ? $price_old['currency'] : 1)).'"
							data-decimal="'.Yii::$app->zii->showCurrency($currency,3).'" /></td>';
				
				$html .= '<td class="center"><input type="text"
							class="red bold center form-control input-sm number-format input-price-'.$price_code.'"
							data-price_code="'.$price_code_partner.'"
							data-price_name="'. uh($v2['title']). ' "
							data-item_id="'.$item_id.'"
							onblur="call_ajax_function(this)"
							data-action="change_item_price"
							data-old="'.(isset($price_partner['price']) ? $price_partner['price'] : '').'" value="'.(isset($price_partner['price']) ? $price_partner['price'] : '').'"
							data-currency="'.((isset($price_partner['currency']) ? $price_partner['currency'] : 1)).'"
							data-decimal="'.Yii::$app->zii->showCurrency($currency,3).'" /></td>';
				
				$html .= '<td class=" center">';
				$html .= '<select
							data-decimal="'.Yii::$app->zii->showCurrency($currency,3).'"
							data-target-input=".input-price-'.$price_code.'"
							onchange="get_decimal_number(this);"
						 	data-action="change_item_price_currency"
						 	data-price_code="'.$price_code.'"
						 	data-item_id="'.$item_id.'"
						 	data-action-change-after-save="1"
							class="ajax-select2-no-search sl-cost-price-currency form-control ajax-select2 input-sm"
							data-search="hidden" name="">';
				
				foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v3){
					$html .= '<option value="'.$v3['id'].'" '.($currency == $v3['id'] ? 'selected' : '').'>'.$v3['code'].'</option>';
				}
				
				$html .= '</select>';
				$html .= '</td>';
				
				$html .= '<td class="center"><input type="text"
							class="center form-control input-sm"
							data-price_code="'.$price_code.'"
							data-price_name="'. uh($v2['title']). ' "
							data-item_id="'.$item_id.'"
							data-filter_id="'.$v2['id'].'"
							onblur="call_ajax_function(this)"
							data-action="change_item_price_status"
							data-old="'.(isset($filter['status']) ? $filter['status'] : '').'"
							value="'.(isset($filter['status']) ? $filter['status'] : '').'"
									
							/></td>';
				
				$html .= '<td class="w50p center">
<i data-item_id="'.$item_id.'" data-filter_id="'.$v2['id'].'" onclick="call_ajax_function(this);" data-action="TOUR_TYPE1_add_detail_text" class="pointer fa fa-edit" title="Chỉnh sửa text chi tiết"></i>
			<i data-modal-target=".mymodal1" data-price_code="'.$price_code.'" data-item_id="'.$item_id.'" data-filter_id="'.$v2['id'].'" data-confirm-action="quick-delete-article-filter-date-time" data-title="Xóa '.$item['title'] . ': '. $v2['title'].'" onclick="open_ajax_modal(this);" data-class="modal-sm" data-action="open-confirm-dialog" class="fa fa-trash f14px ic-delete"></i>
			</td>
			</tr>';
			}}
			$html .= '<tr class="vmiddle"> <td colspan="8"></td>
<td class="center">
	<i data-select="0" data-modal-target=".mymodal1" data-class="w60" data-item_id="'.$item_id.'" title="Thêm ngày khởi hành" data-index="0" data-code="tour_date_time" data-action="qadd-more-filter-tour-date-time" onclick="open_ajax_modal(this);" class="glyphicon glyphicon-plus pointer"></i>
</td>
</tr>';
			$html .= '</tbody>';
			$html .= '</table></div>';
			
			$html .= '</div></div>';
			
			echo json_encode([
					'html'=>$html,
					//'callback'=>true,
					//'callback_function'=>'load_number_format();'
					'callback'=>true,
					'callback_function'=>'load_datetimepicker();'
			]+$_POST); exit;
			break;
	case 'TOUR_TYPE1_add_detail_text':
		$html = $callback_function = '';
		$item_id = post('item_id');
		$filter_id = post('filter_id');
		
		$callback_function .= '$this.addClass("red");';
		
		$ck_id = randString(8);
		$item = (new Query())->from('articles')->where([
				'id'=>$item_id,
				//'filter_id'=>$filter_id
		])->one();
		$t = (new Query())->from('articles_to_filters')->where([
				'item_id'=>$item_id,
				'filter_id'=>$filter_id
		])->one();
		
		$html .= '<tr class="tourtype1_text_detail_'.$item_id.'_'.$filter_id.'"><td colspan="9"><div class="pd15">
				
<div class="form-group ">
<div class="col-md-12 col-sm-12 col-xs-12">
<label>Tiêu đề</label>
<input name="tab_biz_filter['.$filter_id.'][title]" value="'.(isset($t['detail']['title']) ? uh($t['detail']['title']) : '').'" type="text" class="form-control" placeholder="Tiêu đề bài viết">
		
		
		
		
  </div>  </div>
<div class="form-group ">
<div class="col-md-12 col-sm-12 col-xs-12 mgt15">
<label>Ghi chú</label>
<input name="tab_biz_filter['.$filter_id.'][note]" value="'.(isset($t['detail']['note']) ? uh($t['detail']['note']) : '').'" type="text" class="form-control" placeholder="Ghi chú">
		
		
		
		
  </div>  </div>
<div class="form-group">
<div class="col-md-12 col-sm-12 col-xs-12 mgt15">
<label>Nội dung</label>
<textarea id="ck_'.$ck_id.'" name="tab_biz_filter['.$filter_id.'][text]" class="form-control ckeditor_full" placeholder="Chi tiết bài viết">'.(isset($t['detail']['text']) ? uh($t['detail']['text'],2) : '').'</textarea>
		
		
		
		
  </div>  </div>';
		
		$html .= '<div class="panel panel-default panel-other-detail mgt30">
  <div class="panel-heading">
    <h3 class="panel-title">Thông tin khác</h3>
  </div>
  <div class="panel-body f12px"><ul class="style-none pdl0">';
		
		if(isset($item['tour_other_detail']) && !empty($item['tour_other_detail'])){
			foreach ($item['tour_other_detail'] as $code=>$s){
				
				if(isset($t['detail']['tour_other_detail'][$code]) && $t['detail']['tour_other_detail'][$code] != ""){
					$s = $t['detail']['tour_other_detail'][$code];
				}
				
				$rand = randString(8);
				$rand2 = randString(8);
				$v1 = \app\modules\admin\models\ExamplesData::getTourInfoCategoryDetail($code);
				
				$html .= '<li class="t2-panel mgt15 mgb15 t2-panel-'.$v1['code'].'">
    <div class="green pr mgb10"><i class="'.$v1['icon'].' "></i> <b class="upper">'.$v1['title'].'</b>
    		
    		
<div class="ps r0 t0">
<label class="font-normal">
<input type="checkbox" name="tab_biz_filter['.$filter_id.'][tour_other_detail]['.$v1['code'].'][is_active]" '.(isset($s['is_active']) && $s['is_active'] == 'on' ? 'checked' : '').'/>&nbsp;
Kích hoạt
</label>
&nbsp;-&nbsp;
<label class="font-normal" for="'.$rand2.'">
		
<i id="'.$rand2.'" class="fa fa-refresh pointer " data-item_id="'.$item_id.'" onclick="call_ajax_function(this);" data-code="'.$v1['code'].'" data-target="#'.$rand.'" data-action="reload-default-tour-other-detail"></i>&nbsp;
Lấy giá trị mặc định
</label>
		
		
</div>
		
</div>
		
		
		
    <textarea data-height="150" name="tab_biz_filter['.$filter_id.'][tour_other_detail]['.$v1['code'].'][text]" class="form-control ckeditor_basic4" rows=5 id="'.$rand.'">'.uh(isset($s['text']) ? $s['text'] : '',2).'</textarea>
   </li>';
			}
		}
		
		$html .= '</ul></div></div>';
		
		$html .= '</div></td></tr>';
		
		//$callback_function .= 'jQuery(\'.tourtype1_text_detail_'.$item_id.'_'.$filter_id.'\').after($d.html);';
		$callback_function .= 'if(jQuery(\'.tourtype1_text_detail_'.$item_id.'_'.$filter_id.'\').length==0){
	$this.parent().parent().after($d.html);loadCkeditorBasic4();
}else{
	jQuery(\'.tourtype1_text_detail_'.$item_id.'_'.$filter_id.'\').toggle();loadCkeditorBasic4();
			
}';
		$callback_function .= 'create_ckeditor(\'#ck_'.$ck_id.'\');';
		
		
		echo json_encode([
				'html'=>$html,
				
				'callback'=>true,
				'callback_function'=>$callback_function
		]+$_POST); exit;
		break;
	case 'change_item_price_status':
		$filter_id = post('filter_id');
		$item_id= post('item_id');
		\app\modules\admin\models\Siteconfigs::updateBizrule('articles_to_filters',[
				'filter_id' => $filter_id ,
				'item_id' => $item_id
		],[
				'status'=>post('value')
				
		]);
		
		echo json_encode([
				//'html'=>$html,
				'callback'=>true,
				'callback_function'=>'console.log(data);'
		]+$_POST); exit;
		break;
		
	case 'change_item_price_dateline':
		$filter_id = post('filter_id');
		$item_id= post('item_id');
		Yii::$app->db->createCommand()->update('articles_to_filters',[
				'to_date'=>ctime([
						'string'=>post('value'),
						'format'=>'Y-m-d H:i:s'
				])
		],[
				'filter_id' => $filter_id ,
				'item_id' => $item_id
		])->execute();
		
		echo json_encode([
				//'html'=>$html,
				'callback'=>true,
				'callback_function'=>'console.log(data);'
		]+$_POST); exit;
		break;
	case 'loadTourDetailFilterTourType':
		$html = '<div class="mg5 mgt0">';
		$html .= '<table class="table table-bordered vmiddle table-hovere table-striped table_depart_scheduler_3">';
		
		$html .= '<thead> <tr>
				<th width="60" class="center">#</th>
				<th width="250">Tiêu đề</th>
				<th class="center">Từ (người)</th>
				<th class="center">Đến (người)</th>';
		
		$html .= '<th class="center" width="100">Thao tác</th></tr> </thead>';
		$html .= '<tbody class="">';
		$item_id = post('item_id',0);
		$filter_id = post('id',0);
		//$html .= '<table class="table table-bordered vmiddle list-sm1">';
		$item = \app\modules\admin\models\Filters::getItem(post('id'));
		foreach (\app\modules\admin\models\Filters::getFilters([
				'code'=>'tour_guest_group',
				'parent_id'=>post('id',0),
				'item_id'=>post('item_id',0),
				'orderBy'=>['a.value'=>SORT_ASC,'a.position'=>SORT_ASC,'a.title'=>SORT_ASC]
		]) as $k2=>$v2){
			$html .= '<tr class=""><td class="center">'.($k2+1).'</td>
					
					<td>
			<input name="f['.$v2['id'].'][title]" class="form-control required" value="'. $v2['title'].'" placeholder="Tiêu đề"/>
			</td>
					<td>
					<input name="f['.$v2['id'].'][value]" class="form-control required center" type="text" value="'. $v2['value'].'" placeholer="Số người tối thiểu" />
			</td>
					<td>
			<input name="f['.$v2['id'].'][value1]" class="form-control required center" type="text" value="'. $v2['value1'].'" placeholer="Số người tối đa" />
			</td>
					<td class="w50p center">
			<i data-modal-target=".mymodal1" data-item_id="'.$item_id.'" data-filter_id="'.$v2['id'].'" data-confirm-action="quick-delete-article-filter" data-title="Xóa '.$item['title'] . ': '. $v2['title'].'" onclick="open_ajax_modal(this);" data-class="modal-sm" data-action="open-confirm-dialog" class="fa fa-trash f14px ic-delete"></i>
			</td>
			</tr>';
		}
		$html .= '<tr class="vmiddle"> <td colspan="4"></td>
<td class="center">
	<i data-select="0" data-modal-target=".mymodal1" data-class="w60" data-item_id="'.$item_id.'" title="Thêm nhóm mới" data-index="0" data-code="tour_guest_group" data-action="qadd-more-filter-tour-guest-group" onclick="open_ajax_modal(this);" class="glyphicon glyphicon-plus pointer"></i>
</td>
</tr>';
		$html .= '</tbody>';
		$html .= '</table></div>';
		//$html .= '</table>';
		
		$html .= '<div class="clear"></div><div class="row"><div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu lại</button>';
		
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Hủy</button>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		//
		$html .= '</div></div>';
		
		echo json_encode([
				'html'=>$html,
				//'callback'=>true,
				//'callback_function'=>'load_number_format();'
		]+$_POST); exit;
		break;
	case 'change_item_price':
		$item_id = post('item_id');
		$value = post('value') != "" ? cprice(post('value')) : 0;
		$price_code = post('price_code');
		$price_name = post('price_name');
		$currency = post('currency');
		$state = post('state',2);
		//
		
		//
		\app\modules\admin\models\Content::updateItemPrice([
				'item_id'=>$item_id,
				'price'=>$value,
				'price_code'=>$price_code,
				'price_name'=>$price_name,
				'currency'=>$currency ,
				'state'=>$state
		]);
		
		//
		if(substr($price_code, 0,14) == 'tour_date_time'){
			
			$next_day = substr($price_code, 15);
			$ex = new \yii\db\Expression(" REPLACE(`code`, \"tour_date_time_\", \"\")");
			$t = (new Query())
			->select(['*','time_code'=>$ex])
			->from('articles_prices')
			->where(
					['and',
							['item_id'=>$item_id],
							//['>','time',date('Y-m-d 00:00:00')],
							['like', 'code', 'tour_date_time_%', false]
							
					]
					)
					->andWhere("unix_timestamp(".$ex.")<".strtotime($next_day))
					->orderBy(['time_code'=>SORT_ASC])->one();
					if(empty($t)){
						$next_day = date('Y-m-d');
					}
					if((new Query())
							->from('cronjobs')
							->where([
									'type_code'=>\common\models\Cronjobs::$CHANGE_PRICE_STATE_TYPE1,
									'item_id'=>$item_id,
									'sid'=>__SID__,
									'time'=>$next_day . ' 00:00:00',
							])
							->count(1) == 0){
								Yii::$app->db->createCommand()->insert('cronjobs', [
										'type_code'=>\common\models\Cronjobs::$CHANGE_PRICE_STATE_TYPE1,
										'item_id'=>$item_id,
										'sid'=>__SID__,
										'time'=>$next_day . ' 00:00:00',
								])->execute();
							}
		}
		//
		echo json_encode([
				//'html'=>$html,
				//'d'=>substr($price_code,0, 14),
				//'m'=>substr($price_code, 15),
				//'callback'=>true,
				//'callback_function'=>'console.log(data);'
		]); exit;
		break;
	case 'change_item_price_currency':
		$item_id = post('item_id');
		$value = post('value',1);
		$price_code = post('price_code');
		$price_name = post('price_name');
		
		Yii::$app->db->createCommand()->update('articles_prices', ['currency'=>$value],[
				'code'=>$price_code,
				'item_id'=>$item_id,
		])->execute();
		echo json_encode([
				//'html'=>$html,
				//'callback'=>true,
				//'callback_function'=>'load_number_format();'
		]); exit;
		break;
	case 'set_default_tour_price':
		$item_id = post('item_id',0);
		$price_code = post('price_code');
		$code = post('code');
		//
		Yii::$app->db->createCommand()->update('articles_prices', ['state'=>2],[
				'like','code', $code.'%'
		])->execute();
		Yii::$app->db->createCommand()->update('articles_prices', ['state'=>1],[
				'like','code', $price_code
		])->execute();
		//
		echo json_encode([
				//'html'=>$html,
				//'callback'=>true,
				//'callback_function'=>'load_number_format();'
		]+$_POST); exit;
		break;
		
	case 'set_default_tour_hotel_price':
		$item_id = post('item_id',0);
		$price_code = post('price_code');
		// Set gia
		Yii::$app->db->createCommand()->update('articles_prices', ['state'=>2],[
				'like','code', 'tour_hotel_%',false
		])->execute();
		Yii::$app->db->createCommand()->update('articles_prices', ['state'=>1],[
				'code'=> $price_code
		])->execute();
		//
		$group_id = post('group_id');
		$hotel_id = post('hotel_id');
		// Set hotel
		Yii::$app->db->createCommand()->update('articles_to_filters', ['state'=>0],[
				'item_id'=>$item_id,
				'filter_id'=>(new Query())->from('filters')->where([
						'code'=>['tour_hotel','tour_guest_group'],'sid'=>__SID__
				])->select('id')
		])->execute();
		
		Yii::$app->db->createCommand()->update('articles_to_filters', ['state'=>1],[
				'item_id'=>$item_id,
				'filter_id'=>[$group_id, $hotel_id]
		])->execute();
		
		echo json_encode([
				//'html'=>$html,
				
				//'callback'=>true,
				//'callback_function'=>'console.log(data);'
		]+$_POST); exit;
		break;
	case 'loadTourDetailFilterTourHotel':
		$html = '';
		$item_id = post('item_id',0);
		$code = post('code');
		$filter_id = post('id',0);
		$groups = \app\modules\admin\models\Filters::getFilters([
				'code'=>'tour_guest_group',
				'item_id'=>$item_id,
				'orderBy'=>['a.value'=>SORT_ASC,'a.position'=>SORT_ASC,'a.title'=>SORT_ASC]
		]);
		$html .= '<table class="table table-bordered vmiddle table-hovere table-striped">';
		$html .= '<thead>
				<tr><th>Khách sạn</th>';
		if(!empty($groups)){
			foreach ($groups as $group){
				$html .= '<th class="center">'.uh($group['title']).'</th>';
			}
		}
		$html .= '<th class="center w100p">Tiền tệ</th><th class="center">
				<div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Thao tác <span class="caret"></span>
  </button>
  <ul class="dropdown-menu dropdown-menu-right">
    <li><a data-class="w60" data-item_id="'.$item_id.'" title="Thêm nhóm mới" data-index="0" data-code="tour_guest_group" data-action="qadd-more-filter-tour-guest-group" onclick="return open_ajax_modal(this);" href="#"><i class="fa fa-group"></i>&nbsp; Thêm nhóm mới</a></li>
    <li><a data-class="w60" data-item_id="'.$item_id.'" title="Quản lý nhóm" data-index="0" data-code="tour_guest_group" data-action="loadTourDetailFilterTourType" onclick="return open_ajax_modal(this);" href="#"><i class="fa fa-cogs"></i>&nbsp; Quản lý nhóm</a></a></li>
    		
  </ul>
</div>
				</th></tr>
    		
				</thead><tbody>';
		foreach (\app\modules\admin\models\Filters::getFilters([
				'code'=>$code,
				'item_id'=>$item_id,
				'orderBy'=>['a.value'=>SORT_ASC,'a.position'=>SORT_ASC,'a.title'=>SORT_ASC]
		]) as $k1=>$v2){
			$html .= '<tr class="">
					
					<td>
			<label class="ip-label font-normal"><span>'. $v2['title']. ' </span></label>
			</td>';
			$currency = 1;
			if(!empty($groups)){
				foreach ($groups as $group){
					$price_code = $code . '_' . $v2['id'] . '_' . $group['id'];
					$price = \common\models\Articles::getItemPrice($price_code,$item_id);
					$currency = isset($price['currency']) ? $price['currency'] : $currency;
					$html .= '<td class="center">
							<div class="input-group">
							
							<input type="text"
							class="red bold center form-control input-sm number-format input-price-'.$k1.'"
							data-price_code="'.$price_code.'"
							data-price_name="'. uh($v2['title']). ' - '.uh($group['title']).'"
							data-item_id="'.$item_id.'"
							data-group_id="'.$group['id'].'"
							data-hotel_id="'.$v2['id'].'"
							onblur="call_ajax_function(this)"
							data-action="change_item_price"
							data-state="'.(isset($price['state']) ? $price['state'] : 2).'"
							data-old="'.(isset($price['price']) ? $price['price'] : '').'" value="'.(isset($price['price']) ? $price['price'] : '').'"
							data-currency="'.((isset($price['currency']) ? $price['currency'] : 1)).'"
							data-decimal="'.Yii::$app->zii->showCurrency($currency,3).'" />
									
									<span title="Đặt mặc định" class="input-group-addon">
        <input '.(isset($price['state']) && $price['state'] == 1 ? 'checked' : '').' onchange="call_ajax_function(this)" data-action="set_default_tour_hotel_price" data-price_code="'.$price_code.'" data-item_id="'.$item_id.'" data-group_id="'.$group['id'].'"
							data-hotel_id="'.$v2['id'].'" type="radio" aria-label="" name="setDefault['.$v2['code'].']">
      </span>
									</div>
									</td>';
				}
			}
			
			
			$html .= '<td class=" center">';
			if(isset($price_code)){
				$html .= '<select
							data-decimal="'.Yii::$app->zii->showCurrency($currency,3).'"
							data-target-input=".input-price-'.$k1.'"
							onchange="get_decimal_number(this);"
						 	data-action="change_item_price_currency"
						 	data-price_code="'.$price_code.'"
						 	data-item_id="'.$item_id.'"
						 	data-action-change-after-save="1"
							class="ajax-select2-no-search sl-cost-price-currency form-control ajax-select2 input-sm"
							data-search="hidden" name="">';
				
				foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v3){
					$html .= '<option value="'.$v3['id'].'" '.($currency == $v3['id'] ? 'selected' : '').'>'.$v3['code'].'</option>';
				}
				
				$html .= '</select>';
			}
			$html .= '</td><td></td>
			</tr>';
		}
		
		$html .= '</tbody></table>';
		
		$html .= '<table class="table table-bordered vmiddle table-hovere table-striped">
				<caption>Bảng giá phát sinh theo điểm khởi hành</caption>
				';
		$html .= '<thead>
				<tr><th>Điểm khởi hành</th>';
		
		$html .= '<th class="center">Giá phát sinh</th>';
		
		$html .= '<th class="center w100p">Tiền tệ</th> </tr>
				
				</thead><tbody>';
		foreach (\app\modules\admin\models\Filters::getFilters([
				'code'=>'tour_start',
				'item_id'=>$item_id,
				'orderBy'=>['a.value'=>SORT_ASC,'a.position'=>SORT_ASC,'a.title'=>SORT_ASC]
		]) as $k1=>$v2){
			$html .= '<tr class="">
					
					<td>
			<label class="ip-label font-normal"><span>'. $v2['title']. ' </span></label>
			</td>';
			$currency = 1;
			
			$price_code = $v2['code'] . '_' . $v2['id'];
			$price = \common\models\Articles::getItemPrice($price_code,$item_id);
			$currency = isset($price['currency']) ? $price['currency'] : $currency;
			$html .= '<td class="center"><div class="input-group"><input type="text"
							class="red bold center form-control input-sm number-format .input-price-'.$k1.'-'.$v2['code'].'"
							data-price_code="'.$price_code.'"
							data-price_name="Khởi hành từ '. uh($v2['title']).'"
							data-item_id="'.$item_id.'"
							onblur="call_ajax_function(this)"
							data-state="'.(isset($price['state']) ? $price['state'] : 2).'"
							data-action="change_item_price"
							data-old="'.(isset($price['price']) ? $price['price'] : '').'" value="'.(isset($price['price']) ? $price['price'] : '').'"
							data-currency="'.((isset($price['currency']) ? $price['currency'] : 1)).'"
							data-decimal="'.Yii::$app->zii->showCurrency($currency,3).'" />
							<span class="input-group-addon" title="Đặt mặc định">
        					<input '.(isset($price['state']) && $price['state'] == 1 ? 'checked' : '').' onchange="call_ajax_function(this);set_default_item_filter_value(this);" data-filter_id="'.$v2['id'].'" data-action="set_default_tour_price" data-price_code="'.$price_code.'" data-code="'.$v2['code'].'" data-item_id="'.$item_id.'" type="radio" aria-label="..." name="setDefaultPrice['.$v2['code'].']">
      						</span>
							</div>
							</td>';
			
			
			
			$html .= '<td class=" center">';
			$html .= '<select
							data-decimal="'.Yii::$app->zii->showCurrency($currency,3).'"
							data-target-input=".input-price-'.$k1.'-'.$v2['code'].'"
							onchange="get_decimal_number(this);"
						 	data-action="change_item_price_currency"
						 	data-price_code="'.$price_code.'"
		 				 	data-item_id="'.$item_id.'"
						 	data-action-change-after-save="1"
							class="ajax-select2-no-search sl-cost-price-currency form-control ajax-select2 input-sm"
							data-search="hidden" name="">';
			
			foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v3){
				$html .= '<option value="'.$v3['id'].'" '.($currency == $v3['id'] ? 'selected' : '').'>'.$v3['code'].'</option>';
			}
			
			$html .= '</select>';
			$html .= '</td><!--<td class="center">
					
					<input
			onchange="call_ajax_function(this)"
			data-item_id="'.$item_id.'"
			data-filter_id="'.$v2['id'].'"
			data-action="set_default_item_filter_value" type="radio"
			name="checked['.$v2['code'].']"
			value="'.$v2['id'].'" '.(\app\modules\admin\models\Content::getItemFilterState($item_id,$v2['id']) == 1 ? 'checked' : '').'/>
					</td>-->
			</tr>';
		}
		
		$html .= '</tbody></table>';
		
		echo json_encode([
				'html'=>$html,
				//'callback'=>true,
				//'callback_function'=>'load_number_format();'
		]+$_POST); exit;
		break;
		
		
		
	case 'loadTourDetailFilter':
		$html = '';
		$item_id = post('item_id',0);
		$filter_id = post('id',0);
		$html .= '<table class="table table-bordered vmiddle list-sm1">';
		$item = \app\modules\admin\models\Filters::getItem(post('id'));
		foreach (\app\modules\admin\models\Filters::getFilters([
				'parent_id'=>post('id',0),
				'item_id'=>post('item_id',0)
		]) as $v2){
			$html .= '<tr class=""><td>
			<label class="ip-label font-normal"><span>'. $v2['title'].'</span></label>
			</td><td class="w50p center">
			<i data-item_id="'.$item_id.'" data-filter_id="'.$v2['id'].'" data-confirm-action="quick-delete-article-filter" data-title="Xóa '.$item['title'] . ': '. $v2['title'].'" onclick="open_ajax_modal(this);" data-class="modal-sm" data-action="open-confirm-dialog" class="fa fa-trash f14px ic-delete"></i>
			</td>
			</tr>';
		}
		$html .= '</table>';
		echo json_encode([
				'html'=>$html,
				//'callback'=>true,
				//'callback_function'=>'reloadAutoPlayFunction(true);'
		]+$_POST); exit;
		break;
	case 'quick-tour_quick_add_detail_filter':
		$f = post('f',[]);
		$item_id = post('item_id');
		$code = post('code');
		$id = post('id');
		$update_filters = false;
		if(!empty($f)){
			switch (post('code')){
				case 'tour_destinationxx':case 'tour_startxx': case 'tour_placexxx':
					
					switch ($code){
						case 'tour_destination':
							$type_id = 1;
							break;
						case 'tour_start':
							$type_id = 2;
							break;
						default: // tour_place
							$type_id = 0;
							break;
					}
					
					$destinations = (new Query())->from('departure_places')->where(['id'=>$f])->all();
					if(!empty($destinations)){
						
						foreach ($destinations as $v){
							if((new Query())->from('departure_places_to_filters')->where([
									'item_id'=>$v['id'],
									'type_id'=>$type_id,
							])->count(1) == 0){
								$filter_id = Yii::$app->zii->insert('filters',[
										'code'=>'tour_destination',
										'title'=>$v['name'],
										'value'=>$v['id'],
										'parent_id'=>$id,
										'sid'=>__SID__,
										'lang'=>__LANG__
								]);
								$update_filters = true;
								Yii::$app->db->createCommand()->insert('departure_places_to_filters', [
										'item_id'=>$v['id'],
										'filter_id'=>$filter_id,
										'type_id'=>$type_id,
								])->execute();
							}else{
								$filter_id = (new Query())->select('filter_id')->from('departure_places_to_filters')->where([
										'item_id'=>$v['id'],
										'type_id'=>$type_id,
								])->scalar();
							}
							
							if((new Query())->from('articles_to_filters')->where([
									'item_id'=>$item_id,
									'filter_id'=>$filter_id
							])->count(1) == 0){
								Yii::$app->db->createCommand()->insert('articles_to_filters', [
										'item_id'=>$item_id,
										'filter_id'=>$filter_id
								])->execute();
							}
							if((new Query())->from('articles_to_filters')->where([
									'item_id'=>$item_id,
									'filter_id'=>(new Query())->select('id')->from('filters')->where(['code'=>$code,'sid'=>__SID__])
							])->count(1) == 1){
								Yii::$app->db->createCommand()->update('articles_to_filters',['state'=>1], [
										'item_id'=>$item_id,
										'filter_id'=>$filter_id
								])->execute();
							}
							
						}
						
						if($update_filters){
							Yii::$app->zii->update_table_lft([
									'table'=>'filters',
									'sid'=>__SID__,
									'level'=>true,
									'orderBy'=>['a.position'=>SORT_ASC,'a.title'=>SORT_ASC]
							]);
						}
						
					}
					
					break;
				default:
					switch ($code){
						case 'tour_destination':
							$type_id = 1;
							break;
						case 'tour_start':
							$type_id = 2;
							break;
						default: // tour_place
							$type_id = 0;
							break;
					}
					foreach ($f as $filter_id){
						if((new Query())->from('articles_to_filters')->where([
								'item_id'=>$item_id,
								'filter_id'=>$filter_id,
								//'type_id'=>$type_id
						])->count(1) == 0){
							Yii::$app->db->createCommand()->insert('articles_to_filters', [
									'item_id'=>$item_id,
									'filter_id'=>$filter_id,
									///'type_id'=>$type_id
							])->execute();
						}
					}
					break;
			}
			
		}
		echo json_encode([
				//		'html'=>$html,
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>'reloadAutoPlayFunction1();reloadAutoPlayFunction(true);'
		]); exit;
		break;
	case 'quick_search_filters_detail':
		$filters = \app\modules\admin\models\Filters::getAllArticleFilters(post('item_id'));
		$callback_function =  'jQuery(\'.list-sm1 .list-group-item-h\').hide();';
		foreach (\app\modules\admin\models\Filters::getFilters([
				'parent_id'=>post('id',0),
				'not_in'=>$filters,
				'filter_text'=>post('value'),
		]) as $v2){
			$callback_function .= 'jQuery(\'.list-sm1 .list-group-item-'.$v2['id'].'\').show();';
		}
		
		
		echo json_encode([
				//		'html'=>$html,
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>$callback_function
		]); exit;
		break;
	case 'tour_quick_add_detail_filter':
		$html = '';
		$category_id = post('category_id',0);
		$filters = \app\modules\admin\models\Filters::getAllArticleFilters(post('item_id'));
		$fitem = \app\modules\admin\models\Filters::getItem($category_id);
		$code = post('code');
		//switch ($code){
		//	case 'tour_type':
		$html .= '<ul class="list-group list-sm1 inline-block w100"><li class="list-group-item">
<input data-code="'.$code.'" data-id="'.post('id',0).'" data-id="'.post('item_id',0).'" data-action="quick_search_filters_detail" onkeyup="call_ajax_function(this);" onkeypress="return disabledFnKey(this);" class="form-control" value="" placeholder="Tìm kiếm nhanh"/></li>';
		switch (post('code')){
			case 'tour_destination':case 'tour_start': case 'tour_place':
				switch ($code){
					case 'tour_destination':
						$type_id = 1;
						break;
					case 'tour_start':
						$type_id = 2;
						break;
					default:
						$type_id = 0;
						break;
				}
				foreach (\app\modules\admin\models\Filters::getFilters([
						'parent_id'=>post('id',0),
						'not_in'=>$filters  ,
						//'category_id'=>iss,
						'category_value1'=>isset($fitem['value']) ? $fitem['value'] : 0,
				]) as $v2){
					$html .= '<li class="list-group-item list-group-item-h list-group-item-'.$v2['id'].'">
					<label class="ip-label"><input name="f[]" '.(in_array($v2['id'], $filters) ? 'checked' : '').' class="checkbox" type="checkbox" value="'.$v2['id'].'"/> <span>'. $v2['title'].'</span></label></li>';
				}
				break;
			default:
				foreach (\app\modules\admin\models\Filters::getFilters([
				'parent_id'=>post('id',0),
				'not_in'=>$filters
				]) as $v2){
					$html .= '<li class="list-group-item list-group-item-h list-group-item-'.$v2['id'].'">
					<label class="ip-label"><input name="f[]" '.(in_array($v2['id'], $filters) ? 'checked' : '').' class="checkbox" type="checkbox" value="'.$v2['id'].'"/> <span>'. $v2['title'].'</span></label></li>';
				}
				break;
		}
		
		$html .= '</ul>';
		//		break;
		//}
		
		
		$html .= '<div class="clear"></div><div class="row"><div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu lại</button>';
		
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Hủy</button>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		//
		$html .= '</div></div>';
		//
		echo json_encode([
				'html'=>$html,
				//'callback'=>true,
				//'callback_function'=>'reloadAutoPlayFunction(true);'
		]); exit;
		break;
	case 'quick-quick-setup-social-network':
		$field_name = post('field_name');
		$field_value = post('field_value');
		
		
		\app\modules\admin\models\Siteconfigs::updateSiteConfigs("other_setting/social/$field_name", $field_value);
		
		echo json_encode([
				'event'=>'hidemodal',
				'callback'=>true,
				'callback_function'=>'window.location=window.location'
		]);
		exit;
		break;
	case 'change_social_setting_link':
		$value = post('value'); $key = post('key');
		$v = get_social()[$value];
		$c = isset(Yii::$site['other_setting'][$key][$value]) ? Yii::$site['other_setting'][$key][$value] : '';
		echo json_encode([
				'callback'=>true,
				'callback_function'=>'var $target = jQuery(".input-change-social-setting-link");$target.attr({\'placeholder\':\''.$v['hint_link'].'\'}).val(\''.($c).'\');jQuery(\'.input-change-social-setting-link-name\').val(\''.$value.'\')'
		]);
		exit;
		break;
	case 'quick-setup-social-network':
		$html = '';$key = 'social';
		$html .= '<div class="form-group"><div class="col-sm-12">
		<div class="col-sm-3"><div class="row"><select data-key="'.$key.'" onchange="call_ajax_function(this)" data-action="change_social_setting_link" class="form-control chosen-select" data-search="hidden">';
		$i=0;
		foreach (get_social() as $k1=>$v1){
			if($i++ == 0) $value = $k1;
			$html .= '<option value="'.$k1.'">'.$v1['name'].'</option>';
		}
		$c = isset(Yii::$site['other_setting'][$key][$value]) ? Yii::$site['other_setting'][$key][$value] : '';
		$html .= '</select></div></div>
		<div class="col-sm-9 mgl-1"><div class="row">
				<input type="text" name="field_value" class="form-control required input-change-social-setting-link" required placeholder="" value="'.$c.'">
		</div></div></div>
		</div>';
		
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu lại</button>';
		
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Hủy</button>';
		$html .= '<input type="hidden" value="'.$value.'" name="field_name" class="input-change-social-setting-link-name"/>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		//
		
		//
		echo json_encode([
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>'load_number_format();'
		]); exit;
		
		break;
	case 'quick-add-more-tours-program-extend-prices':
		$f = post('f',[]);
		$id = post('id',0);
		$f['bizrule'] = json_encode(post('biz',[]));
		$f['item_id'] = post('item_id',0);
		$f['segment_id'] = post('segment_id',0);
		$f['type_id'] = post('guide_type',2);
		//
		$f['quantity'] = cprice($f['quantity']);
		$f['price1'] = cprice($f['price1']);
		//
		if($id == 0){
			Yii::$app->db->createCommand()->insert('tours_programs_segments_extend_prices', $f)->execute();
		}else{
			Yii::$app->db->createCommand()->update('tours_programs_segments_extend_prices', $f,[
					'id'=>$id
			])->execute();
		}
		//
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>'reloadAutoPlayFunction(true);'
		]); exit;
		break;
	case 'add-more-tours-program-extend-prices':
		$html = '';
		$item_id = post('item_id',0);
		$segment_id = post('segment_id',0);
		$id = post('id',0);
		$parent_id = post('parent_id',0);
		$guide_type = post('guide_type',2);
		$guide_language = post('guide_language',DEFAULT_LANG);
		$item = \app\modules\admin\models\ToursPrograms::getItem($item_id);
		$segment = \app\modules\admin\models\ProgramSegments::getItem($segment_id);
		$v = (new Query())->from('tours_programs_segments_extend_prices')->where(['id'=>$id])->one();
		$html .= '<div class="form-group"><div class="col-sm-12"><label >Tên chi phí <i class="red font-normal">(*)</i></label><input type="text" name="f[title]" class="form-control required" required placeholder="Nhập tên chi phí" value="'.(isset($v['title']) ? uh($v['title']) : '').'"></div></div>';
		$html .= '<div class="form-group"><div class="col-sm-12"><label >Diễn giải  </label><input type="text" name="biz[note]" class="form-control " placeholder="Diễn giải" value="'.(isset($v['note']) ? uh($v['note']) : '').'"></div></div>';
		$html .= '<div class="form-group"><div class="col-sm-12"><label >Số lượng <i class="red font-normal">(*)</i></label><input type="number" min="0" name="f[quantity]" class="form-control " required placeholder="Số lượng" value="'.(isset($v['quantity']) ? ($v['quantity']) : 0).'"></div></div>';
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
		
		
		
		
		//$html .= '<div class="form-group"><div class="col-sm-12"><label >Số ngày <i class="red font-normal">(*)</i></label><input type="number" min="1" max="99" name="f[number_of_day]" class="form-control number-format required" required placeholder="Nhập số ngày tour của chặng này" value="'.(isset($segment['number_of_day']) ? ($segment['number_of_day']) : '').'"></div></div>';
		
		//$html .= '<div class="form-group"><div class="col-sm-12"><label >Thứ tự sắp xếp</label><input type="number" min="1" max="99" name="f[position]" class="form-control number-format" placeholder="Thứ tự sắp xếp" value="'.(isset($segment['position']) ? ($segment['position']) : 0).'"></div></div>';
		//
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu lại</button>';
		$html .= $id > 0 ? '<button data-action="open-confirm-dialog" data-title="Xác nhận xóa chi phí !" data-class="modal-sm" data-confirm-action="quick_delete_program_segment_extend_price" onclick="return open_ajax_modal(this);" data data-id="'.$id.'" data-item_id="'.$item_id.'" type="button" class="btn btn-warning"><i class="fa fa-trash "></i> Xóa chi phí</button>' : '';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Hủy</button>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		//
		
		//
		echo json_encode([
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>'load_number_format();'
		]); exit;
		break;
	case 'quick-setup-tourprogram-guides':
		$f = post('f');
		$item_id = post('item_id');
		$segment_id = post('segment_id',0);
		$package_id = post('package_id',0);
		//
		if($segment_id == 0){ // Level 1
			\app\modules\admin\models\Siteconfigs::updateBizrule(\app\modules\admin\models\ToursPrograms::tableName(),[
					'id'=>$item_id
			],[
					'guide_language'=>$f['guide_language'],
					'guide_type'=>$f['guide_type'],
			]);
		}else{
			if((new Query())->from('tours_programs_segments_guides')->where([
					'item_id'=>$item_id,
					'segment_id'=>$segment_id,
					'package_id'=>$package_id,
			])->count(1) == 0){
				Yii::$app->db->createCommand()->insert('tours_programs_segments_guides', [
						'item_id'=>$item_id,
						'segment_id'=>$segment_id,
						'type_id'=>$f['guide_type'],
						'lang'=>$f['guide_language'],
						'package_id'=>$package_id,
				])->execute();
			}else{
				Yii::$app->db->createCommand()->update('tours_programs_segments_guides',[
						'type_id'=>$f['guide_type'],
						'lang'=>$f['guide_language'],
				],[
						'item_id'=>$item_id,
						'segment_id'=>$segment_id,
						'package_id'=>$package_id,
				])->execute();
			}
		}
		//
		
		foreach (\app\modules\admin\models\ProgramSegments::getAllChild($item_id,$segment_id) as $s2){
			if((new Query())->from('tours_programs_segments_guides')->where([
					'item_id'=>$item_id,
					'segment_id'=>$s2,
					'package_id'=>$package_id,
			])->count(1) == 0){
				Yii::$app->db->createCommand()->insert('tours_programs_segments_guides', [
						'item_id'=>$item_id,
						'segment_id'=>$s2,
						'type_id'=>$f['guide_type'],
						'lang'=>$f['guide_language'],
						'package_id'=>$package_id,
				])->execute();
			}else{
				Yii::$app->db->createCommand()->update('tours_programs_segments_guides',[
						'type_id'=>$f['guide_type'],
						'lang'=>$f['guide_language'],
				],[
						'item_id'=>$item_id,
						'segment_id'=>$s2,
						'package_id'=>$package_id,
				])->execute();
			}
		}
		
		//
		switch ($f['guide_type']){
			case 2: // Từng chặng
				// Tu dong chon cho level ben duoi
				
				break;
			case 1: // Suốt tuyến
				
				break;
		}
		///\app\modules\admin\models\ToursPrograms::setSegmentsAutoGuides(['item_id'=>$item_id]);
		loadTourProgramGuides($item_id,[
				'loadDefault'=>true,
				'updateDatabase'=>true,
		]);
		echo json_encode([
				//'html'=>$html,
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>'reloadAutoPlayFunction(true);'
		]); exit;
		break;
	case 'setup-tourprogram-guides':
		$html = '';
		$item_id = post('item_id',0);
		$segment_id = post('segment_id',0);
		$parent_id = post('parent_id',0);
		$guide_type = post('guide_type',2);
		$guide_language = post('guide_language',DEFAULT_LANG);
		$item = \app\modules\admin\models\ToursPrograms::getItem($item_id);
		$segment = \app\modules\admin\models\ProgramSegments::getItem($segment_id);
		
		
		$html .= '<div class="form-group"><div class="col-sm-12 edit-form-left">
		'.Ad_edit_show_select_field([],[
				'field'=>'guide_language',
				'label'=>'Ngôn ngữ',
				'class'=>'select2 ',
				//'field_name'=>'category_id[]',
				//'multiple'=>true,
				'attrs'=>[
						'data-search'=>'hidden'
				],
				'data'=>\app\modules\admin\models\AdLanguage::getList(['translate'=>true]),
				'data-selected'=>[$guide_language],
				'option-value-field'=>'code',
				'option-title-field'=>'title',
		]).'</div></div>';
		
		
		$html .= '<div class="form-group"><div class="col-sm-12"><label >Loại hướng dẫn <i class="red font-normal"></i></label><br>
		<label class="mgr15"><input name="f[guide_type]" type="radio" value="2" '.($guide_type == 2 ? 'checked' : '').'/> Hướng dẫn viên từng chặng</label>
		<label class="mgl15"><input name="f[guide_type]" type="radio" value="1" '.($guide_type == 1 ? 'checked' : '').'/> Hướng dẫn viên suốt tuyến</label>
		</div></div>';
		
		
		//$html .= '<div class="form-group"><div class="col-sm-12"><label >Số ngày <i class="red font-normal">(*)</i></label><input type="number" min="1" max="99" name="f[number_of_day]" class="form-control number-format required" required placeholder="Nhập số ngày tour của chặng này" value="'.(isset($segment['number_of_day']) ? ($segment['number_of_day']) : '').'"></div></div>';
		
		//$html .= '<div class="form-group"><div class="col-sm-12"><label >Thứ tự sắp xếp</label><input type="number" min="1" max="99" name="f[position]" class="form-control number-format" placeholder="Thứ tự sắp xếp" value="'.(isset($segment['position']) ? ($segment['position']) : 0).'"></div></div>';
		//
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu lại</button>';
		$html .= $segment_id > 0 ? '<button data-action="open-confirm-dialog" data-title="Xác nhận xóa chặng tour !" data-class="modal-sm" data-confirm-action="quick_delete_program_segment" onclick="return open_ajax_modal(this);" data data-id="'.$segment_id.'" data-item_id="'.$item_id.'" type="button" class="btn btn-warning"><i class="fa fa-trash "></i> Xóa chặng</button>' : '';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Hủy</button>';
		$html .= '</div><input type="hidden" name="f[position]" value="'.(isset($segment['position']) ? $segment['position'] : post('index')).'"/>
				<input type="hidden" name="f[item_id]" value="'.$item_id.'"/>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		//
		
		echo json_encode([
				'html'=>$html,
				//'callback'=>true,
				//'callback_function'=>'reloadAutoPlayFunction(true);'
		]); exit;
		break;
	case 'quick-add-more-tour-segment':
		$item_id = post('item_id',0);
		$segment_id = post('segment_id',0);
		$f = post('f',[]);
		//
		//
		if($segment_id>0){
			Yii::$app->db->createCommand()->update(\app\modules\admin\models\ProgramSegments::tableName(),$f,['id'=>$segment_id,'sid'=>__SID__])->execute();
		}else {
			$f['sid'] = __SID__;
			
			
			$segment_id = Yii::$app->zii->insert(\app\modules\admin\models\ProgramSegments::tableName(),$f);
			
			 
		}
		//
		if($segment_id>0){
			
			if((new Query())->from('tours_programs_segments_guides')->where([
					'item_id'=>$item_id,
					'segment_id'=>$segment_id
			])->count(1) == 0){
				
				
				if($f['parent_id']>0){
					 
					$p = (new Query())
					->select(['type_id','lang'])
					->from('tours_programs_segments_guides')
					->where([
							'item_id'=>$item_id,
							'segment_id'=>$f['parent_id'],
					])->one();
					 
					$type_id = $p['type_id'];
					$lang = $p['lang'];
					 
				}else{
					$p = \app\modules\admin\models\ToursPrograms::getItem($item_id);
					$type_id = isset($p['guide_type']) ? $p['guide_type'] : 2;
					$lang = isset($p['guide_language']) ? $p['guide_language'] : DEFAULT_LANG;
				}
				
				
				Yii::$app->db->createCommand()->insert('tours_programs_segments_guides', [
						'item_id'=>$item_id,
						'segment_id'=>$segment_id,
						'type_id'=>$type_id,
						'lang'=>$lang,
				])->execute();
			}else{
				
			}
			
			
			
			Yii::$app->db->createCommand()->delete(\app\modules\admin\models\ProgramSegments::tableToPlace(),['segment_id'=>$segment_id])->execute();
			$place = post('places',[]);
			if(!empty($place)){
				foreach ($place as $p){
					//$callback_function .= 'log(\''.$p.'\');';
					
					Yii::$app->db->createCommand()->insert(\app\modules\admin\models\ProgramSegments::tableToPlace(),[
							'segment_id'=>$segment_id,
							'place_id'=>$p
					])->execute();
					
				}
			}
		}
		//
		
		//
		loadTourProgramGuides($item_id,[
				'loadDefault'=>true,
				'updateDatabase'=>true,
		]);
		
		$callback_function .= 'reloadAutoPlayFunction(true);';
		echo json_encode([
				//'html'=>$html,
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>$callback_function
		]); exit;
		break;
	case 'add-more-tour-segment':
		$html = '';
		$item_id = post('item_id',0);
		$segment_id = post('segment_id',0);
		$parent_id = post('parent_id',0);
		$segment = \app\modules\admin\models\ProgramSegments::getItem($segment_id);
		$local = Yii::$app->zii->parseCountry(isset($segment['local_id']) ? $segment['local_id'] : 234);
		$html .= '<div class="form-group">
				<div class="col-sm-4"><div class="row">
				<div class="col-sm-12"><label >Quốc gia</label>
		<select name="f[local_id]" data-placeholder="Chọn quốc gia" class="form-control select-input-country ajax-chosen-select-ajax" data-role="chosen-load-country">';
		$html .= (!empty($local) ? '<option selected value="'.$local['country']['id'].'">'.uh($local['country']['title']).'</option>' : '');
		$html .= '</select></div>
				</div></div>
				
				<div class="col-sm-8"><div class="row">
				<div class="col-sm-12 group-sm34"><label >Địa danh <i class="red font-normal">(*)</i></label>
		<select data-local_id="'.(!empty($local) ? $local['country']['id'] : 0).'" name="places[]" data-placeholder="Chọn địa danh" multiple data-action="load_dia_danh" data-role="load_dia_danh" class="form-control input-sm ajax-chosen-select-ajax">';
		if(isset($segment['places']) && !empty($segment['places'])){
			foreach ($segment['places'] as $sg){
				$html .= '<option selected value="'.$sg['id'].'">'.uh($sg['title']).'</option>';
			}
		}
		$html .= '</select></div>
				</div></div>
				
				</div>';
		
		
		
		
		
		
		$html .= '<div class="form-group"><div class="col-sm-12"><label >Tên chặng <i class="red font-normal">(*)</i></label><input type="text" name="f[title]" class="form-control required" required placeholder="Nhập tên chặng tour" value="'.(isset($segment['title']) ? uh($segment['title']) : '').'"></div></div>';
		$html .= '<div class="form-group"><div class="col-sm-12"><label >Số ngày <i class="red font-normal">(*)</i></label><input type="number" min="1" max="99" name="f[number_of_day]" class="form-control number-format required" required placeholder="Nhập số ngày tour của chặng này" value="'.(isset($segment['number_of_day']) ? ($segment['number_of_day']) : '').'"></div></div>';
		$html .= '<div class="form-group"><div class="col-sm-12"><label >Là chặng con của </label>
		<select name="f[parent_id]" data-placeholder="Chọn danh mục cha" required class="form-control required input-sm chosen-select">
		<option value="0">--</option>		';
		$segments = \app\modules\admin\models\ProgramSegments::getAll($item_id,[
				'parent_id'=>0,
				'not_in'=>[$segment_id]
		]);
		foreach ($segments as $k=>$v1){
			$html .= '<option '.($v1['id'] == $parent_id ? 'selected' : '') .' value="'.$v1['id'].'">'.$v1['title'].'</option>';
		}
		$html .= '</select></div></div>';
		
		$html .= '<div class="form-group"><div class="col-sm-12"><label >Thứ tự sắp xếp</label><input type="number" min="0" max="99" name="f[position]" class="form-control number-format" placeholder="Thứ tự sắp xếp" value="'.(isset($segment['position']) ? ($segment['position']) : post('index')).'"></div></div>';
		//
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu lại</button>';
		$html .= $segment_id > 0 ? '<button data-action="open-confirm-dialog" data-title="Xác nhận xóa chặng tour !" data-class="modal-sm" data-confirm-action="quick_delete_program_segment" onclick="return open_ajax_modal(this);" data data-id="'.$segment_id.'" data-item_id="'.$item_id.'" type="button" class="btn btn-warning"><i class="fa fa-trash "></i> Xóa chặng</button>' : '';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Hủy</button>';
		$html .= '</div>
				<input type="hidden" name="f[item_id]" value="'.$item_id.'"/>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		//
		
		echo json_encode([
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>'reloadAutoPlayFunction(true);'
		]); exit;
		break;
	case 'quick-renew_shop_time_life':
		$id = post('id');
		$time_renew = post('time_renew',0);
		if($time_renew > 0){
			$text1 = Yii::$app->zii->getTextRespon([
					'code'=>'RP_SHOP_RENEW',
					'sid'=>$id,
					'show'=>false]);
			//
			$fx = Yii::$app->zii->getConfigs('CONTACTS',__LANG__,$id);
			$user = \app\modules\admin\models\Users::getAdminUser($id);
			$domain = \app\modules\admin\models\Users::getMainDomain($id);
			$shop = \app\modules\admin\models\Shops::getItem($id);
			//
			Yii::$app->db->createCommand()->update(\app\modules\admin\models\Shops::tableName(),[
					'to_date'=>date('Y-m-d',mktime(0,0,0,
							date('m',strtotime($shop['to_date'])) + $time_renew,
							date('d',strtotime($shop['to_date'])),
							date('Y',strtotime($shop['to_date'])))),
			],['id'=>$id])->execute();
			//
			$shop = \app\modules\admin\models\Shops::getItem($id);
			$regex = [
					//'{LOGO}' => isset(Yii::$site['logo']['logo']['image']) ? '<img src="'.Yii::$site['logo']['logo']['image'].'" style="max-height:100px"/>' : '',
					'{DOMAIN}' => $domain,
					'{COMPANY_NAME}'=>$fx['name'],
					'{COMPANY_ADDRESS}'=>$fx['name'],
					'{TIME_SENT}'=>date('d/m/Y H:i:s'),
					'{ADMIN_NAME}'=>$user['lname'] . ' ' . $user['fname'],
					'{ADMIN_ADDRESS}' => $user['address'] != "" ? $user['address'] : $fx['address'],
					'{ADMIN_EMAIL}'=>$user['email'],
					'{ADMIN_PHONE}'=>$user['phone'],
					'{SERVICES_LIST}'=>'<table cellspacing="0" cellpadding="0" border="0" class="table table-bordered " style="width:100%"><thead> <tr>
<th style="border:1px solid orange;background:#FF9800;text-align:center;color:white "><div style="padding:8px">Tên dịch vụ</div></th>
<th style="border:1px solid orange;background:#FF9800;text-align:center;color:white "><div style="padding:8px">Ngày hết hạn</div></th>   </tr> </thead> <tbody>
<tr>
<td style="border:1px solid orange; "><div style="padding:8px">Tài khoản: <a target="_blank" href="http://'.($domain).'">'.($domain).'</a></div></td>
<td style="border:1px solid orange; text-align:center"><div style="padding:8px">'.date('d/m/Y', strtotime($shop['to_date'])).'</div></td>
</tr> </tbody> </table>'
					
			];
			$fx['email'] = (isset($fx['email']) ? $fx['email'] : $user['email']);
			$form1 = replace_text_form($regex, uh($text1['value']));
			
			$fx1 = Yii::$app->zii->getConfigs('EMAILS_RESPON',__LANG__,$id);
			//view($fx1,true);
			$fx['sender'] = $fx['email'];
			$fx['short_name']  = isset($fx['short_name']) && $fx['short_name'] != "" ? $fx['short_name'] : isset($fx['name']) ? $fx['name'] : '';
			if(isset($fx1['RP_CONTACT'])){
				$fx['email'] = $fx1['RP_CONTACT']['email'] != "" ? $fx1['RP_CONTACT']['email'] : (isset($fx['email']) ? $fx['email'] : $user['email']);
			}
			//view($fx,true);
			
			if(Yii::$app->zii->sendEmail([
					'subject'=>replace_text_form($regex , $text1['title'])  ,
					'body'=>$form1,
					'from'=>'info@codedao.info',
					//'from'=>'noreply.thaochip@gmail.com',
					'fromName'=>$fx['short_name'],
					//'replyTo'=>'zinzin',
					//'replyToName'=>$f['guest']['full_name'],
					'to'=>$fx['email'],
					//'to'=>'zinzinx8@gmail.com',
					'toName'=>$fx['short_name'],
					'sid'=>$id
			])){
				
				
			}
		}
		echo json_encode([
				'event'=>'hide-modal',
				'callback_after'=>true,
				'callback_after_function'=>'showModal(\'Thông báo\',\'Gia hạn dịch vụ thành công.\');'
		]);exit;
		break;
	case 'renew_shop_time_life':
		$html = ''; $id = post('id',0);
		//
		$v = \app\modules\admin\models\Shops::getItem($id);
		if(!empty($v)){
			$html .= '<div class="form-group"><div class="col-sm-12"><table class="table table-bordered vmiddle"><tbody>';
			
			$html .= '<tr><td >Tài khoản</td><td class="bold">'.$v['code'].'</td></tr>';
			$html .= '<tr><td >Domain</td><td class="bold">'.$v['domain'].'</td></tr>';
			$html .= '<tr><td >Email</td><td class="bold">'.$v['email'].'</td></tr>';
			$html .= '<tr><td >Ngày hết hạn</td><td class="bold">'.date('d/m/Y',strtotime($v['to_date'])).'</td></tr>';
			$html .= '<tr><td class="bold">Gia hạn thêm</td>
			<td class="bold"><select name="time_renew" class="form-control select2" data-search="hidden">
			<option value="0"> -- </option>
			<option value="1"> 1 tháng </option>
			<option value="6"> 6 tháng </option>';
			for($i = 1; $i<11;$i++){
				$html .= '<option '.($i==1 ? 'selected' : '').' value="'.($i*12).'">'.$i.' năm </option>';
			}
			$html .= '
			</select></td></tr>';
			$html .= '</tbody></table>
<label><input required type="checkbox" class="required"/> Xác nhận gia hạn</label>
</div></div>';
		}
		//
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="fa fa-clock-o"></i> Gia hạn</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Hủy</button>';
		$html .= '</div>';
		
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		echo json_encode([
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>'jQuery(\'[data-toggle="tooltip"]\').tooltip();'
		]);exit;
		break;
	case 'genTourCode':
		echo ''; exit;
		$code = '';
		$cus_id = post('cus_id');
		$in = post('pIN');
		$out = post('pOUT');
		$start = post('start');
		$end = post('end');
		$id = post('id');
		//
		//view($this->admin());
		$customer = $this->loadModel('customers');
		$contract = $this->loadModel('contracts');
		$c = $customer->getItem($cus_id);
		$cCode = $this->admin()->getCountryCode($c['local_id']);
		$regIN = $this->admin()->getRegion($in);
		$regOUT = $this->admin()->getRegion($out);
		$dateIN = strtotime(convertTime($start));
		$dateOUT = strtotime(convertTime($end));
		
		$code .= $cCode . $c['code'].$regIN.($regOUT != $regIN ? $regOUT : '').date('d',$dateIN).date('d',$dateOUT);
		$code .= date("my",$dateOUT);
		$code = $contract->checkTourCode($code,$id);
		//
		echo $code; exit;
		break;
	case 'Tourprogram_ReloadAllPrice':
		//
		$id = $item_id = post('id',0);
		$guest = post('guest',0);
		$c = [];
		$v = \app\modules\admin\models\ToursPrograms::getItem($id);
		
		if(!empty($v)){
			//
			if($guest>-1){
				$c = ['guest'=>$guest];
			}
			
			switch (post('field')){
				case 'guest1':
					$c['guest1'] = post('value');
					break;
				case 'guest2':
					$c['guest2'] = post('value');
					break;
				case 'guest3':
					$c['guest3'] = post('value');
					break;
				case 'nationality':
					$c['nationality'] = post('value');
					break;
				case 'from_date':
					$c['from_date'] = ctime(['string'=> post('value')]);
					$time = ctime(['string'=>$c['from_date'] ,'return_type'=>1]);
					$to_date = date('Y-m-d H:i:s',mktime(0,0,0,date('m',$time),date('d',$time)+max($v['day'],$v['night']),date('Y',$time)));
					$c['to_date'] = ctime(['string'=> $to_date]);
					break;
			}
			//
			Yii::$app->db->createCommand()->update( \app\modules\admin\models\ToursPrograms::tableName(),$c,['id'=>$id,'sid'=>__SID__])->execute();
			//
			$a = loadTourProgramDetail([
					'id'=>$id,
					'loadDefault'=>true,
					'updateDatabase'=>true,
			]);
			foreach (\app\modules\admin\models\ProgramSegments::getAll($id,['parent_id'=>0]) as $segment){
				$x1 = \app\modules\admin\models\ProgramSegments::getAll($id,['parent_id'=>$segment['id']]);
				if(!empty($x1)){
					foreach ($x1 as $x2) {
						loadTourProgramDistances($id,[
								'loadDefault'=>true,
								'updateDatabase'=>true,
								'segment'=>$x2
						]);
					}
				}else{
					loadTourProgramDistances($id,[
							'loadDefault'=>true,
							'updateDatabase'=>true,
							'segment'=>$segment
					]);
				}
			}
			
			/*
			 loadTourProgramGuides($id,[
			 'loadDefault'=>true,
			 'updateDatabase'=>true,
			 ///'segment'=>$segment
			 ]);
			 */
			//\app\modules\admin\models\ToursPrograms::setSegmentsAutoGuides([
			//		'item_id'=>$id
			//]);
			loadTourProgramGuides($id,[
					'loadDefault'=>true,
					'updateDatabase'=>true,
			]);
		}
		
		//
		echo json_encode(['post'=>$_POST,'html'=>'']);
		exit;
		break;
	case 'change_date_range_from_day':
		$day = post('day',0);
		$night = post('night',0);
		$from_date = post('from_date');
		$time = ctime(['string'=>$from_date,'return_type'=>1]);
		$to_date = date('d/m/Y',mktime(0,0,0,date('m',$time),date('d',$time)+min($day,$night),date('Y',$time)));
		
		echo json_encode([
				'html'=>$to_date,
		]);
		exit;
		break;
	case 'quick-add-more-distance-to-supplier-price':
		$f = post('f',[]);
		$supplier_id = post('supplier_id');
		$new = post('new',[]);
		
		if(!empty($new)){
			foreach ($new as $n){
				if($n['title'] != ""){
					$n['sid'] = __SID__;
					$n['type_id'] = post('controller_code');
					if(isset($n['distance'])){
						$n['distance'] = cprice($n['distance']);
					}
					if(isset($n['overnight'])){
						$n['overnight'] = cprice($n['overnight']);
					}
					$f[] = Yii::$app->zii->insert('distances',$n);
				}
			}
		}
		
		if(!empty($f)){
			foreach ($f as $n){
				//distances_to_suppliers
				if((new Query())->from('distances_to_suppliers')->where(['item_id'=>$n,'supplier_id'=>$supplier_id])->count(1) == 0){
					Yii::$app->db->createCommand()->insert('distances_to_suppliers',[
							'item_id'=>$n,'supplier_id'=>$supplier_id
					])->execute();
				}
			}
		}
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>'reloadAutoPlayFunction(true);'
		]);exit;
		break;
	case 'add-more-distance-to-supplier-price':
		$supplier_id = post('supplier_id',0);
		
		$item = \app\modules\admin\models\Customers::getItem($supplier_id);
		//$places = \app\modules\admin\models\Customers::getSupplierPlace($supplier_id);
		$ePlace = $eID = $eP = [];
		foreach (\app\modules\admin\models\Customers::getSupplierPlace($supplier_id) as $p){
			$ePlace[] = $p['id'];
			$eP[] = $p['name'];
		}
		foreach (\app\modules\admin\models\Cars::get_list_distance_from_price($supplier_id) as $p){
			$eID[] = $p['id'];
		}
		//
		$type_id = post('controller_code',$item['type_id']);
		
		$distances = (new Query())->from(['a'=>'distances'])->where(['a.sid'=>__SID__,'a.is_active'=>1])
		->andWhere(['>','a.state',-1]);
		if($type_id>0) $distances->andWhere(['a.type_id'=>$type_id]);
		if(!empty($eID)){
			$distances->andWhere(['not in','a.id',$eID]);
		}
		if(!empty($ePlace)){
			$distances->andWhere(['a.id'=>(new Query())->from('distance_to_places')->where([
					'place_id'=>$ePlace
			]+($type_id >0 ? ['type_id'=>$type_id] : []))->select('distance_id')]);
		}
		$distances = $distances->orderBy(['a.title'=>SORT_ASC])->all();
		//
		
		$html = '';
		$html .= '<div class="form-group"><div class="col-sm-12">';
		///$html .= \app\modules\admin\models\Menu::getMenuPosition(\app\modules\admin\models\Menu::getItem(post('category_id',0)));
		$html .= '</div></div>';
		$html .= '<div class="form-group">';
		$html .= '<div class="col-sm-12"><p class="fl100 help-block">Các chặng xe thuộc địa danh <b class="underline red">'.implode('</b> | <b class="underline red">', $eP).'</b> có thể thêm vào báo giá:</p>';
		$html .= '<select data-placeholder="Chọn chặng xe" multiple name="f[]" id="chosen-load-distances" data-place="'.implode(',', $ePlace).'" data-type_id="'.$type_id.'" data-existed="'.implode(',', $eID).'" data-index="" data-target=".ajax-result-price-distance" role="load_distances" class="form-control ajax-chosen-select-ajax">';
		if(!empty($distances)){
			foreach ($distances as $k=>$v){
				$html .= '<option value="'.$v['id'].'">'.uh($v['title']).'</option>';
			}
		}
		$html .= '</select>';
		$html .= '</div><p class="col-sm-12 help-block hide">*** Lưu ý: Danh sách chặng sẽ lấy theo địa danh đã chọn ở tab "Thông tin chung".</p></div>';
		
		$html .= '<div class="form-group quick-addnew-form">';
		$html .= '<div class="col-sm-12">';
		$html .= '<label>Nếu chặng vận chuyển chưa tồn tại bạn có thể thêm nhanh tại đây:</label>';
		$html .= '<input name="new[0][title]" type="text" class="form-control" value="" placeholder="Nhập tên chặng">';
		$html .= '<input name="new[0][distance]" type="text" class="form-control inline-block mgt10 w50 ajax-number-format" value="" placeholder="Khoảng cách (km)">';
		$html .= '<input name="new[0][overnight]" type="text" class="form-control inline-block mgt10 w50 ajax-number-format" value="" placeholder="Lưu đêm">';
		$html .= '</div></div><hr/>';
		$html .= '<div class="form-group quick-addnew-form">';
		$html .= '<div class="col-sm-12">';
		
		$html .= '<input name="new[1][title]" type="text" class="form-control" value="" placeholder="Nhập tên chặng">';
		$html .= '<input name="new[1][distance]" type="text" class="form-control inline-block mgt10 w50 ajax-number-format" value="" placeholder="Khoảng cách (km)">';
		$html .= '<input name="new[1][overnight]" type="text" class="form-control inline-block mgt10 w50 ajax-number-format" value="" placeholder="Lưu đêm">';
		$html .= '</div></div>';
		
		
		//
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		echo json_encode([
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>'jQuery(\'[data-toggle="tooltip"]\').tooltip();'
		]);exit;
		break;
	case 'quick_change_menu_price_currency':
		$a = [
		'item_id',
		//'season_id',
		//'weekend_id',
		//'group_id',
		'supplier_id',
		'package_id',
		'quotation_id',
		'nationality_id',
		
		];
		$con = [];
		foreach ($a as $b){
			$$b = post($b,0);
			$con[$b] = $$b;
		}
		Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice(post('controller_code'),post('price_type',1)),['currency'=>post('value')],$con)->execute();
		exit;
		break;
	case 'quick_change_menus_price_default':
		$a = [
		'item_id',
		//'season_id',
		//'weekend_id',
		//'group_id',
		'supplier_id',
		'package_id',
		'quotation_id',
		'nationality_id',
		
		];
		$con = [];
		foreach ($a as $b){
			$$b = post($b,0);
			$con[$b] = $$b;
		}
		Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice(post('controller_code'),post('price_type',1)),['is_default'=>0],
				[
						'package_id'=>$package_id,
						'supplier_id'=>$supplier_id,
						'quotation_id'=>$quotation_id,
						'nationality_id'=>$nationality_id
				])->execute();
				Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice(post('controller_code'),post('price_type',1)),['is_default'=>1],$con)->execute();
				
				switch (post('controller_code')){
					case TYPE_ID_HOTEL:
					case TYPE_ID_SHIP_HOTEL:
						Yii::$app->db->createCommand()->update('rooms_to_hotel',['is_default'=>0],
						[
						//'package_id'=>$package_id,
						'parent_id'=>$supplier_id,
						//'quotation_id'=>$quotation_id,
						//'nationality_id'=>$nationality_id
						])->execute();
						Yii::$app->db->createCommand()->update('rooms_to_hotel',['is_default'=>0],
								[
										'room_id'=>$item_id,
										'parent_id'=>$supplier_id,
										//'quotation_id'=>$quotation_id,
										//'nationality_id'=>$nationality_id
								])->execute();
								
								break;
				}
				
				exit;
				break;
				
	case 'quick_change_menu_price':
		$a = [
		'item_id',
		'season_id',
		'weekend_id',
		'group_id',
		'supplier_id',
		'package_id',
		'quotation_id',
		'nationality_id',
		
		];
		$con1 = [];
		foreach ($a as $b){
			$$b = post($b,0);
			$con1[$b] = $$b;
			if($$b>0){
				$con[$b] = $$b;
			}
		}
		
		$time_id = post('time_id',-1);
		$con1['time_id'] = $time_id;
		if($time_id > -1){
			$con['time_id'] = $time_id;
		}
		//
		Yii::$app->db->createCommand()->delete('menus_to_prices',$con)->execute();
		//
		$con1['price1'] = cprice(post('value')) > 0 ? cprice(post('value')) : 0;
		$con1['currency'] = post('currency',1);
		//
		Yii::$app->db->createCommand()->insert('menus_to_prices',$con1)->execute();
		exit;
		break;
	case 'quick_change_supplier_service_price':
		$supplier_type = post('supplier_type');
		$field = post('field','price1');
		$ticket_id = post('ticket_id',0);
		$a = [
				//'item_id',
				'season_id',
				//'weekend_id',
				'group_id',
				'supplier_id',
				'package_id',
				'quotation_id',
				//'nationality_id',
				//'parent_group_id'
				
		];
		if(isset($_POST['item_id'])){
			$a[] = 'item_id';
		}
		if(isset($_POST['weekend_id'])){
			$a[] = 'weekend_id';
		}
		if(isset($_POST['nationality_id'])){
			$a[] = 'nationality_id';
		}
		if(isset($_POST['vehicle_id'])){
			$a[] = 'vehicle_id';
		}
		if(isset($_POST['parent_group_id'])){
			$a[] = 'parent_group_id';
		}
		if(isset($_POST['station_from'])){
			$a[] = 'station_from';
		}
		if(isset($_POST['station_to'])){
			$a[] = 'station_to';
		}
		
		
		$con1 = [];
		foreach ($a as $b){
			$$b = post($b,0);
			if($$b > -1){
				$con1[$b] = $$b;
			}
			if($$b>-1){
				$con[$b] = $$b;
			}
		}
		
		$time_id = post('time_id',-1);
		if(isset($_POST['time_id'])){
			$con1['time_id'] = $time_id;
			
			if($time_id > -1){
				$con['time_id'] = $time_id;
			}}
			$t = '';
			
			$t = Yii::$app->zii->getTablePrice($supplier_type,post('price_type',1));
			//
			
			//Yii::$app->db->createCommand()->delete($t,$con)->execute();
			
			//
			$con1[$field] = cprice(post('value')) > 0 ? cprice(post('value')) : 0;
			if(isset($_POST['currency'])){
				$con1['currency'] = post('currency',1);
			}
			//
			
			if((new Query())->from($t)->where($con)->count(1) == 0){
				
				Yii::$app->db->createCommand()->insert($t,$con1)->execute();
				
			}else{
				
				if($field == 'price2'){
					$con1 = ['price2'=>cprice(post('value')) > 0 ? cprice(post('value')) : 0];
				}
				foreach ($con as $k=>$v){
					unset($con1[$k]);
				}
				
				echo Yii::$app->db->createCommand()->update($t,$con1,$con)->execute();
			}
			//
			switch ($supplier_type){
				case TYPE_ID_TRAIN:
					 
					//
					if($ticket_id == 0 && (new Query())->from('trains_to_prices')->where([
							'station_from'=>$station_from,
							'station_to'=>$station_to,
							'supplier_id'=>$supplier_id,
							'package_id'=>$package_id,
							'season_id'=>$season_id,
							'item_id'=>$item_id,
							'ticket_id'=>0
					])->count(1) > 0){
						$ticket_id = Yii::$app->zii->insert('tickets',[
								'type_id'=>TYPE_ID_TRAIN,
								'sid'=>__SID__,
								'title'=>\app\modules\admin\models\Stations::getTicketTitle($station_from,$station_to)
						]);
						Yii::$app->db->createCommand()->update(
								\app\modules\admin\models\Tickets::tableName(),
								['lang_code'=>'text_station_'.$station_from.'_'.$station_to],
								['id'=>$ticket_id])->execute();
						
						Yii::$app->db->createCommand()->update('trains_to_prices', [
								'ticket_id'=>$ticket_id
						],[
								'station_from'=>$station_from,
								'station_to'=>$station_to,
								'supplier_id'=>$supplier_id,
								'package_id'=>$package_id,
								'ticket_id'=>0,
								'item_id'=>$item_id,
						])->execute();
						// 
						\app\modules\admin\models\Tickets::updateSupplier($ticket_id, $supplier_id);
						$callback = true;
						$callback_function .= '$this.attr("data-ticket_id",'.$ticket_id.');';
					}elseif($ticket_id>0){
						\app\modules\admin\models\Tickets::updateSupplier($ticket_id, $supplier_id);
					}
					break;
			}
			//			
//			$callback_function .= 'log($d);';
			break;
			
	case 'quick-quick_change_category_position':
		$pos = post('pos');
		Yii::$app->db->createCommand()->delete('{{%items_to_posiotion}}',['item_id'=>post('category_id',0)])->execute();
		
		if(!empty($pos)){
			foreach ($pos as $p=>$v){
				Yii::$app->db->createCommand()->insert('{{%items_to_posiotion}}',[
						'position_id'=>$p,
						'item_id'=>post('category_id',0),
						'type'=>0
				])->execute();
			}
		}
		$xP = implode(' | ', Menu::getPosition(post('category_id',0),0));
		$html = '<a href="#" data-category_id="'.post('category_id',0).'" data-action="quick_change_category_position" class="cate-pos-'.post('category_id',0).'" onclick="open_ajax_modal(this);return false;" data-title="Chỉnh sửa vị trí hiển thị danh mục">'.($xP != "" ? $xP : '-').'</a>';
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>'jQuery(\'.cate-pos-'.post('category_id',0).'\').replaceWith(\''.$html.'\');'
		]);exit;
		break;
	case 'quick_change_category_position':
		$html = '';
		$html .= '<div class="form-group"><div class="col-sm-12">';
		$html .= \app\modules\admin\models\Menu::getMenuPosition(\app\modules\admin\models\Menu::getItem(post('category_id',0)));
		$html .= '</div></div>';
		
		
		
		
		//
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		echo json_encode([
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>'jQuery(\'[data-toggle="tooltip"]\').tooltip();'
		]);exit;
		break;
	case 'quick-add-more-position':
		$new = post('new',[]);
		if(!empty($new) && Yii::$app->user->can([ROOT_USER])){
			foreach ($new as $v){
				if($v['name'] != "" && $v['title'] != "" && (new Query())->from('{{%positions}}')->where(['name'=>$v['name'],'type'=>$v['type']])->count(1) == 0){
					$v['sid'] = __SID__;
					Yii::$app->db->createCommand()->insert('{{%positions}}',$v)->execute();
					Yii::$app->db->createCommand()->insert('positions_to_users',[
							'position_id'=>$v['name'],
							'user_id'=>__SID__,
							'type_id'=>$v['type']
					])->execute();
				}elseif ($v['name'] != "" && $v['title'] != ""){
					Yii::$app->db->createCommand()->insert('positions_to_users',[
							'position_id'=>$v['name'],
							'user_id'=>__SID__,
							'type_id'=>$v['type'],
							'position'=>1
					])->execute();
				}
			}
		}
		echo json_encode([
				'event'=>'hide-modal'
		]);exit;
		break;
	case 'add-more-position':
		$html = '';
		
		$html .= '<div class="form-group"><table class="table table-bordered vmiddle"><thead><tr><th class="center w150p">Code</th><th class="center">Tiêu đề</th></tr></thead><tbody>';
		
		for($i=0; $i<3;$i++){
			
			$html .= '<tr><input type="hidden" name="new['.$i.'][type]" value="'.post('type',0).'"/>
    		<td class="pr"><input onblur="check_input_required(this);" type="text" class="sui-input w100 form-control input-sm input-condition-required input-destination-required" value="" name="new['.$i.'][name]" placeholder=""/></td>
    		<td>
    		<input onblur="check_input_required(this);" type="text" class="sui-input w100 form-control input-sm input-condition-required input-destination-required" value="" name="new['.$i.'][title]" placeholder="">
    		</td>';
			$html .= '</tr>';
			
		}
		$html .= '</tbody></table></div>';
		
		
		$html .= '<div class="group-sm34"><p>Danh sách đối tượng đã thêm</p>';
		$html .= '<table class="table vmiddle table-hover table-bordered">';
		$html .= '<thead><tr>
    				<th class="center">Code</th>
					<th class="center">Tiêu đề</th>
    				<th class="coption"></th>
    				</tr></thead>';
		$html .= '<tbody class="">';
		
		$l = \app\modules\admin\models\Menu::_getMenuPosition(0);
		if(!empty($l) && Yii::$app->user->can([ROOT_USER])){
			$role = [
					'type'=>'single',
					'table'=>'positions',
					//'controller'=>Yii::$app->controller->id,
					'action'=>'Ad_quick_change_item'
			];
			foreach ($l as $k=>$v){
				$role['id']=$v['id'];
				$role['action'] = 'Ad_quick_change_item';
				$html .= '<tr class="tr-item-odr-'.$v['name'].'">'.Ad_list_show_qtext_field($v,[
						'field'=>'name',
						'class'=>'aleft',
						'decimal'=>0,
						'role'=>$role
				]).'
    	 '.Ad_list_show_qtext_field($v,[
    	 		'field'=>'title',
    	 		'class'=>'aleft',
    	 		'decimal'=>0,
    	 		'role'=>$role
    	 ]).'
    	 		
    				<td class="center pr">
    	 		
    						<a data-modal-target=".mymodal1" data-trash="0" data-action="open-confirm-dialog" data-title="Xác nhận xóa package !" data-class="modal-sm" data-confirm-action="quick_delete_position_user" data-position_id="'.$v['name'].'" data-type="'.$v['type'].'" onclick="return open_ajax_modal(this);" class="btn btn-link delete_item icon" data-toggle="tooltip" data-placement="top" title="Toàn bộ dữ liệu đã nhập liên quan đến bản ghi này sẽ bị xóa.">Xóa</a>
    						</td>';
    	 $html .= '</tr>';
			}}else{
				
			}
			
			$html .= '</tbody></table>';
			$html .= '</div>';
			
			//
			$html .= '<div class="modal-footer">';
			$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
			$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
			$html .= '</div>';
			$_POST['action'] = 'quick-' . $_POST['action'];
			foreach ($_POST as $k=>$v){
				$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
			}
			///
			
			echo json_encode([
					'html'=>$html,
					'callback'=>true,
					'callback_function'=>'jQuery(\'[data-toggle="tooltip"]\').tooltip();'
			]);exit;
			
			break;
	case 'quick-add-more-quotation-price-to-supplier':
		//
		$supplier_id = post('supplier_id',0);
		$child_id = post('child_id',[]);
		$new = post('new',[]);
		if(!empty($new)){
			foreach ($new as $k=>$v){
				if(trim($v['title']) != ""){
					$v['sid'] = __SID__;
					$v['supplier_id'] = $supplier_id;
					$v['from_date'] = ctime(['string'=>$v['from_date']]);
					$v['to_date'] = ctime(['string'=>$v['to_date']]);
					$v['to_date'] = date('Y-m-d 23:59:59',strtotime($v['to_date']));
					
					if(!(strtotime($v['to_date']) > strtotime($v['from_date'])) ){
						//
						$v['to_date'] = (date('Y', strtotime($v['from_date'])) + 2) . "-12-31 23:59:59";
						//
					}
					
					$quotation_id = Yii::$app->zii->insert('supplier_quotations',$v);
					$child_id[] = $quotation_id;
					
				}
			}
		}
		if(!empty($child_id)){
			foreach ($child_id as $package_id){
				Yii::$app->db->createCommand()->insert('supplier_quotations_to_supplier',[
						'supplier_id'=>$supplier_id,
						'quotation_id'=>$package_id
				])->execute();
			}
		}
		//
		if(post('update_quotation') == 'on'){
			$controller_code = post('controller_code',post('type_id'));
			switch ($controller_code){
				case TYPE_ID_VECL: case TYPE_ID_GUIDES:
				case TYPE_ID_SHIP: case TYPE_ID_TRAIN:
					$incurred_prices = \app\modules\admin\models\Customers::getSupplierQuotations($supplier_id);
					if(!empty($incurred_prices)){
						foreach ($incurred_prices as $k=>$v){
							Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice($controller_code,post('price_type',1)),['quotation_id'=>$v['id']],[
									'supplier_id'=>$supplier_id,
									'quotation_id'=>0
							])->execute();
							break;
						}
					}
					break;
			}
		}
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
				
				'callback_function'=>'reloadAutoPlayFunction(true);',
				'p'=>$_POST
		]);exit;
		break;
	case 'add-more-quotation-price-to-supplier':
		
		$supplier_id = post('supplier_id',0);
		$html = '';
		 
		$html .= '<div class="form-group hide">';
		$html .= '<div class="group-sm34 col-sm-12"><select data-placeholder="Chọn 1 hoặc nhiều báo giá đã có" name="child_id[]" multiple data-role="chosen-load-package" class="form-control ajax-chosen-select-ajax" style="width:100%">';
		//if(!empty($l4['listItem'])){
		foreach (\app\modules\admin\models\Customers::getAvailabledQuotations($supplier_id) as $k4=>$v4){
			
			$html .= '<option value="'.$v4['id'].'">'.$v4['title'].' </option>';
			
		}
		//}
		
		
		$html .= '</select></div>';
		$html .= '</div>
				<p class="help-block italic hide">*** Bạn có thể chọn giá trị có sẵn hoặc thêm mới ở ô nhập bên dưới.</p>
				';
		$html .= '<div class="">';
		
		$html .= '<div class="group-sm34"> ';
		$html .= '<table class="table vmiddle table-hover table-bordered"><thead><tr>
    				<th center>Tiêu đề</th>
					<th class="center">Thời gian áp dụng</th>
    				<th class="center">Thời gian kết thúc</th>
    				</tr></thead>';
		$html .= '<tbody class="">';
		
		for($i=0; $i<3;$i++){
			 
			$html .= '<tr>
    				<td class="pr"><input onblur="check_input_required(this);" type="text" class="sui-input w100 form-control input-sm input-condition-required input-destination-required" value="" name="new['.$i.'][title]" placeholder="Tiêu đề"/></td>
    		<td>
    						<input data-format="d/m/Y" data-mask="39/19/9999" onblur="check_input_required(this);" type="text" class="sui-input w100 form-control input-sm datepicker2 input-condition-required input-destination-required" value="" name="new['.$i.'][from_date]" placeholder="Thời gian bắt đầu">
    						</td>
    						<td> 
    						<input data-format="d/m/Y" data-mask="39/19/9999" type="text" class="sui-input w100 form-control input-sm datepicker2 " value="" name="new['.$i.'][to_date]" placeholder="Thời gian kết thúc">
    						</td>
    						';
			$html .= '</tr>';
			
			
			
		}
		
		$html .= '</tbody></table>';
		$html .= '</div>';
		
		
		$html .= '<div class="group-sm34"><p class="f12e underline">Danh sách các báo giá đã thêm:</p>';
		$html .= '<table class="table vmiddle table-hover table-bordered">';
		$html .= '<thead><tr>
    				<th class="center">Tiêu đề</th>
					<th class="center">Thời gian áp dụng</th>
				<th class="center">Thời gian kết thúc</th>
    				<th class="coption center">Trạng thái</th><th class="coption"></th>
    				</tr></thead>';
		$html .= '<tbody class="">';
		
		$l = \app\modules\admin\models\Customers::getSupplierQuotations($supplier_id);
		
		
		if(!empty($l)){
			$role = [
					'type'=>'single',
					'table'=>'supplier_quotations',
					//'controller'=>Yii::$app->controller->id,
					'action'=>'Ad_quick_change_item'
			];
			foreach ($l as $k=>$v){
				$role['id']=$v['id'];
				$role['action'] = 'Ad_quick_change_item';
				$html .= '<tr class="tr-item-odr-'.$supplier_id.'-'.$v['id'].'">'.Ad_list_show_qtext_field($v,[
						'field'=>'title',
						'class'=>'  aleft',
						'decimal'=>0,
						'role'=>$role
				]).'
    	'.Ad_list_show_qtext_field($v,[
    			'field'=>'from_date',
    			'value'=>date("d/m/Y H:i:s",strtotime($v['from_date'])),
    			'class'=>'input-sm datetimepicker2',
    			 
    			//'readonly'=>true,
    			'role'=>$role + ['field_type'=>'date','timepicker'=>1,
    					'format'=>'d/m/Y H:i',
    					'mask'=>'39/19/9999 29:59',
    					'time'=>"1",]
    	]).'
    	'.Ad_list_show_qtext_field($v,[
    			'field'=>'to_date',
    			'value'=>date("d/m/Y H:i:s",strtotime($v['to_date'])),
    			'class'=>'input-sm datetimepicker2',
    			 
    			'role'=>$role + ['field_type'=>'date','timepicker'=>1,
    					'format'=>'d/m/Y H:i:s',
    					'mask'=>'39/19/9999 29:59:59',
    					'time'=>"1",] 
    	]). Ad_list_show_checkbox_field($v,[ 
    			'field'=>'is_active',
    			'class'=>'ajax-switch-btn switchBtn ',
    			//'decimal'=>0,
    			'role'=>$role
    	]).'
<td class="center pr">
<a data-modal-target=".mymodal1" data-trash="0" data-action="open-confirm-dialog" data-title="Xác nhận xóa dữ liệu !" data-class="modal-sm" data-confirm-action="quick_delete_quotation_supplier" data-quotation_id="'.$v['id'].'" data-supplier_id="'.$supplier_id.'" onclick="return open_ajax_modal(this);" class="btn btn-link delete_item icon" data-toggle="tooltip" data-placement="top" title="Toàn bộ dữ liệu đã nhập cho báo giá này sẽ bị xóa. Lưu ý: không thể phục hồi dữ liệu đã xóa.">Xóa</a>
</td>';
    	$html .= '</tr>';  
			}}else{
				
				
				$html .= '<tr><td colspan="5"><p><b class="red ">Bạn chưa sử dụng báo giá nào.</b></p>
						
						<p class="help-block italic red "><b class="underline">Lưu ý: </b> Khi cài đặt báo giá, các đơn giá (đã nhập trước đó) mà không thuộc 1 báo giá nào sẽ bị xóa.</p></td></tr>';
				
				
			}
			
			$c = (new Query())->from(Yii::$app->zii->getTablePrice(post('controller_code'),post('price_type',1)))->where([
					'quotation_id'=>0,
					'supplier_id'=>$supplier_id
			])->count(1);
			//	exit;
			if($c>0){
				$html .= '<tr><td colspan="5"><p><b class="red ">Opp! Chúng tôi nhận thấy rằng có <span class="underline">'.number_format($c).'</span> đơn giá đã nhập cho đơn vị này nhưng chưa nằm trong báo giá nào.</b></p>
						
					<p class="bold green">Bạn có muốn cập nhật vào báo giá [đầu tiên] trong danh sách thêm mới bên trên không ?</p>
					<label><input name="update_quotation" type="checkbox"/> Cập nhật ngay</label>
					<p class="help-block italic red "><b class="underline">Lưu ý: </b> Khi cài đặt báo giá, các đơn giá (đã nhập trước đó) mà không thuộc 1 báo giá nào sẽ bị xóa.</p></td></tr>';
			}
			$html .= '</tbody></table>';
			$html .= '</div>';
			
			$html .= '</div>';
			//
			$html .= '<div class="modal-footer">';
			$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
			$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
			$html .= '</div>';
			$_POST['action'] = 'quick-' . $_POST['action'];
			foreach ($_POST as $k=>$v){
				$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
			}
			///
			
			echo json_encode([
					'html'=>$html,
					'callback'=>true,
					'callback_function'=>'jQuery(\'[data-toggle="tooltip"]\').tooltip();reload_app(\'switch-btn\');load_datetimepicker2();'
			]);exit;
			
			break;
	case 'quick-add-more-vehicle-to-supplier':
		$supplier_id = post('supplier_id'); $type_id = post('type_id');
		$new = post('new',[]);$new1 = post('new1',[]);
		$f = post('f',[]);
		
		if(!empty($new)){
			foreach ($new as $k=>$v){
				//
				if(trim($v['title'])!=""){
					//$v['type_id'] = $type_id;
					$v['sid'] = __SID__;
					$v['type'] = 3;
					$quantity = $v['quantity']; unset($v['quantity']);
					$v['id'] = \app\modules\admin\models\VehiclesCategorys::getID();
					Yii::$app->db->createCommand()->insert('vehicles_categorys',$v)->execute();
					$v['type'] = 1;
					$v['pmax'] = $new1[$k]['pmax'];
					$v['pmin'] = $new[$k]['pmin'];
					Yii::$app->zii->insert('vehicles_categorys',$v);
					$f[$v['id']]['quantity'] = $quantity;
				}
			}
		}
		$pxxx = [];
		if(!empty($f)){
			foreach ($f as $vehicle_id => $v){
				if($v['quantity']>0){
					$quantity = $v['quantity'];
					if((new Query())->from('vehicles_to_cars')->where([
							'vehicle_id'=>$vehicle_id,
							'parent_id'=>$supplier_id
					])->count(1)==0){
						$vhc = \app\modules\admin\models\VehiclesCategorys::getItem($vehicle_id);
						Yii::$app->db->createCommand()->insert('vehicles_to_cars',[
								'vehicle_id'=>$vehicle_id,
								'parent_id'=>$supplier_id,
								'quantity'=>$quantity,
								'group_id'=>$vhc['seats']
						])->execute() ;
					}
				}
			}
		}
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
				'p'=>$pxxx,
				'callback_function'=>'reloadAutoPlayFunction(true);'
		]);exit;
		break;
	case 'quick-add-more-vehicle-to-supplier-price':
		$f = post('f',[]);
		$controller_code = post('controller_code');
		$supplier_id = post('supplier_id',0);
		$parent_group_id = \app\modules\admin\models\Cars::getParentGroupID();
		$parent_group_id > 1 ? $parent_group_id : 1;
		// Lấy ds báo giá
		$quotations = \app\modules\admin\models\Customers::getSupplierQuotations($supplier_id,[
				'order_by'=>['a.to_date'=>SORT_DESC,'a.title'=>SORT_ASC],
				'is_active'=>1
		]);
		// Lay package
		$packages = \app\modules\admin\models\PackagePrices::getPackages($supplier_id);
		// Lay nhom quoc tich
		$nationalitys = \app\modules\admin\models\NationalityGroups::get_supplier_group($supplier_id,true);
		//
		if(empty($nationalitys)){
			$nationalitys = [
					['id'=>0,'title'=>'Mặc định']
			];
		}
		
		
		if(!empty($quotations)){
			foreach ($quotations as $quotation){
				foreach ($packages as $package){
					foreach ($nationalitys as $nationality){
						foreach ($f as $id){
							
							Yii::$app->db->createCommand()->insert(Yii::$app->zii->getTablePrice($controller_code,post('price_type',1)),[
									'parent_group_id'=>$parent_group_id,
									'item_id'=>$id,
									'supplier_id'=>$supplier_id,
									'nationality_id'=>$nationality['id'],
									'quotation_id'=>$quotation['id'],
									'package_id'=>$package['id'],
									'pmax'=>9999
									
							])->execute();
						}
					}
				}
			}
		}
		
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
				
				'callback_function'=>'reloadAutoPlayFunction(true);'
		]);exit;
		break;
		break;
	case 'add-more-vehicle-to-supplier-price':
		$html = ''; $supplier_id = post('supplier_id',0);
		//$html .= '<div class="form-group">';
		//$html += '<label class="control-label col-sm-2" for="inputLoaithu">Kỳ nộp</label>';
		//$html .= '<div class="col-sm-12">';
		//$html .= '<select data-id="'.$supplier_id.'" data-supplier_id="'.$supplier_id.'" id="select-chon-xe" data-type="'.(post('type_id')).'" data-type_id="'.(post('type_id')).'" data-existed="'.(post('existed')).'" data-target="#bang_list_chon_xe" onchange="get_list_vehicles_makers(this);"  data-role="select_vehicles_makers" class="form-control chosen-select"><option value="0">-- Hãng phương tiện --</option>';
		//	foreach (\app\modules\admin\models\VehiclesMakers::getAll(['type_id'=>post('type_id')]) as $k1=>$v1){
		//		$html .= '<option value="'.$v1['id'].'">'.uh($v1['title']).'</option>';
		//	}
		
		//$html .= '</select></div></div>';
		
		$html .= '<div class="form-group"  style="margin-bottom:0">';
		
		$html .= '<div class="col-sm-12 ">';
		$html .= '<table class="table table-hover table-bordered vmiddle table-striped  " style="margin-bottom:0"> <thead><tr>
				<th rowspan="2" class="w50p">#</th> <th rowspan="2">Tên phương tiện</th>
		<th rowspan="2" style="width:150px"></th>
				
		<th class="center w100p">Chọn</th>
				
				
		</tr>
		</thead></table>';
		$html .= '<div class="div-slim-scroll" data-height="260"><table class="table table-hover table-bordered vmiddle table-striped"><tbody id="bang_list_chon_xe" class="ajax-result-after-insert ">';
		
		$listVehicles = \app\modules\admin\models\Cars::getListCars($supplier_id);
		if(!empty($listVehicles)){
			foreach ($listVehicles as $k1=>$v1){
				$html .= '<tr><td class="w50p">'.($k1+1).'</td>
						<td >'.uh($v1['title']).'</td>
						<td style="width:150px"></td>
						<td class="w100p center"><input type="checkbox" name="f[]" value="'.$v1['id'].'" class="">
								
    								</td></tr>';
			}
		}
		$html .= '</tbody></table></div><div class="show_error_out"></div>';
		//$html .= '<p class="help-block">Nếu chưa có trong danh sách phương tiện có thể thêm mới tại ô dưới đây</p>';
		//$html .= '<p class="help-block">Nếu chưa có trong danh sách phương tiện <a data-type_id="'+($this.attr('data-type_id'))+'" class="bold pointer" onclick="quick_add_more_vehicle_category(this);">click vào đây</a> để thêm mới.</p>';
		$html .= '</div> ';
		$html .= '</div>';
		
		
		///////////////////////////////////////////
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-success"><i class="fa-plus fa"></i> Thêm vào bảng giá</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		
		echo json_encode([
				'event'=>'hide-modal',
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>'reload_app(\'chosen\');loadScrollDiv();'
				
		]);exit;
		break;
		
	case 'add-more-vehicle-to-supplier':
		$html = ''; $supplier_id = post('supplier_id',0);
		$html .= '<div class="form-group">';
		//$html += '<label class="control-label col-sm-2" for="inputLoaithu">Kỳ nộp</label>';
		$html .= '<div class="col-sm-12">';
		$html .= '<select data-id="'.$supplier_id.'" data-supplier_id="'.$supplier_id.'" id="select-chon-xe" data-type="'.(post('type_id')).'" data-type_id="'.(post('type_id')).'" data-existed="'.(post('existed')).'" data-target="#bang_list_chon_xe" onchange="get_list_vehicles_makers(this);"  data-role="select_vehicles_makers" class="form-control chosen-select"><option value="0">-- Hãng phương tiện --</option>';
		foreach (\app\modules\admin\models\VehiclesMakers::getAll(['type_id'=>post('type_id')]) as $k1=>$v1){
			$html .= '<option value="'.$v1['id'].'">'.uh($v1['title']).'</option>';
		}
		
		$html .= '</select></div></div>';
		
		$html .= '<div class="form-group"  style="margin-bottom:0">';
		
		$html .= '<div class="col-sm-12 ">';
		$html .= '<table class="table table-hover table-bordered vmiddle table-striped  " style="margin-bottom:0"> <thead><tr>
				<th rowspan="2" class="w50p">#</th> <th rowspan="2">Tên phương tiện</th>
		<th rowspan="2" style="width:150px">Hãng</th>
				
		<th class="center w100p">Số lượng</th>
				
				
		</tr>
		</thead></table>';
		$html .= '<div class="div-slim-scroll" data-height="260"><table class="table table-hover table-bordered vmiddle table-striped"><tbody id="bang_list_chon_xe" class="ajax-result-after-insert ">';
		$listVehicles = \app\modules\admin\models\VehiclesCategorys::getAvailableVehicle([
				'limit'=>1000,
				'type_id'=>post('type_id'),
				'supplier_id'=>$supplier_id
		]);
		if(!empty($listVehicles)){
			foreach ($listVehicles as $k1=>$v1){
				$html .= '<tr><td class="w50p">'.($k1+1).'</td>
						<td >'.uh($v1['title']).'</td>
						<td style="width:150px">'.uh($v1['maker_name']).'</td>
						<td class="w100p"><input type="text" name="f['.$v1['id'].'][quantity]" value="" class="form-control center input-sm ajax-number-format">
								
    								</td></tr>';
			}
		}
		$html .= '</tbody></table></div><div class="show_error_out"></div>';
		$html .= '<p class="help-block">Nếu chưa có trong danh sách phương tiện có thể thêm mới tại ô dưới đây</p>';
		//$html .= '<p class="help-block">Nếu chưa có trong danh sách phương tiện <a data-type_id="'+($this.attr('data-type_id'))+'" class="bold pointer" onclick="quick_add_more_vehicle_category(this);">click vào đây</a> để thêm mới.</p>';
		$html .= '</div> ';
		$html .= '</div>';
		$html .= '<div class="form-group"><div class="col-sm-12">';
		$html .= '<table class="table table-hover table-bordered vmiddle table-striped"> <thead><tr>
				<th class="center mw100p" rowspan="2">Hãng / Loại</th>
				<th class="center " rowspan="2">Tên phương tiện</th>
				<th class="center mw100p" rowspan="2">Số ghế ngồi</th>
				<th class="center" colspan="2">Khách Inbound</th>
				<th class="center" colspan="2">Khách Nội Địa</th>
				<th class="center mw100p" rowspan="2">Số lượng</th>
				</tr>
				<tr><th class="center mw100p">Tối thiểu</th><th class="center mw100p">Tối đa</th><th class="center mw100p">Tối thiểu</th><th class="center mw100p">Tối đa</th></tr></thead><tbody>';
		
		for($i=0; $i<3; $i++){
			$html .= '<tr><td>';
			$html .= '<select data-id="'.$supplier_id.'" data-supplier_id="'.$supplier_id.'"  data-role="select_vehicles_makers" name="new['.$i.'][maker_id]" class="form-control chosen-select">';
			foreach (\app\modules\admin\models\VehiclesMakers::getAll(['type_id'=>post('type_id')]) as $k1=>$v1){
				$html .= '<option value="'.$v1['id'].'">'.uh($v1['title']).'</option>';
			}
			
			$html .= '</select></td>
				  <td class="center"><input onblur="check_input_required(this);" type="text" name="new['.$i.'][title]" value="" class="form-control input-sm input-condition-required input-destination-required inline-block"/></td>
				  <td class="center"><input onblur="check_input_required(this);" type="text" name="new['.$i.'][seats]" value="" class="form-control input-sm center ajax-number-format input-condition-required input-destination-required inline-block"/></td>';
			$html .= '<td class="center"><input onblur="check_input_required(this);" type="text" name="new1['.$i.'][pmin]" value="" class="form-control input-sm center ajax-number-format input-condition-required input-destination-required inline-block"/></td>';
			$html .= '<td class="center"><input onblur="check_input_required(this);" type="text" name="new1['.$i.'][pmax]" value="" class="form-control input-sm center ajax-number-format input-condition-required input-destination-required inline-block"/></td>';
			$html .= '<td class="center"><input onblur="check_input_required(this);" type="text" name="new['.$i.'][pmin]" value="" class="form-control input-sm center ajax-number-format input-condition-required input-destination-required inline-block"/></td>';
			$html .= '<td class="center"><input onblur="check_input_required(this);" type="text" name="new['.$i.'][pmax]" value="" class="form-control input-sm center ajax-number-format input-condition-required input-destination-required inline-block"/></td>';
			$html .= '<td class="center"><input onblur="check_input_required(this);" type="text" name="new['.$i.'][quantity]" value="" class="form-control input-sm center ajax-number-format input-condition-required input-destination-required inline-block"/></td>';
			$html .= '</tr>';
		}
		$html .= '</tbody></table>';
		$html .= '</div></div>';
		
		///////////////////////////////////////////
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		
		echo json_encode([
				'event'=>'hide-modal',
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>'reload_app(\'chosen\');loadScrollDiv();'
				
		]);exit;
		break;
	case 'loadSupplierListVehicle':
		$html = ''; $supplier_id = post('id');
		
		
		
		$lx = \app\modules\admin\models\Cars::getListCars($supplier_id);
		$html .= '
       <table class=" table table-hover table-bordered vmiddle table-striped"> <thead>
		<tr> <th rowspan="2">#</th> <th rowspan="2">Tên phương tiện</th>
		<th rowspan="2" style="min-width:150px">Hãng</th>
				
		<th class="center w100p">Số lượng</th>
				
		<th class="center">Kích hoạt</th>
		<th class="center" title="">Mặc định (tính giá)</th>
		<th class="center"></th></tr>
		</thead> <tbody> ';
		$itype = 'text' ;
		$existed_id =array();
		if(!empty($lx)){
			$l2 = \app\modules\admin\models\Cars::get_vehicles_makers();
			
			foreach ($lx as $k1=>$v1){
				$existed_id[] = $v1['id'];
				$v1['is_active'] = isset($v1['is_active']) ? $v1['is_active'] : 0;
				
				$html .= '<tr> <th scope="row">'.($k1+1).'</th>
		 <td>'.$v1['title'].'</td> <td>'.$v1['maker_title'].'</td>
		 		
		<td class="center"><input data-field="quantity" data-supplier_id="'.$supplier_id.'" data-vehicle_id="'.$v1['id'].'" onblur="quick_change_supplier_list_vehicle(this);" type="'.$itype.'" name="c['.$k1.'][quantity]" value="'.(isset($v1['quantity']) ? $v1['quantity'] : 0).'" class="form-control input-sm center numberFormat mw100p inline-block"/></td>
				
		<td class="center">'.getCheckBox(array(
				'name'=>'c['.$k1.'][is_active]',
				'value'=>$v1['is_active'],
				'type'=>'singer',
				'class'=>'switchBtn ajax-switch-btn',
				'attrs'=>[
						'data-field'=>"is_active", 'data-supplier_id'=>$supplier_id,
						'data-vehicle_id'=>$v1['id'],
						'onchange'=>"setCheckboxBool(this);quick_change_supplier_list_vehicle(this);",
				]
				
		)).'
       			</td>
		<td class="center">
		<input data-group_id="'.$v1['seats'].'" data-field="is_default" data-supplier_id="'.$supplier_id.'" data-vehicle_id="'.$v1['id'].'" onchange="quick_change_supplier_list_vehicle(this);"  type="radio" value="'.$v1['id'].'" '.($v1['is_default'] == 1 ? 'checked' : '').' name="ckc_default['.$v1['seats'].']"/>
				</td>
 		<td class="center"><input type="hidden" name="c['.$k1.'][id]" value="'.$v1['id'].'"/><input type="hidden" name="existed[]" value="'.$v1['id'].'"/><i data-class="modal-sm" data-supplier_id="'.$supplier_id.'" data-vehicle_id="'.$v1['id'].'" title="Xóa" data-title="Xác nhận xóa ?" data-action="open-confirm-dialog" data-confirm-action="delete-supplier-vehicle" class="glyphicon glyphicon-trash pointer" data-name="delete_car_id" onclick="open_ajax_modal(this);"></i></td>
        </tr> ';
			}}
			$html .= '<tr class="ajax-html-result-before-list-vehicles"><td colspan="7" class=" aright "><button data-supplier_id="'.$supplier_id.'" data-action="add-more-vehicle-to-supplier" data-class="w80" data-type_id="'.post('code').'" data-existed="" data-quantity="1" data-count="'.count($lx).'" data-title="Thêm phương tiện" onclick="open_ajax_modal(this);" data-name="c" title="Thêm phương tiện" type="button" class="btn btn-success btn-sm btn-add-more"><i class="glyphicon glyphicon-plus"></i> Thêm phương tiện</button></td></tr>';
			$html .= '</tbody>
					
        </table>
					
        ';
			echo json_encode([
					'html'=>$html,
					'callback'=>true,
					'target'=>post('target'),
					'callback_function'=>'reload_app(\'switch-btn\');',
			]);exit;
			break;
	case 'loadSupplierHotelPrice1':
		
		
		echo json_encode([
		'html'=>getSupplierPricesList(post('supplier_id',0)),
		//'callback'=>true,
		//'callback_function'=>'console.log(data)',
		]+$_POST);exit;
		
		
		break;
	case 'loadSupplierVehiclePrices':
		
		
		echo json_encode([
		'html'=>getSupplierVehiclePrices(post('supplier_id',0)),
		//'callback'=>true,
		//'callback_function'=>'console.log(data)',
		]+$_POST);exit;
		
		
		break;
		
	case 'loadSupplierVehiclePrices2':
		
		
		echo json_encode([
		'html'=>getSupplierVehiclePrices2(post('supplier_id',0)),
		//'callback'=>true,
		//'callback_function'=>'console.log(data)',
		]+$_POST);exit;
		
		
		break;
		
	case 'loadSupplierHotelPrice':
		$html = ''; $supplier_id = post('id',0);
		// Lay package
		$packages = \app\modules\admin\models\PackagePrices::getPackages($supplier_id);
		// Lay nhom quoc tich
		$nationalitys = \app\modules\admin\models\NationalityGroups::get_supplier_group($supplier_id);
		// Lay mua co tinh gia truc tiep
		$incurred_prices_list = \app\modules\admin\models\Customers::getCustomerSeasons($supplier_id,[
				'price_type'=>[0]
		]);
		// Lay danh sach cuoi tuan ngay thuong tinh gia truc tiep
		$incurred_prices_weekend_list = \app\modules\admin\models\Customers::getCustomerWeekend([
				'price_type'=>[0],
				'supplier_id'=>$supplier_id
		]);
		$l3 = \app\modules\admin\models\Hotels::getListRooms($supplier_id);
		// Lay nhom phong
		$room_groups = \app\modules\admin\models\Seasons::get_rooms_groups($supplier_id);
		//$html .= json_encode($nationalitys);
		$setting_room = false;
		if(!empty($packages)){
			foreach ($packages as $package){
				if(!empty($nationalitys)){
					foreach ($nationalitys as $kb=>$vb){
						//
						$html .= '<div class="col-sm-12 "><div class="row"><p class="grid-sui-pheader bold aleft"><i style="font-weight: normal;">Bảng giá ';
						if($package['id']>0){
							$html .= '<b class="italic green underline">' .$package['title'] .'</b> ';
						}
						$html .= ' - áp dụng cho <b class="italic underline">' .$vb['title'] .'</b> ';
						$html .= '</i></p></div></div>';
						//////////////////////////////////
						$html .= '<div class="fl100 pr auto_height_price_list" >
       	<table class="table table-prices table-hover table-bordered vmiddle table-striped"> <thead>
      	<tr><th rowspan="3" style="min-width:200px">Tiêu đề</th>
		<th rowspan="3" class="center cposition" style="min-width:110px">Giá niêm yết</th>';
						
						
						
						if(!empty($incurred_prices_list)){
							foreach ($incurred_prices_list as $k=> $in){
								$price_type = isset($in['supplier']['price_type']) ? $in['supplier']['price_type'] : 0;
								if(in_array($price_type, [0]) && !in_array($in['type_id'],[3,4])){
									$html .= '<th colspan="'.(count($room_groups) * (!empty($incurred_prices_weekend_list) ? count($incurred_prices_weekend_list) : 1)).'" class="center ">' . $in['title'] . '</th>';
								}
							}
						}
						$html .= '<th rowspan="3" class="center w80p">Tiền tệ</th></tr>';
						$html .= '<tr>';
						if(!empty($incurred_prices_list)){
							foreach ($incurred_prices_list as $k=> $in){
								$price_type = isset($in['supplier']['price_type']) ? $in['supplier']['price_type'] : 0;
								if(in_array($price_type, [0]) && !in_array($in['type_id'],[3,4]) && !empty($room_groups)){
									foreach ($room_groups as $room){
										if($room['id'] == 0) $setting_room = true;
										$html .= '<th colspan="'.count($incurred_prices_weekend_list).'" class="center"><a data-class="w70" data-parent_id="'.($supplier_id).'" data-supplier_id="'.($supplier_id).'" data-id="'.$room['id'].'" data-action="add-more-room-group" data-title="Thiết lập nhóm phòng" onclick="open_ajax_modal(this);" class="pointer hover_underline">'.(($room['id'] == 0 ? '<span class="red">' : ''). $room['title'].($room['note'] != "" ? '<p><i class="f11p font-normal">('.$room['note'].')</i></p>' : '').($room['id'] == 0 ? '</span>' : '')).'</a></th>';
									}}
							}}
							$html .= '</tr>';
							
							if(!empty($incurred_prices_list)){
								foreach ($incurred_prices_list as $k=> $in){
									$price_type = isset($in['supplier']['price_type']) ? $in['supplier']['price_type'] : 0;
									if(in_array($price_type, [0]) && !in_array($in['type_id'],[3,4]) && !empty($room_groups)){
										foreach ($room_groups as $room){
											if(!empty($incurred_prices_weekend_list)){
												$class = count($incurred_prices_weekend_list) == 1 ? 'hide' : '';
												foreach ($incurred_prices_weekend_list as $w){
													//$price_type = isset($w['supplier']['price_type']) ? $w['supplier']['price_type'] : 0;
													//if(in_array($price_type, [0]))
													$html .= '<th colspan="1" class="center '.$class.'" style="min-width:110px"><p data-class="w70" class="pointer" title="Xem ở mục \'Cài đặt cuối tuần\'">'.$w['title'].'</p></th>';
												}
											}
											
										}}
								}}
								$html .= '</tr>';
								
								$html .= '</thead> <tbody class="rstgs_'.cbool($setting_room).'"> ';
								$itype = 'text' ;
								//  view($setting_room);
								
								if(!empty($l3) && !$setting_room ){
									foreach ($l3 as $k1=>$v1){
										//$v1['is_active'] = isset($v1['is_active']) ? $v1['is_active'] : 0;
										
										$p = \app\modules\admin\models\Hotels::get_price($v1['id'],$supplier_id,$vb['id'],$package['id']);
										
										$html .= '<tr>
												
		<td>'.$v1['title'].'</td>
				
		<td class="center"><input type="'.$itype.'" name="prices['.$package['id'].']['.$vb['id'].']['.$v1['id'].'][price2]" value="'.(isset($p['price2']) ? $p['price2'] : '').'" class="form-control input-sm aright numberFormat w100 inline-block input-currency-price-'.$package['id'] .'-'.$v1['id'].'" data-decimal="'.Yii::$app->zii->showCurrency(isset($p['currency']) ? $p['currency'] : 1,3).'"/></td>';
										if(!empty($incurred_prices_list)){
											foreach ($incurred_prices_list as $k=> $in){
												$price_type = isset($in['supplier']['price_type']) ? $in['supplier']['price_type'] : 0;
												if(in_array($price_type, [0])  && !in_array($in['type_id'],[3,4]) && !empty($room_groups)){
													foreach ($room_groups as $room){
														if(!empty($incurred_prices_weekend_list)){
															foreach ($incurred_prices_weekend_list as $w){
																$price_type = isset($w['supplier']['price_type']) ? $w['supplier']['price_type'] : 0;
																if(in_array($price_type, [0]))
																	$html .= '<td class="center"><input type="text" name="prices['.$package['id'].']['.$vb['id'].']['.$v1['id'].'][list_child]['.$in['id'].']['.$room['id'].']['.$w['id'].'][price1]" value="'.(isset($p[$in['id']][$room['id']][$v1['id']][$w['id']]['price1']) ? $p[$in['id']][$room['id']][$v1['id']][$w['id']]['price1'] : '').'" class="form-control input-sm aright numberFormat w100 inline-block input-currency-price-'.$package['id'] .'-'.$v1['id'].'" data-decimal="'.Yii::$app->zii->showCurrency(isset($p['currency']) ? $p['currency'] : 1,3).'"/></td>';
															}
														}
													}}
											}
										}
										$html .= '<td class="center ">';
										
										
										$html .= '<select data-target-input=".input-currency-price-'.$package['id'] .'-'.$v1['id'].'" data-decimal="'.Yii::$app->zii->showCurrency(isset($p['currency']) ? $p['currency'] : 1,3).'" onchange="get_decimal_number(this);" class="ajax-select2-no-search sl-cost-price-currency form-control select2-hide-search input-sm" data-search="hidden" name="prices['.$package['id'].']['.$vb['id'].']['.$v1['id'].'][currency]">';
										//if(isset($v['currency']['list']) && !empty($v['currency']['list'])){
										foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v2){
											$html .= '<option value="'.$v2['id'].'" '.(isset($p['currency']) && $p['currency'] == $v2['id'] ? 'selected' : '').'>'.$v2['code'].'</option>';
										}
										//}
										
										$html .= '</select>';
										
										$html .= '<input type="hidden" name="prices['.$package['id'].']['.$vb['id'].']['.$v1['id'].'][item_id]" value="'.$v1['id'].'"/></td>
												
        </tr> ';
									}}elseif ($setting_room){
										$html .= '<tr><td colspan="100%"><p class="help-block red underline">Bạn cần cài đặt nhóm phòng (FIT & GIT) trước khi nhập giá</p></td></tr>';
									}
									//
									$html .= '</tbody></table></div>';
									
									//////////////////////////////////
					}
				}
			}}
			$html .= '<div class="col-sm-12 aright" style="margin-top: 15px; margin-bottom: 15px; ">';
			$html .= '<button data-class="w80" data-action="add-more-package-price-to-supplier" data-title="Thêm package" data-existed="" type="button" data-id="'.($supplier_id).'" data-target=".ajax-result-nationality-group" onclick="open_ajax_modal(this);" class="btn btn-warning btn-add-more2" type="button"><i class="glyphicon glyphicon-plus"></i> Quản lý báo giá</button>';
			$html .= '<button data-class="w60" data-action="add-more-package-price-to-supplier" data-title="Thêm package" data-existed="" type="button" data-id="'.($supplier_id).'" data-target=".ajax-result-nationality-group" onclick="open_ajax_modal(this);" class="btn btn-warning btn-add-more2" type="button"><i class="glyphicon glyphicon-plus"></i> Thêm package</button>';
			$html .= '<button data-class="w60" data-action="add-more-nationality-group-to-supplier" data-title="Thêm nhóm quốc tịch" data-existed="" type="button" data-id="'.($supplier_id).'" data-target=".ajax-result-nationality-group" onclick="open_ajax_modal(this);" class="btn btn-warning btn-add-more" type="button"><i class="glyphicon glyphicon-plus"></i> Thêm nhóm quốc tịch</button>
		</div> ';
			echo json_encode([
					'html'=>$html,
					'target'=>post('target')
			]);exit;
			break;
	case 'open-confirm-dialog':
		$html = '';
		
		$html .= '<div class=""><p clas="help-block">'.post('confirm-text','Bạn có chắc chắn xóa bản ghi này ?').'</p><p>&nbsp;</p></div>';
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-ok"></i> Xác nhận</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		 
		break;
	case 'quick-open-confirm-dialog':
		//$callback = false; $callback_function = ''; $event = 'hide-modal'; $modal = '.mymodal,.mymodal1';
		include_once __DIR__ . '/confirm-modal.php';
		/*
		echo json_encode([
				//'html'=>$_POST,
				'callback'=>$callback,
				'callback_function'=>$callback_function,
				'event'=>$event,
				'modal_target'=>$modal,
		]); exit;
		
		*/
		
		break;
	case 'change_season_price_type':
		$con = [
		'parent_id'=>post('season_id',0),
		'supplier_id'=>post('supplier_id',0),
		];
		//
		if(post('value') ==2){
			Yii::$app->db->createCommand()->update('seasons_categorys_to_suppliers',[
					'parent_id'=>0
			],$con)->execute();
		}
		exit;
		break;
	case 'quick_change_supplier_season':
		$con = [
		'season_id'=>post('season_id',0),
		'supplier_id'=>post('supplier_id',0),
		//'type_id'=>post('type_id',0),
		];
		if(post('parent_id')>0){
			$con['parent_id'] = post('parent_id');
		}
		$new_value = post('new_value');
		switch (post('type')){
			case 'number':
				$new_value = cprice($new_value);
				break;
		}
		Yii::$app->db->createCommand()->update('seasons_categorys_to_suppliers',[
				post('field')=>$new_value
		],$con)->execute();
		exit;
		break;
		
	case 'quick_change_supplier_list_vehicle':
		$con = [
		'vehicle_id'=>post('vehicle_id',0),
		'parent_id'=>post('supplier_id',0),
		//'type_id'=>post('type_id',0),
		];
		$group_id = post('group_id',0);
		if($group_id>0){
			$con['group_id'] = $group_id;
		}
		if(post('parent_id')>0){
			$con['supplier_id'] = post('supplier_id');
		}
		$new_value = post('new_value');
		switch (post('type')){
			case 'number':
				$new_value = cprice($new_value);
				break;
		}
		if(in_array(post('field'), ['is_default'])){
			Yii::$app->db->createCommand()->update('vehicles_to_cars',[
					post('field')=>0
			],['parent_id'=>post('supplier_id',0),'group_id'=>$group_id])->execute();
			$new_value = 1;
		}
		if(in_array(post('field'), ['is_active'])){
			
			$new_value = cbool($new_value);
		}
		
		
		Yii::$app->db->createCommand()->update('vehicles_to_cars',[
				post('field')=>$new_value
		],$con)->execute();
		exit;
		break;
		
	case 'removeSupplierSeason':
		Yii::$app->db->createCommand()->delete('seasons_to_suppliers',[
		'season_id'=>post('season_id'),
		'supplier_id'=>post('supplier_id'),
		'parent_id'=>post('parent_id'),
		'type_id'=>post('type_id')
		])->execute();
		break;
	case 'change_location_append':
		$html = '';
		switch (post('value')){
			case 1:
				foreach (\app\modules\admin\models\NationalityGroups::get_supplier_group(post('supplier_id',0)) as $k=>$v){
					$c = (new Query())->from('seasons_to_private_suppliers')->where([
							'season_category_id'=>post('season_id',0),
							'object_id'=>post('value',0),
							'supplier_id'=>post('supplier_id',0),
							'group_id'=>$v['id'],
					])->count(1);
					$html .= '<option '.($c > 0 ? 'selected' : '').' value="'.$v['id'].'">'.uh($v['title']).'</option>';
				}
				break;
			case 2:
				foreach (\app\modules\admin\models\Local::getAllCountry() as $k=>$v){
					$c = (new Query())->from('seasons_to_private_suppliers')->where([
							'season_category_id'=>post('season_id',0),
							'object_id'=>post('value',0),
							'supplier_id'=>post('supplier_id',0),
							'group_id'=>$v['id'],
					])->count(1);
					$html .= '<option '.($c > 0 ? 'selected' : '').' value="'.$v['id'].'">'.uh($v['name']).'</option>';
				}
				break;
		}
		echo json_encode([
				'html'=>$html,
		]);exit;
		break;
	case 'change_seasons_private_suppliers':
		
		Yii::$app->db->createCommand()->delete('seasons_to_private_suppliers',[
		'season_category_id'=>post('season_id',0),
		'object_id'=>post('object_id',0),
		'supplier_id'=>post('supplier_id',0),
		
		])->execute();
		if(!empty(post('value'))){
			foreach (post('value') as $v){
				Yii::$app->db->createCommand()->insert('seasons_to_private_suppliers',[
						'season_category_id'=>post('season_id',0),
						'object_id'=>post('object_id',0),
						'supplier_id'=>post('supplier_id',0),
						'group_id'=>$v,
				])->execute();
			}
		}
		echo json_encode([
				'value'=>$_POST,
				'callback'=>true,
				'callback_function'=>''
		]);
		
		exit;
		break;
	case 'set_default_supplier_room':
		Yii::$app->db->createCommand()->update('rooms_to_hotel',[
		'is_default'=>0
		],['parent_id'=>post('supplier_id')])->execute();
		Yii::$app->db->createCommand()->update('rooms_to_hotel',[
				'is_default'=>1
		],['room_id'=>post('room_id'),'parent_id'=>post('supplier_id')])->execute();
		exit;
		break;
	case 'loadSupplierListRooms':
		$html = '';
		$id = post('id',0);
		$controller = post('controller');
		$model = load_model($controller);
		$code = post('code');
		$tab = post('tab',0);
		$v = $model->getItem($id);
		$l3 = \app\modules\admin\models\Hotels::getListRooms(isset($v['id']) ? $v['id'] : 0);
		//
		
		$html .= '<table class="table table-hover table-bordered vmiddle table-striped"> <thead>
				
				
        <tr> <th class="w50p">#</th><th rowspan="2">Tiêu đề</th>
		<th class="center ">Mô tả</th>
		<th class="center cposition">Số chỗ</th>
				
		<th class="center cposition">Số lượng</th>
	 <th class="center cposition">Mặc định</th>
		<th class="center cposition"></th></tr>
				
		</thead> <tbody class=""> ';
		$itype = 'text' ;
		$existed = array();
		if(!empty($l3)){
			$led = \app\modules\admin\models\AdminMenu::get_menu_link('rooms_categorys',TYPE_CODE_ROOM_HOTEL);
			
			foreach ($l3 as $k1=>$v1){
				$v1['is_active'] = isset($v1['is_active']) ? $v1['is_active'] : 0;
				$existed[] = $v1['id'];
				$html .= '<tr> <th scope="row">'.($k1+1).'</th>
						
		<td><a href="'.$led.'" target="_blank">'.$v1['title'].'</a></td>
		<td>'.$v1['note'].'</td>
		<td class="center">'.$v1['seats'].'</td>
 		<td class="center"><input type="'.$itype.'" name="h['.$v1['id'].'][quantity]" value="'.(isset($v1['quantity']) ? $v1['quantity'] : 0).'" class="form-control input-sm center numberFormat mw100p inline-block"/><input type="hidden" name="h['.$v1['id'].'][id]" value="'.($v1['id']).'"/></td>
 		<td class="center"><input data-role="radio-option-ckc102" onchange="setRadioBool(this);set_default_supplier_room(this)" data-supplier_id="'.$id.'" data-room_id="'.$v1['id'].'" type="radio" '.(isset($v1['is_default']) && $v1['is_default'] == 1 ? 'checked' : '').' class="radio-option-ckc102" name="h['.$v1['id'].'][is_default]" /></td>
 		<td class="center"><input type="hidden" name="existed[]" value="'.$v1['id'].'"/>
 				
 				<i data-supplier_id="'.$id.'"
					data-item_id="'.$v1['id'].'"
					data-confirm-text="<span class=red>Lưu ý: Bản ghi <b class=underline>'.$v1['title'].'</b> sẽ bị xóa khỏi toàn bộ các báo giá.</span>"
					class="pointer glyphicon glyphicon-trash btn-delete-item" data-id="'.$v1['id'].'" data-name="remove_menu" data-confirm-action="quick_change_menu_price_remove" data-action="open-confirm-dialog" data-class="modal-sm" data-title="Xác nhận xóa." onclick="open_ajax_modal(this);"></i>
							
							</td>
        </tr> ';
			}}
			$html .= '<tr class="ajax-result-quick-add-more-before"><td colspan="6"></td><td class="center"><button data-class="" data-title="Thêm phòng khách sạn '.(isset($v['title']) ? ' <b class=red>'.$v['title'].'</b>' : '' ).' " data-action="add-more-room-to-hotel" data-existed="'.implode(',', $existed).'" data-count="'.count($l3).'" type="button" data-type_id="'.$code.'" data-id="'.(isset($v['id']) ? $v['id'] : 0).'" data-target=".ajax-result-more-room-hotel" title="Thêm phòng khách sạn" onclick="open_ajax_modal(this);" class="btn btn-default btn-add-more"> <i class="glyphicon glyphicon-plus"></i></button></td></tr>';
			$html .= '</tbody></table>';
			
			
			echo json_encode([
					'html'=>$html,
					//'callback'=>true,
					//'callback_function'=>'jQuery(\'.input-change-location-append\').change();reload_app(\'chosen\');'
			]+$_POST);exit;
			break;
	case 'loadSupplierSeasons':
		$html = '';
		$id = post('id',0);
		$supplier_id = post('supplier_id',post('id',0));
		$controller = post('controller');
		$model = load_model($controller);
		$code = post('code');
		$tab = post('tab',0);
		/*/
		 $incurred_prices = $model->get_incurred_category($code,[2,3,4],[
		 'supplier_id'=>$id,
		 'type_id'=>20
		 ]);
		 /*/
		$incurred_prices = \app\modules\admin\models\Customers::getCustomerSeasons($id);
		$isWeekend = 0;
		$existed = array();
		$html .= '<ul class="nav form-edit-tab-level2 nav-tabs" role="tablist">';
		if(!empty($incurred_prices)){
			$i3 = 0;
			
			
			foreach ($incurred_prices as $k3=> $v3){
				if(in_array($v3['type_id'], [3,4])){
					$isWeekend = 1;
				}
				$html .= '<li data-type_id="'.$v3['type_id'].'" role="presentation" class="pr list-with-remove-btn'.($i3++ == $tab ? ' active' : '').'"><a href="#tab-price-distance-'.$k3.'" role="tab" data-toggle="tab"><b>'.$v3['title'].'</b></a>'.(in_array($v3['type_id'], [0]) ? '' : '<i class="fa fa-remove small_remove_item pointer" data-confirm-action="quick-remove-supplier-seasons" data-supplier_id="'.$id.'" data-season_id="'.$v3['id'].'" data-action="open-confirm-dialog" data-class="modal-sm" data-title="Xác nhận xóa ?" onclick="open_ajax_modal(this)" title="Xóa"></i>').'</li>';
			}
			
			
			
		}
		$html .= '<li role="presentation" class="pr list-with-remove-btn"><a data-weekend="'.$isWeekend.'" data-class="w60" data-type_id="'.$code.'" data-action="add-more-season-category-to-supplier" data-title="Thêm mùa dịch vụ" title="Thêm mùa dịch vụ" data-id="'.($id).'" data-supplier_id="'.($supplier_id).'" data-target=".ajax-result-nationality-group" onclick="open_ajax_modal(this);" class="btn btn-link"><i class="glyphicon glyphicon-plus"></i></a></li>';
		
		$html .= '</ul>';
		$i3 = 0;
		$html .= '<div class="tab-content">';
		foreach ($incurred_prices as $k3=>$v3){
			
			
			$price_type = $v3['price_type'];
			//view($price_type);
			$html .= '<div role="tabpanel" class="tab-panel tab2-panel '.($i3++ == $tab ? 'active' : '').'" id="tab-price-distance-'.$k3.'">';
			$html .= '<div class="mg5">';
			
			$html .= '<table class="table table-bordered vmiddle" style="margin-top:10px"><thead><tr class="hide"><th class="" style="width:200px"></th><th></th><th></th></tr><thead><tbody>
					<tr>
					<td class="bold" style="width:200px">Phương thức tính giá</td>
					<td colspan="2">
					<label style="margin-right:15px">
			<div><select data-tab-target=".input-ajax-auto-load-seasons-detail" data-tab="'.$k3.'" data-supplier_id="'.$id.'" data-season_id="'.$v3['id'].'" data-type_id="20" data-field="price_type" onchange="quick_change_supplier_season(this);change_season_price_type(this);" name="seasons['.$v3['id'].'][price_type]" class="form-control input-sm select2" data-search="hidden"> ';
			foreach (\app\modules\admin\models\Seasons::get_incurred_charge_type($code) as $cx=>$vx){
				$html .= '<option '.($price_type == $vx['id'] ? 'selected' : '').' value="'.$vx['id'].'">'.$vx['title'].'</option>';
			}
			$html .= '</select></div>
			</label>
					
					<span class="italic">(*) Lưu ý: Khi chọn phương thức là phụ thu, toàn bộ các ràng buộc của mùa này với các mùa khác sẽ bị loại bỏ.</span>
					</td>
					</tr>
					
					<tr class="input-incurred-season-price" style="'.($price_type == 0 ? 'display:none' : '').'">
					<td class="bold">Giá trị</td>
					<td colspan="2">
					<label class="input-incurred-season-price" style="margin-right:15px;'.($price_type == 0 ? 'display:none' : '').'" ><input data-supplier_id="'.$id.'" data-season_id="'.$v3['id'].'" data-type_id="20" data-field="price_incurred" data-type="number" onblur="quick_change_supplier_season(this);" type="text" data-decimal="2" name="seasons['.$v3['id'].'][price_incurred]" class="h28 sui-input aright bold red input-incurred-season-price number-format" data-old="'.($price_type == 0 ? '' : (isset($v3['price_incurred']) ? $v3['price_incurred'] : '')).'" value="'.($price_type == 0 ? '' : (isset($v3['price_incurred']) ? $v3['price_incurred'] : '')).'" placeholder="Giá phát sinh" style="'.($price_type == 0 ? 'display:none' : '').'"/></label>
					<label class="input-incurred-season-parent-id w150p" style="margin-right:15px;'.($price_type == 1 ? 'display:inline-block' : 'display:none').'" ><select data-supplier_id="'.$id.'" data-season_id="'.$v3['id'].'" data-type_id="20" data-field="parent_id" onchange="quick_change_supplier_season(this);" data-placeholder="Tính giá theo" style="" class="form-control sui-input input-sm select2" data-search="hidden" name="seasons['.$v3['id'].'][parent_id]"><option value="0">--</option>';
			foreach ($incurred_prices as $k5=>$v5){
				if($v3['id'] != $v5['id']){
					if($price_type == 1){
						if(!($v5['price_type'] == 2)){
							$html .= '<option data-price_type="'.$v5['price_type'].'" '.(isset($v3['parent_id']) && $v3['parent_id'] == $v5['id'] ? 'selected' : '').' value="'.$v5['id'].'">Giá '.$v5['title'].'</option>';
						}
					}else{
						$html .= '<option data-price_type="'.$v3['price_type'].'" '.(isset($v3['parent_id']) && $v3['parent_id'] == $v5['id'] ? 'selected' : '').' value="'.$v5['id'].'">Giá '.$v5['title'].'</option>';
					}
				}
			}
			
			$html .= '</select></label>
					
					
					<label class="input-incurred-season-currency" style="margin-right:15px; '.($price_type == 2 ? 'display:inline-block' : 'display:none').'" >';
			//if(isset(\ZAP\Zii::$site['other_setting']['currency']['list'])){
			$html .= '<select data-decimal="0" data-supplier_id="'.$id.'" data-season_id="'.$v3['id'].'" data-type_id="20" data-field="currency" onchange="quick_change_supplier_season(this);get_decimal_number(this);" class="sl-cost-price-currency form-control input-sm select2" data-search="hidden" name="seasons['.$v3['id'].'][currency]">';
			//if(isset($v['currency']['list']) && !empty($v['currency']['list'])){
			foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v2){
				$html .= '<option value="'.$v2['id'].'" '.(isset($v3['currency']) && $v3['currency'] == $v2['id'] ? 'selected' : '').'>'.$v2['code'].'</option>';
			}
			//}
			
			$html .= '</select></label>';
			
			$html .= '<label class="input-incurred-season-currency" style="margin-right:15px; '.($price_type == 2 ? 'display:inline-block' : 'display:none').'" >/</label>
				<label class="input-incurred-season-currency" style="min-width:160px; margin-right:15px; '.($price_type == 2 ? 'display:inline-block' : 'display:none').'" >';
			//if(isset(\ZAP\Zii::$site['other_setting']['currency']['list'])){
			$html .= '<select data-supplier_id="'.$id.'" data-season_id="'.$v3['id'].'" data-type_id="20" data-field="unit_price" onchange="quick_change_supplier_season(this);" data-search="hidden" class="sl-cost-price-currency form-control select2 input-sm" name="seasons['.$v3['id'].'][unit_price]">';
			//if(isset($v['currency']['list']) && !empty($v['currency']['list'])){
			foreach(\app\modules\admin\models\Seasons::get_unit_prices() as $k2=>$v2){
				$html .= '<option value="'.$v2['id'].'" '.(isset($v3['unit_price']) && $v3['unit_price'] == $v2['id'] ? 'selected' : '').'>'.$v2['title'].'</option>';
			}
			//}
			
			$html .= '</select>';
			//}
			
			$html .= '</label>';
			
			$html .= '<label class="input-incurred-season-currency" style="margin-right:15px; '.($price_type == 2 ? 'display:inline-block' : 'display:none').'" >/</label>
				<label class="input-incurred-season-currency" style="min-width:160px; margin-right:15px; '.($price_type == 2 ? 'display:inline-block' : 'display:none').'" >';
			//if(isset(\ZAP\Zii::$site['other_setting']['currency']['list'])){
			$html .= '<select data-supplier_id="'.$id.'" data-season_id="'.$v3['id'].'" data-type_id="20" data-field="time_id" onchange="quick_change_supplier_season(this);" data-search="hidden" class="sl-cost-price-currency form-control select2 input-sm" name="seasons['.$v3['id'].'][time_id]">';
			//if(isset($v['currency']['list']) && !empty($v['currency']['list'])){
			for($i = -1; $i<4;$i++){
				$html .= '<option value="'.$i.'" '.(isset($v3['time_id']) && $v3['time_id'] == $i ? 'selected' : '').'>'.($i>-1 ? 'Áp dụng cho buổi ' . showPartDay($i) : '--').'</option>';
			}
			//}
			
			$html .= '</select>';
			//}
			
			$html .= '</label>';
			
			$html .= '</td>
					</tr>';
			if(in_array($v3['type_id'], [3,4,5]) && $price_type >0){
				$html .= '<tr>
					<td class="bold">Áp dụng mùa riêng</td>
					<td colspan="2"><label class=" w150p" style=" " ><select data-supplier_id="'.$id.'" data-season_id="'.$v3['id'].'" data-type_id="20" data-field="sub_id" onchange="quick_change_supplier_season(this)" style="" class="form-control input-sub_id-change-price sui-input input-sm select2" data-search="hidden" name="seasons['.$v3['id'].'][sub_id]"><option value="0">--</option>';
				foreach ($incurred_prices as $k5=>$v5){
					if($v3['id'] != $v5['id']){
						$html .= '<option '.(isset($v3['sub_id']) && $v3['sub_id'] == $v5['id'] ? 'selected' : '').' value="'.$v5['id'].'">'.$v5['title'].'</option>';
					}
				}
				$html .= '</select></label>
						
						
						
						
					</td>
					</tr>';
			}
			$html .= '<tr>
					<td class="bold">Đối tượng áp dụng</td>
					<td style="width:200px">
					<select data-supplier_id="'.$id.'" data-season_id="'.$v3['id'].'" data-type_id="20" data-field="object_id" onchange="quick_change_supplier_season(this);change_location_append(this);" name="seasons['.$v3['id'].'][object_id]" class="form-control input-sm select2 input-change-location-append" data-search="hidden">
					<option value="0">Tất cả</option>
					<option '.(isset($v3['object_id']) && $v3['object_id'] == 1 ? 'selected' : '').' value="1">Nhóm quốc tịch</option>
					<option '.(isset($v3['object_id']) && $v3['object_id'] == 2 ? 'selected' : '').' value="2">Quốc gia</option>
					</select>
					</td>
					<td>
							
					 ';
			///if(Yii::$app->user->can([ROOT_USER])){
			$html .= '<div class="input-group group-sm30">
					
					 <select  data-supplier_id="'.$id.'" data-season_id="'.$v3['id'].'" onchange="change_seasons_private_suppliers(this)" data-object_id="'.(isset($v3['object_id']) ? $v3['object_id'] : 0).'" data-placeholder="Nhóm quốc tịch hoặc các quốc gia được áp dụng" multiple class="form-control sui-input input-sm chosen-select input-location-appended" data-role="chosen-load-country" data-search="hidden" name="seasons['.$v3['id'].'][sub_id]">';
			
			
			$html .= '</select>
					
					
      <span class="input-group-btn">
        <button data-class="w60" data-action="add-more-nationality-group-to-supplier" data-title="Thêm nhóm quốc tịch" data-existed="" type="button" data-id="'.($id).'" data-target=".ajax-result-nationality-group" onclick="open_ajax_modal(this);" class="btn btn-sm btn-success btn-add-more add-more-nationality-group-to-supplier" type="button" title="Thêm mới nhóm quốc tịch"><i class="glyphicon glyphicon-plus"></i></button>
      </span>
    </div><!-- /input-group -->';
			//}
			
			$html .= '</td></tr></tbody></table>';
			
			
			
			$html .'</div>';
			///}else{
			
			//}
			$html .= '<table class="table vmiddle table-hover table-bordered"><thead><tr>';
			$html .= '<th rowspan="2" class="w200p center">Thời gian bắt đầu</th>';
			
			$html .= '<th rowspan="2" class="center w200p">Thời gian kết thúc</th>';
			$html .= '<th rowspan="2" class="center w200p">Buổi</th>';
			$html .= '<th rowspan="2" class="center ">Tiêu đề</th>';
			$html .= '<th rowspan="2" class="center w50p"> 
<div class="btn-list-add-more-1" style="border:none;padding:0"><button data-part_time="" 
data-tab="'.$k3.'" data-class="w60" data-type_id="'.$v3['type_id'].'" 
data-season_id="'.$v3['id'].'" data-action="sadd-more-season-to-supplier" 
data-title="Thêm mùa cao điểm, lễ tết, cuối tuần" data-existed="'.implode(',', $existed).'" 
type="button" data-id="'.($id).'" data-target=".ajax-result-price-distance-'.$k3.'" title="Thêm" onclick="open_ajax_modal(this);"
class="btn btn-sm btn-warning btn-add-more">
<i class=" glyphicon glyphicon-plus"></i></button></div>


</th>';
			$html .= '</tr></thead><tbody class="ajax-result-price-distance-'.$k3.'">';
			
			if(in_array($v3['type_id'], [3,4,5])){
				$l4 = \app\modules\admin\models\Seasons::get_list_weekend_by_parent($id, $v3['id']);
				//view($l4);
				if(!empty($l4)){
					foreach ($l4 as $k4=>$v4){
						$existed[] = $v4['id'];
						$html .= '<tr class="tr-distance-id-'.$v4['id'].'">';
						$html .= '<td class="center">'.$v4['from_time'].' '.read_date($v4['from_date']).'</td>';
						$html .= '<td class="center">'.$v4['to_time'].' '.read_date($v4['to_date']).'</td>';
						$html .= '<td class="center">'.($v4['part_time']>-1 ? showPartDay($v4['part_time']) : '-').'</td>';
						$html .= '<td class="center">'.$v4['title'].'<input type="hidden" value="'.$v4['id'].'" name="seasons['.$v3['id'].'][list_child]['.$v4['id'].'][id]"/></td>';
						$html .= '<td class="center"><i data-type_id="'.$v3['type_id'].'" data-season_id="'.$v4['id'].'" data-supplier_id="'.$id.'" data-parent_id="'.$v3['id'].'" title="Xóa" data-name="delete_price_distance_id" onclick="return removeSupplierSeason(this);" data-target=".tr-distance-id-'.$v4['id'].'" class="pointer glyphicon glyphicon-trash"></i></td>';
						$html .= '</tr>';
					}
					
				}
			}else {
				$l4 = \app\modules\admin\models\Seasons::get_list_seasons_by_parent($id, $v3['id']);
				if(!empty($l4)){
					foreach ($l4 as $k4=>$v4){
						$existed[] = $v4['id'];
						$t1 = strtotime($v4['from_date']);
						$t2 = strtotime($v4['to_date']);
						
						$lunar1 = Yii::$app->calendar->convertSolar2Lunar(date("d",$t1),date("m",$t1),date("Y",$t1));
						$lunar2 = Yii::$app->calendar->convertSolar2Lunar(date("d",$t2),date("m",$t2),date("Y",$t2));
						
						$html .= '<tr class="tr-distance-id-'.$v4['id'].'">';
						$html .= '<td class="center" title="'.date('d/m/Y',$lunar1).' âm lịch">'.date("d/m/Y",strtotime($v4['from_date'])).'</td>';
						$html .= '<td class="center" title="'.date('d/m/Y',$lunar2).' âm lịch">'.date("d/m/Y",strtotime($v4['to_date'])).'</td>';
						$html .= '<td class="center">-</td>';
						$html .= '<td><a>'.$v4['title'].'</a><input type="hidden" value="'.$v4['id'].'" name="seasons['.$v3['id'].'][list_child]['.$v4['id'].'][id]"/></td>';
						$html .= '<td class="center">
<i data-type_id="'.$v3['type_id'].'" 
data-season_id="'.$v4['id'].'" 
data-supplier_id="'.$id.'" 
data-parent_id="'.$v3['id'].'" 
title="Sao chép thời gian" 
data-action="seassion-copy-time" 
onclick="call_ajax_function(this);" 
data-target=".tr-distance-id-'.$v4['id'].'" class="pointer fa fa-copy"></i>

<i data-type_id="'.$v3['type_id'].'" 
data-season_id="'.$v4['id'].'" 
data-supplier_id="'.$id.'" 
data-parent_id="'.$v3['id'].'" 
title="Xóa" 
data-name="delete_price_distance_id" 
onclick="return removeSupplierSeason(this);" 
data-target=".tr-distance-id-'.$v4['id'].'" class="pointer glyphicon glyphicon-trash"></i>

</td>';
						$html .= '</tr>';
					}
					 
				}
			}
			$html .= '</tbody></table></div>';
			
			$html .= '<div class="aright btn-list-add-more-1"><button data-part_time="" data-tab="'.$k3.'" data-class="w60" data-type_id="'.$v3['type_id'].'" data-season_id="'.$v3['id'].'" data-action="sadd-more-season-to-supplier" data-title="Thêm mùa cao điểm, lễ tết, cuối tuần" data-existed="'.implode(',', $existed).'" type="button" data-id="'.($id).'" data-target=".ajax-result-price-distance-'.$k3.'" title="Thêm" onclick="open_ajax_modal(this);" class="btn btn-warning btn-add-more"><i class=" glyphicon glyphicon-plus"></i> Thêm mới</button></div>';
			$html .= '</div>';
		}
		
		$html .= '</div>';
		//
		
		echo json_encode([
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>'jQuery(\'.input-change-location-append\').change();reload_app(\'chosen\');'
		]+$_POST);exit;
		break;
	case 'quick-sadd-more-season-to-supplier':
		$callback = false; $callback_function = ''; $event = 'hide-modal';
		//
		$f = post('f',[]);
		$new = post('new',[]);
		$child_id = isset($f['child_id']) ? $f['child_id'] : [];
		//
		//if(!empty($new)){
		switch (post('type_id')){
			case SEASON_TYPE_WEEKEND: case SEASON_TYPE_WEEKDAY:// NT - CT
				
				if(!empty($new)){
					foreach ($new as $n){
						
						if(trim($n['from_time']) != "" && trim($n['to_time']) != "" && trim($n['title']) != ""){
							$child_id[] = Yii::$app->zii->insert('weekend',[
									'title'=>$n['title'],
									'sid'=>__SID__,
									'from_date'=>$n['from_date'],
									'to_date'=>$n['to_date'],
									'from_time'=>isset($n['from_time']) ? $n['from_time'] : '00:00:00',
									'to_time'=>isset($n['to_time']) ? $n['to_time'] : '23:59:59',
									//'parent_id'=>post('season_id'),
									'type_id'=>post('type_id'),
									'part_time'=>isset($n['part_time']) ? $n['part_time'] : -1
							]);
						}
					}
				}
				
				
				
				break;
			case SEASON_TYPE_TIME:// NT - CT
				
				if(!empty($new)){
					foreach ($new as $n){
						
						if(trim($n['title']) != ""){
							$child_id[] = Yii::$app->zii->insert('weekend',[
									'title'=>$n['title'],
									'sid'=>__SID__,
									'from_date'=>$n['from_date'],
									'to_date'=>$n['to_date'],
									'from_time'=>isset($n['from_time']) ? $n['from_time'] : '00:00:00',
									'to_time'=>isset($n['to_time']) ? $n['to_time'] : '23:59:59',
									//'parent_id'=>post('season_id'),
									'type_id'=>post('type_id'),
									'part_time'=>isset($n['part_time']) ? $n['part_time'] : -1
							]);
						}
					}
				}
				
				
				
				break;
				
			default: // Khoang thoi gian
				if(!empty($new)){
					foreach ($new as $n){
						if($n['title'] != "" && check_date_string($n['from_date'])){
							$n['to_date'] =  ctime(['string'=>$n['to_date']]);
							$n['to_date'] = date("Y-m-d 23:59:59",strtotime($n['to_date']));
							$child_id[] = Yii::$app->zii->insert('seasons',[
									//'parent_id'=>post('id'),
									'type_id'=>post('type_id'),
									'title'=>$n['title'],
									'sid'=>__SID__,
									'from_date'=>ctime(['string'=>$n['from_date']]),
									'to_date'=>$n['to_date'],
							]);
						}
					}
				}
				
				break;
				//}
				
		}
		if(!empty($child_id)){
			foreach ($child_id as $c){
				if((new Query())->from('seasons_to_suppliers')->where([
						'season_id'=>$c,
						'parent_id'=>post('season_id'),
						'supplier_id'=>post('id'),
						'type_id'=>post('type_id'),
				])->count(1) == 0){
					Yii::$app->db->createCommand()->insert('seasons_to_suppliers',[
							'season_id'=>$c,
							'parent_id'=>post('season_id'),
							'supplier_id'=>post('id'),
							'type_id'=>post('type_id'),
					])->execute();
				}
			}
		}
		//
		$callback = true;
		$callback_function = 'jQuery(\'.input-ajax-auto-load-seasons-detail\').attr(\'data-tab\','.post('tab',0).');reloadAutoPlayFunction(true);';
		echo json_encode([
				'event'=>$event,
				'callback'=>$callback,
				'callback_function'=>$callback_function,
		]+[$child_id]);exit;
		break;
	case 'sadd-more-season-to-supplier':
		$r = array(); $r['html'] = '';
		$m = new \app\modules\admin\models\Seasons();
		$type_id = isset($_POST['type_id']) ? $_POST['type_id'] : 2;
		///view($type_id);
		//
		$existed = post('existed');
		//
		$l4 = in_array($type_id,[3,4,5]) ? $m->get_weekend(['limit'=>100,'type_id'=>$type_id,'not_in'=>($existed != "" ? explode(',', $existed) : [])]) : $m->getList(['limit'=>100,'not_in'=>($existed != "" ? explode(',', $existed) : [])]);
		$r['html'] = '<div class="form-group">';
		$r['html'] .= '<div class="group-sm34 col-sm-12"><select name="f[child_id][]" multiple data-existed="'.$existed.'" data-role="chosen-load-season" class="form-control ajax-chosen-select-ajax" style="width:100%">';
		switch ($type_id){
			case SEASON_TYPE_WEEKEND: case SEASON_TYPE_WEEKDAY: case SEASON_TYPE_TIME:
				foreach (\app\modules\admin\models\Seasons::getAvailableWeekend([
				'parent_id'=>post('season_id'),
				'type_id'=>$type_id,
				'supplier_id'=>post('id')
				]) as $k4=>$v4){
					$r['html'] .= '<option value="'.$v4['id'].'">['.$v4['title'].'] '.$v4['from_time'] . ' ' . read_date($v4['from_date']). ' -> ' . $v4['to_time'] . ' ' . read_date($v4['to_date']) .'</option>';
				}
				break;
			default:
				foreach (\app\modules\admin\models\Seasons::getAvailableSeason([
				'parent_id'=>post('season_id'),
				//'type_id'=>$type_id,
				'supplier_id'=>post('id')
				]) as $k4=>$v4){
					$r['html'] .= '<option value="'.$v4['id'].'">'.$v4['title'].' ('.date("d/m/Y",strtotime($v4['from_date'])) .' - ' . date("d/m/Y",strtotime($v4['to_date'])) .')</option>';
				}
				break;
		}
		/*
		 if(!empty($l4['listItem'])){
		 foreach ($l4['listItem'] as $k4=>$v4){break;
		 if(in_array($type_id,[3,4,5])){
		 $r['html'] .= '<option value="'.$v4['id'].'">['.$v4['title'].'] '.$v4['from_time'] . ' ' . read_date($v4['from_date']). ' -> ' . $v4['to_time'] . ' ' . read_date($v4['to_date']) .'</option>';
		 }else{
		 $r['html'] .= '<option value="'.$v4['id'].'">'.$v4['title'].' ('.date("d/m/Y",strtotime($v4['from_date'])) .' - ' . date("d/m/Y",strtotime($v4['to_date'])) .')</option>';
		 }
		 }
		 }
		 */
		$r['html'] .= '</select></div>';
		$r['html'] .= '</div><p class="help-block italic ">*** Bạn có thể chọn giá trị có sẵn hoặc thêm mới ở ô nhập bên dưới</p><hr>';
		$r['html'] .= '<div class="">';
		
		$r['html'] .= '<div class="group-sm34">';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
		switch ($type_id){
			case SEASON_TYPE_WEEKEND: 
			case SEASON_TYPE_WEEKDAY:
			case SEASON_TYPE_TIME: 
				
				break;
			default:
				$r['html'] .= '<caption class="hide">
<label><input type="checkbox" />

</label>
</caption>';
				break;
		}
		$r['html'] .= '<tbody class="">';
		
		for($i=0; $i<3;$i++){
			switch ($type_id){
				case SEASON_TYPE_WEEKEND: case SEASON_TYPE_WEEKDAY:
					$r['html'] .= '<tr>
    					<td><select class="form-control input-sm ajax-select2-no-search"  name="new['.$i.'][from_date]">';
					for($j = 0;$j<7;$j++){
						$r['html'] .= '<option value="'.$j.'">'.read_date($j).'</option>';
					}
					$r['html'] .= '</select></td> 
    					<td><input type="text" data-time="1" data-format="H:i:s" data-mask="29/59/59" class="sui-input form-control input-sm datepicker2" value="" name="new['.$i.'][from_time]" placeholder="Thời gian bắt đầu"/></td>
    					<td><select class="form-control input-sm ajax-select2-no-search" name="new['.$i.'][to_date]">';
					for($j = 0;$j<7;$j++){
						$r['html'] .= '<option value="'.($j == 0 ? 7 : $j).'">'.read_date($j).'</option>';
					}
					$r['html'] .= '</select></td> 
    					<td class="center "><input type="text" data-time="1" data-format="H:i:s" data-mask="29/59/59" class="sui-input w100 form-control input-sm datepicker2" value="" name="new['.$i.'][to_time]" placeholder="Thời gian kết thúc"/></td>
    					<td class=""><input type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][title]" placeholder="Tiêu đề"/></td>';
					$r['html'] .= '</tr>';
					break;
				case SEASON_TYPE_TIME: 
					$r['html'] .= '<tr>
    					<td><select class="form-control input-sm ajax-select2-no-search"  name="new['.$i.'][from_date]">';
					for($j = 0;$j<7;$j++){
						$r['html'] .= '<option value="'.$j.'">'.read_date($j).'</option>';
					}
					$r['html'] .= '</select></td>
							
    					<td><select class="form-control input-sm ajax-select2-no-search" name="new['.$i.'][to_date]">';
					for($j = 0;$j<7;$j++){
						$r['html'] .= '<option value="'.($j == 0 ? 7 : $j).'">'.read_date($j).'</option>';
					}
					$r['html'] .= '</select></td>
    					<td><select class="form-control input-sm ajax-select2-no-search" name="new['.$i.'][part_time]">';
					for($j = 0;$j<4;$j++){
						$r['html'] .= '<option value="'.$j.'">'.showPartDay($j).'</option>'; 
					}
					$r['html'] .= '</select></td>
    					<td class=""><input type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][title]" placeholder="Tiêu đề"/></td>';
					$r['html'] .= '</tr>';
					break;
				default:
					$r['html'] .= '<tr>
    					<td class="pr"><input data-format="d/m/Y" data-mask="99/99/9999" onblur="addrequired_input(this);" type="text" class="sui-input form-control input-sm datepicker2" value="" name="new['.$i.'][from_date]" placeholder="Thời gian bắt đầu"/></td>
    					<td class="center pr"><input data-format="d/m/Y" data-mask="99/99/9999" onblur="addrequired_input(this);" type="text" class="sui-input w100 form-control input-sm datepicker2" value="" name="new['.$i.'][to_date]" placeholder="Thời gian kết thúc"/></td>
    					<td class="center "><input onblur="addrequired_input(this);" type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][title]" placeholder="Tiêu đề"/> </td>
					<td class="center">

</td>';
					$r['html'] .= '</tr>';
					break; 
			}
			
		}
		
		$r['html'] .= '</tbody></table>';
		/*
		$r['html'] .= '<input data-name="f" 
title=" Chọn tệp tin tải lên" 
data-folder_save="/tmp"
data-action="lt-al-2018-2028"
data-upload-group="-xls-import-class" 
data-include_site_name="0" 
data-group="-xls-import-class" 
data-filename-placement="inside" 
onchange="call_ajax_function(this)" type="file" 
class="bootstrap-file-inputs btn-boostrap-file-input fa fa-file-excel-o mgb5 f12e btn-warning btn-sm" 
name="myfile" id="myfile-xls-import-class" accept=".xls,.xlsx">';
		*/
		$r['html'] .= '</div>';
		$r['html'] .= '</div>';
		//
		$r['html'] .= '<div class="modal-footer">';
		$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
		$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$r['html'] .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		
		$r['event'] = $_POST['action'];
		$r['existed'] = $existed;
		$r['callback'] = true;
		$r['callback_function'] = 'load_datetimepicker2();jQuery(\'.bootstrap-file-inputs\').bootstrapFileInput();';
		echo json_encode($r);exit;
		
		break; 
	case 'generateSitemap':
		$html = Yii::$app->zii-> generateSitemap($_POST);
		echo json_encode([
				'html'=>$html
		]);exit;
		break;
	case 'checkExistedTourProgramCode':
		//$state = true;
		$id = post('id',0);
		$code = unMark(post('code'),'',false);
		$state = $code != "" ? ((new Query())->from('tours_programs')->where(['code'=>$code])->andWhere(['not in','id',$id])->count(1) > 0 ? false : true) : false;
		echo json_encode([
				'state'=>$state,
				'code'=>$code,
				'alert'=>$state ? '' : 'Mã tour không hợp lệ hoặc đã được sử dụng.',
		]+$_POST);
		exit;
		break;
	case 'change_selected_tour_service_group':
		$html = '';
		$place_id = post('place_id',0);$id = post('id');
		$changeDropdown = true;
		$place = [];
		if($place_id > 0){
			$place = \app\modules\admin\models\DeparturePlaces::getItem($place_id);
		}
		$dropdown = '<select data-placeholder="Chọn địa danh" onchange="quick_search_tour_service(\'.input-quick-search-service\');" data-action="load_dia_danh" data-role="load_dia_danh" class="form-control input-sm ajax-chosen-select-ajax input-quick-search-local"><option value="0">-</option>';
		if(!empty($place)){
			$dropdown .= '<option value="'.$place['id'].'" selected>'.$place['title'].'</option>';
		}
		$dropdown .= '</select>';
		
		switch ($id){
			case TYPE_ID_HOTEL: case TYPE_ID_REST:	case TYPE_ID_SHIP_HOTEL:
				$model = load_model('customers');
				
				$l = $model->getList([
						'type_id'=>$id,
						'p'=>1,
						'place_id'=>$place_id,
						'not_in'=>post('selected',[])
				]);
				//$html .= json_encode($l);
				if(!empty($l['listItem'])){
					foreach($l['listItem'] as $k=>$v){
						// Lay package
						$packages = \app\modules\admin\models\PackagePrices::getPackages($v['id']);
						if(empty($packages)){
							$packages = [['id'=>0,'title'=>'']];
						}
						foreach ($packages as $package){
							$html .= '<li data-package_id="'.$package['id'].'" data-type_id="'.$id.'" data-id="'.$v['id'].'" data-supplier_id="'.$v['id'].'" class="ui-state-highlight">'.($package['id']>0 ? '<i class="green underline">['.uh($package['title']).']</i>&nbsp;' : '').''.uh($v['name']).'</li>';
						}
					}
				}
				break;
			case TYPE_CODE_DISTANCE:
				$model = load_model('distances');
				
				$l = $model->getAll(TYPE_ID_VECL, [
						'limit'=>100,
						'place_id'=>$place_id,
						'not_in'=>post('selected'),
						'p'=>1
				]);
				//$html .= json_encode($l);
				if(!empty($l)){
					foreach($l as $k=>$v){
						// Lay package
						$packages = \app\modules\admin\models\PackagePrices::getPackages($v['id']);
						if(empty($packages)){
							$packages = [['id'=>0,'title'=>'']];
						}
						foreach ($packages as $package){
							$html .= '<li data-package_id="'.$package['id'].'" data-type_id="'.$id.'" data-id="'.$v['id'].'" class="ui-state-highlight">'.uh($v['title']).'</li>';
						}
					}
				}
				break;
			case TYPE_ID_SHIP:
				$model = load_model('distances');
				
				$l = $model->getAll(TYPE_ID_SHIP, [
						'limit'=>100,
						'place_id'=>$place_id,
						'not_in'=>post('selected'),
						'p'=>1
				]);
				//$html .= json_encode($l);
				if(!empty($l)){
					foreach($l as $k=>$v){
						// Lay package
						$packages = \app\modules\admin\models\PackagePrices::getPackages($v['id']);
						if(empty($packages)){
							$packages = [['id'=>0,'title'=>'']];
						}
						foreach ($packages as $package){
							$html .= '<li data-package_id="'.$package['id'].'" data-type_id="'.$id.'" data-id="'.$v['id'].'" class="ui-state-highlight">'.uh($v['title']).'</li>';
						}
					}
				}
				break;
				
			case TYPE_ID_SCEN:
				$model = load_model('tickets');
				$l = $model->getList([
						'limit'=>100,
						'p'=>1,
						'type_id'=>TYPE_ID_SCEN,
						'place_id'=>$place_id,
						'filter_text'=>post('value'),
						'not_in'=>post('selected')
				]);
				//$html .= json_encode($l);
				if(!empty($l['listItem'])){
					foreach($l['listItem'] as $k=>$v){
						
						$html .= '<li data-package_id="0" data-type_id="'.$id.'" data-id="'.$v['id'].'" class="ui-state-highlight">'.uh($v['title']).'</li>';
						
					}
				}
				break;
			case TYPE_ID_GUIDES:
				$model = load_model('guides');
				$l = $model->getGuidesByPlace([
						'limit'=>100,
						'p'=>1,
						'place_id'=>$place_id,
						'filter_text'=>post('value'),
						'not_in'=>post('selected'),
						
				]);
				//$html .= json_encode($l);
				if(!empty($l)){
					foreach($l as $k=>$v){
						// Lay package
						$packages = \app\modules\admin\models\PackagePrices::getPackages(Yii::$app->zii->getSupplierIDFromService($v['id'],TYPE_ID_GUIDES));
						if(empty($packages)){
							$packages = [['id'=>0,'title'=>'']];
						}
						foreach ($packages as $package){
							$html .= '<li data-package_id="'.$package['id'].'" data-type_id="'.$id.'" data-id="'.$v['id'].'" class="ui-state-highlight">'.uh($v['title']).' &nbsp;<i class="underline font-normal green">['.uh($v['supplier_name']).']</i></li>';
						}
					}
				}
				
				break;
				
			case TYPE_ID_TEXT:
				//$model = load_model('guides');
				$l = \app\modules\admin\models\TextInstructions::getAll([
				'limit'=>100,
				'p'=>1,
				'place_id'=>$place_id,
				'filter_text'=>post('value'),
				'not_in'=>post('selected')
				]);
				//$html .= json_encode($l);
				if(!empty($l)){
					foreach($l as $k=>$v){
						$html .= '<li data-package_id="0" data-type_id="'.$id.'" data-id="'.$v['id'].'" class="ui-state-highlight">'.uh($v['title']).'</li>';
					}
				}
				$html .= '<li data-package_id="0" data-type_id="'.$id.'" data-id="0" class="ui-state-highlight">
						
						<input type="text" name="new['.$id.'][]" class="form-control" placeholder="Thêm nhanh" title="Kéo thả sang ô bên trái rồi điền giá trị"/>
						</li>';
				
				break;
			case TYPE_ID_TRAIN:
				$changeDropdown = true;
				$dropdown = '<select data-placeholder="Chọn chặng"
						onchange="quick_search_tour_service(\'.input-quick-search-service\');"
						data-action="load_train_ticket"
						data-role="load_train_ticket" class="form-control input-sm input-quick-search-train-distance chosen-select" style="display: none;">';
				foreach (\app\modules\admin\models\PackagePrices::getAll(['type_id'=>TYPE_ID_TRAIN, 'not_in'=>post('existed',[])]) as $k1=>$v1){
					$dropdown .= '<option value="'.$v1['id'].'">'.uh($v1['title']) . '</option>';
				}
				$dropdown .= '<option value=""></option></select>';
				
				break;
				
		}
		$html .= '';
		echo json_encode([
				'html'=>$html,
				'changeDropdown'=>$changeDropdown,
				'dropdown'=>$dropdown,
				'callback'=>true,
				'callback_function'=>''
		]+$_POST);
		exit;
		break;
	case 'quick_search_tour_service':
		$html = '';
		$id = post('type_id',0);
		$item_id = post('item_id',0);
		$place_id = post('place_id',0);
		switch ($id){
			case TYPE_ID_HOTEL: case TYPE_ID_REST: case TYPE_ID_VECL:case TYPE_ID_SHIP_HOTEL:
				$model = load_model('customers');
				
				$l = $model->getList([
						'type_id'=>$id,
						'p'=>1,
						'is_active'=>1,
						'place_id'=>$place_id,
						'filter_text'=>post('value'),
						'not_in'=>post('selected')
				]);
				if(!empty($l['listItem'])){
					foreach($l['listItem'] as $k=>$v){
						// Lay package
						$packages = \app\modules\admin\models\PackagePrices::getPackages($v['id']);
						if(empty($packages)){
							$packages = [['id'=>0,'title'=>'']];
						}
						foreach ($packages as $package){
							$html .= '<li data-package_id="'.$package['id'].'" data-type_id="'.$id.'" data-id="'.$v['id'].'" data-supplier_id="'.$v['id'].'" class="ui-state-highlight">'.($package['id']>0 ? '<i class="green underline">['.uh($package['title']).']</i>&nbsp;' : '').''.uh($v['name']).'</li>';
						}
					}
				}
				break;
			case TYPE_CODE_DISTANCE:
				$model = load_model('distances');
				
				$l = $model->getAll(TYPE_ID_VECL, [
						'limit'=>100,
						'place_id'=>$place_id,
						'not_in'=>post('selected'),
						'filter_text'=>post('value'),
						'p'=>1
				]);
				//$html .= json_encode($l);
				if(!empty($l)){
					foreach($l as $k=>$v){
						// Lay package
						$packages = \app\modules\admin\models\PackagePrices::getPackages($v['id']);
						if(empty($packages)){
							$packages = [['id'=>0,'title'=>'']];
						}
						foreach ($packages as $package){
							$html .= '<li data-package_id="'.$package['id'].'" data-type_id="'.$id.'" data-id="'.$v['id'].'" class="ui-state-highlight">'.uh($v['title']).'</li>';
						}
					}
				}
				break;
			case TYPE_ID_SHIP:
				$model = load_model('distances');
				
				$l = $model->getAll(TYPE_ID_SHIP, [
						'limit'=>100,
						'place_id'=>$place_id,
						'not_in'=>post('selected'),
						'filter_text'=>post('value'),
						'p'=>1
				]);
				//$html .= json_encode($l);
				if(!empty($l)){
					foreach($l as $k=>$v){
						// Lay package
						$packages = \app\modules\admin\models\PackagePrices::getPackages($v['id']);
						if(empty($packages)){
							$packages = [['id'=>0,'title'=>'']];
						}
						foreach ($packages as $package){
							$html .= '<li data-package_id="'.$package['id'].'" data-type_id="'.$id.'" data-id="'.$v['id'].'" class="ui-state-highlight">'.uh($v['title']).'</li>';
						}
					}
				}
				break;
				
			case TYPE_ID_SCEN:
				$model = load_model('tickets');
				
				$l = $model->getList([
						'limit'=>100,
						'p'=>1,
						'type_id'=>TYPE_ID_SCEN,
						'place_id'=>$place_id,
						'filter_text'=>post('value'),
						'not_in'=>post('selected')
				]);
				//$html .= json_encode($l);
				if(!empty($l['listItem'])){
					foreach($l['listItem'] as $k=>$v){
						$html .= '<li data-type_id="'.$id.'" data-id="'.$v['id'].'" class="ui-state-highlight">'.uh($v['title']).'</li>';
					}
				}
				break;
				
			case TYPE_ID_GUIDES:
				$model = load_model('guides');
				$l = $model->getGuidesByPlace([
						'limit'=>100,
						'p'=>1,
						'place_id'=>$place_id,
						'filter_text'=>post('value'),
						'not_in'=>post('selected'),
						'language'=>post('language','')
				]);
				
				//$html .= json_encode($l);
				if(!empty($l)){
					foreach($l as $k=>$v){
						// Lay package
						$packages = \app\modules\admin\models\PackagePrices::getPackages(Yii::$app->zii->getSupplierIDFromService($v['id'],TYPE_ID_GUIDES));
						if(empty($packages)){
							$packages = [['id'=>0,'title'=>'']];
						}
						foreach ($packages as $package){
							$html .= '<li data-package_id="'.$package['id'].'" data-type_id="'.$id.'" data-id="'.$v['id'].'" class="ui-state-highlight">
										<div class="col-sm-8 col-index-1 col-border-right">'.uh($v['title']).' &nbsp;<i class="underline font-normal green">['.uh($v['supplier_name']).']</i></div>
												
												</li>';
						}
					}
				}
				
				break;
				
			case TYPE_ID_TEXT:
				//$model = load_model('guides');
				$l = \app\modules\admin\models\TextInstructions::getAll([
				'limit'=>100,
				'p'=>1,
				'place_id'=>$place_id,
				'filter_text'=>post('value'),
				'not_in'=>post('selected')
				]);
				//$html .= json_encode($l);
				if(!empty($l)){
					foreach($l as $k=>$v){
						$html .= '<li data-package_id="0" data-type_id="'.$id.'" data-id="'.$v['id'].'" class="ui-state-highlight">'.uh($v['title']).'</li>';
					}
				}
				$html .= '<li data-package_id="0" data-type_id="'.$id.'" data-id="0" class="ui-state-highlight">
						
						<input type="text" name="new['.$id.'][]" class="form-control" placeholder="Thêm nhanh" title="Kéo thả sang ô bên trái rồi điền giá trị"/>
						</li>';
				
				break;
				
			case TYPE_CODE_VEHICLE:
				
				$l = \app\modules\admin\models\Cars::getListCars(post('supplier_id'),[
				'limit'=>100,
				'p'=>1,
				'place_id'=>$place_id,
				'filter_text'=>post('value'),
				'not_in'=>post('selected'),
				'is_active'=>1
				]);
				//$html .= json_encode($l);
				if(!empty($l)){
					foreach($l as $k=>$v){
						$html .= '<li data-type_id="'.$id.'" data-id="'.$v['id'].'" class="ui-state-highlight li_child_item_id_'.$v['id'].'">
								<div class="col-sm-8 col-index-1 col-border-right">'.uh($v['title']).' <i class="underline font-normal green">['.uh($v['maker_title']).']</i></div>
								<div class="col-sm-4 col-index-2"><input type="number" class="form-control center number-format selected_quantity" data-name="selected_quantity[]" placeholder="Số lượng"/></div>
								</li>';
					}
				}
				break;
			case TYPE_ID_TRAIN:
				
				
				foreach (\app\modules\admin\models\ServicesProvider::getAll([
				'type_id'=>TYPE_ID_TRAIN
				]) as $c){
					foreach(\app\modules\admin\models\ServicesProvider::getTrainDistanceBySupplier([
							'supplier_id'=>$c['id'],
							'package_id'=>post('train_distance_id',0)
					]) as $k=>$v){
						$html .= '<li data-package_id="'. post('train_distance_id',0) .'" data-type_id="'.$id.'" data-station_from="'.$v[0].'" data-station_to="'.$v[1].'" data-ticket_id="'.$v['ticket_id'].'" data-id="'.$v['ticket_id'].'" class="ui-state-highlight li_child_item_id_'.$v[0].'">
								<div class="col-sm-12 col-index-1">'.uh($v['distance']).' <i>['.$c['name'].']</i></div>
										
								</li>';
					}
				}
				
				break;
				
				
		}
		echo json_encode([
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>'load_number_format();'
		]+$_POST);
		exit;
		break;
		
	case 'quick-add-more-distance-supplier':
		$item_id = post('id',0);
		$segment_id = post('segment_id',0);
		$day = post('day');
		$time = post('time');
		$nationality = post('nationality');
		$guest = post('guest');
		//$type_id = post('type_id',0);
		$item = \app\modules\admin\models\ToursPrograms::getItem($item_id);
		$selected_value = post('selected_value',[]);
		//
		$l1 = (new \yii\db\Query())->from('tours_programs_to_suppliers')->where(
				['and',
						['not in','supplier_id',$selected_value],
						['item_id'=>$item_id,'segment_id'=>$segment_id]
						
				])->all();
				if(!empty($l1)){
					foreach ($l1 as $k1=>$v1){
						Yii::$app->zii->removeTransportSupplierTourProgram([
								'supplier_id'=>$v1['supplier_id'],
								'item_id'=>$item_id,
								'segment_id'=>$segment_id,
						]);
					}
				}
				//
				$selected_id = post('selected_id',[]);
				$i = 0;
				if(!empty($selected_value)){
					$selected_type_id = post('selected_type_id');
					foreach ($selected_value as $k => $id){
						$type_id = $selected_type_id[$k];
						if(!in_array($id, $selected_id)){
							
							if((new yii\db\Query)->from('tours_programs_to_suppliers')->where([
									//'position'=>$k,
									//'type_id'=>$type_id,
									'supplier_id'=>$id,
									'item_id'=>$item_id,
									'segment_id'=>$segment_id,
							])->count(1) == 0){
								
								
								
								Yii::$app->db->createCommand()->insert('tours_programs_to_suppliers',[
										'position'=>$k,
										'type_id'=>$type_id,
										'supplier_id'=>$id,
										'item_id'=>$item_id,
										'segment_id'=>$segment_id,
								])->execute();
								// Lấy xe tự động
								$c = (Yii::$app->zii->getVehicleAuto([
										'total_pax'=>$item['guest'],
										'nationality_id'=>$item['nationality'],
										'supplier_id'=>$id,
										'auto'=>true,
										
								]));
								if(!empty($c) && isset($c[0])){
									$c = $c[0];
									if((new Query())->from('tours_programs_suppliers_vehicles')->where([
											'vehicle_id'=>$c['id'],
											'supplier_id'=>$id,
											'item_id'=>$item_id,
											'segment_id'=>$segment_id,
									])->count(1) == 0){
										Yii::$app->db->createCommand()->insert('tours_programs_suppliers_vehicles',[
												'vehicle_id'=>$c['id'],
												'quantity'=>$c['quantity'],
												'supplier_id'=>$id,
												'item_id'=>$item_id,  'segment_id'=>$segment_id,
										])->execute();
									}else{
										
									}
								}
							}else{
								Yii::$app->db->createCommand()->update('tours_programs_to_suppliers',[
										'position'=>$k
								],[
										'supplier_id'=>$id,
										'item_id'=>$item_id,
										'segment_id'=>$segment_id,
								])->execute();
							}
						}else{
							Yii::$app->db->createCommand()->update('tours_programs_to_suppliers',[
									'position'=>$k
							],[
									'supplier_id'=>$id,
									'item_id'=>$item_id,
									'segment_id'=>$segment_id,
							])->execute();
						}
					}
				}
				loadTourProgramGuides($item_id,[
						'loadDefault'=>true,
						'updateDatabase'=>true,
				]);
				echo json_encode([
						'event'=>'hide-modal',
						'callback'=>true,
						'callback_function'=>'reloadAutoPlayFunction(true);'
				]);
				exit;
				break;
	case 'quick-add-tours-services':
		$item_id = post('id');
		$day = post('day');
		$time = post('time');
		$day_id = $day; $time_id = $time;
		
		
		$selected_value = post('selected_value',[]);
		$removed_item_id = post('removed_item_id',[]);
		
		
		if(!empty($removed_item_id)){
			$removed_type_id = post('removed_type_id',[]);
			$removed_package_id = post('removed_package_id',[]);
			
			foreach ($removed_item_id as $k=>$id){
				Yii::$app->db->createCommand()->delete('tours_programs_services_prices',[
						'item_id'=>$item_id,
						'day_id'=>$day,
						'time_id'=>$time,
						'type_id'=>$removed_type_id[$k],
						'package_id'=>$removed_package_id[$k],
						'service_id'=>$id
				])->execute();
				Yii::$app->db->createCommand()->delete('tours_programs_services_days',[
						'item_id'=>$item_id,
						'day_id'=>$day,
						'time_id'=>$time,
						'type_id'=>$removed_type_id[$k],
						'package_id'=>$removed_package_id[$k],
						'service_id'=>$id
				])->execute();
			}
		}
		
		
		$i = 0;
		if(!empty($selected_value)){
			$selected_type_id = post('selected_type_id');
			$selected_package_id = post('selected_package_id');
			$new = post('new',[]);
			$new_added = [];
			if(!empty($new)){
				foreach ($new as $type_id=>$titles){
					if(!empty($titles)){
						foreach ($titles as $title){
							switch ($type_id){
								case TYPE_ID_TEXT:
									if(trim($title) != "" && (new Query())->from(\app\modules\admin\models\TextInstructions::tableName())->where(['title'=>trim($title),'sid'=>__SID__])->count(1) == 0){
										$f['title'] = trim($title);
										$f['sid'] = __SID__;										 									
										$tid = Yii::$app->zii->insert(\app\modules\admin\models\TextInstructions::tableName(),$f);
										$tid = Yii::$app->db->createCommand()->update(\app\modules\admin\models\TextInstructions::tableName(),[
												'lang_code'=>'text_text_instructions_'. $tid
										],[
												'id'=>$tid
										])->execute();
										$new_added[] = $tid;
										//
										Yii::$app->db->createCommand()->insert('user_text_translate',[
												'sid' => __SID__,
												'lang_code' => 'text_text_instructions_'. $tid,
												'lang' => ADMIN_LANG,
												'value' => $f['title'],
										])->execute();
										//
									}else{
										$new_added[] = (new Query())->from(\app\modules\admin\models\TextInstructions::tableName())->where(['title'=>trim($title),'sid'=>__SID__])->select('id')->scalar();
									}
									break;
							}}
					}
				}
			}
			$m = '';
			$selected_quantity = post('selected_quantity',[]);
			foreach ($selected_value as $k => $id){
				$type_id = $selected_type_id[$k];
				$package_id = (isset($selected_package_id[$k]) ? $selected_package_id[$k] : 0 );
				if($id == 0){
					if(isset($new_added[0])){
						$id = $new_added[0]; unset($new_added[0]); $new_added = array_values($new_added);
					}
				}
				switch ($type_id){
					case TYPE_ID_SHIP: case TYPE_ID_SCEN: case TYPE_ID_GUIDES:
						$sub_item_id = $id;
						break;
					default:
						$sub_item_id = 0;
						break;
				}
				if($id>0){
					//	foreach ($v as $id){
					$supplier_id = Yii::$app->zii->getSupplierIDFromService($id,$type_id);
					
					if((new Query())->from('tours_programs_services_prices')->where([
							'item_id'=>$item_id,
							'type_id'=>$type_id,
							'service_id'=>$id,
							'day_id'=>$day,
							'time_id'=>$time,
							'supplier_id'=>$supplier_id,
							//'sub_item_id'=>[$sub_item_id,0],
							'package_id'=>(isset($selected_package_id[$k]) ? $selected_package_id[$k] : 0 ),
							
					])->count(1) == 0){
						// Lấy giá mặc định
						$price1 = 0; $currency = 1;
						$state = 2;
						$price = [];
						switch ($type_id){
							case TYPE_ID_TRAIN:
								$price = \app\modules\admin\models\ToursPrograms::getServiceDefaultPrice([
								'item_id'=>$item_id,
								'service_id'=>$id,
								'type_id'=>$type_id,
								'package_id'=>$package_id,
								'day_id'=>$day_id,
								'time_id'=>$time_id,
								]);
								break;
							default:
									
								$price = \app\modules\admin\models\ToursPrograms::getServiceDefaultPrice([
									'item_id'=>$item_id,
									'service_id'=>$id,
									'type_id'=>$type_id,
									'package_id'=>$package_id,
									'day_id'=>$day_id,
									'time_id'=>$time_id,
								]);																
								break;
						}
						//
						
						if(!empty($price) && $price['price1'] > 0){
							$state = 1;
							if(isset($price['sub_item_id'])){
								$sub_item_id = $price['sub_item_id'];
							}
							$currency = $price['currency'];
							$selected_quantity[$id] = $price['quantity'];
							$price1 = $price['price1'];
						}
						//break;
						//$callback_function .= 'log(\''.json_encode($price).'\');';
						//
						
						Yii::$app->db->createCommand()->insert('tours_programs_services_prices',[
								'item_id'=>$item_id,
								'type_id'=>$type_id,
								'service_id'=>$id,
								'day_id'=>$day,
								'time_id'=>$time,
								'price1'=>$price1,
								'currency'=>$currency,
								'position'=>$k,
								'state'=>$state,
								'supplier_id'=>$supplier_id,
								'sub_item_id'=>$sub_item_id,
								'package_id'=>$package_id,
						]
								+ (isset($selected_quantity[$id]) && is_numeric($selected_quantity[$id]) ? ['quantity'=>$selected_quantity[$id]] : [])
								)->execute();
								
								
					}else{
						
						
						Yii::$app->db->createCommand()->update('tours_programs_services_prices',
								[
										'position'=>$k,
								]
								+ (isset($selected_quantity[$id]) && is_numeric($selected_quantity[$id]) ? ['quantity'=>$selected_quantity[$id]] : [])
								,
								[
										'item_id'=>$item_id,
										'type_id'=>$type_id,
										'service_id'=>$id,
										'day_id'=>$day,
										'time_id'=>$time,
										'supplier_id'=>$supplier_id,
										//'sub_item_id'=>[$sub_item_id,0],
										'package_id'=>(isset($selected_package_id[$k]) ? $selected_package_id[$k] : 0 ),
								])->execute();
								
					}
				}
				
				
				if($id>0){
					
					
					
					if((new \yii\db\Query())->from('tours_programs_services_days')->where([
							'item_id'=>$item_id,'service_id'=>$id,
							'day_id'=>$day,
							'time_id'=>$time,
							'type_id'=>$type_id,
							'package_id'=>$selected_package_id[$k],
					])->count(1) == 0){
						
						Yii::$app->db->createCommand()->insert('tours_programs_services_days',
								[
										'service_id'=>$id,
										'item_id'=>$item_id,
										'day_id'=>$day,
										'time_id'=>$time,
										'type_id'=>$type_id,
										'package_id'=>(isset($selected_package_id[$k]) ? $selected_package_id[$k] : 0 ),
										'position'=>$k,
								]
								
								)->execute();
								
								
					}else{
						Yii::$app->db->createCommand()->update('tours_programs_services_days',
								[
										'position'=>$k,
										
								]
								
								,[
										'service_id'=>$id,
										'item_id'=>$item_id,
										'day_id'=>$day,
										'time_id'=>$time,
										'type_id'=>$type_id,
										'package_id'=>(isset($selected_package_id[$k]) ? $selected_package_id[$k] : 0 ),
										
								])->execute();
					}
				}
				
			}
		}
		//
		
		
		//
		$callback_function .= 'reloadAutoPlayFunction(true);';
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
				'z' => isset($s) ? $s : '',
				//'m'=>(isset($selected_quantity[$id]) && is_numeric($selected_quantity[$id]) ? ['quantity'=>$selected_quantity[$id]] : []),
				'callback_function'=>$callback_function
		]);
		exit;
		break;
	case 'quick-add-tours-distance-services':
		$item_id = post('id');
		$supplier_id = post('supplier_id',0);
		$time = post('time');
		$segment_id = post('segment_id',0);
		
		$selected_value = post('selected_value',[]);
		//view($selected_value);
		Yii::$app->db->createCommand()->delete('tours_programs_services_distances',['and',[
				'item_id'=>$item_id,
				'supplier_id'=>$supplier_id,
				//'time'=>$time,
				'segment_id'=>$segment_id
		],[
				'not in','service_id',$selected_value
		]])->execute();
		$i = 0;
		if(!empty($selected_value)){
			$selected_type_id = post('selected_type_id');
			foreach ($selected_value as $k => $id){
				$type_id = $selected_type_id[$k];
				if((new Query())->from('tours_programs_services_distances')->where([
						'item_id'=>$item_id,
						'supplier_id'=>$supplier_id,
						'service_id'=>$id,
						'type_id'=>$type_id,
						'segment_id'=>$segment_id
				])->count(1) == 0){
					//	foreach ($v as $id){
					Yii::$app->db->createCommand()->insert('tours_programs_services_distances',[
							'item_id'=>$item_id,
							'type_id'=>$type_id,
							'service_id'=>$id,
							'supplier_id'=>$supplier_id,
							//'time'=>$time,
							'segment_id'=>$segment_id,
							'position'=>$k
					])->execute();
					//}
				}else {
					Yii::$app->db->createCommand()->update('tours_programs_services_distances',['position'=>$k],[
							'item_id'=>$item_id,
							'type_id'=>$type_id,
							'service_id'=>$id,
							'segment_id'=>$segment_id,
							'supplier_id'=>$supplier_id,
							//'time'=>$time,
							
					])->execute();
				}
			}
		}
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>'reloadAutoPlayFunction(true);'
		]);
		exit;
		break;
	case 'add-tours-distance-services':
		$id = post('id',0);
		$day = post('day',0);
		$supplier_id = post('supplier_id',0);
		$time = post('time',0);
		$segment_id = post('segment_id',0);
		$html = '';
		
		$html .= '<p class="help-block">Bạn đang chọn dịch vụ vận chuyển</p>
				<table class="table table-bordered vmiddle"><thead><tr>
				
				<th class="center bold col-ws-6">Dịch vụ đã chọn</th>
				<th class="center bold col-ws-6">Dịch vụ có thể chọn</th>
				</tr></thead><tbody>';
		$html .= '<tr class="vtop">
				<td class="hide">
				<ul class="style-none ul-style-l01">';
		foreach (showListChooseService() as $k=>$v){
			$html .= $v['id'] == TYPE_CODE_DISTANCE ? '<li class="'.($v['id'] == TYPE_CODE_DISTANCE ? 'li-service-first-child' : '').'"><a data-segment_id="'.$segment_id.'" data-day="'.$day.'" data-time="'.$time.'" data-id="'.$v['id'].'" onclick="return change_selected_tour_service_group(this);" href="#">'.$v['title'].'</a></li>' : '';
		}
		$services = \app\modules\admin\models\ToursPrograms::getProgramDistanceServices($id,$supplier_id,[
				'segment_id'=>$segment_id
		]);
		
		$html .= '</ul>
				
				</td>
				<td class="">
				
				<ul id="sortable1" class="connectedSortable style-none">';
		if(!empty($services)){
			foreach ($services as $kv=>$sv){
				$html .= '<li data-package_id="'.$sv['package_id'].'" data-type_id="'.$sv['type_id'].'" data-id="'.$sv['id'].'" class="ui-state-default">'.(isset($sv['title']) ? uh($sv['title']) : uh($sv['name'])).(isset($sv['supplier_name']) ? ' <i class="underline font-normal green">['.uh($sv['supplier_name']).']</i>' : '').'
						<input value="'.$sv['id'].'" type="hidden" class="selected_value_'.$sv['type_id'].' selected_value_'.$sv['type_id'].'_'.$day.'_'.$time.'" name="selected_value[]"/>
						<input value="'.$sv['type_id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_type_id[]"/>
						<input value="'.$sv['package_id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_package_id[]"/></li>';
			}
		}
		$place = [];
		if(post('place_id') > 0){
			$place = \app\modules\admin\models\DeparturePlaces::getItem(post('place_id'));
		}
		
		$html .= '</ul>
				</td>
				<td class="">
<div class="div-quick-search-service">
						<div class="fl w50">
						<select data-segment_id="'.$segment_id.'" data-placeholder="Chọn địa danh" onchange="quick_search_tour_service(\'.input-quick-search-service\');" data-action="load_dia_danh" data-role="load_dia_danh" class="form-control input-sm ajax-chosen-select-ajax input-quick-search-local">';
		if(!empty($place)){
			$html .= '<option value="'.$place['id'].'" selected>'.$place['name'].'</option>';
		}
		$html .= '</select>
						</div><div class="fl w50">
						<input data-time="'.$time.'" data-day="'.$day.'" data-type_id="'.TYPE_CODE_DISTANCE.'" type="text" onkeyup="quick_search_tour_service(this);" onkeypress="return disabledFnKey(this);" placeholder="Tìm kiếm nhanh" class="form-control input-quick-search-service"/></div></div>
<div class="fl100"><div class="available_services div-slim-scroll" data-height="auto">
								
<ul id="sortable2" class="connectedSortable style-none">
								
</ul></div></div>
								
				</td>
				</tr>';
		
		
		$html .= '</tbody></table>';
		
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Đóng lại</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		//$r['event'] = $_POST['action'];
		echo json_encode([
				'html'=>$html,
				'event'=>$_POST['action'],
				'callback'=>true,
				'callback_function'=>'loadScrollDiv();loadSelectTagsinput1();jQuery(\'.li-service-first-child a\').click();
jQuery("#sortable2").sortable({connectWith: ".connectedSortable",
receive:function(event,ui){
				(ui.item).addClass(\'ui-state-highlight\').removeClass(\'ui-state-default\')
				.find(\'input\').remove()
},
				
}).disableSelection();
jQuery("#sortable1").sortable({connectWith: ".connectedSortable",
receive:function(event,ui){
				$type_id = ui.item.attr(\'data-type_id\');
				$id = ui.item.attr(\'data-id\');
				(ui.item).removeClass(\'ui-state-highlight\').addClass(\'ui-state-default\')
				.append(\'<input value="\'+$id+\'" type="hidden" class="selected_value_\'+$type_id+\' selected_value_\'+$type_id+\'_'.$day.'_'.$time.' " name="selected_value[]"/><input value="\'+$type_id+\'" type="hidden" class="selected_value_\'+$type_id+\'" name="selected_type_id[]"/>\')
},
change:function(event,ui){
				//console.log(ui.item.index())
				//(ui.item).removeClass(\'ui-state-highlight\').addClass(\'ui-state-default\')
},
	start: function(event, ui) {
				
        //console.log("Start position: " + ui.item.index());
    },
				stop: function(event, ui) {
				
        //console.log("New position: " + ui.item.index());
    }
}).disableSelection();'
				//'alert'=>$state ? '' : 'Mã tour không hợp lệ hoặc đã được sử dụng.',
		]+$_POST);
		exit;
		break;
	case 'quick-add-more-tours-program-guides':
		$guide_type = post('guide_type',1);
		$item_id = post('item_id',0);
		$segment_id = post('segment_id',0);
		$selected_value = post('selected_value',[]);
		$selected_quantity = post('selected_quantity',[]);
		// Xóa dl dư
		Yii::$app->db->createCommand()->delete('tours_programs_guides',['and',
				['item_id'=>$item_id,'segment_id'=>$segment_id],
				['not in','guide_id',$selected_value]
		])->execute();
		
		Yii::$app->db->createCommand()->delete('tours_programs_guides_prices',['and',
				['item_id'=>$item_id,'segment_id'=>$segment_id],
				['not in','service_id',$selected_value]
		])->execute();
		//
		
		$supplier_id = 0;
		if(!empty($selected_value)){
			foreach ($selected_value as $position => $guide_id){
				
				$quantity = $selected_quantity[$guide_id];
				
				$supplier_id = Yii::$app->zii->getSupplierIDFromService($guide_id,TYPE_ID_GUIDES);
				
				if((new Query())->from('tours_programs_guides')->where([
						'item_id'=>$item_id,
						'segment_id'=>$segment_id,
						'supplier_id'=>$supplier_id,
						'guide_id'=>$guide_id,
						'type_id'=>$guide_type,
				])->count(1) == 0){
					
					$c = Yii::$app->db->createCommand()->insert('tours_programs_guides',[
							'item_id'=>$item_id,
							'segment_id'=>$segment_id,
							'supplier_id'=>$supplier_id,
							'guide_id'=>$guide_id,
							'position'=>$position,
							'type_id'=>$guide_type,
							'quantity'=>$quantity,
							
					])->execute();
					
				}else{
					
					$c = Yii::$app->db->createCommand()->update('tours_programs_guides',[
							'position'=>$position,
							'quantity'=>$quantity,
					],[
							'item_id'=>$item_id,
							'segment_id'=>$segment_id,
							'supplier_id'=>$supplier_id,
							'guide_id'=>$guide_id,
							'type_id'=>$guide_type,
					])->execute();
				}
				
				if((new Query())->from('tours_programs_guides_prices')->where([
						'item_id'=>$item_id,
						'segment_id'=>$segment_id,
						'supplier_id'=>$supplier_id,
						'service_id'=>$guide_id,
						'type_id'=>$guide_type,
				])->count(1) == 0){
					$cprice = Yii::$app->zii->getProgramGuidesPrices([
							'item_id'=>$item_id,
							'controller_code'=>TYPE_ID_GUIDES,
							'service_id'=>$guide_id,
							'loadDefault'=>true,
							'segment_id'=>$segment_id,
							'updateDatabase'=>false
					]);
					$cdays = \app\modules\admin\models\ToursPrograms::getAutoGuideQuantity([
							'item_id'=>$item_id,
							'segment_id'=>$segment_id
					]);
					
					$c = Yii::$app->db->createCommand()->insert('tours_programs_guides_prices',[
							'item_id'=>$item_id,
							'segment_id'=>$segment_id,
							'supplier_id'=>$supplier_id,
							'service_id'=>$guide_id,
							'type_id'=>$guide_type,
							'quantity'=>$quantity,
							'price1'=> (isset($cprice['price1']) ? $cprice['price1'] : 0),
							'currency'=> (isset($cprice['currency']) ? $cprice['currency'] : 1),
							'number_of_day'=>isset($cdays['number_of_day']) ? $cdays['number_of_day'] : 0,
							
					])->execute();
					
				}else{
					
					$c = Yii::$app->db->createCommand()->update('tours_programs_guides_prices',[
							'quantity'=>$quantity,
					],[
							'type_id'=>$guide_type,
							'item_id'=>$item_id,
							'segment_id'=>$segment_id,
							'supplier_id'=>$supplier_id,
							'service_id'=>$guide_id,
							
					])->execute();
				}
			}
		}
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
				'post'=>$quantity,
				'callback_function'=>'reloadAutoPlayFunction(true);'
		]); exit;
		break;
	case 'add-more-tours-program-guides':
		$id = post('id',0);
		$day = post('day',0);
		$time = post('time',0);
		$item_id = post('item_id',$id);
		$segment_id = post('segment_id',0);
		$guide_type = post('guide_type',2);
		$guide_language = post('guide_language',DEFAULT_LANG);
		$html = '';
		
		$html .= '<div class="hide">
				<label data-toggle="tooltip" title="HDV từng chặng sẽ chọn ở từng chặng riêng biệt." >
				<input readonly type="radio" name="guide_type" value="2" '.($guide_type == 2 ? 'checked' : '').'/> HDV Từng chặng</label>
						
				<label data-toggle="tooltip" title="HDV suốt tuyến chỉ được chọn 1 lần duy nhất ở chặng đầu tiên của tour." class="mgr15">
				<input readonly type="radio" name="guide_type" value="1" '.($guide_type == 1 ? 'checked' : '').'/> HDV Suốt tuyến</label>
						
						
						
				</div>
				<table class="table table-bordered vmiddle"><thead><tr>
						
				<th class="center bold col-ws-6">Danh sách đã chọn</th>
				<th class="center bold col-ws-6">Danh sách có thể chọn</th>
				</tr></thead><tbody>';
		$html .= '<tr class="vtop">
				<td class="hide">
				<ul class="style-none ul-style-l01">';
		//foreach (showListChooseService() as $k=>$v){
		$html .= '<li class="li-service-first-child"><a data-day="0" data-time="-1" data-id="'.TYPE_ID_GUIDES.'" onclick="return change_selected_tour_service_group(this);" href="#"></a></li>';
		//}
		$services = \app\modules\admin\models\ToursPrograms::getProgramGuides([
				'item_id'=>$item_id,
				'guide_type'=>$guide_type,
				'segment_id'=>$segment_id,
		]);
		$html .= '</ul>
				
				</td>
				<td class="">
				
				<ul id="sortable1" class="connectedSortable style-none">';
		if(!empty($services)){
			foreach ($services as $kv=>$sv){
				$package = \app\modules\admin\models\PackagePrices::getItem($sv['package_id']);
				$html .= '<li data-package_id="'.$sv['package_id'].'" data-type_id="'.$sv['type_id'].'" data-id="'.$sv['id'].'" class="ui-state-default">
						<div class="col-sm-8 col-index-1 col-border-right">
						'.(!empty($package) ? '<i class="underline green">['.uh($package['title']).']</i>&nbsp;' : '').(isset($sv['title']) ? uh($sv['title']) : uh($sv['name'])).(isset($sv['supplier_name']) ? ' <i class="underline font-normal green"> ['.uh($sv['supplier_name']).']</i>' : '').'
								
						<input value="'.$sv['id'].'" type="hidden" class="selected_value_xc selected_value_'.$sv['type_id'].' selected_value_'.$sv['type_id'].'_'.$segment_id.'_'.$time.'" name="selected_value[]"/>
						<input value="'.$sv['type_id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_type_id[]"/>
						<input value="'.$sv['package_id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_package_id[]"/>
						</div>
						<div class="col-sm-4 col-index-2"><input type="number" class="form-control center number-format selected_quantity" name="selected_quantity['.$sv['id'].']" data-name="selected_quantity['.$sv['id'].']" placeholder="Số lượng" value="'.$sv['quantity'].'"/></div>
								
								</li>';
			}
		}
		$place = [];
		if(post('place_id') > 0){
			$place = \app\modules\admin\models\DeparturePlaces::getItem(post('place_id'));
		}
		$html .= '</ul>
				</td>
				<td class="">
<div class="div-quick-search-service">
						<div class=" col-sm-4"><div class="row">
						<select data-language="'.$guide_language.'" data-placeholder="Chọn địa danh" onchange="quick_search_tour_service(\'.input-quick-search-service\');" data-action="load_dia_danh" data-role="load_dia_danh" class="form-control input-sm ajax-chosen-select-ajax input-quick-search-local">';
		if(!empty($place)){
			$html .= '<option value="'.$place['id'].'" selected>'.$place['name'].'</option>';
		}
		$html .= '</select>
						</div></div>
				
						<div class=" col-sm-4"><div class="row">
						<select data-language="'.$guide_language.'" data-placeholder="Chọn ngôn ngữ" data-search="hidden" onchange="quick_search_tour_service(\'.input-quick-search-service\');" class="form-control chosen-select input-quick-search-language"><option></option>';
		foreach (\app\modules\admin\models\AdLanguage::getList() as $lang){
			$html .= '<option '.($lang['code'] == $guide_language ? 'selected' : '').' value="'.$lang['code'].'">'.$lang['title'].'</option>';
		}
		$html .= '</select>
						</div></div>
				
						<div class=" col-sm-4"><div class="row">
						<input data-language="'.$guide_language.'" data-time="'.$time.'" data-day="'.$segment_id.'" data-type_id="'.TYPE_ID_GUIDES.'" type="text" onkeyup="quick_search_tour_service(this);" onkeypress="return disabledFnKey(this);" placeholder="Tìm kiếm nhanh" class="form-control input-quick-search-service"/>
								</div></div>
								
								</div>
<div class="fl100"><div class="available_services div-slim-scroll" data-height="auto">
								
<ul id="sortable2" class="connectedSortable style-none">
								
</ul></div></div>
								
				</td>
				</tr>';
		
		
		$html .= '</tbody></table>';
		
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Đóng</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		//$r['event'] = $_POST['action'];
		echo json_encode([
				'html'=>$html,
				'event'=>$_POST['action'],
				'callback'=>true,
				'callback_function'=>'loadScrollDiv();loadSelectTagsinput1();reloadTooltip();jQuery(\'.li-service-first-child a\').click();
				jQuery("#sortable2").sortable({connectWith: ".connectedSortable",
receive:function(event,ui){
				$type_id = ui.item.attr(\'data-type_id\');
				$id = ui.item.attr(\'data-id\');
				$package_id = ui.item.attr(\'data-package_id\');
				(ui.item).addClass(\'ui-state-highlight\')
				.removeClass(\'ui-state-default\')
				.find(\'input, .col-removed\').remove();
				(ui.item).addClass(\'ui-state-highlight\')
				.append(\'<input value="\'+$package_id+\'" type="hidden" class="removed_value_\'+$type_id+\'" name="removed_package_id[]"/>\')
				.append(\'<input value="\'+$id+\'" type="hidden" class="removed_value_\'+$type_id+\'" name="removed_item_id[]"/>\')
				.append(\'<input value="\'+$type_id+\'" type="hidden" class="removed_value_\'+$type_id+\'" name="removed_type_id[]"/>\')
				
},
				
}).disableSelection();
jQuery("#sortable1").sortable({connectWith: ".connectedSortable",
receive:function(event,ui){
				$type_id = ui.item.attr(\'data-type_id\');
				$id = ui.item.attr(\'data-id\');
				$package_id = ui.item.attr(\'data-package_id\');
				$ap = \'<input value="\'+$package_id+\'" type="hidden" class="selected_value_\'+$type_id+\'" name="selected_package_id[]"/><input value="\'+$id+\'" type="hidden" class="selected_value_xc selected_value_\'+$type_id+\' selected_value_\'+$type_id+\'_'.$day.'_'.$time.' " name="selected_value[]"/><input value="\'+$type_id+\'" type="hidden" class="selected_value_\'+$type_id+\'" name="selected_type_id[]"/>\';
				$ap += \'<div class="col-sm-4 col-index-2 col-removed"><input type="number" class="form-control required center number-format selected_quantity" name="selected_quantity[\'+$id+\']" data-name="selected_quantity[\'+$id+\']" placeholder="Số lượng" value="'.(\app\modules\admin\models\ToursPrograms::getNumberOfGuides([
						'item_id'=>$item_id,
						'segment_id'=>$segment_id,
						
				])).'"/></div>\';
				ui.item.removeClass(\'ui-state-highlight\').addClass(\'ui-state-default\')
				.append($ap);
				ui.item.find(\'.removed_value_\'+$type_id).remove();
},
change:function(event,ui){
				//console.log(ui.item.index())
				//(ui.item).removeClass(\'ui-state-highlight\').addClass(\'ui-state-default\')
},
	start: function(event, ui) {
				
        //console.log("Start position: " + ui.item.index());
    },
				stop: function(event, ui) {
				
       // console.log("New position: " + ui.item.index());
    }
}).disableSelection();jQuery(\'.input-quick-search-language\').change();'
				//'alert'=>$state ? '' : 'Mã tour không hợp lệ hoặc đã được sử dụng.',
		]+$_POST);
		exit;
		break;
		
	case 'add-tours-services':
		$id = post('id',0);
		$day = post('day',0);
		$time = post('time',0);
		$item_id = post('item_id',$id);
		$segment_id = post('segment_id',0);
		
		$html = '';
		
		$html .= '<p class="help-block">Bạn đang chọn dịch vụ cho ngày thứ <b class="red">'.(post('day',0)+1).'</b> - buổi <b class="green underline">'.showPartDay(post('time',0)).'</b></p>
				<table class="table table-bordered vmiddle"><thead><tr>
				<th class="center bold col-ws-2">Dịch vụ</th>
				<th class="center bold col-ws-4">Dịch vụ đã chọn</th>
				<th class="center bold col-ws-4">Dịch vụ có thể chọn</th>
				</tr></thead><tbody>';
		$html .= '<tr class="vtop">
				<td class="">
				<ul class="style-none ul-style-l01">';
		foreach (showListChooseService() as $k=>$v){
			if(!in_array($v['id'], [TYPE_CODE_DISTANCE,TYPE_ID_GUIDES])){
				switch ($v['id']){
					case TYPE_ID_TRAIN:
						$html .= '<li class="'.($k==0 ? 'li-service-first-child' : '').'">
						<a data-item_id="'.$item_id.'" 
data-day="'.$day.'" 
data-time="'.$time.'" 
data-id="'.$v['id'].'"
data-type_id="'.$v['id'].'"
data-action="Tour_open_form_change_selected_service_day"
onclick="Tour_change_selected_tour_service_day(this);return false;" href="#">'.$v['title'].'</a></li>';
						break;
					default:
						$html .= '<li class="'.($k==0 ? 'li-service-first-child' : '').'">
						<a data-item_id="'.$item_id.'" data-day="'.$day.'" data-time="'.$time.'" data-id="'.$v['id'].'"
						onclick="return change_selected_tour_service_group(this);" href="#">'.$v['title'].'</a></li>';
						break;
				}
				
			}
			
		}
		$services = \app\modules\admin\models\ToursPrograms::getProgramServices($id,$day,$time);
		$html .= '</ul>
				
				</td>
				<td class="div-quick-search-service-col-left">
				
				<ul id="sortable1" class="connectedSortable style-none">';
		if(!empty($services)){
			foreach ($services as $kv=>$sv){
				$package = \app\modules\admin\models\PackagePrices::getItem($sv['package_id']);
				switch ($sv['type_id']){
					case TYPE_ID_GUIDES:
						$html .= '
							<li data-package_id="'.$sv['package_id'].'" data-type_id="'.$sv['type_id'].'" data-id="'.$sv['id'].'" class="ui-state-default">
							<div class="col-sm-8 col-index-1 col-border-right">
									'.(!empty($package) ? '<i class="underline green">['.uh($package['title']).']</i>&nbsp;' : '').(isset($sv['title']) ? uh($sv['title']) : uh($sv['name'])).(isset($sv['supplier_name']) ? ' <i class="underline font-normal green">['.uh($sv['supplier_name']).']</i>' : '').' </div>
											
							<input value="'.$sv['id'].'" type="hidden" class="selected_value_'.$sv['type_id'].' selected_value_'.$sv['type_id'].'_'.$day.'_'.$time.'" name="selected_value[]"/>
						<input value="'.$sv['type_id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_type_id[]"/>
						<input value="'.$sv['package_id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_package_id[]"/>
							<div class="col-sm-4 col-index-2 col-removed">
							<input type="number"
								class="form-control required center number-format selected_quantity"
								name="selected_quantity['.$sv['id'].']" data-name="selected_quantity['.$sv['id'].']"
								placeholder="Số lượng"
								value="'.$sv['quantity'].'"></div></li>';
						break;
					case TYPE_ID_TRAIN:
						$ticket = \app\modules\admin\models\Tickets::getTrainTicketDetail($sv['id']);
						$html .= '<li data-package_id="'.$sv['package_id'].'" 
data-type_id="'.$sv['type_id'].'" data-id="'.$sv['id'].'" 
class="ui-state-default">

'
.(isset($sv['title']) ? uh($sv['title']) : uh($sv['name'])).(isset($sv['supplier_name']) ? 
' <i class="underline font-normal green">['.uh($sv['supplier_name']).']</i>' : '').
(!empty($ticket) && isset($ticket['supplier']['name']) ? '<i class="green">&nbsp; | '.uh($ticket['supplier']['name']).'</i>&nbsp;' : '').
(!empty($ticket) && isset($ticket['room']['title']) ? '<i class="green">&nbsp; | '.uh($ticket['room']['title']).'</i>&nbsp;' : '').
'
						<input value="'.$sv['id'].'" type="hidden" class="selected_value_'.$sv['type_id'].' selected_value_'.$sv['type_id'].'_'.$day.'_'.$time.'" name="selected_value[]"/>
						<input value="'.$sv['type_id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_type_id[]"/>
						<input value="'.$sv['package_id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_package_id[]"/>
						</li>';
						break;
						
						
						
					default:
						$html .= '<li data-package_id="'.$sv['package_id'].'" data-type_id="'.$sv['type_id'].'" data-id="'.$sv['id'].'" class="ui-state-default">'.(!empty($package) ? '<i class="underline green">['.uh($package['title']).']</i>&nbsp;' : '').(isset($sv['title']) ? uh($sv['title']) : uh($sv['name'])).(isset($sv['supplier_name']) ? ' <i class="underline font-normal green">['.uh($sv['supplier_name']).']</i>' : '').'
						<input value="'.$sv['id'].'" type="hidden" class="selected_value_'.$sv['type_id'].' selected_value_'.$sv['type_id'].'_'.$day.'_'.$time.'" name="selected_value[]"/>
						<input value="'.$sv['type_id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_type_id[]"/>
						<input value="'.$sv['package_id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_package_id[]"/>
						</li>';
						break;
				}
				 
			}
		}
		$place = [];
		if(post('place_id') > 0){
			$place = \app\modules\admin\models\DeparturePlaces::getItem(post('place_id'));
		}
		$html .= '</ul>
		</td>
		<td class="">
<div class="div-quick-search-service div-quick-search-service-col-right">
		<div class="fl w50 ex-service-select-dropdown">
		<select data-placeholder="Chọn địa danh" onchange="quick_search_tour_service(\'.input-quick-search-service\');" data-action="load_dia_danh" data-role="load_dia_danh" class="form-control input-sm ajax-chosen-select-ajax input-quick-search-local">';
		if(!empty($place)){
			$html .= '<option value="'.$place['id'].'" selected>'.$place['name'].'</option>';
		}
		$html .= '</select>
		</div><div class="fl w50">
		<input data-item_id="'.$item_id.'" data-time="'.$time.'" data-day="'.$day.'" data-type_id="'.TYPE_ID_HOTEL.'" type="text" onkeyup="quick_search_tour_service(this);" onkeypress="return disabledFnKey(this);" placeholder="Tìm kiếm nhanh" class="form-control input-quick-search-service"/></div></div>
<div class="fl100"><div class="available_services div-slim-scroll" data-height="auto">
				
<ul id="sortable2" class="connectedSortable style-none">
				
</ul></div></div>
				
				</td>
				</tr>';
		
		
		$html .= '</tbody></table>';
		
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Đóng</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		//$r['event'] = $_POST['action'];
		echo json_encode([
				'html'=>$html,
				'event'=>$_POST['action'],
				'callback'=>true,
				'callback_function'=>'load_chosen_select();loadScrollDiv();loadSelectTagsinput1();jQuery(\'.li-service-first-child a\').click();jQuery("#sortable2").sortable({connectWith: ".connectedSortable",
receive:function(event,ui){
				$type_id = ui.item.attr(\'data-type_id\');
				$id = ui.item.attr(\'data-id\');
				$package_id = ui.item.attr(\'data-package_id\');
				(ui.item).addClass(\'ui-state-highlight\')
				.removeClass(\'ui-state-default\')
				.find(\'input\').remove();
				(ui.item).find(\'.col-removed\').remove();
				(ui.item).addClass(\'ui-state-highlight\')
				.append(\'<input value="\'+$package_id+\'" type="hidden" class="removed_value_\'+$type_id+\'" name="removed_package_id[]"/>\')
				.append(\'<input value="\'+$id+\'" type="hidden" class="removed_value_\'+$type_id+\'" name="removed_item_id[]"/>\')
				.append(\'<input value="\'+$type_id+\'" type="hidden" class="removed_value_\'+$type_id+\'" name="removed_type_id[]"/>\')
				
},
				
}).disableSelection();
jQuery("#sortable1").sortable({connectWith: ".connectedSortable",
receive:function(event,ui){
				$type_id = ui.item.attr(\'data-type_id\');
				$id = ui.item.attr(\'data-id\');
				$package_id = ui.item.attr(\'data-package_id\');
				var $ap = \'<div class="col-sm-4 col-index-2 col-removed"><input type="number" class="form-control required center number-format selected_quantity" name="selected_quantity[\'+$id+\']" data-name="selected_quantity[\'+$id+\']" placeholder="Số lượng" value=""/></div>\';
				ui.item.removeClass(\'ui-state-highlight\').addClass(\'ui-state-default\')
				.append(\'<input value="\'+$package_id+\'" type="hidden" class="selected_value_\'+$type_id+\'" name="selected_package_id[]"/><input value="\'+$id+\'" type="hidden" class="selected_value_\'+$type_id+\' selected_value_\'+$type_id+\'_'.$day.'_'.$time.' " name="selected_value[]"/><input value="\'+$type_id+\'" type="hidden" class="selected_value_\'+$type_id+\'" name="selected_type_id[]"/>\')
				if($type_id == '.TYPE_ID_GUIDES.'){
					ui.item.append($ap)
				}
				ui.item.find(\'.removed_value_\'+$type_id).remove();
},
change:function(event,ui){
				//console.log(ui.item.index())
				//(ui.item).removeClass(\'ui-state-highlight\').addClass(\'ui-state-default\')
},
	start: function(event, ui) {
				
        //console.log("Start position: " + ui.item.index());
    },
				stop: function(event, ui) {
				
        //console.log("New position: " + ui.item.index());
    }
}).disableSelection();'
				//'alert'=>$state ? '' : 'Mã tour không hợp lệ hoặc đã được sử dụng.',
		]+$_POST);
		exit;
		break;
		
	case 'add-more-distance-supplier':
		
		$id = post('id',0);
		$day = post('day',0);
		$time = post('time',0);
		$segment_id = post('segment_id',0);
		$html = '';
		
		$html .= '
				<table class="table table-bordered vmiddle"><thead><tr>
				
				<th class="center bold col-ws-6">Danh sách đã chọn</th>
				<th class="center bold col-ws-6">Danh sách có thể chọn</th>
				</tr></thead><tbody>';
		$html .= '<tr class="vtop">
				
				<td class="">
				
				<ul id="sortable1" class="connectedSortable style-none">';
		$services = Yii::$app->zii->getTourProgramSuppliers($id,['segment_id'=>$segment_id]);
		if(!empty($services)){
			foreach ($services as $kv=>$sv){
				$html .= '<li data-type_id="'.$sv['type_id'].'" data-id="'.$sv['id'].'" class="ui-state-default">'.(isset($sv['title']) ? uh($sv['title']) : uh($sv['name'])).(isset($sv['supplier_name']) ? ' <i class="underline font-normal green">['.uh($sv['supplier_name']).']</i>' : '').'
									<input value="'.$sv['id'].'" type="hidden" class="selected_value_'.$sv['type_id'].' selected_value_'.$sv['type_id'].'_'.$day.'_'.$time.'" name="selected_value[]"/>
									<input value="'.$sv['type_id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_type_id[]"/>
									<input value="'.$sv['id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_id[]"/>
											</li>';
			}
		}
		$place = [];
		if(post('place_id') > 0){
			$place = \app\modules\admin\models\DeparturePlaces::getItem(post('place_id'));
		}
		$html .= '</ul>
		</td>
		<td class="">
<div class="div-quick-search-service">
		<div title="Chọn địa danh" class="fl w50">
		<select data-placeholder="Chọn địa danh" data-segment_id="'.$segment_id.'" onchange="quick_search_tour_service(\'.input-quick-search-service\');" data-action="load_dia_danh" data-role="load_dia_danh" class="form-control input-sm ajax-chosen-select-ajax input-quick-search-local">';
		if(!empty($place)){
			$html .= '<option value="'.$place['id'].'" selected>'.$place['name'].'</option>';
		}
		$html .= '</select>
		</div><div class="fl w50">
		<input data-segment_id="'.$segment_id.'" data-time="'.$time.'" data-day="'.$day.'" data-type_id="'.TYPE_ID_VECL.'" type="text" onkeyup="quick_search_tour_service(this);" onkeypress="return disabledFnKey(this);" placeholder="Tìm kiếm nhanh" class="form-control input-quick-search-service"/></div></div>
<div class="fl100"><div class="available_services div-slim-scroll" data-height="auto">
				
<ul id="sortable2" class="connectedSortable style-none">
				
</ul></div>	</div>
				</td>
				</tr>';
		
		
		$html .= '</tbody></table>';
		
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Đóng</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		//$r['event'] = $_POST['action'];
		echo json_encode([
				'html'=>$html,
				'event'=>$_POST['action'],
				'callback'=>true,
				'callback_function'=>'loadScrollDiv();quick_search_tour_service(\'.input-quick-search-service\');loadSelectTagsinput1();jQuery(\'.li-service-first-child a\').click();jQuery("#sortable2").sortable({connectWith: ".connectedSortable",
receive:function(event,ui){
				(ui.item).addClass(\'ui-state-highlight\').removeClass(\'ui-state-default\')
				.find(\'input\').remove()
},
				
}).disableSelection();
jQuery("#sortable1").sortable({connectWith: ".connectedSortable",
receive:function(event,ui){
				$type_id = ui.item.attr(\'data-type_id\');
				$id = ui.item.attr(\'data-id\');
				(ui.item).removeClass(\'ui-state-highlight\').addClass(\'ui-state-default\')
				.append(\'<input value="\'+$id+\'" type="hidden" class="selected_value_\'+$type_id+\' selected_value_\'+$type_id+\'_'.$day.'_'.$time.' " name="selected_value[]"/><input value="\'+$type_id+\'" type="hidden" class="selected_value_\'+$type_id+\'" name="selected_type_id[]"/>\')
},
change:function(event,ui){
				//console.log(ui.item.index())
				//(ui.item).removeClass(\'ui-state-highlight\').addClass(\'ui-state-default\')
},
	start: function(event, ui) {
				
        //console.log("Start position: " + ui.item.index());
    },
				stop: function(event, ui) {
				
       // console.log("New position: " + ui.item.index());
    }
}).disableSelection();'
				//'alert'=>$state ? '' : 'Mã tour không hợp lệ hoặc đã được sử dụng.',
		]+$_POST);
		exit;
		break;
	case 'quick-quick-edit-supplier-services':
		//
		$selected_value = post('selected_value',[]);
		$selected_quantity = post('selected_quantity');
		$supplier_id = post('supplier_id');
		$item_id = post('item_id');
		$segment_id = post('segment_id',0);
		//
		$l1 = (new Query())->from('tours_programs_suppliers_vehicles')->where(['and',[
				'supplier_id'=>$supplier_id,
				'item_id'=>$item_id,
				'segment_id'=>$segment_id
				
		],['not in','vehicle_id',$selected_value]])->all();
		Yii::$app->db->createCommand()->delete('tours_programs_suppliers_vehicles',['and',[
				'supplier_id'=>$supplier_id,
				'item_id'=>$item_id,
				'segment_id'=>$segment_id
				
		],['not in','vehicle_id',$selected_value]])->execute();
		
		if(!empty($l1)){
			foreach ($l1 as $k1=>$v1){
				Yii::$app->db->createCommand()->delete('tours_programs_suppliers_prices',[
						'supplier_id'=>$supplier_id,
						'item_id'=>$item_id,
						'segment_id'=>$segment_id,
						'vehicle_id'=>$v1['vehicle_id']
						
				])->execute();
			}
		}
		
		//
		if(!empty($selected_value)){
			foreach ($selected_value as $k=>$v){
				if(!$selected_quantity[$k]>0){
					$selected_quantity[$k] = 0;
				}
				if((new Query())->from('tours_programs_suppliers_vehicles')->where([
						'supplier_id'=>$supplier_id,
						'item_id'=>$item_id,
						'segment_id'=>$segment_id,
						'vehicle_id'=>$v])->count(1) == 0){
						Yii::$app->db->createCommand()->insert('tours_programs_suppliers_vehicles',[
								'supplier_id'=>$supplier_id,
								'item_id'=>$item_id,
								'vehicle_id'=>$v,
								'segment_id'=>$segment_id,
								'quantity'=>$selected_quantity[$k],
						])->execute();
				}else{
					Yii::$app->db->createCommand()->update('tours_programs_suppliers_vehicles',[
							'quantity'=>$selected_quantity[$k],
							//'type_id'=>TYPE_ID_VECL
					],['supplier_id'=>$supplier_id,
							'item_id'=>$item_id,
							'segment_id'=>$segment_id,
							'vehicle_id'=>$v])->execute();
				}
			}
		}//
		loadTourProgramGuides($item_id,[
				'loadDefault'=>true,
				'updateDatabase'=>true,
		]);
		//
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
				//	'post'=>$_POST,
				'callback_function'=>'reloadAutoPlayFunction(true);'
		]); exit;
		break;
	case 'quickGetAutoVehicleAjax':
		$a = [
		'supplier_id', 'item_id', 'total_pax' , 'nationality_id'
				];
		foreach ($a as $b){
			$$b = post($b,0);
		}
		$l = Yii::$app->zii->getVehicleAuto([
				'total_pax'=>$total_pax,
				'nationality_id'=>$nationality_id,
				'supplier_id'=>$supplier_id,
				'auto'=>true,
				
		]);
		$html = ''; $sl = [];
		if(!empty($l)){
			foreach ($l as $kv=>$sv){
				$sv['type_id'] = TYPE_CODE_VEHICLE;
				$html .= '<li style="background:gold" data-type_id="'.$sv['type_id'].'" data-id="'.$sv['id'].'" class="ui-state-default li_child_item_id_'.$sv['id'].'">
				<div class="col-sm-8 col-index-1 col-border-right">Chọn tự động: '.uh($sv['title']).' <i class="underline font-normal green">['.uh($sv['maker_title']).']</i></div>
				<div class="col-sm-4 col-index-2"><input type="number" class="form-control center number-format selected_quantity" name="selected_quantity[]" data-name="selected_quantity[]" placeholder="Số lượng" value="'.$sv['quantity'].'"/></div>
				<input value="'.$sv['id'].'" type="hidden" class="selected_value_'.$sv['type_id'].' selected_value_'.$sv['type_id'].'_0_0" name="selected_value[]"/>
				<input value="'.$sv['type_id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_type_id[]"/>
				</li>';
				$sl[] = '.li_child_item_id_'.$sv['id'];
			}
		}
		echo json_encode(['html'=>$html,'remove_item'=>implode(',', $sl)]+$_POST);exit;
		break;
	case 'quick-edit-supplier-services':
		$segment_id = post('segment_id',0);
		$item_id = post('item_id',0);
		$day = post('day',0);
		$time = post('time',0);
		$supplier_id = post('supplier_id',0);
		$item = \app\modules\admin\models\ToursPrograms::getItem($item_id);
		$services = Yii::$app->zii->getSelectedVehicles([
				//'totalPax'=>post('total_pax',0),
				//'nationality'=>post('nationality',0),
				'supplier_id'=>$supplier_id,
				'item_id'=>$item_id,
				'segment_id'=>$segment_id,
				'default'=>false,
				
				////'auto'=>true,
				//'update'=>true,
		]);
		
		/*
		 echo json_encode([
		 'callback'=>true,
		 'callback_function'=>'console.log(data)',
		 's'=>$_POST,
		 'ds'=>$services
		 ]); exit;
		 */
		$html = '';
		
		$html .= '
				<table class="table table-bordered vmiddle"><thead><tr>
				
				<th class="center bold col-ws-6">Danh sách đã chọn</th>
				<th class="center bold col-ws-6">Danh sách có thể chọn</th>
				</tr></thead><tbody>';
		$html .= '<tr class="vtop">
				
				<td class="">
		<div class="col-sm-8 bold col-border-left col-border-top col-border-right pd8">Tên phương tiện</div>
		<div class="center col-sm-4 bold col-border-right col-border-top pd8">Số lượng</div>
				<ul id="sortable1" class="connectedSortable style-none ajax-result-get-auto-b3091">';
		if(!empty($services)){
			foreach ($services as $kv=>$sv){
				$sv['type_id'] = TYPE_CODE_VEHICLE;
				$html .= '<li data-type_id="'.$sv['type_id'].'" data-id="'.$sv['id'].'" class="ui-state-default">
						
						<div class="col-sm-8 col-index-1 col-border-right">'.uh($sv['title']).' <i class="underline font-normal green">['.uh($sv['maker_title']).']</i></div>
								<div class="col-sm-4 col-index-2"><input type="number" class="form-control center number-format selected_quantity" name="selected_quantity[]" data-name="selected_quantity[]" placeholder="Số lượng" value="'.$sv['quantity'].'"/></div>
										
									<input value="'.$sv['id'].'" type="hidden" class="selected_value_'.$sv['type_id'].' selected_value_'.$sv['type_id'].'_'.$day.'_'.$time.'" name="selected_value[]"/>
									<input value="'.$sv['type_id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_type_id[]"/>
											
				</li>';
			}
		}
		
		$place = [];
		if(post('place_id') > 0){
			$place = \app\modules\admin\models\DeparturePlaces::getItem(post('place_id'));
		}
		$html .= '</ul>
				</td>
				<td class="">
<div class="div-quick-search-service">
						<div title="Chọn địa danh" class="hide">
						<select data-placeholder="Chọn địa danh" onchange="quick_search_tour_service(\'.input-quick-search-service\');" data-action="load_dia_danh" data-role="load_dia_danh" class="form-control input-sm ajax-chosen-select-ajax input-quick-search-local"></select>
						</div><div class="fl w100">
						<input data-supplier_id="'.$supplier_id.'" data-time="'.$time.'" data-day="'.$day.'" data-type_id="'.TYPE_CODE_VEHICLE.'" type="text" onkeyup="quick_search_tour_service(this);" onkeypress="return disabledFnKey(this);" placeholder="Tìm kiếm nhanh" class="form-control input-quick-search-service"/></div></div>
<div class="fl100"><div class="available_services div-slim-scroll" data-height="auto">
								
<ul id="sortable2" class="connectedSortable style-none">
								
</ul></div></div>
				</td>
				</tr>';
		
		
		$html .= '</tbody></table>';
		
		$html .= '<div class="modal-footer">';
		$html .= '<button
				data-toggle="tooltip"
				title="Chọn lại danh sách xe cho nhà xe này. Loại xe và số lượng xe sẽ được lấy tự động từ hệ thống."
				onclick="quickGetAutoVehicleAjax(this)"
				data-target=".ajax-result-get-auto-b3091"
				data-supplier_id="'.$supplier_id.'"
				data-item_id="'.$item_id.'"
				data-total_pax="'.$item['guest'].'"
				data-nationality_id="'.$item['nationality'].'"
				type="button" class="btn btn-success"><i class="fa fa-hand-lizard-o"></i> Chọn tự động</button><button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Đóng</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		//$r['event'] = $_POST['action'];
		echo json_encode([
				'html'=>$html,
				'event'=>$_POST['action'],
				'callback'=>true,
				'callback_function'=>'reloadTooltip();loadScrollDiv();quick_search_tour_service(\'.input-quick-search-service\');loadSelectTagsinput1();jQuery(\'.li-service-first-child a\').click();jQuery("#sortable2").sortable({connectWith: ".connectedSortable",
receive:function(event,ui){
				var $type_id = ui.item.attr(\'data-type_id\');
				(ui.item).addClass(\'ui-state-highlight\').removeClass(\'ui-state-default\')
				.find(\'input.selected_value_\'+$type_id+\'\').remove();
				$iq = ui.item.find(\'input.selected_quantity\');
				$iq.removeAttr(\'name\');
},
				
}).disableSelection();
jQuery("#sortable1").sortable({connectWith: ".connectedSortable",
receive:function(event,ui){
				var $type_id = ui.item.attr(\'data-type_id\');
				var $id = ui.item.attr(\'data-id\');
				(ui.item).removeClass(\'ui-state-highlight\').addClass(\'ui-state-default\')
				.append(\'<input value="\'+$id+\'" type="hidden" class="selected_value_\'+$type_id+\' selected_value_\'+$type_id+\'_'.$day.'_'.$time.' " name="selected_value[]"/><input value="\'+$type_id+\'" type="hidden" class="selected_value_\'+$type_id+\'" name="selected_type_id[]"/>\')
				.append(\'\');
				$iq = ui.item.find(\'input.selected_quantity\');
				$iq.attr(\'name\',$iq.attr(\'data-name\'));
},
change:function(event,ui){
				//console.log(ui.item.index())
				//(ui.item).removeClass(\'ui-state-highlight\').addClass(\'ui-state-default\')
},
	start: function(event, ui) {
				
       // console.log("Start position: " + ui.item.index());
    },
				stop: function(event, ui) {
				
       // console.log("New position: " + ui.item.index());
    }
}).disableSelection();'
				//'alert'=>$state ? '' : 'Mã tour không hợp lệ hoặc đã được sử dụng.',
		]+$_POST);
		exit;
		break;
	case 'getExchangeRateToday':
		$price = Yii::$app->zii->getExchangeRate(post('from'),post('to'));
		echo json_encode([
				'price'=>$price
		]);exit;
		
		break;
	case 'quick-change-exchange-rate':
		$price = post('price');
		$item_id = post('item_id');$callback_function = '';
		foreach ($price as $from=>$v){
			foreach ($v as $to=>$value){
				if((new Query())->from('tours_programs_exchange_rate')->where([
						'item_id'=>$item_id,
						'from_currency'=>$from,
						'to_currency'=>$to
				])->count(1) == 0){
					Yii::$app->db->createCommand()->insert('tours_programs_exchange_rate',[
							'item_id'=>$item_id,
							'from_currency'=>$from,
							'to_currency'=>$to,
							'value'=>$value
					])->execute();
				}else{
					Yii::$app->db->createCommand()->update('tours_programs_exchange_rate',[
							'value'=>$value
					],[
							'item_id'=>$item_id,
							'from_currency'=>$from,
							'to_currency'=>$to,
							
					])->execute();
				}
				$ex[$from] = $value;
				$ex['note'] = 'Cập nhật ' . date('d/m/Y H:i:s');
				\app\modules\admin\models\Siteconfigs::updateBizrule('tours_programs',[
						'id'=>$item_id,
						
				],[
						'exchange_rate'=>$ex
				]);
				//convert-exchange_rate_'.$c.'
				$callback_function .= 'jQuery(".convert-exchange_rate_'.$from.'").html(\''.number_format($value).'\');';
				
				
			}
		}
		
		$callback_function .= 'reloadAutoPlayFunction(true);';
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>$callback_function
		]);
		exit;
		break;
		
	case 'change-exchange-rate':
		
		$currency = post('currency');
		$symbol = Yii::$app->zii->showCurrency($currency,1);
		$decimal_number = Yii::$app->zii->showCurrency($currency,3);
		$item_id = post('item_id');
		$c = \app\modules\admin\models\UserCurrency::getItem($currency);
		$html = '';
		
		$html .= '
				<table class="table table-bordered vmiddle"><thead><tr>
				<th class="center bold col-ws-3">Tiền tệ</th>
				<th class="center bold col-ws-4">Tỷ giá</th>
				<th class="center bold col-ws-4">Tỷ giá hôm nay</th>
				 <th class="center bold col-ws-1"></th>
				</tr></thead><tbody>';
		//if(!empty($services)){
		foreach (Yii::$app->zii->getUserCurrency()['list'] as $k1=>$v1){
			if($v1['id'] != $currency){
				$html .= '<tr class="">
						
				<td class="center bold">
		 '.($v1['code'] . ' - ' . $symbol).'
				</td>
				<td class="center bold ">
		 <input type="text" name="price['.$v1['id'].']['.$currency.']" value="'.Yii::$app->zii->getItemExchangeRate(
		 		[
		 				'item_id'=>$item_id,
		 				'from'=>$v1['id'],
		 				'to'=>$currency,
		 				'time'=>post('time')
		 		]).'" class="form-control aright number-format input-currency-exchange-rate" data-decimal="'.($c['decimal_number']).'"/>
				</td>
		 		<td class="center"><b>'.number_format(Yii::$app->zii->getExchangeRate($v1['id'],$currency),$c['decimal_number']).'</b></td>
				 <td class="center"><i data-from="'.$v1['id'].'" data-to="'.$currency.'" onclick="getExchangeRateToday(this);" title="Lấy tỷ giá hôm nay" class="fa fa-refresh pointer"></i></td>
				</tr>';
			}
		}
		//}
		
		$html .= '</tbody></table>';
		
		$html .= '<div class="modal-footer">';
		if($item_id>0){
			$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Cập nhật</button>';
		}
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Đóng</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		//$r['event'] = $_POST['action'];
		echo json_encode([
				'html'=>$html,
				'event'=>$_POST['action'],
				'callback'=>true,
				'callback_function'=>'load_number_format();'
				//'alert'=>$state ? '' : 'Mã tour không hợp lệ hoặc đã được sử dụng.',
		]+$_POST);
		exit;
		break;
	case 'quick-qedit-service-detail-day':
		$f = post('f');
		$biz = post('biz');
		$a = [
				'service_id',
				'id',
				'type_id',
				'package_id',
				'day_id',
				'item_id',
				'supplier_id',
				'service_id',
				'sub_item_id'
				
		];
		foreach ($a as $b){
			$$b = post($b,12);
		}
		$time_id = post('time_id',-1);
		
		
		if((new Query())->from('tours_programs_services_prices')->where([
				'supplier_id'=>$supplier_id,
				'package_id'=>$package_id,
				'item_id'=>$id,
				'service_id'=>$service_id,
				'day_id'=>$day_id,
				'time_id'=>$time_id,
				'type_id'=>$type_id,
				'sub_item_id'=>$item_id,
		])->count(1) == 0){
			$f['bizrule'] = json_encode($biz,JSON_UNESCAPED_UNICODE);
			Yii::$app->db->createCommand()->insert('tours_programs_services_prices',[
					'supplier_id'=>$supplier_id,
					'package_id'=>$package_id,
					'item_id'=>$id,
					'service_id'=>$service_id,
					'day_id'=>$day_id,
					'time_id'=>$time_id,
					'type_id'=>$type_id,
					'sub_item_id'=>$item_id,
					
			]+$f)->execute();
		}else{
			Yii::$app->db->createCommand()->update('tours_programs_services_prices',$f,[
					'supplier_id'=>$supplier_id,
					'package_id'=>$package_id,
					'item_id'=>$id,
					'service_id'=>$service_id,
					'day_id'=>$day_id,
					'time_id'=>$time_id,
					'type_id'=>$type_id,
					'sub_item_id'=>$item_id,
					
			])->execute();
			\app\modules\admin\models\Siteconfigs::updateBizrule('tours_programs_services_prices',[
					'supplier_id'=>$supplier_id,
					'package_id'=>$package_id,
					'item_id'=>$id,
					'service_id'=>$service_id,
					'day_id'=>$day_id,
					'time_id'=>$time_id,
					'type_id'=>$type_id,
					'sub_item_id'=>$item_id,
					
			],$biz);
			
			\app\modules\admin\models\Siteconfigs::updateBizrule('tours_programs_services_days',[
					//'supplier_id'=>$supplier_id,
					//'package_id'=>$package_id,
					'item_id'=>$id,
					'service_id'=>$service_id,
					'day_id'=>$day_id, 
					'time_id'=>$time_id,
					//'type_id'=>$type_id,
					//'sub_item_id'=>$item_id, 
					 
			],$biz); 
		}
		
		
		echo json_encode([
				//'html'=>$html,
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>'reloadAutoPlayFunction(true);'
		]+$_POST);
		exit;
		break;
		
	case 'quick-qedit-service-detail':
		$price = post('price') > 0 ? post('price') : 0;
		if((new Query())->from('tours_programs_suppliers_prices')->where([
				'supplier_id'=>post('supplier_id'),
				'vehicle_id'=>post('vehicle_id'),
				'item_id'=>post('item_id'),
				'service_id'=>post('service_id'),
		])->count(1) == 0){
			Yii::$app->db->createCommand()->insert('tours_programs_suppliers_prices',[
					'supplier_id'=>post('supplier_id'),
					'vehicle_id'=>post('vehicle_id'),
					'item_id'=>post('item_id'),
					'service_id'=>post('service_id'),
					'price1'=>$price	,
					'quantity'=>post('distance',1),
			])->execute();
		}else{
			Yii::$app->db->createCommand()->update('tours_programs_suppliers_prices',[
					
					'price1'=>$price,
					'quantity'=>post('distance',1),
			],[
					'supplier_id'=>post('supplier_id'),
					'vehicle_id'=>post('vehicle_id'),
					'item_id'=>post('item_id'),
					'service_id'=>post('service_id'),
					
					
			])->execute();
		}
		
		
		echo json_encode([
				//'html'=>$html,
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>'reloadAutoPlayFunction(true);'
		]+$_POST);
		exit;
		break;
	case 'reloadDistanceServicePriceAuto':
		$a  = [
		'supplier_id',
		'vehicle_id',
		'service_id',
		'distance_id',
		'item_id',
		
		];
		foreach ($a as $b){
			$$b = post($b,0);
		}
		$item = \app\modules\admin\models\ToursPrograms::getItem($item_id);
		
		$from_date = $item['from_date'];
		
		$quotation = \app\modules\admin\models\Suppliers::getQuotation([
				'supplier_id'=>$supplier_id,
				'date'=>$from_date
		]);
		//view($quotation);
		//
		$nationality_group = \app\modules\admin\models\Suppliers::getNationalityGroup([
				'supplier_id'=>$supplier_id,
				'nationality_id'=>$item['nationality'],
		]);
		//
		$seasons = \app\modules\admin\models\Suppliers::getSeasons([
				'supplier_id'=>$supplier_id,
				
				'date'=>$from_date,
				//'time_id'=>$time_id
		]);
		$groups = \app\modules\admin\models\Suppliers::getGuestGroup([
				'supplier_id'=>$supplier_id,
				'total_pax'=>$item['guest'],
				'date'=>$from_date,
				//'time_id'=>$time_id
		]);
		//
		
		$prices = Yii::$app->zii->calcDistancePrice([
				'supplier_id'=>$supplier_id,
				'vehicle_id'=>$vehicle_id,
				'distance_id'=>$service_id,
				'item_id'=>$item_id,
				'quotation_id'=>isset($quotation['id']) ? $quotation['id'] : 0,
				'nationality_id'=>isset($nationality_group['id']) ? $nationality_group['id'] : 0,
				'season_id'=>isset($seasons['seasons_prices']['id']) ? $seasons['seasons_prices']['id'] : 0,
				
				'total_pax'=>$item['guest'],
				'weekend_id'=>isset($seasons['week_day_prices']['id']) ? $seasons['week_day_prices']['id'] : 0,
				//'package_id'=>0,
				'group_id'=>isset($groups['id']) ? $groups['id'] : 0,
				'loadDefault'=>true,
				'updateDatabase'=>false,
		]);
		echo json_encode($prices);exit;
		break;
		
	case 'reloadServiceDayPriceAuto':
		$a = [
		'service_id',
		'id',
		'type_id',
		'package_id',
		'day_id',
		'item_id','sub_item_id',
		'supplier_id','service_id','nationality_id','total_pax'
				
				];
		foreach ($a as $b){
			$$b = post($b,0);
		}
		$item = \app\modules\admin\models\ToursPrograms::getItem($id);
		$time_id = post('time_id',-1);
		
		$prices = Yii::$app->zii->getServiceDetailPrices([
				'supplier_id'=>$supplier_id,
				'package_id'=>$package_id,
				'type_id'=>$type_id,
				'item_id'=>$id,
				'sub_item_id'=>$sub_item_id,
				'day_id'=>$day_id,
				'time_id'=>$time_id,
				'season_time_id'=>$time_id,
				'service_id'=>$service_id,
				'from_date'=>date('Y-m-d', mktime(0,0,0,
						date('m',strtotime($item['from_date'])),
						date('d',strtotime($item['from_date']))+$day_id,
						date('Y',strtotime($item['from_date'])))),
				'nationality_id'=>$item['nationality'],
				'total_pax'=>$item['guest'],
				'loadDefault'=>true	,
				'updateDatabase'=>false,
		]);
		echo json_encode($prices + [
				'supplier_id'=>$supplier_id,
				'package_id'=>$package_id,
				'type_id'=>$type_id,
				'item_id'=>$id,
				'sub_item_id'=>$sub_item_id,
				'day_id'=>$day_id,
				'time_id'=>$time_id,
				'service_id'=>$service_id,
				'from_date'=>$item['from_date'],
				'nationality_id'=>$item['nationality']	,
				'total_pax'=>$item['guest'],
				'loadDefault'=>true	,
				'updateDatabase'=>false,
		]);exit;
		break;
	case 'qedit-service-detail':
		
		$a  = [
		'supplier_id',
		'vehicle_id',
		'service_id',
		'distance_id',
		'item_id','segment_id'
				
				];
		foreach ($a as $b){
			$$b = post($b,0);
		}
		$prices = Yii::$app->zii->calcDistancePrice([
				'supplier_id'=>$supplier_id,
				'vehicle_id'=>$vehicle_id,
				'service_id'=>$service_id,
				'distance_id'=>$service_id,
				'item_id'=>$item_id,
				'segment_id'=>$segment_id,
				'updateDatabase'=>false,
				'loadDefault'=>false,
				
		]);
		
		//
		$supplier = \app\modules\admin\models\Customers::getItem($supplier_id);
		$vehicle = \app\modules\admin\models\VehiclesCategorys::getItem($vehicle_id);
		$distance = \app\modules\admin\models\Distances::getItem($service_id);
		$html = '';
		
		$html .= '
				<table class="table table-bordered vmiddle"><thead><tr>
				<th class="center bold col-ws-2">Nhà cung cấp</th>
				<th class="center bold col-ws-3">Loại phương tiện</th>
				<th class="center bold col-ws-3">Chặng di chuyển</th>
				<th class="center bold col-ws-1">Khoảng cách (km)</th>
				<th class="center bold col-ws-2">Đơn giá</th>
				<th class="center bold col-ws-1"></th>
				</tr></thead><tbody>';
		//if(!empty($services)){
		//	foreach ($services as $kv=>$sv){
		$html .= '<tr class="">
				
				<td class="center">'.($supplier['title']).'</td>
				<td class="center">'.(isset($vehicle['title']) ? $vehicle['title'] : '-').'</td>
				<td class="center">'.(isset($distance['title']) ? $distance['title'] : '-').'</td>
				<td class="center">
		  '.(isset($prices['price_type']) && $prices['price_type'] == 1 ? '<input name="distance" value="'.$prices['quantity'].'" type="text" class="input-distance-service-distance form-control bold center aright number-format"/>' : '-').'
		  		
		  		</td>
				<td class="">
						<input name="price" value="'.(isset($prices['price1']) ? $prices['price1'] : 0).'" type="text" class="input-distance-service-price form-control bold aright number-format"/>
				</td>
				<td class="center">
						<i data-item_id="'.$item_id.'" data-vehicle_id="'.$vehicle_id.'" data-distance_id="'.$service_id.'" data-service_id="'.$service_id.'" data-supplier_id="'.$supplier_id.'" onclick="reloadDistanceServicePriceAuto(this)" class="fa fa-refresh f12e pointer" title="Tính giá tự động theo số liệu hệ thống"></i>
				</td>
				</tr>';
		//	}
		//}
		
		
		
		$html .= '</tbody></table>';
		
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Đóng</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		//$r['event'] = $_POST['action'];
		echo json_encode([
				'html'=>$html,
				'event'=>$_POST['action'],
				'callback'=>true,
				'callback_function'=>'load_number_format();'
				//'alert'=>$state ? '' : 'Mã tour không hợp lệ hoặc đã được sử dụng.',
		]+$_POST);
		exit;
		break;
	case 'qedit-service-detail-day':
		$a = [
		'service_id',
		'id',
		'type_id',
		'package_id',
		'day_id',
		'item_id',
		'supplier_id','service_id'				
		];
		foreach ($a as $b){
			$$b = post($b,0);
		}
		$time_id = post('time_id',-1);
		
		
		
		
		$package = \app\modules\admin\models\PackagePrices::getItem($package_id);
		
		$prices = Yii::$app->zii->getServiceDetailDayPrices([
				'supplier_id'=>$supplier_id,
				'package_id'=>$package_id,
				'type_id'=>$type_id,
				'item_id'=>$id,
				'sub_item_id'=>$item_id,
				'day_id'=>$day_id,
				'time_id'=>$time_id,
				'service_id'=>$service_id
		]);
		$callback_function .= 'log(\''.json_encode($prices).'\');';
		$sub_item = Yii::$app->zii->getSupplierServiceDetail(isset($prices['sub_item_id']) ? $prices['sub_item_id'] : 0,$type_id);
		$service = \app\modules\admin\models\ToursPrograms::getProgramService($service_id,$type_id);
		
		$html = '';
		
		$html .= '
				<table class="table table-bordered vmiddle"><thead><tr>
				<th class="center bold col-ws-3">Dịch vụ / Nhà cung cấp DV</th>
				<th class="center bold col-ws-3">Chi tiết dịch vụ</th>
				<th class="center bold col-ws-1">Loại DV</th>
				<th class="center bold col-ws-1">ĐVT</th>
				<th class="center bold col-ws-1">Số lượng</th>
				<th class="center bold col-ws-2">Đơn giá (<i class="red underline">'.Yii::$app->zii->showCurrency(isset($prices['currency']) ? $prices['currency'] : 1).'</i>)</th>
				<th class="center bold col-ws-1">Tính lại</th>
				</tr></thead><tbody>';
		//if(!empty($services)){
		//	foreach ($services as $kv=>$sv){
		$html .= '<tr class="">
			<td>'.(!empty($service) ? uh($service['title']) : '').'</td>
				<td class="center " colspan="">';
		switch ($type_id){
			case TYPE_ID_SCEN:
				if(!isset($prices['supplier']['title'])){
					$html .= (isset($sub_item['title']) ? $sub_item['title'] 
					: (!empty($package) ? '<i class="underline green">'.uh($package['title']).'</i>&nbsp;' : ''));
				}
				break;
				
			case TYPE_ID_TRAIN:
				$ticket = \app\modules\admin\models\Tickets::getItem($service_id);
				if(!empty($ticket)){
					$html .= $ticket['title'];			 
				}
				break;
			default:
				$html .= (isset($sub_item['title']) ? $sub_item['title'] : '' ) 
				. (!empty($package) ? '<i class="underline green">&nbsp;['.uh($package['title']).']</i>&nbsp;' : '');
				break;
		}
		$html .= '</td>
				
				<td class="center">
		 		   '.getServiceType(isset($prices['type_id']) ? $prices['type_id'] : 0).'
				</td><td class="center">'.getServiceUnitPrice(isset($prices['type_id']) ? $prices['type_id'] : 0).'</td>
				<td class="center">
						
				<input name="f[quantity]" value="'.(isset($prices['quantity']) ? $prices['quantity'] : 0).'" type="text" class="input-service-day-price-quantity form-control bold center number-format"/>
		  		</td>
				<td class="">
<input data-decimal="'.Yii::$app->zii->showCurrency((isset($prices['currency']) ? $prices['currency'] : 1),3).'" name="f[price1]" value="'.(isset($prices['price1']) ? $prices['price1'] : 0).'" type="text" class="input-distance-service-price form-control bold aright number-format"/>
				</td>
				<td class="center">
						<i data-id="'.$id.'"
								data-type_id="'.$type_id.'"
								data-service_id="'.$service_id.'"
								data-supplier_id="'.$supplier_id.'"
								data-time_id="'.$time_id.'"
								data-day_id="'.$day_id.'"
								data-package_id="'.$package_id.'"
								data-sub_item_id="'.$item_id.'"
								data-toggle="tooltip"
								onclick="reloadServiceDayPriceAuto(this)" 
class="fa fa-refresh f12e pointer" title="Tính giá tự động theo số liệu hệ thống"></i>
				</td>
				</tr>';
		//	}
		//}
		
		$sv = \app\modules\admin\models\ToursPrograms::getProgramServiceDayDetail($id,$day_id,$time_id,$service_id);  
		
		$html .= '<tr><td colspan="7">
<label>Ghi chú:</label>  
<textarea class="form-control" name="biz[note]" placeholder="Thêm ghi chú cho dịch vụ này">'. (isset($sv['note']) ? uh($sv['note']) : '').'</textarea>
</td></tr>';
		
		$html .= '</tbody></table>';
		
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Đóng</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		$callback_function .= 'load_number_format(); ';
		
		echo json_encode([
				'html'=>$html,
				'event'=>$_POST['action'],
				'post'=>$_POST, 
				'callback'=>true,
				'callback_function' => $callback_function,
				//'alert'=>$state ? '' : 'Mã tour không hợp lệ hoặc đã được sử dụng.',
		]+$_POST);
		exit;
		break;
	case 'loadTourProgramDistances':
		
		echo json_encode([
		'html'=>getTourProgramSegments(post('id',0),['updateDatabase'=>true])['html']
		]+$_POST);exit;
		
		
		break;
	case 'loadTourProgramGuides':
		echo json_encode([
		'html'=>loadTourProgramGuides(post('id',0),['updateDatabase'=>true])['html']
		]+$_POST);exit;
		break;
	case 'loadTourProgramDetail':
		$html = '';
		//$model = load_model('tours_programs');
		$id = post('id',0);
		$day = post('day',0);
		$html = loadTourProgramDetail([
				'day'=>$day, 'id'=>$id
		])['html'];
		echo json_encode([
				'html'=>$html,
		]+$_POST);exit;
		
		 
		break;
	case 'loadSupplierHightway':
		$id = post('id',0);
		if($id == 0) {echo json_encode(['html'=>'<p class="help-block">Bạn cần lưu dữ liệu trước khi thêm chặng cao tốc</p>']+$_POST);exit;}
		$existed = [];
		$html = '<div class="col-sm-12 "><div class="row"><p class="grid-sui-pheader bold aleft"><i style="font-weight: normal;">Chặng cao tốc - Đò phà</i></p></div></div>';
		
		
		$model = load_model('distances');
		$v= $model->getItem($id);
		$l3 = $model->get_list_seats();
		$l4 = $model->get_list_hight_way ($id);
		
		$existed = [];
		if(!empty($l3)){
			$html .= '<div class="col-sm-12 "><div class="row"><table class="table table-bordered vmiddle mgt15"><thead>
		<tr>';
			$html .= '<th rowspan="2">Tiêu đề</th>';
			if(!empty($l3)){
				foreach ($l3 as $t3){
					$html .= '<th class="center mw120p">Xe '.$t3.' chỗ</th>';
					
				}
			}
			$html .= '<th rowspan="2" class="center w100p">Tiền tệ</th>';
			$html .= '<th rowspan="2" class="center w100p">Bắt buộc</th>';
			$html .= '<th rowspan="2" class="center w100p">Khứ hồi</th>';
			$html .= '<th rowspan="2" class="center w50p">Xóa</th>';
			$html .= '</tr>
		</thead><tbody>';
			if(!empty($l4)){
				foreach ($l4 as $k4=>$v4){
					//
					//view($v4);
					$currency = 1;
					
					if(!in_array($v4['id'] , $existed)) $existed[] = $v4['id'];
					//
					$html .= '<tr class="tr-distance-id-'.$v4['id'].'"><td><a href="'.(\app\modules\admin\models\AdminMenu::get_menu_link('hight_way',$v4['type_id'])).'/edit?id='.$v4['id'].'#tab-panel-prices" target="_blank">'.$v4['title'].'</a></td>';
					if(!empty($l3)){
						foreach ($l3 as $c3=>$t3){
							$p4 = isset($v4['prices'][$t3]) ? $v4['prices'][$t3] : array() ;//:  $this->model()->get_hight_way_prices($v4['id'],$t3);
							//view($p4);
							if($c3==0 && !empty($p4)){
								$currency = $p4['currency'];
								//$dactive[$v4['id']] = $p4['is_active'];
							}
							$html .= '<td class="aright bold">'.(isset($p4['price1']) ? number_format($p4['price1'],$this->app()->showCurrency($currency,3)) : '').'</td>';
							
						}
					}
					$html .= '<td class="center w100p"><input type="hidden" name="hight_way['.$v4['id'].'][id]" value="'.$v4['id'].'"/>'.$this->app()->showCurrency($currency).'</td>';
					$html .= '<td class="center">'.getCheckBox(array(
							'name'=>'hight_way['.$v4['id'].'][is_required]',
							'value'=>$v4['is_required'],
							'type'=>'singer',
							'class'=>'switchBtn ajax-switch-btn',
							//'cvalue'=>true,
							
					)).'</td>';
					$html .= '<td class="center">'.getCheckBox(array(
							'name'=>'hight_way['.$v4['id'].'][around]',
							'value'=>$v4['around'],
							'type'=>'singer',
							'class'=>'switchBtn ajax-switch-btn',
							//'cvalue'=>true,
							'checked'=>$v4['around'] == 2 ? true : false
							
					)).'</td>';
					$html .= '<td class="center"><i title="Xóa" data-id="'.$v4['id'].'" data-name="delete_high_way" onclick="addToRemove(this);" data-target=".tr-distance-id-'.$v4['id'].'" class="pointer glyphicon glyphicon-trash btn-delete-item"></i></td>';
					$html .= '</tr>';
					
				}}
				$html .= '</tbody></table>';
				
				//$html .= '<p class="aright mgt15">';
				
				//$html .= '<button data-required-save="true" data-type_id="'.TYPE_CODE_ROOM_TRAIN.'" data-existed="'.implode(',', $existed).'" data-supplier_id="'.$id.'" data-title="Thêm giường, ghế" type="button" onclick="open_ajax_modal(this);" data-class="" data-action="add-more-room-to-supplier" class="btn btn-warning btn-data-required-save"><i class="fa fa-plus"></i> Thêm giường, ghế</button></p><p>&nbsp;</p>';
				$html .= '<div class="aright btn-list-add-more-1"><button data-class="w90" data-title="Thêm cao tốc - tàu phà '.(isset($v['title']) ? 'chặng: <b class=red>'.$v['title'].'</b>' : '' ).' " data-action="add-more-hight-way" data-existed="'.implode(',', $existed).'" data-count="'.count($existed).'" type="button" data-type_id="'.TYPE_CODE_HIGHT_WAY.'" data-colspan="'.count($l3).'" data-id="'.$id.'" data-name="dprice" data-target=".ajax-result-more-hight-way" title="Thêm cao tốc" onclick="open_ajax_modal(this);" class="btn btn-warning btn-add-more"><i class=" glyphicon glyphicon-plus"></i> Thêm cao tốc</button></div>';
				$html .= '</div></div>';
		}
		echo json_encode([
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>'reload_app(\'switch-btn\');'
		]+$_POST);exit;
		break;
	case 'quick-add-more-hight-way':
		$r['event'] = 'hide-modal';
		$r['post'] = $_POST;
		$id = post('id',0);
		$items = post('items');
		if(!empty($items)){
			foreach ($items as $item){
				Yii::$app->db->createCommand()->insert('distance_to_places',[
						'distance_id'=>$id,
						'place_id'=>$item,
						'type_id'=>TYPE_CODE_HIGHT_WAY
				])->execute();
			}
		}
		$r['callback']=true;
		$r['callback_function']='loadHtmlData(jQuery(\'.ajax-load-prices\'));';
		echo json_encode($r);exit;
		break;
	case 'add-more-hight-way':
		$r = array(); $r['html'] = '';
		$supplier_id = post('supplier_id',0);
		$m = load_model('distances'); //
		
		
		
		$r['html'] .= '<div class="form-group">
				
          <div class="col-sm-12">
	 <table class="table table-bordered vmiddle mgt15"><thead>
		<tr>
		<th class="center w50p">#</th>
		<th class="center">Tiêu đề </th>
		<th class="center w150p">Chọn</th>
				
		</tr>
		</thead><tbody>';
		foreach ($m->getAll(TYPE_CODE_HIGHT_WAY,['not_in'=>post('existed',[])]) as $k1=>$v1){
			$r['html'] .= '<tr>
		<td class="center">'.($k1+1).'</td>
		<td class=""><a>'.uh($v1['title']) . '</a></td>
		<td class="center "><input type="checkbox" name="items[]" value="'.$v1['id'].'" class=""></td>
				
		</tr>';
		}
		$r['html'] .= '</tbody></table>   </div>
         </div>';
		
		
		
		
		$r['html'] .= '<div class="modal-footer">';
		$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
		$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$r['html'] .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		$r['event'] = $_POST['action'];
		echo json_encode($r);exit;
		
		break;
	case 'loadSupplierRooms':
		$id = post('id',0);
		$existed = [];
		$html = '';
		$m = load_model('rooms_categorys');
		$html .= '<table class="table table-bordered vmiddle mgt15"><thead>
		<tr>
		<th class="center w50p">#</th>
		<th class="center">Tiêu đề </th>
		<th class="center w150p">Số lượng</th>
		<th class="center w100p">Mặc định</th>
		<th class="center w50p"></th>
		</tr>
		</thead><tbody>';
		foreach ($m->getRoomBySupplier($id) as $k1=>$v1){
			$existed[] = $v1['id'];
			$html .= '<tr>
		<td class="center">'.($k1+1).'</td>
		<td class=""><a>'.uh($v1['title']) . ($v1['note'] != "" ? ' ('.uh($v1['note']).')' : '').'</a></td>
		<td class="center "><input name="items['.$v1['id'].'][quantity]" class="form-control center input-sm number-format ajax-number-format" value="'.$v1['quantity'].'"></td>
		<td class="center"><input
				data-role="radio-option-ckc102"
				onchange="setRadioBool(this);set_default_supplier_room(this)"
				data-supplier_id="'.$id.'"
				data-room_id="'.$v1['id'].'" type="radio"
				class="radio-option-ckc102"
						
				name="h['.$v1['id'].'][is_default]"
				'.($v1['is_default'] == 1 ? 'checked' : '').'
						></td>
		<td class="center"><i class="pointer glyphicon glyphicon-trash btn-delete-item"
data-room_id="'.$v1['id'].'"
data-item_id="'.$id.'" 
data-action="remove_supplier_room"
onclick="call_ajax_function(this);"></i></td>
		</tr>';
		}
		$html .= '</tbody></table>';
		
		$html .= '<p class="aright mgt15">';
		//
		$html .= '<button data-required-save="true" data-type_id="'.TYPE_CODE_ROOM_TRAIN.'" data-load="new" data-existed="'.implode(',', $existed).'" data-supplier_id="'.$id.'" data-title="Thêm giường, ghế" type="button" onclick="open_ajax_modal(this);" data-class="" data-action="add-more-room-to-supplier" class="btn btn-warning btn-data-required-save"><i class="fa fa-plus"></i> Thêm giường, ghế</button></p><p>&nbsp;</p>';
		
		
		
		echo json_encode(['html'=>$html] + $_POST);exit;
		break;
		
	case 'loadSupplierRoutes':
		$id = post('id',0);
		$existed = [];
		$html = '';
		$m = load_model('distances');
		$html .= '<table class="table table-bordered vmiddle mgt15"><thead>
		<tr>
		<th class="center w50p">#</th>
		<th class="center">Tiêu đề </th>
				
		<th class="center w50p"></th>
		</tr>
		</thead><tbody>';
		foreach ($m->getItemBySupplier($id) as $k1=>$v1){
			$existed[] = $v1['id'];
			$html .= '<tr>
		<td class="center">'.($k1+1).'</td>
		<td class=""><a>'.uh($v1['title']) .'</a></td>
				
		<td class="center"><i class="pointer glyphicon glyphicon-trash btn-delete-item" data-id="'.$v1['id'].'" data-name="remove_routes" onclick="addToRemove(this);"></i></td>
		</tr>';
		}
		$html .= '</tbody></table>';
		
		$html .= '<p class="aright mgt15">';
		
		$html .= '<button data-required-save="true" data-type_id="'.TYPE_ID_TRAIN.'" data-load="new" data-existed="'.implode(',', $existed).'" data-supplier_id="'.$id.'" data-title="Thêm tuyến" type="button" onclick="open_ajax_modal(this);" data-class="" data-action="add-more-route-to-supplier" class="btn btn-warning btn-data-required-save"><i class="fa fa-plus"></i> Thêm tuyến</button></p><p>&nbsp;</p>';
		
		
		
		echo json_encode(['html'=>$html] + $_POST);exit;
		break;
	case 'loadSupplierPrices':
		$id = $supplier_id = post('id',0);
		$html = loadSupplierTrainPrices($supplier_id);
		echo json_encode(['html'=>$html,
				'callback'=>true,
				'callback_function'=>'load_number_format();',
		] + $_POST);exit;
		break;
	case 'quick-add-more-room-to-supplier':
		$supplier_id = post('supplier_id',0);
		$existed = post('existed',[]);
		if(!is_array($existed) && $existed != ""){
			$existed = explode(',', $existed);
		}
		
		foreach (post('items',[]) as $k1=>$v1){
			if($v1['quantity'] != "" && cprice($v1['quantity'])>0 ){
				Yii::$app->db->createCommand()->insert('rooms_to_hotel',[
						'parent_id'=>$supplier_id,
						'room_id'=>$k1,
						'quantity'=>cprice($v1['quantity'])						
				])->execute();
			}
		}
		
		//$responData['p'] = $_POST;
		
		//$callback_function .= 'log($d.p);';
		
		$callback_function .= 'closeAllModal();reloadAutoPlayFunction(true);';
		
		 
		break;
		
	case 'quick-add-more-route-to-supplier':
		$supplier_id = post('supplier_id',0);
		$existed = post('existed',[]);
		if(!is_array($existed) && $existed != ""){
			$existed = explode(',', $existed);
		}
		
		foreach (post('items',[]) as $k1=>$v1){
			if(cbool($v1) == 1){
				Yii::$app->db->createCommand()->insert('distances_to_suppliers',['supplier_id'=>$supplier_id,'item_id'=>$k1])->execute();
			}
		}
		
		$r['event'] = 'hide-modal';
		$r['callback'] = true;
		$r['callback_function'] = 'loadHtmlData(jQuery(\'.ajax-load-routes\'));loadHtmlData(jQuery(\'.ajax-load-prices\'))';
		$r['supplier_id'] = $supplier_id;
		echo json_encode($r);exit;
		break;
	case 'add-more-route-to-supplier':
		$r = array(); $r['html'] = '';
		$supplier_id = post('supplier_id',0);
		$m = load_model('distances'); //
		
		
		
		$r['html'] .= '<div class="form-group">
				
          <div class="col-sm-12">
	 <table class="table table-bordered vmiddle mgt15"><thead>
		<tr>
		<th class="center w50p">#</th>
		<th class="center">Tiêu đề </th>
		<th class="center w150p">Chọn</th>
				
		</tr>
		</thead><tbody>';
		foreach ($m->getAll(TYPE_ID_TRAIN,['not_in'=>post('existed',[])]) as $k1=>$v1){
			$r['html'] .= '<tr>
		<td class="center">'.($k1+1).'</td>
		<td class=""><a>'.uh($v1['title']) . '</a></td>
		<td class="center "><input type="checkbox" name="items['.$v1['id'].']" class=""></td>
				
		</tr>';
		}
		$r['html'] .= '</tbody></table>   </div>
         </div>';
		
		$r['html'] .= '<div class="modal-footer">';
		$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
		$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$r['html'] .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		$r['event'] = $_POST['action'];
		echo json_encode($r);exit;
		break;
	case 'quick_search_station_for_add_supplier':
		$val = $filter = trim(post('val'));
		$supplier_id = post('supplier_id',0);
		//$stationModel = load_model('stations');
		$station_from = post('station_from',0);
		$station_to = post('station_to',0);
		$from_id = 0;
		if(post('field') == 'from'){
			$from_id = $val;
			$filter = '';
		}
		$r = [];
		foreach ( \app\modules\admin\models\Stations::getAll([
				'type_id'=>TYPE_ID_TRAIN,
				'filter_text'=>$filter,
				'supplier_id'=>$supplier_id,
				'not_in'=>$station_from,
				'not_in_q'=>$station_from,
				'not_in_query'=>(new Query())
				->from('trains_to_prices')
				->where([
						'station_from'=>$station_from,
						'supplier_id'=>$supplier_id
				])->select('station_to')
		]) as $k=>$v){
			$r[] = $v['id'];
				}
				echo json_encode($r); exit;
				break;
				
	case 'quick-search-room-train':
		//$val = post('val');
		$stationModel = load_model('rooms_categorys');
		//$stations = $stationModel->getAll(['type_id'=>TYPE_ID_TRAIN,'filter_text'=>$val]);
		$r = [];
		foreach ( $stationModel->getAllRooms(TYPE_CODE_ROOM_TRAIN,['filter_text'=>trim(post('q'))]) as $k=>$v){
			$r[] = $v['id'];
		}
		echo json_encode($r); exit;
		break;
		
	case 'quick-add-more-station-to-distance':
		$items1 = post('items1');
		$supplier_id = post('supplier_id',0);
		$quotation_id = post('quotation_id',0);
		$items2 = post('items2',[]);
		//
		//$gadi = \app\modules\admin\models\Stations::getItem($items1); 
		if(!empty($items2)){
			/*
			foreach (array_merge($items2,[$items1]) as $item){
				break;
				if((new Query())->from('distances_to_stations')->where([
						'item_id'=>$supplier_id,
						'station_id'=>$item						
				])->count(1) == 0 && 0>1){
					
					Yii::$app->db->createCommand()->insert('distances_to_stations',[
							'item_id'=>$supplier_id,
							'station_id'=>$item							
					])->execute();
				}
			}
			exit; 
			/*/
			$seasons = explode(',', post('seasons'));
			$items = explode(',',post('items'));
			$package_id = post('package_id');
			$type_id = post('type_id');
			//
			foreach ($seasons as $season){
				 
				foreach ($items as $item){
					foreach ($items2 as $item2){
						
						
						if($items1 != $item2 && (new Query())->from('trains_to_prices')->where([
								'station_from'=>$items1,
								'station_to'=>$item2,
								'item_id'=>$item,
								'season_id'=>$season,
								'supplier_id'=>$supplier_id,
								'package_id'=>$package_id,
								'type_id'=>$type_id,
								'quotation_id'=>$quotation_id 
						])->count(1) == 0){
							//--//
							$ticket = (new Query())->from('trains_to_prices')->where([
									'station_from'=>$items1,
									'station_to'=>$item2,
									'item_id'=>$item,
									'season_id'=>$season,
									'supplier_id'=>$supplier_id,
									'package_id'=>$package_id,
									'type_id'=>$type_id,
									'quotation_id'=>$quotation_id
							])->one() ;
							
							
							
							if(!empty($ticket)){
								$ticket_id = $ticket['ticket_id'];
							}else{
								//exit;
								$ticket_id = Yii::$app->zii->insert(\app\modules\admin\models\Tickets::tableName(),[
										'lang_code'=>"text_station_${items1}_{$item2}",
										'title'=>\app\modules\admin\models\Stations::getTicketTitle($items1,$item2),
										'sid'=>__SID__,
										'type_id'=>$type_id,
										
								]);
							}
							//exit;
							// Tạo ticket
							//$gaden = \app\modules\admin\models\Stations::getItem($item2);
							//
							
							
							Yii::$app->db->createCommand()->insert('trains_to_prices',[
									'station_from'=>$items1,
									'station_to'=>$item2,
									'item_id'=>$item,
									'season_id'=>$season,
									'supplier_id'=>$supplier_id,
									'package_id'=>$package_id,
									'quotation_id'=>$quotation_id,
									'type_id'=>$type_id,
									'ticket_id'=>$ticket_id
									
							])->execute();
						}
					}
				}
			}
		}
		//
		$callback_function .= '
closeAllModal();
reloadAutoPlayFunction(true);';
		//		 
		break;
	case 'add-more-station-to-distance':
		$r = array(); $r['html'] = '';
		$supplier_id = post('supplier_id',0);
		$quotation_id = post('quotation_id',0);
		$package_id = post('package_id',0);
		$m = load_model('distances'); //
		
		$stationModel = load_model('stations');
		$stations = $stationModel->getAll(['type_id'=>TYPE_ID_TRAIN]);
		
		if($package_id==0){
			$r['html'].= '<fieldset class="f12px mgb15"><legend>Lưu ý</legend>';
			
			$r['html'].= '<p>
1. Bạn cần thêm tuyến hoạt động của tàu để có thể cài đặt được ga đi - ga đến (thường tuyến sẽ đặt tên theo ga đầu - ga cuối)
<br/>VD: <span class="underline red italic">Tuyến Bắc - Nam</span>
<br/>2. Để nhập được giá bạn cần phải thêm danh sách phòng / giường / ghế trước.
</p>';
			
			
			$r['html'].= '</fieldset>';
		}else{
		
		$r['html'] .= '<div class="form-group">
				
        <div class="col-sm-12">
	 	<table class="table table-bordered vmiddle mgt15"><thead>
				
		</thead><tbody><tr>
		<th class="center w150p">Ga đi</th>
		<th class=""><select data-quotation_id="'.$quotation_id.'" data-supplier_id="'.$supplier_id.'" name="items1" data-field="from" onchange="quick_search_station_for_add_supplier(this)" class="chosen-select input-station-from" data-role="load-station">';
		$slt = 0;
		if(!empty($stations)){
			foreach ($stations as $ks=>$s){
				if($ks == 0){
					$slt = $s['id'];
				}
				$r['html'] .= '<option value="'.$s['id'].'">'.uh($s['title']).'</option>';
			}}
			$r['html'] .= '</select></th>
					
					
		</tr><tr class="vtop">
		<th rowspan="'.(count($stations)+1).'" class="center" style="vertical-align:top !important ">Ga đến</th>
				
	 <th><input data-quotation_id="'.$quotation_id.'" data-supplier_id="'.$supplier_id.'"  data-from="'.$slt.'" onkeyup="quick_search_station_for_add_supplier(this)" class="form-control input-sm input-station-to" placeholder="Tìm kiếm nhanh"/></th>
		</tr>';
			
			if(!empty($stations)){
				foreach ($stations as $ks=>$s){
					$r['html'] .= '<tr class="quick-search-tr-item quick-search-tr-item-'.$s['id'].'">
							
		<th class=""><label>
						<input type="checkbox" value="'.$s['id'].'" name="items2[]"/> &nbsp;
						'.uh($s['title']).'</label></th>
								
								
		</tr>';
				}
			}
			$r['html'] .= '</tbody></table>   </div>
         </div>';
			
			
		}
			
			$r['html'] .= '<div class="modal-footer">';
			if($package_id>0){
				$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
			}
			$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Đóng</button>';
			$r['html'] .= '</div>';
			$_POST['action'] = 'quick-' . $_POST['action'];
			foreach ($_POST as $k=>$v){
				$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
			}
			///
			$r['event'] = $_POST['action'];
			$r['callback'] = true;
			$r['callback_function'] = 'jQuery(\'.input-station-from\').change();';
			echo json_encode($r);exit;
			break;
	case 'add-more-room-to-supplier':
		$r = array(); $r['html'] = '';
		$supplier_id = post('supplier_id',0);
		$m = load_model('rooms_categorys'); //
		
		
		
		$r['html'] .= '<div class="form-group">
				
          <div class="col-sm-12">
	 <table class="table table-bordered vmiddle mgt15"><thead>
		<tr>
		<th class="center w50p">#</th>
		<th class="center">Tiêu đề </th>
		<th class="center w150p">Số lượng</th>
				
		</tr>
		</thead><tbody>';
		$r['html'] .= '<tr >
		<td class="center"></td>
		<td class=""><input data-action="quick-search-room-train" onkeyup="quick_filter_text_value(this)" class="form-control input-sm" placeholder="Tìm kiếm nhanh"/></td>
		<td class="center "> </td>
				
		</tr>';
		foreach ($m->getAllRooms(TYPE_CODE_ROOM_TRAIN,['not_in'=>post('existed',[])]) as $k1=>$v1){
			$r['html'] .= '<tr class="quick-search-tr-item quick-search-tr-item-'.$v1['id'].'">
		<td class="center">'.($k1+1).'</td>
		<td class=""><a>'.uh($v1['title']) . ($v1['note'] != "" ? ' ('.uh($v1['note']).')' : '').'</a></td>
		<td class="center "><input name="items['.$v1['id'].'][quantity]" class="form-control center input-sm number-format ajax-number-format" value=""></td>
				
		</tr>';
		}
		$r['html'] .= '</tbody></table>   </div>
         </div>';
		
		
		
		
		$r['html'] .= '<div class="modal-footer">';
		$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
		$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$r['html'] .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		$r['event'] = $_POST['action'];
		echo json_encode($r);exit;
		break;
	case 'quick-add-more-guides':
		$model = load_model('guides');
		
		$f = post('f');$biz = post('biz',[]);
		$f['bizrule'] = cjson($biz);
		
		$guide_id = post('guide_id',0);
		$supplier_id = post('supplier_id',0);
		$existed = post('existed',[]);
		if(!is_array($existed) && $existed != ""){
			$existed = explode(',', $existed);
		}
		//
		
		$remove_menu = post('remove_menu',[]);
		
		if($guide_id == 0){ // Thêm mới
			$f['sid'] = __SID__;
			$guide_id = Yii::$app->zii->insert($model->tableGuide(),$f);
			
			Yii::$app->db->createCommand()->insert($model->tableToSupplier(),['guide_id'=>$guide_id,'supplier_id'=>$supplier_id])->execute();
			//exit;
			$insert_menu = true;
		}else {
			Yii::$app->zii->update($model->tableGuide(),$f,['id'=>$guide_id,'sid'=>__SID__]);
			$insert_menu = false;
		}
		
		
		// Cập nhật giá
		//Yii::$app->db->createCommand()->delete($menuModel->tableToPrice(),['item_id'=>$menu_id,'parent_id'=>$supplier_id])->execute();
		//Yii::$app->db->createCommand()->insert($menuModel->tableToPrice(),['item_id'=>$menu_id,'parent_id'=>$supplier_id,'price1'=>cprice($prices['price1']),'currency'=>$prices['currency']])->execute();
		//
		//if(!empty($remove_menu)){
		//	Yii::$app->db->createCommand()->delete($model->tableToSupplier(),['supplier_id'=>$supplier_id,'guide_id'=>$remove_menu])->execute();
		//}
		//if($insert_menu) Yii::$app->db->createCommand()->insert($menuModel->tableToSupplier(),['menu_id'=>$menu_id,'supplier_id'=>$supplier_id])->execute();
		//
		
		$r['event'] = $_POST['action'];
		$r['supplier_id'] = $supplier_id;
		echo json_encode($r);exit;
		break;
	case 'add-more-guides':
		
		$r = array(); $r['html'] = '';
		$menu_id = post('guide_id',0);
		$menuModel = load_model('guides');
		$supplier_id = post('supplier_id',0);
		
		
		$item = $menu_id >0 ? $menuModel->getGuide($menu_id) : [];
		//view($item,true);
		
		$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label aleft">Tiêu đề</label>
          <div class="col-sm-12">
	<input name="f[title]" class="form-control input-sm required" value="'.(!empty($item) ? uh($item['title']) : '').'"/>
			
            </div>
         </div>';
		
		$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label aleft">Mô tả ngắn</label>
          <div class="col-sm-12">
	<input name="biz[info]" class="form-control input-sm " value="'.(!empty($item) ? uh($item['info']) : '').'"/>
			
            </div>
         </div>';
		$r['html'] .= '<div class="form-group edit-form-left"><div class="col-sm-6"><div class="row">
				
          <div class="col-sm-12">
'.Ad_edit_show_select_field([],[
		'field'=>'language',
		'label'=>'Ngôn ngữ',
		'class'=>'select2 ',
		//'field_name'=>'category_id[]',
		//'multiple'=>true,
		'attrs'=>[
				'data-search'=>'hidden'
		],
		'data'=>\app\modules\admin\models\AdLanguage::getList(),
		'data-selected'=>[!empty($item) ? $item['language'] : DEFAULT_LANG],
		'option-value-field'=>'code',
		'option-title-field'=>'title',
]).'
				
            </div></div></div>
				
				
				
				
				<div class="col-sm-6"><div class="row">
				
<div class="col-sm-12">
				
</div></div></div>
				
				
         </div>';

$r['html'] .= '<div class="modal-footer">';
$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
$r['html'] .= '</div>';
$_POST['action'] = 'quick-' . $_POST['action'];
foreach ($_POST as $k=>$v){
	$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
}
///
$r['event'] = $_POST['action'];
echo json_encode($r);exit;
break;
	case 'loadGuidePrices':
		$id = post('id',0);
		$menus = load_model('guides');
		$l = $menus->getGuides(['supplier_id'=>$id]);
		
		//
		$packageModel = load_model('package_prices');
		$servicesProvider = load_model('services_provider');
		$packages = $packageModel->getPackages($id);
		$m = load_model('nationality_groups');
		$nationalitys = $m->get_supplier_group($id);
		if(empty($nationalitys)){
			$nationalitys = [
					['id'=>0,'title'=>'']
			];
		}
		$html = '';
		//
		
		$incurred_list = \app\modules\admin\models\Seasons::get_incurred_category_for_price(TYPE_ID_REST,[2],[
				'supplier_id'=>$id,
				'type_id'=>20,
				'price_type'=>0
		]);
		$ckc_incurred = true;
		if(empty($incurred_list)){
			$incurred_list = [[
					'id'=>0,'title'=>''
			]];
			$ckc_incurred = false;
		}
		$incurred_prices_weekend_list = \app\modules\admin\models\Seasons::get_incurred_category_for_price(TYPE_ID_REST,[3,4],[
				'supplier_id'=>$id,
				'type_id'=>20,
				'price_type'=>0
		]);
		$room_groups = \app\modules\admin\models\Seasons::get_rooms_groups($id);
		
		if(empty($incurred_prices_weekend_list)){
			$incurred_prices_weekend_list = [[
					'id'=>0,'title'=>''
			]];
			
		}
		$existed_nationality = [];$existed = [];
		foreach ($packages as $package){
			if(!empty($nationalitys)){
				
				foreach ($nationalitys as $kb=>$vb){
					$existed_nationality[] = $vb['id'];
					$html .= '<div class="col-sm-12 mgt15"><div class="row pr"><p class="grid-sui-pheader bold aleft"><i style="font-weight: normal;">';
					
					$html .= 'Bảng giá ';
					if($package['id']>0){
						$html .= '<b class="italic green underline">' .$package['title'] .'</b> ';
					}
					$html .= $vb['id'] > 0 ? ' - áp dụng cho <b class="italic underline">' .$vb['title'] .'</b> ' : '';
					
					$html .= '</i>'.($vb['id'] > 0 ? '<i data-name="remove_nationality" data-id="'.$vb['id'].'" onclick="addToRemove(this);" class="fa fa-trash pointer btn-remove btn-delete-item"></i>' : "").'</p></div></div>';
					$colspan = count($room_groups) * (count($incurred_prices_weekend_list)>0 ? count($incurred_prices_weekend_list) : 1);
					$html .= '<div class="col-sm-12"><div class="row"><table class="table table-bordered vmiddle ">
<thead>
<tr><th rowspan="4" class="center w50p"></th>
<th rowspan="4" class="center">Tiêu đề</th>
<th rowspan="4" class="center">Ngôn ngữ</th>
<th colspan="'.($colspan*count($incurred_list)).'" class="center underline">Đơn giá</th>
<th rowspan="4" class="w100p center">Tiền tệ</th><th rowspan="4" class="w100p"></th>
</tr>
							<tr class="'.($colspan*count($incurred_list) > 1 && $ckc_incurred ? '' : 'hide').'">';
					if(!empty($incurred_list)){
						foreach ($incurred_list as $in){
							$html .= '<th colspan="'.($colspan).'" class="center w200p">'.$in['title'].'</th>';
						}
					}
					
					$html .= '
</tr><tr class="hide">';
					if(!empty($incurred_list)){
						foreach ($incurred_list as $in){
							if(!empty($room_groups)){
								foreach ($room_groups as $room){
									$html .= '<th colspan="'.(count($incurred_prices_weekend_list)).'" class="center w200p"><a data-class="w70" data-parent_id="'.(isset($v['id']) ? $v['id'] : 0).'" data-id="'.$room['id'].'" data-action="add-more-room-group" data-title="Thiết lập nhóm phòng" onclick="open_ajax_modal(this);" class="pointer hover_underline">'.$room['title'].($room['note'] != "" ? '<p><i class="f11p font-normal">('.$room['note'].')</i></p>' : '').'</a></th>';
								}
							}
						}}
						$html .= '
</tr><tr class="'.(count($incurred_prices_weekend_list) > 1 ? '' : 'hide').'">';
						if(!empty($incurred_list)){
							foreach ($incurred_list as $in){
								if(!empty($room_groups)){
									foreach ($room_groups as $room){
										if(!empty($incurred_prices_weekend_list)){
											foreach ($incurred_prices_weekend_list as $weekend){
												$html .= '<th class="center w200p"><i>'.$weekend['title'].'</i></th>';
											}
										}
									}
								}
							}}
							
							$html .='</tr></thead>
<tbody >';
							
							if(!empty($l)){
								foreach ($l as $k1=>$v1){
									
									$existed[] = $v1['id'];
									$p = $menus->get_price($v1['id'],$id,$vb['id'],$package['id']);
									$html .= '<tr>
<td class="center">'.($k1+1).'</td>
<td><a class="pointer" data-supplier_id="'.$id.'" data-guide_id="'.$v1['id'].'" data-title="Chỉnh sửa hướng dẫn viên" onclick="open_ajax_modal(this);" data-class="w90" data-action="add-more-guides">'.uh($v1['title']).'</a></td>
<td class="center">'.(Yii::$app->zii->showLang($v1['language'])).'</td>';
									if(!empty($incurred_list)){
										foreach ($incurred_list as $in){
											if(!empty($room_groups)){
												foreach ($room_groups as $room){
													if(!empty($incurred_prices_weekend_list)){
														foreach ($incurred_prices_weekend_list as $w){
															$html .= '<td class="center"><input onblur="addFormEditField(this)" type="text" name="prices['.$package['id'].']['.$vb['id'].']['.$v1['id'].'][list_child]['.$in['id'].']['.$room['id'].']['.$w['id'].'][price1]" value="'.(isset($p[$in['id']][$room['id']][$v1['id']][$w['id']]['price1']) ? $p[$in['id']][$room['id']][$v1['id']][$w['id']]['price1'] : '').'" data-old="'.(isset($p[$in['id']][$room['id']][$v1['id']][$w['id']]['price1']) ? $p[$in['id']][$room['id']][$v1['id']][$w['id']]['price1'] : '').'" class="form-control input-sm aright number-format w100 inline-block input-currency-price-'.$v1['id'].'" data-decimal="'.Yii::$app->zii->showCurrency((isset($p['currency']) && $p['currency'] ? $p['currency'] : 1),3).'"/></td>';
														}
													}
												}
											}
										}}
										$html .= '<td class="center">';
										$html .= '<select data-target-input=".input-currency-price-'.$v1['id'].'" data-decimal="'.Yii::$app->zii->showCurrency((isset($p['currency']) && $p['currency'] ? $p['currency'] : 1),3).'" onchange="get_decimal_number(this);addFormEditField(this)" class="ajax-select2-no-search sl-cost-price-currency form-control ajax-select2 input-sm" data-search="hidden" name="prices['.$package['id'].']['.$vb['id'].']['.$v1['id'].'][currency]">';
										//if(isset($v['currency']['list']) && !empty($v['currency']['list'])){
										foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v2){
											$html .= '<option value="'.$v2['id'].'" '.(isset($p['currency']) && $p['currency'] == $v2['id'] ? 'selected' : '').'>'.$v2['code'].'</option>';
										}
										//}
										
										$html .= '</select>';
										$html .= '</td>';
										$html .= '<td class="center">
<i class="pointer glyphicon glyphicon-trash btn-delete-item" data-id="'.$v1['id'].'" data-name="remove_menu" onclick="addToRemove(this);"></i>
</td>
</tr> ';
										
								}
							}
							
							$html .= '</tbody></table></div></div>';
				}
			}
		}
		
		//
		
		
		$html .= '<p class="aright mgt15">';
		//
		$html .= '<button data-required-save="true" data-load="new" data-existed="'.implode(',', $existed).'" data-supplier_id="'.$id.'" data-title="Thêm hướng dẫn" type="button" onclick="open_ajax_modal(this);" data-class="w90" data-action="add-more-guides" class="btn btn-warning btn-data-required-save"><i class="fa fa-plus"></i> Thêm hướng dẫn</button></p><p>&nbsp;</p>';
		
		
		
		echo $html; exit;
		break;
	case 'loadMenus':
		echo json_encode(['html'=>getSupplierPricesList(post('supplier_id',0))]);exit;
		$id =$supplier_id= post('id',0);
		$menus = load_model('menus');
		$l = $menus->getMenus(['supplier_id'=>$id]);
		$quotations = \app\modules\admin\models\Customers::getSupplierQuotations($supplier_id,[
				'order_by'=>['a.to_date'=>SORT_DESC,'a.title'=>SORT_ASC],
				'is_active'=>1
		]);
		//
		$packageModel = load_model('package_prices');
		$servicesProvider = load_model('services_provider');
		
		$m = load_model('nationality_groups');
		
		$html = '';
		
		$html .= getPriceHeaderButton($supplier_id,
				[
						'controller_code'=>TYPE_ID_REST,
						'type_id'=>TYPE_ID_REST,
						'quotation'=>true,'package'=>true,
						'nationality'=>true,'group'=>true,
						
						'menu'=>true
				]);
		$ckc_incurred =  true;
		
		$existed_nationality = [];$existed = [];
		
		
		// Lay package
		$packages = \app\modules\admin\models\PackagePrices::getPackages($supplier_id);
		// Lay nhom quoc tich
		$nationalitys = \app\modules\admin\models\NationalityGroups::get_supplier_group($supplier_id);
		// Lay mua co tinh gia truc tiep
		$incurred_list = $incurred_prices_list = \app\modules\admin\models\Customers::getCustomerSeasons($supplier_id,[
				'price_type'=>[0],'type_id'=>2
		]);
		// Lay danh sach cuoi tuan ngay thuong tinh gia truc tiep
		$incurred_prices_weekend_list = \app\modules\admin\models\Customers::getCustomerWeekend([
				'price_type'=>[0],
				'supplier_id'=>$supplier_id,
				'return_type'=>'for_price',
		]);
		
		// Lay danh sach buổi tinh gia truc tiep
		$incurred_prices_weekend_list_time = \app\modules\admin\models\Customers::getCustomerWeekendTime([
				'price_type'=>[0],
				'supplier_id'=>$supplier_id,
				'return_type'=>'for_price',
		]);
		$l3 = \app\modules\admin\models\Hotels::getListRooms($supplier_id);
		// Lay nhom phong
		$room_groups = \app\modules\admin\models\Seasons::get_rooms_groups($supplier_id);
		if(!empty($quotations)){
			foreach ($quotations as $q=>$quotation){
				$html .= '<div class="col-sm-12 mgt15 quotation-block" style=""><div class="row pr"><p class="grid-sui-pheader bold aleft">
				'.$quotation['title'].'<i> - Áp dụng từ <span class="  underline">'.date('d/m/Y H:i:s',strtotime($quotation['from_date'])).' - '.date('d/m/Y H:i:s',strtotime($quotation['to_date'])).'</span></i></p></div>';
				
				
				$html .= '<div class="row-10">';
				
				foreach ($packages as $package){
					if(!empty($nationalitys)){
						
						foreach ($nationalitys as $kb=>$vb){
							$existed_nationality[] = $vb['id'];
							$html .= '<div class="col-sm-12 mgt15"><div class="row pr"><p class="grid-sui-pheader bold aleft"><i style="font-weight: normal;">';
							
							
							if($package['id']>0){
								$html .= 'Gói dịch vụ ';
								$html .= '<b class="italic green underline">' .$package['title'] .'</b> ';
							}else{
								$html .= 'Bảng giá ';
							}
							$html .= ' - áp dụng cho <b class="italic underline">' .$vb['title'] .'</b> ';
							
							$html .= '</i><i data-name="remove_nationality" data-id="'.$vb['id'].'" onclick="addToRemove(this);" class="fa fa-trash pointer hide btn-remove btn-delete-item"></i></p></div></div>';
							$colspan = count($room_groups) * (count($incurred_prices_weekend_list)>0 ? count($incurred_prices_weekend_list) : 1);
							$html .= '<div class="col-sm-12"><div class="row"><table class="table table-bordered vmiddle ">
<thead>
<tr><th rowspan="5" class="center w50p"></th>
<th rowspan="5" class="center" style="min-width:200px">Tiêu đề</th>
<th colspan="'.($colspan*count($incurred_list) *count($incurred_prices_weekend_list_time) ).'" class="center underline ">Bảng giá</th>
<th rowspan="5" class="w100p center" title="Chuyển đổi nhanh loại tiền tệ">Tiền tệ <hr><select
		data-target=".select-currency-'.$quotation['id'].'-'.$package['id'].'-'.$vb['id'].'"
		data-decimal="0" onchange="get_decimal_number(this);change_multi_currency_price(this);" class="sl-cost-price-currency form-control select2 input-sm" data-search="hidden" >';
							//if(isset($v['currency']['list']) && !empty($v['currency']['list'])){
							foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v2){
								$html .= '<option value="'.$v2['id'].'">'.$v2['code'].'</option>';
							}
							//}
							
							$html .= '</select></th><th rowspan="5" class="w100p">Mặc định</th>		<th rowspan="5" class="w100p"></th>
</tr>
							<tr class="'.(count($incurred_list) > 1 && $ckc_incurred ? '' : 'hide').'">';
							if(!empty($incurred_list)){
								foreach ($incurred_list as $in){
									$html .= '<th colspan="'.($colspan * count($incurred_prices_weekend_list_time)).'" class="center w200p">'.$in['title'].'</th>';
								}
							}
							
							$html .= '
</tr><tr class="'.(count($room_groups) > 1 ? ' ' : 'hide').'">';
							if(!empty($incurred_list)){
								foreach ($incurred_list as $in){
									if(!empty($room_groups)){
										foreach ($room_groups as $room){
											$html .= '<th colspan="'.(count($incurred_prices_weekend_list) * count($incurred_prices_weekend_list_time)).'" class="center w200p"><a data-class="w70" data-supplier_id="'.$supplier_id.'" data-parent_id="'.(isset($v['id']) ? $v['id'] : 0).'" data-id="'.$room['id'].'" data-action="add-more-room-group" data-title="Thiết lập nhóm phòng" onclick="open_ajax_modal(this);" class="pointer hover_underline">'.$room['title'].($room['note'] != "" ? '<p><i class="f11p font-normal">('.$room['note'].')</i></p>' : '').'</a></th>';
										}
									}
								}}
								$html .= '</tr>';
								$html .= '<tr class="'.(count($incurred_prices_weekend_list) > 1 ? '' : 'hide').'">';
								if(!empty($incurred_list)){
									foreach ($incurred_list as $in){
										if(!empty($room_groups)){
											foreach ($room_groups as $room){
												if(!empty($incurred_prices_weekend_list)){
													foreach ($incurred_prices_weekend_list as $weekend){
														$html .= '<th colspan="'.(count($incurred_prices_weekend_list_time)).'" class="center w200p"><i>'.$weekend['title'].'</i></th>';
													}
												}
											}
										}
									}}
									
									$html .='</tr>';
									$html .= '<tr class="'.(count($incurred_prices_weekend_list) > 1 ? '' : 'hide').'">';
									if(!empty($incurred_list)){
										foreach ($incurred_list as $in){
											if(!empty($room_groups)){
												foreach ($room_groups as $room){
													if(!empty($incurred_prices_weekend_list)){
														foreach ($incurred_prices_weekend_list as $weekend){
															if(!empty($incurred_prices_weekend_list_time)){
																foreach ($incurred_prices_weekend_list_time as $weekend_time){
																	$html .= '<th class="center w150p"><i>'.$weekend_time['title'].'</i></th>';
																}
															}
														}
													}
												}
											}
										}}
										
										$html .='</tr>';
										
										$html .= '</thead><tbody >';
										
										if(!empty($l)){
											foreach ($l as $k1=>$v1){
												$existed[] = $v1['id'];
												//$p = $menus->get_price($v1['id'],$id,$vb['id'],$package['id']);
												$currency = 1;
												$tr = [
														$supplier_id,
														$quotation['id'],
														$package['id'],
														$vb['id'],
														$v1['id']
												];
												$html .= '<tr class="tr-price-'.implode('-', $tr).'">
<td class="center">'.($k1+1).'</td>
<td><a class="pointer" data-supplier_id="'.$id.'" data-menu_id="'.$v1['id'].'" data-title="Chỉnh sửa thực đơn" onclick="open_ajax_modal(this);" data-class="w90" data-action="add-more-menu-supplier">'.uh($v1['title']).'</a></td>';
												if(!empty($incurred_list)){
													foreach ($incurred_list as $in){
														if(!empty($room_groups)){
															foreach ($room_groups as $room){
																if(!empty($incurred_prices_weekend_list)){
																	foreach ($incurred_prices_weekend_list as $w){
																		if(!empty($incurred_prices_weekend_list_time)){
																			foreach ($incurred_prices_weekend_list_time as $weekend_time){
																				$price = \app\modules\admin\models\Menus::getMenuPrice([
																						'item_id'=>$v1['id'],
																						'season_id'=>$in['id'],
																						'weekend_id'=>$w['id'],
																						'group_id'=>$room['id'],
																						'supplier_id'=>$supplier_id,
																						'package_id'=>$package['id'],
																						'quotation_id'=>$quotation['id'],
																						'time_id'=>$weekend_time['id'],
																						'nationality_id'=>$vb['id']
																				]);
																				if(!empty($price)) $currency = $price['currency'];
																				$html .= '<td class="center"><input
											data-supplier_id="'.$supplier_id.'"
											data-quotation_id="'.$quotation['id'].'"
											data-package_id="'.$package['id'].'"
											data-nationality_id="'.$vb['id'].'"
											data-item_id="'.$v1['id'].'"
											data-season_id="'.$in['id'].'"
											data-group_id="'.$room['id'].'"
											data-weekend_id="'.$w['id'].'"
											data-time_id="'.$weekend_time['id'].'"
											onblur="quick_change_menu_price(this);"
											type="text" name="prices['.$package['id'].']['.$vb['id'].']['.$v1['id'].'][list_child]['.$in['id'].']['.$room['id'].']['.$w['id'].'][price1]"
											value="'.(isset($price['price1']) ? $price['price1'] : '').'"
											data-old="'.(isset($price['price1']) ? $price['price1'] : '').'"
											class="form-control input-sm aright number-format w100 inline-block input-currency-price-'.$v1['id'].'" data-decimal="'.Yii::$app->zii->showCurrency($currency,3).'"/></td>';
																			}
																		}
																	}
																}
															}
														}
													}}
													$html .= '<td class="center">';
													$html .= '<select
					data-supplier_id="'.$supplier_id.'"
					data-quotation_id="'.$quotation['id'].'"
					data-package_id="'.$package['id'].'"
					data-nationality_id="'.$vb['id'].'"
					data-item_id="'.$v1['id'].'"
					data-decimal="'.Yii::$app->zii->showCurrency($currency,3).'" data-target-input=".input-currency-price-'.$v1['id'].'" onchange="get_decimal_number(this);quick_change_menu_price_currency(this);" class="ajax-select2-no-search sl-cost-price-currency form-control ajax-select2 input-sm select-currency-'.$quotation['id'].'-'.$package['id'].'-'.$vb['id'].'" data-search="hidden" name="prices['.$package['id'].']['.$vb['id'].']['.$v1['id'].'][currency]">';
													//if(isset($v['currency']['list']) && !empty($v['currency']['list'])){
													foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v2){
														$html .= '<option value="'.$v2['id'].'" '.($currency == $v2['id'] ? 'selected' : '').'>'.$v2['code'].'</option>';
													}
													//}
													
													$html .= '</select>';
													$html .= '</td>';
													
													$html .= '<td class="center"><input type="checkbox" name="set_default['.$quotation['id'].']['.$package['id'].']" value="'.$v1['id'].'"/></td>';
													
													$html .= '<td class="center">
<i data-supplier_id="'.$supplier_id.'"
					data-quotation_id="'.$quotation['id'].'"
					data-package_id="'.$package['id'].'"
					data-nationality_id="'.$vb['id'].'"
					data-item_id="'.$v1['id'].'"
					data-confirm-text="<span class=red>Lưu ý: Thực đơn <b class=underline>'.$v1['title'].'</b> sẽ bị xóa khỏi toàn bộ các báo giá.</span>"
					class="pointer glyphicon glyphicon-trash btn-delete-item" data-id="'.$v1['id'].'" data-name="remove_menu" data-confirm-action="quick_change_menu_price_remove" data-action="open-confirm-dialog" data-class="modal-sm" data-title="Xác nhận xóa." onclick="open_ajax_modal(this);"></i>
</td>
</tr> ';
											}
										}
										
										$html .= '</tbody></table></div></div>';
						}
					}
				}
				
				//
				
				
				$html .= '</div></div>';
			}
			
		} else{
			$html .= '<div class="col-sm-12"><p class="help-block red ">Bạn cần tạo báo giá trước khi nhập giá.</p></div>';
		}
		
		echo $html; exit;
		break;
	case 'quick-add-more-menu-supplier':
		$menuModel = load_model('menus');
		$foodModel = load_model('foods');
		$f = post('f');$biz = post('biz',[]);
		$f['bizrule'] = cjson($biz);
		$prices = post('prices');
		$menus = post('menus',[]);
		$menu_id = post('menu_id',0);
		$supplier_id = post('supplier_id',0);
		$existed = post('existed',[]);
		if(!is_array($existed) && $existed != ""){
			$existed = explode(',', $existed);
		}
		//
		$remove_menu = post('remove_menu',[]);
		
		if($menu_id == 0){ // Thêm mới
			$f['sid'] = __SID__;
			$f['supplier_id'] = $supplier_id;
			if((new Query())->from(['a'=>'menus'])
					//->innerJoin(['b'=>'menus_to_suppliers'],'a.id=b.menu_id')
					->where([
							'a.title'=>$f['title'],'a.supplier_id'=>$supplier_id
					])->count(1) == 0){
						$menu_id = Yii::$app->zii->insert($menuModel->tableName(),$f);
					}else{
						//
						
						//
						echo json_encode([
								'callback'=>true,
								'callback_function'=>'jQuery(".error-field-alert").html(\'<div class="alert alert-danger mgt5" role="alert">Tên menu đã tồn tại, vui lòng đổi tên khác.</div>\');'
						]);exit;
						$menu_id = (new Query())->select('a.id')->from(['a'=>'menus'])->where(['a.title'=>$f['title'],'a.supplier_id'=>$supplier_id])->scalar();
					}
					
					
					
					$insert_menu = true;
		}else {
			Yii::$app->zii->update($menuModel->tableName(),$f,['id'=>$menu_id,'sid'=>__SID__]);
			$insert_menu = false;
		}
		
		// Cập nhật giá
		//Yii::$app->db->createCommand()->delete($menuModel->tableToPrice(),['item_id'=>$menu_id,'parent_id'=>$supplier_id])->execute();
		//Yii::$app->db->createCommand()->insert($menuModel->tableToPrice(),['item_id'=>$menu_id,'parent_id'=>$supplier_id,'price1'=>cprice($prices['price1']),'currency'=>$prices['currency']])->execute();
		//
		if(!empty($remove_menu)){
			Yii::$app->db->createCommand()->delete($menuModel->tableToSupplier(),['supplier_id'=>$supplier_id,'menu_id'=>$remove_menu])->execute();
		}
		if($insert_menu) Yii::$app->db->createCommand()->insert($menuModel->tableToSupplier(),['menu_id'=>$menu_id,'supplier_id'=>$supplier_id])->execute();
		//
		
		Yii::$app->db->createCommand()->delete($foodModel->tableToMenu(),['menu_id'=>$menu_id])->execute();
		
		//view($menus,true);
		if(!empty($menus)){
			foreach ($menus as $km=> $menu){
				Yii::$app->db->createCommand()->insert($foodModel->tableToMenu(),['menu_id'=>$menu_id,'food_id'=>$menu,'position'=>$km])->execute();
			}
		}
		//
		$category_id = post('category_id',[]);
		Yii::$app->db->createCommand()->delete(\app\modules\admin\models\Menus::tableToCategory(),['and',
				['not in', 'category_id',$category_id],
				['item_id'=>$menu_id]
		])->execute();
		if(!empty($category_id)){
			foreach ($category_id as $c){
				if((new Query())->from(\app\modules\admin\models\Menus::tableToCategory())->where(['category_id'=>$c])->count(1) == 0){
					Yii::$app->db->createCommand()->insert(\app\modules\admin\models\Menus::tableToCategory(),[
							'category_id'=>$c,
							'item_id'=>$menu_id,
					])->execute();
				}
			}
		}
		//
		$r['event'] = $_POST['action'];
		$r['supplier_id'] = $supplier_id;
		echo json_encode($r);exit;
		break;
	case 'changeMenusType':
		$type_id = post('type_id',0);
		$item_id = post('item_id',0);
		$html = '';
		$categorys = \app\modules\admin\models\FoodsCategorys::getAll(['type_id'=>$type_id]);
		$existed = \app\modules\admin\models\Menus::getItemCategorys($item_id);
		if(!empty($categorys)){
			foreach ($categorys as $k=>$v){
				$html .= '<option '.(in_array($v['id'], $existed) ? 'selected' : '').' value="'.$v['id'].'">'.uh($v['title']).'</option>';
			}
		}
		echo json_encode([
				'html'=>$html,
				
		]);exit;
		break;
	case 'add-more-menu-supplier':
		$r = array(); $r['html'] = '';
		$menu_id = post('menu_id',0);
		$menuModel = load_model('menus');
		$supplier_id = post('supplier_id',0);
		
		
		$item = $menu_id >0 ? $menuModel->getMenus(['supplier_id'=>$supplier_id,'menu_id'=>$menu_id])[0] : [];
		//view($item,true);
		
		$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label aleft">Tiêu đề</label>
          <div class="col-sm-12">
	<input name="f[title]" onkeypress="if (event.keyCode==13){return nextFocus(this);}" class="form-control input-sm required" value="'.(!empty($item) ? uh($item['title']) : '').'"/>
       <div class="error-field-alert"></div>
            </div>
         </div>';
		
		$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label aleft">Mô tả ngắn</label>
          <div class="col-sm-12">
	<input name="biz[info]" class="form-control input-sm " onkeypress="if (event.keyCode==13){return nextFocus(this);}" value="'.(!empty($item) ? uh($item['info']) : '').'"/>
			
            </div>
         </div>';
		$r['html'] .= '<div class="form-group edit-form-left"><div class="col-sm-8"><div class="row">
				
          <div class="col-sm-3">
'.Ad_edit_show_select_field([],[
		'field'=>'type_id',
		'label'=>'Loại thực đơn',
		'class'=>'select2 ',
		//'field_name'=>'category_id[]',
		//'multiple'=>true,
		'attrs'=>[
				'data-search'=>'hidden',
				'data-menu_id'=>!empty($item) ? $item['id'] : 0,
				'data-item_id'=>!empty($item) ? $item['id'] : 0,
				'data-target'=>'.group-select-menus-category',
				'data-target2'=>'.group-menus-category',
				'onchange'=>'changeMenusType(this)',
		],
		'data'=>[
				['id'=>1,'title'=>'Set menu'],
				['id'=>2,'title'=>'Buffet'],
		],
		'data-selected'=>[!empty($item) ? $item['type_id'] : 1],
		'option-value-field'=>'id',
		'option-title-field'=>'title',
]).'
				
            </div>
					<div class="col-sm-9">
'.Ad_edit_show_select_field_group([],[
		//'field'=>'type_id',
		'label'=>'Danh mục món ăn',
		'class'=>'chosen-select group-menus-category group-select-menus-category',
		'default_value'=>0,
		'field_name'=>'category_id[]',
		'multiple'=>true,
		'attrs'=>[
				'data-search'=>'hidden',
				'data-placeholder'=>'Chọn danh mục món ăn',
				'data-type_id'=>!empty($item) ? $item['type_id'] : 0,
				'data-menu_id'=>!empty($item) ? $item['id'] : 0,
				'data-item_id'=>!empty($item) ? $item['id'] : 0,
				'onchange'=>'addSelectedMenusCategory(this)',
				'data-target'=>'.group-button-menus-category'
				
		],
		'data'=>\app\modules\admin\models\FoodsCategorys::getAll(['type_id'=>!empty($item) ? $item['type_id'] :1]),
		'data-selected'=>\app\modules\admin\models\Menus::getItemCategorys(!empty($item) ? $item['id'] : 0),
		'option-value-field'=>'id',
		'option-title-field'=>'title',
		'groups'=>[
				'attrs'=>[
						'onclick'=>'',
						'title'=>'Thêm danh mục món ăn',
						'data-toggle'=>'tooltip',
						'data-placement'=>'top',
						'data-type_id'=>!empty($item) ? $item['type_id'] : 1,
						'data-menu_id'=>!empty($item) ? $item['id'] : 0,
						'data-item_id'=>!empty($item) ? $item['id'] : 0,
						'onclick'=>'open_ajax_modal(this)',
						'data-action'=>'add-more-menus-categorys',
						'data-modal-target'=>'.mymodal1',
						'data-title'=>'Thêm danh mục món ăn',
						'data-existed'=>implode(',', \app\modules\admin\models\Menus::getItemCategorys(!empty($item) ? $item['id'] : 0)),
				],
				'class'=>'btn-success group-menus-category group-button-menus-category',
				'label'=>'<i class="fa fa-plus"></i>',
		],
]).'
				
            </div>
					</div></div>
				
				
				
				
				<div class="col-sm-4"><div class="row">
				
<div class="col-sm-12">
		'. Ad_edit_show_select_field([],[
				'field'=>'time',
				'label'=>'Thời gian',
				'class'=>'ajax-select2 ',
				'default_value'=>0,
				//'field_name'=>'category_id[]',
				//'multiple'=>true,
				'attrs'=>[
						'data-search'=>'hidden'
				],
				'data'=>[
						['id'=>1,'title'=>'Sáng'],
						['id'=>2,'title'=>'Trưa'],
						['id'=>3,'title'=>'Tối'],
				],
				'data-selected'=>[!empty($item) ? $item['time'] : 0],
				'option-value-field'=>'id',
				'option-title-field'=>'title',
		]).'
</div></div></div>
				
				
         </div>';
		
		$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label aleft">Danh sách món ăn</label>
          <div class="col-sm-12">
	<table class="table table-bordered vmiddle">
<thead><th class="center w50p">#</th><th class="center">Tên món ăn</th> <th class="center w50p"></th></thead>
<tbody class="ajax-result-add-more-foods tb-sortable">';
		$menu_exitsted = [];$k = 0;
		if(isset($item['foods']) && !empty($item['foods'])){
			foreach ($item['foods'] as $k=>$v){
				$menu_exitsted[] = $v['id'];
				$r['html'] .= '<tr class="move-content"><td class="center">'.($k+1).'
							<input type="hidden" name="menus[]" value="'.$v['id'].'"/>
							</td>
		<td class=""><a data-modal-target=".mymodal1" class="pointer after-event-'.$v['id'].'" data-id="'.$v['id'].'" data-title="Sửa món ăn" onclick="open_ajax_modal(this);" data-action="quick-edit-food">'.uh($v['title']).'</a></td>
				
		<td class="center"><i onclick="removeTrItem(this)" class="fa fa-trash f12px pointer"></i></td>
				
				
		</tr>';
			}
		}
		$r['html'] .= '</tbody>
		</table>
    <p class="aright "><button data-class="w80" data-count="'.(isset($item['foods']) ? count($item['foods']) : 0).'" data-existed="'.(implode(',', $menu_exitsted)).'" data-title="Thêm món ăn" data-modal-target=".mymodal1" data-action="add-foods-to-menu" onclick="open_ajax_modal(this);" type="button" class="btn btn-warning btn-add-foods-to-menu"><i class="fa fa-plus"></i> Thêm món ăn</button></p>
            </div>
         </div>';
		
		
		$r['html'] .= '<div class="modal-footer">';
		$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$r['html'] .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		
		$r['event'] = $_POST['action'];
		$r['callback'] = true;  $r['callback_function'] = 'reloadTooltip();jQuery( ".tb-sortable" ).sortable();jQuery( ".tb-sortable" ).disableSelection();';
		echo json_encode($r);exit;
		break;
	case 'quick-add-more-menus-categorys':
		$item_id = post('item_id',0);
		$type_id = post('type_id',1);
		$f = post('f',[]);
		$biz = post('biz',[]);
		$f['sid']= __SID__; $html = '';
		$f['type_id']= $type_id;
		$f['bizrule'] = json_encode($biz);
		$selected = explode(',',  post('existed',[]));
		if((new Query())->from(\app\modules\admin\models\FoodsCategorys::tableName())->where(['title'=>$f['title'],'type_id'=>$type_id,'sid'=>__SID__])->count(1) == 0){
			$id = Yii::$app->zii->insert(\app\modules\admin\models\FoodsCategorys::tableName(),$f);
			$item = \app\modules\admin\models\FoodsCategorys::getItem($id);
			$s = true;
			$html .= '<option value="'.$id.'">'.uh($f['title']).'</option>';
		}else{
			$s = false;
			$item = (new Query())->from(\app\modules\admin\models\FoodsCategorys::tableName())->where(['title'=>$f['title'],'type_id'=>$type_id,'sid'=>__SID__])->one();
			$id = $item['id'];
		}
		$selected[] = $id;
		echo json_encode([
				'event'=>'hide-modal',
				'modal_target'=>'.mymodal1',
				'item'=>!empty($item) ? $item : false,
				'callback'=>true,
				'callback_function'=>'jQuery(\'.group-select-menus-category\').append(\''.$html.'\').val(['.implode(',', $selected).']).trigger(\'chosen:updated\')'
		]);exit;
		break;
	case 'add-more-menus-categorys':
		$r = array(); $r['html'] = '';
		$menu_id = post('menu_id',0);
		$menuModel = load_model('menus');
		$supplier_id = post('supplier_id',0);
		
		
		
		
		$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label aleft">Tiêu đề</label>
          <div class="col-sm-12">
	<input name="f[title]" onkeypress="if (event.keyCode==13){return nextFocus(this);}" class="form-control input-sm required" value=""/>
				
            </div>
         </div>';
		
		$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label aleft">Mô tả ngắn</label>
          <div class="col-sm-12">
	<input name="biz[info]" class="form-control input-sm " onkeypress="if (event.keyCode==13){return nextFocus(this);}" value=""/>
				
            </div>
         </div>';
		$r['html'] .= '<div class="form-group edit-form-left"><div class="col-sm-12"><div class="row">
				
				
				
					</div></div>
				
				
				
				
				
				
				
         </div>';
		
		
		
		
		$r['html'] .= '<div class="modal-footer">';
		$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$r['html'] .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		
		$r['event'] = $_POST['action'];
		//$r['callback'] = true;
		//$r['callback_function'] = '';
		echo json_encode($r);exit;
		break;
		
	case 'quick-quick-edit-food':
		$id = post('id',0);
		$f = post('f',[]);
		Yii::$app->db->createCommand()->update(\app\modules\admin\models\Foods::tableName(),$f,['id'=>$id])->execute();
		$r['new_value'] = $f['title'];
		$r['id']=$id;
		$r['event'] = $_POST['action'];
		echo json_encode($r);exit;
		break;
	case 'quick-edit-food':
		$r = array(); $r['html'] = '';
		$id = post('id',0);
		$item = \app\modules\admin\models\Foods::getItem($id);
		
		$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label aleft">Tiêu đề</label>
          <div class="col-sm-12">
	<input name="f[title]" class="form-control input-sm required" value="'.(!empty($item) ? uh($item['title']) : '').'"/>
			
            </div>
         </div>';
		
		
		
		
		
		$r['html'] .= '<div class="modal-footer">';
		$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$r['html'] .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		
		$r['event'] = $_POST['action'];
		//	$r['existed'] = $existed;
		echo json_encode($r);exit;
		break;
	case 'quick-add-foods-to-menu':
		$f = post('f',[]); $child_id = post('child_id',[]);
		$new = post('new',[]);
		$count = post('count',0);
		$existed = explode(',', post('existed'));
		$m = load_model('foods');
		if(!empty($new)){
			foreach ($new as $v){
				if($v['title'] != "" && (new Query())->from($m->tableName())
						->where(['title'=>$v['title'],'sid'=>__SID__])->count(1) == 0){
							$v['sid'] = __SID__;
							$child_id[] = Yii::$app->zii->insert($m->tableName(),$v);
				}
			}
		}
		
		$new2 = explode(';', post('new2',''));
		$xitem = [] ;
		if(!empty($new2)){
			foreach ($new2 as $k=>$v){
				if(trim($v) != ""){
					$item = (new Query())->from($m->tableName())->where(['title'=>trim($v),'sid'=>__SID__])->one();
					
					if(!empty($item)){
						$id = $item['id'];
						$xitem[] = $item;
					}else{
						$id = Yii::$app->zii->insert($m->tableName(),['title'=>trim($v),'sid'=>__SID__]);
						$xitem[] = (new Query())->from($m->tableName())->where(['id'=>$id,'sid'=>__SID__])->one();
					}
					//$child_id[] = $id;
				}
			}
		}
		
		$r = [];
		$r['html']  = '';
		if(!empty($child_id)){
			$l = ($m->getList(['listItem'=>false,'limit'=>1000,'in'=>$child_id]));
			
			if(!empty($l)){
				foreach ($l as $k=>$v){
					$count++;
					$existed[] = $v['id'];
					$r['html'] .= '<tr><td class="center">'.($count).'
							<input type="hidden" name="menus[]" value="'.$v['id'].'"/>
							</td>
		<td class="">'.uh($v['title']).'</td>
		<td>
				<input
				
				data-menu_id="7"
				data-food_id="12"
				data-action="Ad_quick_change_menus_position"
				data-field="position" class="center w100 number-format" type="number" value="'.$count.'">
				</td>
		<td class="center"><i onclick="removeTrItem(this)" class="fa fa-trash f12px pointer"></i></td>
						
		</tr>';
					
				}
			}
		}
		if(!empty($xitem)){
			foreach ($xitem as $k=>$v){
				$count++;
				$existed[] = $v['id'];
				$r['html'] .= '<tr class="move-content"><td class="center">'.($count).'
							<input type="hidden" name="menus[]" value="'.$v['id'].'"/>
							</td>
		<td class="">'.uh($v['title']).'</td>
				
		<td class="center"><i onclick="removeTrItem(this)" class="fa fa-trash f12px pointer"></i></td>
				
		</tr>';
				
			}
		}
		
		
		
		
		
		
		$r['event'] = $_POST['action'];
		$r['existed'] = $existed;
		$r['count'] = $count;
		echo json_encode($r);exit;
		break;
	case 'add-foods-to-menu':
		$r = array(); $r['html'] = '';
		$existed = post('existed');
		
		
		$r['html'] .= '<div class="form-group hide">
          <label class="col-sm-12 control-label aleft">Chọn các món ăn có sẵn</label>
          <div class="col-sm-12 group-sm34">
 <select name="child_id[]" multiple data-existed="'.$existed.'" data-role="chosen-load-foods" class="form-control ajax-chosen-select-ajax" style="width:100%">';
		$m = load_model('foods');
		$l = $m->getList(['listItem'=>false,'limit'=>100,'not_in'=>$existed]);
		if(!empty($l)){
			foreach ($l as $k=>$v){
				$r['html'] .= '<option value="'.$v['id'].'">'.uh($v['title']).'</option>';
			}
		}
		$r['html'] .= '</select></div></div>';
		
		$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label hide aleft">Thêm món ăn </label>
          <div class="col-sm-12">
		<input data-action="load_foods" data-delimiter=" " class="form-control input-sm autocomplete tagsinput1" type="text" name="new2" value="" placeholder="Nhập tên món ăn"/>
				
		<p class="help-block italic f11px">Chọn từ danh sách có sẵn hoặc nhập mới cách nhau bởi dấu chấm phẩy ";"</p>
				
	<table class="table table-bordered vmiddle hide">
<thead><th class="center w50p">#</th><th class="center">Tên món ăn</th> </thead>
<tbody>';
		
		for($i=0;$i<5;$i++){
			//$r['html'] .= '<tr><td class="center">'.($i+1).'</td><td class=""><input class="form-control input-sm" type="text" name="new['.$i.'][title]" value="" placeholder="Tên món ăn"/></td></tr>';
		}
		
		
		
		
		
		
		$r['html'] .= '</tbody>
		</table>
				
            </div>
         </div>';
		
		
		$r['html'] .= '<div class="modal-footer">';
		$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$r['html'] .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		
		$r['event'] = $_POST['action'];
		//	$r['existed'] = $existed;
		echo json_encode($r);exit;
		break;
	case 'changeNotificationState':
		$id = post('id',0);
		Yii::$app->db->createCommand()->update('notifications',['state'=>1],['sid'=>__SID__,'id'=>$id])->execute();
		exit;
		break;
	case 'changeOrderPrice':
		$discount = post('discount',0);
		$vat = post('vat',0);
		$currency = post('currency',1);
		$price = post('price',0);
		$p = ($price - $discount) * (($vat+100))/100;
		
		echo json_encode([
				'price' => $p,
				'price_after'=>Yii::$app->zii->showPrice($p,$currency),
				'price_text'=>docso($p)
		]);
		exit;
		break;
		
	case 'setDefaultCurrency':
		$v = Yii::$app->zii->getCurrency(post('id',1));
		echo json_encode($v); exit;
		break;
	case 'get_item_link':
		
		$url = post('url','');
		
		echo cu(parse_url_post($url),true);
		exit;
		break;
	case 'get_local_not_in_group':
		$r = []; $html = '';
		$id = post('id');
		$not_in = post('not_in');
		$m = load_model('nationality_groups');
		$l = $m->get_all_local_other($id,$not_in);
		if(!empty($l)){
			foreach ($l as $k=>$v){
				$html .= '<option selected value="'.$v['id'].'">'.$v['name'].'</option>';
			}
		}
		$r['html'] = $html;
		echo json_encode($r); exit;
		break;
	case 'quick-add-more-ticket':
		$f = post('f',0);
		$id = post('id',0);
		$supplier_id = post('supplier',0);
		if($id>0) $f['id'] = $id;
		$prices = isset($_POST['prices']) ? $_POST['prices'] : [];
		if(!empty($f)){
			$m = load_model('tickets');
			if(isset($f['id']) && $f['id'] > 0){
				$id = $f['id']; unset($f['id']);
				Yii::$app->db->createCommand()->update($m->tableName(),$f,['id'=>$id])->execute();
			}else{
				//
				$f['sid'] = __SID__;
				
				Yii::$app->db->createCommand()->insert($m->tableName(),$f)->execute();
				$id = Yii::$app->db->createCommand("select max(id) from ".$m->tableName())->queryScalar();
				
			}
			//
			$lang_code = 'text_ticket_' . $id ;
			Yii::$app->db->createCommand()->update(\app\modules\admin\models\Tickets::tableName(),['lang_code'=>$lang_code],['id'=>$id])->execute();
			//
			if($supplier_id>0){
				Yii::$app->db->createCommand()->insert($m->tableToSupplier(),[
						'item_id'=>$id,
						'supplier_id'=>$supplier_id
				])->execute();
			}
			
			// update price
			Yii::$app->db->createCommand()->delete($m->tableToPrices(),['item_id'=>$id])->execute();
			Yii::$app->db->createCommand()->delete($m->tableToNationalityGroup(),['ticket_id'=>$id])->execute();
			if(!empty($prices)){
				foreach ($prices as $k=>$v){
					// $k = $season_id
					if(!empty($v)){
						foreach ($v as $k1=>$v1){
							// $k1 = $group_id
							Yii::$app->db->createCommand()->insert($m->tableToNationalityGroup(),['ticket_id'=>$id,'group_id'=>$k1])->execute();
							//view($a);
							// $currency = $v1['currency]
							if(!empty($v1['list'])){
								foreach ($v1['list'] as $k2=>$v2){
									// $k2 = guest_id
									Yii::$app->db->createCommand()->insert($m->tableToPrices(),[
											'group_id'=>$k1,
											'guest_group_id'=>$k2,
											'season_id'=>$k,
											'price1'=>cprice($v2['price1']),
											'currency'=>$v1['currency'],
											'item_id'=>$id])->execute();
									
								}
							}
						}
					}
				}
			}
		}
		$r['event'] = 'hide-modal';
		//$r['delay'] = 2000;
		$r['callback'] = true;
		$r['callback_function'] = 'show_left_small_loading(\'hide\');';
		echo json_encode($r);exit;
		break;
	case 'add-more-nationality-group-to-tickets':
		$r = array(); $r['html'] = '';
		$m =load_model('nationality_groups');
		$type_id = isset($_POST['type_id']) ? $_POST['type_id'] : 2;
		///view($type_id);
		//
		$existed = post('existed');
		//
		$l4 = $m->get_all_supplier_group(0,['not_in'=>$existed,'state'=>2]) ;
		$r['html'] = '<div class="form-group">';
		$r['html'] .= '<div class="group-sm34 col-sm-12"><select name="f[child_id][]" multiple data-existed="'.$existed.'" data-role="chosen-load-season" class="form-control ajax-chosen-select-ajax" style="width:100%">';
		if(!empty($l4)){
			foreach ($l4 as $k4=>$v4){
				
				$r['html'] .= '<option value="'.$v4['id'].'">'.$v4['title'].' ('.$v4['count_local'] .' quốc gia)</option>';
				
			}
		}
		$r['html'] .= '</select></div>';
		$r['html'] .= '</div><p class="help-block italic ">*** Bạn có thể chọn giá trị có sẵn hoặc thêm mới ở ô nhập bên dưới.</p><hr>';
		$r['html'] .= '<div class="">';
		
		$r['html'] .= '<div class="group-sm34"><p>Thêm mới nhóm</p>';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
		$r['html'] .= '<tbody class="">';
		
		for($i=0; $i<1;$i++){
			
			$r['html'] .= '<tr>
    				<td class="pr"><input type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][title]" placeholder="Tên nhóm"/></td>';
			$r['html'] .= '</tr>';
			
			$r['html'] .= '<tr><td>';
			$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label aleft">Quốc gia trong nhóm</label>
          <div class="col-sm-12">
					
              <select name="new['.$i.'][local_id][]" multiple data-existed="'.$existed.'" data-role="chosen-load-country" class="form-control ajax-chosen-select-ajax" style="width:100%">
              		
              		
              		
          </select>
              		<label class="mgt15"><input data-action="get_local_not_in_group" data-id="'.post('id').'" onchange="get_local_not_in_group(this)" type="checkbox" /> Chọn tất cả các quốc gia</label>
              		</div>
         </div>';
			
			$r['html'] .= '</td></tr>';
			
		}
		
		$r['html'] .= '</tbody></table>';
		$r['html'] .= '</div>';
		
		
		$r['html'] .= '<div class="group-sm34"><p>Danh sách các nhóm đã thêm</p>';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
		$r['html'] .= '<thead><tr>
    				<th>Tên nhóm</th>
    				<th class="coption"></th>
    				</tr></thead>';
		$r['html'] .= '<tbody class="">';
		$l = $m -> get_supplier_group(post('id'));
		if(!empty($l)){
			foreach ($l as $k=>$v){
				
				$r['html'] .= '<tr>
    				<td class="pr"><a>'.$v['title'].' <i>('.$v['count_local'].' quốc gia)</i></a></td>
    				<td class="center">
    						<a target="_blank" href="'.AdminMenu::get_menu_link('nationality_groups').'?supplier_id='.post('id').'" class="btn btn-link edit_item  icon">Sửa</a>
    						<a data-action="quick_delete_nationality_group_supplier" data-group_id="'.$v['id'].'" data-supplier_id="'.post('id').'" onclick="return quick_delete_nationality_group_supplier(this)" class="btn btn-link delete_item icon" data-title="Xóa bản ghi này ?" title="">Xóa</a>
    						</td>';
				$r['html'] .= '</tr>';
			}}
			
			$r['html'] .= '</tbody></table>';
			$r['html'] .= '</div>';
			
			$r['html'] .= '</div>';
			//
			$r['html'] .= '<div class="modal-footer">';
			$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
			$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
			$r['html'] .= '</div>';
			$_POST['action'] = 'quick-' . $_POST['action'];
			foreach ($_POST as $k=>$v){
				$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
			}
			///
			
			$r['event'] = $_POST['action'];
			$r['existed'] = $existed;
			echo json_encode($r);exit;
			
			break;
	case 'quick-add-more-nationality-group-to-tickets':
		$r = [];
		
		$f = isset($_POST['f']) ? $_POST['f'] : array();
		$child_id = isset($f['child_id']) ? $f['child_id'] : [];
		$new = isset($_POST['new']) ? $_POST['new'] : array();
		$m = load_model('nationality_groups');
		//$supplier_id = post('id');
		
		if(!empty($new)){
			foreach ($new as $k=>$v){
				if(trim($v['title'] != "")){
					
					Yii::$app->db->createCommand()->insert($m->tableName(),['title'=>$v['title'],'state'=>2,'sid'=>__SID__])->execute();
					$group_id = Yii::$app->db->createCommand("select max(id) from ".$m->tableName())->queryScalar();
					
					//Yii::$app->db->createCommand()->insert($m->table_to_supplier(),['group_id'=>$group_id],['supplier_id',$supplier_id]);
					if(isset($v['local_id']) && !empty($v['local_id'])){
						foreach ($v['local_id'] as $k1=>$v1){
							Yii::$app->db->createCommand()->insert($m->tableToLocal(),['group_id'=>$group_id,'local_id'=>$v1])->execute();
						}
					}
					$child_id[] = $group_id;
				}
			}
		}
		$r['html'] = '';
		if(!empty($child_id)){
			$m1 = load_model('guest_groups');
			$guests = $m1->getAll();
			$l = $m->getListByID($child_id);
			if(!empty($l)){
				foreach ($l as $k=>$v){
					$r['html'] .= '<tr><td>'.$v['title'].'</td>';
					if(!empty($guests)){
						foreach ($guests as $g){
							$r['html'] .= '<td><input data-decimal="'.Yii::$app->zii->showCurrency(isset($p['currency']) && $p['currency'] ? $p['currency'] : 1,3).'" name="prices[0]['.$v['id'].'][list]['.$g['id'].'][price1]" class="form-control bold input-sm aright ajax-number-format input-currency-price-'.$v['id'].'" /></td>';
						}
					}
					$r['html'] .= '<td class="group-sm30">';
					//if(isset(\ZAP\Zii::$site['other_setting']['currency']['list'])){
					$r['html'] .= '<select data-target-input=".input-currency-price-'.$v['id'].'" data-decimal="'.Yii::$app->zii->showCurrency((isset($p['currency']) && $p['currency'] ? $p['currency'] : 1),3).'" onchange="get_decimal_number(this);" class="ajax-select2-no-search sl-cost-price-currency form-control select2-hide-search input-sm" name="prices[0]['.$v['id'].'][currency]">';
					
					foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v2){
						$r['html'] .= '<option value="'.$v2['id'].'" '.(isset($p['currency']) && $p['currency'] == $v2['id'] ? 'selected' : '').'>'.$v2['code'].'</option>';
					}
					
					$r['html'] .= '</select>';
					//}
					$r['html'] .= '</td><td class="center"><input type="hidden" value="'.$v['id'].'" class="input-hidden-id"/><i class="glyphicon glyphicon-trash pointer" onclick="removeTrItem(this);reload_existed_btn(this);"></i></td></tr>';
				}
			}
		}
		
		//$r['target'] = $_POST['target'];
		$r['event'] = $_POST['action'];
		$r['existed'] = $child_id;
		echo json_encode($r);exit;
		break;
	case 'ajax-form-add-new':
		$r = array(); $r['html'] = '';
		$existed = post('existed');
		$id = post('id',0);// isset($_POST['id']) && $_POST['id'] > 0 ? $_POST['id'] : 0;
		
		//
		switch (post('controller')){
			case 'tickets':
				$m2 = load_model('tickets');
				//$p2 = load_model('departure_places');
				$item = $m2->getItem($id);
				
				$place_id = post('place',0);
				if($place_id > 0) $item['place_id'] = $place_id;
				
				$r['html'] = '<div class="form-group"><label class="bold fl100">Địa danh</label>';
				$r['html'] .= '<div class="group-sm30 fl100">
<select name="f[place_id]" data-existed="'.$existed.'" data-role="chosen-load-place" 
class="form-control ajax-chosen-select-ajax" style="width:100%">';
				if(isset($item['place_id']) && $item['place_id'] > 0){
					$place = \app\modules\admin\models\Places::getItem($item['place_id']);
					if(!empty($place)){
						$r['html'] .= '<option selected value="'.$place['id'].'">'.$place['title'].'</option>';
					}
				}
				$r['html'] .= '</select></div>';
				$r['html'] .= '</div>';
				
				
				$r['html'] .= '<div class="">
		    				<label class="bold">Tiêu đề</label>
    <input type="text" class="form-control required" placeholder="Tiêu đề" value="'.(isset($item['title']) ? uh($item['title']) : '').'" name="f[title]">
    		
		    				';
				$r['html'] .= '<div class="mgt15"><label class="bold">Bảng giá</label><table class="table vmiddle table-hover table-bordered">';
				$r['html'] .= '<thead><tr><th>Nhóm khách</th>';
				
				$m = load_model('guest_groups');
				$guests = $m->getAll();
				if(!empty($guests)){
					foreach ($guests as $g){
						$r['html'] .= '<th class="center">'.$g['title'].'</th>';
					}
				}
				
				$r['html'] .= '<th class="center">Tiền tệ</th><th class="center"></th>';
				$r['html'] .= '</tr></thead>';
				
				$r['html'] .= '<tbody class="ajax-load-group-nationality">';
				
				$existed = [];
				$l = $m2->getNationalityGroup(post('id'));
				if(!empty($l)){
					foreach ($l as $k=>$v){
						$p = $m2->get_prices($id,$v['id']);
						//view($p);
						$existed[] = $v['id'];
						$r['html'] .= '<tr><td>'.$v['title'].'</td>';
						if(!empty($guests)){
							foreach ($guests as $g){
								$r['html'] .= '<td><input value="'.(isset($p[$g['id']]['price1']) ? $p[$g['id']]['price1'] : '').'" data-decimal="'.Yii::$app->zii->showCurrency(isset($p[$g['id']]['currency']) ? $p[$g['id']]['currency'] : 1,3).'" name="prices[0]['.$v['id'].'][list]['.$g['id'].'][price1]" class="form-control bold input-sm aright ajax-number-format input-currency-price-'.$v['id'].'" /></td>';
							}
						}
						$r['html'] .= '<td class="group-sm30">';
						//if(isset(\ZAP\Zii::$site['other_setting']['currency']['list'])){
						$r['html'] .= '<select data-target-input=".input-currency-price-'.$v['id'].'" data-decimal="'.Yii::$app->zii->showCurrency(isset($p[$g['id']]['currency']) ? $p[$g['id']]['currency'] : 1,3).'" onchange="get_decimal_number(this);" class="ajax-select2-no-search sl-cost-price-currency form-control select2-hide-search input-sm" name="prices[0]['.$v['id'].'][currency]">';
						
						foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v2){
							$r['html'] .= '<option value="'.$v2['id'].'" '.(isset($p[$g['id']]['currency']) && $p[$g['id']]['currency'] == $v2['id'] ? 'selected' : '').'>'.$v2['code'].'</option>';
						}
						
						$r['html'] .= '</select>';
						//}
						$r['html'] .= '</td><td class="center"><input type="hidden" value="'.$v['id'].'" class="input-hidden-id"/><i class="glyphicon glyphicon-trash pointer" onclick="removeTrItem(this);reload_existed_btn(this);"></i></td></tr>';
					}
				}
				$r['html'] .= '</tbody></table>';
				
				
				
				$r['html'] .= '</div></div>';
				
				$r['html'] .= '<p class="aright btn-list-add-more-1">
		    				<button data-class="w60" data-action="add-more-nationality-group-to-tickets" data-title="Thêm nhóm quốc tịch" data-existed="'.implode(',', $existed).'" type="button" data-id="0" data-target=".ajax-result-nationality-group" onclick="open_ajax_modal(this);" data-modal-target=".mymodal1" class="btn btn-warning btn-add-more"><i class="glyphicon glyphicon-plus"></i> Thêm nhóm quốc tịch</button>
		    				</p><hr/>';
				
				$_POST['action'] = 'quick-add-more-ticket';
				break;
		}
		
		
		
		//
		$r['html'] .= '<div class="modal-footer">';
		$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$r['html'] .= '</div>';
		
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		$r['supplier_id'] = post('supplier');
		$r['event'] = $_POST['action'];
		$r['existed'] = $existed;
		echo json_encode($r);exit;
		
		break;
	case 'load_distance_to_element':
		//$q = $_POST['data']['q'];
		$place = post('place');
		$existed = post('existed');
		$type_id = isset($_POST['type_id']) && $_POST['type_id'] > 0 ? $_POST['type_id'] : 0;
		//
		$sql = "select a.id,a.title from distances as a where a.state>0 and a.is_active=1";
		$sql .= $type_id > 0 ? " and a.type_id=$type_id" : "";
		$sql .= $existed !="" ? " and a.id not in($existed)" : "";
		//
		if($place != "" && $place != 'null'){
			$sql .= " and a.id in (select distance_id from distance_to_places where place_id in ($place) ".($type_id > 0 ? " and type_id=$type_id" : '').")";
		}
		//
		$sql .= " limit 200";
		$l = Yii::$app->db->createCommand($sql)->queryAll();
		$r = '';
		if(!empty($l)){
			foreach ($l as $k=>$v){
				$r .= '<option value="'.$v['id'].'">'.$v['title'].'</option>';
			}
		}
		echo $r; exit;
		break;
	case 'add_new_cost_distance':
		$f = post('f',[]);
		$fn = post('fn',[]);
		$existed = isset($_POST['existed']) ? $_POST['existed'] : '';
		$existed = $existed != "" ? explode(',', $existed) : array();
		$index = post('index') >0 ? post('index') : 0;
		$html = '';$m = load_model('cars');
		// Check them moi
		
		if(!empty($fn)){
			foreach ($fn as $k=>$v){
				$v['title'] = trim($v['title']);
				if(strlen($v['title'])>2){
					$d = load_model('distances');
					if(Yii::$app->zii->countTable($m->tableDistances(),array(
							'title'=>$v['title'],
							'sid'=>__SID__,
							'type_id'=>$f['type_id']
					)) == 0){
						$v['sid'] = __SID__;
						$v['type_id'] = $f['type_id'];
						$nid = $d->get_max_id($d->tableName());
						Yii::$app->db->command()->insert($m->tableDistances(),$v+array('id',$nid))->execute();
						$f['distance_id'][] = $nid;
						//view($nid,true);
						//
						if(isset($f['place_id']) && strlen($f['place_id'])>0){
							foreach (explode(',', $f['place_id']) as $t){
								Yii::$app->db->command()->insert('distance_to_places',array(
										'distance_id'=>$nid,
										'place_id'=>$t,
										'type_id',$f['type_id']))->execute();
							}
						}
					}else{
						
					}
				}
			}
		}
		//
		
		$r = array();
		//
		$l3 = $m->get_list_cars_by_seats($f['id'],array('is_active'=>1));
		if(isset($f['distance_id']) && !empty($f['distance_id']) && !empty($l3)){
			$l4 = $m->get_distance_by_id($f['distance_id']);
			if(!empty($l4)){
				foreach ($l4 as $k4=>$v4){
					$existed[] = $v4['id']; $index++;
					$currency[$v4['id']] = $dactive[$v4['id']] = 1;
					foreach ($l3 as $k3=>$v3){
						if(!isset($r[$k3])) $r[$k3] = '';
						$r[$k3] .= '<tr class="tr-distance-id-'.$v4['id'].'"><td>'.$v4['title'].'</td>';
						if(!empty($v3)){
							foreach ($v3 as $c3=>$t3){
								
								$r[$k3] .= '<td class="center"><input name="dprice['.$k3.']['.$v4['id'].']['.$t3['id'].'][price1]" class=" sui-input sui-input-focus w100 ajax-number-format aright sl-cost-price input-currency-price-'.$v4['id'].'-'.$k3.'" data-decimal="'.Yii::$app->zii->showCurrency(isset($currency[$v4['id']]) ? $currency[$v4['id']] : 1,3).'" value="" /></td>';
								$r[$k3] .= '<td class="center"><input name="dprice['.$k3.']['.$v4['id'].']['.$t3['id'].'][price2]" class=" sui-input sui-input-focus w100 ajax-number-format aright sl-cost-price input-currency-price-'.$v4['id'].'-'.$k3.'" data-decimal="'.Yii::$app->zii->showCurrency(isset($currency[$v4['id']]) ? $currency[$v4['id']] : 1,3).'" value="" /></td>';
							}
						}
						$r[$k3] .= '<td class="center w100p">';
						//if(isset(Zii::$site['other_setting']['currency']['list'])){
						$r[$k3] .= '<select data-target-input=".input-currency-price-'.$v4['id'].'-'.$k3.'" data-decimal="'.Yii::$app->zii->showCurrency(isset($currency[$v4['id']]) ? $currency[$v4['id']] : 1,3).'" onchange="get_decimal_number(this);" class="ajax-select2-no-search sl-cost-price-currency form-control select2-hide-search input-sm" name="dcurrency['.$k3.']['.$v4['id'].'][currency]">';
						//if(isset($v['currency']['list']) && !empty($v['currency']['list'])){
						foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v2){
							$r[$k3] .= '<option value="'.$v2['id'].'" '.(isset($currency[$v4['id']]) && $currency[$v4['id']] == $v2['id'] ? 'selected' : '').'>'.$v2['code'].'</option>';
						}
						//}
						
						$r[$k3] .= '</select>';
						//}
						$r[$k3] .= '</td>';
						$r[$k3] .= '<td class="center">'.getCheckBox(array(
								'name'=>'dactive['.$k3.']['.$v4['id'].'][is_active]',
								'value'=>$dactive[$v4['id']],
								'type'=>'singer',
								'class'=>'switchBtn ajax-switch-btn',
								//'cvalue'=>true,
								
						)).'</td>';
						$r[$k3] .= '<td class="center"><i title="Xóa" data-name="delete_price_distance_id" onclick="remove_item_class(this);" data-confirm="Lưu ý: Chặng '.$v4['title'].' sẽ không còn được áp dụng cho toàn bộ các phương tiện của doanh nghiệp này. Bạn có chắc chắn ?" data-target=".tr-distance-id-'.$v4['id'].'" class="pointer glyphicon glyphicon-trash"></i></td>';
						$r[$k3] .= '</tr>';
						
					}
				}}
		}
		
		echo json_encode(array('event'=>post('action'),'r'=>$r,'index'=>$index,'target_class'=>'ajax-result-price-distance-','existed'=>implode(',',  $existed)));
		exit;
		break;
		
	case 'auto_load_car_list':
		$id = $_POST['id'];
		$m = load_model('cars');
		$lx = $m->getListCars($id);
		$sloption = '<select name="'.$_POST['name'].'" class="ajax-select2 sui-input sui-input-focus w100 numberFormat center sl-cost-car_id">';
		if(!empty($lx)){
			foreach ($lx as $k2=>$v2){
				$sloption .= '<option value="'.$v2['id'].'">'.$v2['title'].'</option>';
			}
		}
		$sloption .= '</select>';
		echo $sloption;
		exit;
		break;
	case 'quick_add_more_vehicle_category':
		$m = app\modules\admin\controllers\Vehicles_categorysController();
		$id = $m->add(false);
		$item = $m->model->getItem($id);
		$html = '';
		if(!empty($item)){
			$html = '';
		}
		echo json_encode(array('id'=>$id,'html'=>$html, 'event'=>$_POST['action']));
		exit;
		break;
	case 'check_vehicle_category_existed':
		
		$val = post('val');
		$id = post('id',0);
		$a = $id > 0 ?
		(new Query())->from('vehicles_categorys')->where(['title'=>$val,'sid'=>__SID__])
		->andWhere(['not in','id',$id])
		->count(1) :
		(new Query())->from('vehicles_categorys')->where(['title'=>$val,'sid'=>__SID__])->count(1);
		echo json_encode(array(
				'state'=>$a > 0 ? true : false
		));
		exit;
		break;
	case 'set_quantity_vehicles_categorys':
		$f = isset($_POST['f']) ? $_POST['f'] : array();
		$existed_id = isset($_POST['existed_id']) ? $_POST['existed_id'] : '';
		$existed_id = $existed_id != "" ? explode(',', $existed_id) : array();
		$index = post('index') >0 ? post('index') : 0;
		$html = '';
		if(!empty($f)){
			foreach ($f as $k=>$v){
				
				if($v['quantity'] > 0){
					$index++;
					$existed_id[] = $v['id'];
					$html .= '<tr><td></td>';
					$html .= '<td>'.$v['title'].'</td>';
					$html .= '<td>'.$v['maker_name'].'</td>';
					$html .= '<td><input type="text" name="c['.$index.'][quantity]" value="'.$v['quantity'].'" class="form-control center input-sm ajax-number-format"/><input type="hidden" name="c['.$index.'][id]" value="'.$v['id'].'"/><input type="hidden" name="c['.$index.'][title]" value="'.$v['title'].'"/><input type="hidden" name="c['.$index.'][maker_id]" value="'.$v['maker_id'].'"/><input type="hidden" name="c['.$index.'][maker_name]" value="'.$v['maker_name'].'"/><input type="hidden" name="c['.$index.'][is_active]" value="1"/></td><td></td><td></td>';
					$html .= '</tr>';
				}
			}
		}
		echo json_encode(array('event'=>post('action'),'html'=>$html,'index'=>$index,'target_class'=>'ajax-html-result-before-list-vehicles','existed_id'=>implode(',',  $existed_id)));
		exit;
		break;
	case 'get_list_vehicles_makers':
		$id = post('id',0);
		$index = post('index',0);
		$html = '';
		$m = load_model('vehicles_categorys');
		$l = $m->getAvailableVehicle(array(
				'limit'=>1000, 'maker_id'=>$id,
				'type_id'=>$_POST['type_id'],
				'supplier_id'=>post('supplier_id')
		));
		if(!empty($l)){
			foreach ($l as $k=>$v){
				$index += $k;
				$html .= '<tr><td class="w50p">'.($k+1).'</td>';
				$html .= '<td>'.$v['title'].'</td>';
				$html .= '<td class="w150p">'.$v['maker_name'].'</td>';
				$html .= '<td class="w100p"><input type="text" name="f['.$v['id'].'][quantity]" value="" class="form-control center input-sm ajax-number-format"/>
						
    								</td>';
				$html .= '</tr>';
			}
		}
		//$html .= '</tbody></table>';
		echo $html;
		exit;
		break;
	case 'quick-add-more-room-group':
		$r = [];
		$f = post('f',[]);
		$new = post('new',[]);
		$supplier_id = post('supplier_id',post('id',0));
		$delete_price_distance_id = post('delete_price_distance_id',[]);
		if(!empty($delete_price_distance_id)){
			Yii::$app->db->createCommand()->delete('{{%rooms_groups}}',array('id'=>$delete_price_distance_id,'sid'=>__SID__))->execute();
		}
		if(!empty($f)){
			foreach ($f as $k=>$v){
				Yii::$app->db->createCommand()->update('{{%rooms_groups}}',$v,['id'=>$v['id'],'sid'=>__SID__])->execute();
			}
		}
		if(!empty($new)){
			foreach ($new as $k=>$v){
				if($v['pmin'] > -1 && $v['pmax'] > $v['pmin'] && $v['title'] != ""){
					$v['sid'] = __SID__;
					if(isset($v['supplier_id'])) unset($v['supplier_id']);
					$id = Yii::$app->zii->insert('{{%rooms_groups}}',$v);
					if($id>0){
						
					}
				}
			}
		}
		if(post('update_quotation') == 'on'){
			$controller_code = post('controller_code',post('type_id'));
			switch ($controller_code){
				case TYPE_ID_VECL: case TYPE_ID_HOTEL:
					$incurred_prices = \app\modules\admin\models\Seasons::get_rooms_groups($supplier_id,false);
					if(!empty($incurred_prices)){
						foreach ($incurred_prices as $k=>$v){
							Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice($controller_code,post('price_type',1)),['group_id'=>$v['id']],[
									'supplier_id'=>$supplier_id,
									'group_id'=>0
							])->execute();
							break;
						}
					}
					break;
			}
		}
		//$r['target'] = $_POST['target'];
		$r['event'] = 'hide-modal';
		$r['existed'] = $_POST;
		$r['callback']=true;
		$r['callback_function'] = 'reloadAutoPlayFunction(true);';
		echo json_encode($r);exit;
		break;
	case 'add-more-room-group':
		$r = array(); $r['html'] = '';
		$supplier_id = post('supplier_id',post('id',0));
		$m =load_model('rooms_categorys');
		$type_id = isset($_POST['type_id']) ? $_POST['type_id'] : 2;
		///view($type_id);
		//
		$existed = post('existed');
		//
		//$l4 = in_array($type_id,[3,4]) ? $m->get_weekend(['limit'=>100,'not_in'=>($existed != "" ? explode(',', $existed) : [])]) : $m->getList(['limit'=>100,'not_in'=>($existed != "" ? explode(',', $existed) : [])]);
		$r['html'] = '<div class="form-group"><div class="form_quick_remove_item"></div>';
		$r['html'] .= '<div class="group-sm34 col-sm-12">';
		//$r['html'] .= '<div class="group-sm34">';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered"><thead><tr>';
		$r['html'] .= '<th class="center w150p">Từ</th><th class="center w150p">Đến</th><th class="center">Tên nhóm</th><th class="center">Ghi chú</th>';
		$r['html'] .= '<th class="w50p"></th></tr></thead><tbody class="">';
		
		$l = \app\modules\admin\models\Seasons::get_rooms_groups($supplier_id,false);
		
		if(!empty($l)){
			foreach ($l as $k=>$v){
				$r['html'] .= '<tr>
    					<td><input type="text" class="sui-input form-control input-sm w100 center ajax-number-format" value="'.$v['pmin'].'" name="f['.$k.'][pmin]" placeholder="Số lượng tối thiểu"/></td>
    					<td><input type="text" class="sui-input form-control input-sm w100 center ajax-number-format" value="'.$v['pmax'].'" name="f['.$k.'][pmax]" placeholder="Số lượng tối đa"/></td>
    					<td class="center "><input type="text" class="sui-input w100 form-control input-sm" value="'.$v['title'].'" name="f['.$k.'][title]" placeholder="Tên nhóm"/></td>
    					<td class="center "><input type="text" class="sui-input w100 form-control input-sm" value="'.$v['note'].'" name="f['.$k.'][note]" placeholder="Ghi chú"/><input type="hidden" value="'.$v['id'].'" name="f['.$k.'][id]"/> </td>
    					<td class="center"><i title="Xóa" data-name="delete_price_distance_id" data-name="removed_group_id" onclick="add_delete_item('.$v['id'].',this);removeTrItem(this);" class="pointer glyphicon glyphicon-trash"></i></td>
    							';
				$r['html'] .= '</tr>';
			}
		}
		
		$r['html'] .= '</tbody></table>';
		//$r['html'] .= '</div>';
		$r['html'] .= '</div>';
		$r['html'] .= '</div><p class="help-block italic ">*** Bạn có thể thêm mới nhóm ở ô nhập bên dưới. Sau khi lưu, load lại trình duyệt để nhận dữ liệu mới.</p><hr>';
		$r['html'] .= '<div class="">';
		
		$r['html'] .= '<div class="group-sm34">';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered"><thead><tr>';
		$r['html'] .= '<th class="center w150p">Từ</th><th class="center w150p">Đến</th><th class="center">Tên nhóm</th><th class="center">Ghi chú</th>';
		$r['html'] .= '</tr></thead><tbody class="">';
		
		for($i=0; $i<3;$i++){
			
			$r['html'] .= '<tr>
    					<td><input type="text" class="sui-input form-control input-sm w100 center ajax-number-format input-condition-required input-destination-required" value="" name="new['.$i.'][pmin]" placeholder="Số lượng tối thiểu"/></td>
    					<td><input type="text" class="sui-input form-control input-sm w100 center ajax-number-format input-condition-required input-destination-required" value="" name="new['.$i.'][pmax]" placeholder="Số lượng tối đa"/></td>
    					<td class="center "><input type="text" class="sui-input w100 form-control input-sm input-condition-required input-destination-required" value="" name="new['.$i.'][title]" placeholder="Tên nhóm"/></td>
    					<td class="center "><input type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][note]" placeholder="Ghi chú"/><input type="hidden" value="'.$supplier_id.'" name="new['.$i.'][parent_id]" /></td>';
			$r['html'] .= '</tr>';
			
		}
		$controller_code = post('controller_code');
		switch ($controller_code){
			case TYPE_ID_VECL: case TYPE_ID_HOTEL:
				$c  = (new Query())->from(Yii::$app->zii->getTablePrice($controller_code,post('price_type',1)))->where([
				'supplier_id'=>$supplier_id,
				'group_id'=>0
				])->count(1);
				if($c>0){
					$r['html'] .= '<tr><td colspan="5"><p><b class="red ">Opp! Chúng tôi nhận thấy rằng có <span class="underline">'.number_format($c).'</span> đơn giá đã nhập cho đơn vị này nhưng chưa thuộc nhóm nào.</b></p>
							
					<p class="bold green">Bạn có muốn cập nhật vào nhóm [đầu tiên] trong danh sách nhóm của nhà cung cấp này không ?</p>
					<label><input name="update_quotation" type="checkbox"/> Cập nhật ngay</label>
							<p class="help-block italic red "><b class="underline">Lưu ý: </b> Khi cài đặt nhóm, các đơn giá (đã nhập trước đó) mà không thuộc 1 nhóm nào sẽ bị xóa.</p></td></tr>';
				}
				break;
		}
		
		$r['html'] .= '</tbody></table>';
		$r['html'] .= '</div>';
		$r['html'] .= '</div>';
		//
		$r['html'] .= '<div class="modal-footer">';
		$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Đóng</button>';
		$r['html'] .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		
		$r['event'] = $_POST['action'];
		$r['existed'] = $existed;
		echo json_encode($r);exit;
		
		break;
	case 'quick-add-more-nationality-group-to-supplier':
		$r = [];
		$f = isset($_POST['f']) ? $_POST['f'] : array();
		$child_id = isset($f['child_id']) ? $f['child_id'] : [];
		$new = isset($_POST['new']) ? $_POST['new'] : array();
		$m = load_model('nationality_groups');
		
		$supplier_id = post('supplier_id',post('id',0));
		//view($new,true);
		if(!empty($new)){
			foreach ($new as $k=>$v){
				if(trim($v['title'] != "")){
					Yii::$app->db->createCommand()->insert($m->tableName(),['title'=>$v['title'],'sid'=>__SID__])->execute();
					$group_id = Yii::$app->db->createCommand("select max(id) from ".$m->tableName())->queryScalar();
					if(isset($v['local_id']) && !empty($v['local_id'])){
						foreach ($v['local_id'] as $k1=>$v1){
							Yii::$app->db->createCommand()->insert($m->tableToLocal(),['group_id'=>$group_id,'local_id'=>$v1])->execute();
						}
					}
					$child_id[] = $group_id;
				}
			}
		}
		
		if(!empty($child_id)){
			foreach ($child_id as $group_id){
				Yii::$app->db->createCommand()->insert($m->tableToSupplier(),['group_id'=>$group_id,'supplier_id'=>$supplier_id])->execute();
				
			}
		}
		if(post('update_quotation') == 'on'){
			$l = \app\modules\admin\models\NationalityGroups::get_supplier_group($supplier_id);
			if(!empty($l)){
				foreach ($l as $k=>$v){
					Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice(post('controller_code'),post('price_type',1)),[
							'nationality_id'=>$v['id']
					],[
							'supplier_id'=>$supplier_id,
							'nationality_id'=>0
					])->execute();
					break;
				}
			}
		}
		$r['callback'] = true;
		$r['callback_function'] = 'reloadAutoPlayFunction(true);';
		$r['event'] = 'hide-modal';
		//$r['existed'] = $existed;
		echo json_encode($r);exit;
		break;
		
		
	case 'quick_delete_nationality_group_supplier':
		/*
		 $m =load_model('nationality_groups');
		 $sql = "delete a,b,c from {$m->tableName()} as a
		 left join {$m->table_to_local()} as b on a.id=b.group_id
		 left join {$m->table_to_supplier()} as c on a.id=c.group_id
		 where a.state=1 and c.supplier_id=".post('supplier_id') . " and c.group_id=".post('group_id');
		 Yii::$app->db->createCommand($sql)->execute();
		 $sql = "delete c from {$m->table_to_supplier()} as c where c.supplier_id=".post('supplier_id') . "
		 and c.group_id=".post('group_id');
		 Yii::$app->db->createCommand($sql)->execute();
		 */
		Yii::$app->db->createCommand()->delete('nationality_groups_to_supplier',[
		'group_id'=>post('group_id',0),
		'supplier_id'=>post('supplier_id',0),
		])->execute();
		exit;
		break;
	case 'quick-sadd-more-package-price-to-supplier':
		//
		$supplier_id = post('supplier_id',0);
		$type_id = post('type_id',0);
		$child_id = post('child_id',[]);
		$new = post('new',[]);
		if(!empty($new)){
			foreach ($new as $k=>$v){
				if(trim($v['title']) != ""){
					$v['sid'] = __SID__;
					$v['supplier_id'] = $supplier_id;
					$v['type_id'] = $type_id;
					$child_id[] = Yii::$app->zii->insert('package_prices',$v);
				}
			}
		}
		if(!empty($child_id)){
			foreach ($child_id as $package_id){
				Yii::$app->db->createCommand()->insert('package_to_supplier',[
						'supplier_id'=>$supplier_id,
						'package_id'=>$package_id
				])->execute();
			}
		}
		//
		if(post('update_quotation') == 'on'){
			$controller_code = post('controller_code',post('type_id'));
			switch ($controller_code){
				case TYPE_ID_VECL:
					$incurred_prices = \app\modules\admin\models\PackagePrices::getPackages($supplier_id,false);
					if(!empty($incurred_prices)){
						foreach ($incurred_prices as $k=>$v){
							Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice($controller_code,post('price_type',1)),['package_id'=>$v['id']],[
									'supplier_id'=>$supplier_id,
									'package_id'=>0
							])->execute();
							break;
						}
					}
					break;
			}
		}
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>'reloadAutoPlayFunction(true);',
				'p'=>$_POST
		]);exit;
		break;
		
	case 'sadd-more-package-price-to-supplier':
		
		$supplier_id = post('supplier_id',0);
		$html = '';
		//$m = new app\modules\admin\models\PackagePrices();
		//$existed = post('existed',[]);
		//view($existed,true);
		//$l4 = $m->getList(['not_in'=>$existed]) ;
		$html .= '<div class="form-group">';
		$html .= '<div class="group-sm34 col-sm-12"><select data-placeholder="Chọn 1 hoặc nhiều package đã có" name="child_id[]" multiple data-role="chosen-load-package" class="form-control ajax-chosen-select-ajax" style="width:100%">';
		//if(!empty($l4['listItem'])){
		foreach (\app\modules\admin\models\PackagePrices::getAvailabledPackages($supplier_id,[
				'type_id'=>post('type_id')
		]) as $k4=>$v4){
			
			$html .= '<option value="'.$v4['id'].'">'.$v4['title'].' </option>';
			
		}
		//}
		
		$html .= '</select></div>';
		$html .= '</div>
				<p class="help-block italic ">*** Bạn có thể chọn giá trị có sẵn hoặc thêm mới ở ô nhập bên dưới.</p>
				<hr>';
		$html .= '<div class="">';
		
		$html .= '<div class="group-sm34"><p>Thêm mới package</p>';
		$html .= '<table class="table vmiddle table-hover table-bordered">';
		$html .= '<tbody class="">';
		
		for($i=0; $i<3;$i++){
			
			$html .= '<tr>
    				<td class="pr"><input type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][title]" placeholder="Tên nhóm"/></td>';
			$html .= '</tr>';
			
			
			
		}
		
		$html .= '</tbody></table>';
		$html .= '</div>';
		
		
		$html .= '<div class="group-sm34"><p>Danh sách các nhóm đã thêm</p>';
		$html .= '<table class="table vmiddle table-hover table-bordered">';
		$html .= '<thead><tr>
    				<th>Tên nhóm</th>
    				<th class="coption"></th>
    				</tr></thead>';
		$html .= '<tbody class="">';
		
		$l = \app\modules\admin\models\PackagePrices::getPackages($supplier_id,false);
		if(!empty($l)){
			$role = [
					'type'=>'single',
					'table'=>\app\modules\admin\models\PackagePrices::tableName(),
					//'controller'=>Yii::$app->controller->id,
					'action'=>'Ad_quick_change_item'
			];
			foreach ($l as $k=>$v){
				$role['id']=$v['id'];
				$role['action'] = 'Ad_quick_change_item';
				$html .= '<tr class="tr-item-odr-'.$supplier_id.'-'.$v['id'].'">'.Ad_list_show_qtext_field($v,[
						'field'=>'title',
						'class'=>'number-format aleft',
						'decimal'=>0,
						'role'=>$role
				]).'
						
    				<td class="center pr">
    						<i data-controller_code="'.post('controller_code').'" data-modal-target=".mymodal1" data-trash="1" data-action="open-confirm-dialog" data-title="Xác nhận xóa package !" data-class="modal-sm" data-confirm-action="quick_delete_package_supplier" data-package_id="'.$v['id'].'" data-supplier_id="'.$supplier_id.'" onclick="return open_ajax_modal(this);" class="pointer fa fa-trash-o f12e" data-toggle="tooltip" data-placement="top" title="Tạm xóa, sau này có thể thêm trở lại từ ô chọn bên trên. Toàn bộ đơn giá đã nhập cho package này sẽ bị xóa."></i>
    						<a data-controller_code="'.post('controller_code').'" data-modal-target=".mymodal1" data-trash="0" data-action="open-confirm-dialog" data-title="Xác nhận xóa package !" data-class="modal-sm" data-confirm-action="quick_delete_package_supplier" data-package_id="'.$v['id'].'" data-supplier_id="'.$supplier_id.'" onclick="return open_ajax_modal(this);" class="btn btn-link delete_item icon" data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn bản ghi này. Toàn bộ dữ liệu đã nhập cho package này sẽ bị xóa.">Xóa</a>
    						</td>';
				$html .= '</tr>';
			}}else{
				$html .= '<tr><td colspan="2"><p><b class="red ">Bạn chưa sử dụng package nào.</b></p>
						
						<p class="help-block italic red "><b class="underline">Lưu ý: </b> Khi cài đặt package, các đơn giá (đã nhập trước đó) mà không thuộc 1 package nào sẽ bị xóa.</p></td></tr>';
			}
			switch (post('controller_code')){
				case TYPE_ID_VECL:
					$c  = (new Query())->from(Yii::$app->zii->getTablePrice(TYPE_ID_VECL,post('price_type',1)))->where([
					'supplier_id'=>$supplier_id,
					'package_id'=>0
					])->count(1);
					if($c>0){
						$html .= '<tr><td colspan="5"><p><b class="red ">Opp! Chúng tôi nhận thấy rằng có <span class="underline">'.number_format($c).'</span> đơn giá đã nhập cho đơn vị này nhưng chưa thuộc nhóm nào.</b></p>
								
					<p class="bold green">Bạn có muốn cập nhật vào nhóm [đầu tiên] trong danh sách nhóm của nhà cung cấp này không ?</p>
					<label><input name="update_quotation" type="checkbox"/> Cập nhật ngay</label>
							<p class="help-block italic red "><b class="underline">Lưu ý: </b> Khi cài đặt nhóm, các đơn giá (đã nhập trước đó) mà không thuộc 1 nhóm nào sẽ bị xóa.</p></td></tr>';
					}
					break;
			}
			$html .= '</tbody></table>';
			$html .= '</div>';
			
			$html .= '</div>';
			//
			$html .= '<div class="modal-footer">';
			$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
			$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
			$html .= '</div>';
			$_POST['action'] = 'quick-' . $_POST['action'];
			foreach ($_POST as $k=>$v){
				$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
			}
			///
			
			echo json_encode([
					'html'=>$html,
					'callback'=>true,
					'callback_function'=>'jQuery(\'[data-toggle="tooltip"]\').tooltip();'
			]);exit;
			
			break;
			
	case 'add-more-package-price-to-supplier':
		$r = array(); $r['html'] = '';
		$m = new app\modules\admin\models\PackagePrices();
		$existed = post('existed',[]);
		//view($existed,true);
		$l4 = $m->getList(['not_in'=>$existed]) ;
		$r['html'] = '<div class="form-group">';
		$r['html'] .= '<div class="group-sm34 col-sm-12"><select name="f[child_id][]" multiple data-existed="'.$existed.'" data-role="chosen-load-package" class="form-control ajax-chosen-select-ajax" style="width:100%">';
		if(!empty($l4['listItem'])){
			foreach ($l4['listItem'] as $k4=>$v4){
				
				$r['html'] .= '<option value="'.$v4['id'].'">'.$v4['title'].' </option>';
				
			}
		}
		$r['html'] .= '</select></div>';
		$r['html'] .= '</div><p class="help-block italic ">*** Bạn có thể chọn giá trị có sẵn hoặc thêm mới ở ô nhập bên dưới.</p><hr>';
		$r['html'] .= '<div class="">';
		
		$r['html'] .= '<div class="group-sm34"><p>Thêm mới package</p>';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
		$r['html'] .= '<tbody class="">';
		
		for($i=0; $i<3;$i++){
			
			$r['html'] .= '<tr>
    				<td class="pr"><input type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][title]" placeholder="Tên nhóm"/></td>';
			$r['html'] .= '</tr>';
			
			
			
		}
		
		$r['html'] .= '</tbody></table>';
		$r['html'] .= '</div>';
		
		
		$r['html'] .= '<div class="group-sm34"><p>Danh sách các nhóm đã thêm</p>';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
		$r['html'] .= '<thead><tr>
    				<th>Tên nhóm</th>
    				<th class="coption"></th>
    				</tr></thead>';
		$r['html'] .= '<tbody class="">';
		
		$l = $m -> getPackages(post('id'));
		if(!empty($l)){
			$role = [
					'type'=>'single',
					'table'=>$m->tableName(),
					//'controller'=>Yii::$app->controller->id,
					'action'=>'Ad_quick_change_item'
			];
			foreach ($l as $k=>$v){
				$role['id']=$v['id'];
				$role['action'] = 'Ad_quick_change_item';
				$r['html'] .= '<tr>'.Ad_list_show_qtext_field($v,[
						'field'=>'title',
						'class'=>'number-format aleft',
						'decimal'=>0,
						'role'=>$role
				]).'
						
    				<td class="center">
						
    						<a data-action="quick_delete_package_supplier" data-id="'.$v['id'].'" data-supplier_id="'.post('id').'" onclick="return quick_delete_package_supplier(this)" class="btn btn-link delete_item icon" data-title="Xóa bản ghi này ?" title="">Xóa</a>
    						</td>';
				$r['html'] .= '</tr>';
			}}
			
			$r['html'] .= '</tbody></table>';
			$r['html'] .= '</div>';
			
			$r['html'] .= '</div>';
			//
			$r['html'] .= '<div class="modal-footer">';
			$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
			$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
			$r['html'] .= '</div>';
			$_POST['action'] = 'quick-' . $_POST['action'];
			foreach ($_POST as $k=>$v){
				$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
			}
			///
			
			$r['event'] = $_POST['action'];
			$r['existed'] = $existed;
			echo json_encode($r);exit;
			
			break;
	case 'add-more-nationality-group-to-supplier':
		$r = array(); $r['html'] = '';
		
		$m = new \app\modules\admin\models\NationalityGroups();
		$type_id = isset($_POST['type_id']) ? $_POST['type_id'] : 2;
		$supplier_id = post('supplier_id',post('id',0));
		//
		$existed = post('existed');
		
		$l4 = $m->get_all_supplier_group($supplier_id,['not_in'=>$existed]) ;
		$r['html'] = '<div class="form-group">';
		$r['html'] .= '<div class="group-sm34 col-sm-12"><select name="f[child_id][]" multiple data-existed="'.$existed.'" data-role="chosen-load-season" class="form-control ajax-chosen-select-ajax" style="width:100%">';
		if(!empty($l4)){
			foreach ($l4 as $k4=>$v4){
				
				$r['html'] .= '<option value="'.$v4['id'].'">'.$v4['title'].' ('.$v4['count_local'] .' quốc gia)</option>';
				
			}
		}
		$r['html'] .= '</select></div>';
		$r['html'] .= '</div><p class="help-block italic ">*** Bạn có thể chọn giá trị có sẵn hoặc thêm mới ở ô nhập bên dưới. Quản lý danh sách nhóm <a class="btn-link bold f12e underline red" target="_blank" href="'.AdminMenu::get_menu_link('nationality_groups').'?supplier_id='.$supplier_id.'">tại đây</a></p><hr>';
		$r['html'] .= '<div class="">';
		
		$r['html'] .= '<div class="group-sm34"><p>Thêm mới nhóm</p>';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
		$r['html'] .= '<tbody class="">';
		
		for($i=0; $i<1;$i++){
			
			$r['html'] .= '<tr>
    				<td class="pr"><input type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][title]" placeholder="Tên nhóm"/></td>';
			$r['html'] .= '</tr>';
			
			$r['html'] .= '<tr><td>';
			$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label aleft">Quốc gia trong nhóm</label>
          <div class="col-sm-12">
					
              <select name="new['.$i.'][local_id][]" multiple data-existed="'.$existed.'" data-role="chosen-load-country" class="form-control ajax-chosen-select-ajax" style="width:100%">
              		
              		
              		
          </select>
              		<label class="mgt15"><input data-action="get_local_not_in_group" data-id="'.$supplier_id.'" onchange="get_local_not_in_group(this)" type="checkbox" /> Các quốc gia chưa thuộc nhóm nào</label>
              		</div>
         </div>';
			
			$r['html'] .= '</td></tr>';
			
		}
		
		$r['html'] .= '</tbody></table>';
		$r['html'] .= '</div>';
		
		
		$r['html'] .= '<div class="group-sm34"><p>Danh sách các nhóm đã thêm</p>';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
		$r['html'] .= '<thead><tr>
    				<th>Tên nhóm</th>
    				<th class="coption"></th>
    				</tr></thead>';
		$r['html'] .= '<tbody class="">';
		
		$l = $m -> get_supplier_group($supplier_id);
		if(!empty($l)){
			foreach ($l as $k=>$v){
				
				$r['html'] .= '<tr>
    				<td class="pr"><a>'.$v['title'].' <i>('.$v['count_local'].' quốc gia)</i></a></td>
    				<td class="center">
    						<a target="_blank" href="'.AdminMenu::get_menu_link('nationality_groups').'?supplier_id='.$supplier_id.'" class="btn btn-link edit_item  icon">Sửa</a>
    						<a data-action="quick_delete_nationality_group_supplier" data-group_id="'.$v['id'].'" data-supplier_id="'.$supplier_id.'" onclick="return quick_delete_nationality_group_supplier(this)" class="btn btn-link delete_item icon" data-title="Xóa bản ghi này ?" title="">Xóa</a>
    						</td>';
				$r['html'] .= '</tr>';
			}}
			
			switch (post('controller_code')){
				case TYPE_ID_VECL: case TYPE_ID_GUIDES:
					$c  = (new Query())->from(Yii::$app->zii->getTablePrice(post('controller_code'),post('price_type',1)))->where([
					'supplier_id'=>$supplier_id,
					'nationality_id'=>0
					])->count(1);
					if($c>0){
						$r['html'] .= '<tr><td colspan="5"><p><b class="red ">Opp! Chúng tôi nhận thấy rằng có <span class="underline">'.number_format($c).'</span> đơn giá đã nhập cho đơn vị này nhưng chưa thuộc nhóm nào.</b></p>
								
					<p class="bold green">Bạn có muốn cập nhật vào nhóm [đầu tiên] trong danh sách nhóm của nhà cung cấp này không ?</p>
					<label><input name="update_quotation" type="checkbox"/> Cập nhật ngay</label>
							<p class="help-block italic red "><b class="underline">Lưu ý: </b> Khi cài đặt nhóm quốc tịch, các đơn giá (đã nhập trước đó) mà không thuộc 1 nhóm nào sẽ bị xóa.</p></td></tr>';
					}
					break;
			}
			
			$r['html'] .= '</tbody></table>';
			//
			
			//
			$r['html'] .= '</div>';
			
			$r['html'] .= '</div>';
			//
			$r['html'] .= '<div class="modal-footer">';
			$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
			$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
			$r['html'] .= '</div>';
			$_POST['action'] = 'quick-' . $_POST['action'];
			foreach ($_POST as $k=>$v){
				$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
			}
			///
			
			$r['event'] = $_POST['action'];
			$r['existed'] = $existed;
			echo json_encode($r);exit;
			
			break;
			
	case 'quick-add-more-package-price-to-supplier':
		$m = load_model('package_prices');
		$new = post('new',[]);
		$f = post('f');
		$f['child_id'] = isset($f['child_id']) ? $f['child_id']: [];
		if(!empty($new)){
			foreach ($new as $k=>$v){
				
				if(trim($v['title']) != ""){
					
					Yii::$app->db->createCommand()->insert($m->tableName(),[
							'title'=>$v['title'],
							'sid'=>__SID__,
							'supplier_id'=>post('id',0),
							
					])->execute();
					$new_id = Yii::$app->db->createCommand("select max(id) from ".$m->tableName())->queryScalar();
					$f['child_id'][] = $new_id;
					//
					
					//
				}
				
			}
		}
		if(!empty($f['child_id'])){
			foreach ($f['child_id'] as $c){
				Yii::$app->db->createCommand()->insert($m->tableToSupplier(),['package_id'=>$c,'supplier_id'=>post('id')])->execute();
			}
		}
		
		$r['event'] = 'hide-modal';
		$r['callback']=true;
		$r['callback_function'] = 'reloadAutoPlayFunction(true);';
		echo json_encode($r);exit;
		break;
	case 'quick-add-more-season-to-supplier':
		$r = $existed = array();
		$m = new app\modules\admin\models\Seasons();
		$f = isset($_POST['f']) ? $_POST['f'] : array();
		//$existed = isset($_POST['existed']) ? $_POST['existed'] : array();
		//
		$type_id = isset($_POST['type_id']) ? $_POST['type_id'] : 2;
		
		
		$r['html'] = '';
		$id = $_POST['id']; $season_id = $_POST['season_id'];
		$new = isset($_POST['new']) ? $_POST['new'] : array();
		
		
		$i=0;
		if(!empty($new)){
			foreach ($new as $k=>$v){
				if(in_array($type_id,[3,4,5])){
					if(trim($v['from_time']) != "" && trim($v['to_time']) != ""){
						//$v['type_id'] = Zii::$db->getField($m->tableCategory(), 'type_id',['id'=>$season_id]);
						$v['type_id'] = (new Query())->select(['type_id'])->from($m->tableCategory())->where(['id'=>$season_id])->scalar();
						
						Yii::$app->db->createCommand()->insert($m->tableWeekend(),[
								'title'=>$v['title'],
								'sid'=>__SID__,
								'from_date'=>$v['from_date'],
								'to_date'=>$v['to_date'],
								'from_time'=>$v['from_time'],
								'to_time'=>$v['to_time'],
								'parent_id'=>$season_id,'type_id'=>$v['type_id']
						])->execute();
						
						$new_id = Yii::$app->db->createCommand("select max(id) from ".$m->tableWeekend())->queryScalar();
						if($new_id > 0){
							//$existed[] = $new_id;
							if(isset($f['child_id'])) $f['child_id'][] = $new_id;
							else $f['child_id'] = [$new_id];
							
						}
					}
				}else{
					if(trim($v['title']) != ""){
						
						Yii::$app->db->createCommand()->insert($m->tableName(),[
								'title'=>$v['title'],
								'sid'=>__SID__,
								'from_date'=>ctime(['string'=>$v['from_date']]),
								'to_date'=>ctime(['string'=>$v['to_date']]),
								'parent_id'=>$season_id,'type_id'=>10
						])->execute();
						$new_id = Yii::$app->db->createCommand("select max(id) from ".$m->tableName())->queryScalar();
						if($new_id > 0){
							//$existed[] = $new_id;
							if(isset($f['child_id'])) $f['child_id'][] = $new_id;
							else $f['child_id'] = [$new_id];
							
						}
					}
				}
				
			}
		}
		$i=0;
		if(isset($f['child_id']) && !empty($f['child_id'])){
			if(in_array($type_id,[3,4,5])){
				$l4 = $m->get_weekend(['in'=>$f['child_id'],'not_in'=>$existed]);
				if(!empty($l4['listItem'])){
					foreach ($l4['listItem'] as $k=>$v){
						$existed[] = $v['id'];
						$r['html'] .= '<tr class="tr-distance-id-'.$v['id'].'">';
						$r['html'] .= '<td class="center">'.$v['from_time'].' '.read_date($v['from_date']).'</td>';
						$r['html'] .= '<td class="center">'.$v['to_time'].' '.read_date($v['to_date']).'</td>';
						$r['html'] .= '<td><a>'.$v['title'].'</a><input type="hidden" value="'.$v['id'].'" name="seasons['.$season_id.'][list_child]['.$v['id'].'][id]"/></td>';
						$r['html'] .= '<td class="center"><i title="Xóa" data-name="delete_price_distance_id" onclick="removeTrItem(this);" data-target=".tr-distance-id-'.$v['id'].'" class="pointer glyphicon glyphicon-trash"></i></td>';
						$r['html'] .= '</tr>';
					}
				}
			}else{
				$l4 = $m->getList(['in'=>$f['child_id'],'not_in'=>$existed]);
				if(!empty($l4['listItem'])){
					foreach ($l4['listItem'] as $k=>$v){
						$existed[] = $v['id'];
						$r['html'] .= '<tr class="tr-distance-id-'.$v['id'].'">';
						$r['html'] .= '<td class="">'.date("d/m/Y",strtotime($v['from_date'])).'</td>';
						$r['html'] .= '<td class="center">'.date("d/m/Y",strtotime($v['to_date'])).'</td>';
						$r['html'] .= '<td><a>'.$v['title'].'</a><input type="hidden" value="'.$v['id'].'" name="seasons['.$season_id.'][list_child]['.$v['id'].'][id]"/></td>';
						$r['html'] .= '<td class="center"><i title="Xóa" data-name="delete_price_distance_id" onclick="removeTrItem(this);" data-target=".tr-distance-id-'.$v['id'].'" class="pointer glyphicon glyphicon-trash"></i></td>';
						$r['html'] .= '</tr>';
					}
				}
			}
		}
		
		
		
		$r['target'] = $_POST['target'];
		$r['event'] = $_POST['action'];
		$r['existed'] = $existed;
		echo json_encode($r);exit;
		break;
	case 'add-more-season-to-supplier':
		$r = array(); $r['html'] = '';
		$m = new app\modules\admin\models\Seasons();
		$type_id = isset($_POST['type_id']) ? $_POST['type_id'] : 2;
		///view($type_id);
		//
		$existed = post('existed');
		//
		$l4 = in_array($type_id,[3,4,5]) ? $m->get_weekend(['limit'=>100,'type_id'=>$type_id,'not_in'=>($existed != "" ? explode(',', $existed) : [])]) : $m->getList(['limit'=>100,'not_in'=>($existed != "" ? explode(',', $existed) : [])]);
		$r['html'] = '<div class="form-group">';
		$r['html'] .= '<div class="group-sm34 col-sm-12"><select name="f[child_id][]" multiple data-existed="'.$existed.'" data-role="chosen-load-season" class="form-control ajax-chosen-select-ajax" style="width:100%">';
		if(!empty($l4['listItem'])){
			foreach ($l4['listItem'] as $k4=>$v4){
				if(in_array($type_id,[3,4,5])){
					$r['html'] .= '<option value="'.$v4['id'].'">['.$v4['title'].'] '.$v4['from_time'] . ' ' . read_date($v4['from_date']). ' -> ' . $v4['to_time'] . ' ' . read_date($v4['to_date']) .'</option>';
				}else{
					$r['html'] .= '<option value="'.$v4['id'].'">'.$v4['title'].' ('.date("d/m/Y",strtotime($v4['from_date'])) .' - ' . date("d/m/Y",strtotime($v4['to_date'])) .')</option>';
				}
			}
		}
		$r['html'] .= '</select></div>';
		$r['html'] .= '</div><p class="help-block italic ">*** Bạn có thể chọn giá trị có sẵn hoặc thêm mới ở ô nhập bên dưới</p><hr>';
		$r['html'] .= '<div class="">';
		
		$r['html'] .= '<div class="group-sm34">';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
		$r['html'] .= '<tbody class="">';
		
		for($i=0; $i<3;$i++){
			if(in_array($type_id,[3,4,5])){
				$r['html'] .= '<tr>
    					<td><select class="form-control input-sm ajax-select2-no-search"  name="new['.$i.'][from_date]">';
				for($j = 0;$j<7;$j++){
					$r['html'] .= '<option value="'.$j.'">'.read_date($j).'</option>';
				}
				$r['html'] .= '</select></td>
    					<td><input type="text" class="sui-input form-control input-sm ajax-timepicker" value="" name="new['.$i.'][from_time]" placeholder="Thời gian bắt đầu"/></td>
    					<td><select class="form-control input-sm ajax-select2-no-search" name="new['.$i.'][to_date]">';
				for($j = 0;$j<7;$j++){
					$r['html'] .= '<option value="'.($j == 0 ? 7 : $j).'">'.read_date($j).'</option>';
				}
				$r['html'] .= '</select></td>
    					<td class="center "><input type="text" class="sui-input w100 form-control input-sm ajax-timepicker" value="" name="new['.$i.'][to_time]" placeholder="Thời gian kết thúc"/></td>
    					<td class=""><input type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][title]" placeholder="Tiêu đề"/></td>';
				$r['html'] .= '</tr>';
			}else{
				$r['html'] .= '<tr>
    					<td class="pr"><input onblur="addrequired_input(this);" type="text" class="sui-input form-control input-sm ajax-datepicker" value="" name="new['.$i.'][from_date]" placeholder="Thời gian bắt đầu"/></td>
    					<td class="center pr"><input onblur="addrequired_input(this);" type="text" class="sui-input w100 form-control input-sm ajax-datepicker" value="" name="new['.$i.'][to_date]" placeholder="Thời gian kết thúc"/></td>
    					<td class="center "><input onblur="addrequired_input(this);" type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][title]" placeholder="Tiêu đề"/> </td>';
				$r['html'] .= '</tr>';
			}
		}
		
		$r['html'] .= '</tbody></table>';
		$r['html'] .= '</div>';
		$r['html'] .= '</div>';
		//
		$r['html'] .= '<div class="modal-footer">';
		$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
		$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$r['html'] .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		
		$r['event'] = $_POST['action'];
		$r['existed'] = $existed;
		echo json_encode($r);exit;
		
		break;
		
		
		
		
	case 'quick-add-more-season-category-to-supplier':
		$m =load_model('seasons');
		$f = post('f',[]);
		$new = isset($_POST['new']) ? $_POST['new'] : [];
		$supplier_id = post('supplier_id',post('id',0));
		$new_cate = post('new_cate',[]);
		$code = \app\modules\admin\models\Seasons::get_incurred_charge_type(post('type_id'))[0]['id'];
		if(!empty($new_cate)){
			foreach ($new_cate as $c){
				Yii::$app->db->createCommand()->insert($m->table_category_to_supplier(),[
						'season_id'=>$c,
						'supplier_id'=>$supplier_id,
						'price_type'=>$code
				])->execute();
			}
		}
		
		if($f['title'] != ""){
			$season_category_id = Yii::$app->zii->insert($m->tableCategory(),array(
					'title'=>$f['title'],
					'code'=>'CUSTOMIZE',
					'type_id'=>$f['type_id'],
					'position'=>254,
					'sid'=>__SID__,
					'created_by'=>$supplier_id
			));
			Yii::$app->db->createCommand()->insert($m->table_category_to_supplier(),[
					'season_id'=>$season_category_id,
					'supplier_id'=>post('id'),
					'price_type'=>$code
			])->execute();
			if(!empty($new) && $season_category_id>0){
				foreach ($new as $k=>$v){
					switch ($f['type_id']){
						case SEASON_TYPE_TIME:
							if(trim($v['title']) != ""){
								$season_id = Yii::$app->zii->insert('weekend',[
										'title'=>$v['title'],
										'sid'=>__SID__,
										'from_date'=>$v['from_date'],
										'to_date'=>$v['to_date'],
										//'from_time'=>isset($v['from_time']) ? $v['from_time'] : '00:00:00',
										//'to_time'=>isset($v['to_time']) ? $v['to_time'] : '00:00:00',
										'part_time'=>isset($v['part_time']) ? $v['part_time'] : 0,
										//'parent_id'=>$season_category_id,
										'type_id'=>$f['type_id']
								]);
								Yii::$app->db->createCommand()->insert($m->tableToSuppliers(),[
										'season_id'=>$season_id,
										'parent_id'=>$season_category_id,
										'supplier_id'=>$supplier_id,
										'type_id'=>$f['type_id']])->execute();
							}
							break;
						case SEASON_TYPE_WEEKEND: case SEASON_TYPE_WEEKDAY:
							if(trim($v['title']) != "" && trim($v['from_time']) != "" && trim($v['to_time']) != ""){
								$season_id = Yii::$app->zii->insert('weekend',[
										'title'=>$v['title'],
										'sid'=>__SID__,
										'from_date'=>$v['from_date'],
										'to_date'=>$v['to_date'],
										'from_time'=>isset($v['from_time']) ? $v['from_time'] : '00:00:00',
										'to_time'=>isset($v['to_time']) ? $v['to_time'] : '00:00:00',
										//'part_time'=>isset($v['part_time']) ? $v['part_time'] : 0,
										//'parent_id'=>$season_category_id,
										'type_id'=>$f['type_id']
								]);
								Yii::$app->db->createCommand()->insert($m->tableToSuppliers(),[
										'season_id'=>$season_id,
										'parent_id'=>$season_category_id,
										'supplier_id'=>$supplier_id,
										'type_id'=>$f['type_id']])->execute();
							}
							break;
						default :
							if($v['from_date'] != "" && $v['to_date'] != ""){
								
								$season_id = Yii::$app->zii->insert($m->tableName(),[
										//'parent_id'=>$season_category_id,
										'title'=>$v['title'],
										'type_id'=>$f['type_id'],
										'sid'=>__SID__,
										'from_date'=>ctime(['string'=>$v['from_date']]),
										'to_date'=>ctime(['string'=>$v['to_date'],'format'=>'Y-m-d']) . ' 23:59:59',
								]);
								
								Yii::$app->db->createCommand()->insert($m->tableToSuppliers(),[
										'season_id'=>$season_id,
										'parent_id'=>$season_category_id,
										'supplier_id'=>$supplier_id,
										'type_id'=>$f['type_id']])->execute();
								
							}
							break;
					}
					
				}
			}
		}
		
		if(post('update_quotation') == 'on'){
			$controller_code = post('controller_code',post('type_id'));
			switch ($controller_code){
				case TYPE_ID_VECL:
					$incurred_prices = \app\modules\admin\models\Customers::getCustomerSeasons($supplier_id);
					if(!empty($incurred_prices)){
						foreach ($incurred_prices as $k=>$v){
							if(!in_array($v['type_id'] ,[3,4,5])){
								Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice($controller_code,1),['season_id'=>$v['id']],[
										'supplier_id'=>$supplier_id,
										'season_id'=>0
								])->execute();
								Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice($controller_code,2),['season_id'=>$v['id']],[
										'supplier_id'=>$supplier_id,
										'season_id'=>0
								])->execute();
								
								break;
							}
						}
					}
					break;
			}
		}
		
		if(post('update_weekend') == 'on'){
			$controller_code = post('controller_code',post('type_id'));
			switch ($controller_code){
				case TYPE_ID_VECL:
					$incurred_prices = \app\modules\admin\models\Customers::getCustomerSeasons($supplier_id);
					if(!empty($incurred_prices)){
						foreach ($incurred_prices as $k=>$v){
							if(in_array($v['type_id'] ,[3,4])){
								Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice($controller_code,1),['weekend_id'=>$v['id']],[
										'supplier_id'=>$supplier_id,
										'weekend_id'=>0
								])->execute();
								Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice($controller_code,2),['weekend_id'=>$v['id']],[
										'supplier_id'=>$supplier_id,
										'weekend_id'=>0
								])->execute();
								break;
							}
						}
					}
					break;
			}
		}
		
		
		$r['event'] = 'hide-modal';
		$r['callback'] = true;
		$r['callback_function'] = 'reloadAutoPlayFunction(true);';
		//$r['existed'] = $existed;
		echo json_encode($r);exit;
		break;
		
		
	case 'add-more-season-category-to-supplier':
		$r = array(); $r['html'] = '';
		$m =load_model('seasons');
		$type_id = isset($_POST['type_id']) ? $_POST['type_id'] : 2;
		$supplier_id = post('supplier_id',0);
		$controller_code = post('controller_code',post('type_id'));
		
		//
		$existed = post('existed');
		//
		
		//$r['html'] .= '</div><p class="help-block italic ">*** Bạn có thể chọn giá trị có sẵn hoặc thêm mới ở ô nhập bên dưới</p><hr>';
		
		$r['html'] .= '<div class="form-group"><div class="col-sm-12"><select name="new_cate[]" data-placeholder="Chọn các mùa có sẵn trên hệ thống" class="ajax-chosen-select-ajax" multiple>';
		
		foreach (\app\modules\admin\models\Seasons::getAvailableSeasons($type_id,post('id',0)) as $k1=>$v1){
			$r['html'] .= '<option value="'.$v1['id'].'">'.uh($v1['title']).'</option>';
		}
		
		$r['html'] .= '</select></div></div>';
		
		$r['html'] .= '<p class="help-block italic ">*** Bạn có thể chọn giá trị có sẵn hoặc thêm mới ở ô nhập bên dưới</p><hr>';
		
		$r['html'] .= '<div class="">
				<div class="option-list1 inline-block">
				<label class="pointer mgr15"><input class="input-rs-031719" onchange="change_form_add_supplier_season_category(this)" type="radio" value="2" name="f[type_id]" checked/> Khoảng thời gian</label>
				<label class="pointer mgr15"><input onchange="change_form_add_supplier_season_category(this)" type="radio" value="3" name="f[type_id]" /> Cuối tuần</label>
				<label class="pointer mgr15"><input onchange="change_form_add_supplier_season_category(this)" type="radio" value="4" name="f[type_id]" /> Ngày thường</label>
				<label class="pointer mgr15"><input onchange="change_form_add_supplier_season_category(this)" type="radio" value="5" name="f[type_id]" /> Buổi trong ngày</label>
				</div>
				';
		
		$r['html'] .= '<div class="group-sm34">';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
		$r['html'] .= '<tbody class="ajax-rs-0301">';
		
		$r['html'] .= '</tbody></table>';
		$r['html'] .= '</div>';
		
		$r['html'] .= '<div class="group-sm34">';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
		$r['html'] .= '<tbody class="s-rs-0301">';
		switch ($controller_code){
			case TYPE_ID_VECL:
				
				$c  = (new Query())->from(Yii::$app->zii->getTablePrice($controller_code,1))->where([
				'supplier_id'=>$supplier_id,
				'season_id'=>0
				])->count(1);
				$c1  = (new Query())->from(Yii::$app->zii->getTablePrice($controller_code,2))->where([
						'supplier_id'=>$supplier_id,
						'season_id'=>0
				])->count(1);
				$c = $c + $c1;
				if($c > 0){
					
					$r['html'] .= '<tr><td colspan="5"><p><b class="red ">Opp! Chúng tôi nhận thấy rằng có <span class="underline">'.number_format($c).'</span> đơn giá đã nhập cho đơn vị này nhưng chưa thuộc nhóm nào.</b></p>
							
					<p class="bold green">Bạn có muốn cập nhật vào nhóm [đầu tiên] trong danh sách nhóm của nhà cung cấp này không ?</p>
					<label><input name="update_quotation" type="checkbox"/> Cập nhật ngay</label>
							<p class="help-block italic red "><b class="underline">Lưu ý: </b> Khi cài đặt nhóm, các đơn giá (đã nhập trước đó) mà không thuộc 1 nhóm nào sẽ bị xóa.</p></td></tr>';
				}
				if(post('weekend') == 1){
					$c  = (new Query())->from(Yii::$app->zii->getTablePrice($controller_code,1))->where([
							'supplier_id'=>$supplier_id,
							'weekend_id'=>0
							
					])->count(1);
					$c  += (new Query())->from(Yii::$app->zii->getTablePrice($controller_code,2))->where([
							'supplier_id'=>$supplier_id,
							'weekend_id'=>0
							
					])->count(1);
					if($c > 0){
						
						$r['html'] .= '<tr><td colspan="5"><p><b class="red ">Opp! Chúng tôi nhận thấy rằng có <span class="underline">'.number_format($c).'</span> đơn giá đã nhập cho đơn vị này nhưng chưa thuộc <b class="green underline">nhóm ngày</b> nào.</b></p>
								
						<p class="bold green">Bạn có muốn cập nhật vào nhóm [đầu tiên] trong danh sách <b class="green underline">nhóm ngày</b> của nhà cung cấp này không ?</p>
						<label><input name="update_weekend" type="checkbox"/> Cập nhật ngay</label>
								<p class="help-block italic red "><b class="underline">Lưu ý: </b> Khi cài đặt nhóm, các đơn giá (đã nhập trước đó) mà không thuộc 1 nhóm nào sẽ bị xóa.</p></td></tr>';
					}
				}
				break;
		}
		
		$r['html'] .= '</tbody></table>';
		$r['html'] .= '</div>';
		
		$r['html'] .= '</div>';
		//
		$r['html'] .= '<div class="modal-footer">';
		$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$r['html'] .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		
		$r['event'] = $_POST['action'];
		$r['existed'] = $existed;
		$r['callback'] = true;
		$r['callback_function'] = 'change_form_add_supplier_season_category(jQuery(\'.input-rs-031719\'))';
		echo json_encode($r);exit;
		
		break;
	case 'quick-add-more-room-to-hotel':
		$r = $existed = array();
		$m = new \app\modules\admin\models\Hotels();
		$f = post('f',[]);
		$child_id = [];
		$supplier_id = post('supplier_id',post('id',0));
		//
		
		if(!isset($c['child_id'])) $c['child_id'] = [];
		if(!empty($f)){
			foreach ($f as $k=>$v){
				if(cprice($v['quantity'])>0){
					$c['child_id'][] = $v['id'];
					$child_id[] = $v['id'];
					//foreach ($child_id as $c){
					if((new Query())->from('rooms_to_hotel')->where([
							'parent_id'=>$supplier_id,
							'room_id'=>$v['id'],
					])->count(1) == 0){
						Yii::$app->db->createCommand()->insert('rooms_to_hotel',[
								'parent_id'=>$supplier_id,
								'room_id'=>$v['id'],
								'quantity'=>cprice($v['quantity'])
						])->execute();
					}
					//}
				}
			}
		}
		//
		
		$r['html'] = '';
		
		$new = post('new',[]);
		if(!empty($new)){
			foreach ($new as $k=>$v){
				if(trim($v['title']) != "" 
						&& (new Query())->from($m->tableRoomCategory())
						->where(array('sid'=>__SID__ ,'title'=>trim($v['title'])))->count(1) == 0){
					//
					
					Yii::$app->db->createCommand()->insert($m->tableRoomCategory(),[
							'title'=>$v['title'],
							'sid'=>__SID__,
							'seats'=>$v['seats']
					])->execute();
					
					$new_id = Yii::$app->db->createCommand("select max(id) from ".$m->tableRoomCategory())->queryScalar();
					if($new_id>0){
						$c['child_id'][] = $new_id;
						$f[$new_id]['quantity'] = $v['quantity'];
						
						if((new Query())->from('rooms_to_hotel')->where([
								'parent_id'=>$supplier_id,
								'room_id'=>$new_id,
						])->count(1) == 0){
							Yii::$app->db->createCommand()->insert('rooms_to_hotel',[
									'parent_id'=>$supplier_id,
									'room_id'=>$new_id,
									'quantity'=>cprice($v['quantity'])
							])->execute();
						}else{
							
						}
					}
				}
			}
		}
		
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>'reloadAutoPlayFunction(true);',
		]);exit;
		break;
	case 'quick_find_room_category':
		$m = new \app\modules\admin\models\RoomsCategorys();
		$l = $m->getList([
				'limit'=>1000,
				'filter_text'=>post('val'),
		]);
		$r = [];
		if(!empty($l['listItem'])){
			foreach ($l['listItem'] as $v){
				$r[] = $v['id'];
			}
		}
		echo json_encode(['state'=>true,'list'=>$r]); exit;
		break;
	case 'add-more-room-to-hotel':
		$r = array(); $r['html'] = '';
		//
		$supplier_id = post('supplier_id',post('id',0));
		$m = new \app\modules\admin\models\RoomsCategorys();
		$existed = post('existed');
		$l4 = $m->getList([
				'limit'=>1000,'count'=>false,
				'order_by'=>'a.title, a.seats',
				'not_in'=>strlen($existed) > 0 ? explode(',', $existed) : [],
		]);
		//
		$l4 = \app\modules\admin\models\RoomsCategorys::getAvailabledRooms([
				'supplier_id'=>$supplier_id,
				'order_by'=>'a.title, a.seats',
				'type_id'=>-1 //TYPE_CODE_ROOM_HOTEL
		]);
		
		
		
		$r['html'] .= '<div class="fl100" data-height="auto">';
		$r['html'] .= '<input type="text" onkeypress="if (event.keyCode==13){return false;};" onkeyup="if (event.keyCode==13){return false;};return quick_find_room_category(this);" placeholder="Tìm kiếm nhanh" value="" class="form-control input-sm keyup_event mgb5"/>
				<table class="table vmiddle table-hover table-bordered mgb-1" ><thead><tr>';
		$r['html'] .= '<th rowspan="2">Tiêu đề</th>';
		$r['html'] .= '<th rowspan="2" class="center w100p">Số chỗ</th>';
		$r['html'] .= '<th rowspan="2" class="center w100p">Số lượng</th>';
		
		$r['html'] .= '</tr></thead></table>';
		$r['html'] .= '<div class="group-sm34 div-slim-scroll" data-height="auto">';
		
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered"></thead><tbody class="">';
		//$r['html'] .= '<tr>
		//				<td><input type="text" onkeypress="if (event.keyCode==13){return false;};" onkeyup="if (event.keyCode==13){return false;};return quick_find_room_category(this);" placeholder="Tìm kiếm nhanh" value="" class="form-control input-sm keyup_event"/></td>
		//				<td class="w100p"></td><td class="w100p"></td>';
		//$r['html'] .= '</tr>';
		if(!empty($l4)){
			foreach ($l4 as $k=>$v){
				$r['html'] .= '<tr class=" tr_item tr_item_'.$v['id'].'">
    					<td>'.$v['title'].'</td>
    							<td class="center w100p">'.$v['seats'].'</td>
    					<td class="w100p"><input type="text" class="sui-input w100 form-control input-sm ajax-number-format center" value="" name="f['.$v['id'].'][quantity]" placeholder=""/><input type="hidden" value="'.$v['id'].'" name="f['.$v['id'].'][id]"/> </td>';
				$r['html'] .= '</tr>';
			}}
			
			$r['html'] .= '</tbody></table>';
			$r['html'] .= '</div>';
			$r['html'] .= '</div>';
			//
			$r['html'] .= '<div class="fl100"><p class="help-block">*** Nếu chưa có trong CSDL bạn có thể thêm nhanh tại đây.</p>';
			$r['html'] .= '<div class="group-sm34">';
			
			$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
			//$r['html'] .= '<th rowspan="2">Tiêu đề</th>';
			//$r['html'] .= '<th rowspan="2">Số chỗ</th>';
			//$r['html'] .= '<th rowspan="2" class="center w100p">Số lượng</th>';
			
			$r['html'] .= '<tbody class="">';
			
			for($i=0; $i<3;$i++){
				$r['html'] .= '<tr>
    					<td><input type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][title]" placeholder="Tiêu đề"/></td>
    					<td class="center w100p"><input type="text" class="sui-input w100 form-control input-sm ajax-number-format center" value="" name="new['.$i.'][seats]" placeholder="Số chỗ"/></td>
    					<td class="center w100p"><input type="text" class="sui-input w100 form-control input-sm ajax-number-format center" value="" name="new['.$i.'][quantity]" placeholder="Số lượng"/> </td>';
				$r['html'] .= '</tr>';
			}
			
			$r['html'] .= '</tbody></table>';
			$r['html'] .= '</div>';
			$r['html'] .= '</div>';
			//
			$r['html'] .= '<div class="modal-footer">';
			$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
			$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
			$r['html'] .= '</div>';
			$_POST['action'] = 'quick-' . $_POST['action'];
			foreach ($_POST as $k=>$v){
				$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
			}
			///
			
			$r['event'] = $_POST['action'];
			$r['existed'] = $existed;
			$r['callback'] = true;
			$r['callback_function'] = 'loadScrollDiv(); ';
			echo json_encode($r);exit;
			
			break;
		
			
	case 'set_quantity_currency':
		$f = isset($_POST['f']) ? $_POST['f'] : array();
		$existed_id = isset($_POST['existed_id']) ? $_POST['existed_id'] : '';
		$existed_id = $existed_id != "" ? explode(',', $existed_id) : array();
		//$count = post('count',0);
		$index = post('index') >0 ? post('index') : 0;
		$html = '';
		if(!empty($f)){
			foreach ($f as $k=>$v){
				if(isset($v['is_active']) && $v['is_active'] == 'on'){
					//$index++;
					$existed_id[] = $v['id'];
					$html .= '<tr><td class="center">'.($index+1).'<input type="hidden" name="f[currency][list]['.($index).'][id]" value="'.$v['id'].'" />
    									<input type="hidden" name="f[currency][list]['.($index).'][id]" value="'.$v['id'].'" />
    									<input type="hidden" name="f[currency][list]['.($index).'][title]" value="'.$v['title'].'" />
    									<input type="hidden" name="f[currency][list]['.($index).'][code]" value="'.$v['code'].'" />
    									<input type="hidden" name="f[currency][list]['.($index).'][decimal_number]" value="'.$v['decimal_number'].'" />
										<input type="hidden" name="f[currency][list]['.($index).'][symbol]" value="'.$v['symbol'].'" />
    									</td>';
					$html .= '<td>'.$v['title'].'</td>';
					$html .= '<td class="center">'.$v['code'].'</td>';
					$html .= '<td class="center">'.$v['symbol'].'</td>';
					$html .= '<td class="center"><select class="form-control input-sm select2 " data-search="hidden" name="f[currency][list]['.$index.'][display]">
          <option value="1">Hiển thị sau số tiền (10,000đ)</option>
          <option value="-1">Hiển thị trước số tiền ($10,000)</option>
          </select></td>';
					$html .= '<td class="center"><select class="form-control input-sm select2 " data-search="hidden" name="f[currency][list]['.$index.'][display_type]">
          <option value="1">Hiển thị mã quốc tế ('.$v['code'].')</option>
          <option value="2">Hiển thị symbol ('.$v['symbol'].')</option>
          </select></td>';
					$html .= '<td class="center"><input onchange="setDefaultCurrency(this)" '.($index++ == 0 ? 'checked' : '').' type="radio" name="f[currency][default]" value="'.$v['id'].'"/></td>';
					$html .= '<td class="center"><i class="glyphicon glyphicon-trash pointer" onclick="removeTrItem(this)"></i></td>';
					$html .= '</tr>';
				}
			}
		}
		echo json_encode(array('event'=>post('action'),'html'=>$html,'index'=>$index,'target_class'=>'ajax-html-result-before-list-vehicles','existed_id'=>implode(',',  $existed_id)));
		exit;
		break;
		
	case 'load_all_currency2':	
		$modalName = 'mymodal';
		$modalID = '#' . $modalName;
		$index = post('index',0);
		
		$html = '<table class="table table-hover table-bordered vmiddle table-striped"> <thead>';
		$html .= '<tr> <th rowspan="2">#</th> <th rowspan="2">Loại tiền tệ</th>
		<th rowspan="2" style="min-width:150px">Mã quốc tế</th>
		<th rowspan="2" style="min-width:150px">Ký hiệu</th>
		<th class="center mw100p">Chọn</th>
		 </tr>
		</thead> <tbody>';
		//$m = new \app\modules\admin\models\Currency();
		
		$l = \app\modules\admin\models\Currency::getList(array(
				'limit'=>1000,
				//'maker_id'=>$id,
				//'type_id'=>$_POST['type_id'],
				'not_in'=>post('existed',[])
		));
		if(!empty($l['listItem'])){
			foreach ($l['listItem'] as $k=>$v){
				$index += $k;
				$html .= '<tr><td>'.($k+1).'</td>';
				$html .= '<td>'.$v['title'].'</td>';
				$html .= '<td>'.$v['code'].'</td>';
				$html .= '<td>'.$v['symbol'].'</td>';
				$html .= '<td class="center"><input type="checkbox" name="f['.$index.'][is_active]" />
    								<input type="hidden" name="f['.$index.'][id]" value="'.$v['id'].'"/>
    								<input type="hidden" name="f['.$index.'][title]" value="'.$v['title'].'"/>
    								<input type="hidden" name="f['.$index.'][code]" value="'.$v['code'].'"/>
    								<input type="hidden" name="f['.$index.'][decimal_number]" value="'.$v['decimal_number'].'"/>
    								<input type="hidden" name="f['.$index.'][symbol]" value="'.$v['symbol'].'"/>
    								</td>';
				$html .= '</tr>';
			}
		}
		$html .= '</tbody></table>';
		
		$body = $html;
		
		$modal = Yii::$app->zii->renderModal([
		'action' => 'set_quantity_currency2',
		'name'=>$modalName,
		'body'=>'<div class="mgb5 pr member-avatar mgt10">
'.$body.'
</div>',
'class'=>'w60',
'title' => 'Danh sách các loại tiền tệ',
'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-save"></i> Lưu lại</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Đóng</button></div>'
		]);
		
		$complete_function = '';
		
		$complete_function .= 'izi.openModal($d.modal);';
		echo json_encode([
				'callback'=>true,
				'complete'=>true,
				'complete_function' => $complete_function,
				'modal' => $modal,
		]);exit;
		break;
	case 'load_all_currency':
		
		$index = post('index') >0 ? post('index') : 0;
		$html = '<table class="table table-hover table-bordered vmiddle table-striped"> <thead>';
		$html .= '<tr> <th rowspan="2">#</th> <th rowspan="2">Loại tiền tệ</th>
		<th rowspan="2" style="min-width:150px">Mã quốc tế</th>
		<th rowspan="2" style="min-width:150px">Ký hiệu</th>		
		<th class="center mw100p">Chọn</th>
		 </tr>
		</thead> <tbody>';
		$m = new app\modules\admin\models\Currency();
		$l = $m->getList(array(
				'limit'=>1000,
				//'maker_id'=>$id,
				//'type_id'=>$_POST['type_id'],
				'not_in'=>post('existed',[])
		));
		if(!empty($l['listItem'])){
			foreach ($l['listItem'] as $k=>$v){
				$index += $k;
				$html .= '<tr><td>'.($k+1).'</td>';
				$html .= '<td>'.$v['title'].'</td>';
				$html .= '<td>'.$v['code'].'</td>';
				$html .= '<td>'.$v['symbol'].'</td>';
				$html .= '<td class="center"><input type="checkbox" name="f['.$index.'][is_active]" />
    								<input type="hidden" name="f['.$index.'][id]" value="'.$v['id'].'"/>
    								<input type="hidden" name="f['.$index.'][title]" value="'.$v['title'].'"/>
    								<input type="hidden" name="f['.$index.'][code]" value="'.$v['code'].'"/>
    								<input type="hidden" name="f['.$index.'][decimal_number]" value="'.$v['decimal_number'].'"/>
    								<input type="hidden" name="f['.$index.'][symbol]" value="'.$v['symbol'].'"/>
    								</td>';
				$html .= '</tr>';
			}
		}
		$html .= '</tbody></table>';
		
		echo $html;
		exit;
		break;
	case 'set_default_ftp_server':
		$id = $_POST['id']; $val = $_POST['val'];
		Yii::$app->db->createCommand()->update('{{%server_config}}',array('is_active'=>0),array('sid'=>__SID__))->execute();
		Yii::$app->db->createCommand()->update('server_config',array('is_active'=>$val),array('id'=>$id,'sid'=>__SID__))->execute();
		//echo cjson($_POST);
		exit;
		break;
	case 'quick_update_seo':
		$id = post('id'); $table = $_POST['table'];$biz = post('biz');
		
		Siteconfigs::updateBizrule($table == 1 ? Content::tableName() : Menu::tableName(),['id'=>$id,'sid'=>__SID__],$biz) ;
		echo json_encode(['event'=>'quick_update_seo','target'=>ADMIN_ADDRESS,'delay'=>0]);
		exit;
		break;
	case 'parseSeoUrl':
		$url = $_POST['val'];
		$u = (parse_url($url));
		$url = '';
		$path = explode('/', $u['path']);
		if(!empty($path)){
			$path = array_reverse($path);
			foreach ($path as $p){
				if($p != ""){
					$url = $p; break;
				}
			}
			$m = new app\modules\admin\models\Slugs();
			$item= $m->getContentItem($url);
			echo json_encode(array('state'=>!empty($item),'item'=>$item));
		}
		exit();
		break;
	case 'changeFilterCode':
		$val = $_POST['val'];
		$v = (new Query())->select('code')->from(['{{%filters}}'])->where(['id'=>$val])->one();
		if(!empty($v)){
			echo json_encode(array('code'=>$v['code'],'state'=>true, ));
		}else{
			echo json_encode(array('code'=>'normal','state'=>false ));
		}
		exit;
		break;
	case 'addNewTab':
		$tab = $_POST['tab'];
		$c_type = $_POST['c_type'];
		$role = $_POST['role'];
		$id = $_POST['id'];
		
		
		$html = '<div role="tabpanel" class="tab-pane" id="'.$tab.'"><div class="p-content"><div class="row">
		<div class="col-sm-12"><div class="form-group"><label class="col-sm-12 control-label">Tiêu đề</label><div class="col-sm-12">
		<input data-id="0" type="text" name="tab['.$role.'][title]" class="form-control" id="inputTitleTab'.$role.'" placeholder="Title" value="Tab '.($role + 1).'" />
				
		<input type="hidden" name="tab['.$role.'][id]" class="form-control" value="0" />   </div> </div>';
		if($c_type === true || $c_type == 'true'){
			$lc = app\modules\admin\models\Content::getTabCategorys();
			$html .= '<div class="form-group '.(count($lc) < 2 ? 'hide' : '').'"><label class="col-sm-12 control-label">Kiểu form</label><div class="col-sm-12">';
			
			$html .= '<select data-id="'.$id.'" data-role="'.($role).'" onchange="changeFormLessionType(this);" name="tab['.($role).'][type]" class="form-control input-sm">';
			if(!empty($lc)){
				foreach($lc as $kx=>$vx){
					$html .= '<option '.('text' == $vx['type'] ? 'selected' : '').' value="'.$vx['type'].'">'.$vx['name'].'</option>';
				}
			}
			$html .= '</select>';
			$html .= '</div></div>';
		}
		$html .= '</div><div class="col-sm-12"><div class="form-group"><div class="col-sm-12 ajax_result_form_change'.$role.'"><textarea data-id="0" name="tab_biz['.$role.'][text]" class="form-control" id="xckc_'.$tab.'"  ></textarea>  </div> </div></div></div></div></div>';
		echo $html;
		exit;
		break;
	case 'selectDeparturePlace':
		$tour_type = $_POST['val'];
		$label = $tour_type == 1 ? getTextTranslate(21) : getTextTranslate(22);
		$html = $html1 = '';
		$m = new app\modules\admin\models\Content();
		$l = $m->getDeparturePlace(array(
				'is_destination'=>1,'type'=>$tour_type
		));
		
		if(!empty($l)){
			$html = '<optgroup label="'.$label.'">';
			foreach($l as $k1=>$v1){
				$html .= '<option  value="'.$v1['id'].'">+&nbsp;'. $v1['name'].'</option> ';
			}
			$html .= '</optgroup>';
		}
		if($tour_type == 2){
			$l = $m->getDeparturePlace(array(
					'is_start1'=>1,//'type'=>$tour_type
			));
		}else {
			$l = $m->getDeparturePlace(array(
					'is_start'=>1,//'type'=>$tour_type
			));
		}
		
		if(!empty($l)){
			//$html1 = '<optgroup label="'.$label.'">';
			foreach($l as $k1=>$v1){
				$html1 .= '<option  value="'.$v1['id'].'">+&nbsp;'. $v1['name'].'</option> ';
			}
			//$html1 .= '</optgroup>';
		}
		
		
		
		echo json_encode(array('s'=>$html1,'d'=>$html));
		//echo $html;
		exit;
		break;
	case 'checkCustomercode':
		$id = post('id');  $field = post('field');$val = post('val');
		$m = new app\modules\admin\models\Customers();
		$v = array(
				'name'=>'',
				'phone'=>'',
				'address'=>''
		);
		$ckc = $state = true;
		if($field == 'email'){
			$ckc = validateEmail($val);
			//view($ckc);
		}
		if($ckc){
			
			$v = (new Query())->from(['a'=>$m->tableName()])->where(["a.$field"=>$val,'a.sid'=>__SID__])->andWhere(['not in','a.id',$id])->one();
			if(!empty($v)){
				$state = true;
			}else{
				$state = false;
			}
		}
		echo json_encode( array('state'=>$state, 'data'=> $v)); exit;
		break;
		
	case 'checkExistedAuthItem':
		$id = post('id');  $field = post('field');$val = post('val');
		
		$ckc = $state = true;
		
		$v = (new Query())->from(['a'=>'user_groups'])->where(["a.name"=>$val,'sid'=>__SID__])->andWhere(['not in','a.id',$id])->one();
		if(!empty($v)){
			$state = true;
		}else{
			$state = false;
		}
		
		echo json_encode( array('state'=>$state, 'data'=> $v)); exit;
		break;
	case 'quick_update_default_language':
		$f = post('f', DEFAULT_LANG);
		$language = app\modules\admin\models\AdLanguage::getList();
		if(!empty($language)){
			foreach ($language as $k=>$x){
				if($x['code'] == $f){
					$language[$k]['default'] = 1;
				}else{
					$language[$k]['default'] = 0;
				}
			}
		}
		
		//unset(Yii::$app->session['config']['language']);
		Siteconfigs::updateData('LANGUAGE', $language);
		$callback_function = '$this.find("button").attr("disabled","");';
		Yii::$app->session->remove('config');
		echo json_encode([
				'p'=>$_POST,
				'callback'=>true,
				'callback_function'=>$callback_function
		]); exit;
		
		break;
	case 'checkOldPassword':
		$userPassword = post('val');
		if($userPassword != ""){
			$t = Yii::$app->user->validatePassword($userPassword);
		}else{
			$t = false;
		}
		
		echo json_encode(array('state'=>$t,'delay'=>0));
		exit;
		break;
		
	case 'quick_update_user':
		$f = isset($_POST['f']) ? $_POST['f'] : array();
		$biz = isset($_POST['biz']) ? $_POST['biz'] : array();
		//$m = $this->loadModel('users');
		if(!empty($biz)){
			Yii::$app->db->createCommand()->update('{{%users}}',['bizrule'=>json_encode($biz)],['id'=>Yii::$app->user->id])->execute();
			if(isset($biz['avatar'])){
				$_SESSION['config']['adLogin']['avatar'] = $biz['avatar'];
			}
		}
		if(!empty($f))	Yii::$app->db->createCommand()->update('{{%users}}',$f,['id'=>Yii::$app->user->id])->execute();
		echo json_encode(array('event'=>'edit_user_success','delay'=>0));exit;
		break;
	case 'ajax_uploads':
		
		if (isset($_FILES['myfile'])) {
			
			$callback = false; $callback_function = '';
			
			$fileName = $_FILES['myfile']['name'];
			$fileType = $_FILES['myfile']['type'];
			$fileError = $_FILES['myfile']['error'];
			$fileStatus = array(
					'status' => 0,
					'message' => ''
			);
			
			$fType = explode('.',$fileName);$fType = strtolower($fType[count($fType)-1]);
			$file_extensions = file_extension_upload($fType);
			if ($fileError== 1) { //Lỗi vượt dung lượng
				$fileStatus['message'] = 'Dung lượng quá giới hạn cho phép';
			} elseif (!in_array(strtolower($fType), $file_extensions)) { //Kiểm tra định dạng file
				$fileStatus['message'] = 'Không cho phép định dạng '.$fileType;
			} else { //Không có lỗi nào
				//move_uploaded_file($_FILES['myfile']['tmp_name'], 'uploads/'.$fileName);
				$fx = explode('/', $fileType);
				
				$upload_option = post('upload_option',[]);
				if(!isset($upload_option['rename'])){
					$upload_option['rename'] = false;
				}
				
				if(post('upload-group') == '-xls-import-class'){
					$upload_option['folder_save'] = '/tmp';
					$upload_option['include_site_name'] = false;
					$upload_option['replace'] = true;
					
				}
				
				
				
				
				if(post('upload-group') == '-xls-import-class'){
					$callback = true; $callback_function = '';
					$file = $_FILES['myfile']['tmp_name'];
					//
					$data = \app\modules\admin\models\ClassManage::getListMemberClassFromExcel($file);
					//
					$html = '<table class="table table-bordered table-hover table-responsive table-striped vmiddle">';
					$html .= '<thead><tr>
					 		<th>#</th>
					 		<th class="center" colspan="2">Họ tên</th>
					 		<th class="center">Số điện thoại</th>
					 		<th class="center">Email</th></tr>
					 		</thead><tbody>';
					if(!empty($data)){
						foreach ($data as $k=>$v){
							$html .= '<tr><th scope="row">'.($k+1).'</th>';
							$html .= '<td>'.uh($v['lname']).'</td>';
							$html .= '<td>'.uh($v['fname']).'</td>';
							$html .= '<td class="center">'.uh($v['phone']).'</td>';
							$html .= '<td class="center">'.uh($v['email']);
							foreach ($v as $key=>$value){
								$html .= '<input type="hidden" name="f['.$k.']['.$key.']" value="'.$value.'" />';
							}
							$html .= '</td>';
							
							$html .= '</tr>';
						}
					}
					
					$html .= '</body></table>';
					$fileStatus['html'] = $html;
					
					
					$callback_function .= 'jQuery("#progress-group-xls-import-class").html(server.html);';
					
				}else{
					$file = Yii::file()->upload_files($_FILES['myfile'],$upload_option);
				}
				
				$fileStatus['status'] = 1;
				$fileStatus['message'] = "Bạn đã upload $fileName thành công";
				$fileStatus['image'] = $file;
				$fileStatus['callback'] = $callback;
				$fileStatus['callback_function'] = $callback_function;
			}
			echo json_encode($fileStatus);
			exit();
		}
		break;
	case 'quick_edit_field': // Chưa sửa hết
		$f = $_POST['f'];
		if(isset($f['table']) && $f['id']){
			$table = $f['table'];
			$id = $f['id'];
			if(isset($f['_target'])){
				$target = $f['_target'];
				unset($f['_target']);
			}else{
				$target = '';
			}
			$con = array('id'=>$id);
			
			if (isset($f['sid'])){
				array_merge($con,array('sid'=>$f['sid']));
				unset($f['sid']);
			}
			unset($f['id']); unset($f['table']);
			
			Yii::$app->db->createCommand()->update($table,$f,$con)->execute();
			
			echo json_encode(array('event'=>'quick_edit_field','target'=>$target,'title'=>$f['title']));
		}
		exit;
		break;
	case 'setdefaultTemplete':
		$id = post('id');
		$lang = post('lang');
		Yii::$app->db->createCommand()->update('{{%temp_to_shop}}',['state'=>2],['sid'=>__SID__,'lang'=>$lang])->execute();
		Yii::$app->db->createCommand()->update('{{%temp_to_shop}}',['state'=>1],['temp_id'=>$id, 'sid'=>__SID__,'lang'=>$lang])->execute();
		
		break;
	case 'checkDomain':
		$val = post('val'); $id = post('id');
		$msg = 'Có thể sử dụng domain này'; $state = true;
		if(!validate_domain($val)){
			$msg = 'Domain không hợp lệ';
			$state = false;
		}else{
			if((new Query())->from('{{%domain_pointer}}')->where(['domain'=>$val])->andWhere(['not in','id',$id])->count(1) > 0){
				$msg = 'Domain <b>'.$val.'</b> đã được sử dụng';$state = false;
			}
		}
		echo json_encode(array('domain'=>$val,'state'=>$state,'msg'=>$msg));
		exit;
		break;
	case 'set_main_domain':
		$val = post('val');
		//view($_POST);
		Yii::$app->db->createCommand()->update('{{%domain_pointer}}',['is_default'=>2],['sid'=>__SID__])->execute();
		Yii::$app->db->createCommand()->update('{{%domain_pointer}}',['is_default'=>1],['id'=>$val, 'sid'=>__SID__])->execute();
		echo json_encode(array('state'=>true));
		exit;
		break;
	case 'templete_load_child':
		$id = post('id');
		$m = new app\modules\admin\models\Templete;
		$l = $m->getList(array('limit'=>10000,'parent_id'=>$id));
		$html = '';
		if(!empty($l['listItem'])){
			foreach($l['listItem'] as $k1=>$v1){
				$html .= '<option value="'.$v1['id'].'">['.$v1['name'].'] '.uh($v1['title']).'</option>';
			}
		}
		echo $html;
		exit;
		break;
	case 'checkUserExisteds':
		$val = $_POST['value']; $field = $_POST['field']; $id = $_POST['id'];
		$ckc = true;
		switch ($field){
			case 'email':
				$ckc = validateEmail($val);
				break;
		}
		$sql = "select count(1) from users as a where a.$field='$val' and a.id not in($id) and a.sid=".__SID__;
		echo json_encode(array('state'=> $ckc ? Yii::$app->db->createCommand($sql)->queryScalar() : 1 ));
		exit;
		break;
		
	case 'forgot':
		$f = post('f');
		$user_type = isset($f['user_type']) ? $f['user_type'] : 'users';
		$m = new \app\modules\admin\models\Users();
		$u = $user_type == 'members' ?  $m->getItem($f['email']) : $m->getItem($f['email']);
		
		if(!empty($u)){
			$password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
			if($user_type == 'members'){
				$link = SITE_ADDRESS . '/members/forgot?action=reset&password_reset_token='.$password_reset_token;
			}else $link = yii\helpers\Url::to([DS.Yii::$app->controller->module->id.DS.'forgot','action'=>'reset','password_reset_token'=>$password_reset_token],true);
			$t = true;
			$search = array(
					'{LOGO}',
					'{DOMAIN}',
					'{USER}',
					'{LINK}',
					
			);
			$replace = array(
					'',
					__DOMAIN__,
					$f['email'],
					$link,
					
			);
			
			$text = Yii::$app->getTextRespon(array('code'=>'RP_FORGOT', 'show'=>false));
			$fx = Yii::$app->getConfigs('CONTACTS');
			$form = str_replace($search, $replace, uh($text['value']));
			Yii::$app->sendEmail(array(
					'subject'=>str_replace($search, $replace, $text['title'])  ,
					'body'=>$form,
					//'from'=>$fx['email'],
					//'fromName'=>$fx['short_name'],
					'replyTo'=>$fx['email'],
					'replyToName'=>$fx['short_name'],
					'to'=>$f['email'],'toName'=>$u['lname'] .' ' . $u['fname']
			));
			Yii::$app->db->createCommand()->update($user_type,['password_reset_token'=>$password_reset_token],['id'=>$u['id'],'email'=>$f['email'],'sid'=>__SID__])->execute();
			//view($a);
		}else $t = false;
		echo json_encode(array('event'=>'forgot','email'=>$f['email'], 'state'=>$t,'delay'=>0));exit;
		break;
	case 'get_decimal_number':
		$id = post('id',0);
		$m = new \app\modules\admin\models\Currency();
		$item = $m->getItem($id);
		$d = 2;
		if(!empty($item)){
			$d = $item['decimal_number'];
		}
		echo $d;
		exit;
		break;
}

if(Yii::$app->request->method == 'POST'){	
	$responData['callback'] = $callback;
	$responData['complete'] = $complete;
	$responData['callback_function'] = $callback_function;
	$responData['complete_function'] = $complete_function;
	$responData['modal'] = $modal;
	$responData['html'] = $html;				
	echo json_encode($responData); exit;
}else{
	echo ''; exit;
}