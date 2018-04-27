<?php
$status = 1;
$fund = \app\modules\admin\models\Accounting::getFund(getParam('currency',1));

//if(Yii::$app->controller->action == 'index'){
	if(!empty($fund)){
		$status = 1;
	}else{
		$status = 0;
	}
//}

$a = [
		'create',
		'save_temp',
		'save_continue',
		'save_print',
		'confirm',
		'cancel',
		'delivery_order',
		'complete',
		'complete_print',
		'print',
		'back',
		'restore',
		'change_fund_preamount',		
		'fund_view'
		
];

foreach ($a as $b){
	$$b = false;
}

$a = [];
switch ($status){
	case 0:
		$a = [
		'create',
		//'change_fund_preamount',
		//'fund_view',
		//'delivery_order',
		'back'
				];
		break;
	default: 
		$a = [
		//'save_temp',
		'change_fund_preamount',		 
		'fund_view',		 
		//'delivery_order',		 		  
		'back'				
		];
		break;
}
foreach ($a as $b){
	$$b = true;
}

$btn = [
	
		'change_fund_preamount' => [
				'label'=>'Thay đổi số dư quỹ',
				'icon'=>'fa-random',
				'class'=>'btn-warning btn-index-1',
				'display'=>$save_temp,
				'type'=>'button',
				'attrs'=>[
						'data-role'=>1,
						
						'data-action'=>'Acc_fund_change_pre_amount'
				],
				
				'onclick'=>'call_ajax_function(this);',
		],
		
		'fund_view' => [
				'label'=>'Xem sổ quỹ',
				'icon'=>'fa-eye',
				'class'=>'btn-primary btn-index-2',
				'display'=>$save_temp,
				'type'=>'button',
				'attrs'=>[
						'data-role'=>2,
						'data-controller_text'=>CONTROLLER_TEXT,
						'data-action'=>'Acc_open_fund_date_range'
				],				
				'onclick'=>'call_ajax_function(this);',
		],
		
		
		'restore' => [
				'label'=>'Khôi phục',
				'icon'=>'fa-undo',
				'class'=>'btn-primary btn-index-14',
				'display'=>$save_temp,
				'attrs'=>[
						'data-role'=>14,
						
						'data-action'=>'Bill_restore_deleted_bill'
				],
				
				'onclick'=>'call_ajax_function(this);',
		],
		'create' => [
				'label'=>'Tạo sổ quỹ',
				'icon'=>'fa-plus',
				'class'=>'btn-success btn-index-1',
				'display'=>$save_temp,
				'attrs'=>[
						'data-role'=>1,
						'data-action'=>'Acc_fund_create_fund',
				],
				'onclick'=>'call_ajax_function(this);',
				'type'=>'button'
		],
		'save_temp' => [
				'label'=>'Lưu tạm',
				'icon'=>'fa-save',
				'class'=>'btn-warning btn-index-2',
				'display'=>$save_temp,
				'attrs'=>[
						'data-role'=>2
				],
				'onclick'=>'setBtnClick(this);',
		],
		
		'save_continue' => [
				'label'=>'Lưu lại & tiếp tục',
				'icon'=>'fa-save',
				'class'=>'btn-warning btn-index-3',
				'display'=>$save_temp,
				'attrs'=>[
						'data-role'=>3
				],
				'onclick'=>'setBtnClick(this);',
		],
		
		'save_print' => [
				'label'=>'Lưu tạm & In',
				'icon'=>'fa-print',
				'class'=>'btn-primary btn-index-4',
				'display'=>$save_temp,
				'attrs'=>[
						'data-role'=>4
				],
				'onclick'=>'setBtnClick(this);',
		],
		
		
		'cancel' => [
				'label'=>'Hủy đơn hàng',
				'icon'=>'fa-pause-circle-o',
				'class'=>'btn-danger btn-index-15',
				'display'=>$save_temp,
				'attrs'=>[
						'data-role'=>15
				],
				'onclick'=>'setBtnClick(this);',
		],
		
		
		
		'confirm' => [
				'label'=>'Xác nhận',
				'icon'=>'fa-check-square-o',
				'class'=>'btn-primary btn-index-5',
				'display'=>$save_temp,
				'attrs'=>[
						'data-role'=>5
				],
				'onclick'=>'setBtnClick(this);',
		],
		
		'complete' => [
				'label'=>'Hoàn tất',
				'icon'=>'fa-check-circle',
				'class'=>'btn-primary btn-index-6',
				'display'=>$save_temp,
				'attrs'=>[
						'data-role'=>6
				],
				'onclick'=>'setBtnClick(this);',
		],
		'complete_print' => [
				'label'=>'Hoàn tất & In',
				'icon'=>'fa-print',
				'class'=>'btn-primary btn-index-7',
				'display'=>$save_temp,
				'attrs'=>[
						'data-role'=>7
				],
				'onclick'=>'setBtnClick(this);',
		],
		
		'delivery_order' => [
				'label'=>'Giao hàng',
				'icon'=>'fa-truck',
				'class'=>'btn-primary btn-index-8',
				'display'=>$save_temp,
				'attrs'=>[
						'data-role'=>8
				],
				'onclick'=>'setBtnClick(this);',
		],
		
		
		
		'print' => [
				'label'=>'In đơn hàng',
				'icon'=>'fa-print',
				'class'=>'btn-warning btn-index-9',
				'display'=>$save_temp,
				'attrs'=>[
						'data-role'=>9
				],
				//'onclick'=>'gotoUrl(\''.cu([CONTROLLER_TEXT . '/print','id'=>$id]).'\');',
		],
		
		
		
		
		
		
		
		
	'back' => [
			'label'=>'Quay lại',
			'icon'=>'fa-arrow-left',
			'class'=>'btn-default',
			'display'=>$save_temp,
			'onclick'=>'gotoUrl(\''.cu([CONTROLLER_TEXT.DS]).'\');',
			
	]	
];

foreach ($btn as $k => $b){ 
	if($$k){
		echo '<button type="'.(isset($b['type']) ? $b['type'] : 'submit').'" 
class="btn '.$b['class'].'" title="'.(isset($b['title']) ? $b['title'] : '').'"
'.(isset($b['onclick']) ? 'onclick="'.$b['onclick'].'" ' : '');
		if(isset($b['attrs']) && !empty($b['attrs'])){
			foreach ($b['attrs'] as $key=>$value){
				echo $key .'="'.$value.'" ';
			}
		} 
echo '> 
	    <i class="fa '.$b['icon'].'"></i>&nbsp;<span class="ng-binding">'.$b['label'].'</span></button>';
	}
}

?>

             