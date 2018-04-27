<?php
switch (Yii::$app->request->post('action')){
	
	case 'add_user_language':
		$keyCode = 'LANGUAGE';
		$f = post('f',[]);
		$c = Yii::$app->getConfigs($keyCode,false,__SID__,false);
		if(!empty($f)){
			foreach ($f as $item_id){
				$item = \app\modules\admin\models\AdLanguage::getItem($item_id);
				$item['root_active'] = $item['is_active'] = 1;
				$c[] = $item;
			}
		}
		
		
		\app\modules\admin\models\Siteconfigs::updateBizData($c,[
				'code' => $keyCode,
				'sid' => __SID__,
		]);
		$callback_function .= 'reloadAutoPlayFunction(true);closeAllModal();';
		break;
	
	case 'open_modal_language':
		
		
		$body = ' ';
		$body.= '<table class="table table-hover table-bordered vmiddle table-striped"><thead>';
		$body.= '<tr> <th class="w50p center">#</th>
<th>Tiêu đề</th>
<th>Mã code</th>
<th>HL code</th>
<th>Translate code</th>
<th class="center">Chọn</th>
</tr>';
		
		$body.= '</thead><tbody>';
		
		foreach (\app\modules\admin\models\AdLanguage::getAll(['igrone_user_existed'=>true]) as $k=>$v){
			 
			$body.= '<tr>';
			$body.= '<td class="center">'.($k+1).'</td>';
			$body.= '<td>'.$v['title'].'</td>';
			$body.= '<td>'.$v['code'].'</td>';
			$body.= '<td>'.$v['hl_code'].'</td>';
			$body.= '<td>'.$v['lang_code'].'</td>';
			$body.= '<td class="center"><input type="checkbox" name="f[]" value="'.$v['id'].'"/></td>';
						 			
			$body .= '</tr>';
		}
		
		$body .= '</tbody></table>';
		
		$modal = Yii::$app->zii->renderModal([
				'action' => 'add_user_language',
				'name'=>$modalName,
				'body'=>'<div class="col-sm-12"><div class="mgb5 pr member-avatar mgt10">
'.$body.'
</div></div>',
				'class'=>'w60',
				'title' => 'Thêm mới ngôn ngữ',
				'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-save"></i> Cập nhật</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Đóng</button></div>'
		]);
		
		$complete_function .= 'izi.openModal($d.modal);';
		
		
		break;
	
	case 'change_language_status':
		$item_code = post('item_code');
		$item_id = post('item_id',0);
		$checked = post('checked');
		$keyCode = 'LANGUAGE';
		$btn = post('btn');
		$c = Yii::$app->getConfigs($keyCode,false,__SID__,false);
		
		if($item_id == 0){
			$item = \app\modules\admin\models\AdLanguage::getItemByCode($item_code);
		}else{
			$item = \app\modules\admin\models\AdLanguage::getItem($item_id);
		}
		$resetDefaultLang = $rp = false;
		if(!empty($c)){
			//
			switch ($btn){
				case 'is_active': case 'root_active':
					foreach ($c as $k => $lang){
						if($lang['code'] == $item_code){
							if($item_code == ROOT_LANG){
								break;
							}
							
							$c[$k]['item_id'] = $item['id'];
							$c[$k] = array_merge($c[$k],$item);
							$c[$k][$btn] = $checked;
							if($checked == 0){
								$c[$k]['is_active'] = $checked;
								if(isset($lang['is_default']) && $lang['is_default'] == 1){
									$resetDefaultLang = true; 									
								}
							}
							
							break;
						}
					}
					
					break;
					
				case 'is_default':
					foreach ($c as $k => $lang){
						if($lang['code'] == $item_code){
							$c[$k]['is_default'] = $c[$k]['default'] = 1;
						}else{
							$c[$k]['is_default'] = $c[$k]['default'] = 0;
						}
					}
					break;
				case 'delete':
					foreach ($c as $k => $lang){
						if($lang['code'] == $item_code){	
							if(isset($lang['is_default']) && $lang['is_default'] == 1){
								$resetDefaultLang = true;
							}
							unset($c[$k]);
							$rp = true;
							
							break;
						}
						
					}
					break;
			}
			\app\modules\admin\models\Siteconfigs::updateBizData($c,[
					'code' => $keyCode,
					'sid' => __SID__,
			],$rp);
			if($resetDefaultLang){
				\app\modules\admin\models\AdLanguage::setDefaultLanguage(ROOT_LANG);
			}
		}
		
		
		
		$callback_function .= 'reloadAutoPlayFunction(true);';
		//$callback_function .= 'console.log(data);';
		
		break;
		
	case 'load_all_system_language': 
		$html = '';
		$html .= '<table class="table table-hover table-bordered vmiddle table-striped"><thead>';
		$html .= '<tr> <th class="w50p center">#</th> 
<th>Tiêu đề</th>
<th>Mã code</th>
'.(__IS_ROOT__ ? '<th class="center">Root active</th>' : '').' 
<th class="center">Kích hoạt</th>
<th class="center">Mặc định</th>'.(__IS_ROOT__ ? '<th></th>' : '').' </tr>';
		
		$html .= '</thead><tbody>';
		foreach (\app\modules\admin\models\AdLanguage::getList() as $k=>$v){
			$v['is_active'] = isset($v['is_active']) ? $v['is_active'] : 0;
			$v['is_default'] = isset($v['is_default']) ? $v['is_default'] : 0;
			$html .= '<tr>';
			$html .= '<td class="center">'.($k+1).'</td>';
			$html .= '<td>'.(isset($v['title']) ? $v['title'] : '').'</td>';
			$html .= '<td>'.$v['code'].'</td>';		 
			$html .= (__IS_ROOT__ ? '<td class="center"><input 
data-item_code="'.$v['code'].'"
data-item_id="'.(isset($v['id']) ? $v['id'] : 0).'"
data-action="change_language_status" 
data-btn="root_active" 
onchange="call_ajax_function(this);" type="checkbox" name="f['.$k.'][root_active]"
'.($v['code'] == ROOT_LANG ? 'disabled=""' : '').' 
'.(isset($v['root_active']) && $v['root_active'] == 1 ? 'checked' : '').' /></td>' : '');
			
			$html .= '<td class="center"><input '.(isset($v['root_active']) && $v['root_active'] == 1 ? '' : 'disabled').' 
data-item_code="'.$v['code'].'"
data-item_id="'.(isset($v['id']) ? $v['id'] : 0).'"
data-action="change_language_status" 
data-btn="is_active" 
'.($v['code'] == ROOT_LANG ? 'disabled=""' : '').' 
onchange="call_ajax_function(this);" type="checkbox" name="f['.$k.'][is_active]" '.($v['is_active'] == 1 ? 'checked' : '').' value="'.$v['is_active'].'" /></td>

					  <td class="center"><input '.(isset($v['is_active']) && $v['is_active'] == 1 ? '' : 'disabled').' 
data-item_code="'.$v['code'].'"
data-item_id="'.(isset($v['id']) ? $v['id'] : 0).'"
data-action="change_language_status" 
data-btn="is_default" 

onchange="call_ajax_function(this);" data-role="radio_bool1" type="radio" name="f[is_default]" '.(isset($v['is_default']) && $v['is_default'] == 1 ? 'checked' : '').' class="radio_bool1"/></td>';
			$html .= (__IS_ROOT__ ? '<td class="center">'.($v['code'] == ROOT_LANG ? '' : '<i title="Xóa" class="glyphicon glyphicon-trash pointer" 

data-item_code="'.$v['code'].'"
data-item_id="'.(isset($v['id']) ? $v['id'] : 0).'"
data-action="change_language_status" 
data-btn="delete" 

onclick="if(confirm(\'Xác nhận.\')){call_ajax_function(this);}"></i>').'</td>' : '');
			
			$html .= '</tr>';
		}		
		$html .= '</tbody></table>';
		if(__IS_ROOT__){
			$html .= '<p>
<button class="btn btn-default" type="button"
data-action="open_modal_language" 
data-btn="add" 

onclick="call_ajax_function(this)"><i class="fa fa-plus"></i> Thêm ngôn ngữ</button>

</p>';
		}
		 
		break;
}