<?php
switch (post('action')){
	
	case 'Export-excel-user-translate-data':
		\app\modules\admin\models\UserTextTranslate::exportExcel();
		
	 break;
}