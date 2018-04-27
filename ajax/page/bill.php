<?php
switch (post('action')){
	
	case 'Bill_restore_deleted_bill':
		$id = post('id');
		Yii::$app->db->createCommand()->update(\app\modules\admin\models\Bills::tableName(), ['status'=>2],[
				'id'=>$id,
				'sid'=>__SID__
		])->execute();
		$item = \app\modules\admin\models\Bills::getItem($id);
		\app\modules\admin\models\Bills::updateOrderStatus(1,$item['order_id']);
		$callback_function .= 'window.location=window.location;';
		break;
	case 'quick-submit-Bill_distroy_item':
		$id = post('id');
		Yii::$app->db->createCommand()->update(\app\modules\admin\models\Bills::tableName(), ['status'=>15],[
				'id'=>$id,
				'sid'=>__SID__
		])->execute();
		$item = \app\modules\admin\models\Bills::getItem($id);
		\app\modules\admin\models\Bills::updateOrderStatus(6,$item['order_id']);
		$callback_function .= 'window.location=window.location;';
		break;	
	case 'Bill_distroy_item':	
		$id = post('id');
		$body .= '<fieldset class="f12px mgb15"><legend>Xác nhận hủy đơn hàng '.$id.'</legend>';
		
		$body .= '<p>Đơn hàng sẽ được hủy sau khi bạn xác nhận tại form này.</p>';
				
		$body .= '</fieldset>';
		
		
						
		
		$modal = Yii::$app->zii->renderModal([
				//'action' => 'quick-sent-request-change-for-operator',
				'name'=>$modalName,
				'body'=>$body,
				'class'=>'w50',
				'title' => 'Hủy đơn hàng',
				'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-check-square-o"></i> Xác nhận</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Hủy</button></div>'
		]);
		
		$complete_function = '';
		
		$complete_function .= 'izi.openModal($d.modal); ';
		break;
		
	case 'bill-copy-bill':
		$id = post('id');
		//$callback_function .= 'changeUrl(\'Edit\',\''.$href.'\');';
		$callback_function .= 'showModal(\'Thông báo\',\'Chức năng đang hoàn thiện\');';
		break;
	case 'bill-submit-edit-form':
		$f = post('f',[]);
		$biz = post('biz',[]);
		$controller_text = post('controller_text');
		$controller_action = post('controller_action');
		$id = post('id');
		$btnSubmit = post('btnSubmit',0);
		switch ($controller_action){
			case 'add':
				$id = \app\modules\admin\models\Bills::genCode();
				$data = [
						'id'=>$id,
						'sid'=>__SID__,
						'created_by'=>Yii::$app->user->id,
						'created_at'=>time(),
						'updated_at'=>time(),
						'branch_id'		=>	Yii::$app->user->branch_id,
				];
				Yii::$app->db->createCommand()->insert(\app\modules\admin\models\Bills::tableName(), $data)->execute();
				$href = cu([$controller_text . '/edit','id'=>$id]);
				$callback_function .= 'changeUrl(\'Edit\',\''.$href.'\');';
				
				break;
			case 'edit':
				
				break;
		}
		$f['created_at'] = (isset($f['created_at']) && $f['created_at'] != "" ? ctime(['string'=>$f['created_at'],'return_type'=>1]) : time());
		//
		if($btnSubmit>0){
			//$f['status'] = $btnSubmit;
		}
		$f['bizrule'] = json_encode($biz,JSON_UNESCAPED_UNICODE);
		Yii::$app->db->createCommand()->update(\app\modules\admin\models\Bills::tableName(), $f,[
				'id'=>$id,
				'sid'=>__SID__
		])->execute();
		//
		$item = \app\modules\admin\models\Bills::getItem($id);
		switch ($btnSubmit){
			//
			case 2: // Lưu tạm
			case 3: // Lưu và tiếp tục
			case 4: // Lưu và in	
				if($item['status'] != $btnSubmit && $item['status']<5){
					Yii::$app->db->createCommand()->update(\app\modules\admin\models\Bills::tableName(), ['status'=>$btnSubmit],[
					'id'=>$id,
					'sid'=>__SID__ 
					])->execute();
					$callback_function .= 'window.location = window.location;';
				}
				break;
			case 5: // Xác nhận
				$s = true;
				// 1. Check thông tin khách hàng
				if(!(isset($biz['bill']['customer']['id']) && $biz['bill']['customer']['id'] > 0)){
					$callback_function .= 'showModal(\'Thông báo\',\'Vui lòng chọn khách hàng.\');';
					$s = false;
				}
				// 2. Kiểm tra tình trạng thanh toán
				if($f['owed_total']>0){
					$callback_function .= 'showModal(\'Thông báo\',\'Vui lòng thanh toán hết số tiền ghi trên đơn hàng.\');';
					$s = false;
				}
				if($s){
					 					 
					Yii::$app->db->createCommand()->update(\app\modules\admin\models\Bills::tableName(), ['status'=>$btnSubmit],[
							'id'=>$id,
							'sid'=>__SID__
					])->execute();
					$callback_function .= 'showModal(\'Thông báo\',\'Thay đổi trạng thái đơn hàng thành công.\');';
					$callback_function .= 'reload(3000);';
					//
					\app\modules\admin\models\Bills::updateOrderStatus(1,$item['order_id']);
					
					//
				}
				break;
				
			case 8: // giao hàng
				// 1. Xác nhận
				$s = true;
				// 1. Check thông tin khách hàng
				if(!(isset($biz['bill']['customer']['id']) && $biz['bill']['customer']['id'] > 0)){
					$callback_function .= 'showModal(\'Thông báo\',\'Vui lòng chọn khách hàng.\');';
					$s = false;
				}
				// 2. Kiểm tra tình trạng thanh toán
				if($f['owed_total']>0){
					$callback_function .= 'showModal(\'Thông báo\',\'Vui lòng thanh toán hết số tiền ghi trên đơn hàng.\');';
					$s = false;
				}
				
				// 2. Check thông tin giao hang
				
				if($s){
					if(!(isset($biz['bill']['ship']['name']) && $biz['bill']['ship']['name'] != "")){
						$callback_function .= 'showModal(\'Thông báo\',\'Vui lòng chọn đơn vị giao hàng.\');';
						$s = false;
					}
					if($s){
						Yii::$app->db->createCommand()->update(\app\modules\admin\models\Bills::tableName(), ['status'=>$btnSubmit],[
								'id'=>$id,
								'sid'=>__SID__
						])->execute();
						//$callback_function .= 'showModal(\'Thông báo\',\'Vui lòng chọn khách hàng.\');';
						$callback_function .= 'reload(0);';
					}
					
					\app\modules\admin\models\Bills::updateOrderStatus(2,$item['order_id']);
				}
				
				
				break;
				
			case 6: // Hoàn tất đơn hàng
			case 7: // Hoàn tất đơn hàng & in
				// 2. lập phiếu thu
				$acc_bill_id = \app\modules\admin\models\Accounting::createBillFromPos($id);				
				// 3. Lập phiếu xuât kho
				if($acc_bill_id != ""){
					$acc_bill_export_id = \app\modules\admin\models\Accounting::createBillExportFromPos($id);
				}
				
				Yii::$app->db->createCommand()->update(\app\modules\admin\models\Bills::tableName(), ['status'=>$btnSubmit],[
						'id'=>$id,
						'sid'=>__SID__
				])->execute();

				if($btnSubmit == 7){
					$href = cu([$controller_text . '/print','id'=>$id]);
					$callback_function .= 'gotoUrl(\''.$href.'\');';
				}else{
					$href = cu([DS.$controller_text]);
					$callback_function .= 'gotoUrl(\''.$href.'\');';
				}
				\app\modules\admin\models\Bills::updateOrderStatus(3,$item['order_id']);
				break;
			case 9: // in đơn hàng
				
				break; 
			case 15: // Hủy đơn hàng
				
				Yii::$app->db->createCommand()->update(\app\modules\admin\models\Bills::tableName(), ['status'=>$btnSubmit],[
				'id'=>$id,
				'sid'=>__SID__
				])->execute();
				$callback_function .= 'showModal(\'Thông báo\',\'Hủy đơn hàng thành công.\');goBack();';
				\app\modules\admin\models\Bills::updateOrderStatus(6,$item['order_id']);
				break;
			case 14: // khôi phục đơn hàng
				
				break;
		}
		
		//$callback_function .= 'console.log('.$btnSubmit.');';
		$responData['f'] = $biz;
	
		break;
	
	case 'quick-submit-order-confirm-action':
		$order_id = post('order_id',0);
		Yii::$app->db->createCommand()->update(\app\modules\admin\models\Orders::tableName(), ['is_confirmed'=>1],['id'=>$order_id])->execute();
		$order_code = post('order_code');
		$bill_id = \app\modules\admin\models\Bills::importBillFromOrder($order_code);
		
		
		
		$callback_function .= 'closeAllModal();var strWindowFeatures = "menubar=yes,location=yes,resizable=yes,scrollbars=yes,status=yes";';
		$callback_function .= 'var win = window.open("'.\app\modules\admin\models\AdminMenu::get_menu_link('bills').'/edit?id='.$bill_id.'", \'_blank\');';
		$callback_function .= 'if (win) {
    //Browser has allowed it to be opened
    win.focus();
} else {
    //Browser has blocked it
    alert(\'Vui lòng bỏ chặn popup trình duyệt.\');
}';
		//$callback_function .= 'alert(\''.\app\modules\admin\models\AdminMenu::get_menu_link('bills').'\');';
		break;
	case 'order-confirm-action':
		$order_id = post('order_id',0);
		 
		
		$body .= '<div class="form-group">

<div class="col-sm-12">
Xác nhận đơn hàng và lập hóa đơn bán hàng
</div></div>';
		
		
		 
		
		$modal = Yii::$app->zii->renderModal([
				//'action' => 'quick-add-customer',
				'name'=>$modalName,
				'body'=>'<div class="col-sm-12"><div class="mgb5 pr member-avatar mgt10">
'.$body.'
</div></div>',
				'class'=>'modal-sm',
				'title' => 'Xác nhận đơn hàng',
				'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-check-square-o"></i> Đồng ý</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Hủy</button></div>'
		]);
		
		$complete_function = '';
		
		$complete_function .= 'izi.openModal($d.modal);';
		
		break;
	
	case 'bill_huy_don_hang':
		$bill_id = post('bill_id',0);
		Yii::$app->destroyBill($bill_id);
		$callback_function .= 'reloadAutoPlayFunction(true);';
		break; 
	case 'bill_luu_hoa_don':
		$bill_id = post('bill_id',0);
		$bill = Yii::$app->getBill($bill_id);
		$responData['bill'] = $bill;
		$reload = true; $state = true;
		if(!(isset($bill['customer']['id']) && $bill['customer']['id']>0)){
			$reload = false;
			$callback_function .= 'jQuery(".list-bills-'.$bill_id.'").find(".autocomplete_customer").addClass("error").focus();';
			$state = false; 
		}
		if($bill['guest_pay'] < $bill['grand_total']){
			$callback_function .= 'showModal(\'Thông báo\',\'Khách hàng này không được phép bán nợ.<br/>Vui lòng thanh toán đủ <b>'.number_format($bill['grand_total']).'</b>.\');';
			$state = false;
		}
		
		if($state){
			 
			/*
			 * Quy trình lập bill
			 * 1. Lập bill lưu lại thông tin mua hàng
			 * 2. Lập phiếu thu
			 * 3. Lập phiếu xuất kho
			 */
			// 1. Lập bill 
			$pos_bill_id = $acc_bill_id = '' ;
			$pos_bill_id = \app\modules\admin\models\Bills::createBill($bill,['status'=>1]);
			//$callback_function .= 'console.log(\''.$pos_bill_id.'\');'; 
			// 2. lập phiếu thu
			$acc_bill_id = \app\modules\admin\models\Accounting::createBillFromPos($pos_bill_id);
			//exit;
			// 3. Lập phiếu xuât kho
			if($acc_bill_id != ""){
				$acc_bill_export_id = \app\modules\admin\models\Accounting::createBillExportFromPos($pos_bill_id);
			}
			//exit;
			// Hủy bill lưu trong cookie
			Yii::$app->destroyBill($bill_id);
		}
		//exit;
		if($reload){
			$callback_function .= 'show_left_small_loading(\'show\');show_left_small_loading(\'hide\',3000);reloadAutoPlayFunction(true);';
		}
		//$callback_function .= 'console.log($d.bill);';
		
		break;
	case 'bill_luu_in_hoa_don':
		$bill_id = post('bill_id',0);
		$callback_function .= 'reloadAutoPlayFunction(true); '; 
		break;
	
	case 'bill_thu_tien':
		$bill_id = post('bill_id',0); 
		$bills = Yii::$app->getBill($bill_id);
		$callback_function .= '
$this.attr("disabled","");
jQuery(".list-bills-'.$bill_id.'").find(\'.label_excess_cash\').html("");
jQuery(".list-bills-'.$bill_id.'").find(\'.input_bill_thu_tien\')
.val('.$bills['grand_total'].')
.focus().select();'; 
		break;
	case 'bill_update_customer':
		$bill_id = post('bill_id',0);
		$item_id = post('item_id',0);
		$quantity = post('value',0);
		$bills = Yii::$app->getBill();
		 
		if(isset($bills[$bill_id])){ // Đã có
			$bills[$bill_id]['customer'] = post('item',[]);
			Yii::$app->setBill($bills);
			Yii::$app->refreshBill($bills);
		}
		
		$callback_function .= 'reloadAutoPlayFunction(true);';
		break;
	case 'bill_remove_customer':
		$bill_id = post('bill_id',0);
		$item_id = post('item_id',0);
		$quantity = post('value',0);
		$bills = Yii::$app->getBill();
		 
		if(isset($bills[$bill_id])){ // Đã có
			$bills[$bill_id]['customer'] = [];
			Yii::$app->setBill($bills);
			Yii::$app->refreshBill($bills);
		}
		
		$callback_function .= 'reloadAutoPlayFunction(true);';
		break;	
		
	case 'bill-load-data':
		$bill_id =$i= post('bill_id',0);
		
		$html .= '<div class="pdt15 pdb15 pdl5 pdr5 pos-quick-create-body"> 

<table class="table table-striped table-bordered table-hover vmiddle list-bill-'.$i.'" >
<thead>
<tr>
<th width="45%" class="ng-binding"> Sản phẩm / Hàng hóa</th>
<th width="15%" class="center"><span class="ng-binding">SL</span></th>
<th width="20%" class="center"><span class="ng-binding">Đơn giá</span></th>
<th width="15%" class="center"><span class="ng-binding">Thành tiền</span></th>
<th class="remove "></th>
</tr>
</thead>
<tbody class="ng-scope amz-body-'.$i.'">';
		$bills = Yii::$app->getBill($i);
		if(!(isset($bills['currency']) && $bills['currency']>0)){
			$bills['currency'] = Yii::$app->currency;
		}
		if(isset($bills['listItem']) && !empty($bills['listItem'])){
			foreach ($bills['listItem'] as $k=>$item){
			
			$quantity = isset($item['quantity']) ? $item['quantity'] : 1;
			$discount = [
					'value' => isset($item['discount']['value']) ? $item['discount']['value'] : 0,
					'type' => isset($item['discount']['type']) ? $item['discount']['type'] : '%', // 1: % 2: TM
			];
			
			$sub_total = $quantity * $item['price'];
			$html .= '<tr class="options item-'.$i.'-'.$item['id'].'">
<td>
<span class="ng-binding ng-scope">
<span class="hidden-640 ng-binding">['.$item['code'].'] '.$item['title'].'</span>
</span>
		
</td>
<td class="center">
<input type="number" class="form-control center input-sm" required="required" aria-label="..."
onblur="call_ajax_function(this)"
data-action="bill_update_quantity"
data-bill_id="'.$i.'"
data-item_id="'.$item['id'].'"
placeholder="Nhập số lượng sản phẩm" value="'.$quantity.'">
</td>
                                                        <td class="number aright">
                                                            <div class="width-100 ng-binding">
 <button
data-toggle="tooltip" data-placement="top"
title="Thiết lập giảm giá" class="btn mgr3 btn-xs btn-info attr-row ng-scope" >
<i class="fa fa-gift "></i>
</button>
                                                               '.number_format($item['price']).'
                                                               		
'.($discount['value'] > 0 ? '<span class="red ng-binding ng-scope">(' . $discount['value'] . $discount['type'] . ')</span>': '').'
		
		
		
		
                                                            </div>
		
                                                        </td>
                                                        <td class="number ng-binding aright">
                                                             '.number_format($sub_total).'
                                                             </td>
                                                        <td class="pr center">
<button onclick="call_ajax_function(this)" data-action="bill_remove_action"
data-bill_id="'.$i.'"
data-item_id="'.$item['id'].'"
class="btn btn-xs btn-danger attr-row" data-toggle="tooltip" data-placement="left" title="Xóa">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>';
			
		}}
			$html .= '</tbody></table>
 
</div>
<div class="pos-quick-create-bottom">
<div class="mgl10 mgr10">
<form class="pdb5">

<table class="w100 mw100 vmiddle"> 
<colgroup>
<col>
<col>
<col class="w60px">
</colgroup>

<tbody> 

<tr>  
<td colspan="2" class="pr"><input type="text" 
data-bill_id="'.$i.'"
'.(isset($bills['customer']['id']) && $bills['customer']['id'] > 0 ? 'disabled=""' : '').'
data-action="Au_load_customer"
value="'.(isset($bills['customer']['name']) ? $bills['customer']['name'] : '').'"
class="form-control autocomplete_customer required" required="" aria-label="..." placeholder="Nhập tên/mã/sđt tìm kiếm khách hàng">
<input type="hidden" name="" class="customer_code" value="'.(isset($bills['customer']['code']) ? $bills['customer']['code'] : '').'"/>
<input type="hidden" name="" class="customer_id" value="'.(isset($bills['customer']['id']) ? $bills['customer']['id'] : '').'"/>
'.(isset($bills['customer']['id']) && $bills['customer']['id'] > 0 ? '<span 
onclick="call_ajax_function(this);"
data-action="bill_remove_customer"
data-bill_id="'.$i.'"
class="ps t10 r10 pointer"><i class="fa red fa-remove"></i></span>' : '').'
</td> 
<td class="aright"><button type="button" class="btn btn-default" title="Thêm mới khách hàng"><i class="fa fa-user-plus"></i></button></td> 
 </tr> 
 
<tr class="">  
<td colspan="1" class="bold"><label class="mgt5 pdt5 pdb5 f16px">Tiền hàng</label></td> 
<td colspan="1" class="bold aright ">
<label class="mgt5 f16px">'.(getCurrencyText(isset($bills['sub_total']) ? $bills['sub_total'] : 0,$bills['currency'],['show_symbol'=>true])).'</label></td> 
<td class="aright"></td> 
</tr>

<tr class="">  
<td colspan="1" class=""><label class="mgt5 pdt5 pdb5">Giảm giá</label></td> 
<td colspan="1" class="aright"><label class="mgt5">'.(isset($bills['total_discount']) ? getCurrencyText($bills['total_discount'],$bills['currency']) : '').'</label></td> 
<td class="aright"><button type="button" class="btn btn-default" title="Thiết lập giảm giá"><i class="fa fa-gift"></i></button></td>
</tr>

 
<tr>  
<td colspan="1" class="bold"><label class="mgt5 pdt5 pdb5 f16px text-danger">Thanh toán</label></td> 
<td colspan="1" class="bold aright "><label class="mgt5 f16px text-danger">'.(isset($bills['grand_total']) ? getCurrencyText($bills['grand_total'],$bills['currency']): '').'</label></td> 
<td class="aright"></td> 
</tr>

<tr class="f16px">  
<td colspan="1" class="bold"><label class="mgt5 pdt5 pdb5">Khách đưa</label></td> 
<td colspan="1" class="bold aright ">
<input data-bill_id="'.$i.'" 
data-action="bill_update_item"
data-field="guest_pay"
onblur="call_ajax_function(this);"
type="text" class="form-control aright number-format input_bill_thu_tien" aria-label="..." 
placeholder="Nhập số tiền khách đưa" 
value="'.(isset($bills['guest_pay']) ? $bills['guest_pay'] : '').'"
data-old="'.(isset($bills['guest_pay']) ? $bills['guest_pay'] : '').'"
>
</td> 
<td class="aright"><button type="button" class="btn btn-default" title="Thiết lập thanh toán"><i class="fa fa-cc-visa"></i></button></td>
</tr> 


<tr class="f16px">  
<td colspan="1" class="bold"><label class="mgt5 pdt5 pdb5">Tiền thừa</label></td> 
<td colspan="1" class="aright "><label class="mgt5 label_excess_cash">'.(isset($bills['excess_cash']) ? number_format($bills['excess_cash']) : '').'</label></td> 
<td class="aright"></td> 
</tr>

<tr class="f16px">  
<td colspan="2" class="aright">


<button 
onclick="if(confirm(\'Xác nhận\')){call_ajax_function(this)}"
data-action="bill_huy_don_hang"
data-bill_id="'.$i.'"
type="button" class="btn btn-default mgr5"
data-toggle="tooltip" data-placement="top" title="Hủy đơn hàng"
><i class="fa fa-remove"></i></button>
<button type="button" class="btn btn-default mgr5"
data-toggle="tooltip" data-placement="top" title="Thông tin hóa đơn"
><i class="fa fa-info-circle"></i></button>
<button type="button" class="btn btn-default mgr5"
data-toggle="tooltip" data-placement="top" title="Ghi chú hóa đơn"
><i class="fa fa-pencil-square"></i></button>

<button '.(!empty($bills) ? '' : 'disabled=""').'
onclick="call_ajax_function(this)"
data-action="bill_thu_tien"
data-bill_id="'.$i.'"
data-keycode="119"
type="button" class="btn btn-warning mgr5 btn-function">Thu tiền (F8)</button>

<!-- Indicates a successful or positive action -->
<button '.(!empty($bills) ? '' : 'disabled=""').'
onclick="call_ajax_function(this)"
data-action="bill_luu_hoa_don"
data-keycode="120"
data-bill_id="'.$i.'"
type="button" class="btn btn-success mgr5 btn-function">Lưu (F9)</button>

<!-- Contextual button for informational alert messages -->
<button '.(!empty($bills) ? '' : 'disabled=""').'
onclick="call_ajax_function(this)"
data-action="bill_luu_in_hoa_don"
data-keycode="121"
data-bill_id="'.$i.'"
type="button" class="btn btn-primary btn-function">Lưu & In (F10)</button>

 

</td> 
 <td class="aright"></td> 
</tr>
 
</tbody> </table>
  
   
    
</form>

</div> </div>   

</div>';
		
			
		$callback_function .= 'bill_loadAutocompleteCustomer();load_number_format();resizePosSpace();';	
		break;
	
	case 'bill_update_item':
		$bill_id = post('bill_id',0);
		$item_id = post('item_id',0);
		$quantity = post('value',0);
		$bills = Yii::$app->getBill();
		if(isset($bills[$bill_id]) && !empty($bills[$bill_id])){
			$field = post('field');
			$value = post('value',0);
			switch ($field){
				case 'guest_pay':
					$value = cprice($value);
					$ex = $value - $bills[$bill_id]['grand_total'];
					$bills[$bill_id][$field] = $value;
					
					Yii::$app->refreshBill($bills);
					
					$callback_function .= '$this.parent().parent().parent().find(".label_excess_cash").val(\''.number_format($ex).'\');';
					
					break;
			}
		}		 
		
		$callback_function .= 'reloadAutoPlayFunction(true);';
		
		
		break;
		
	case 'bill_update_quantity':
		//$item = post('item',[]);
		$bill_id = post('bill_id',0);
		$item_id = post('item_id',0);
		$quantity = post('value',0);
		$bills = Yii::$app->getBill();
		$b = Yii::$app->getBillItem($bill_id,$item_id);		
		if(!empty($b) && $quantity>0){ // Đã có
			$bills[$bill_id]['listItem'][$item_id]['quantity'] = $quantity;
			Yii::$app->setBill($bills);
			Yii::$app->refreshBill($bills);
		}		
		
		$callback_function .= 'reloadAutoPlayFunction(true);';
		
		
		break;
	case 'bill_remove_action':
		//$item = post('item',[]);
		$bill_id = post('bill_id',0);
		$item_id = post('item_id',0);
		$bills = Yii::$app->getBill();
		$b = Yii::$app->getBillItem($bill_id,$item_id);		
		if(!empty($b)){ // Đã có
			unset($bills[$bill_id]['listItem'][$item_id]);
			Yii::$app->setBill($bills);
			Yii::$app->refreshBill($bills);
		}		
		$callback_function .= 'jQuery(".item-'.$bill_id.'-'.$item_id.'").remove();reloadAutoPlayFunction(true);';
		
		break;
	
	case 'bill_add_action':
		$callback = true;
		$item = post('item',[]);
		$bill_id = post('bill_id',0);
		$reload = true;
		$callback_function .= 'jQuery(".list-bills-'.$bill_id.'").find(".error-bill-'.$bill_id.'").remove(); ';
		if(isset($item['loadbarcode']) && $item['loadbarcode'] == 1 && $item['barcode'] != ""){
			$it = \app\modules\admin\models\Content::getItem(0,[
					'barcode' 	=> 	$item['barcode'],
					'select'	=>	['a.id','a.code','a.barcode','a.title','a.price2','a.currency','a.bizrule']
			]);
			if(!empty($it)){
				$item['price'] = $it['price2'];
				$item['exchange_rate'] = 1;
				$item['time'] = __TIME__;
				$item['label'] = $item['value'] = $it['title'];				 
				$item['desc'] = uh($it['info']);
				$item['id'] = $it['id'];
				$item['code'] = $it['code'];
				$item['barcode'] = $it['barcode'];
				$item['title'] = $it['title'];
				$item['price1'] = $it['price1'];
				$item['price2'] = $it['price2'];
				$item['currency'] = $it['currency'];
				$item['url'] = $it['url'];
				$item['url_link'] = $it['url_link'];
				$item['icon'] = $it['icon'];
				$item['listImages'] = isset($it['listImages']) ? $it['listImages'] : [];
			}else{
				$callback_function .= 'jQuery(".list-bills-'.$bill_id.'").prepend(\'<p class="red pd15 text-danger error-bill-'.$bill_id.'">Không tìm thấy sản phẩm phù hợp với mã <b class="green">{'.$item['barcode'].'}</b></p>\');';
				$item = [];
				$reload = false;
			}
		}
		
		if(!empty($item)){
		
		
		
		
		$quantity = isset($item['quantity']) ? $item['quantity'] : 1;
		
		$item['quantity'] = $quantity;
		
		$discount = [
				'value' => isset($item['discount']['value']) ? $item['discount']['value'] : 0,
				'type' => isset($item['discount']['type']) ? $item['discount']['type'] : '%', // 1: % 2: TM
		];
		 
		$item['discount'] = $discount;
		
		$bills = Yii::$app->getBill();
		$b = Yii::$app->getBillItem($bill_id,$item['id']);
		//$callback_function .= 'console.log(\''.json_encode($b).'\');';
		$x1 = true;
		
		if(!empty($b)){ // Đã có
			$item['quantity'] = $b['quantity'];
			$item['quantity'] ++;
			$bills[$bill_id]['listItem'][$item['id']] = $item;
			Yii::$app->setBill($bills); 
			Yii::$app->refreshBill($bills);
			$x1 = false;
		}else{ // Chưa có
			$bills[$bill_id]['listItem'][$item['id']] = $item; 
			Yii::$app->setBill($bills);
			Yii::$app->refreshBill($bills);
		
		}

		 
		$quantity = isset($item['quantity']) ? $item['quantity'] : 1;
		$sub_total = $quantity * $item['price2'];
		$item['sub_total'] = $sub_total;
		//$responData['tr'] = '';
		}
		 
		$callback_function .= '
var $tbody = jQuery(".list-bill-'.$bill_id.'").find("tbody");
//$tbody.append($d.tr);
//console.log(data);

';
		if($reload){
			$callback_function .= 'reloadAutoPlayFunction(true);';
		}
		
		break;
}