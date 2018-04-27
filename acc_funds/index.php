<div class="list-btn-hd-fixed list-btn-fixed" >
 
        <div class="list-btn2 aright">
<?php 
include 'epage/btn-list.php';
?>            
        </div>
    </div>
<?php 
if(!empty($l['listItem'])){
	echo '<input type="hidden" 
data-controller_text="'. CONTROLLER_TEXT.'"
class="auto_play_script_function" data-remove="1" data-action="Acc_open_fund_date_range" value="call_ajax_function(this);"/>';
}
?>    


 


<?php
echo '<div class="admin-menu-index col-sm-12 col-xs-12 mgt30"><div class="row">';

if(!empty($l['listItem'])){
echo '<div class="table-responsive ">';


echo '<table class="table table-bordered vmiddle table-hover fixedHeader">';
echo afShowThread(array(
		array(
				'name'=>'Ngày lập',
				'class'=>'center ctime ',
				'filter'=>[
						'field'=>'date'
				]
		),
	array(
			'name'=>'Tiền tệ',
			'class'=>'cposition center',
			'filter'=>[
					'field'=>'text'
			]
	),
		array(
				'name'=>'Số dư đầu kỳ',
				'class'=>'center  ',
				'filter'=>[
						'field'=>'price_range'
				]
		),
		array(
				'name'=>'Số dư cuối kỳ',
				'class'=>'center  ',
				'filter'=>[
						'field'=>'price_range'
				]
		),
		array(
				'name'=>'Trạng thái',
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
		.Ad_list_show_plain_text_field(date('d/m/Y H:i:s',$v['created_at']))
        
		.Ad_list_show_plain_text_field(Yii::$app->currencies->getCurrencyCode($v['currency']))
    	
		.Ad_list_show_plain_text_field('<span class="aright bold green pm0">'.getCurrencyText($v['pre_amount'],$v['currency'],['show_symbol'=>true]).'</span>')
        .Ad_list_show_plain_text_field('<span class="aright bold red pm0">'.getCurrencyText($v['amount'],$v['currency'],['show_symbol'=>true]).'</span>')
        .Ad_list_show_plain_text_field($v['is_locked'] == 1 ? '<span class="label f12px label-default inline-block">Đã khóa</span>' : '<span class="label f12px label-success inline-block">Đang hoạt động</span>')
             
.'<td class="center">

<a href="'.$link.'" class="" data-toggle="tooltip" title="Khóa sổ"><i class="fa fa-lock f16pxi hover-red"></i></a>

 
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
}else{
	echo '<p>Chưa có sổ quỹ nào được tạo</p>';
}
echo '</div></div>';