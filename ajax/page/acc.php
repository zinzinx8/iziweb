<?php
switch (post('action')){
	
	case 'Acc_fund_create_fund':
		
		$fund = \app\modules\admin\models\Accounting::getFund(1);
		
		$body .= '<fieldset class="f12px mgb15"><legend>Thông báo</legend>';
		
		$body .= '<p class="pm0">Chức năng này chỉ dành cho tài khoản VIP</p>';
		
		
		 
		
		$body .= '</fieldset>';
		
		
		
		
		$modal = Yii::$app->zii->renderModal([
				//'action' => 'quick-sent-request-change-for-operator',
				'name'=>$modalName,
				'body'=>$body,
				'class'=>'w60',
				'title' => 'Khởi tạo sổ quỹ',
				'footer' => '<div class="modal-footer">
				<!--
<button type="submit" class="btn btn-primary">
<i class="fa fa-check-square-o"></i> Xác nhận</button>-->
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Hủy</button></div>'
		]);
		
		$complete_function = '';
		
		$complete_function .= 'izi.openModal($d.modal);load_datetimepicker2();';
		break;
	case 'quick-submit-Acc_fund_change_pre_amount':
		$fund = \app\modules\admin\models\Accounting::getFund(1);
		$acc_type = post('acc_type');
		$currency = post('currency');
		$amount = cprice(post('amount'));
		$amount1 = $fund['amount'];
		if($amount>0){
			$action = '+';
			if($acc_type == 2){
				$amount *= -1;
				$action = '-';
			}
			$amount1 += $amount;
			//$amount += $fund['pre_amount'];
			$fund['pre_amount'] += $amount;
			$fund['amount'] = $amount1;
			Yii::$app->db->createCommand()->update('acc_funds', [
					'pre_amount'=>$fund['pre_amount'],
					'amount'=>$fund['amount'],
					
			],['id'=>$fund['id'],'sid'=>__SID__])->execute();
			
			Yii::$app->db->createCommand()->update('acc_funds',['check_sum'=>Yii::$app->security->generateFundBookCheckSum($fund)],[
					'sid'=>__SID__,
					'currency'=>$currency,
					'branch_id'=>Yii::$app->user->branch_id,
					'is_locked'=>0,
			])->execute();
			
			
			$logs = isset($fund['logs']) ? $fund['logs'] : [];
			$logs[time()] = 'Thay đổi số dư đầu kỳ quỹ ' . $action . $amount
			
			.'<br/>User: ' . Yii::$app->user->id;
			\app\modules\admin\models\Siteconfigs::updateBizrule('acc_funds',['sid'=>__SID__,'currency'=>$currency,'branch_id'=>Yii::$app->user->branch_id],
					[
							'logs'=>$logs
					]);
			
		}
		$callback_function .= 'closeAllModal();';
		break;
	case 'Acc_fund_change_pre_amount':
		
		$fund = \app\modules\admin\models\Accounting::getFund(1);
		
		$body .= '<fieldset class="f12px mgb15"><legend>Chọn loại tiền tệ</legend>';
		
		$body .= '<p class="pm0"><label class="mgr30"><input type="radio" name="acc_type" checked value="1"/> Thay đổi tăng</label>';
		$body .= '<label><input type="radio" name="acc_type" value="2"/> Thay đổi giảm</label></p>';
		
		 
		$body .= '<div class="form-group">
<div class="col-sm-4 "><label>Chọn quỹ</label><select name="currency" class="form-control input-sm" >';
		foreach (Yii::$app->currencies->getUserCurrency() as $currency){
			$body .= '<option value="'.$currency['id'].'">'.$currency['code'].' ('.$currency['symbol'].')</option>';
		}
		$body .= '</select></div>
<div class="col-sm-8"><label>Số tiền</label>
<input type="text" name="amount" class="form-control number-only input-sm aright required" required placeholder="Nhập số tiền muốn tăng hoặc giảm quỹ"/>
</div>';
		if(!empty($fund)){
			$body .= '<div class="col-sm-12">';
			$body .= '<p class="mgt15 pm0">Số dư đầu kỳ: <b class="green">'.Yii::$app->zii-> showPrice($fund['pre_amount'],$fund['currency']).'</b></p>';
			$body .= '<p class="pm0">Số dư cuối kỳ: <b class="red">'.Yii::$app->zii-> showPrice($fund['amount'],$fund['currency']).'</b></p>';
			$body .= '</div>';
		}
				

		$body .= '</div>';
		
		$body .= '</fieldset>';
		
		
		
		
		$modal = Yii::$app->zii->renderModal([
				//'action' => 'quick-sent-request-change-for-operator',
				'name'=>$modalName,
				'body'=>$body,
				'class'=>'w60',
				'title' => 'Thay đổi số dư đầu kỳ quỹ',
				'footer' => '<div class="modal-footer">
				
<button type="submit" class="btn btn-primary">
<i class="fa fa-check-square-o"></i> Xác nhận</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Hủy</button></div>'
		]);
		
		$complete_function = '';
		
		$complete_function .= 'izi.openModal($d.modal);load_datetimepicker2();';
		break;
	
	case 'quick-submit-Acc_open_fund_date_range':
		$branch_id = post('branch_id',0);
		$currency = post('currency',1);
		$from_date = post('from_date','');
		$to_date = post('to_date','');
		
		if($from_date == ''){
			$from_date = date('Y-m-01');
		}
		if($to_date == ''){
			$to_date= date('Y-m-d');
		}
		
		$from_date = ctime(['string'=> $from_date,'format'=>'Y-m-d']);
		$to_date = ctime(['string'=> $to_date,'format'=>'Y-m-d']);
		
		//$web = post(''); 
		
		$controller_text = post('controller_text');
		$href = cu([$controller_text . '/view',
				'branch_id'=>$branch_id,
				'currency'=>$currency,
				'from_date'=>$from_date,
				'to_date'=>$to_date,
		]);
		$callback_function .= 'changeUrl(\'Edit\',\''.$href.'\');reload();';
		
		break;
	case 'Acc_open_fund_date_range':	

		$body .= '<fieldset class="f12px mgb15"><legend>Tiêu chí xem</legend>';
		
		$body .= '<p class="pm0"><label><input type="radio" name="month_type" checked/> Tháng hiện hành</label></p>';
		$body .= '<p class="pm0"><label><input type="radio" name="month_type" /> Khoảng thời gian</label></p>';
		$body .= '<div class="form-group">
<div class="col-sm-6">Từ ngày

<div class="input-group">
<input name="from_date" type="text"
data-format="d/m/Y"
class="form-control datetimepicker2"
placeholder="'.(date('01/m/Y')).'" value=""/>              
                    <span class="input-group-addon">
                        <span onclick="showCalendar(this);" class="pointer fa fa-calendar"></span>
                    </span>
                </div>


</div>
<div class="col-sm-6 pr">Đến ngày
<div class="input-group">
<input name="to_date" type="text"
data-format="d/m/Y"
class="form-control datetimepicker2"
data-maxDate="'.(date('d/m/Y')).'"
placeholder="'.(date('d/m/Y')).'" value=""/>              
                    <span class="input-group-addon">
                        <span onclick="showCalendar(this);" class="pointer fa fa-calendar"></span>
                    </span>
                </div>
</div>
</div>';
		$body .= '<div class="form-group">
<div class="col-sm-6 "><label>Tiền tệ</label><select name="currency" class="form-control input-sm" >';
		foreach (Yii::$app->currencies->getUserCurrency() as $currency){
			$body .= '<option value="'.$currency['id'].'">'.$currency['code'].' ('.$currency['symbol'].')</option>';
		}
$body .= '</select></div>
<div class="col-sm-6"><label>Chi nhánh</label>
<select name="branch_id" class="form-control input-sm" >';
		foreach (\app\modules\admin\models\Branches::getAll() as $currency){
			$body .= '<option value="'.$currency['id'].'">'.$currency['name'].' </option>';
		}
$body .= '</select>
</div>

</div>';		
		
		$body .= '</fieldset>';
		
		
						
		
		$modal = Yii::$app->zii->renderModal([
				//'action' => 'quick-sent-request-change-for-operator',
				'name'=>$modalName,
				'body'=>$body,
				'class'=>'w60',
				'title' => 'Tổng hợp sổ quỹ',
				'footer' => '<div class="modal-footer">

<button type="submit" class="btn btn-primary">
<i class="fa fa-check-square-o"></i> Xem sổ quỹ</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Hủy</button></div>'
		]);
		
		$complete_function = '';
		
		$complete_function .= 'izi.openModal($d.modal);load_datetimepicker2();';
		break;
		
	 
}