<?php 
//\app\modules\admin\models\Bills::importBillFromOrder('KSJSN1');
//$b = \app\modules\admin\models\Accounting::createBillFromPos($id);
//view($b);
$status = isset($v['status']) ? $v['status'] : -1;

?>

<form name="ajaxForm" action="/ajax" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">
<div class="breadcrumbs breadcrumbs-fixed" >
        <h3>
            <a href="<?php echo \app\modules\admin\models\AdminMenu::get_menu_link('bills');?>" class="hidden-640 ng-binding">Đơn hàng</a>
             
<small class="small-code ng-binding"><i class="fa fa-arrow-right"></i><?php 
echo $id != "" ? $id  : 'Tạo đơn hàng' ;
?></small>

             
        </h3>
        <div class="toolbar">
<?php 
include 'epage/btn-list.php';
?>            
        </div>
    </div>
<?php 
$r = randString(8);
if(isset($v['status']) && in_array($v['status'], [7,6,1])){
$js = 'jQuery(".'.$r.'").find("input,button,textarea,select").attr("disabled","");
jQuery(".'.$r.'").find("i").attr("disabled","").removeAttr("onclick");';
$this->registerJs($js);
}
?>    
<div class="<?=$r?>">    
<input type="hidden" name="action" value="bill-submit-edit-form" />
<input type="hidden" class="btnSubmit" name="btnSubmit" value="" />
<input type="hidden" name="formSubmit" value="true"  />
<input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"/>
<input type="hidden" name="id" value="<?php echo getParam('id','');?>"/> 
<input type="hidden" name="controller_action" value="<?php echo Yii::$app->controller->action->id;?>"/>
<input type="hidden" name="controller_text" value="<?php echo CONTROLLER_TEXT;?>"/>
<div class="col-sm-12 pdt15">
 
<?php 
$currency = Yii::$app->currencies->getItem($v['currency']);

echo '<input 
data-currency="'.$v['currency'].'"
data-decimal_number="'.$currency['decimal_number'].'"

type="hidden" class="bill-detail-infomation"/>';
?> 
 <div class="row">
                <div class="col-xs-12 col-sm-8">
 <div class="row">                    

<div class="mcol-md-20 mcol-xs-50">
	<div class="col-xs-12 alert alert-warning" style="height: 86px">
	<div class="col-xs-9" > <div class="row">
<span title="Tổng tiền hàng" class="ng-binding">Tiền hàng:   
<b class="ng-binding green bill-label-sub-total">
<?php echo getCurrencyText(isset($v['sub_total']) ? $v['sub_total'] : 0,$v['currency'],['show_symbol'=>true]);?></b>
<?php 
echo '<input name="f[sub_total]" class="bill-input-sub-total" value="'.(isset($v['sub_total']) ? $v['sub_total'] : 0).'" type="hidden"/>';
?>
</span>
                                <br>
<span title="Số lượng" class="ng-binding">Số lượng <b class="ng-binding bill-label-total-item"><?php echo ($v['total_item']);?></b>
<?php 
echo '<input name="f[total_item]" class="bill-input-total-item" value="'.(isset($v['total_item']) ? $v['total_item'] : 0).'" type="hidden"/>';
?>
</span>
                                
</div></div>
                            
                            
                            <div class="infobox-icon ps t15 r5	">
                                <i class="fa fa-tags icon-only orange-2 bigger-280" title=""></i>
                            </div>
                        </div>
</div>


<div class="mcol-md-20 mcol-xs-50">
	<div class="col-xs-12 alert alert-warning"  style="height: 86px">
	<div class="col-xs-9" > <div class="row">
