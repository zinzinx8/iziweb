<?php
switch (Yii::$app->request->post('action')){
	
	case 'qedit-detail-service-detail-day':
		$item_id = post('item_id',0);
		$request_id = post('request_id',0);
		$customer_id = post('customer_id',0);
		$role = post('role',1);
		$request = \app\modules\admin\models\SaleSentRequest::getItem($request_id);
		$u = \app\modules\admin\models\Users::getItem($request['created_by'],['validateSid'=>0]);
		$cus = \app\modules\admin\models\Customers::getItem($customer_id);
		//$user_confirmed = \app\modules\admin\models\SaleSentRequest::getUserConfirmed($request_id);
		$body = '';
		
		
		$r2 = randString(10);
		$body .= '<fieldset class="f12px mgb15"><legend>Nội dung</legend>';
		
		$body .= '<p><textarea name="f[text]" class="form-control required" required="" rows=5 placeholder="Nhập nội dung tin nhắn"></textarea></p>';
		
		
		$body .= '</fieldset>';
		
		
		
		
		
		
		$modal = Yii::$app->zii->renderModal([
				//'action' => 'quick-sent-request-change-for-operator',
				'name'=>$modalName,
				'body'=>$body,
				'class'=>'w60',
				'title' => 'Gửi yêu tin nhắn cho <b class="red">' . $u['name'] .'</b>',
				'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-send-o"></i> Gửi đi</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Đóng</button></div>'
		]);
		
		$complete_function = '';
		
		$complete_function .= 'izi.openModal($d.modal);jQuery("#'.$r2.'").checkboxradio();';
		
		break;
	
	
	case 'quick-sent-qoutation-for-sale':
		$item_id = $id = post('item_id',0);
		$request_id = post('request_id',0);
		$customer_id = post('customer_id',0);
		$role = post('role'); //
		$cus = \app\modules\admin\models\Customers::getItem($customer_id);
		$model = \app\modules\admin\models\ToursPrograms::getItem($item_id);
		// Gửi cho sale
		$request = \app\modules\admin\models\SaleSentRequest::getItem($request_id);
		$u = \app\modules\admin\models\Users::getItem($request['created_by']);
		// Gửi thư + thông báo
		$title = $text = '';
		
		if($role == 1){
		
		$title = '<b class="red">['.$request['code'].'] </b>  <b>' . Yii::$app->user->name . '</b> gửi bảng tính tour ' . '(v'.($model['sent_repeated']+1).')';
		$text = '<p>Xin chào <b>'.$u['name'].'</b></p>';
		$text .= '<p><b>'.Yii::$app->user->name.'</b> đã hoàn tất bảng tính tour cho yêu cầu của bạn .</p>';
		$text .= '<p>Chi tiết bảng tính xem <a target="_blank" href="'.\app\modules\admin\models\AdminMenu::get_menu_link('tours_programs').'/view?id='.$id.'">tại đây</a></p>';
		
		\app\modules\admin\models\Mailbox::sentMessage([
				'from'	=>	Yii::$app->user->id,
				'to'	=>	!empty($u) ? $u['id'] : 0,
				'title'	=>	$title,
				'text'	=>	$text,
				'biz'	=>	[
						'request_id' 	=>	$request['id'],
						'quotation_id' 	=>	$id,
						//'intra_request'=>true,
						//'view_more_link' => \app\modules\admin\models\AdminMenu::get_menu_link('sale_sent_request'),
				],
				'notify_title' => '<b class="red">['.$request['code'].'] </b>  <b>' . Yii::$app->user->name . '</b> đã hoàn thành bảng tính tour '
		]);
		
		Yii::$app->db->createCommand()->update( \app\modules\admin\models\ToursPrograms::tableName(),
				['sent_repeated' => (new \yii\db\Expression('sent_repeated+1'))])->execute();
				//
				$log = '<b class="red">['.$request['code'].'] </b>  <b>' . Yii::$app->user->name . '</b> gửi bảng tính tour ' . '<b>cho '.$u['name'].' (lần '.($model['sent_repeated']+1).')</b>';
				Yii::$app->log2->setLog($log, \app\modules\admin\models\ToursPrograms::tableName(),['id'=>$id]);
		//		Yii::$app->view->registerJs('openModal(\'Thông báo\',\'Gửi thông báo thành công cho <b>'.$u['name'].'</b>\');'); 
		$a = 'Gửi thông báo thành công cho <b>'.$u['name'].'</b>';		
		}else{
			$a = '';
		}
		// Gửi cho khách
		if(post('confirm_send_to_customer') == 'on'){
			//
			$fx = Yii::$app->zii->getConfigs('CONTACTS');
			$fx['sender'] = $fx['email'];
			$fx['short_name']  = $fx['short_name'] != "" ? $fx['short_name'] : $fx['name'];
			
			$fx1 = Yii::$app->zii->getConfigs('EMAILS_RESPON');
			
			if(isset($fx1['RP_CONTACT'])){
				$fx['email'] = $fx1['RP_CONTACT']['email'] != "" ? $fx1['RP_CONTACT']['email'] : $fx['email'];
			}
			
			
			
			Yii::$app->zii->sendEmail(array(
					'subject'=>"Bảng báo giá chương trình tour",
					'body'=>Yii::$app->izi->Tour_RenderEmailForGuest($item_id),
					'from'=>$fx['sender'],
					'fromName'=>$fx['short_name'] != "" ? $fx['short_name'] : $fx['name'],
					//'replyTo'=>$fx['email'],
					//'replyToName'=>$fx['short_name'],
					'to'=>$cus['email'],
					'toName'=>$cus['short_name'],
					'write_log'=>false,
			));
			
			$a .= '<br>Gửi báo giá thành công cho <b>'.$cus['code'].'</b>';
		}
		$callback_function .= 'openModal(\'Thông báo\',\''.$a.'\');';		
		
		break;
		
	case 'quick-sent-request-change-for-operator':
		$item_id = $id = post('item_id',0);
		$request_id = post('request_id',0);
		$customer_id = post('customer_id',0);
		$f = post('f');
		$u = \app\modules\admin\models\SaleSentRequest::getUserConfirmed($request_id);
		$request = \app\modules\admin\models\SaleSentRequest::getItem($request_id);
		
		// gửi thông báo cho điều hành
		$title = '<b class="red">['.$request['code'].'] </b>  <b>' . Yii::$app->user->name . '</b> gửi yêu cầu thay đổi dịch vụ ';
		$text = '<p>Xin chào <b>'.$u['name'].'</b></p>';
		$text .= '<p><b>'.Yii::$app->user->name.'</b> gửi yêu cầu thay đổi dịch vụ  .</p>';
		$text .= '<div class=""><p>Nội dung yêu cầu:</p>'.$f['text'].'</div>';
		$text .= '<p>Chi tiết bảng tính xem <a target="_blank" href="'.\app\modules\admin\models\AdminMenu::get_menu_link('tours_programs').'/edit?id='.$id.'">tại đây</a></p>';
		
		\app\modules\admin\models\Mailbox::sentMessage([
				'from'	=>	Yii::$app->user->id,
				'to'	=>	!empty($u) ? $u['id'] : 0,
				'title'	=>	$title,
				'text'	=>	$text,
				'biz'	=>	[
						'request_id' 	=>	$request['id'],
						'quotation_id' 	=>	$id,
						//'intra_request'=>true,
						//'view_more_link' => \app\modules\admin\models\AdminMenu::get_menu_link('sale_sent_request'),
				],
				'notify_title' => '<b class="red">['.$request['code'].'] </b>  <b>' . Yii::$app->user->name . '</b> gửi yêu cầu thay đổi dịch vụ '
		]);
		
		$log = '<b class="red">['.$request['code'].'] </b>  <b>' . Yii::$app->user->name . '</b> gửi yêu cầu thay đổi dịch vụ '  ;
		$log .= '<p>'.$f['text'].'</p>';
		Yii::$app->log2->setLog($log, \app\modules\admin\models\ToursPrograms::tableName(),['id'=>$id]);		
		$callback_function .= 'openModal(\'Thông báo\',\'Gửi yêu cầu thành công\');';
		
		break;
		
	case 'quick-submit-open-form-resend-send-msg-for-sale':
		$item_id = $id = post('item_id',0);
		$request_id = post('request_id',0);
		$customer_id = post('customer_id',0);
		$f = post('f');
		//$u = \app\modules\admin\models\SaleSentRequest::getUserConfirmed($request_id);
		$request = \app\modules\admin\models\SaleSentRequest::getItem($request_id);
		$u = \app\modules\admin\models\Users::getItem($request['created_by'],['validateSid'=>0]);
		// gửi thông báo cho sale
		$title = '<b class="red">['.$request['code'].'] </b> <b>' . Yii::$app->user->name . '</b> gửi tin nhắn ' . '';
		$text = '<p>Xin chào <b>'.$u['name'].'</b></p>';
		//$text .= '<p><b>'.Yii::$app->user->name.'</b> gửi yêu cầu thay đổi dịch vụ  .</p>';
		//$text .= '<p><b>'.Yii::$app->user->name.'</b> gửi yêu cầu thay đổi dịch vụ  .</p>';
		$text .= '<div class="">
'.$f['text'].'</div>';
		$text .= '<p>Chi tiết bảng tính xem <a target="_blank" href="'.\app\modules\admin\models\AdminMenu::get_menu_link('tours_programs').'/view?id='.$id.'">tại đây</a></p>';
		
		\app\modules\admin\models\Mailbox::sentMessage([
				'from'	=>	Yii::$app->user->id,
				'to'	=>	!empty($u) ? $u['id'] : 0,
				'title'	=>	$title,
				'text'	=>	$text,
				'biz'	=>	[
						'request_id' 	=>	$request['id'],
						'quotation_id' 	=>	$id,
						
				],
				'notify_title' => '<b class="red">['.$request['code'].'] </b> <b>' . Yii::$app->user->name . '</b> gửi tin nhắn cho bạn</b>'
		]);
				
		$log = '<b class="red">['.$request['code'].'] </b>  <b>' . Yii::$app->user->name . '</b> gửi tin nhắn cho <b>'.$u['name'].'</b>';
		$log .= '<p>'.$f['text'].'</p>';
		Yii::$app->log2->setLog($log, \app\modules\admin\models\ToursPrograms::tableName(),['id'=>$id]);
		
		$callback_function .= 'openModal(\'Thông báo\',\'Gửi tin nhắn thành công\');';
		break;
		
		
	case 'open-form-resend-send-msg-for-sale':
		$item_id = post('item_id',0);
		$request_id = post('request_id',0);
		$customer_id = post('customer_id',0);
		$role = post('role',1);
		$request = \app\modules\admin\models\SaleSentRequest::getItem($request_id);
		$u = \app\modules\admin\models\Users::getItem($request['created_by'],['validateSid'=>0]);
		$cus = \app\modules\admin\models\Customers::getItem($customer_id);
		//$user_confirmed = \app\modules\admin\models\SaleSentRequest::getUserConfirmed($request_id);
		$body = '';
		
	 
		$r2 = randString(10);
		$body .= '<fieldset class="f12px mgb15"><legend>Nội dung</legend>';
		 
		$body .= '<p><textarea name="f[text]" class="form-control required" required="" rows=5 placeholder="Nhập nội dung tin nhắn"></textarea></p>';	
		 						
				
		$body .= '</fieldset>';
		
		  
		
		
		 
		
		$modal = Yii::$app->zii->renderModal([
				//'action' => 'quick-sent-request-change-for-operator',
				'name'=>$modalName,
				'body'=>$body,
				'class'=>'w60',
				'title' => 'Gửi yêu tin nhắn cho <b class="red">' . $u['name'] .'</b>',
				'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-send-o"></i> Gửi đi</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Đóng</button></div>'
		]);
		
		$complete_function = '';
		
		$complete_function .= 'izi.openModal($d.modal);jQuery("#'.$r2.'").checkboxradio();';
		
		break;	
	
	case 'open-form-resend-request-change':
		$item_id = post('item_id',0);
		$request_id = post('request_id',0);
		$customer_id = post('customer_id',0);
		$role = post('role',1);
		$request = \app\modules\admin\models\SaleSentRequest::getItem($request_id);
		$u = \app\modules\admin\models\Users::getItem($request['created_by'],['validateSid'=>0]);
		$cus = \app\modules\admin\models\Customers::getItem($customer_id);
		//$user_confirmed = \app\modules\admin\models\SaleSentRequest::getUserConfirmed($request_id);
		$body = '';
		
	 
		$r2 = randString(10);
		$body .= '<fieldset class="f12px mgb15"><legend>Thông tin yêu cầu</legend>';
		 
		$body .= '<p><textarea name="f[text]" class="form-control required" required="" rows=5 placeholder="Nhập thông tin yêu cầu"></textarea></p>';	
		 						
				
		$body .= '</fieldset>';
		
		  
		
		
		 
		
		$modal = Yii::$app->zii->renderModal([
				'action' => 'quick-sent-request-change-for-operator',
				'name'=>$modalName,
				'body'=>$body,
				'class'=>'w60',
				'title' => 'Gửi yêu cầu điều chỉnh dịch vụ',
				'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-send-o"></i> Gửi đi</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Đóng</button></div>'
		]);
		
		$complete_function = '';
		
		$complete_function .= 'izi.openModal($d.modal);jQuery("#'.$r2.'").checkboxradio();';
		
		break;	
		
	case 'quick-submit-open-form-quotation-confirm':
		$item_id = post('item_id',0);
		$request_id = post('request_id',0);
		$customer_id = post('customer_id',0);
		$request = \app\modules\admin\models\SaleSentRequest::getItem($request_id);
		$item = \app\modules\admin\models\ToursPrograms::getItem($item_id);
		// 
		Yii::$app->db->createCommand()->update(\app\modules\admin\models\ToursPrograms::tableName(), ['is_confirmed'=>1],[
				'id'=>$item_id,
				'sid'=>__SID__
		])->execute();
		// Gửi thông báo
		$notify_title = 'Chương trình <b>'.$item['code'].'</b> đã được chốt thành công.';
		$message = [
				'to'	=>	[$request['created_by'],$item['created_by']],
				'title'	=>	$notify_title,
				'link'	=>	\app\modules\admin\models\AdminMenu::get_menu_link('tours_programs') .'/edit?id='.$item_id
		];
		Yii::$app->notify->sentNotify($message);
		
		$callback_function .= 'jQuery(".btn-quotation-confirm").attr(\'disabled\',\'\');';
		
		$complete_function .= 'openModal(\'Thông báo\',\'Xác nhận báo giá thành công.\');';
		break;
	case 'open-form-quotation-confirm':
		$item_id = post('item_id',0);
		$request_id = post('request_id',0);
		$customer_id = post('customer_id',0);
		$role = post('role',1);
		$request = \app\modules\admin\models\SaleSentRequest::getItem($request_id);
		$u = \app\modules\admin\models\Users::getItem($request['created_by'],['validateSid'=>0]);
		$cus = \app\modules\admin\models\Customers::getItem($customer_id);
		
		$body = '<fieldset class="f12px mgb15">
<legend>Lưu ý</legend>
<p class="pm0 italic text-muted">- Sau khi chốt báo giá, một số trường thông tin sẽ bị khóa lại không thể thay đổi (hoặc nhờ Admin can thiệp).</p>
<p class="pm0 italic text-muted">- Các thay đổi về dịch vụ đều phải được thực hiện bởi người tạo báo giá (Điều hành)</p>

</fieldset>'; 
		
		if($role == 1){
		$body .= '<fieldset class="f12px mgb15">
<legend>Thông tin Sale</legend>
<p class="pm0"><b class="green">'.$u['name'].'</b> | <b>'.$u['email'].'</b> | <b>'.$u['phone'].'</b></p>
		</fieldset>';
		}
		$r2 = randString(10);
		$body .= '<fieldset class="f12px mgb15"><legend>Thông tin Khách hàng</legend>';
		if(!empty($cus)){
			$body .= '<p class=""><b class="green">'.$cus['name'].'</b> | <b>'.$cus['email'].'</b> | <b>'.$cus['phone'].'</b></p>';
			if(validateEmail($cus['email'])){
				//$body .= '<label><input type="checkbox" '.($role == 2 ? 'checked' : '').' name="confirm_send_to_customer" id="'.$r2.'"> Gửi báo giá cho <b class="red">'.$cus['code'].'</b></label>';
				//$body .= '<p class="pm0 italic">Báo giá sẽ được gửi tới địa chỉ email <b>'.$cus['email'].'</b></p>';
			}else{
				//$body .= '<p class="pm0 italic">Địa chỉ email không hợp lệ</p>';
			}
			
		}else{  
			$body .= '<p>Chưa có thông tin</p>';	
		}							
				
		$body .= '</fieldset>';
		
		  
		
		
		 
		
		$modal = Yii::$app->zii->renderModal([
				//'action' => 'quick-sent-qoutation-for-sale',
				'name'=>$modalName,
				'body'=>$body,
				'class'=>'w60',
				'title' => 'Chốt báo giá với khách hàng',
				'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-check-square-o"></i> Xác nhận</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Đóng</button></div>'
		]);
		
		$complete_function = '';
		
		$complete_function .= 'izi.openModal($d.modal);jQuery("#'.$r2.'").checkboxradio();';
		
		break;	
	case 'open-form-resend-request':
		$item_id = post('item_id',0);
		$request_id = post('request_id',0);
		$customer_id = post('customer_id',0);
		$role = post('role',1);
		$request = \app\modules\admin\models\SaleSentRequest::getItem($request_id);
		$u = \app\modules\admin\models\Users::getItem($request['created_by'],['validateSid'=>0]);
		$cus = \app\modules\admin\models\Customers::getItem($customer_id);
		
		$body = '<fieldset class="f12px mgb15">
<legend>Lưu ý</legend>
<p class="pm0 italic text-muted">
Báo giá luôn được gửi 01 bản cho Sale, muốn gửi bản copy cho khách hàng hãy tích chọn ô bên dưới 
</p>
</fieldset>';
		
		if($role == 1){
		$body .= '<fieldset class="f12px mgb15"><legend>Thông tin Sale</legend>
		<p class="pm0"><b class="green">'.$u['name'].'</b> | <b>'.$u['email'].'</b> | <b>'.$u['phone'].'</b></p>
 


		</fieldset>';
		}
		$r2 = randString(10);
		$body .= '<fieldset class="f12px mgb15"><legend>Thông tin Khách hàng</legend>';
		if(!empty($cus)){
			$body .= '<p class=""><b class="green">'.$cus['name'].'</b> | <b>'.$cus['email'].'</b> | <b>'.$cus['phone'].'</b></p>';
			if(validateEmail($cus['email'])){
				$body .= '<label><input type="checkbox" '.($role == 2 ? 'checked' : '').' name="confirm_send_to_customer" id="'.$r2.'"> Gửi báo giá cho <b class="red">'.$cus['code'].'</b></label>';
				$body .= '<p class="pm0 italic">Báo giá sẽ được gửi tới địa chỉ email <b>'.$cus['email'].'</b></p>';
			}else{
				$body .= '<p class="pm0 italic">Địa chỉ email không hợp lệ</p>';
			}
			
		}else{  
			$body .= '<p>Chưa có thông tin</p>';	
		}							
				
		$body .= '</fieldset>';
		
		  
		
		
		 
		
		$modal = Yii::$app->zii->renderModal([
				'action' => 'quick-sent-qoutation-for-sale',
				'name'=>$modalName,
				'body'=>$body,
				'class'=>'w60',
				'title' => 'Gửi báo giá cho Sale & Khách hàng',
				'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-send-o"></i> Gửi đi</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Đóng</button></div>'
		]);
		
		$complete_function = '';
		
		$complete_function .= 'izi.openModal($d.modal);jQuery("#'.$r2.'").checkboxradio();';
		
		break;
		
	case 'intra_request_select_operator_receive':
		$f = post('f',[]);
		$tr = '';
		if(!empty($f)){
			foreach ($f as $k=> $id){
				$user = \app\modules\admin\models\Users::getItem($id);
				$tr .= '<tr><td  >'.uh($user['fullName']).'</th><td class="center" >
'.(isset($user['groupName']) ? $user['groupName'] : '').'
</td>
<th class="center "></td>
<td class="center">
<input class="intra_request_list_operator_receive_existed" name="biz[user][]" data-title="'.uh($user['fullName']).'" type="hidden" value="'.$user['id'].'"/>
<i onclick="removeTrItem(this);" class="hover-red pointer fa fa-trash" title="Xóa"></i>
 
</td></tr>';
			}
		}
		
		$responData['tr'] = $tr;
		$callback_function .= 'jQuery(".hmkijs").append($d.tr);closeAllModal();';
		
		
		break;
	case 'request-quick-filter-user-group':
		$val = post('value');
		$r = post('role');
		$body = '';
		foreach (\app\modules\admin\models\Users::getAll(['group_id'=>$val]) as $k => $v){
			
			$body .= '<tr class="tr-user-'.$v['id'].'"><th class="center" scope="row">'.($k+1).'</th>
					
<td class="">
'.uh($v['fullName']).'
</td>
<td class="">'.(isset($v['groupName']) ? $v['groupName'] : '').'</td>
<td class="center">
 <input type="checkbox" value="'.$v['id'].'" name="f[]"  data-role="'.$r.'"/>
</td>
</tr>';
		}
		$responData['body'] = $body;
		$callback_function .= 'jQuery(".list-child-item").html($d.body); 
jQuery(".hmkijs .intra_request_list_operator_receive_existed").each(function(i,e){
jQuery(\'.tr-user-\'+(jQuery(e).val())).find("input").attr("disabled","");
});
';
		break;
	case 'intra_request_list_operator_receive':
		$r = randString();
		$body .= '<table class="table table-hover table-bordered vmiddle">
<caption class="bold f14px">Danh sách thành viên </caption>
<thead>
<tr>
<th class="center w50p">
<p class="mgb0">
<input type="checkbox" onchange="checkAllChild(this);" data-role="'.$r.'"/>
</p> 
</th>
<th class="center w50p">STT</th><th  >Họ tên</th><th class="center" >
<select data-role="'.$r.'" onchange="call_ajax_function(this);" data-action="request-quick-filter-user-group" class="form-control input-sm">
<option value="0">Nhóm / Phòng ban</option>';
		foreach (\app\modules\admin\models\Permission::getAll() as $v1){
			$body .= '<option value="'.$v1['id'].'">'.$v1['title'].'</option>';
		}
$body .= '</select>
</th>


</tr>
</thead>
<tbody class="list-child-item">';
		foreach (\app\modules\admin\models\Users::getAll([
				'p'=>1,
				'not_in' => Yii::$app->user->id
		]) as $k => $v){
			$body .= '<tr onclick="selectCurrentCheckbox(this);" class="tr-user-'.$v['id'].'">
<td class="center">
 <input type="checkbox" value="'.$v['id'].'" name="f[]"  data-role="'.$r.'"/> 
</td>
<th class="center" scope="row">'.($k+1).'</th>

<td class="">
'.uh($v['fullName']).'
</td>
<td class="">'.(isset($v['groupName']) ? $v['groupName'] : '').'</td> 

</tr>';
		}

$body .= '
 

</tbody>

</table>';
		
		$modal = Yii::$app->zii->renderModal([
		'action' => 'intra_request_select_operator_receive',
		'name'=>$modalName,
		'body'=>'<div class="col-sm-12"><div class="mgb5 pr member-avatar mgt10">
'.$body.'
</div></div>',
'class'=>'w60',
'title' => 'Chọn thành viên',
'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-save"></i> Chọn</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Đóng</button></div>'
		]);
		
		$complete_function = '';		
		$complete_function .= 'izi.openModal($d.modal);
jQuery(".hmkijs .intra_request_list_operator_receive_existed").each(function(i,e){
jQuery(\'.tr-user-\'+(jQuery(e).val())).find("input").attr("disabled","");
});
chosen_select_init();';
		
		break;
	case 'intra_sent_request_to_operator':
		$id = post('id',0);
		$f = \app\modules\admin\models\FormActive::getFormSubmit();
		
		$f['date_start'] = $f['date_start']  != "" ? ctime(['string'=>$f['date_start'],'format'=>'Y-m-d']) : date('Y-m-d'); 
		$deadline = post('deadline');
		$f['deadline_date'] = ctime(['string'=>$deadline['date'] != '' ? $deadline['date'] : date('Y-m-d'),'format'=>'Y-m-d']);
		
		
		$hour = (isset($deadline['hour']) ? $deadline['hour'] : 0);
		$minute = (isset($deadline['minute']) ? $deadline['minute'] : 0);
		$second = (isset($deadline['second']) ? $deadline['second'] : 0);
		$f['deadline_time'] = "$hour:$minute:$second";
		
		$f['deadline_ext_time'] = isset($deadline['ext_time']) ? $deadline['ext_time'] : 0;
		$f['deadline_ext_type'] = isset($deadline['ext_type']) ? $deadline['ext_type'] : 1;
		$make_request = post('make_request');
		
		//$callback_function .= 'log(\''.json_encode($f).'\');';
		
		//break;
		if($id>0){
			$con = array('id'=> $id,'sid'=>__SID__);
			$f['lastmodify'] =  time();
			$f['title'] = \app\modules\admin\models\SaleSentRequest::getRequestTitle($f);
			Yii::$app->db->createCommand()->update(\app\modules\admin\models\SaleSentRequest::tableName(),$f,$con)->execute();
		}else{
			$f['code'] = \app\modules\admin\models\SaleSentRequest::genCode();
			$f['sid'] = __SID__;
			$f['created_by'] = Yii::$app->user->id;
			$f['created_at'] = $f['lastmodify'] =  time();
			$f['title'] = \app\modules\admin\models\SaleSentRequest::getRequestTitle($f);
			
			$id = Yii::$app->zii->insert(\app\modules\admin\models\SaleSentRequest::tableName(),$f);
			$href = ABSOLUTE_ADMIN_ADDRESS. '/' . post('controller_text') . '/edit?id='.$id;
			$callback_function .= 'changeUrl(\'Edit\',\''.$href.'\');';
		}
		 
		
		$biz = post('biz',[]);
		$users = isset($biz['user']) ? $biz['user'] : [];
		//
		Yii::$app->db->createCommand()->delete('intra_request_to_users',['and',[
				'not in', 'user_id', $users
		],[
				'request_id'=>$id
		]])->execute();
		if(!empty($users)){
			foreach ($users as $user){
				if((new \yii\db\Query())->from('intra_request_to_users')->where([
						'user_id'=>$user,
						'request_id'=>$id,
				])->count(1) == 0){
					Yii::$app->db->createCommand()->insert('intra_request_to_users', [
							'user_id'=>$user,
							'request_id'=>$id,
					])->execute();
				}
			}
		}
		
		if($make_request == 'on'){
			$con = array('id'=> $id,'sid'=>__SID__);
			Yii::$app->db->createCommand()->update(\app\modules\admin\models\SaleSentRequest::tableName(),[
					'repeated' => new \yii\db\Expression('repeated+1'),
			],$con)->execute();
			
			 
			//
			$text = '';
			$days = isset($biz['day']) ? $biz['day'] : [];
			
			if(!empty($days)){
				$text .= '<table class="table table-hover table-bordered vmiddle"><thead>
<tr><th class="center w50p">Ngày</th><th class="center" style="width: 200px">Địa danh</th><th class="center">Chi tiết</th></tr></thead><tbody class="">';
				foreach ($days as $k=>$day){
					$text .= '
<tr class="tr-index-x tr-index-0x">
<th class="center" scope="row">'.($k+1).'</th>
<td class="center"><a href="javascript:void(0)"><span>'.(isset($day['place_name']) ? $day['place_name'] : '').'</span></a></td>
<td class="">'.(isset($day['text']) ? $day['text'] : '').'</td>
</tr>';
					
				}
				
				$text .= '<tr><td colspan="2" class="center">Ghi chú</td>
<td class="">'.(isset($biz['note']) ? $biz['note'] : '').'</td></tr>';
				$text .= '</tbody></table>';	
				
				$text .= '<p>Xem chi tiết yêu cầu <a href="'.\app\modules\admin\models\AdminMenu::get_menu_link('sale_sent_request').'/view?id='.$id.'" class="bold underline f14px" target="_blank">tại đây</a></p>';
			}
			
			$user = \app\modules\admin\models\SaleSentRequest::getUserConfirmed($id);
			 
			\app\modules\admin\models\Mailbox::sentMessage([
					'from'	=>	Yii::$app->user->id,
					'to'	=>	!empty($user) ? $user['id'] : $users,
					'title'	=>	$f['title'],
					'text'	=>	$text,
					'biz'	=>	[
							'request_id' => $id,
							'intra_request'=>true,
							'view_more_link' => \app\modules\admin\models\AdminMenu::get_menu_link('sale_sent_request'),
					],
					'notify_title' => '<b>' . Yii::$app->user->name . '</b> gửi yêu cầu tính tour' . '<p>'.$f['title'].'</p>'
			]);
			 
			 
			$callback_function .= '
showModal(\'Thông báo\',\'Gửi yêu cầu thành công. Vui lòng chờ phản hồi từ người nhận.\');
	$this.find("input,button,select,textarea,a,i").attr("disabled","").addClass("disabled");
$this.find("select").find("option").attr("disabled","");
jQuery(".chosen-select").trigger("chosen:updated"); 
';
			
		}
		
		// $callback_function .= 'console.log(data);';
		
		break;
	 
}