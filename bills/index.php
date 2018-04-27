 
<?php
  
echo '<div class="admin-menu-index col-sm-12 col-xs-12"><div class="row"><div class="table-responsive ">';
echo '<table class="table table-bordered vmiddle table-hover fixedHeader">';
echo afShowThread(array(
	array(
			'name'=>'Mã phiếu',
			'class'=>' center',
	),
	array(
			'name'=>'Ngày bán',
			'class'=>'center',
	),
	array(
			'name'=>'Khách hàng / NCC',
			'class'=>'center '
	),
		array(
				'name'=>'Người bán',
				'class'=>'center '
		),
		array(
				'name'=>'Cửa hàng / Chi nhánh',
				'class'=>'center '
		),
		
		array(
				'name'=>'Trạng thái',
				'class'=>'center '
		),
		array(
				'name'=>'Tổng tiền ('.Yii::$app->currency['symbol'].')',
				'class'=>'center '
		),
		
		array(
				'name'=>'Còn nợ ('.Yii::$app->currency['symbol'].')',
				'class'=>'center '
		),
 

)); 
echo '<tbody>';
$role = [
		'type'=>'single',
		'table'=>$model->tableName(),
		'controller'=>Yii::$app->controller->id,		
		'action'=>'Ad_quick_change_item'
];
if(!empty($l['listItem'])){
	foreach($l['listItem'] as $k=>$v){ 
	  $role['id']=$v['id'];	  
	  $link = get_link_edit($v['id']);
	  $del = true;
	  if(in_array($v['status'], [1,6,7])){
	  	$del = false;
	  }
    echo '<tr class="tr_item_'.$v['id'].'">
        '.Ad_list_show_check($v).'
        <td class="center">'.($k+1).'</td>
<td class="center"><a href="'.$link.'"><i class="fa fa-search-plus"></i> '.($v['id']).'</a></td>
<td class="center">'.date('d/m/Y H:i',$v['created_at']).'</td>
<td class="center">'.$v['bill']['customer']['name'].'</td>
<td class="center">'.$v['staff_name'].'</td>
<td class="center">'.$v['branch_code'].'</td> 
<td class="center bill-status-'.$v['status'].'">'.\app\modules\admin\models\Bills::showStatus($v['status']).'</td>
<td class="aright"><b title="'.docso($v['grand_total']).'" class="text-danger">'.getCurrencyText($v['grand_total'],$v['currency'],['show_symbol'=>false]).'</b></td>
<td class="aright"><b title="'.docso($v['owed_total']).'" class="red">'.getCurrencyText($v['owed_total'],$v['currency'],['show_symbol'=>false]).'</b></td>
<td class="center">
<a href="'.$link.'" class="" data-toggle="tooltip" title="Sửa hóa đơn"><i class="fa fa-file-code-o f16pxi hover-red"></i></a>
<a href="#" class="" onclick="call_ajax_function(this);return false;" data-id="'.$v['id'].'" data-action="bill-copy-bill" data-toggle="tooltip" title="Sao chép"><i class="fa fa-copy f16pxi hover-red"></i></a>
<a target="_blank" href="'.cu([CONTROLLER_TEXT . '/print' ,'id'=>$v['id'],'print'=>1]).'" class="" data-toggle="tooltip" title="In đơn hàng"><i class="fa fa-print f16pxi hover-red"></i></a>
<a '.(!$del ? 'disabled="" onclick="return false;"' : 'onclick="call_ajax_function(this);"').' 
data-id="'.$v['id'].'"
data-action="Bill_distroy_item"
href="#" class="" data-toggle="tooltip" title="Hủy đơn hàng">
<i class="fa fa-trash '.(!$del ? 'text-muted fa-disabled' : 'text-danger hover-red').' f16pxi "></i></a>
</td>
</tr>';

  }
}
echo '</tbody></table></div>';
echo afShowPagination(array(
    'btn'=>['del'=>['attr'=>$role]],	
	'p'=>$l['p'],
	'total_records'=>$l['total_records'],
	'total_pages'=>$l['total_pages'],
	'limit'=>$l['limit'],
));
echo '</div></div>';