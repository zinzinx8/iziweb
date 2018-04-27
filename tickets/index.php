<?php
// \app\modules\admin\models\Tickets::updateOldPlace();
echo '<div class="admin-menu-index col-sm-12 col-xs-12"><div class="row"><div class="table-responsive ">';
 
echo '<table class="table table-bordered vmiddle table-hover fixedHeader">';
echo afShowThread(array(
	array(
			'name'=>'Icon',
			'class'=>'cicon center',
	),
	array(
			'name'=>getTextTranslate(54,ADMIN_LANG),
			'class'=>'',
	),
		array(
				'name'=> 'Địa danh',
				'class'=>'center',
		),
	array(
			'name'=>getTextTranslate(57,ADMIN_LANG),
			'class'=>'center cactive',
	),

)); 
echo '<tbody>';
$role = [
		'type'=>'single',
		'table'=>$model->tableName(),
		'controller'=>Yii::$app->controller->id,
			
];
if(!empty($l['listItem'])){
	foreach($l['listItem'] as $k=>$v){
		$role['id']=$v['id'];
		$role['action'] = 'Ad_quick_change_item';
		$link = get_link_edit($v['id']);
		$local = \app\modules\admin\models\Local::getItem($v['local_id']);
    echo '<tr class="tr_item_'.$v['id'].'">
        '.Ad_list_show_check($v).'
        <td class="center">'.($k+1).'</td>
'.Ad_list_show_icon($v).'
       <td class=""><a href="#"
data-title="Cập nhật vé tham quan",
data-action="ajax-form-add-new",
data-class="w90",
data-controller-id="'.CONTROLLER_ID.'",
data-controller="'.Yii::$app->controller->id.'",
data-controller-text="'.CONTROLLER_TEXT.'",
data-id="'.$v['id'].'"
onclick="open_ajax_modal(this);return false;"
>'.$v['title'].'</a></td> 
        <td class="center nowrap">'.($v['place_name']).(!empty($local) ? '<p>['.showLocalName($local['title'],$local['type_id']).']</p>' : '').'</td>';  
    	         
echo Ad_list_show_checkbox_field($v,[
        		'field'=>'is_active',
        		//'class'=>'number-format ',
        		//'decimal'=>0,
        		'role'=>$role
		]);
        echo Ad_list_show_option_field($v,[
        		'role'=>$role,
        		'action'=>'Ad_quick_delete_item',
        		'btn'=>['edit'=>[
        				'attr'=>[
        						'title'=>'Cập nhật vé tham quan',
        						'action'=>'ajax-form-add-new',
        						'class'=>'w90',
        						'controller-id'=>CONTROLLER_ID,
        						'controller'=>Yii::$app->controller->id,
        						'controller-text'=>CONTROLLER_TEXT,
        						'id'=>$v['id']
        						
        				],
        				'event'=>[
        						'onclick'=>'open_ajax_modal(this);return false;'
        				]
        		]]
        ]) .'
      </tr>';

  }
}
echo '</tbody></table></div>';
echo afShowPagination([
		'p'=>$l['p'],
		'total_records'=>$l['total_records'],
		'total_pages'=>$l['total_pages'],
		'limit'=>$l['limit'],
		'btn'=>['del'=>['attr'=>$role]]
]);
echo '</div></div>';