<?php 
$language = (\app\modules\admin\models\AdLanguage::getUserLanguage());
 
?>
<div class="clear"></div>
<div class="p-contentxx "> 
 
         <div class="col-sm-12s">
<form class="form-inline hide" method="post" action="" data-action="current" onsubmit="return submitAjax(this);" style="margin-bottom: 10px"> 
  <div class="form-group">
   <b class="f12e">ID [<?php echo $model->getID();?>]</b> 
    <input style="min-width:200px" type="text" name="f[value]" value="" class="form-control input-sm required " placeholder="Thêm nhanh (tiếng việt)">
  </div>
  <input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"/>
  <input type="hidden" value="quickAddTextTranslate" name="action"/>
  <input type="hidden" name="ajaxSubmit" value="1"/>
  <button type="submit" class="btn btn-sm btn-default btn-sd"><i class="glyphicon glyphicon-plus"></i> Thêm nhanh</button>
  	 
</form>
 
<div class="table-responsive ">
<?php
 
  
?>
  <table class="table table-bordered  table-hover fixedHeader">
  <?php
  $a = $b = array();
  $b[] = [
  		'name' => 'T.Code',
  		'class'=>'center ',
  ];
  if(!empty($language)){
  	foreach ($language as $x){
  		if(isset($x['is_default']) && $x['is_default'] == 1){
  			$b[] = array('name'=>$x['title'] .' ('.$x['code'].')','class'=>'');
  		}else{
  			$a[] = array('name'=>$x['title'] .' ('.$x['code'].')','class'=>'');
  		}
  	}
  }
  if(__IS_ROOT__){
  $a[] = array(
        'name'=>'A.Load',
        'class'=>'center',
    );
  
  }
  echo afShowThread(array_merge($b, $a),array('CHECK'=>false,'ACTION'=>false,'STT' => __IS_ROOT__ ? 
  		'<button 
data-action="open_modal_add_text_translate"
onclick="call_ajax_function(this);" 
type="button" title="Thêm nhanh"><i class="fa fa-plus"></i></button>' : 'ID'));
  ?>

    <tbody>
<?php
$role = [
		'type'=>'single',
		'table'=>$model->tableName(),
		'controller'=>Yii::$app->controller->id,
			
			
];
if(!empty($l['listItem'])){ 
	foreach ($l['listItem'] as $k=>$v){
		$role['id']=$v['id'];
		$role['action'] = 'Ad_quick_change_item';
		//$role_c = str_replace('"','&quot;',json_encode(array('table'=>$model->tableName(), 'id'=>$v['id'],'lang'=>DEFAULT_LANG)));
  echo '<tr class="tr_item_'.$v['id'].' pd3c5 vmiddle">
         
        <td class="center">'.$v['id'].'</td>
<td class="nowrap">'.(__IS_ROOT__ ? '<input 
data-id="'.$v['id'].'" 
data-action="change_text_teranslate_data"
data-btn="lang_code"
onblur="call_ajax_function(this);" 
class="w100 sui-input sui-input-focus" data-old="'.$v['lang_code'].'" value="'.$v['lang_code'].'"/>' : $v['lang_code']).'
</td>
 
        <td class="">
<input 
data-id="'.$v['id'].'"
data-action="change_text_teranslate_data"
data-btn="value"
onblur="call_ajax_function(this);" 
data-lang="'.DEFAULT_LANG.'" 
class="w100 sui-input sui-input-focus" data-old="'.uh($v['value']).'" value="'.uh($v['value']).'"/></td>';
  if(!empty($language)){
  	foreach ($language as $x){
  		if(isset($x['is_default']) && $x['is_default'] == 1){
  			
  		}else{
  			//$role_c = str_replace('"','&quot;',json_encode(array('table'=>$model->tableName(), 'id'=>$v['id'],'lang'=>$x['code']	)));
  			$m = $model->getTextByLang($v['id'],$x['code']);
  			$title = !empty($m) ? uh($m) : '';
  			echo '<td class="nowrap">
<input
data-id="'.$v['id'].'" 
data-action="change_text_teranslate_data"
data-btn="value"
onblur="call_ajax_function(this);" 
data-lang="'.$x['code'].'" 
class="w100 sui-input sui-input-focus" data-old="'.$title.'" value="'.$title.'"/></td>';
  		}
  	}
  }
  if(__IS_ROOT__){
  echo '<td class="center">'.getCheckBox(array(
            'name'=>'auto_load',
            'value'=>$v['auto_load'],
            'type'=>'singer',
            'class'=>'switchBtn ',
            'attr'=>showJqueryAttr($role,true)+array(
                'data-old'=>$v['auto_load'],
                'data-boolean'=>1,
                'data-field'=>'auto_load',
                'onchange'=>'Ad_quick_change_item(this);'
                //'data-table'=>$this->controller()->model->tableName(),
                
            ),
        )).'</td>';
  }
      echo '</tr>';

  }
}


?>
    </tbody>
  </table>
</div>
<?php
 
echo afShowPagination(array(
		'p'=>$l['p'],
		'total_records'=>$l['total_records'],
		'total_pages'=>$l['total_pages'],
		'limit'=>$l['limit'],'select_option'=>false,	
));
?>
 
       </div>
</div>