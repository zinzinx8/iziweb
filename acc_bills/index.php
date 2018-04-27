<div class="list-btn-hd-fixed list-btn-fixed">
<div class="list-btn2 aright">
<button type="button" data-target="#editFormContent" onclick="call_ajax_function(this);" class="btn btn-sm btn-success" 
data-role="1"><i class="fa fa-plus"></i> Lập phiếu</button>
<div class="btn-export-wtime inline-block"></div>
<button type="button" onclick="goBack(this);" 
class="btn btn-sm btn-default submitForm" data-role="5"><i class="fa fa-arrow-left"></i> Quay lại</button></div></div>


<?php
echo '<div class="admin-menu-index col-sm-12 col-xs-12"><div class="row"><div class="table-responsive ">';
echo '<table class="table table-bordered vmiddle table-hover fixedHeader">';
echo afShowThread(array(
	 
	array(
			'name'=>'Mã phiếu',
			'class'=>'cposition',
			'filter'=>[
					'field'=>'text'
			]
	),
	array(
			'name'=>'Ngày tháng',
			'class'=>'center ctime ',
			'filter'=>[
					'field'=>'date'
			]
	),
		array(
				'name'=>'Số tiền',
				'class'=>'center  ',
				'filter'=>[
						'field'=>'price_range'
				]
		),
		array(
				'name'=>'Nội dung',
				'class'=>'center  '
		),
		

),['CHECK'=>false]); 
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
	  $v['fid'] = '<i class="fa fa-search-plus"></i> ' . $v['id'];
    echo '<tr class="tr_item_'.$v['id'].'">
         
        <td class="center">'.($k+1).'</td>'
        
        .Ad_list_show_link_field($v,['field'=>'fid','link'=>$link])    
    	
        .Ad_list_show_plain_text_field(date('d/m/Y H:i:s',$v['created_at']))
        .Ad_list_show_plain_text_field('<p class="aright bold">'.getCurrencyText($v['amount'],$v['currency'],['show_symbol'=>true]).'</p>')
        .Ad_list_show_plain_text_field($v['reson_text'] .' ' .($v['bill_id'] != '' ? ' - đơn hàng <b class="green">'.$v['bill_id'].'</b>' : ""))
             
.'<td class="center">

<a href="'.$link.'" class="" data-toggle="tooltip" title="Sửa phiếu"><i class="fa fa-pencil-square-o f16pxi hover-red"></i></a>
<a target="_blank" href="'.cu([CONTROLLER_TEXT . '/print' ,'id'=>$v['id'],'print'=>1]).'" class="" data-toggle="tooltip" title="In phiếu"><i class="fa fa-print f16pxi hover-red"></i></a>
 
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