<span title="Giảm giá trên tổng đơn hàng" class="ng-binding">Giảm giá: 
<b class="ng-binding bill-label-discount-total"><?php echo getCurrencyText(isset($v['discount_total']) ? $v['discount_total'] : 0,$v['currency'],['show_symbol'=>true]);?></b>
<?php 
echo '<input name="f[discount_total]" class="bill-input-discount-total" value="'.(isset($v['discount_total']) ? $v['discount_total'] : 0).'" type="hidden"/>';
?>
</span>
<br>
<span title="" class="ng-binding"><b class="ng-binding"></b></span>
                                 
	</div></div>
                            
                            
                            <div class="infobox-icon ps t15 r5	">
                                <i class="fa fa-gift icon-only orange-2 bigger-280" title=""></i>
                            </div>
                        </div>
</div>


<div class="mcol-md-20 mcol-xs-50">
	<div class="col-xs-12 alert alert-warning" style="height: 86px">
	<div class="col-xs-9" > <div class="row">
<span title="Cước phí vận chuyển" class="ng-binding">Phí ship:   
<b class="ng-binding green bill-label-ship-total"><?php echo getCurrencyText(isset($v['ship_total']) ? $v['ship_total'] : 0,$v['currency'],['show_symbol'=>true]);?></b>
<?php 
echo '<input name="f[ship_total]" class="bill-input-ship-total" value="'.(isset($v['ship_total']) ? $v['ship_total'] : 0).'" type="hidden"/>';
?>
</span>
                               
                                
	</div></div>
                            
                            
                            <div class="infobox-icon ps t15 r5	">
                                <i class="fa fa-truck icon-only orange-2 bigger-280" title=""></i>
                            </div>
                        </div>
</div>

<div class="mcol-md-20 mcol-xs-50">
	<div class="col-xs-12 alert alert-warning"  style="height: 86px">
	<div class="col-xs-9" > <div class="row">
                                <span title="Tổng số tiền khách phải thanh toán" class="ng-binding">Tổng tiền: 
                                <b class="ng-binding bill-label-grand-total red"><?php echo getCurrencyText(isset($v['grand_total']) ? $v['grand_total'] : 0 ,$v['currency'],['show_symbol'=>true]);?></b>
<?php 
echo '<input name="f[grand_total]" class="bill-input-grand-total" value="'.(isset($v['grand_total']) ? $v['grand_total'] : 0).'" type="hidden"/>';
?>
                                </span>
                                <br>
                                <span title="" class="ng-binding"><b class="ng-binding"></b></span>
                                 
	</div></div>
                            
                            
                            <div class="infobox-icon ps t15 r5	">
                                <i class="fa fa-money icon-only orange-2 bigger-280" title=""></i>
                            </div>
                        </div>
</div>


<div class="mcol-md-20 mcol-xs-50">
	<div class="col-xs-12 alert alert-warning"  style="height: 86px">
	<div class="col-xs-9" > <div class="row">
                                <span title="Số tiền khách thanh toán" class="ng-binding">Thanh toán: 
<b class="ng-binding bill-label-guest-total"><?php echo getCurrencyText(isset($v['guest_pay']) ? $v['guest_pay'] : 0,$v['currency'],['show_symbol'=>true]);?></b>
<?php 
echo '<input name="f[guest_pay]" class="bill-input-guest-total" value="'.(isset($v['guest_pay']) ? $v['guest_pay'] : 0).'" type="hidden"/>';
?>
</span>
                                <br>
<span title="" class="ng-binding">Còn lại: <b class="ng-binding bill-label-total-owed"><?php echo getCurrencyText($v['owed_total'],$v['currency'],['show_symbol'=>true]);?></b>
<?php 
echo '<input name="f[owed_total]" class="bill-input-total-owed" value="'.(isset($v['owed_total']) ? $v['owed_total'] : 0).'" type="hidden"/>';
?>
</span>
<?php 
//$v['excess_cash']; 
if(isset($v['bill']['guest_cash']) && !empty($v['bill']['guest_cash'])){
	foreach ($v['bill']['guest_cash'] as $key=>$value){
		echo '<input class="bill-input-guest-cash bill-input-guest-cash-'.$key.'" type="hidden" value="'.$value.'" name="biz[guest_cash]['.$key.']"/>';		
	}
}
?>                                 
</div></div>
                            
                            
                            <div class="infobox-icon ps t15 r5	">
                                <i class="fa fa-cc-visa icon-only orange-2 bigger-280" title=""></i>
                            </div>
                        </div>
