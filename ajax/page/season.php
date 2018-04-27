<?php
use yii\db\Query;

switch (post('action')){
	
	case 'lt-al-2018-2028':
		$data = \moonland\phpexcel\Excel::widget([
		'mode' => 'import',
		'fileName' => post('value'),
		'setFirstRecordAsKeys' => false, // if you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
		'setIndexSheetByName' => true, // set this if your excel data with multiple worksheet, the index of array will be set with the sheet name. If this not set, the index will use numeric.
		//'getOnlySheet' => 'sheet1', // you can set this property if you want to get the specified sheet from the excel data with multiple worksheet.
		]);
		$responData['file'] = $data;
		$callback_function .= 'console.log(data);';
		break;
	case 'quick-submit-seassion-copy-time':
		$f = post('f',[]);
		$type_id = post('type_id');
		$season_id = post('season_id');
		$supplier_id = post('supplier_id');
		$parent_id = post('parent_id');
		$cp_am = post('cp_am','');
		//$season_id = post('season_id');
		$season = \app\modules\admin\models\Seasons::getItem($season_id);
		$y1 = date('Y', strtotime($season['from_date']));
		$y2 = date('Y', strtotime($season['to_date']));
		$sy = $y2 - $y1;
		$rename_title = post('rename_title');
		//$callback_function .= 'console.log(\''.$rename_title.'\');';
		if($rename_title != ''){
			$season['title'] = $rename_title;
		}
		$data = [
				'sid'=>__SID__,
				'type_id'=>$type_id
		];
		
		$date_range = ceil((mktime(date('H', strtotime($season['to_date']))
				,date('m', strtotime($season['to_date']))
				,date('s', strtotime($season['to_date']))
				,date('m', strtotime($season['to_date']))
				,date('d', strtotime($season['to_date']))
				,date('Y', strtotime($season['to_date']))
				) - mktime(
				date('H', strtotime($season['from_date']))
				,date('m', strtotime($season['from_date']))
				,date('s', strtotime($season['from_date']))
				,date('m', strtotime($season['from_date']))
				,date('d', strtotime($season['from_date']))
				,date('Y', strtotime($season['from_date']))
				)
				)/86400);
		
		///$data['r'] = $date_range;
		//
		
		$calendar1 = Yii::$app->calendar->getArrayDateInfo(date('d',strtotime($season['from_date']))
				, date('m',strtotime($season['from_date']))
				, date('Y',strtotime($season['from_date'])));
		$calendar2 = Yii::$app->calendar->getArrayDateInfo(date('d',strtotime($season['to_date']))
				, date('m',strtotime($season['to_date']))
				, date('Y',strtotime($season['to_date'])));
		//
		$ngay_am1 = $calendar1['output_am'];
		$ngay_am2 = $calendar2['output_am'];
		//
		if($cp_am == 'on'){			
			$y1 = date('Y', strtotime($ngay_am1));
			$y2 = date('Y', strtotime($ngay_am2));
			$sy = $y2 - $y1;
			
			$d1 = date('d', strtotime($ngay_am1));
			$m1 = date('m', strtotime($ngay_am1));
			
			$d2 = date('d', strtotime($ngay_am2));
			$m2 = date('m', strtotime($ngay_am2));
			
		}
		//
					
		//
		
		if(!empty($f)){
			foreach ($f as $year){
				$y3 = $year - $sy;
				// Them vao ban season
				if(strtotime($season['from_date']) > strtotime($season['to_date'])){
					
				}
				
				// 
				$from_time = mktime(0,0,0, date('m',strtotime($season['from_date'])), date('d',strtotime($season['from_date'])), date('Y',strtotime($season['from_date'])));
				$to_date = mktime(0,0,0, date('m',strtotime($season['to_date'])), date('d',strtotime($season['to_date'])), date('Y',strtotime($season['to_date'])));
								
				
				//
				if($cp_am == 'on'){
					//$ngay_am1 = "$y3-$m1-$d1";
					$ngay_am2 = "$year-$m2-$d2";
					
					$ngay_am1 = date('Y-m-d',mktime(23,59,59,$m2,$d2-$date_range,$year));
					
					//
					//$data['dr'] = $date_range;
					//$data['n1'] = $ngay_am1;
					//$data['n2'] = $ngay_am2;
					//
					$data['from_date'] = Yii::$app->calendar->convertAmlich2Duonglich($ngay_am1) . ' 00:00:00';
					$data['to_date'] = Yii::$app->calendar->convertAmlich2Duonglich($ngay_am2) . ' 23:59:59';
				}else{
					$data['from_date'] = date($y3. "-m-d 00:00:00", strtotime($season['from_date']));
					$data['to_date'] = date("$year-m-d 23:59:59", strtotime($season['to_date']));
				}
				
				if(strtotime($data['to_date']) > strtotime($data['from_date'])){
				
				$data['title'] = str_replace([$y1,$y2,'{{%YEAR}}','{{%YEAR2}}','{{%YEAR1}}'], [$y3,$year,$year,$year,$y3], $season['title']);
				$season_id = \app\modules\admin\models\Seasons::insertSeason($data);
				// Them vao bang spl
				$data2 = [
						'season_id' => $season_id,
						'parent_id'=>$parent_id,
						'supplier_id'=>$supplier_id,
						'type_id'=>$type_id,
				];
				
				Yii::$app->db->createCommand()->insert('seasons_to_suppliers', $data2)->execute();
				}
			}
		}
		$responData['data']=$data;
		
		$callback_function .= '
var $tab = jQuery(".nav.nav-tabs>li.active>a");
var $href = $tab.attr("href");
closeAllModal();reloadAutoPlayFunction(true);
var $tab2 = jQuery(".nav.nav-tabs>li>a[href="+$href+"]"); 
console.log($d.data);
//$tab2.click();
';
		break;
	case 'seassion-copy-time':
		$season_id = post('season_id');
		$supplier_id = post('supplier_id');
		$season = \app\modules\admin\models\Seasons::getItem($season_id);
		$year1 = date('Y',strtotime($season['from_date']));
		$year = $year2 = date('Y',strtotime($season['to_date']));
		
		$calendar1 = Yii::$app->calendar->getArrayDateInfo(date('d',strtotime($season['from_date']))
				, date('m',strtotime($season['from_date']))
				, date('Y',strtotime($season['from_date'])));
		$calendar2 = Yii::$app->calendar->getArrayDateInfo(date('d',strtotime($season['to_date']))
				, date('m',strtotime($season['to_date']))
				, date('Y',strtotime($season['to_date'])));
		$body = '
<fieldset class="f12px mgb15">
<legend>Khoảng thời gian hiện tại</legend>
<p class="pm0 italic ">Ngày dương lịch: <b>'.date('d/m/Y',strtotime($season['from_date'])).' - '.date('d/m/Y',strtotime($season['to_date'])).'</b></p>
 
<p class="pm0 italic ">Ngày âm lịch: <b class="green">'.$calendar1['ngay_am'].' - '.$calendar2['ngay_am'].'</b></p>
				
</fieldset>

<fieldset class="f12px mgb15">
<legend>Bạn muốn chép thời gian cho năm nào ?</legend>';
		
		
		
		for($y = $year+1;$y<((date('Y')+11)) ;$y++){
			
			if((new Query())->from(['a'=>'seasons'])
					->innerJoin(['b'=>'seasons_to_suppliers'],'a.id=b.season_id')
					->where([
					'b.supplier_id'=>$supplier_id,		
					'a.type_id'=>$season['type_id'],
					'a.sid'=>__SID__,
					'a.from_date'=>getBeginTimeOfDate(date('d/m/'.$y, strtotime($season['from_date']))),
					'a.to_date'=>getEndTimeOfDate(date('d/m/'.$y, strtotime($season['to_date']))),
			])->count(1) == 0){
			
				$body .= '<label for="checkbox-'.$y.'">'.$y.'</label>
    			<input type="checkbox" checked name="f[]" value="'.$y.'" id="checkbox-'.$y.'" class="checkboxradio">';
			}
		}
		$body .= '<label class="fl100 mgt15">Tiêu đề</label>
<input placeholder="ex: {{%YEAR}}" name="rename_title" type="text" value="'.str_replace([$year,$year1], ['{{%YEAR}}','{{%YEAR1}}'], $season['title']).'" class="form-control input-sm required"/>
';
		
	//	$body .= '<label for="checkbox-cc-am" class="mgt5">Chép năm âm lịch</label>
    //			<input type="checkbox" name="cp_am" id="checkbox-cc-am" class="checkboxradio">';
		
		$body .= '</fieldset>';
 
		
				
		
		
		$modal = Yii::$app->zii->renderModal([
				//'action' => 'quick-sent-qoutation-for-sale',
				'name'=>$modalName,
				'body'=>$body,
				'class'=>'w60 mw500',
				'title' => 'Sao chép khoảng thời gian',
				'footer' => '<div class="modal-footer">
<button type="submit" class="btn btn-primary">
<i class="fa fa-check-square-o"></i> Xác nhận</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">
<i class="fa fa-close"></i> Đóng</button></div>'
		]);
		
		$complete_function = '';
		
		$complete_function .= 'izi.openModal($d.modal);jQuery("input.checkboxradio").checkboxradio();';
		break;
}