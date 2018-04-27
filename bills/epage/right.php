<div class="col-xs-12 col-sm-4 form-horizontal">
                    <div class="widget-box transparent" style="margin-top: 0px !important;">
                        <div class="tabbable">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a data-toggle="tab" >
                                        <h4 class="lighter no-margin text-info">
                                            <i class="fa fa-info-circle"></i>
                                            <span class="hidden-320 ng-binding">TT đơn hàng</span>
                                        </h4>
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="tab" >
                                    <i class="fa fa-history"></i>
                                        <span class="lighter orange-2"><b class="ng-binding">Lịch sử</b> </span>
                                    </a>
                                </li>
                            </ul>


                            <div class="tab-content no-border black padding-0 ">
                                <!-- region customer-->
                                <div id="info" class="tab-pane in active pdt15">
                                    <div class="widget-body">
                                        <div class="widget-main padding-4">
                                            <div class="tab-content padding-8 overflow-visible">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label no-padding-right ng-binding aleft stt-<?php echo $status;?>">Ngày tạo</label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group">
<?php 
echo '<input '.($status > 4  ? 'readonly=""' : '').' name="f[created_at]" type="text" data-format="d/m/Y H:i" data-mask="39/29/3999" data-time="1" 
class="form-control datetimepicker2 stt-'.$status.'" 
placeholder="Ngày hôm nay" value="'.(!empty($v) ? date("d/m/Y H:i", $v['created_at']) : '').'"/>';
?>                    
                    <span class="input-group-addon">
                        <span onclick="showCalendar(this);" class="pointer fa fa-calendar"></span>
                    </span>
                </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label no-padding-right ng-binding aleft" for="form-field-1">Mã phiếu</label>
                                                    <div class="col-sm-8 form-value">
<?php echo '<input disabled="disabled" readonly="readonly" type="text" class="form-control" placeholder="Hệ thống tự tạo" value="'.(!empty($v) ? $v['id'] : '').'"/>';?>                                                    
 
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label no-padding-right ng-binding aleft" for="form-field-1">Người bán</label>
                                                    <label class="col-sm-8 control-label no-padding-right ng-binding aleft" > 
 
<?php echo isset($v['staff_name']) ? $v['staff_name'] : '';?>
 
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
<textarea name="biz[note]" rows="3" class="col-xs-12 ng-pristine ng-valid form-control" id="form-field-10"  placeholder="Ghi chú tại đây">
<?php echo isset($v['note']) ? $v['note'] : '';?>
</textarea>
                                                        
                                                    </div>
                                                </div>
                                                <div class="form-group hide" data-ng-show="orderStatus > 1">
                                                    <label class="col-sm-5 control-label no-padding-right"></label>
                                                    <div class="col-sm-7 text-right padding-right">
                                                        <button type="button" id="btnNewComment" class="btn btn-primary btn-sm ng-binding" >
                                                            <i class="icon-pen"></i>&nbsp;Thêm ghi chú
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
 
                            </div>
                        </div>
                    </div>


                    <div class="widget-box transparent xs" style="margin-top: 0px !important;">
                        <div class="widget-header">
                            <h4 class="lighter ng-binding">
                                <i class="fa fa-user"></i>
                                Khách hàng
                            </h4>
                        </div>
                        <div class="widget-body pdt15">
                            <div class="widget-main padding-4">
                                <div class="tab-content padding-8 overflow-visible">
<div class="form-group">
<label class="col-sm-4 aleft control-label no-padding-right ng-binding" for="form-field-1">
Họ và tên
<b class="label ng-binding ng-pristine ng-valid ng-hide" style="float:right" 
></b>
</label>
<?php 
echo '<input class="bill-input-customer" data-field="id" type="hidden" name="biz[bill][customer][id]" value="'.(isset($v['bill']['customer']['id']) ? $v['bill']['customer']['id']: 0).'"/>';
echo '<input class="bill-input-customer" data-field="local_id" type="hidden" name="biz[bill][customer][local_id]" value="'.(isset($v['bill']['customer']['local_id']) ? $v['bill']['customer']['local_id']: 0).'"/>';
?>
                                        <div class="col-sm-8">
                                            <div class="input-group">
<input
data-action="Au_load_customer"
name="biz[bill][customer][name]" type="text" 
data-field="name"
data-type_id="<?php echo TYPE_ID_CUSTOMER;?>"
class="form-control autocomplete_customer bill-input-customer"
placeholder="Tìm kiếm khách hàng" 
value="<?php echo isset($v['bill']['customer']['name']) ? $v['bill']['customer']['name']: '';?>">
<span class="input-group-btn">
<button class="btn btn-info" type="button"
data-target2="bill-quick-add-customer"
onclick="call_ajax_function(this);" data-action="open-form-quick-add-customer" data-type_id="<?php echo TYPE_ID_CUSTOMER;?>"
><i class="fa fa-plus"></i></button>
</span>
    </div><!-- /input-group -->
                                        </div>


                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right aleft" for="form-field-1">Email</label>
                                        <div class="col-sm-8">
