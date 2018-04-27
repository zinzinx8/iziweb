<?php
$status = isset($v['status']) ? $v['status'] : -1;
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
		'restore'
		
];

foreach ($a as $b){
	$$b = false;
}

$a = [];
switch ($status){
	// Pos:
	case 1:  
		$a = [
		'create',
		//'save_temp',
		//'save_continue',
		//'save_print',
		//'confirm',
		//'cancel',
		//'delivery_order',
		//'complete',
	//	'complete_print',
		'print',
		'back'
				
		];
		break;
	case 2: 
	case 3:case 4:
		$a = [
	//'save_temp',
	'save_continue',
	'confirm',
	'delivery_order',
	'back'
			];
			break; 	// Lưu tạm
	
 
	case 5:   // Xác nhận 			
		$a = [
		//'save_temp',
		'save_continue',
		'cancel',
		///'confirm',
		'delivery_order', 
		'back'
				];
		break;	// Đã đặt
	case 6:	// hoàn tất đơn hàng
	case 7:
		$a = [
		'create',
		//'save_temp',
		//'save_continue',
		//'save_print',
		//'confirm',
		//'cancel',
		//'delivery_order',
		//'complete',
		//	'complete_print',
		'print',
		'back'
				
				];
		
		break;	
	case 8:   // giao hàng
		
		$a = [
		//'create',
		//'save_temp',
		//'save_continue',
		//'save_print',
		//'confirm',
		'cancel',
		//'delivery_order',
		'complete',
		'complete_print',
		//'print',
		'back'
				
				];
		break;	
	
	 
	
	case 15:   
		$a = [
		'restore',
		//'save_temp',
		//'save_continue',
		//'save_print',
		//'confirm',
		//'cancel',
		//'delivery_order',
		//'complete',
		//'complete_print',
		//'print',
		'back'
				
				];
		
		break;	// Đã hủy
	default: 
		$a = [
		//'save_temp',
		'save_continue',		 
		//'confirm',		 
		//'delivery_order',		 		  
		'back'				
		];
		break;
}
foreach ($a as $b){
	$$b = true;
}

$btn = [
	
		'restore' => [
				'label'=>'Khôi phục',
				'icon'=>'fa-undo',
				'class'=>'btn-primary btn-index-14',
				'display'=>$save_temp,
				'attrs'=>[
						'data-role'=>14,
						'data-id'=>$id,
						'data-action'=>'Bill_restore_deleted_bill'
				],
				
				'onclick'=>'call_ajax_function(this);',
		],
		'create' => [
				'label'=>'Tạo đơn hàng',
				'icon'=>'fa-plus',
				'class'=>'btn-success btn-index-1',
				'display'=>$save_temp,
				'attrs'=>[
						'data-role'=>1
				],
				'onclick'=>'gotoUrl(\''.cu([CONTROLLER_TEXT . '/add']).'\');',
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
						'data-role'=>15,
						'data-id'=>$id,
						'data-action'=>'Bill_distroy_item'
				],
				'onclick'=>'call_ajax_function(this);',
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
				'onclick'=>'gotoUrl(\''.cu([CONTROLLER_TEXT . '/print','id'=>$id]).'\');',
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

             