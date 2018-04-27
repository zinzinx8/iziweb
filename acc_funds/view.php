<div class="list-btn-hd-fixed list-btn-fixed" >
 
        <div class="list-btn2 aright">
<?php 
include 'epage/btn-list.php';
?>            
        </div>
    </div>
<?php 

$currency = getParam('currency',1);
$pre_amount = \app\modules\admin\models\AccFunds::getPreAmount($currency, getParam('from_date'));
//view(\app\modules\admin\models\AccFunds::getPreAmount($currency, '2018-03-22'));
?>
<table class="table table-bordered table-hover vmiddle">
<caption>

<?php 
echo '<label class="col-sm-12 aright f14px bold" title="Số dư quỹ tính đến ngày '.date('d/m/Y',strtotime(getParam('from_date'))).'">Số dư đầu kỳ: '.getCurrencyText($pre_amount,$currency,['show_symbol'=>false]).'</label>';
?>
</caption>
<thead class="bg-success">
<tr>
<th rowspan="2" class="center">STT</th>
<th rowspan="1" colspan="3" class="center">Chứng từ</th>
<th rowspan="2" class="center">Diễn giải</th>
<th rowspan="2" class="center">Thu</th>
<th rowspan="2" class="center">Chi</th>
<th rowspan="2" class="center">Tồn quỹ</th>

</tr>
<tr>

<th rowspan="1" class="center">Ngày tháng</th>
 <th rowspan="1" class="center">Thu</th>
 <th rowspan="1" class="center">Chi</th>
</tr>
</thead>
<tbody>
<?php
$sub_total = $pre_amount;
$tong_thu = $tong_chi = 0;
foreach (\app\modules\admin\models\AccBills::getAll(['type_id'=>[1,2], 'order_by'=>['a.created_at'=>SORT_ASC]]+$_GET) as $k1=>$v1){
	if($v1['type_id'] == 1){
		$sub_total += $v1['amount'];
		$tong_thu += $v1['amount'];
	}else{
		$sub_total -= $v1['amount'];
		$tong_chi += $v1['amount'];
	}
	echo '<tr>
<th scope="row" class="row w50p center" >'.($k1+1).'</th>
<td rowspan="1" class="center">'.date('d/m/Y',$v1['created_at']).'</td>
<td rowspan="1" class="center">'.($v1['type_id'] == 1 ? $v1['id'] : '').'</td>
<td rowspan="1" class="center">'.($v1['type_id'] == 2 ? $v1['id'] : '').'</td>
<td rowspan="1" class="aleft">'.(isset($v1['reson_text']) ? uh($v1['reson_text']) : '').'</td>
<td rowspan="1" class="aright">'.($v1['type_id'] == 1 ? getCurrencyText($v1['amount'],$v1['currency'],['show_symbol'=>false]) : '').'</td>
<td rowspan="1" class="aright">'.($v1['type_id'] == 2 ? getCurrencyText($v1['amount'],$v1['currency'],['show_symbol'=>false]) : '').'</td>

<td rowspan="1" class="aright ">'.getCurrencyText($sub_total,$currency,['show_symbol'=>false]) .'</td>
</tr>';
}
?>
</tbody>

<tfoot class="bgeee">
<tr class="bold">
<th rowspan="1" colspan="4" class="aright"></th>
<th rowspan="1" class="">Tổng cộng: </th>
<th rowspan="1" class="aright green underline">
<?php echo getCurrencyText($tong_thu,$currency,['show_symbol'=>false]) ;?>
</th>
<th rowspan="1" class="aright red underline"><?php echo getCurrencyText($tong_chi,$currency,['show_symbol'=>false]) ;?></th>
<th rowspan="1" class="aright"></th>
 
</tr>
<tr class="bold">
<th rowspan="1" colspan="4" class="aright"></th>
<th rowspan="1" class="">Số dư cuối kỳ: </th>
<th rowspan="1" class="aright"></th>
<th rowspan="1" class="aright"></th>
<th rowspan="1" class="aright">
<?php 
$du_cuoi = ($tong_thu - $tong_chi + $pre_amount);

echo '<b class="underline '.($du_cuoi > 0 ? 'green' : 'red').'">' . getCurrencyText($du_cuoi,$currency,['show_symbol'=>false]) . '</b>' ;?>
<?php 

?>
</th>
 
</tr>
</tfoot>
</table>