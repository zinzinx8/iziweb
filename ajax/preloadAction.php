<?php
switch (Yii::$app->request->post('action')){
		
	case 'countNotifis':
		$noti = (new \yii\db\Query())
		->from(['b'=>'notifications'])
		->leftJoin(['a'=>'{{%notifications_to_users}}'],'b.id=a.notify_id')
		->where(['or',['b.type_id'=>0,'b.sid'=>__SID__,'b.state'=>-1],[
			'a.state'=>-1,
			'a.user_id'=>Yii::$app->user->id,
			'a.type_id'=>1
		]])->count(1);
		
		echo json_encode([
			'unview'=>$noti,
			'b'=>2,
			'new_mail' => \app\modules\admin\models\Mailbox::countItemChild(1,['is_read'=>0])
		]);exit; 
		break; 
	case 'admin_init':
		$callback_function = '';
		
		// Load css
		$callback_function .= 'loadCssFiles($r.script,2);'; 
		$r = [
				'https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css',
				__LIBS_DIR__ . '/themes/css/base.css?v='.__TIME__,
				__LIBS_DIR__ . '/font-awesome/css/font-awesome.min.css',
				__LIBS_DIR__ . '/fontello/css/fontello.css',
				
				//__LIBS_DIR__ . '/bootstrap/datetime/bootstrap-datetimepicker.css',
				__LIBS_DIR__ . '/ui-datetimepicker/jquery.datetimepicker.css',
				__LIBS_DIR__ . '/bootstrap/colorpicker/dist/css/bootstrap-colorpicker.min.css',
				
				__LIBS_DIR__ . '/bootstrap/tagsinput/dist/bootstrap-tagsinput.css',
				__LIBS_DIR__ . '/tagsinput/dist/jquery.tagsinput.min.css',
				__LIBS_DIR__ . '/vendors/nprogress/nprogress.css',
//				__LIBS_DIR__ . '/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css',
				__LIBS_DIR__ . '/chosen/chosen.css',
				__LIBS_DIR__ . '/shieldui/css/light/all.min.css',
				__LIBS_DIR__ . '/popup/colorbox/colorbox.css',
				__LIBS_DIR__ . '/contextMenu/dist/jquery.contextMenu.css',
				__LIBS_DIR__ . '/menu/superfish-1.7.4/src/css/full.css?v='.__TIME__,
				__LIBS_DIR__ . '/onoff/jquery.switchButton.css',
				__LIBS_DIR__ . '/select2/dist/css/select2.min.css',
				  
				__LIBS_DIR__ . '/jquery-ui-1.12.1/themes/base/jquery-ui.min.css',
				__LIBS_DIR__ . '/jquery-ui-1.12.1/themes/base/theme.css', 
				__RSDIR__ .'/dist/css/AdminLTE.min.css?v='.__TIME__,
				
		];
		$r[] = __RSDIR__ .'/build/css/custom.css?v='.__TIME__;
		$r[] = __LIBS_DIR__ . '/bootstrap/assets/css/docs.css';
		$r[] = __RSDIR__ .'/plugins/Uniform-3.0/dist/css/default.css';
		
		
		
		$r[] = __RSDIR__ .'/bower_components/Ionicons/css/ionicons.min.css';
		$r[] = __RSDIR__ .'/bower_components/jvectormap/jquery-jvectormap.css';
		$r[] = __RSDIR__ .'/bower_components/datatables/datatables.min.css';
		$r[] = __RSDIR__ .'/plugins/iCheck/flat/blue.css';
		// Load 
		
		
		
		
		 
		
		
		
		
		
		echo json_encode([
		'script'=>$r,
		'callback'=>true,
		'callback_function' => $callback_function
		]); exit;
		break;
	
	case 'admin_init_js':
		$r = []; $callback_function = '';
		$r = [
				Yii::getAlias('@admin') . '/js/functions.js?v='.__TIME__,
				__LIBS_DIR__ . '/jquery-ui-1.12.1/jquery-ui.min.js',
				__LIBS_DIR__ . '/ckeditor/ckeditor.js',
				__LIBS_DIR__ . '/onoff/jquery.switchButton.js',
				
				// 
				
			//	__LIBS_DIR__ . '/bootstrap/assets/js/moment.min.js',
				//__LIBS_DIR__ . '/bootstrap/datetime/bootstrap-datetimepicker.js',
				__LIBS_DIR__ . '/jquery.maskedinput/dist/jquery.maskedinput.min.js',
				//
				
				__LIBS_DIR__ . '/ui-datetimepicker/build/jquery.datetimepicker.full.js',
				
				__LIBS_DIR__ . '/bootstrap/bootstrap.file-input.js',
				__LIBS_DIR__ . '/bootstrap/colorpicker/dist/js/bootstrap-colorpicker.min.js',
				__LIBS_DIR__ . '/scrolls/slimscroll/jquery.slimscroll.min.js',
				__LIBS_DIR__ . '/themes/js/typeahead.bundle.js',
				__LIBS_DIR__ . '/bootstrap/tagsinput/dist/bootstrap-tagsinput.min.js',
				__LIBS_DIR__ . '/tagsinput/dist/jquery.tagsinput.min.js',
				__LIBS_DIR__ . '/menu/superfish-1.7.7/src/js/hoverIntent.js',
				__LIBS_DIR__ . '/menu/superfish-1.7.7/src/js/superfish.js?v='.__TIME__,
				__RSDIR__ 	 . '/js/jquery.number.min.js',
				__LIBS_DIR__ . '/themes/js/jquery-migrate-1.4.1.min.js',
				__LIBS_DIR__ . '/chosen/chosen.jquery.js',
				__LIBS_DIR__ . '/chosen/chosen.ajaxaddition.jquery.js',
				//__LIBS_DIR__ . '/shieldui/js/shieldui-all.min.js', 
				__LIBS_DIR__ . '/themes/js/base.js?v='.__TIME__,
				__LIBS_DIR__ . "/bootstrap/3.2.0/js/ie10-viewport-bug-workaround.js",
				__LIBS_DIR__ . "/bootstrap/assets/js/bootstrap-tooltip.js",
				__LIBS_DIR__ . '/select2/dist/js/select2.full.js',
				__LIBS_DIR__ . "/bootstrap/assets/js/bootstrap-tooltip.js",
				__LIBS_DIR__ . '/bootstrap/assets/js/bootstrap-confirmation.js',
				__LIBS_DIR__ . '/jquerycookie/jquery.cookie.js',
				__LIBS_DIR__ . '/themes/js/jquery.ddslick.min.js',
				__RSDIR__ .'/bower_components/fastclick/lib/fastclick.js',
			//	__RSDIR__ .'/dist/js/adminlte.js',
			//	'https://adminlte.io/themes/AdminLTE/dist/js/adminlte.js',
				
				__RSDIR__ .'/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js',
				
				__RSDIR__ .'/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
				__RSDIR__ .'/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
				__RSDIR__ .'/bower_components/jquery-slimscroll/jquery.slimscroll.min.js',
				__RSDIR__ .'/bower_components/chart.js/chart.js',
				__RSDIR__ .'/plugins/Uniform-3.0/dist/js/jquery.uniform.standalone.js',
				__RSDIR__ .'/plugins/tablefixedheader/tablefixedheader.js?v='.__TIME__,
				__RSDIR__ .'/bower_components/jquery-knob/js/jquery.knob.js',
				__RSDIR__ .'/plugins/iCheck/icheck.min.js',
				__LIBS_DIR__ . '/popup/colorbox/jquery.colorbox.js',
				'https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js'
				
		];
		if(!Yii::$app->user->isGuest){
			$controller = Yii::$app->request->post('controller');
			$filename = __RSPATH__ .'/' .ADMIN_VERSION .'/dist/js/pages/' . $controller. '.js';
			if(file_exists($filename)){
				$r[] = __RSDIR__ .'/' .ADMIN_VERSION .'/dist/js/pages/' . $controller. '.js?v='.__TIME__;
			}
		}else{
			$callback_function .= '$(".login-box").removeClass("hide");'; 
		}
				
		 
		echo json_encode([ 
		'script'=>$r,
		'callback'=>true,
		'callback_function' => $callback_function
		]); exit;
		break;
	case 'load_css2':
		$r = []; 
		$r[] = __LIBS_DIR__ . '/themes/css/base.css';
		$r[] = __LIBS_DIR__ . '/bootstrap/tagsinput/dist/bootstrap-tagsinput.css';
		$r[] = __LIBS_DIR__ . '/tagsinput/dist/jquery.tagsinput.min.css';
		$r[] = __LIBS_DIR__ . '/chosen/chosen.css';
		$r[] = __LIBS_DIR__ . '/jquery-ui-1.12.1/jquery-ui.min.css';
		$r[] = __LIBS_DIR__ . '/jquery-ui-1.12.1/jquery-ui.theme.min.css';
		
		$r[] = __RSDIR__ .'/' . ADMIN_VERSION .'/bower_components/font-awesome/css/font-awesome.min.css';
		$r[] = __RSDIR__ .'/' . ADMIN_VERSION .'/bower_components/Ionicons/css/ionicons.min.css';
		$r[] = __RSDIR__ .'/' . ADMIN_VERSION .'/bower_components/jvectormap/jquery-jvectormap.css';
		$r[] = __RSDIR__ .'/' . ADMIN_VERSION .'/bower_components/datatables/datatables.min.css';
		
		echo json_encode(['script'=>$r]); exit;
		break;
	case 'load_js2':
		$r = [];
		//$r[] = __LIBS_DIR__. '/bootstrap/bootstrap.file-input.js';
		//$r[] = __RSDIR__ . '/js/functions.js';
		$r[] = __RSDIR__ .'/' . ADMIN_VERSION .'/dist/js/setup.js';
		//$r[] = __RSDIR__ .'/' . ADMIN_VERSION .'/js/main.js';
		if(!Yii::$app->user->isGuest){
		$controller = Yii::$app->request->post('controller');
		$filename = __RSPATH__ .'/' .ADMIN_VERSION .'/dist/js/pages/' . $controller. '.js';
		if(file_exists($filename)){
			$r[] = __RSDIR__ .'/' .ADMIN_VERSION .'/dist/js/pages/' . $controller. '.js';
		}}
		
		echo json_encode(['script'=>$r,'load_js'=>false,'jsFile'=>'']); exit;
		break;
}
 