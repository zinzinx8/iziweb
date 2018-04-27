<?php
switch (Yii::$app->request->post('action')){
	case 'mes_goto_message':
		$id = post('id');
		$folder = post('folder','inbox');
		$role = post('role','next');
		$web = post('web');		
		$mes = \app\modules\admin\models\Mailbox::getNextMessage($id,$folder,$role);
		$responData['mes'] = $mes;
		if(!empty($mes)){
			$link = Ad_GetUrl([
					'folder'=>$folder,
					'id' => $mes['id'],
			],[
					//'igrones' 	=>	['id'],
					'action'	=>	'view',
					'controller' => $web['controller_text']
			]);
			$callback_function .= 'gotoUrl(\''.$link.'\');';
		}else{
			$callback_function .= '$this.attr(\'disabled\',\'\'); ';
		}
		break;
	case 'mes_delete_single_item':
		$id = post('id');
		$folder = post('folder');
		switch ($folder){
			case 'trash': case 'draft':
				\app\modules\admin\models\Mailbox::moveToTrash($id,true);
				break;
			default:
				\app\modules\admin\models\Mailbox::moveToTrash($id);
				break;
		}
		
		$web = post('web');
		$mes = \app\modules\admin\models\Mailbox::getNextMessage($id,$folder,'next');
		$responData['mes'] = $mes;
		if(!empty($mes)){
			$link = Ad_GetUrl([
					'folder'=>$folder,
					//'id' => $mes['id'],
			],[
					'igrones' 	=>	['id'],
					'action'	=>	false,
					'controller' => $web['controller_text']
			]);
			$callback_function .= 'gotoUrl(\''.$link.'\');';
		}else{
			//$callback_function .= '$this.attr(\'disabled\',\'\'); ';
			$link = Ad_GetUrl([
					'folder'=>$folder,
					//'id' => $mes['id'],
			],[
					'igrones' 	=>	['id','view'],
					'action'	=>	false,
					'controller' => $web['controller_text']
			]);
			$callback_function .= 'gotoUrl(\''.$link.'\');';
		}
		
		break;
}