<?php
switch (Yii::$app->request->post('action')){
	
	case 'change_user_text_teranslate':
		$btn = post('btn');
		$lang_code = post('lang_code');
		switch ($btn){
			case 'delete':
				\app\modules\admin\models\UserTextTranslate::deleteUserText($lang_code);
				Yii::$app->t->deleteLangcode($lang_code);
				$callback_function .= '$this.parent().parent().find("input").val("");';
				break;
				
			case 'load_default':
				$callback_function .= 'var $pr = $this.parent().parent();';
				$language = \app\modules\admin\models\AdLanguage::getUserLanguage();
				if(!empty($language)){
					foreach ($language as $lang){ 
						$v = \app\modules\admin\models\TextTranslate::getItem2($lang_code,$lang['code']);
						if(!empty($v)){
							\app\modules\admin\models\UserTextTranslate::deleteUserText($lang_code, $v['lang']);
							$item = [
									
									'lang_code' => $lang_code,
									'lang' => $v['lang'],
									'value' => $v['value'],
									'sid' => __SID__
							];
							
							Yii::$app->db->createCommand()->insert(\app\modules\admin\models\UserTextTranslate::tableName(),$item)->execute();							
							Yii::$app->t->upadteLangcode($lang_code,$v['lang'],$v['value']);							
							$callback_function .= '$pr.find("input[data-lang='.$v['lang'].']").val(\''.$v['value'].'\');';
						}
					}
				}
				 
				break;
			case 'load_default2':
				$callback_function .= 'var $pr = $this.parent().parent();';
				$l = \app\modules\admin\models\TextTranslate::getListByLangCode($lang_code);
				if(!empty($l)){
					\app\modules\admin\models\UserTextTranslate::deleteUserText($lang_code);
					$language = (\app\modules\admin\models\AdLanguage::getUserLanguageCode());
					foreach ($l as $v){
						if(in_array($v['lang'], $language)){
						$item = [
								
								'lang_code' => $lang_code,
								'lang' => $v['lang'],
								'value' => $v['value'],
								'sid' => __SID__
						];
												
						Yii::$app->db->createCommand()->insert(\app\modules\admin\models\UserTextTranslate::tableName(),$item)->execute();
						
						Yii::$app->t->upadteLangcode($lang_code,$v['lang'],$v['value']);
						
						$callback_function .= '$pr.find("input[data-lang='.$v['lang'].']").val(\''.$v['value'].'\');';
					}}
				}else{
					$callback_function .= 'showModal(\'Thông báo\',\'Không có dữ liệu.\');';
				}
				break;
		}
		
		
		break;
	
	case 'add_text_translate':
		$id = \app\modules\admin\models\TextTranslate::getID();
		$lang_code = str_replace(' ', '', trim(post('lang_code')));
		$f = post('f',[]);
		
		$msg_success = $msg_error = '';
		
		if(($lang_code) != "" &&
			(new \yii\db\Query())->from(\app\modules\admin\models\TextTranslate::tableName())
			->where([
					'lang_code' => $lang_code
			])->count(1) == 0
			){
				if(!empty($f)){
					foreach ($f as $lang=>$value){
						 
						if(trim($value) != ""){
							Yii::$app->db->createCommand()->insert(\app\modules\admin\models\TextTranslate::tableName(),[
									'id' => $id,
									'lang_code' => $lang_code,
									'lang' => $lang,
									'value' => trim($value),
							])->execute();
							
							//
							$msg_success .= '<p>Cập nhật <b class="green">{'.$lang_code.'}/{'.$lang.'}</b> thành công.</p>';
						}
					}
				}
				
				$callback_function .= 'jQuery("#alert_modal_add_text_translate").html(\'<div class="alert alert-success" role="alert">'.$msg_success.'</div>\').show();
jQuery("input[name=lang_code]").focus();
'; 
			}else{
				
				if(!empty($f)){
					foreach ($f as $lang=>$value){
						
						if(trim($value) != "" && (new \yii\db\Query())->from(\app\modules\admin\models\TextTranslate::tableName())
								->where([
										'lang_code' => $lang_code,
										'lang' => $lang
								])->count(1) == 0){
							Yii::$app->db->createCommand()->insert(\app\modules\admin\models\TextTranslate::tableName(),[
									'id' => $id,
									'lang_code' => $lang_code,
									'lang' => $lang,
									'value' => trim($value),
							])->execute();
							$msg_success .= '<p>Cập nhật <b class="green">{'.$lang_code.'}/{'.$lang.'}</b> thành công.</p>';
						}else{
							$msg_error .= '<p>Giá trị <b class="red">{'.$lang_code.'}/{'.$lang.'}</b> không hợp lệ hoặc đã được sử dụng.</p>';
						}
					}
				}
				
				$msg = '';
				if($msg_success != ""){
					$msg .= '<div class="alert alert-success" role="alert">'.$msg_success.'</div>';
				}
				if($msg_error != ""){
					$msg .= '<div class="alert alert-danger" role="alert">'.$msg_error.'</div>';
				}
				if($msg != ""){
					$callback_function .= 'jQuery("#alert_modal_add_text_translate").html(\''.$msg.'\').show();';
				}
			}
		
		
		break;
	case 'open_modal_add_text_translate':
		
		
		$body = '<div class="form-group">
    <label >Translate Code</label>
    <input type="text" class="form-control required" name="lang_code" required="" value="" placeholder="Viết liền không dấu, không ký tự đặc biệt">
  </div>
 
';
		foreach (\app\modules\admin\models\AdLanguage::getUserLanguage() as $v){
			$body .= '<div class="form-group">
    <label >'.$v['title'].' - '.$v['code'].'</label>
    <input type="text" 
class="form-control '.($v['code'] == ROOT_LANG ? 'required' : '').'" '.($v['code'] == ROOT_LANG ? 'required=""' : '').' 
placeholder="" name="f['.$v['code'].']" value="" >
  </div>
					
';
		}
		$idx = 'alert_modal_add_text_translate';
		$body .= '<div class="form-group display-none" id="'.$idx.'"></div>';
		
		$modal = Yii::$app->zii->renderModal([
				'action' => 'add_text_translate',
				'name'=>$modalName,
				'body'=>'<div class="col-sm-12"><div class="mgb5 pr member-avatar mgt10">
'.$body.'
</div></div>',
				'class'=>'w60',
				'title' => 'Thêm mới text',
				'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-save"></i> Lưu lại</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Đóng</button></div>'
		]);
		
		$complete_function .= 'izi.openModal($d.modal);';
		
		
		break;
	case 'change_user_text_teranslate_data':
		$value = post('value');
		$lang = post('lang');
		$lang_code = post('lang_code');
		if($value == ''){
			break;
		}
		
		/*
		 * 1. Kiểm tra bảng user text
		 * 1.1 Nếu chưa có -> thêm vào
		 * 1.2 Nếu đã có -> cập nhật
		 * 
		 * 2. Kiểm tra bản text
		 * 2.1 Nếu chưa có -> thêm vào + đặt state=2
		 * 2.2 Nếu đã có -> Bỏ qua
		 */
	 
		if((new \yii\db\Query())->from(\app\modules\admin\models\UserTextTranslate::tableName())
		->where([
				'lang_code' => $lang_code,
				'lang' => $lang,
				'sid' => __SID__
		])->count(1) == 0){											
			$item = ['sid'=>__SID__,'lang_code'=>$lang_code];
			$item['lang'] = $lang;
			$item['value'] = $value;								
			Yii::$app->db->createCommand()->insert(\app\modules\admin\models\UserTextTranslate::tableName(),$item)->execute();
				
		}else{
			Yii::$app->db->createCommand()->update(\app\modules\admin\models\UserTextTranslate::tableName(),[
					'value' => $value
			],[
					'lang_code' => $lang_code,
					'lang' => $lang,
					'sid' => __SID__
			])->execute();
		}
		
		 
		/*///////////
		if(
				(new \yii\db\Query())->from(\app\modules\admin\models\TextTranslate::tableName())
				->where([
						'lang_code' => $lang_code,
						'lang' => $lang
				])->count(1) == 0
				){
					
					$item = [
							'id' => 	\app\modules\admin\models\TextTranslate::getID(),
							'lang_code' => $lang_code,
							'lang' => $lang,
							'value' => $value,
							'state' => 2,
					];
					 
					
					Yii::$app->db->createCommand()->insert(\app\modules\admin\models\TextTranslate::tableName(),$item)->execute();
					
				} 
		//*/		
		Yii::$app->t->upadteLangcode($lang_code,$lang,$value);
		$callback_function .= 'console.log(data);';
		break;
		
	case 'change_text_teranslate_data':
		$id = post('id');
		$value = post('value');
		$btn = post('btn');
		switch ($btn){
			case 'lang_code':
				$value = str_replace(' ', '', trim($value));
				if(($value) != "" &&
				(new \yii\db\Query())->from(\app\modules\admin\models\TextTranslate::tableName())
				->where([
				'and',['not in','id',$id],[$btn=>$value]
				])->count(1) == 0
				){
					Yii::$app->db->createCommand()->update(\app\modules\admin\models\TextTranslate::tableName(),[
							$btn => $value
					],[
							'id' => $id,
					])->execute();
				}else{
					$callback_function .= 'showModal(\'Thông báo\',\'Giá trị không hợp lệ hoặc đã được sử dụng. Vui lòng chọn giá trị khác.\'); ';
				}
				
				break;
			case 'value':
				$lang = post('lang');
				if(
						(new \yii\db\Query())->from(\app\modules\admin\models\TextTranslate::tableName())
						->where([
								'id' => $id,
								'lang' => $lang
						])->count(1) == 0
				){
							
							$item = \app\modules\admin\models\TextTranslate::getItem($id,ROOT_LANG);	
							$item['lang'] = $lang;
							$item[$btn] = $value;
							
							Yii::$app->db->createCommand()->insert(\app\modules\admin\models\TextTranslate::tableName(),$item)->execute();
										
						}else{
							Yii::$app->db->createCommand()->update(\app\modules\admin\models\TextTranslate::tableName(),[
									$btn => $value
							],[
									'id' => $id,
									'lang' => $lang
							])->execute();
						}
				break;
		}
		
		
		
		//$callback_function .= 'console.log(data);';
		break;
		 
}