</div>

</div>
                     
                     
                     
                     

                    <!-- END DATA INFORMATION-->
                    <div class="row">
                        <div class="col-xs-12 barcode" >
                            <div class="form-group">
                                <div class="ng-scope col-sm-12">
                                     
<input
<?php 
if($status > 4 ){
	echo 'disabled=""';
}
?>
class="form-control pos-quick-create-input-text autocomplete2"
data-action="Au_LoadProduct"
placeholder="Nhập tên, mã sản phẩm hoặc mã vạch">                                     
                                </div>
                            </div>

                        </div>
                        <div class="col-xs-12">
                            <div class="table-responsive">
<table id="sample-table-2" class="table table-striped table-bordered table-hover table-list-item">
                                    <thead>
                                        <tr>
                                            <th class="hidden-640 center ng-binding">STT</th>
                                            <th class="hidden-640 ng-binding">Mã hàng hóa</th>
                                            <th class="ng-binding">Tên hàng hóa</th>
                                            <th class="text-center ">
                                                <span class="show-640 ng-binding">SL</span> 
                                            </th>
                                            <th class="hidden-480 text-center ng-binding">Giá bán <?php echo ' ('.Yii::$app->currency['symbol'].')';?></th>
                                            <th class="text-center hidden-320 ng-binding">Thành tiền <?php echo ' ('.Yii::$app->currency['symbol'].')';?></th>
                                            <th class="text-center" >
                                                
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="ng-scope">
<?php 
if(isset($v['bill']['listItem']) && !empty($v['bill']['listItem'])){
	$i = 0;
	foreach ($v['bill']['listItem'] as $k1=>$v1){
		echo '<tr class="bill-item-'.$v1['id'].'">';
		echo '<td class="hidden-640 text-center ng-binding">'.(++$i);
		echo '<input type="hidden" name="biz[bill][listItem]['.$v1['id'].'][code]" value="'.$v1['code'].'" />';
		echo '<input type="hidden" name="biz[bill][listItem]['.$v1['id'].'][title]" value="'.$v1['title'].'" />';
		echo '<input type="hidden" name="biz[bill][listItem]['.$v1['id'].'][id]" value="'.$v1['id'].'" />';
		echo '<input type="hidden" name="biz[bill][listItem]['.$v1['id'].'][url]" value="'.(isset($v1['url']) ? $v1['url'] : '').'" />';
		
		echo '<input type="hidden" name="biz[bill][listItem]['.$v1['id'].'][url_link]" value="'.(isset($v1['url_link']) ? $v1['url_link'] : '').'" />'; 
		
		echo '<input type="hidden" name="biz[bill][listItem]['.$v1['id'].'][icon]" value="'.(isset($v1['icon']) ? $v1['icon'] : '').'" />';
		echo '<input type="hidden" name="biz[bill][listItem]['.$v1['id'].'][currency]" value="'.$v1['currency'].'" />';
	
		echo '<input type="hidden" name="biz[bill][listItem]['.$v1['id'].'][price1]" value="'.(isset($v1['price1']) ? $v1['price1'] : 0).'" />';
		if(isset($v1['listImages']) && !empty($v1['listImages'])){
			foreach ($v1['listImages'] as $ii=> $im1){
				foreach ($im1 as $key=>$value){
					echo '<input type="hidden" name="biz[bill][listItem]['.$v1['id'].'][listImages]['.$ii.']['.$key.']" value="'.$value.'" />';
				}
			}
		}
		
		
		echo '</td>';
		
		echo '<td class="hidden-640">
                                                <span class="ng-binding ng-scope">
                                                    #'.$v1['code'].'                        
                                                </span> 
                                            </td>';
		echo '<td>
<div class="ng-binding ng-scope">'.uh($v1['title']).'</div></td>';

		echo '<td class="text-center w150p" >
                                                <div class="ace-spinner touch-spinner" >
                                                     
                                                    <div class="input-group input-group-sm" >
'.($status > 4  ? '' : '<span class="input-group-btn">
        <button data-item_id="'.$v1['id'].'" onclick="izi.Bill_update_quantity(this);" data-role="desc" class="btn btn-default" type="button">-</button>
        
</span>').' 
 



<input
'.($status > 4  ? 'readonly' : '').'
name="biz[bill][listItem]['.$v1['id'].'][quantity]"
data-role="set"
data-item_id="'.$v1['id'].'"
value="'.$v1['quantity'].'"
type="text" data-min="1" 
onblur="izi.Bill_update_quantity(this);"
data-old="'.$v1['quantity'].'"
class="numeric spinner-input form-control mousetrap ng-pristine ng-valid center bill-input-item-quantity bill-input-item-quantity-'.$v1['id'].'" >
'.($status > 4  ? '' : '<span class="input-group-btn">        
<button data-item_id="'.$v1['id'].'" onclick="izi.Bill_update_quantity(this);" data-role="asc" class="btn btn-default" type="button">+</button>
</span>').'

                                                    </div>
                                                </div>
                                                 
                                            </td>';
		echo '<td class="hidden-480 text-right bold">
		<div class="ng-binding">
<input type="hidden" name="biz[bill][listItem]['.$v1['id'].'][discount_total]" value="'.(isset($v1['discount_total']) ? $v1['discount_total'] : 0).'"/>
<input type="hidden" name="biz[bill][listItem]['.$v1['id'].'][discount][value]" value="'.(isset($v1['discount']['value']) ? $v1['discount']['value'] : 0).'"/>
<input type="hidden" name="biz[bill][listItem]['.$v1['id'].'][discount][type]" value="'.(isset($v1['discount']['type']) ? $v1['discount']['type'] : '%').'"/>
'.($status > 4  ? '' : '<button type="button" data-toggle="tooltip" data-placement="top" title="Thiết lập giảm giá" class="btn mgr3 btn-xs btn-info attr-row ng-scope">
<i class="fa fa-gift "></i>
</button>').'

		<b>'.getCurrencyText($v1['price2'],$v1['currency'],['show_symbol'=>false]).'</b>
<input type="hidden" name="biz[bill][listItem]['.$v1['id'].'][price2]"
data-decimal="'.Yii::$app->zii->showCurrency($v1['currency'],3).'"
class="bill-input-item-price bill-input-item-price-'.$v1['id'].'" value="'.$v1['price2'].'"/>
		
		
		</div>
		
		</td>';
		echo '<td class="text-right hidden-320 ng-binding bold">
<b class="bill-label-item-sub-total bill-label-item-sub-total-'.$v1['id'].'">'.getCurrencyText($v1['sub_total'],$v1['currency'],['show_symbol'=>false]).'</b>
<input type="hidden" name="biz[bill][listItem]['.$v1['id'].'][sub_total]"
data-decimal="'.Yii::$app->zii->showCurrency($v1['currency'],3).'"
class="bill-input-item-sub-total bill-input-item-sub-total-'.$v1['id'].'" value="'.$v1['sub_total'].'"/>
</td>
<td class="text-center" >
'.($status > 4  ? '' :'
<i data-item_id="'.$v1['id'].'" onclick="izi.Bill_remove_item(this);" class="fa fa-trash f16pxi hover-red pointer bigger-120"></i>').'                                                
</td>';
		echo '</tr>';
	}
}
?>                                    
 
                                       
                                    </tbody> 
                                </table>

                            </div>
                            
                        </div>
                    </div>
                </div>
<?php 
include_once __DIR__ . '/epage/right.php';
?>                
                <div class="col-sm-12">
                    <div class="text-right toolbar2">
                        <?php 
include 'epage/btn-list.php';
?> 
                    </div>
                </div>
            </div>
</div>
</div>
</form>

 