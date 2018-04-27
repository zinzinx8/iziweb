<?php
function Tour_load_other_cost($id,$o = []){
	$fields = isset($o['fields']) ? $o['fields'] : [];
	$print = isset($o['print']) ? $o['print'] : false;
	$inline_css = isset($o['inline_css']) ? $o['inline_css'] : false;
	
	$cols = 12; $scols= 0;
	
	if((isset($fields['price']) && !$fields['price'])){
		--$cols;
	}
	if((isset($fields['amount']) && !$fields['amount'])){
		--$cols;
	}
	if($print){
		--$cols;
	}

	$scols = 12 - $cols;
	$item = \app\modules\admin\models\ToursPrograms::getItem($id);
	$html = '<table class="table table-bordered vmiddle">
<caption>
<p class="upper bold grid-sui-pheader aleft ">Các khoản chi phí khác</p>
</cation>
<colgroup class="dcols-'.$cols.'">';
	$w = 100/$cols;
	for($i = 0; $i< $cols;$i++){
		$html .= '<col style="width:'.$w.'%">'; 
	}
	$html .= '</colgroup>
<thead>
<tr>
<th class="" colspan="3">Chi phí</th>
<th class="" colspan="3">Diễn giải</th>
<th class="" colspan="2">IN KHHD</th> 
<th class="center">Số lượng</th>
<th class="center">Đơn giá</th>
<th class="center">Thành tiền</th>'.(!$print ? '<th class="center no-print"></th>' : "").'

</tr>
</thead>
<tbody>';
	foreach (\app\modules\admin\models\ToursPrograms::Tour_get_other_cost($id) as $v1){
		
		$ex_rate = Yii::$app->currencies->getExchangeRate([
				'from'=>$v1['currency'],
				'to'=>$item['currency'],
				'exchange_rate'=>isset($item['exchange_rate']) ? $item['exchange_rate'] : [],
		]);
		
		$amount = $v1['quantity'] * $v1['price1'] * ($ex_rate);
		
		 
		$html .= '<tr class="">
<td class="" colspan="3">
<a data-item_id="'.$id.'"
data-id="'.$v1['id'].'"
href="#" 
data-class="w60" 
data-action="add-tours-program-other-prices" 
data-title="Chi phí phát sinh" 
'.(!$print ? 'onclick="open_ajax_modal(this);return false;" 
title="Click để chỉnh sửa"' : "").'
class="" >
'.$v1['title'].'</a>
</td>

<td class="" colspan="3">
<a data-item_id="'.$id.'"
data-id="'.$v1['id'].'"
href="#" 
data-class="w60" 
data-action="add-tours-program-other-prices" 
data-title="Chi phí phát sinh" 
'.(!$print ? 'onclick="open_ajax_modal(this);return false;" 
title="Click để chỉnh sửa"' : "").'
class="" >
'.$v1['note'].'</a>
</td>

<td class="" colspan="2">
<a 
href="#" 
data-item_id="'.$id.'"
data-id="'.$v1['id'].'"
data-class="w60" 
data-action="add-tours-program-other-prices" 
data-title="Chi phí phát sinh" 
'.(!$print ? 'onclick="open_ajax_modal(this);return false;" 
title="Click để chỉnh sửa"' : "").'

class="" > 
'.(isset($v1['plan_note']) ? $v1['plan_note'] : '').'</a>
</td>

<td class="center bold">
'.number_format($v1['quantity'],2).'
</td>
<td class="aright bold">
<b class="'.($ex_rate != 1 ? 'red' : '').'" title="'.($ex_rate != 1 ? 'Đơn giá '.$v1['price1'] . Yii::$app->zii->showCurrency(isset($v1['currency']) ? $v1['currency'] : 1,1) : '').'">'.number_format($v1['price1']*$ex_rate,Yii::$app->zii->showCurrency(isset($item['currency']) ? $item['currency'] : 1,3)).'</b>
</td>
<td class="aright bold red">
'.number_format($amount,Yii::$app->zii->showCurrency(isset($item['currency']) ? $item['currency'] : 1,3)).'
</td> 
'.(!$print ? '<td class="center no-print">

<i 
data-item_id="'.$id.'"
data-id="'.$v1['id'].'"
data-class="w60" 
data-action="add-tours-program-other-prices" 
data-title="Chi phí phát sinh" 
onclick="open_ajax_modal(this);return false;" 
title="Click để chỉnh sửa"
class="fa fa-pencil-square-o f14px hover-red pointer"></i>
<i
title="Xóa chi phí này"
data-action="open-confirm-dialog" 
data-title="Xác nhận xóa chi phí !" 
data-class="modal-sm" 
data-confirm-action="quick_delete_program_other_price" 
onclick="return open_ajax_modal(this);" 
data data-id="'.$v1['id'].'" data-item_id="'.$v1['item_id'].'" 
class="fa fa-trash f14px hover-red pointer"></i>
</td>' : '').'


</tr>';
	}
	
 
	$html .= '<tr><td colspan="'.$cols.'" class="pr vtop">
<p class="aright">
<button 
data-item_id="'.$id.'"
data-toggle="tooltip" 
data-placement="left" 
data-class="w60" 
data-action="add-tours-program-other-prices" 
data-title="Chi phí phát sinh"
onclick="open_ajax_modal(this);" 
title="Chi phí phát sinh"
class="btn btn-danger input-sm" 
type="button">
<i class="fa fa-plus"></i> Thêm chi phí</button></p></td></tr> 
</tbody><tbody></tbody></table>';
	
	return $html;
}