<input name="biz[bill][customer][email]" type="text" 
data-field="email"
class="bill-input-customer width-100 ng-pristine ng-valid form-control" value="<?php echo isset($v['bill']['customer']['email']) ? $v['bill']['customer']['email']: '';?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right aleft" for="form-field-1">Điện thoại</label>
                                        <div class="col-sm-8">
<input name="biz[bill][customer][phone]" type="text" data-field="phone"
class="bill-input-customer width-100 ng-pristine ng-valid form-control" value="<?php echo isset($v['bill']['customer']['phone']) ? $v['bill']['customer']['phone']: '';?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right aleft" for="form-field-1">Địa chỉ</label>
                                        <div class="col-sm-8">
<textarea name="biz[bill][customer][address]" data-field="address"
class="bill-input-customer width-100 ng-pristine ng-valid form-control" ><?php echo isset($v['bill']['customer']['address']) ? $v['bill']['customer']['address']: '';?></textarea>
                                        </div>
                                    </div>
                                     
                                </div>
                            </div>
                            <!-- /widget-main -->
                        </div>
                        <!-- /widget-body -->
                    </div>
<?php 
echo '<input class="bill-input-ship" data-field="id" type="hidden" name="biz[bill][ship][id]" value="'.(isset($v['bill']['ship']['id']) ? $v['bill']['ship']['id']: 0).'"/>';
echo '<input class="bill-input-ship" data-field="local_id" type="hidden" name="biz[bill][ship][local_id]" value="'.(isset($v['bill']['ship']['local_id']) ? $v['bill']['ship']['local_id']: 0).'"/>';
?>
                    <div class="widget-box transparent xs" style="margin-top: 0px !important;">
                        <div class="widget-header">
                            <h4 class="lighter ng-binding">
                                <i class="fa fa-truck"></i>
                                Giao hàng
                            </h4>
                        </div>
                        <div class="widget-body pdt15">
                            <div class="widget-main padding-4">
                                <div class="tab-content padding-8 overflow-visible">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label aleft no-padding-right ng-binding" for="form-field-1">Vận chuyển</label>
                                        <div class="col-sm-8">
                                             
<div class="input-group">
<input
data-action="Au_load_customer"
name="biz[bill][ship][name]" type="text" 
data-field="name"
data-target2="bill-auto-select-ship"
data-type_id="<?php echo TYPE_ID_SHIPER;?>" 
class="form-control autocomplete_customer bill-input-ship"
placeholder="Tìm kiếm đv giao hàng" 
value="<?php echo isset($v['bill']['ship']['name']) ? $v['bill']['ship']['name']: '';?>">
<span class="input-group-btn">
<button class="btn btn-info" type="button"
data-target2="bill-quick-add-ship"
onclick="call_ajax_function(this);" data-action="open-form-quick-add-customer" 
data-type_id="<?php echo TYPE_ID_SHIPER;?>"
><i class="fa fa-plus"></i></button>
</span>
    </div>                                            

 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 aleft control-label no-padding-right ng-binding">Ngày giao</label>
                                        <div class="col-sm-8">
                                                        <div class='input-group ' >
<input name="biz[bill][ship][date]" value="<?php echo isset($v['bill']['ship']['date']) ? $v['bill']['ship']['date']: '';?>" type='text' class="form-control datetimepicker2" />
                    <span class="input-group-addon">
                        <span onclick="showCalendar(this);" class="pointer fa fa-calendar"></span>
                    </span>
                </div>
                                                    </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 aleft control-label no-padding-right ng-binding">Người giao</label>
                                        <div class="col-sm-8">
                                             
<input name="biz[bill][ship][staff_name]" value="<?php echo isset($v['bill']['ship']['staff_name']) ? $v['bill']['ship']['staff_name']: '';?>" class="width-100 ng-pristine ng-valid form-control" type="text" >
                                                
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-12">
<textarea rows="3" name="biz[bill][ship][note]" class="col-xs-12 ng-pristine ng-valid form-control" id="form-field-10" placeholder="Ghi chú tại đây" maxlength="1900" ><?php echo isset($v['bill']['ship']['note']) ? $v['bill']['ship']['note']: '';?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /widget-main -->
                        </div>
                        <!-- /widget-body -->
                    </div>
                </div>